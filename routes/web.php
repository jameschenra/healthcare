<?php

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

Route::get('/', 'User\HomeController@index')->name('home');
Route::get('/payment/terms', 'User\PaymentController@terms')->name('payment.terms');

// -------- User Routes ----------
Route::group(['namespace' => 'User', 'as' => 'user.'], function () {

    Route::get('/priceterms/{type}', 'HomeController@priceTerms')->name('priceterms');
    // Authentication
    Route::group(['as' => 'auth.'], function() {
        Route::get('/login', 'Auth\LoginController@showLoginForm')->name('showLogin');
        Route::post('/login', 'Auth\LoginController@login')->name('login');
        Route::get('/signup', 'Auth\RegisterController@showRegistrationForm')->name('showSignup');
        Route::post('/signup', 'Auth\RegisterController@register')->name('signup');

        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

        Route::get('/forgot-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgot-password.showLinkRequestForm');
        Route::post('/forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot-password.sendResetLinkEmail');
        Route::get('/forgot-password/{token}', 'Auth\ResetPasswordController@showResetForm')->name('forgot-password.showResetForm');
        Route::post('/forgot-password-process', 'Auth\ResetPasswordController@reset')->name('forgot-password.reset');
    });

    // contact us
    Route::get('/contactus', 'ContactController@index')->name('contactus');

    Route::group(['middleware' => 'auth'], function() {
        // profile
        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function(){
            Route::get('/', 'ProfileController@index')->name('index');
            Route::group(['middleware' => 'auth.primary'], function(){
                Route::get('/edit', 'ProfileController@edit')->name('edit');
                Route::put('/update', 'ProfileController@update')->name('update');
            });
            
        });
        
        // membership
        Route::group(['prefix' => 'membership', 'as' => 'membership.', 'middleware' => 'auth.primary'], function() {
            Route::get('/', 'MembershipController@index')->name('index');
            Route::get('/terms/{planTypeId}', 'MembershipController@terms')->name('terms');
            Route::get('/signup/{planTypeId}', 'MembershipController@signUp')->name('signup');

            Route::get('/create', 'MembershipController@create')->name('create');
            Route::post('/store', 'MembershipController@store')->name('store');
            Route::get('/edit/{pendingId}', 'MembershipController@edit')->name('edit');
            Route::put('/update', 'MembershipController@update')->name('update');
            Route::delete('/destroy/{pendingId}', 'MembershipController@delete')->name('destroy');

            Route::get('/plans', 'MembershipController@planSummary')->name('plans');
        });

        // payments
        Route::group(['prefix' => 'payments', 'as' => 'payments.', 'middleware' => 'auth.primary'], function(){
            Route::get('/', 'PaymentController@index')->name('index');
            Route::get('/paypal', 'PaymentController@paypal')->name('paypal');
            Route::get('/paypalSuccess', 'PaymentController@paypalSuccess')->name('paypalSuccess');
            Route::get('/cache', 'PaymentController@cache')->name('cache');
            Route::post('/stripe', 'PaymentController@stripe')->name('stripe');
            Route::get('/history', 'PaymentController@history')->name('history');
        });

        // members
        Route::group(['prefix' => 'members', 'as' => 'members.'], function(){
            Route::get('/', 'MemberController@index')->name('index');
            Route::group(['middleware' => 'auth.primary'], function(){
                Route::get('/planlist/{id}/{update_type}', 'MemberController@showUpgrade')->name('showupgrade');
                Route::post('/upgrade', 'MemberController@upgrade')->name('upgrade');
                Route::get('/upgrade/showterms', 'MemberController@showTerms')->name('upgrade.showterms');
                Route::get('/upgrade/showpayment', 'MemberController@showPayment')->name('upgrade.showpayment');
                Route::post('/upgrade/payment/stripe', 'MemberController@stripe')->name('upgrade.stripe');
                Route::post('/upgrade/payment/paypal', 'MemberController@paypal')->name('upgrade.paypal');
                Route::get('/upgrade/payment/paypalSuccess', 'MemberController@paypalSuccess')->name('upgrade.paypalsuccess');
                Route::post('/upgrade/payment/cache', 'MemberController@cache')->name('upgrade.cache');
            });
        });

        // pendings
        Route::group(['prefix' => 'pendings', 'as' => 'pendings.', 'middleware' => 'auth.primary'], function() {
            Route::get('/', 'MembershipController@pendings')->name('index');

            Route::get('/edit/{id}', 'MembershipController@pendings_edit')->name('edit');
            Route::put('/update', 'MembershipController@pendings_update')->name('update');
            Route::delete('/destroy/{id}', 'MembershipController@pendings_delete')->name('destroy');
        });
    });

    Route::get('/test', 'MemberController@test')->name('test');
    
});


