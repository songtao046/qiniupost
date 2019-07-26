<?php

namespace App\Http\Controllers;

use App\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;
use PHPUnit\Util\RegularExpression;
use Qiniu\Storage\ArgusManager;

use App\Image;
use App\ImageSet;
use zgldh\QiniuStorage\QiniuStorage;



class QiniuController extends Controller
{
    public function getUploadToken(Request $request)
    {

        //图片类型 type: avatars-用户头像 covers-小说封面 image-小说链接图片 stamp-表情 card-卡片 book-特集书籍 other-其他
        $type = $request->has('type') ? $request->input('type') : 'image';
        $key = '/storage/app/public/'.$type.'/'.Str::random(40);
        if ($type == 'avatars') {
            $callBody = '{"key":"'.$key.'","hash":"$(etag)","w":"$(imageInfo.width)", "h":"$(imageInfo.height)", "user_id":"$(x:user_id)"}';
        } elseif ($type == 'covers') {
            $callBody = '{"key":"'.$key.'","hash":"$(etag)","w":"$(imageInfo.width)", "h":"$(imageInfo.height)", "novel_id":"$(x:novel_id)"}';
        } else {
            $callBody = '{"key":"'.$key.'","hash":"$(etag)","w":"$(imageInfo.width)", "h":"$(imageInfo.height)"}';
        }

        $disk = QiniuStorage::disk('qiniu');
        $policy['callbackUrl'] = 'http://35.200.68.27/qiniu/uploadCallback';
        $policy['callbackBody'] = $callBody;
        $policy['callbackBodyType'] = 'application/json';
        $policy['mineLimit'] = 'image/*';

        $token = $disk->uploadToken(null, 3600, $policy);
        return redirect()->route('testPost', ['token' => $token, 'key' => $key]);
//        return [
//            'status' => 'ok',
//            'token' => $token,
//            'key' => $key,
//        ];
    }


    public function uploadCallback(Request $request)
    {
        $key = $this->requestValueOfKey($request, 'key');
        $hash = $this->requestValueOfKey($request, 'hash');
        $w = $this->requestValueOfKey($request, 'w', 'no width');
        $h = $this->requestValueOfKey($request, 'h', 'no height');

        //图片类型 avatar cover image
        $type = $this->requestValueOfKey($request, 'type', 'image');
        if ($type == 'avatars') {
            $user_id = $this->requestValueOfKey($request, 'user_id');

            if ($user_id == 'null') {
                Log::error('type: '.$type.' user_id: '.$user_id.' user_id is Invalid');
                return $this->result('err', 'invalid user id');
            }
            User::where('id', $request->user_id)->update(['cover_url' => 'avatar@'.$hash]);
            return $this->result('ok', $key.$hash);
        } elseif ($type == 'cover') {
            $novel_id = $this->requestValueOfKey($request, 'novel_id');

            if ($novel_id == 'null') {
                Log::error('type: '.$type.' novel_id: '.$novel_id.' novel_id is Invalid');
                return $this->result('err', 'invalid novel id');
            }

            Image::where('id', $request->novel_id)->update(['cover_url' => 'avatar@'.$hash]);
            return $this->result('ok', $key.$hash);
        } else {
            return $this->result('ok', $key.'***'.$hash);
        }
    }


    private function requestValueOfKey(Request $request, $key, $default = 'null')
    {
        return $request->has($key) ? $request->input($key) : $default;
    }

    private function result(string $status, string $info)
    {
        return ['status' => $status, 'info' => $info];
    }



}
