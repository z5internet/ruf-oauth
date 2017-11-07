<?php

$this->route->group(['prefix' => 'oauth'], function () {

	$this->route->get('/request',function() {

		return file_get_contents(base_path('public/assets/index.html'));

	});

	$this->route->post('/authorizeApi', 'z5internet\RufOAuth\App\Http\Controllers\Authorize@getAuthorizationFormInfo');

	$this->route->get('/authorize', 'z5internet\RufOAuth\App\Http\Controllers\Authorize@authorize');

	$this->route->get('/token', 'z5internet\RufOAuth\App\Http\Controllers\Authorize@issueToken');

});