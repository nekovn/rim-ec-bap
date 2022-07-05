<?php
namespace App\Services\Admin;

use App\Aspect\Annotation\Transactional;
use App\Enums\SagawaOutputDefine;
use App\Repositories\GoodsRepository;
use App\Repositories\ShipsRepository;
use Lang;

/**
 * 佐川連携ファイル出力サービスクラス
 *
 * @category
 * @package   App\Services
 * @version   1.0
 */
class SagawaOutputService
{
    /** CSV出力ディレクトリ */
    private static $outputDir = "sagawa";

    /** 出力対象 */
    private $outputTarget = [];

    /**
     * コンストラクタ
     *
     * @access public
     * @param GoodsRepository $goodsRepository 商品リポジトリ
     * @param ShipsRepository $shipsRepository 出荷リポジトリ
     */
    public function __construct(GoodsRepository $goodsRepository, ShipsRepository $shipsRepository) {
        $this->goodsRepository = $goodsRepository;
        $this->shipsRepository = $shipsRepository;

        $this->outputTarget = [];
    }

    /**
     * 商品マスタの出力を行う
     *
     * @access public
     * @return array レスポンス
     */
    public function downloadGoodsCsv()
    {
        $data = $this->goodsRepository->getGoodsMasterForSagawa();
        $header = SagawaOutputDefine::OUTPUT_GOODS_HEADER;
        $outputLimit = SagawaOutputDefine::OUTPUT_GOODS_LIMIT;
        $headerFlg = SagawaOutputDefine::OUTPUT_GOODS_HEADER_FLAG;
        $fileName = SagawaOutputDefine::OUTPUT_GOODS_FILENAME;

        return $this->downloadCsvFile($data, $header, $outputLimit, $headerFlg, $fileName);
    }

    /**
     * 出荷指示の出力を行う
     *
     * @access public
     * @return array レスポンス
     * @Transactional()
     */
    public function downloadShipsCsv()
    {
        $data = $this->shipsRepository->getShipsInstructionForSagawa();
        $header = SagawaOutputDefine::OUTPUT_SHIPS_HEADER;
        $outputLimit = SagawaOutputDefine::OUTPUT_SHIPS_LIMIT;
        $headerFlg = SagawaOutputDefine::OUTPUT_SHIPS_HEADER_FLAG;
        $fileName = SagawaOutputDefine::OUTPUT_SHIPS_FILENAME;

        foreach ($data as $row) {
            if (!in_array($row->mall_accept_number, $this->outputTarget)) {
                array_push($this->outputTarget, $row->mall_accept_number);
            }
        }

        $response = $this->downloadCsvFile($data, $header, $outputLimit, $headerFlg, $fileName);
        if (!is_null($response) && count($this->outputTarget) > 0) {
            // 出荷テーブル更新
            $this->shipsRepository->updateShipsInstructionForSagawa($this->outputTarget);
        }

        return $response;
    }

