<div id="gmostatus">
  支払方法:<br>
  {{Form::select('payment_method',
                  ['credit' => 'クレジットカード', 
                    'au' => 'auかんたん決済', 
                    'docomo' => 'ドコモ払い決済', 
                    'sb' => 'ソフトバンクまとめて支払い（B）決済', 
                    'other' => 'その他'], 
                'credit', 
                [])}}<br>

  {{Form::open(['route'=>'member.receive', 'method'=>'POST'])}}
    OrderID:<br>
    {{Form::text('OrderID','',[])}}<br>
    <span class="kome">※ORD-00-0000000000</span><br>
    
    Status:<br>
    {{Form::select('Status',
                    ['UNPROCESSED' => 'UNPROCESSED', 
                     'AUTHENTICATED' => 'AUTHENTICATED', 
                     'CHECK' => 'CHECK',
                     'CAPTURE' => 'CAPTURE',
                     'AUTH' => 'AUTH',
                     'SALES' => 'SALES',
                     'VOID' => 'VOID',
                     'RETURN' => 'RETURN',
                     'RETURNX' => 'RETURNX',
                     'SAUTH' => 'SAUTH'],
                    'CAPTURE', 
                    [])}}
    <br>

    PayType:<br>
    {{Form::text('PayType','',[])}}
    <br>
    <span class="kome">※数字2桁</span><br>
    {{Form::submit('送信', [])}}
  {{ Form::close() }}
</div>

