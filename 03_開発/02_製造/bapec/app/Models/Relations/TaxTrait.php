<?php
namespace App\Models\Relations;

use App\Models\Relations\HasTax;

/**
 * 消費税モデルTrait
 */
trait TaxTrait
{
    /**
     * Define a tax relationship.
     *
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @param  string|null  $dateColumn
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasTax($related, $foreignKey = null, $localKey = null, $dateColumn = null)
    {
        $instance = $this->newRelatedInstance($related);
        $foreignKey = $foreignKey ?: 'tax_kind';
        $localKey = $localKey ?: 'tax_kind';
        $dateColumn = $dateColumn ?: '';

        return new HasTax($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey, $dateColumn);
    }
}
