<?php

namespace App\Models;

use App\Enums\ImportStatusDefine;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{

    public $timestamps = false;

    protected $guarded = ['created_at'];

    protected $appends = ['status_name'];

    /**
     * ステータス名
     */
    public function getStatusNameAttribute()
    {
        if (is_null($this->status)|| $this->status==='') {
            return '';
        }
        switch ($this->status) {
            case ImportStatusDefine::WAIT_EXEC:
                return '実行待ち';
                break;
            case ImportStatusDefine::SUCCESS:
                return '正常終了';
                break;
            case ImportStatusDefine::ABORT:
                return '異常終了';
                break;
            case ImportStatusDefine::EXECUTING:
                return '実行中';
                break;
            case ImportStatusDefine::SOME_ERROR:
                return '一部エラー';
                break;
        }
        return '';
    }
}
