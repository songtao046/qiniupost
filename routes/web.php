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


Route::get('getToken', 'QiniuController@getUploadToken');

Route::get('testToken', function () {
    $csrf_token = csrf_token();
    $form = <<<FORM
    <form action="/getToken" method="GET">
        <input type="hidden" name="_token" value="{$csrf_token}">
        <input name="code" type="hidden" value="14138100">
        <input name="type" type="text">
        <input type="submit" value="Test"/>
    </form>
FORM;
    return $form;
});


Route::get('testPost', function () {
   $form = <<<FORM
    <form action="http://up-as0.qiniup.com/" method="POST" enctype="multipart/form-data">
        <input name="key" type="hidden" value="<resource_key>">
        <input name="x:<user_id>" type="hidden" value="1">
        <input name="token" type="hidden" value="aMarU4280TyakIHt1udQcLs9LwpS6lUhiR3DuNdS:QyJcnRI3i8zbHLq8U0GPMzdBv7s=:eyJjYWxsYmFja1VybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hcGlcL3Fpbml1XC91cGxvYWRDYWxsYmFjayIsImNhbGxiYWNrQm9keSI6IntcImtleVwiOlwiJChrZXkpXCIsXCJoYXNoXCI6XCIkKGV0YWcpXCIsXCJ3XCI6XCIkKGltYWdlSW5mby53aWR0aClcIiwgXCJ1c2VyX2lkXCI6XCIkKHg6dXNlcl9pZClcIn0iLCJjYWxsYmFja0JvZHlUeXBlIjoiYXBwbGljYXRpb25cL2pzb24iLCJzY29wZSI6Im1hZ25ldCIsImRlYWRsaW5lIjoxNTYzOTYyMjAxfQ=="
}>
        <!--<input name="crc32" type="hidden">-->
        <input name="file" type="file">
        <br>
        <input type="submit" value="上传"/>
    </form>
FORM;
    return $form;

});

Route::post('/qiniu/uploadCallback', 'QiniuController@uploadCallback');