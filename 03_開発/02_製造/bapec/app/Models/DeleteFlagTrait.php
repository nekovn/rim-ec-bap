<?php
namespace App\Models;

use App\Enums\FlagDefine;
use App\Models\DeleteFlagScope;

/**
 * SoftDeltesを継承して、flagで論理削除を制御する
 */
trait DeleteFlagTrait
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    public static function bootSoftDeletes()
    {
        //SoftDeletes無効
    }

    public static function bootDeleteFlagTrait()
    {
        static::addGlobalScope(new DeleteFlagScope());
    }

    public function initializeSoftDeletes()
    {
        // 日付列でないため無効
    }

    /**
     * 論理削除処理
     */
    protected function runSoftDelete()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $columns = [$this->getDeletedAtColumn() => FlagDefine::ON];

        $this->{$this->getDeletedAtColumn()} = FlagDefine::ON;

        $query->update($columns);
    }

    /**
     * 論理削除復帰処理
     */
    public function restore()
    {
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = FlagDefine::OFF;

        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
    }

    /**
     * 論理削除されているかどうか確認する
     *
     * @return bool
     */
    public function trashed()
    {
        return (1 == $this->{$this->getDeletedAtColumn()});
    }

    /**
     * デリートフラグのカラム名を返す
     *
     * @return string
     */
    public function getDeletedAtColumn()
    {
        return 'is_deleted';
    }
}
