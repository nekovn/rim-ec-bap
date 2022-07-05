@php
$screenName = "ショップトップ";
$functionId = 'top';
@endphp
@extends('member.layouts.top')

@section('content')

<article id="top">
    <!--
    <div class="nav_snav">
        <div class="inner">
            <ul class="clearfix">
                <li><a href="#">NEWS</a></li>
                <li><a href="#">ヘアケア</a></li>
                <li><a href="#">スキンケア</a></li>
                <li><a href="#">メイクアップ</a></li>
                <li><a href="#">限定商品</a></li>
                <li><a href="#">ヘアケア</a></li>
                <li><a href="#">スキンケア</a></li>
                <li><a href="#">メイクアップ</a></li>
                <li><a href="#">限定商品</a></li>
            </ul>
        </div>
        <p class="arrow"><img src="{{ asset('images/top/btn_arrow_snav.png') }}" alt=""></p>
    </div>
    -->

    <div class="search_area">
        @include('member.layouts.header-search-form')
    </div>

    <div class="btn_area">
        <dl>
            <!--<dt><a href="/"><img src="{{ asset('images/top/btn_brand.svg') }}" alt="ブランドから探す"></a></dt>
            <dd>
                <a href="/list.html"><img src="{{ asset('images/top/btn_cate.svg') }}" alt="カテゴリーから探す"></a>
            </dd>-->
            <dt class="acNav-title js-acNav-title">
                <h2>CATEGORY</h2>
                <p class="jp">カテゴリーから探す</p>
            </dt>
            <dd class="acNav-content">
                @foreach ($categoryClass1 as $code => $categoryName)
                    <p><a href="{{ url('/goods?c=' . $code)}}">{{$categoryName}}</a></p>
                @endforeach
            </dd>
        </dl>
    </div>

    <div class="mv">
        <img src="{{asset('images/top/img_mv.jpg')}}" alt="">
    </div>

    <!--<section class="sec01">
        <h2>PICK UP</h2>
        <p class="jp">ピックアップ</p>
        <p class="img">
            <a href="#"><img src="/images/top/bnr_pickup.jpg') }}" alt="2022年春決定版。B-crewオンライン限定＆オススメコスメ！"></a>
        </p>
    </section>-->

    <section class="sec02">
        <h2 class="rank"><span>SHAMPOO</span></h2>
        <p class="jp">シャンプー</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/245"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/245/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <p class="name_en">MILBON(ミルボン)</p>
                    <p class="name_jp">グランドリンケージシルキーリュクスシャンプー<br>500ml</p>
                </div>
                <p class="price"><span>2,420</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/245';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/171"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/171/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no2.png') }}" alt="No.2"></p>
                    <p class="name_en">MILBON(ミルボン)</p>
                    <p class="name_jp">ジェミールフランヒートグロスシャンプーS<br>500ml</p>
                </div>
                <p class="price"><span>3,960</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/171';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/242"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/242/main.jpg" alt=""></a>
                    <div class="heart"></div>
                    <!--<div class="sample">
                        <a href="./"><img src="./img/top/icon_sample.png" alt="sample"></a>
                    </div>-->
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no3.png') }}" alt="No.3"></p>
                    <p class="name_en">MILBON(ミルボン)</p>
                    <p class="name_jp">グランドリンケージウィローリュクスシャンプー<br>500ml</p>
                </div>
                <p class="price"><span>2,420</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/242';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/11"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/11/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no4.png') }}" alt="No.4"></p>
                    <p class="name_en">N.(エヌドット)</p>
                    <p class="name_jp">N.  カラーシャンプー　Pu(パープル)<br>320ml</p>
                </div>
                <p class="price"><span>2,640</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/11';" value="詳細を見る"></p>
            </li>
        </ul>
        <!--<p class="btn_rank"><a href="./">ランキングから探す</a></p>-->
    </section>

    <section class="sec02">
        <h2 class="rank"><span>TREATMENT</span></h2>
        <p class="jp">トリートメント</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/143"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/143/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <p class="name_en">MILBON(ミルボン)</p>
                    <p class="name_jp">エルジューダエマルジョン+<br>120g</p>
                </div>
                <p class="price"><span>2,860</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/143';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/144"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/144/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no2.png') }}" alt="No.2"></p>
                    <p class="name_en">MILBON(ミルボン)</p>
                    <p class="name_jp">エルジューダエマルジョン<br>120g</p>
                </div>
                <p class="price"><span>2,860</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/144';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/250"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/250/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no3.png') }}" alt="No.3"></p>
                    <p class="name_en">MILBON(ミルボン)</p>
                    <p class="name_jp">グランドリンケージヴェロアリュクストリートメント<br>500g</p>
                </div>
                <p class="price"><span>5,280</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/250';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/184"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/184/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no4.png') }}" alt="No.4"></p>
                    <p class="name_en">MILBON(ミルボン)</p>
                    <p class="name_jp">ジェミールフランヒートグロストリートメントS<br>500g</p>
                </div>
                <p class="price"><span>4,840</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/184';" value="詳細を見る"></p>
            </li>
        </ul>
        <!--<p class="btn_rank"><a href="./">ランキングから探す</a></p>-->
    </section>

    <section class="sec02">
        <h2 class="rank"><span>STYLING</span></h2>
        <p class="jp">スタイリング</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/131"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/131/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ｵｲﾙNO.3<br>90ml</p>
                </div>
                <p class="price"><span>3,520</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/131';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/23"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/23/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no2.png') }}" alt="No.2"></p>
                    <p class="name_en">N.(エヌドット)</p>
                    <p class="name_jp">N.  ポリッシュオイル<!--<br>120g--></p>
                </div>
                <p class="price"><span>3,740</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/23';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/129"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/129/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no3.png') }}" alt="No.3"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ｵｲﾙNO.1<br>90ml</p>
                </div>
                <p class="price"><span>3,520</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/129';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/25"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/25/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no4.png') }}" alt="No.4"></p>
                    <p class="name_en">N.(エヌドット)</p>
                    <p class="name_jp">N.  スタイリングセラム＜スタイリング＞<!--<br>500g--></p>
                </div>
                <p class="price"><span>1,320</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/25';" value="詳細を見る"></p>
            </li>
        </ul>
        <!--<p class="btn_rank"><a href="./">ランキングから探す</a></p>-->
    </section>

    <section class="sec02">
        <h2 class="rank"><span>MAKE</span></h2>
        <p class="jp">メイク</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/367"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/367/main.png" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <p class="name_en">SPICARE(スピケア)</p>
                    <p class="name_jp">V3エキサイティングファンデーション<br>15g</p>
                </div>
                <p class="price"><span>7,700</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/367';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/121"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/121/main.jpeg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no2.png') }}" alt="No.2"></p>
                    <p class="name_en">Lipaddict(リップアディクト)</p>
                    <p class="name_jp">リップアディクト213　ジュエル<br>7ml</p>
                </div>
                <p class="price"><span>6,600</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/121';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/111"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/111/main.jpeg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no3.png') }}" alt="No.3"></p>
                    <p class="name_en">Lipaddict(リップアディクト)</p>
                    <p class="name_jp">リップアディクト202　コーラリスタ<br>7ml</p>
                </div>
                <p class="price"><span>6,600</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/111';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/370"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/370/main.png" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no4.png') }}" alt="No.4"></p>
                    <p class="name_en">SPICARE(スピケア)</p>
                    <p class="name_jp">V3シャイニングファンデーション<br>15g</p>
                </div>
                <p class="price"><span>9,350</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/370';" value="詳細を見る"></p>
            </li>
        </ul>
        <!--<p class="btn_rank"><a href="./">ランキングから探す</a></p>-->
    </section>

    <section class="sec02">
        <h2 class="rank"><span>SKIN CARE</span></h2>
        <p class="jp">スキンケア</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/108"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/108/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <p class="name_en">Lashaddict(ラッシュアディクト)</p>
                    <p class="name_jp">アイラッシュコンディショニングセラム<br>5ml</p>
                </div>
                <p class="price"><span>11,000</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/108';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/109"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/109/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no2.png') }}" alt="No.2"></p>
                    <p class="name_en">Lashaddict(ラッシュアディクト)</p>
                    <p class="name_jp">ブロウアディクト アイブロウコンディショニングセラム<br>5ml</p>
                </div>
                <p class="price"><span>11,000</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/109';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/369"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/369/main.png" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no3.png') }}" alt="No.3"></p>
                    <p class="name_en">SPICARE(スピケア)</p>
                    <p class="name_jp">V3ピンジェクトセラム<br>10ml</p>
                </div>
                <p class="price"><span>8,800</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/369';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/126"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/126/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no4.png') }}" alt="No.4"></p>
                    <p class="name_en">ホノヲヲ</p>
                    <p class="name_jp">ホノヲヲMCクレンジングマッサージジェル<br>120g</p>
                </div>
                <p class="price"><span>7,150</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/126';" value="詳細を見る"></p>
            </li>
        </ul>
        <!--<p class="btn_rank"><a href="./">ランキングから探す</a></p>-->
    </section>

    <section class="sec02">
        <h2 class="rank"><span>BODY CARE</span></h2>
        <p class="jp">ボディケア</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/134"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/134/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ｸﾘｰﾑNO.3<br>60g</p>
                </div>
                <p class="price"><span>2,860</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/134';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/132"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/132/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no2.png') }}" alt="No.2"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ｸﾘｰﾑNO.1<br>60g</p>
                </div>
                <p class="price"><span>2,860</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/132';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/133"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/133/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no3.png') }}" alt="No.3"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ｸﾘｰﾑNO.2<br>60g</p>
                </div>
                <p class="price"><span>2,860</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/133';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/54"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/54/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no4.png') }}" alt="No.4"></p>
                    <p class="name_en">N.(エヌドット)</p>
                    <p class="name_jp">N. ポリッシュソープ<br>300ml</p>
                </div>
                <p class="price"><span>1,760</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/54';" value="詳細を見る"></p>
            </li>
        </ul>
        <!--<p class="btn_rank"><a href="./">ランキングから探す</a></p>-->
    </section>

    <section class="sec02">
        <h2 class="rank"><span>FRAGRANCE</span></h2>
        <p class="jp">フレグランス</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/141"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/141/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ﾃﾞｨﾌｭｰｻﾞｰ ﾎﾜｲﾄﾑｽｸ<br>80ml</p>
                </div>
                <p class="price"><span>2,420</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/141';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/140"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/140/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no2.png') }}" alt="No.2"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ﾃﾞｨﾌｭｰｻﾞｰ ｸﾞﾘｰﾝﾃｨｰ<br>80ml</p>
                </div>
                <p class="price"><span>2,420</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/140';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/139"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/139/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no3.png') }}" alt="No.3"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ﾃﾞｨﾌｭｰｻﾞｰ ﾛｰｽﾞﾌﾞｰｹ<br>80ml</p>
                </div>
                <p class="price"><span>2,420</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/139';" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/142"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/142/main.jpg" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no4.png') }}" alt="No.4"></p>
                    <p class="name_en">track(トラック)</p>
                    <p class="name_jp">track ﾃﾞｨﾌｭｰｻﾞｰ ｱｯﾌﾟﾙﾍﾟｱｰ<br>80ml</p>
                </div>
                <p class="price"><span>2,420</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/142';" value="詳細を見る"></p>
            </li>
        </ul>
        <!--<p class="btn_rank"><a href="./">ランキングから探す</a></p>-->
    </section>

    <section class="sec02">
        <h2 class="rank"><span>OTHERS</span></h2>
        <p class="jp">その他</p>
        <div class="clearfix">
            <!--<h3>HAIR CARE<span>ヘアケア</span></h3>
            <p class="btn_top5"><a href="./"><span>ヘアケアのTOP5を見る</span></a></p>-->
        </div>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/128"><img src="https://s3.ap-northeast-1.amazonaws.com/bap-ec-public-goods-images/128/main.png" alt=""></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="no"><img src="{{ asset('images/top/icon_no1.png') }}" alt="No.1"></p>
                    <!--<p class="name_en">track(トラック)</p>-->
                    <p class="name_jp">イヤーココ</p>
                </div>
                <p class="price"><span>8,800</span>円(税込)</p>
                <p class="btn"><input class="button" onClick="location.href='/goods/128';" value="詳細を見る"></p>
            </li>
    </section>

    <!--<section class="sec02">
        <h2 class="arrivals"><span>NEW ARRIVALS</span></h2>
        <p class="jp">新着商品</p>
        <ul class="item_list clearfix">
            <li>
                <figure>
                    <a href="/goods/"><img src="{{ asset('images/item/img_item05.jpg') }}" alt="ラッシュアディクトアイラッシュ  コンディショニングセラム"></a>
                    <div class="heart"></div>
                    <div class="sample">
                        <a href="/"><img src="{{ asset('images/top/icon_sample.png') }}" alt="sample"></a>
                    </div>
                </figure>
                <div>
                    <p class="name_en">BJC／スキンケア</p>
                    <p class="name_jp">ラッシュアディクトアイラッシュ コンディショニングセラム<br>5ml</p>
                </div>
                <p class="price"><span>11,000</span>円(税込)</p>
                <p class="btn"><input type="submit" name="submit" value="詳細を見る"></p>
            </li>
            <li>
                <figure>
                    <a href="/goods/"><img src="{{ asset('images/item/img_item06.jpg') }}" alt="【正規品】V3エキサイティング ファンデーション "></a>
                    <div class="heart"></div>
                </figure>
                <div>
                    <p class="name_en">BJC／スキンケア</p>
                    <p class="name_jp">【正規品】V3エキサイティング ファンデーション <br>15g</p>
                </div>
                <p class="price"><span>8,800</span>円(税込)</p>
                <p class="btn"><input type="submit" name="submit" value="詳細を見る"></p>
            </li>
        </ul>
        <p class="btn_rank"><a href="/">新着商品から探す</a></p>
    </section>-->

    <!--<section class="sec03">
        <h2 class="sales"><span>SALES SCHEDULE</span></h2>
        <p class="jp">商品販売スケジュール</p>
        <p class="img"><img src="{{ asset('images/top/img_calender.jpg') }}" alt="商品販売スケジュール"></p>
    </section>-->

    <!--<section class="sec04">
        <h2 class="reviews"><span>REVIEWS</span></h2>
        <p class="jp">購入者様からの声をご紹介</p>
        <dl class="block_rev">
            <dt>
                <figure><img src="{{ asset('images/item/img_item07.jpg') }}" alt="オーキデ アンペリアル ファンデーション"></figure>
                <div>
                    <ul class="star">
                    <li><img  src="{{ asset('images/top/icon_star_on.svg') }}" alt="★"></li>
                    <li><img  src="{{ asset('images/top/icon_star_on.svg') }}" alt="★"></li>
                    <li><img  src="{{ asset('images/top/icon_star_on.svg') }}" alt="★"></li>
                    <li><img  src="{{ asset('images/top/icon_star_on.svg') }}" alt="★"></li>
                    <li><img  src="{{ asset('images/top/icon_star_off.svg') }}" alt="☆"></li>
                    </ul>
                    <p class="name_en">BJC</p>
                    <p class="name_jp">HONO MCクレンジングマッサージジェル</p>
                    <p class="price"><span>12,000</span>円(税込)</p>
                </div>
            </dt>
            <dd>
                <div class="clearfix">
                    <p class="user"><span>●●●●様</span></p>
                    <p class="date">2022/01/23</p>
                </div>
                <p class="txt">テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト・テキスト</p>
            </dd>
        </dl>
    </section>-->

    <!--<section class="sec05">
        <h2 class="info"><span>INFORMATION</span></h2>
        <p class="jp">お知らせ</p>
        <ul class="news">
            <li>
                <a href="/">
                    <p class="date">2022/01/24</p>
                    <p class="title">【重要なお知らせ】年末年始のお届けについて</p>
                </a>
            </li>
            <li>
                <a href="/">
                    <p class="date">2022/01/12</p>
                    <p class="title">決済に関するお知らせ</p>
                </a>
            </li>
        </ul>
    </section>-->

</article>
@endsection

@push('app-style')
<link rel="stylesheet" href="{{asset('vendor/jquery-modal-video/modal-video.min.css')}}" type="text/css" media="all" />
<link rel="stylesheet" href="{{asset('vendor/slick/slick.css')}}" type="text/css" media="all" />
<link rel="stylesheet" href="{{asset('vendor/slick/slick-theme.css')}}" type="text/css" media="all" />
@endpush

@push('app-script')
<script type="text/javascript" src="{{asset('vendor/jquery-modal-video/jquery-modal-video.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.js')}}" charset="utf-8"></script>
<script type="text/javascript">
$(function() {
    $('.sec02 .item_list li figure').matchHeight();
    $('.sec02 .item_list li figure + div').matchHeight();
});
</script>
@endpush