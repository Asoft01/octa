<?php

namespace App\Http\Controllers\Backend\Library\Traits;

use App\Models\AcTag;
use Illuminate\Support\Arr;

trait FindOrCreateAcTags {

	protected function FindOrCreateAcTags($input_tags, $input_domain_id) {
		$collection = collect(Arr::wrap($input_tags));
		$integers = $collection->filter(function($value) { return ctype_digit(strval($value)); });
		$strings = $collection->except($integers->keys());
		$tags = AcTag::whereIn('id', $integers)->get();

		foreach ($strings as $title) {
			$new = AcTag::firstOrCreate(['title' => $title], [
				'category_id' => null,
				'domain_id' => $input_domain_id
			]);
			$tags->push($new);
		}
		
		return $tags;
	}

}