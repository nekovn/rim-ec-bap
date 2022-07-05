<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Services\Admin\GoodsImagesService;

/**
 * 商品画像マスタメンテコントローラー
 *
 * @category  商品画像管理
 * @package   App\Http\Controllers\Admin
 * @version   1.0
 */
class GoodsImagesController extends Controller
{
    /**
     * @var GoodsImagesService
     */
    private $service;

    /**
     * コンストラクタ
     *
     * @access public
     * @param GoodsImagesService $goodsImagesService
     */
    public function __construct(GoodsImagesService $goodsImagesService)
    {
        $this->service = $goodsImagesService;
    }

    /**
     * 商品画像登録ページを表示する。
     *
     * @param $goods_id
     * @return Application|Factory|View
     */
    public function index($goods_id)
    {
        return view('admin.goodsImages')->with([
            'goods_id' => $goods_id
        ]);
    }

    /**
     * 商品ID を指定して 商品画像 を 表示順(昇順) で取得する。
     *
     * @param $goods_id
     * @return JsonResponse
     */
    public function findByGoodsId($goods_id): JsonResponse
    {
        $result = $this->service->findByGoodsId($goods_id);

        // DBに保存してあるファイル名を取り出す
        $entryFiles = array();
        foreach ($result['data'] as $row) {
            $pathArray = explode('/', $row['image']);
            $entryFile = end($pathArray);
            array_push($entryFiles, $entryFile);
        }

        // 物理的に存在するファイル名を取り出す
        $files = array();
        $directory = $this->directory($goods_id);
        $filePaths = Storage::disk(config('app.goods_image_filesystem_driver'))->files($directory);
        foreach ($filePaths as $filePath) {
            $pathArray = explode('/', $filePath);
            $file = end($pathArray);
            array_push($files, $file);
        }

        // 不要なファイルを削除する
        $diffFiles = array_diff($files, $entryFiles);
        foreach ($diffFiles as $diffFile) {
            Storage::disk(config('app.goods_image_filesystem_driver'))->delete($directory . '/' . $diffFile);
        }

        return response()->json($result);
    }

    /**
     * 商品ID を指定して 商品画像 を登録する。
     *
     * @param Request $request
     * @param $goods_id
     * @return JsonResponse
     */
    public function entryByGoodsId(Request $request, $goods_id): JsonResponse
    {
        // 商品画像を登録する
        $all_request = $this->uploadImages($request, $goods_id);
        $this->service->entryByGoodsId($all_request, $goods_id);

        // 最新の状態を返す
        $result = $this->service->findByGoodsId($goods_id);
        return response()->json($result);
    }

    /**
     * 商品ID を指定して 商品画像ファイル を登録する。
     *
     * @param Request $request
     * @param $goods_id
     * @return array
     */
    protected function uploadImages(Request $request, $goods_id): array
    {
        // 商品画像ファイルを配置するディレクトリ
        $directory = $this->directory($goods_id);

        $all_request = $request->all();

        $maxId = (int)$all_request['maxId'];
        for ($id = 1; $id <= $maxId; $id++) {
            $image = 'image_' . $id;
            $upload_image = 'upload_image_' . $id;

            if($request->hasFile($upload_image)) {
                $thumbnail = 'thumbnail_' . uniqid();
                $extension = $request[$upload_image]->getClientOriginalExtension();
                $filename = $thumbnail . '.' . $extension;
                if (config('app.goods_image_filesystem_driver') === 'local') {
                    $path = $request[$upload_image]->storeAs($directory, $filename);
                    $path = str_replace('public/', '', $path);
                } else {
                    $path = $request[$upload_image]->storeAs($directory, $filename, ['disk' => 's3', 'visibility' => 'public']);
                    $path = config('filesystems.disks.s3.bucket') . '/' . $path;
                }
                $all_request[$image] = $path;
            }
        }

        return $all_request;
    }

    /**
     * 商品画像ファイルを配置するディレクトリ
     *
     * @param $goods_id
     * @return string
     */
    protected function directory($goods_id): string
    {
        if (config('app.goods_image_filesystem_driver') === 'local') {
            $directory = 'public/images/goods/' . $goods_id . '/thumbnail';
        } else {
            $directory = $goods_id . '/thumbnail';
        }
        return $directory;
    }

    /**
     * 戻る
     *
     * @access public
     */
    public function back(Request $request)
    {
        return $this->backRedirect($request, GoodsController::$SESSION_KEY, 'goods.index');
    }
}
