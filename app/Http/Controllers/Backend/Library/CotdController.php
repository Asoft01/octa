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
use App\DataTables\CotdDataTable;
use App\Models\AcDomain;


class CotdController extends Controller {

	public function index(CotdDataTable $dataTable) {
		return $dataTable->render('backend.library.index');
	}

}