// -------- Admin Routes ----------
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    // Authentication
    Route::group(['as' => 'auth.'], function() {
        Route::get('/login', 'Auth\LoginController@showLoginForm')->name('showLogin');
        Route::post('/login', 'Auth\LoginController@login')->name('login');
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });
    
    Route::group(['middleware' => 'auth:admins'], function () {
        Route::get('/', 'AdminController@home')->name('home');

        // Admin Users management
        Route::resource('adminusers','AdminUserController')->except('show')->names([
            'index' => 'adminusers.index',
            'create' => 'adminusers.create',
            'store' => 'adminusers.store',
            'edit' => 'adminusers.edit',
            'update' => 'adminusers.update',
            'destroy' => 'adminusers.destroy'
        ]);

        // Users management
        Route::resource('users','UserController')->except('show')->names([
            'index' => 'users',
            'create' => 'users.create',
            'edit' => 'users.edit',
            'store' => 'users.store',
            'update' => 'users.update',
            'destroy' => 'users.destroy'
        ]);

        // Pending members management
        Route::group(['prefix' => 'users/pendingmembers', 'as' => 'pendingmembers'], function(){
            Route::get('/', 'PendingMemberController@index')->name('');
            Route::get('/create', 'PendingMemberController@create')->name('.create');
            Route::post('/store', 'PendingMemberController@store')->name('.store');
            Route::get('/edit/{id}', 'PendingMemberController@edit')->name('.edit');
            Route::put('/update/{id}', 'PendingMemberController@update')->name('.update');
            Route::put('/update/{id}', 'PendingMemberController@update')->name('.update');
            Route::delete('/{id}', 'PendingMemberController@destroy')->name('.destroy');
        });

        // print card management
        Route::group(['prefix' => 'users/print', 'as' => 'users.print.'], function(){
            Route::post('/upload', 'PrintController@uploadPhoto')->name('uploadphoto');
            Route::get('/showprint/{id}',  'PrintController@showPrint')->name('showprint');
            Route::get('/{id}', 'PrintController@edit')->name('edit');
        });

        // Payment management
        Route::group(['prefix' => 'payment', 'as' => 'payment.'], function(){
            Route::get('/pendinglist/{id}', 'PaymentController@pendingList')->name('pendinglist');
            Route::post('/pendingcheckout', 'PaymentController@pendingCheckout')->name('pendingcheckout');
            Route::get('/upgradeinfo', 'PaymentController@upgradeInfo')->name('upgradeinfo');
            Route::get('/checkout', 'PaymentController@checkout')->name('checkout');
            Route::get('/cache', 'PaymentController@cache')->name('cache');
            Route::post('/stripe', 'PaymentController@stripe')->name('stripe');
            Route::get('/paypal', 'PaymentController@paypal')->name('paypal');
            Route::get('/paypalSuccess', 'PaymentController@paypalSuccess')->name('paypalSuccess');
        });

        // Membership track
        Route::group(['prefix' => 'membertrack', 'as' => 'membertrack.'], function(){
            Route::get('/admin', 'MemberTrackController@byAdmin')->name('admin');
            Route::get('/user', 'MemberTrackController@byUser')->name('user');
        });
        

        // Slides management
        Route::resource('slides','SlideController')->except('show')->names([
            'index' => 'slides',
            'create' => 'slides.create',
            'store' => 'slides.store',
            'edit' => 'slides.edit',
            'update' => 'slides.update',
            'destroy' => 'slides.destroy'
        ]);

        // Membership Plans management
        Route::resource('memberplantypes','MemberPlanTypeController')->except('show')->names([
            'index' => 'memberplantypes',
            'edit' => 'memberplantypes.edit',
            'update' => 'memberplantypes.update'
        ]);

        // Discounts management
        Route::resource('discounts','DiscountController')->except('show')->names([
            'index' => 'discounts.index',
            'create' => 'discounts.create',
            'store' => 'discounts.store',
            'edit' => 'discounts.edit',
            'update' => 'discounts.update',
            'destroy' => 'discounts.destroy'
        ]);

        // contents
        Route::group(['prefix' => 'contents', 'as' => 'contents.'], function() {
            Route::get('/contact', 'ContentController@contactContent')->name('contact.edit');
            Route::post('/contact', 'ContentController@updateContact')->name('contact.update');

            Route::get('/body/1', 'ContentController@bodyContent')->name('mission.edit');
            Route::get('/body/2', 'ContentController@bodyContent')->name('vision.edit');
            Route::get('/body/3', 'ContentController@bodyContent')->name('value.edit');

            Route::post('/body', 'ContentController@updateBodyContent')->name('body.update');

            Route::get('/wkhours', 'ContentController@workingHours')->name('wkhours.edit');
            Route::post('/wkhours', 'ContentController@updateWorkingHours')->name('wkhours.update');

            // Testmonials management
            Route::resource('testmonial','TestmonialController')->except('show')->names([
                'index' => 'testmonial.index',
                'create' => 'testmonial.create',
                'store' => 'testmonial.store',
                'edit' => 'testmonial.edit',
                'update' => 'testmonial.update',
                'destroy' => 'testmonial.destroy'
            ]);
        });

        // membership
        Route::group(['prefix' => 'pendings', 'as' => 'pendings.'], function() {
            Route::get('/', 'PaymentOfflineController@index')->name('index');

            Route::get('/approve/{id}', 'PaymentOfflineController@approve')->name('approve');
            Route::delete('/decline/{id}', 'PaymentOfflineController@delete')->name('delete');
        });
    });

});