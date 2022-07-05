@php
/*
 * 配送について画面
 *
 * ※functionId, screenNameはmember/staticPage.blade.phpに設定
 *   固定ページのcontentに表示する内容を記述
 */
@endphp

<div id="shipping">
  <section class="sec01">
    <h2>ABOUT SHIPPING</h2>
    <p class="jp">配送について</p>

    <div class="shipping-box">
      <h3>送料について</h3>
      <p>一回のご注文で、10,000円(税込)以上ご購入いただくと日本全国送料無料でお届けします。</p>
      <p>※10,000円（税込）以下の場合の送料は次のとおりです。</p>
      <table cellpadding="0" cellspacing="0" class="buyer">
          <tbody>
              <tr>
                  <th colspan="3">配送料金</th>
              </tr>
              <tr>
                  <th>南九州</th>
                  <td>熊本、宮﨑、鹿児島</td>
                  <td>600円</td>
              </tr>
              <tr>
                  <th>北九州</th>
                  <td>福岡、佐賀、大分、<br>長崎</td>
                  <td>600円</td>
              </tr>
              <tr>
                  <th>四国</th>
                  <td>徳島、香川、高知、<br>愛媛</td>
                  <td>700円</td>
              </tr>
              <tr>
                  <th>中国</th>
                  <td>岡山、広島、鳥取、<br>島根、山口</td>
                  <td>600円</td>
              </tr>
              <tr>
                  <th>関西</th>
                  <td>京都、滋賀、奈良、<br>大阪、兵庫、和歌山</td>
                  <td>700円</td>
              </tr>
              <tr>
                  <th>北陸</th>
                  <td>富山、石川、福井</td>
                  <td>800円</td>
              </tr>
              <tr>
                  <th>東海</th>
                  <td>静岡、愛知、岐阜、<br>三重</td>
                  <td>800円</td>
              </tr>
              <tr>
                  <th>信越</th>
                  <td>長野、新潟</td>
                  <td>950円</td>
              </tr>
              <tr>
                  <th>関東</th>
                  <td>東京、神奈川、千葉、<br>埼玉、茨木、群馬、<br>山梨、栃木</td>
                  <td>950円</td>
              </tr>
              <tr>
                  <th>南東北</th>
                  <td>宮城、山形、福島</td>
                  <td>1,000円</td>
              </tr>
              <tr>
                  <th>北東北</th>
                  <td>青森、秋田、岩手</td>
                  <td>1,000円</td>
              </tr>
              <tr>
                  <th>北海道</th>
                  <td>北海道</td>
                  <td>1,000円</td>
              </tr>
              <tr>
                  <th>沖縄</th>
                  <td>沖縄※航空便発送</td>
                  <td>1,800円</td>
              </tr>
          </tbody>
      </table>
      <!-- <p>北海道：利尻郡・礼文郡、東京都：大島・八丈島以外の伊豆諸島・小笠原諸島、島根県：隠岐郡、長崎県：対馬市、鹿児島県：奄美市・大島郡・鹿児島郡、沖縄県：八重山郡・島尻郡のうち(北大東村・南大東村)</p> -->
    </div>
    <div class="shipping-box">
      <h3>お届けについて</h3>
      <ul>
        <li>●午前10時59分までにご注文いただいた場合、最短、ご注文当日に出荷いたします。<br>商品により当日出荷できない場合もございます。</li>
        <li>●同一お届け先に2種類以上の商品をお届けの場合、それぞれ個別のお届けとなる場合がございます。</li>
        <li>●送料は、お届け先毎に頂戴いたします。</li>
        <li>●一度のご注文に、異なる送料区分の商品が混在している場合は、最も高い送料1点分を頂戴いたします。</li>
        <li>※交通事情などにより、お届け時間が前後する場合がございます。</li>
        <li>※離島など一部地域で配送が遅くなる場合がございます。</li>
        <li>※商品の一部は、北海道、沖縄、離島へのお届けについて、航空便でお届けできない場合がございます。また、日時指定を頂いてもご指定よりお日にちをいただく場合がございます。</li>
      </ul>
    </div>
    <div class="shipping-box">
      <h3>海外販売について</h3>
      <p>当店は海外へ発送を行っていません。</p>
    </div>
  </section>
</div>