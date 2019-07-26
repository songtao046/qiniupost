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




Route::group(['middleware' => 'cors'], function () {

    Route::get('getToken', 'QiniuController@getUploadToken');

    Route::post('qiniu/uploadCallback', 'QiniuController@uploadCallback');


    Route::get('testCallback', function () {
        $form = <<<FORM
    <form action="http://35.200.68.27/qiniu/uploadCallback" method="POST">
        <input type="submit" value="上传"/>
    </form>
FORM;
        return $form;

    });
});






Route::get('testToken', function () {
    $form = <<<FORM
    <form action="/getToken" method="GET">
        <input name="code" type="hidden" value="14138108">
        <input name="type" type="text">
        <input type="submit" value="Test"/>
    </form>
FORM;
    return $form;
});


Route::get('testPost', ['as' => 'testPost', function (Request $request) {
    $form = <<<FORM
    <form action="http://up-as0.qiniup.com/" method="POST" enctype="multipart/form-data">
        <input name="x:<user_id>" type="hidden" value="1">
        <input name="token" value="{$request->input('token')}">
        <input name="key" value="{$request->input('key')}">
        <input name="file" type="file">
        <br>
        <input type="submit" value="上传"/>
    </form>
FORM;
    return $form;

}]);

