<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $table = 'documents';
    protected $guarded = [];

    protected static function getFileInfo($id, $name)
    {
        return Document::select('md5','extension','created_by')->where([
            ['id', $id],
            ['ascii', $name]
        ])->first();
    }

    protected static function mySelf($user_id,$document_id)
    {
        return Document::select('id')->where([
            ['created_by', $user_id],
            ['id', $document_id]
        ])->first();
    }
}
