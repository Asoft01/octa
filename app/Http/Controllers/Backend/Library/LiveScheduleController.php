<?php

namespace App\Http\Controllers\Backend\Library;

use App\DataTables\LiveSchedulesDataTable;
use App\Http\Controllers\Controller;
use App\Models\AcAccount;
use App\Models\AcLiveSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LiveScheduleController extends Controller {

	public function index(LiveSchedulesDataTable $datatable) {
		return $datatable->render('backend.library.index');
	}

	public function create() {
		$contributors = getAllContributors()->users->sortBy('full_name')->pluck('full_name', 'account.id')->toArray();
		$timezones = timezone()->getTimezones(true)->transform(function($ts) { return $ts->format('(P) e'); })->all();
		return view('backend.library.liveschedules.createOrEdit', compact('contributors', 'timezones'));
	}

	public function edit($id) {
		$model = AcLiveSchedule::findOrFail($id);
		$contributors = getAllContributors()->users->sortBy('full_name')->pluck('full_name', 'account.id')->toArray();
		$timezones = timezone()->getTimezones(true)->transform(function($ts) { return $ts->format('(P) e'); })->all();
		return view('backend.library.liveschedules.createOrEdit', compact('model', 'contributors', 'timezones'));
	}

	public function store(Request $request) {
		$model_id = $request->post('model_id');
		$account_id = $request->post('account_id');
		$model = $model_id ? AcLiveSchedule::findOrFail($model_id) : null;
		$account = $account_id ? AcAccount::findOrFail($account_id) : null;
		$app_timezone = config('app.timezone', 'UTC');
		$unique_slug_rule = $model ? Rule::unique(AcLiveSchedule::class)->ignore($model) : Rule::unique(AcLiveSchedule::class);
		
		$validator = Validator::make($request->post(), [
			'title' => 'required|max:191',
			'slug' => ['required', 'max:191', $unique_slug_rule],
			//'excerpt' => 'required',
			'description' => 'required',
			'eventDatetime' => 'required|date',
			'eventDuration' => 'required|integer|min:1',
			'timezone' => 'required|timezone'
		]);
		
		$validator->validate();
		$valid = $validator->valid();
		
		AcLiveSchedule::updateOrCreate(['id' => $model_id], [
			'account_id' => $account ? $account->id : null,
			'title' => $valid['title'],
			'slug' => $valid['slug'],
			//'excerpt' => $valid['excerpt'],
			'description' => $valid['description'],
			'eventDatetime' => Carbon::parse($valid['eventDatetime'], $valid['timezone'])->setTimezone($app_timezone),
			'eventDuration' => intval($valid['eventDuration'])
		]);

		return redirect()->route('admin.library.schedules')->withFlashSuccess("Success");
	}

	public function delete($id) {
		$model = AcLiveSchedule::findOrFail($id);
		$model->delete();
		return redirect()->route('admin.library.schedules')->withFlashSuccess("Success");
	}

	public function slug(Request $request) {
		$id = $request->post('id');
		$content = (string) $request->post('content');
		$generate = (bool) $request->post('generate');

		$slug = $generate ? Str::slug($content) : $content;
		$model = $id ? AcLiveSchedule::findOrFail($id) : null;
		$unique_slug_rule = $model ? Rule::unique(AcLiveSchedule::class)->ignore($model) : Rule::unique(AcLiveSchedule::class);

		$validator = Validator::make(['slug' => $slug], ['slug' => [$unique_slug_rule]]);

		return response()->json([ 
			'slug' => $slug,
			'valid' => $validator->passes()
		]);
	}

}
