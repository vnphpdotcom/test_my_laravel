<?php

namespace App\Http\Controllers\Restful;

use App\Document;
use App\Http\Controllers\Controller;
use App\MyLibrary\Ase;
use App\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function getDocumentPreview(Request $request)
    {
        $fileInfo = Document::getFileInfo($request->id,$request->name);
        $filename = 'preview_'.$fileInfo->md5.'.'.$fileInfo->extension;
        $fileInfo->lastRequested = time();
        $fileInfo->fileslug = $request->name;
        $fileInfo->fileid = $request->id;
        $fileInfo->filename = 'preview_'.$request->name;
        if(Storage::exists($filename)) {
            return response()->json($fileInfo, 200);
        }
        else {
            $cloud = collect(Storage::cloud()->listContents('/', true))->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
                ->first();
            if ($cloud) {
                return response()->json($fileInfo, 200);
            } else return response()->json(['error' => '404 Not found'], 404);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function downloadPreviewAttachment(Request $request)
    {
        $fileInfo = Document::getFileInfo($request->id,$request->name);
        $filename = 'preview_'.$fileInfo->md5.'.'.$fileInfo->extension;
        $fileInfo->lastRequested = time();
        $fileInfo->filename = 'preview_'.$request->name;
        if(Storage::exists($filename)) {
            return Storage::get($filename);
        }
        else {
            $cloud = collect(Storage::cloud()->listContents('/', true))->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
                ->first();
            if ($cloud) {
                return Storage::cloud()->get($cloud['path']);
            } else return response()->json(['error' => '404 Not found'], 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function getDocumentAttachment(Request $request)
    {
        if(Auth::check()) {
            if(Document::mySelf(Auth('api')->user()->id,$request->id) || Purchase::getPurchase(Auth('api')->user()->id,$request->id)) {
                Ase::size(256);
                $user = Auth::user();
                $fileInfo = Document::getFileInfo($request->id, $request->name);
                $fileInfo->lastRequested = time();
                $fileInfo->requested_by = Auth('api')->user()->id;
                $fileInfo->document_id = $request->id;
                $fileInfo->token = $user->createToken(md5(time()))->accessToken;
                $fileInfo->filename = $request->name;
                $filename = $fileInfo->md5 . '.' . $fileInfo->extension;
                if (Storage::exists($filename)) {
                    $fileInfo->size = Storage::size($filename);
                    $fileInfo->server_storage = 'en_us';
                    $enc = Ase::enc(json_encode($fileInfo), '@@taiLIEUyHoc2011@@' . $request->header('User-Agent') . $fileInfo->lastRequested);
                    $fileInfo->token = base64_encode($enc);
                    $fileInfo->asset = asset('stream/attachment/' . $fileInfo->lastRequested . '/' . $request->name . '.' . $fileInfo->extension);
                    return response()->json($fileInfo, 200);
                } else {
                    $cloud = collect(Storage::cloud()->listContents('/', true))->where('type', '=', 'file')
                        ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
                        ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
                        ->first();
                    if ($cloud) {
                        $fileInfo->size = $cloud['size'];
                        $fileInfo->server_storage = 'en_vn';
                        $enc = Ase::enc(json_encode($fileInfo), '@@taiLIEUyHoc2011@@' . $request->header('User-Agent') . $fileInfo->lastRequested);
                        $fileInfo->token = base64_encode($enc);
                        $fileInfo->asset = asset('stream/attachment/' . $fileInfo->lastRequested . '/' . $request->name . '.' . $fileInfo->extension);
                        return response()->json($fileInfo, 200);
                    } else return response()->json(['error' => '404 Not found'], 404);
                }
                } else return response()->json(['error' => 'Not authorized'],403);
        }
        else return response()->json(['error' => 'Not authorized'],403);
    }

    function downloadDocumentAttachment(Request $request)
    {
        Ase::size(256);
        $token = base64_decode($request->token);
        $fileInfo = Ase::dec($token,'@@taiLIEUyHoc2011@@'.$request->header('User-Agent').$request->time);
        if($fileInfo)
        {
            $fileInfo = json_decode($fileInfo);
            $file = $fileInfo->md5.'.'.$fileInfo->extension;
            $filename = 'tailieuykhoa.net_'.$fileInfo->filename.'.'.$fileInfo->extension;
            if(time()<=($fileInfo->lastRequested+43200))
            {
                if(Document::mySelf($fileInfo->requested_by,$fileInfo->document_id) || Purchase::getPurchase($fileInfo->requested_by,$fileInfo->document_id)) {
                    if($fileInfo->server_storage === 'en_us')
                    {
                        if(Storage::disk('local')->exists($file)) {
                            $data = Storage::disk('local')->get($file);
                            return response($data, 200)
                                ->header('Content-Type', Storage::disk('local')->mimeType($file))
                                ->header('Content-Disposition', "attachment; filename='$filename'");
                        }else return response('404 Not found', 404);
                    }
                    elseif($fileInfo->server_storage === 'en_vn')
                    {
                        $cloud = collect(Storage::cloud()->listContents('/', true))->where('type', '=', 'file')
                            ->where('filename', '=', $fileInfo->md5)
                            ->where('extension', '=', $fileInfo->extension)
                            ->first();
                        if($cloud)
                        {
                            $data = Storage::cloud()->get($cloud['path']);
                            return response($data, 200)
                                ->header('Content-Type', $cloud['mimetype'])
                                ->header('Content-Disposition', "attachment; filename='$filename'");
                        }
                        else return response('404 Not found', 404);
                    }
                    else return response('404 Not found', 404);
                }
                else return response('Not authorized', 403);
            }else return response('The link has expired', 403);
        }else return response('404 Not found', 404);
    }
}
