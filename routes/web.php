<?php

Route::get('/', ['as'=>'main', 'uses'=>'MainController@index']);
Route::get('search', ['as'=>'search','uses'=>'MainController@search']);
Route::get('products/{category_id}', ['as'=>'categoryProducts','uses'=>'MainController@products_in_category']);
