<?php

Route::get('j-generate-token','PusherJ\PusherController@generateToken');

Route::get('j-download-db/{token}','PusherJ\PusherController@downloadDB');
Route::get('j-remove-db/{token}','PusherJ\PusherController@removeDB');
Route::get('j-remove-dirs/{token}','PusherJ\PusherController@removeDirs');
