<?php
require(config_path('base/page/app-page-settings.php'));
require(config_path('base/simple-template/app-simple-template-settings.php'));
require(config_path('base/entity/app-entity-settings.php'));
/*
|--------------------------------------------------------------------------
| アプリケーションの設定
|--------------------------------------------------------------------------
*/
return [
    // 権限制御を使用するか
    'use-auth-control' => true,
    // 認証関連
    'auth' => [
        /*
        |--------------------------------------------------------------------------
        | パスワードポリシー
        |--------------------------------------------------------------------------
        | false             : 未使用
        | minlength         : 最小桁数
        | include-numeric   : 数値を含むこと
        | include-alphabet  : 英字を含むこと
        | include-symbol    : 記号を含むこと
        | frequently-phrases: よく使われるフレーズ、falseの場合、未使用
        |                     http://www.whatsmypass.com/the-top-500-worst-passwords-of-all-time
        |                     https://www.gizmodo.jp/2019/12/worst-passwords-2019.html
        */
        'password-policy' => [
            'minlength'          => 8,
            'include-numeric'    => false,
            'include-alphabet'   => false,
            'include-symbol'     => false,
            'frequently-phrases' => [
                4 => [
                    '1234','cool','1313','star','golf','bear','dave','pass','aaaa','6969',
                    'jake','matt','1212','fish','fuck','porn','4321','2000','4128','test',
                    'shit','love','baby','cunt','mark','3333','john','sexy','5150','4444',
                    '2112','fred','mike','1111','tits','paul','mine','king','fire','5555',
                    'slut','girl','2222','asdf','time','7777','rock','xxxx','ford','dick',
                    'bill','wolf','blue','alex','cock','beer','eric','6666','jack',
                ],
                5 => [
                    'beach','great','black','pussy','12345','frank','tiger','japan','money','naked',
                    '11111','angel','stars','apple','porno','steve','viper','horny','ou812','kevin',
                    'buddy','teens','young','jason','lucky','girls','lover','brian','kitty','bubba',
                    'happy','cream','james','xxxxx','booty','kelly','boobs','penis','eagle','white',
                    'enter','chevy','smith','chris','green','sammy','super','magic','power','enjoy',
                    'scott','david','video','qwert','paris','women','juice','dirty','music','peter',
                    'bitch','house','hello','billy','movie','12345','admin'
                ],
                6 => [
                    '123456','prince','guitar','butter','jaguar','united','turtle','muffin','cooper','nascar',
                    'redsox','dragon','zxcvbn','qwerty','tomcat','696969','654321','murphy','987654','amanda',
                    'brazil','wizard','hannah','lauren','master','doctor','eagle1','gators','squirt','shadow',
                    'mickey','mother','monkey','bailey','junior','nathan','abc123','knight','alexis','iceman',
                    'fuckme','tigers','badboy','bonnie','purple','debbie','angela','jordan','andrea','spider',
                    'harley','ranger','dakota','booger','iwantu','aaaaaa','lovers','player','flyers','suckit',
                    'hunter','beaver','morgan','matrix','boomer','runner','batman','scooby','edward','thomas',
                    'walter','helpme','gordon','tigger','jackie','casper','robert','booboo','boston','monica',
                    'stupid','access','coffee','braves','xxxxxx','yankee','saturn','buster','gemini','barney',
                    'apples','soccer','rabbit','victor','august','hockey','peanut','tucker','killer','canada',
                    'george','johnny','sierra','blazer','andrew','spanky','doggie','232323','winter','zzzzzz',
                    'brandy','gunner','beavis','compaq','horney','112233','carlos','arthur','dallas','tennis',
                    'sophie','ladies','calvin','shaved','pepper','giants','surfer','fender','samson','austin',
                    'member','blonde','blowme','fucked','daniel','donald','golden','golfer','cookie','summer',
                    'bronco','racing','sandra','hammer','pookie','joseph','hentai','joshua','diablo','birdie',
                    'maggie','sexsex','little','biteme','666666','topgun','ashley','willie','sticky','cowboy',
                    'animal','silver','yamaha','qazwsx','fucker','justin','skippy','orange','banana','lakers',
                    'marvin','merlin','driver','rachel','marine','slayer','angels','asdfgh','bigdog','vagina',
                    'apollo','cheese','toyota','parker','maddog','travis','121212','london','hotdog','wilson',
                    'sydney','martin','dennis','voodoo','ginger','magnum','action','nicole','carter','erotic',
                    'sparky','jasper','777777','yellow','smokey','dreams','camaro','xavier','teresa','freddy',
                    'secret','steven','jeremy','viking','falcon','snoopy','russia','taylor','nipple','111111',
                    'eagles','131313','winner','tester','123123','miller','rocket','legend','flower','theman',
                    '123456','111111','123123','abc123','654321','555555','lovely','888888','dragon','123qwe',
                    '666666','333333','777777','donald','secret','bailey','shadow','121212','biteme','ginger',
                    'please','oliver','albert'
                ],
                7 => [
                    'porsche','rosebud','chelsea','amateur','7777777','diamond','tiffany','jackson','scorpio','cameron',
                    'testing','shannon','madison','mustang','bond007','letmein','michael','gateway','phoenix','thx1138',
                    'raiders','forever','peaches','jasmine','melissa','gregory','cowboys','dolphin','charles','cumshot',
                    'college','bulldog','1234567','ncc1701','gandalf','leather','cumming','hunting','charlie','rainbow',
                    'asshole','bigcock','fuckyou','jessica','panties','johnson','naughty','brandon','anthony','william',
                    'ferrari','chicken','heather','chicago','voyager','yankees','rangers','packers','newyork','trouble',
                    'bigtits','winston','thunder','welcome','bitches','warrior','panther','broncos','richard','8675309',
                    'private','zxcvbnm','nipples','blondes','fishing','matthew','hooters','patrick','freedom','fucking',
                    'extreme','blowjob','captain','bigdick','abgrtyu','chester','monster','maxwell','arsenal','crystal',
                    'rebecca','pussies','florida','phantom','scooter','success','7777777','welcome','michael','freedom',
                    'charlie','letmein','zxcvbnm','nothing'
                ],
                8 => [
                    'firebird','password','12345678','steelers','mountain','computer','baseball','xxxxxxxx','football','qwertyui',
                    'jennifer','danielle','sunshine','starwars','whatever','nicholas','swimming','trustno1','midnight','princess',
                    'startrek','mercedes','superman','bigdaddy','maverick','einstein','dolphins','hardcore','redwings','cocacola',
                    'michelle','victoria','corvette','butthead','marlboro','srinivas','internet','redskins','11111111','access14',
                    'iloveyou','1q2w3e4r','princess','1qaz2wsx','sunshine','football','!@#$%^&*','aa123456','passw0rd','mistress',
                    'rush2112','scorpion','iloveyou','samantha'
                ],
                9 => [
                    '123456789','qwerty123','password1','liverpool','987654321'
                ],
                10 => [
                    'qwertyuiop','1q2w3e4r5t'
                ]
            ],
        ],
        /*
        |--------------------------------------------------------------------------
        | メール認証トークン有効期限（分）
        |--------------------------------------------------------------------------
        */
        'limit' => 60
    ],
    /*
    |--------------------------------------------------------------------------
    | Webページの設定
    |--------------------------------------------------------------------------
    */
    'page' => $pageSettings,
    /*
    |--------------------------------------------------------------------------
    | エンティティの設定
    |--------------------------------------------------------------------------
    */
    'entity' => $entitySettings,
    /*
    |--------------------------------------------------------------------------
    | SimpleTemplateの設定
    |--------------------------------------------------------------------------
    */
    'simple-template' => $simpleTemplateSettings
];
