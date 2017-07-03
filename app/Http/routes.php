<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
if( ( isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != env('SITEURL') && $_SERVER['HTTP_HOST'] != env('SITEURL') )  )
{
    Route::get('/',         ['as' => 'website.home',    'uses' => 'Customer\WebSiteController@index']);       
    Route::get('/error',    ['as' => 'website.error',   'uses' => 'Customer\WebSiteController@error']);       
    Route::get('/deactive', ['as' => 'website.deactive','uses' => 'Customer\WebSiteController@deactive']);       
    Route::get('/unpublish',['as' => 'website.unpublish','uses' => 'Customer\WebSiteController@unpublish']);       
    Route::get("/{slug}",   ['as' => 'website.page',    'uses' => 'Customer\WebSiteController@page']);       
}
else
{
    // Validators
    Validator::extend('check_current_password', 'CustomValidation@checkCurrentPassword');
    Validator::extend('valid_domain',           'CustomValidation@validDomain');
     
    // Patterns
    Route::pattern('id', '[0-9]+');
    Route::pattern('slug', '[0-9a-z-_]+');  

    // Site Routes
    Route::get('/', 'Front\HomeController@index');

    // Customer Website
    Route::get('/test', 'Customer\WebsiteController@index');

    // Common
    Route::post('fileupload/slide',         ['as' => 'upload.slide',        'uses' => 'Front\FileUploadController@slide']);
    Route::get('common/map',                ['as' => 'common.map',          'uses' => 'Front\CommonController@map']);
     
    // Pages
    Route::get('page/{slug}',               ['as' => 'page.show',           'uses' => 'Front\PageController@show']);
    Route::get('terms',                     ['as' => 'page.show.terms',     'uses' => 'Front\PageController@show@terms']);
    Route::get('privacy',                   ['as' => 'page.show.privacy',   'uses' => 'Front\PageController@show@privacy']);
    Route::get('about-us',                  ['as' => 'page.show.about',     'uses' => 'Front\PageController@show@about']);

    Route::get('contact-us',                ['as' => 'site.contact',        'uses' => 'Front\ContactController@index']);
    Route::post('postContact',              ['as' => 'site.contact.post',   'uses' => 'Front\ContactController@postIndex']);
     
    // Auth
    /*Route::get('auth/login',                      ['as' => 'login',              'uses' => 'Front\AuthController@login']);
    Route::post('auth/postLogin',                   ['as' => 'login.post',         'uses' => 'Front\AuthController@postLogin']);*/
    Route::get('auth/logout',                       ['as' => 'logout',             'uses' => 'Front\AuthController@logout']);
    /*Route::get('auth/register',                   ['as' => 'register',           'uses' => 'Front\AuthController@register']);
    Route::post('auth/postRegister',                ['as' => 'register.post',      'uses' => 'Front\AuthController@postRegister']);*/
    Route::get('auth/forgot-password',              ['as' => 'forgot',             'uses' => 'Front\AuthController@forgotPassword']);
    Route::post('auth/postForgotPassword',          ['as' => 'forgot.post',        'uses' => 'Front\AuthController@postForgotPassword']);
    Route::get('auth/reset-password/{token?}',      ['as' => 'reset',              'uses' => 'Front\AuthController@resetPassword']);
    Route::post('auth/postResetPassword',           ['as' => 'reset.post',         'uses' => 'Front\AuthController@postResetPassword']);

    Route::get('auth/register-success',             ['as' => 'register.success',   'uses' => 'Front\AuthController@registerSuccess']);
    Route::get('auth/register-complete',            ['as' => 'register.complete',  'uses' => 'Front\AuthController@registerComplete']);

    Route::post('auth/ajaxLogin',                   ['as' => 'ajax.login',         'uses' => 'Front\AuthController@ajaxLogin']);
    Route::post('auth/ajaxRegister',                ['as' => 'ajax.register',      'uses' => 'Front\AuthController@ajaxRegister']);

    Route::get('auth/facebook-register',            ['as' => 'register.facebook',              'uses' => 'Front\AuthController@registerWithSocial@facebook']);
    Route::get('auth/facebook-reigster-callback',   ['as' => 'facebook.register.callback',     'uses' => 'Front\AuthController@registerCallback@facebook']);
    Route::post('auth/postRegisterComplete',        ['as' => 'register.complete.post',         'uses' => 'Front\AuthController@postRegisterComplete']);

    // Account
    Route::get('account',                           ['as' => 'profile',       'uses' => 'Front\AccountController@index']);
    Route::post('account/postIndex',                ['as' => 'profile.post',  'uses' => 'Front\AccountController@postIndex']);

    Route::post('account/ajaxUpdatePassword',       ['as' => 'ajax.update.password',   'uses' => 'Front\AccountController@ajaxUpdatePassword']);
    Route::post('account/ajaxUpdateEmail',          ['as' => 'ajax.update.email',      'uses' => 'Front\AccountController@ajaxUpdateEmail']);
    Route::post('account/ajaxUpdateName',           ['as' => 'ajax.update.name',       'uses' => 'Front\AccountController@ajaxUpdateName']);

    // Site
    Route::get('site',                              ['as' => 'site.list',               'uses' => 'Front\SiteController@index']);
    Route::get('site/themes',                       ['as' => 'site.themes',             'uses' => 'Front\SiteController@themes']);
    Route::post('site/newToSession',                ['as' => 'site.new.session',        'uses' => 'Front\SiteController@newSession']);
    Route::get('site/editToSession/{id}',           ['as' => 'site.edit.session',       'uses' => 'Front\SiteController@editSession']);
    Route::get('site/exitSession/{target?}',        ['as' => 'site.exit.session',       'uses' => 'Front\SiteController@exitSession']);
    Route::post('site/publish',                     ['as' => 'site.publish',            'uses' => 'Front\SiteController@publish']);

    Route::get('site/builder',                      ['as' => 'site.builder',                    'uses' => 'Front\Site\SiteBuilderController@builder']);
    Route::get('site/builder/loadPage',             ['as' => 'site.builder.load.page',          'uses' => 'Front\Site\SiteBuilderController@loadPage']);
    Route::post('site/builder/changePage',          ['as' => 'site.builder.change.page',        'uses' => 'Front\Site\SiteBuilderController@changePage']);
    Route::post('site/builder/updateItem',          ['as' => 'site.builder.update.item',        'uses' => 'Front\Site\SiteBuilderController@updateItem']);
    Route::post('site/builder/uploadImage',         ['as' => 'site.builder.upload.image',       'uses' => 'Front\Site\SiteBuilderController@uploadImageSrc']);
    Route::post('site/builder/sortElements',        ['as' => 'site.builder.sort.element',       'uses' => 'Front\Site\SiteBuilderController@sortElements']);
    Route::post('site/builder/updateElementSection',['as' => 'site.builder.update.element.section', 'uses' => 'Front\Site\SiteBuilderController@updateElementSection']);
    Route::post('site/builder/refreshElementSection',['as' => 'site.builder.refresh.element.section', 'uses' => 'Front\Site\SiteBuilderController@refreshElementSection']);
    Route::post('site/builder/deleteElement',       ['as' => 'site.builder.delete.element',     'uses' => 'Front\Site\SiteBuilderController@deleteElement']);
    Route::post('site/builder/updateElement',       ['as' => 'site.builder.update.element',     'uses' => 'Front\Site\SiteBuilderController@updateElement']);
    Route::post('site/builder/updateMapElement',    ['as' => 'site.builder.update.map',         'uses' => 'Front\Site\SiteBuilderController@updateMapElement']);
    Route::post('site/builder/updateFileElement',   ['as' => 'site.builder.update.file',        'uses' => 'Front\Site\SiteBuilderController@updateFileElement']);
    Route::post('site/builder/updateYoutubeElement',['as' => 'site.builder.update.youtube',     'uses' => 'Front\Site\SiteBuilderController@updateYoutubeElement']);

    Route::get('site/pages',                        ['as' => 'site.pages',              'uses' => 'Front\Site\SitePageController@index']);
    Route::post('site/page/create',                 ['as' => 'site.pages.create',       'uses' => 'Front\Site\SitePageController@create']);
    Route::post('site/page/edit',                   ['as' => 'site.pages.edit',         'uses' => 'Front\Site\SitePageController@edit']);
    Route::post('site/page/delete',                 ['as' => 'site.pages.delete',       'uses' => 'Front\Site\SitePageController@delete']);
    Route::post('site/page/store',                  ['as' => 'site.pages.store',        'uses' => 'Front\Site\SitePageController@store']);

    Route::get('site/settings',                     ['as' => 'site.settings',                 'uses' => 'Front\Site\SiteSettingController@index']);
    Route::post('site/settings/uploadFavicon',      ['as' => 'site.settings.upload.favicon',  'uses' => 'Front\Site\SiteSettingController@uploadFavicon']);
    Route::post('site/settings/updateStatus',       ['as' => 'site.settings.update.status',   'uses' => 'Front\Site\SiteSettingController@updateStatus']);
    Route::post('site/settings/updateTitle',        ['as' => 'site.settings.update.title',    'uses' => 'Front\Site\SiteSettingController@updateTitle']);
    Route::post('site/settings/updateMetaInfo',     ['as' => 'site.settings.update.meta',     'uses' => 'Front\Site\SiteSettingController@updateMetaInfo']);
    Route::post('site/settings/updateUrl',          ['as' => 'site.settings.update.url',      'uses' => 'Front\Site\SiteSettingController@updateUrl']);
    Route::post('site/settings/checkSubdomain',     ['as' => 'site.settings.check.subdomain', 'uses' => 'Front\Site\SiteSettingController@checkSubDomain']);
    Route::post('site/settings/checkPointDomain',   ['as' => 'site.settings.check.pointdomain', 'uses' => 'Front\Site\SiteSettingController@checkPointDomain']);

    Route::get('site/store',                        ['as' => 'site.store',                  'uses' => 'Front\Site\SiteStoreController@index']);
    Route::get('site/store/categories',             ['as' => 'site.store.category.list',    'uses' => 'Front\Site\SiteStoreController@categories']);
    Route::get('site/store/category/delete/{id}',   ['as' => 'site.store.category.delete',  'uses' => 'Front\Site\SiteStoreController@categoryDelete']);
    Route::post('site/store/category/form',         ['as' => 'site.store.category.form',    'uses' => 'Front\Site\SiteStoreController@categoryForm']);
    Route::post('site/store/category/save',         ['as' => 'site.store.category.save',    'uses' => 'Front\Site\SiteStoreController@categorySave']);
    Route::post('site/store/category/status',       ['as' => 'site.store.category.update.status',   'uses' => 'Front\Site\SiteStoreController@categoryUpdateStatus']);
    Route::get('site/store/orders',                 ['as' => 'site.store.order.list',       'uses' => 'Front\Site\SiteStoreController@orders']);
    Route::get('site/store/products',               ['as' => 'site.store.product.list',     'uses' => 'Front\Site\SiteStoreController@products']);
    Route::get('site/store/product/delete/{id}',    ['as' => 'site.store.product.delete',   'uses' => 'Front\Site\SiteStoreController@productDelete']);
    Route::post('site/store/product/form',          ['as' => 'site.store.product.form',     'uses' => 'Front\Site\SiteStoreController@productForm']);
    Route::post('site/store/product/save',          ['as' => 'site.store.product.save',     'uses' => 'Front\Site\SiteStoreController@productSave']);
    Route::post('site/store/product/images',        ['as' => 'site.store.product.images',   'uses' => 'Front\Site\SiteStoreController@productImages']);
    Route::post('site/store/product/uploadImage',   ['as' => 'site.store.product.upload.image', 'uses' => 'Front\Site\SiteStoreController@productImageUpload']);
    Route::post('site/store/product/removeImage',   ['as' => 'site.store.product.remove.image', 'uses' => 'Front\Site\SiteStoreController@productImageDelete']);
    Route::post('site/store/product/setMainImage',  ['as' => 'site.store.product.main.image',   'uses' => 'Front\Site\SiteStoreController@productImageMakeMain']);
    Route::get('site/store/settings',               ['as' => 'site.store.settings',             'uses' => 'Front\Site\SiteStoreController@settingsGeneral']);
    Route::get('site/store/settings/checkout',      ['as' => 'site.store.settings.checkout',    'uses' => 'Front\Site\SiteStoreController@settingsCheckout']);
    Route::get('site/store/settings/tax',           ['as' => 'site.store.settings.tax',         'uses' => 'Front\Site\SiteStoreController@settingsTax']);
    Route::get('site/store/settings/shipping',      ['as' => 'site.store.settings.shipping',    'uses' => 'Front\Site\SiteStoreController@settingsShipping']);
    Route::get('site/store/settings/email',         ['as' => 'site.store.settings.email',       'uses' => 'Front\Site\SiteStoreController@settingsEmail']);

    Route::get('site/customize',                    ['as' => 'site.customize',                  'uses' => 'Front\Site\SiteCustomizeController@index']);
    Route::post('site/customize/updateColor',       ['as' => 'site.customize.update.color',     'uses' => 'Front\Site\SiteCustomizeController@updateColor']);
    Route::post('site/customize/updateTheme',       ['as' => 'site.customize.update.theme',     'uses' => 'Front\Site\SiteCustomizeController@updateTheme']);
                                                            
    // Transaction
    Route::get('transaction',                       ['as' => 'transaction.list',   'uses' => 'Front\TransactionController@index']);

    // Domain
    Route::get('mydomain',                          ['as' => 'domain.list',                'uses' => 'Front\DomainController@index']);         
    Route::get('mydomain/login/{id}',               ['as' => 'domain.login',               'uses' => 'Front\DomainController@login']);
    Route::get('mydomain/lock/{id}',                ['as' => 'domain.lock',                'uses' => 'Front\DomainController@lock']);
    Route::get('mydomain/assign/{id}',              ['as' => 'domain.assign',              'uses' => 'Front\DomainController@assign']);

    Route::post('mydomain/ajaxCheck',               ['as' => 'domain.ajax.check',          'uses' => 'Front\DomainController@ajaxCheck']);
    Route::post('mydomain/ajaxEmailSetup',          ['as' => 'domain.ajax.email.setup',    'uses' => 'Front\DomainController@ajaxEmailSetup']);
    Route::post('mydomain/ajaxSiteAssign',          ['as' => 'domain.ajax.site.assign',    'uses' => 'Front\DomainController@ajaxSiteAssign']);
    Route::post('mydomain/ajaxChangeRegisterStatus',['as' => 'domain.ajax.change.register.status', 'uses' => 'Front\DomainController@ajaxChangeRegisterStatus']);

    /*Route::post('domain/ajaxEmailLogin',    ['as' => 'domain.ajax.email.login',    'uses' => 'DomainController@ajaxEmailLogin']);*/

    // Invite
    Route::get('invite',            ['as' => 'invite.list',        'uses' => 'Front\InviteController@index']);
    Route::post('invite/postSend',  ['as' => 'invite.send.post',   'uses' => 'Front\InviteController@postSend']);

    // Feature
    Route::get('support',           ['as' => 'supports',   'uses' => 'Front\SupportController@index']);

    // Payment
    Route::get('payment/new',               ['as' => 'payment.new',                'uses' => 'Front\PaymentController@purchase']);
    Route::post('payment/postNew',          ['as' => 'payment.new.post',           'uses' => 'Front\PaymentController@postPurchase']);
    Route::get('payment/extend',            ['as' => 'payment.extend',             'uses' => 'Front\PaymentController@extend']);
    Route::post('payment/postExtend',       ['as' => 'payment.extend.post',        'uses' => 'Front\PaymentController@postExtend']);
    Route::get('payment/privacy',           ['as' => 'payment.privacy',            'uses' => 'Front\PaymentController@privacy']);
    Route::post('payment/postPrivacy',      ['as' => 'payment.privacy.post',       'uses' => 'Front\PaymentController@postPrivacy']);
    Route::get('payment/privacyExtend',     ['as' => 'payment.privacy.extend',     'uses' => 'Front\PaymentController@privacyExtend']);
    Route::post('payment/postPrivacyExtend',['as' => 'payment.privacy.extend.post','uses' => 'Front\PaymentController@postPrivacyExtend']);

    Route::post('payment/paypalCallbackPurchase',       ['as' => 'payment.callback.paypal.purchase',   'uses' => 'Front\PaymentController@paypalCallbackPurchase']);
    Route::post('payment/paypalCallbackExtend',         ['as' => 'payment.callback.paypal.extend',     'uses' => 'Front\PaymentController@paypalCallbackExtend']);
    Route::post('payment/paypalCallbackPrivacy',        ['as' => 'payment.callback.paypal.privacy',    'uses' => 'Front\PaymentController@paypalCallbackPrivacy']);
    Route::post('payment/paypalCallbackPrivacyExtend',  ['as' => 'payment.callback.paypal.privacy.extend',    'uses' => 'Front\PaymentController@paypalCallbackPrivacyExtend']);

    // Admin Routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        
        Route::get('/', 'Admin\AuthController@index');
        
        // -- Auth
        Route::get('login',                     ['as' => 'login',         'uses' => 'Admin\AuthController@login']);
        Route::post('postLogin',                ['as' => 'login.post',    'uses' => 'Admin\AuthController@postLogin']);
        Route::get('logout',                    ['as' => 'logout',        'uses' => 'Admin\AuthController@logout']);
        Route::get('forgot-password',           ['as' => 'forgot',        'uses' => 'Admin\AuthController@forgotPassword']);
        Route::post('postForgotPassword',       ['as' => 'forgot.post',   'uses' => 'Admin\AuthController@postForgotPassword']);
        Route::get('reset-password/{token?}',   ['as' => 'reset',         'uses' => 'Admin\AuthController@resetPassword']);
        Route::post('postResetPassword',        ['as' => 'reset.post',    'uses' => 'Admin\AuthController@postResetPassword']);
        
        // -- Account
        Route::get('account',                   ['as' => 'profile',       'uses' => 'Admin\AccountController@index']);
        Route::post('account/postIndex',        ['as' => 'profile.post',  'uses' => 'Admin\AccountController@postIndex']);
        
        // --Dashboard   
        Route::get('dashboard',                 ['as' => 'dashboard', 'uses' => 'Admin\DashboardController@index']);
        
        // --Dashboard   
        Route::get('themes',                    ['as' => 'theme.list',      'uses' => 'Admin\ThemeController@index']);
        Route::get('theme/extract',             ['as' => 'theme.extract',   'uses' => 'Admin\ThemeController@extractTemplate']);
        
        // -- Users
        Route::group(['prefix' => 'member', 'as' => 'member.'], function () {
            Route::get('',                  ['as' => 'list',   'uses' => 'Admin\MemberController@index']);
            Route::get('create',            ['as' => 'create', 'uses' => 'Admin\MemberController@create']);
            Route::get('edit/{id}',         ['as' => 'edit',   'uses' => 'Admin\MemberController@edit']);
            Route::get('delete/{id}',       ['as' => 'delete', 'uses' => 'Admin\MemberController@delete']);
            Route::post('store',            ['as' => 'store',  'uses' => 'Admin\MemberController@store']);
            Route::post('ajaxCheckEmail',   ['as' => 'ajax.check.email', 'uses' => 'Admin\MemberController@ajaxCheckEmail']);
        });
    });
}    

