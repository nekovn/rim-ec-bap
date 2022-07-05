<?php

namespace App\Models\Relations;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use \Illuminate\Database\Eloquent\Builder;

class HasTax extends HasOne
{
    protected $dateColumn;

    /**
     * Create a new has one or many relationship instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @param  string  $dateColumn
     * @return void
     */
    public function __construct(Builder $related, $parent, $foreignKey, $localKey, $dateColumn)
    {
        $this->dateColumn = $dateColumn;

        parent::__construct($related->newQuery(), $parent, $foreignKey, $localKey);
    }

    protected function getDate()
    {
        if ($this->dateColumn) {
            return $this->dateColumn;
            // return $this->parent->{$this->dateColumn};
        } else {
            return Carbon::today();
        }
    }

    public function addConstraints()
    {
        if (static::$constraints) {
            $this->query->whereDate('start_date', '<=', $this->getDate())->where($this->foreignKey, '=', $this->parent->{$this->localKey})->orderByDesc('start_date');
        }
    }

    public function addEagerConstraints(array $models)
    {
        $this->query->where(function (Builder $query) use ($models) {
            foreach ($models as $model) {
                $query->orWhere(function (Builder $query) use ($model) {
                    $query->whereDate('start_date', '<=', $this->getDate())->where($this->foreignKey, '=', $model->{$this->localKey})->orderByDesc('start_date');
                });
            }
        });
    }

    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, null);
        }
        return $models;
    }

    public function getResults()
    {
        return $this->query->first();
    }
}