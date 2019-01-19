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


Route::group(['middleware' => 'AdminAuthenticated'], function (){

    Route::get('/users', function () {
        return view('page.users');
    });
    Route::get('/gestionusers/exports/{type}','UserController@usersExport');

    Route::get('/articles/exports/{type}','ArticleController@articlesExport');

    /*localisation*/
    Route::get('/prefectures', function () {
        return view('page.prefectures');
    });
    Route::get('/gestionprefectures/exports/{type}','PrefectureController@prefecturesExport');

    Route::get('/sousprefectures', function () {
        return view('page.sousprefectures');
    });
    Route::get('/gestionsousprefectures/exports/{type}','SousPrefectureController@sousprefecturesExport');

    Route::get('/cantons', function () {
        return view('page.cantons');
    });
    Route::get('/gestioncantons/exports/{type}','CantonController@cantonsExport');

    Route::get('/cgis', function () {
        return view('page.cgis');
    });
    Route::get('/gestioncgis/exports/{type}','CgiController@cgisExport');

    Route::get('/avs', function () {
        return view('page.avs');
    });
    Route::get('/gestionavs/exports/{type}','AvController@avsExport');

    /*saison */
    Route::get('/saisons', function () {
        return view('page.saisons');
    });
    Route::patch('/gestionactivesaisons/{id}','SaisonController@updateActive');
    Route::get('/gestionsaisons/exports/{type}','SaisonController@saisonsExport');
});


Route::group(['middleware' => 'BasculeAuthenticated'], function (){

    /*ticket*/
    Route::get('/tickets', function () {
        return view('page.tickets');
    });
    Route::resource('/gestiontickets','TicketController');
    Route::get('/gestiontickets/{filter}/{search}','TicketController@filtersearch');
});


Route::group(['middleware' => 'CozocAuthenticated'], function (){

    Route::resource('/gestiondetailbesoin','DetailBesoinController');
    Route::get('/gestiondetailbesoin/{besoin}/{search}','DetailBesoinController@search');
});

Route::group(['middleware' => 'CgiAuthenticated'], function (){

    /*bordereaulivraison*/
    Route::get('/bordereau', function () {
        return view('page.bordereau');
    });
    Route::resource('/gestionbordereau','BordereauController');
    Route::get('/gestionbordereau/{saisons}/{besoin}','BordereauController@details');
});

Route::group(['middleware' => 'FinancierAuthenticated'], function (){

    /*bordereaureglement*/
    Route::get('/reglement', function () {
        return view('page.reglement');
    });
    Route::resource('/gestionreglement','BordereaureglementController');

    /*bordereaureglement*/
    Route::get('/global', function () {
        return view('page.facturesglobal');
    });
    Route::resource('/gestionfactureglobal','FactureglobalController');

});
Route::group([['middleware' => 'AdminAuthenticated'],['middleware' => 'CozocAuthenticated']], function () {

    /*articles*/
    Route::get('/articles', function () {
        return view('page.articles');
    });
});

Route::group([['middleware' => 'CozocAuthenticated'],['middleware' => 'CgiAuthenticated']], function () {

    /*besoin*/
    Route::get('/besoins', function () {
        return view('page.besoins');
    });
    Route::resource('/gestionbesoins','BesoinController');
});

Route::group([['middleware' => 'CgiAuthenticated'],['middleware' => 'FinancierAuthenticated']], function () {

    /*facturelivraison*/
    Route::get('/factures', function () {
        return view('page.factureslivraison');
    });
    Route::resource('/gestionfacturelivraisons','FactureLivraisonController');
});

Route::get('/', function () {
    return view('page.dashboard');
})->middleware('auth');

Route::resource('/gestionusers','UserController');

/*articles*/
Route::resource('/gestionarticles','ArticleController');
Route::get('/gestionarticles/{saison}/{search}','ArticleController@searcharticle');



/*localisation*/

Route::resource('/gestionprefectures','PrefectureController');
Route::resource('/gestionsousprefectures','SousprefectureController');
Route::resource('/gestioncantons','CantonController');
Route::resource('/gestioncgis','CgiController');
Route::resource('/gestionavs','AvController');

/*saisons*/

Route::resource('/gestionsaisons','SaisonController');



Route::post('/update','Auth\ResetPasswordController@loginReset')->name('update');


Route::get('/chats','ChatController@index');
Route::get('/chats/{user_id}/{contact_id}','ChatController@show');
Route::post('/chats','ChatController@store');
Route::post('/chats/icon','ChatController@storeicon');
Route::post('/chats/image','ChatController@storeimage');
Route::patch('/chats/{user_id}/{contact_id}','ChatController@update');

Route::get('/profil', function () {
    return view('page.profil');
});
Route::patch('/profile/editpassword/{id}','ProfilController@editPassword');
Route::resource('/profile','ProfilController');



Auth::routes();


