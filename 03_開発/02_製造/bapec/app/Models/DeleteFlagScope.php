<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\FlagDefine;

class DeleteFlagScope extends SoftDeletingScope
{
    /**
     * デリートフラグがOFFのものだけ取得するスコープ
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where($model->getQualifiedDeletedAtColumn(), FlagDefine::OFF);
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            $column = $this->getDeletedAtColumn($builder);

            return $builder->update([
                $column => FlagDefine::ON,
            ]);
        });
    }

    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([$builder->getModel()->getDeletedAtColumn() => FlagDefine::OFF]);
        });
    }

    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder
                ->withoutGlobalScope($this)
                ->where($model->getQualifiedDeletedAtColumn(), FlagDefine::OFF);

            return $builder;
        });
    }

    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder
                ->withoutGlobalScope($this)
                ->where($model->getQualifiedDeletedAtColumn(), FlagDefine::ON);

            return $builder;
        });
    }
}
