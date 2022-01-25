<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Backend\Library\Traits\SlugifyAcAccount;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;
use App\DataTables\ContributorsDataTable;
use App\Models\AcAccount;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContributorController extends Controller {

	use SlugifyAcAccount;

	public function index(ContributorsDataTable $dataTable) {
		return $dataTable->render('backend.library.index');
	}

	public function create() {
		return view('backend.library.contributors.createOrEdit');
	}

	public function edit($id) {
		$contributor = AcAccount::findOrFail($id);
		$user = $contributor->user;
		return view('backend.library.contributors.createOrEdit', compact('contributor', 'user'));
	}

	public function store(Request $request) {
		$contributor_id = $request->post('contributorID');
		$user_id = $request->post('userID');

		$contributor = $contributor_id ? AcAccount::findOrFail($contributor_id) : null;
		$user = $user_id ? User::findOrFail($user_id) : null;

		$unique_slug_rule = $contributor ? Rule::unique(AcAccount::class)->ignore($contributor) : Rule::unique(AcAccount::class);		
		$unique_email_rule = $user ? Rule::unique(User::class)->ignore($user) : Rule::unique(User::class);

		$validator = Validator::make($request->post(), [
			'firstname' => 'required|max:191',
			'lastname' => 'required|max:191',
			'slug' => ['required', 'max:191', $unique_slug_rule],
			'email' => ['required', 'email', $unique_email_rule],
			'position' => 'nullable|max:191',
			'cv' => 'nullable',
			'bio' => 'nullable',
			'photoFile' => 'required',
			'iconFile' => 'nullable',
			'posterFile' => 'nullable',
			'videoFullFile' => 'nullable',
			'videoPreviewFile' => 'nullable',
		]);
		
		$validator->validate(); // redirects back if invalid
		$valid = $validator->valid();

		$uc = User::updateOrCreate(['id' => $user_id], [
			'first_name' => $valid['firstname'],
			'last_name' => $valid['lastname'],
			'email' => $valid['email'],
			'password' => 'Gfdhgfd7tg8r73h2h87',
			'confirmation_code' => md5(uniqid(mt_rand(), true)),
			'confirmed' => true
		]);

		if (is_null($contributor)) {
			User::find($uc->id)->assignRole('contributor');
		}

		AcAccount::updateOrCreate(['id' => $contributor_id], [
			'ac_domain_id' => $contributor ? optional($contributor)->ac_domain_id : config('app.fallback_domain_id', null),
			'user_id' => $uc->id,
			'slug' => $valid['slug'],
			'position' => $valid['position'],
			'cv' => $valid['cv'],
			'bio' => $valid['bio'],
			'photo' => 'photos/' . $valid['photoFile'],
			'icon' => !empty($valid['iconFile']) ? 'overlays/' . $valid['iconFile'] : null,
			'poster' => !empty($valid['posterFile']) ? 'photos/' . $valid['posterFile'] : null,
			'video' => !empty($valid['videoFullFile']) ? 'videos/' . $valid['videoFullFile'] : null,
			'preview_video' => !empty($valid['videoPreviewFile']) ? 'videos/' . $valid['videoPreviewFile'] : null,
		]);


		return redirect()->route('admin.library.contributors')->withFlashSuccess("Success");
	}

	public function delete($id) {

		$contributor = AcAccount::findOrFail($id);

		// delete s3 directory
		Storage::disk('s3')->delete("photos/" . (string) $contributor->photo);
		Storage::disk('s3')->delete("overlays/" . (string) $contributor->icon);

		$contributor->delete();

		return redirect()->route('admin.library.contributors')->withFlashSuccess("Success");
	}

	public function slug(Request $request) {
		return $this->SlugifyAcAccount($request);
	}

}
