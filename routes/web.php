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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function () {
    // Build the query parameter string to pass auth information to our request
    $query = http_build_query([
        'client_id' => 3,
        'redirect_uri' => 'http://localhost:8000/callback',
        'response_type' => 'code',
        'scope' => 'conference'
    ]);

    // Redirect the user to the OAuth authorization page
    return redirect('http://localhost:8000/oauth/authorize?' . $query);
});

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://localhost:8000/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 3, // from admin panel above
            'client_secret' => 'weH1iUWUexmlYg4PFHZjqJoUy6WnZebjK2bavlGW', // from admin panel above
            'redirect_uri' => 'http://localhost:8000/callback',
            'code' => $request->code // Get code from the callback
        ]
    ]);

    // echo the access token; normally we would save this in the DB
    return json_decode((string) $response->getBody(), true)['access_token'];
});
