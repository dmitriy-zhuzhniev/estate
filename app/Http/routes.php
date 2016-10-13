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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::group(['middleware' => 'authorized'], function () {
    Route::resource('/', 'ApartmentController');
    Route::resource('{id}/parse', 'ApartmentController@parse');
});

Route::group(['middleware' => ['web']], function () {
    /*
    * Route for auth system
    */
    // Вызов страницы регистрации пользователя
    Route::get('register', 'AuthController@register');
    // Пользователь заполнил форму регистрации и отправил
    Route::post('register', 'AuthController@registerProcess');
    // Пользователь получил письмо для активации аккаунта со ссылкой сюда
    Route::get('activate/{id}/{code}', 'AuthController@activate');
    // Вызов страницы авторизации
    Route::get('login', 'AuthController@login');
    // Пользователь заполнил форму авторизации и отправил
    Route::post('login', 'AuthController@loginProcess');
    // Выход пользователя из системы
    Route::get('logout', 'AuthController@logoutuser');
    // Пользователь забыл пароль и запросил сброс пароля. Это начало процесса -
    // Страница с запросом E-Mail пользователя
    Route::get('reset', 'AuthController@resetOrder');
    // Пользователь заполнил и отправил форму с E-Mail в запросе на сброс пароля
    Route::post('reset', 'AuthController@resetOrderProcess');
    // Пользователю пришло письмо со ссылкой на эту страницу для ввода нового пароля
    Route::get('reset/{id}/{code}', 'AuthController@resetComplete');
    // Пользователь ввел новый пароль и отправил.
    Route::post('reset/{id}/{code}', 'AuthController@resetCompleteProcess');
    // Сервисная страничка, показываем после заполнения рег формы, формы сброса и т.
    // о том, что письмо отправлено и надо заглянуть в почтовый ящик.
    Route::get('wait', 'AuthController@wait');

    Route::get('cities', ['as' => 'json.cities', 'uses' => 'CityController@jsonAll']);
    Route::get('districts', ['as' => 'json.districts', 'uses' => 'DistrictController@jsonAll']);
    Route::get('streets', ['as' => 'json.streets', 'uses' => 'StreetController@jsonAll']);

//    Route::get('setDistricts', 'ApartmentController@setDistricts');
//    Route::get('setStreets', 'ApartmentController@setStreets');
});