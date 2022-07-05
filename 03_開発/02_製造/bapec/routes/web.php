<?php
use App\Providers\RouteServiceProvider;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//////////////////////////////
// システム設定
//////////////////////////////
Route::get('js/app-config.js', 'AppConfigController@index');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('js/app-config-admin.js', 'AppConfigController@index');
});

Route::middleware(['auth:member'])->group(function () {
    Route::get('js/app-config-member.js', 'AppConfigController@index');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/baseform', 'Admin\BaseFormController@index')->name('index');
});


//////////////////////////////
// 会員機能のルーティング定義
//////////////////////////////
Route::group(['namespace' => 'Member', 'domain' => config('app.client_domain')], function () {

    // Gateway
    Route::post('gateway', 'Auth\GatewayController@index');
    //B-crewアプリ案内ページ
    Route::view('/app-info', 'member/info')->name('member.info');

    // 固定ページ
    Route::get('static/{dir}/{page}', function ($dir, $page) {
        return view("member.staticPage", ['dir' => $dir, 'page' => $page]);
    });

    // 認証後
    Route::middleware(['verified', 'auth:member'])->group(function () {
        // ショップトップ
        Route::get('/', 'ShopTopController@index')->name('member.shopTop');

        //商品一覧画面
        Route::get('goods', 'GoodsController@search')->name('goods.search');
        //商品詳細画面
        Route::get('goods/{id}', 'GoodsController@index');

        // カート
        Route::group(['prefix' => 'cart'], function () {
            Route::get('', 'CartController@index')->name('member.cart');
            Route::post('next', 'CartController@next')->name('member.cart.next');
        });

        // 注文フロー
        Route::group(['prefix' => 'order'], function () {
            Route::get('input', 'OrderController@input')->name('order.input');
            Route::post('confirm', 'OrderController@confirm')->name('order.confirm');
            Route::get('checkout', 'OrderController@checkout')->name('order.checkout');
            Route::get('complete', 'OrderController@complete')->name('order.complete');
        });

        //会員ページ
        Route::group(['prefix' => 'members'], function () {
            // マイページトップ
            Route::get('home', 'MembersController@home')->name('members.home');
            // 会員情報変更
            Route::get('edit', 'MembersController@edit')->name('members.edit');
            Route::post('update', 'MembersController@update')->name('members.update');
        });

        //購入履歴
        Route::group(['prefix' => 'order-history'], function () {
            // 購入履歴一覧
            Route::get('', 'OrderHistoriesController@index')->name('order.history');
            // 購入履歴詳細
            Route::match(['get','post'], '{id}', 'OrderHistoriesController@detail')->name('order.history.detail');
            // 購入履歴詳細 注文キャンセル
            Route::put('{id}/cancel', 'OrderHistoriesController@cancel')->name('order.cancel');
        });
    });

    Route::group(['namespace' => 'Auth'], function () {
        Route::get('login', 'LoginController@showLoginForm')->name('member.login');
        Route::post('login', 'LoginController@login')->name('member.login');
        Route::get('logout', 'LoginController@logout')->name('member.logout');
        Route::post('logout', 'LoginController@logout');
        // Route::get('register-entry', 'RegisterController@entry')->name('member.register-entry');
        Route::get('register-entry', 'RegisterController@showRegistrationForm')->name('member.register-entry');
        // Route::post('register-entry', 'RegisterController@entryBack')->name('member.register-entry-back');
        // Route::post('register-confirm', 'RegisterController@confirm')->name('member.register-confirm');
        Route::post('register', 'RegisterController@register')->name('member.register');
        // Route::get('email/verify', 'VerificationController@show')->name('member.verification.notice');
        // Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('member.verification.verify');
        // Route::post('email/resend', 'VerificationController@resend')->name('member.verification.resend');
        // Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('member.password.request');
        // Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('member.password.email');
        // Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('member.password.reset');
        // Route::post('password/reset', 'ResetPasswordController@reset')->name('member.password.update');
    });

    //決済通知受付
    Route::post('/payment/receive','PaymentController@receive')->name('member.receive');
    //GMOからの戻り
    Route::post('/payment/back','PaymentController@back')->name('member.receive.back');

    //GMOテスト用
    Route::get('/gmo','GMOTestController@GMOTest');
});


