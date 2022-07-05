<footer>
    <ul class="cta">
        <li>
            <figure><img src="{{asset('images/common/img_point.svg')}}" alt="ポイント"></figure>
            <div>
                <p class="ttl">ポイント</p>
                <p class="txt">B-crewポイント：決済時の利用{{-- 、決済以外のプレゼント応募などに利用が --}}可能。</p>
            </div>
        </li>
        {{--
        <li>
            <figure><img src="{{asset('images/common/img_gift.svg')}}" alt="ギフトラッピング"></figure>
            <div>
                <p class="ttl">ギフトラッピング</p>
                <p class="txt">ギフトラッピング無料お届け先を指定する際にギフトラッピングを承っております。</p>
            </div>
        </li>
        --}}
        <li>
            <figure><img src="{{asset('images/common/img_ship.svg')}}" alt="送料無料／最短当日発送"></figure>
            <div>
                <p class="ttl">送料無料／最短当日発送</p>
                <p class="txt">1会計税込10,000円以上のご購入で送料無料。午前10時59分までのご注文で最短当日発送。</p>
            </div>
        </li>
    </ul>
    <ul class="fnav">
        <li><a href="https://www.b-crew.jp/ec-support/?id={{ Auth::check() ? Auth::user()->bcrew_customer_id : '' }}" target="_blank">お問い合わせ</a></li>
        <li><a href="{{ url('/static/info/shipping') }}">ご利用ガイド</a></li>
        <!--<li><a href="#">よくある質問</a></li>-->
        <!--<li><a href="#">お問い合わせ</a></li>-->
        <li><a href="https://b-a-p.co.jp/company.html" target="_blank">企業情報</a></li>
        <li><a href="{{ url('/static/info/privacy') }}">プライバシーポリシー</a></li>
        <li><a href="{{ url('/static/info/tokusho') }}">特定商取引法に基づく<br>表示</a></li>
    </ul>
    <ul class="sns">
        <!--<li>
            <a href="#"><img src="{{asset('images/common/icon_line.svg')}}" alt="LINE"></a>
        </li>-->
        <li>
            <a href="https://www.instagram.com/beast00825/"  target="_blank"><img src="{{asset('images/common/icon_insta.svg')}}" alt="Instagram"></a>
        </li>
        <!--<li>
            <a href="#"><img src="{{asset('images/common/icon_youtube.svg')}}" alt="YouTube"></a>
        </li>-->
    </ul>
    <p class="copy">&copy; Beauty Artist Planning Co.,Itd All Rights Reserved.</p>
</footer>