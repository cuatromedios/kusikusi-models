<?php

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/
$router->get('/media/{entity_id}/{preset}[/{friendly}]', ['uses' => 'Kusikusi\Http\Controllers\MediaController@get']);