//////////////////////////////
// 管理機能のルーティング定義
//////////////////////////////
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'domain' => config('app.admin_domain')], function () {
    // 認証
    Route::group(['namespace' => 'Auth'], function () {
        Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
        Route::post('login', 'LoginController@login');
        Route::get('logout', 'LoginController@logout');
        Route::post('logout', 'LoginController@logout')->name('admin.logout');
    });
    // 認証後
    Route::middleware(['auth:admin'])->group(function () {
        Route::group(['middleware' => ['get.menu']], function () {
            // ダッシュボード
            Route::get('', 'DashBoardController@index')->name('dashboard');

            // コード管理
            Route::group(['middleware' => ['menu.auth:admin.codes']], function (): void {
                Route::prefix('codes')->group(function (): void {
                    Route::get('', 'CodesController@index');
                });
            });
            // ユーザ管理
            Route::group(['middleware' => ['menu.auth:admin.users']], function (): void {
                Route::prefix('users')->group(function (): void {
                    Route::get('', 'UsersController@index');
                });
            });
            // 顧客管理
            Route::group(['middleware' => ['menu.auth:admin.customers']], function (): void {
                Route::prefix('customers')->group(function (): void {
                    Route::get('', 'CustomersController@index');
                });
            });
            // 受注管理
            Route::group(['middleware' => ['menu.auth:orders']], function () {
                Route::prefix("orders")->group(function () {
                    // 受注一覧
                    Route::get('', 'OrdersController@index')->name('orders.index');
                    // 受注詳細
                    Route::get('{orderId}/details', 'OrdersController@detail')->where('orderId', '[0-9]+');
                    Route::get('details/back', 'OrdersController@back');
                });
            });
            // 出荷管理
            Route::group(['middleware' => ['menu.auth:ships']], function () {
                Route::prefix("ships")->group(function () {
                    // 出荷一覧
                    Route::get('', 'ShipsController@index')->name('ships.index');;
                    // 出荷詳細
                    Route::get('{shipId}/details', 'ShipsController@detail')->where('shipId', '[0-9]+');
                    Route::get('details/back', 'ShipsController@back');
                });
            });
            // 商品管理
            Route::group(['middleware' => ['menu.auth:goods']], function () {
                Route::prefix("goods")->group(function () {
                    // 商品一覧
                    Route::get('', 'GoodsController@index')->name('goods.index');
                    // 商品サムネイル
                    Route::get('{goods_id}/thumbnail', 'GoodsImagesController@index')->name('goodsImages.index');
                    Route::get('thumbnail/back', 'GoodsImagesController@back');
                });
            });
            //カテゴリー
            Route::group(['middleware' => ['menu.auth:categories']], function () {
                Route::prefix("categories")->group(function () {
                    Route::get('', 'CategoriesController@index')->name('categories.index');;
                });
            });
            // ショップ基本情報
            Route::group(['middleware' => ['menu.auth:shopinfo']], function () {
                Route::prefix("shopinfo")->group(function () {
                    Route::get('', 'ShopinfoController@index')->name('shopinfo.index');
                });
            });
            // CSV出力
            Route::group(['middleware' => ['menu.auth:admin.csv-output']], function () {
                Route::prefix("csv-output")->group(function () {
                    Route::get('', 'CsvOutputController@index');
                });
                //画面出力 不要な場合はコメント
                Route::post('general_list', 'CsvOutputController@general_list')->name('csv-output.general_list');
            });
            // 佐川連携ファイル出力
            Route::group(['middleware' => ['menu.auth:admin.sagawa-output']], function () {
                Route::prefix("sagawa-output")->group(function () {
                    Route::get('', 'SagawaOutputController@index');
                });
            });

            // CSV取込
            // 出荷実績
            Route::group(['middleware' => ['menu.auth:admin.import-shipsachieve']], function () {
                Route::prefix("import-shipsachieve")->group(function () {
                    Route::get('', 'ImportShipsController@index');
                });
            });
            // 在庫
            Route::group(['middleware' => ['menu.auth:admin.import-stocks']], function () {
                Route::prefix("import-stocks")->group(function () {
                    Route::get('', 'ImportStocksController@index');
                });
            });
        });
    });
});


