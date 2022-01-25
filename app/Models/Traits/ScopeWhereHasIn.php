<?php

namespace App\Models\Traits;

trait ScopeWhereHasIn {

	/**
	 * 
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $relation
	 * @param  mixed  $values
	 * @param  string  $column  [optional]
	 * @param  string  $boolean  [optional]
	 * @param  boolean  $not  [optional]
	 * @param  string  $operator  [optional]
	 * @param  integer  $count  [optional]
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
    public function scopeWhereHasIn($query, $relation, $values, $column = null, $boolean = 'and', $not = false, $operator = '>=', $count = 1) {
		return $query->whereHas($relation, function($sq) use ($column, $values, $boolean, $not) {
			$column = $sq->qualifyColumn($column ?: $sq->getModel()->getKeyName());
			$sq->whereIn($column, $values, $boolean, $not);
		}, $operator, $count);
    }

}
