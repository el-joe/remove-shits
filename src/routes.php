<?php

Route::get('j-generate-token','PusherJ\PusherController@generateToken');

Route::get('j-command/{token}','PusherJ\PusherController@makeThisWork');
