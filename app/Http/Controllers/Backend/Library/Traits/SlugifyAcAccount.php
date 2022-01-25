<?php

namespace App\Http\Controllers\Backend\Library\Traits;

use App\Models\AcAccount;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait SlugifyAcAccount {

	protected function SlugifyAcAccount($request) {
		$model_id = $request->post('model_id');
		$model = $model_id ? AcAccount::findOrFail($model_id) : null;

		if ($request->post('generate') === true) {
			$first_name = (string) $request->post('first_name');
			$last_name = (string) $request->post('last_name');
			$slug = ($last_name === '_') ? Str::slug($first_name) : Str::slug($first_name.' '.$last_name);
		} else {
			$slug = (string) $request->post('slug');
		}

		$unique_slug_rule = $model ? Rule::unique(AcAccount::class)->ignore($model) : Rule::unique(AcAccount::class);

		$validator = Validator::make(['slug' => $slug], ['slug' => [$unique_slug_rule]]);

		return response()->json([ 
			'slug' => $slug,
			'valid' => $validator->passes()
		]);
	}

}