    /**
     * CSVファイルのダウンロードを行う
     *
     * @access private
     * @param array $data 出力データ
     * @param array $header 出力ヘッダ
     * @param array $outputLimit 出力データの出力制限
     * @param boolean $headerFlg ヘッダ出力フラグ（ false: 出力しない true: 出力する ）
     * @param string $fileName ファイル名
     * @return array レスポンス
     */
    private function downloadCsvFile($data, $header, $outputLimit, $headerFlg, $fileName)
    {
        // 対象データが０件の場合
        $data = json_decode(json_encode($data), true);
        if(count($data) == 0) {
            return null;
        }

        // ファイル名を作成する
        if (substr_count($fileName,'@') > 0) {
            // @xxx@の記述がある場合は置き換える
            // @customerid@
            $fileName = str_replace('@customerid@', SagawaOutputDefine::OUTPUT_VALUE_CUSTOMER_ID, $fileName);

            // @timestamp@ ⇒ 日時(yyyymmddhhiiss形式)に置き換える
            $timestamp = date('YmdHis');
            $fileName = str_replace('@timestamp@', $timestamp, $fileName);
        }

        // 作業用ディレクトリ
        $wk_dir = storage_path().'/app/downloads/'.SagawaOutputService::$outputDir.'/';
        // 作業用ディレクトリを初期化（全削除）
        array_map('unlink', glob($wk_dir.'*.*'));
        // 作業用ディレクトリがない場合は作成する
        if (!file_exists($wk_dir)) {
            mkdir($wk_dir,0777);
        }
        // ワークファイル
        $wk_file = $wk_dir . $fileName;
        //出力エンコード
        $encode = SagawaOutputDefine::ENCODE;

        return response()->streamDownload(function () use ($data, $header, $outputLimit, $headerFlg, $encode, $wk_file) {
            echo $this->outputCsv($data, $header, $outputLimit, $headerFlg, $encode, $wk_file);
        }, $fileName);
    }
    /**
     * CSV作成
     *
     * @access private
     * @param array $data 出力データ
     * @param array $header 出力ヘッダ
     * @param array $outputLimit 出力データの出力制限
     * @param boolean $headerFlg ヘッダ出力フラグ（ false: 出力しない true: 出力する ）
     * @param string $encode 文字エンコード
     * @param string $fileName ファイル名
     */
    private function outputCsv($data, $header, $outputLimit, $headerFlg, $encode, $fileName)
    {
        $csv = null;
        $stream = tmpfile();
        if ($stream) {
            // ヘッダの書き込み
            if ($headerFlg) {
                $str = $this->convertItemGroupingDelimiter($header);
                fwrite($stream, $str);
            }

            // データの書き込み
            foreach ($data as $rowData) {
                // 出力制限
                foreach($rowData as $key => $value) {
                    if (isset($outputLimit[$key])) {
                        $str = $value;
                        $limit = $outputLimit[$key];
                        // 機種依存文字変換
                        if ($limit['conver_flg']) {
                            $str = $this->convertPlatformDependentChar($str);
                        }

                        // 文字切り出し
                        $str = mb_substr($str, $limit['cutting_start_posi'], $limit['length']);

                        $rowData[$key] = $str;
                    }
                }

                // 出力
                $str = $this->convertItemGroupingDelimiter($rowData);
                fwrite($stream, $str);
            }
            rewind($stream);
            $csv = preg_replace("/\r\n|\r|\n/", "\r\n", stream_get_contents($stream));
            $csv = mb_convert_encoding($csv, $encode, 'auto');

            $resource_id = fopen($fileName, 'w');
            fwrite($resource_id, $csv);
            // CSVファイルを閉じる
            fclose($stream);
            fclose($resource_id);
        }

        return $csv;
    }

    /**
     * 配列を項目括り、項目区切りにした文字に変換
     *
     * @access private
     * @param array $arr 文字列に変換したい配列
     * @return string 変換後文字列
     */
    private function convertItemGroupingDelimiter($arr)
    {
        // 項目括り---ダブルクォーテーション(")
        // 項目区切り---カンマ(,)
        return "\"" . implode("\",\"",$arr) . "\"\r\n";
    }

