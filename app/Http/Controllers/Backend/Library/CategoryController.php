<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DB;
use App\DataTables\CategoriesDataTable;
use App\Models\AcCategory;
use App\Models\AcDomain;


class CategoryController extends Controller {

	public function index(CategoriesDataTable $dataTable) {
		return $dataTable->render('backend.library.index');
	}

	public function create() {
		$domains = AcDomain::get(['id', 'title'])->pluck('title', 'id')->sortKeys()->all();
		$selected_domains = [];
		return view('backend.library.categories.createOrEdit', compact('domains', 'selected_domains'));
	}

	public function edit($id) {
		$category = AcCategory::findOrFail($id);
		$domains = AcDomain::get(['id', 'title'])->pluck('title', 'id')->sortKeys()->all();
		$selected_domains = Collection::wrap($category->domains)->pluck('id')->all();
		return view('backend.library.categories.createOrEdit', compact('category', 'domains', 'selected_domains'));
	}

	// either create or update
	public function store(Request $request) {
		$category = $request->post('categoryID') ? AcCategory::findOrFail($request->post('categoryID')) : null;

		$validator = self::makeValidator($request->post());
		$validator->validate();
		$data = $validator->valid();

		$attributes = Arr::only($data, ['title', 'seq']);

		if ($category) {
			$category->fill($attributes)->save();
		} else {
			$category = AcCategory::create($attributes);
		}

		$category->domains()->sync($data['domain_ids']);
		
		return redirect()->route('admin.library.categories')->withFlashSuccess("Success");
	}

	public function delete($id) {
		$asset = AcCategory::findOrFail($id);
		$asset->delete();

		return redirect()->route('admin.library.categories')->withFlashSuccess("Success");
	}

	protected static function makeValidator($input) {
		$domains = AcDomain::get(['id']);
		$in_domains_rule = Rule::in($domains->pluck('id')->all());

		$rules = [
			'title' => 'required|string',
			'seq' => 'required|integer|gte:0',
			'domain_ids' => 'required|array|min:1',
			'domain_ids.*' => ['integer', $in_domains_rule],
		];

		$names = [
			'seq' => 'order',
			'domain_ids' => 'domains',
		];

		$validator = Validator::make($input, $rules)->setAttributeNames($names);
		return $validator;
	}

}
