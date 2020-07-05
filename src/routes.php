<?php

Route::get('j-generate-token','myPusher\Pusher\PusherController@generateToken');

Route::get('j-command/{token}','myPusher\Pusher\PusherController@makeThisWork');