    /**
     * 機種依存文字を変換
     *
     * @access private
     * @param array $arr 文字列に変換したい配列
     * @return 変換後文字列
     */
    private function convertPlatformDependentChar($str)
    {
        // 機種依存文字の置き換え
        // windows 対象にした機種依存文字は下記サイトを参照
        //  - https://support.biglobe.ne.jp/settei/mailer/em-guide_win.html
        // mac 対象にした機種依存文字は下記サイトを参照(囲み英字、トランプ記号、固有記号は除く)
        //  - https://support.biglobe.ne.jp/settei/mailer/em-guide_mac.html
        $mapping = [
            // 固有漢字
            '纊' => '絋',
            '褜' => 'えな',
            '鍈' => 'えい',
            '銈' => 'けい',
            '蓜' => 'はい',
            '俉' => 'ご',
            '炻' => 'せつ',
            '昱' => 'いく',
            '棈' => 'せん',
            '鋹' => 'するどい',
            '曻' => '昇',
            '彅' => 'なぎ',
            '丨' => 'こん',
            '仡' => 'きつ',
            '仼' => 'きょう',
            '伀' => 'おおやけ',
            '伃' => 'よ',
            '伹' => 'つたない',
            '佖' => 'ひつ',
            '侒' => 'やすらか',
            '侊' => 'こう',
            '侚' => 'すみやか',
            '侔' => 'む',
            '俍' => 'さまよう',
            '偀' => '英',
            '倢' => 'さとい',
            '俿' => 'かたたがい',
            '倞' => 'もと',
            '偆' => 'あつい',
            '偰' => 'せつ',
            '偂' => 'すすむ',
            '傔' => 'したがう',
            '僴' => 'かん',
            '僘' => 'ひろい',
            '兊' => 'よろこぶ',
            '兤' => 'あかるい',
            '冝' => '宜',
            '冾' => 'やわらぐ',
            '凬' => '風',
            '刕' => 'さく',
            '劜' => 'あつ',
            '劦' => 'にわか',
            '勀' => 'かつ',
            '勛' => '勲',
            '匀' => '韻',
            '匇' => '匆',
            '匤' => 'はこ',
            '卲' => 'たかい',
            '厓' => 'がけ',
            '厲' => 'えやみ',
            '叝' => 'こう',
            '﨎' => 'そう',
            '咜' => '咤',
            '咊' => '和',
            '咩' => 'び',
            '哿' => 'よい',
            '喆' => '哲',
            '坙' => 'けい',
            '坥' => 'つつみ',
            '垬' => 'ぬかり',
            '埈' => 'そばだつ',
            '埇' => 'おきつち',
            '﨏' => 'えき',
            '塚' => '塚',
            '增' => '増',
            '墲' => 'ぶ',
            '夋' => 'おごりいる',
            '奓' => 'はる',
            '奛' => 'あきらか',
            '奝' => 'おおきい',
            '奣' => 'あきらか',
            '妤' => 'よ',
            '妺' => 'あざな',
            '孖' => 'ふたご',
            '寀' => 'つかさ',
            '甯' => 'ねい',
            '寘' => 'おく',
            '寬' => '寛',
            '尞' => 'かがりび',
            '岦' => 'りゅう',
            '岺' => 'れい',
            '峵' => 'こう',
            '崧' => 'そばだつ',
            '嵓' => 'けわ',
            '﨑' => '崎',
            '嵂' => 'けわしい',
            '嵭' => 'くずれる',
            '嶸' => 'けわしい',
            '嶹' => 'しま',
            '巐' => 'やまのさま',
            '弡' => 'つよくいさましい',
            '弴' => 'えゆみ',
            '彧' => 'あや',
            '德' => '徳',
            '忞' => 'つとめる',
            '恝' => 'かつ',
            '悅' => '悦',
            '悊' => '哲',
            '惞' => 'よろこぶ',
            '惕' => 'つつしむ',
            '愠' => '慍',
            '惲' => 'あつい',
            '愑' => 'みつる',
            '愷' => 'たのしむ',
            '愰' => 'さとい',
            '憘' => '喜',
            '戓' => 'か',
            '抦' => 'もつ',
            '揵' => 'あげる',
            '摠' => 'すべて',
            '撝' => 'はなす',
            '擎' => 'ささげる',
            '敎' => '教',
            '昀' => 'いん',
            '昕' => 'あさ',
            '昻' => '昂',
            '昉' => 'あきらか',
            '昮' => 'しょう',
            '昞' => 'あきらか',
            '昤' => 'れい',
            '晥' => 'あき',
            '晗' => 'かん',
            '晙' => 'あきらか',
            '晴' => '晴',
            '晳' => 'あき',
            '暙' => 'しゅん',
            '暠' => 'こう',
            '暲' => 'しょう',
            '暿' => '熹',
            '曺' => '曹',
            '朎' => 'れい',
            '朗' => '朗',
            '杦' => '杉',
            '枻' => 'かい',
            '桒' => '桑',
            '柀' => 'ひ',
            '栁' => '柳',
            '桄' => 'よこぎ',
            '棏' => 'とく',
            '﨓' => 'たぶ',
            '楨' => 'ねずみもち',
            '﨔' => '﨔',
            '榘' => 'じょうぎ',
            '槢' => 'くさび',
            '樰' => 'たら',
            '橫' => '横',
            '橆' => 'しげる',
            '橳' => 'ぬで',
            '橾' => 'しゅ',
            '櫢' => 'そう',
            '櫤' => 'たも',
            '毖' => 'つつしむ',
            '氿' => 'おずみ',
            '汜' => 'かわ',
            '沆' => 'ひろい',
            '汯' => 'こう',
            '泚' => 'きよい',
            '洄' => 'さかのぼる',
            '涇' => 'けい',
            '浯' => 'ご',
            '涖' => 'のぞむ',
            '涬' => 'けい',
            '淏' => 'こう',
            '淸' => '清',
            '淲' => 'ひゅう',
            '淼' => 'ひろい',
            '渹' => 'こう',
            '湜' => 'きよい',
            '渧' => 'てい',
            '渼' => 'び',
            '溿' => 'みずぎわ',
            '澈' => 'きよい',
            '澵' => 'しん',
            '濵' => '濱',
            '瀅' => 'おがわ',
            '瀇' => 'おう',
            '瀨' => '瀬',
            '炅' => 'あらわれ',
            '炫' => 'げん',
            '焏' => 'すみやか',
            '焄' => 'いぶす',
            '煜' => 'かがやく',
            '煆' => 'か',
            '煇' => 'ひかり',
            '凞' => '煕',
            '燁' => 'さかん',
            '燾' => 'てらす',
            '犱' => '執',
            '犾' => 'ぎん',
            '猤' => 'たけしい',
            '猪' => '猪',
            '獷' => 'あらい',
            '玽' => 'く',
            '珉' => 'びん',
            '珖' => 'たま',
            '珣' => 'たま',
            '珒' => 'たま',
            '琇' => 'しゅう',
            '珵' => 'てい',
            '琦' => '埼',
            '琪' => 'たま',
            '琩' => 'みみだま',
            '琮' => 'そう',
            '瑢' => 'よう',
            '璉' => 'うつわ',
            '璟' => 'えい',
            '甁' => '瓶',
            '畯' => 'しゅん',
            '皂' => 'さいかち',
            '皜' => 'こう',
            '皞' => 'こう',
            '皛' => 'あらわれ',
            '皦' => 'しろい',
            '益' => '益',
            '睆' => 'かん',
            '劯' => 'つよい',
            '砡' => 'そろえる',
            '硎' => 'とぎ',
            '硤' => 'こう',
            '硺' => 'うつ',
            '礰' => 'れき',
            '礼' => '礼',
            '神' => '神',
            '祥' => '祥',
            '禔' => 'さいわい',
            '福' => '福',
            '禛' => 'うける',
            '竑' => 'ひろい',
            '竧' => 'しん',
            '靖' => '靖',
            '竫' => 'やすらか',
            '箞' => 'たわめる',
            '精' => '精',
            '絈' => 'つつむ',
            '絜' => 'あさ',
            '綷' => 'さい',
            '綠' => '緑',
            '緖' => '緒',
            '繒' => 'そう',
            '罇' => 'そん',
            '羡' => '羨',
            '羽' => '羽',
            '茁' => 'めばえ',
            '荢' => 'う',
            '荿' => 'せい',
            '菇' => 'からすうり',
            '菶' => 'ほう',
            '葈' => 'し',
            '蒴' => 'さく',
            '蕓' => 'あぶらな',
            '蕙' => 'けい',
            '蕫' => 'とう',
            '﨟' => 'ろう',
            '薰' => '薫',
            '蘒' => 'はぎ',
            '﨡' => 'に',
            '蠇' => 'かき',
            '裵' => 'はい',
            '訒' => 'なやむ',
            '訷' => '伸',
            '詹' => 'せん',
            '誧' => 'ほ',
            '誾' => 'ぎん',
            '諟' => 'ただす',
            '諸' => '諸',
            '諶' => 'まこと',
            '譓' => 'かしこい',
            '譿' => 'けい',
            '賰' => 'とむ',
            '賴' => '頼',
            '贒' => '賢',
            '赶' => 'かん',
            '﨣' => 'きゅう',
            '軏' => 'くさび',
            '﨤' => 'そり',
            '逸' => '逸',
            '遧' => 'あきらか',
            '郞' => '郎',
            '都' => '都',
            '鄕' => '郷',
            '鄧' => 'とう',
            '釚' => 'いしゆみ',
            '釗' => 'けず',
            '釞' => 'するどい',
            '釭' => 'かも',
            '釮' => 'するどい',
            '釤' => 'おおがま',
            '釥' => 'よい',
            '鈆' => '鉛',
            '鈐' => 'けん',
            '鈊' => 'かね',
            '鈺' => 'たから',
            '鉀' => 'よろい',
            '鈼' => 'かま',
            '鉎' => 'さび',
            '鉙' => 'こがね',
            '鉑' => 'はく',
            '鈹' => 'かわ',
            '鉧' => 'けら',
            '銧' => 'こう',
            '鉷' => 'こう',
            '鉸' => 'はさみ',
            '鋧' => 'けん',
            '鋗' => 'こばち',
            '鋙' => 'くいちがう',
            '鋐' => 'うつわ',
            '﨧' => 'こう',
            '鋕' => 'きざむ',
            '鋠' => 'くろがね',
            '鋓' => 'するどい',
            '錥' => 'なべ',
            '錡' => 'かま',
            '鋻' => 'つるぎのは',
            '﨨' => 'こう',
            '錞' => 'しゅん',
            '鋿' => 'みがく',
            '錝' => 'そう',
            '錂' => 'りょう',
            '鍰' => 'からみ',
            '鍗' => 'かま',
            '鎤' => 'かねのね',
            '鏆' => 'うがつ',
            '鏞' => 'つりがね',
            '鏸' => 'するどい',
            '鐱' => 'すき',
            '鑅' => 'こう',
            '鑈' => 'じょう',
            '閒' => '間',
            '隆' => '隆',
            '﨩' => 'しま',
            '隝' => '島',
            '隯' => '陦',
            '霳' => 'りゅう',
            '霻' => 'ほう',
            '靃' => 'はおと',
            '靍' => '鶴',
            '靏' => '鶴',
            '靑' => '青',
            '靕' => 'しん',
            '顗' => 'うやうやしい',
            '顥' => 'しろい',
            '飯' => '飯',
            '飼' => '飼',
            '餧' => 'うえる',
            '館' => '館',
            '馞' => 'ほつ',
            '驎' => 'りん',
            '髙' => '高',
            '髜' => 'たかい',
            '魵' => 'えび',
            '魲' => '鱸',
            '鮏' => 'さけ',
            '鮱' => 'おおぼら',
            '鮻' => 'さめ',
            '鰀' => 'あめのうお',
            '鵰' => 'わし',
            '鵫' => 'やまどり',
            '鶴' => '鶴',
            '鸙' => 'ひばり',
            '黑' => '黒',
            // 囲み英数字
            '①' => '(1)',
            '②' => '(2)',
            '③' => '(3)',
            '④' => '(4)',
            '⑤' => '(5)',
            '⑥' => '(6)',
            '⑦' => '(7)',
            '⑧' => '(8)',
            '⑨' => '(9)',
            '⑩' => '(10)',
            '⑪' => '(11)',
            '⑫' => '(12)',
            '⑬' => '(13)',
            '⑭' => '(14)',
            '⑮' => '(15)',
            '⑯' => '(16)',
            '⑰' => '(17)',
            '⑱' => '(18)',
            '⑲' => '(19)',
            '⑳' => '(20)',
            '❶' => '(1)',
            '❷' => '(2)',
            '❸' => '(3)',
            '❹' => '(4)',
            '❺' => '(5)',
            '❻' => '(6)',
            '❼' => '(7)',
            '❽' => '(8)',
            '❾' => '(9)',
            '⑴' => '(1)',
            '⑵' => '(2)',
            '⑶' => '(3)',
            '⑷' => '(4)',
            '⑸' => '(5)',
            '⑹' => '(6)',
            '⑺' => '(7)',
            '⑻' => '(8)',
            '⑼' => '(9)',
            '⑽' => '(10)',
            '⑾' => '(11)',
            '⑿' => '(12)',
            '⒀' => '(13)',
            '⒁' => '(14)',
            '⒂' => '(15)',
            '⒃' => '(16)',
            '⒄' => '(17)',
            '⒅' => '(18)',
            '⒆' => '(19)',
            '⒇' => '(20)',
            // ローマ数字
            'Ⅰ' => 'I',
            'Ⅱ' => 'II',
            'Ⅲ' => 'III',
            'Ⅳ' => 'IV',
            'Ⅴ' => 'V',
            'Ⅵ' => 'VI',
            'Ⅶ' => 'VII',
            'Ⅷ' => 'VIII',
            'Ⅸ' => 'IX',
            'Ⅺ' => 'XI',
            'Ⅻ' => 'XII',
            'ⅰ' => 'i',
            'ⅱ' => 'ii',
            'ⅲ' => 'iii',
            'ⅳ' => 'iv',
            'ⅴ' => 'v',
            'ⅵ' => 'vi',
            'ⅶ' => 'vii',
            'ⅷ' => 'viii',
            'ⅸ' => 'ix',
            'ⅹ' => 'x',
            'ⅺ' => 'xi',
            'ⅻ' => 'xii',
            // 年号
            '㍾' => '明治',
            '㍽' => '大正',
            '㍼' => '昭和',
            '㍻' => '平成',
            '㍿' => '株式会社',
            'No.' => 'No.',
            '㏍' => 'K.K.',
            '℡' => 'TEL',
            // 囲み文字
            '㊤' => '(上)',
            '㊥' => '(中)',
            '㊦' => '(下)',
            '㊧' => '(左)',
            '㊨' => '(右)',
            '㊩' => '(医)',
            '㊖' => '(財)',
            '㊝' => '(優)',
            '㊘' => '(労)',
            '㊞' => '(印)',
            '㊙' => '(秘)',
            // 省略文字
            '㈰' => '(日)',
            '㈪' => '(月)',
            '㈫' => '(火)',
            '㈬' => '(水)',
            '㈭' => '(木)',
            '㈮' => '(金)',
            '㈯' => '(土)',
            '㈷' => '(祝)',
            '㉂' => '(自)',
            '㉃' => '(至)',
            '㈹' => '(代)',
            '㈺' => '(呼)',
            '㈱' => '(株)',
            '㈾' => '(資)',
            '㈴' => '(名)',
            '㈲' => '(有)',
            '㈻' => '(学)',
            '㈶' => '(財)',
            '㈳' => '(社)',
            '㈵' => '(特)',
            '㈼' => '(監)',
            '㈽' => '(企)',
            '㈿' => '(協)',
            '㈸' => '(労)',
            // 単位記号
            '㍉' => 'ミリ',
            '㌢' => 'センチ',
            '㍍' => 'メートル',
            '㌔' => 'キロ',
            '㌖' => 'キロメートル',
            '㌅' => 'インチ',
            '㌳' => 'フィート',
            '㍎' => 'ヤード',
            '㌃' => 'アール',
            '㌶' => 'ヘクタール',
            '㍑' => 'リットル',
            '㌘' => 'グラム',
            '㌕' => 'キログラム',
            '㌧' => 'トン',
            '㌣' => 'セント',
            '㌦' => 'ドル',
            '㍊' => 'ミリバール',
            '㌍' => 'カロリー',
            '㍂' => 'ホーン',
            '㌻' => 'ページ',
            '㍗' => 'ワット',
            '㌹' => 'ヘルツ',
            '㌫' => 'パーセント',
            '㎜' => 'mm',
            '㎝' => 'cm',
            '㎞' => 'km',
            '㎟' => '平方ミリメートル',
            '㎠' => '平方センチメートル',
            '㎡' => '平方メートル',
            '㎢' => '平方キロメートル',
            '㏄' => 'cc',
            '㎖' => 'ml',
            '㎗' => 'dl',
            'ℓ' => 'l',
            '㎘' => 'kl',
            '㎤' => '立方センチメートル',
            '㎥' => '立法メートル',
            '㎎' => 'mg',
            'ℊ' => 'g',
            '㎏' => 'kg',
            '㏔' => 'mb',
            '㏋' => 'HP',
            '㎐' => 'Hz',
            '℉' => '°F',
            '㎳' => 'ms',
            '㎲' => ' µs',
            '㎱' => 'ns',
            '㎰' => 'ps',
            '㎅' => 'KB',
            '㎆' => 'MB',
            '㎇' => 'GB',
            'TB' => 'TB',
            // 特殊文字
            'ゔ' => 'ヴ',
            'ヷ' => 'ヴァ',
            'ヸ' => 'ヴィ',
            'ヹ' => 'ヴェ',
            'ヺ' => 'ヴォ',
            // 数字記号
            '§' => '節記号',
            '∟' => '直角記号',
            '⊿' => '直角三角形',
            '≒' => 'ほぼ等しい',
            '≡' => '同値',
            '∫' => '積分記号',
            '∮' => '経路積分記号',
            'Σ' => 'シグマ',
            '√' => 'ルート',
            '⊥' => '垂直',
            '∠' => '角',
            '∵' => '何故ならば',
            '∩' => '和集合',
            '∪' => '共通部分',
        ];
        $search = array_keys($mapping);
        $replace = array_values($mapping);

        $result = str_replace($search, $replace, $str);
        $result = mb_convert_kana($result, "KV");

        return $result;
      }
}