//////////////////////////////
// APIルーティング定義
//////////////////////////////
Route::group(['prefix' => 'api'], function () {

    // システム共通
    Route::group(['prefix' => 'zip', 'namespace' => 'Util'], function () {
        Route::get('search/addr/{zipCode}', 'ZipsController@searchAddr');
        Route::get('search/zip/{addr}', 'ZipsController@searchZip');
    });

    // 会員機能のAPIのルーティング定義
    Route::group(['prefix' => 'member', 'namespace' => 'Member', 'domain' => config('app.client_domain')], function () {

        // ヘッダ検索
        Route::get('headerInfoSearch', 'DashBoardController@getcountHeaderInfo');

        // カート
        Route::prefix("cart")->group(function () {
            Route::post('add', 'CartController@add');
            Route::delete('', 'CartController@delete');
            Route::put('', 'CartController@update');
            Route::get('info', 'CartController@info');
        });

        // 認証後
        Route::middleware(['verified', 'auth:member'])->group(function () {
            //　注文入力 APIのルーティング定義
            Route::prefix("order")->group(function () {
                // 決済方法変更
                Route::post ('changePayment/{payment_id?}', 'OrderController@changePayment')->where ('payment_id','[0-9]+');
                Route::post ('changePoint/{point?}', 'OrderController@changePoint')->where ('point','[0-9]+');
            });
        });
    });

    // 管理機能のAPIのルーティング定義
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'domain' => config('app.admin_domain')], function () {
        // 認証後
        Route::middleware(['auth:admin'])->group(function () {
            // コード管理
            Route::prefix("codes")->group(function () {
                Route::get('search', 'CodesController@search');
                Route::post('store', 'CodesController@store');
                Route::get('{id}/edit', 'CodesController@edit');
                Route::put('{id}', 'CodesController@update');
                Route::delete('{id}', 'CodesController@delete');
            });
            // 管理者管理
            Route::prefix("users")->group(function () {
                Route::get('search', 'UsersController@search');
                Route::post('store', 'UsersController@store');
                Route::get('{id}/edit', 'UsersController@edit');
                Route::put('{id}', 'UsersController@update');
                Route::delete('{id}', 'UsersController@delete');
                //ユーザ権限
                Route::get('edit/auth/{id}', 'UsersController@editauth');
                Route::put('update/auth/{id}', 'UsersController@updateauth');

            });
            // 顧客
            Route::prefix("customers")->group(function () {
                Route::get('search', 'CustomersController@search');
                Route::post('store', 'CustomersController@store');
                Route::get('{id}/edit', 'CustomersController@edit');
                Route::put('{id}', 'CustomersController@update');
                Route::delete('{id}', 'CustomersController@delete');
            });
            // 受注
            Route::group(['middleware' => ['menu.auth:admin.orders']], function (): void {
                Route::prefix('orders')->group(function (): void {
                    Route::get('search', 'OrdersController@search');
                    Route::put('{id}', 'OrdersController@update');
                });
            });
            // 商品
            Route::prefix("goods")->group(function () {
                Route::get('search', 'GoodsController@search');
                Route::post('store', 'GoodsController@store');
                Route::get('{id}/edit', 'GoodsController@edit');
                Route::post('{id}', 'GoodsController@update');
                Route::delete('{id}', 'GoodsController@delete');
                Route::get('codes/{value}', 'GoodsController@codes');

                Route::get('{goods_id}/thumbnail/find', 'GoodsImagesController@findByGoodsId');
                Route::post('{goods_id}/thumbnail/entry', 'GoodsImagesController@entryByGoodsId');
            });
            // 出荷
            Route::group(['middleware' => ['menu.auth:admin.ships']], function (): void {
                Route::prefix('ships')->group(function (): void {
                    Route::get('search', 'ShipsController@search');
                    Route::put('{id}', 'ShipsController@update');
                    Route::put('{id}/mail', 'ShipsController@send');
                    Route::put('{id}/returns', 'ShipsController@returns');
                    Route::put('{id}/cancel', 'ShipsController@cancel');
                });
            });
            //カテゴリー
            Route::group(['middleware' => ['menu.auth:categories']], function () {
                Route::prefix("categories")->group(function () {
                    Route::put('', 'CategoriesController@update');
                    Route::get('search', 'CategoriesController@search');
                    Route::post('store', 'CategoriesController@storeGoods');
                    Route::delete('{id}', 'CategoriesController@deleteGoods');
                });
            });
            // ショップ基本情報
            Route::group(['middleware' => ['menu.auth:shopinfo']], function () {
                Route::prefix("shopinfo")->group(function () {
                    Route::get('', 'ShopinfoController@find');
                    Route::post('', 'ShopinfoController@store');
                });
            });
            //CSV出力
            Route::group(['middleware' => ['menu.auth:admin.csv-output']], function () {
                Route::prefix("csv-output")->group(function () {
                    Route::get('{file_name}/edit/', 'CsvOutputController@edit');
                    Route::post('download', 'CsvOutputController@download');

                });
                //画面出力 不要な場合はコメント
                Route::get('general_list/search', 'CsvOutputController@general_list_search');
            });
            //佐川連携ファイル出力
            Route::group(['middleware' => ['menu.auth:admin.sagawa-output']], function () {
                Route::prefix("sagawa-output")->group(function () {
                    Route::get('download-goods', 'SagawaOutputController@goods');
                    Route::get('download-ships', 'SagawaOutputController@ships');
                });
            });
            //在庫CSV取込
            Route::group(['middleware' => ['menu.auth:admin.import-stocks']], function () {
                Route::prefix("import-stocks")->group(function () {
                    Route::get('getlogs', 'ImportStocksController@search');
                    Route::post('upload', 'ImportStocksController@import');
                });
                Route::prefix("import")->group(function () {
                    Route::get('{id}/file', 'ImportStocksController@file');
                    Route::get('{id}/log', 'ImportStocksController@log');
                });
            });
            //出荷実績CSV取込
            Route::group(['middleware' => ['menu.auth:admin.import-shipsachieve']], function () {
                Route::prefix("import-ships")->group(function () {
                    Route::get('getlogs', 'ImportShipsController@search');
                    Route::post('upload', 'ImportShipsController@import');
                    Route::get('{id}/file', 'ImportShipsController@file');
                    Route::get('{id}/log', 'ImportShipsController@log');
                });
                Route::prefix("import")->group(function () {
                    Route::get('{id}/file', 'ImportStocksController@file');
                    Route::get('{id}/log', 'ImportStocksController@log');
                });
            });
        });
    });
});


// testルーティング定義
// Route::group(['prefix' => 'sandbox', 'namespace' => 'Sandbox', 'domain' => config('app.members_domain')], function () {

//     // 商品一覧
//     Route::get('products', 'ProductsController@index')->name('sandbox.products');
//     // 買い物かご
//     Route::get('carts', 'CartController@index')->name('sandbox.carts');
//     Route::post('checkout', 'CartController@checkout')->name('sandbox.checkout');

//     Route::get('csvimport', 'ImportController@index');

//     // API
//     Route::group(['prefix' => 'api'], function () {
//         Route::resource('products', 'Ajax\ProductsController')->only(['index']);
//         Route::resource('carts', 'Ajax\CartController')->only(['index', 'store', 'destroy']);
//     });
// });
