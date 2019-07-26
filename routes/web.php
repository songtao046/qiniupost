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
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});


Route::get('getToken', 'QiniuController@getUploadToken');

Route::get('testToken', function () {
    $csrf_token = csrf_token();
    $form = <<<FORM
    <form action="/getToken" method="GET">
        <input type="hidden" name="_token" value="{$csrf_token}">
        <input name="code" type="hidden" value="14138108">
        <input name="type" type="text">
        <input type="submit" value="Test"/>
    </form>
FORM;
    return $form;
});


Route::get('testPost', ['as' => 'testPost', function (Request $request) {
    $csrf_token = csrf_token();
   $form = <<<FORM
    <form action="http://up-as0.qiniup.com/" method="POST" enctype="multipart/form-data">
        <input name="x:<user_id>" type="hidden" value="1">
        <input type="hidden" name="_token" value="{$csrf_token}">
        <input name="token" type="hidden" value="{$request->input('token')}">
        <input name="file" type="file">
        <br>
        <input type="submit" value="上传"/>
    </form>
FORM;
    return $form;

}]);

Route::post('/qiniu/uploadCallback', 'QiniuController@uploadCallback');
