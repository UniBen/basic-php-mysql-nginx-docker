<?php

use Framework\Router;

// Home
Router::get('/', 'HomeController@index');

// Pages
Router::get('/shops', 'PagesController@shops');
Router::get('/about', 'PagesController@about');

// Basket
Router::get('/basket/get', 'BasketController@get');
Router::post('/basket/add', 'BasketController@add');

// Films
Router::get('/films', 'Filmscontroller@index');
Router::get('/films/create', 'FilmsController@create');
Router::get('/films/{filmid([0-9]+)}', 'FilmsController@view');
Router::post('/films/{filmid([0-9]+)}', 'FilmsController@view');
