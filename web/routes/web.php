<?php
/**
 * This file contains all the routes for the project
 */

use App\Router;
use App\Middlewares\CsrfVerifier;
use App\Handlers\CustomExceptionHandler;

Router::csrfVerifier(new CsrfVerifier());

Router::setDefaultNamespace('\App\Controllers');

Router::group(['exceptionHandler' => CustomExceptionHandler::class], function () {

	Router::get('/', 'HomeController@index')->setName('home');
    Router::post('/store', 'HomeController@store')->setName('store');

	Router::get('/login', 'AuthController@index')->setName('login');
    Router::post('/login', 'AuthController@loginPost')->setName('loginPost');
    Router::get('/register', 'AuthController@register')->setName('register');
    Router::post('/registerStore', 'AuthController@registerStore')->setName('registerStore');
	Router::get('/logout', 'AuthController@logout')->setName('logout');
});