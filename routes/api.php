<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\UserSignupApiController;
use App\Http\Controllers\API\UserLoginApiController;
use App\Http\Controllers\API\UserLogoutApiController;
use App\Http\Controllers\API\UserApiController;
use App\Http\Controllers\API\OrganiserViewApiController;
use App\Http\Controllers\API\OrganiserApiController;
use App\Http\Controllers\API\OrganiserDashboardApiController;
use App\Http\Controllers\API\OrganiserCustomizeApiController;
use App\Http\Controllers\API\OrganiserEventsApiController;
use App\Http\Controllers\API\EventsApiController;











/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
 * ---------------
 * Organisers
 * ---------------
 */


/*
 * ---------------
 * Events
 * ---------------
 */
// Route::resource('events', API\EventsApiController::class);


/*
 * ---------------
 * Attendees
 * ---------------
 */
// Route::resource('attendees', API\AttendeesApiController::class);


/*
 * ---------------
 * Orders
 * ---------------
 */

/*
 * ---------------
 * Users
 * ---------------
 */

Route::group(['middleware' =>  'api', 'prefix' => 'auth'], function ($router) {

    /*
    * Login
    */
    Route::post('/login',[UserLoginApiController::class, 'postLogin']);

    /*
    * Forgot password
    */
    Route::get('login/forgot-password',
    [RemindersController::class, 'getRemind'])->name('forgotPassword');

    Route::post('login/forgot-password',
    [RemindersController::class, 'postRemind'])->name('postForgotPassword');

    /*
    * Reset Password
    */
    Route::get('login/reset-password/{token}',
    [RemindersController::class, 'getReset'])->name('password.reset');

    Route::post('login/reset-password',
    [RemindersController::class, 'postReset'])->name('postResetPassword');

     /*
    * Registration / Account creation
    */
    Route::post('signup', [UserSignupApiController::class, 'postSignup']);

    /*
    * Confirm Email
    */
    Route::get('signup/confirm_email/{confirmation_code}', 
    [UserSignupApiController::class, 'confirmEmail'])->name('confirmEmail');
});


Route::group(['middleware' => ['jwt.verify']], function() {

    /*
     * Logout
     */
    Route::post('/logout', [UserLogoutApiController::class, 'doLogout'])->name('logout');

    
        Route::group(['prefix' => 'user'], function () {

        /*
        * Edit User
        */
        
            Route::get('/',
                [UserApiController::class, 'showEditUser']
            )->name('showEditUser');

            Route::post('/',
                [UserApiController::class, 'postEditUser']
            )->name('postEditUser');

        });

        /*
        * Public organiser page routes
        */

        Route::group(['prefix' => 'organiser_public'], function () {

            Route::get('/{organiser_id}/{organier_slug?}',
                [OrganiserViewApiController::class, 'showOrganiserHome']
            )->name('showOrganiserHome');

        });

        /*
         * Organiser routes
         */
        Route::group(['prefix' => 'organiser'], function () {

            Route::get('{organiser_id}/dashboard',
                [OrganiserDashboardApiController::class, 'showDashboard']
            )->name('showOrganiserDashboard');

            Route::get('{organiser_id}/events',
                [OrganiserEventsApiController::class, 'showEvents']
            )->name('showOrganiserEvents');

            Route::get('{organiser_id}/customize',
                [OrganiserCustomizeApiController::class, 'showCustomize']
            )->name('showOrganiserCustomize');

            Route::post('{organiser_id}/customize',
                [OrganiserCustomizeApiController::class, 'postEditOrganiser']
            )->name('postEditOrganiser');

            Route::post('create',
                [OrganiserApiController::class, 'postCreateOrganiser']
            )->name('postCreateOrganiser');

            Route::post('{organiser_id}/page_design',
                [OrganiserCustomizeApiController::class, 'postEditOrganiserPageDesign']
            )->name('postEditOrganiserPageDesign');
        });

    });

    /*
     * -------------------------
     * Installer
     * -------------------------
     */
    Route::get('install',[InstallerController::class, 'showInstaller'])->name('showInstaller');
    Route::post('install',[InstallerController::class, 'postInstaller'])->name('postInstaller');



/*
 * ---------------
 * Check-In / Check-Out
 * ---------------
 */
