<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Backend\Library\Traits\SlugifyAcAccount;
use App\Http\Controllers\Controller;
use App\DataTables\ExpertsDataTable;
use App\Models\AcAccount;
use App\Models\AcCurrency;
use App\Models\AcProduct;
use App\Models\AcPrice;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

/**
 * Despite the name, this is more of a specialized AcAccount Resource Controller.
 * 
 * @author Marek Philibert <marekphilibert@gmail.com>
 * @uses App\Http\Controllers\Backend\Library\Traits\SlugifyAcAccount
 */
class ExpertController extends Controller {

	use SlugifyAcAccount;

	/**
	 * Display a table with all the AcAccounts that qualify as Experts.
	 * 
	 * @param App\DataTables\ExpertsDataTable $datatable
	 * @return mixed
	 */
	public function index(ExpertsDataTable $datatable) {
		return $datatable->render('backend.library.index');
	}

	/**
	 * Display form used to create a new AcAccount.
	 * 
	 * @param Illuminate\Http\Request $request
	 * @return Illuminate\View\View
	 */
	public function create(Request $request) {
		$products = self::getTemplateProducts(null, true);
		$timezones = self::getAllTimezones();
		$tz = $request->user()->getTimezone();
		return view('backend.library.experts.createOrEdit', compact('products', 'timezones', 'tz'));
	}

	/**
	 * Display form used to edit existing AcAccount.
	 * 
	 * The blade template used is the same as the one the create() method uses.
	 * The difference is that the view returned by this method will contain a hidden "account_id" field.
	 * 
	 * @param Illuminate\Http\Request $request
	 * @param integer $id An AcAccount primary key.
	 * @return Illuminate\View\View
	 */
	public function edit(Request $request, $id) {
		$account = AcAccount::findOrFail($id);
		$products = self::getTemplateProducts($account, true);
		$timezones = self::getAllTimezones();
		$tz = $request->user()->getTimezone();
		$user = $account->user;
		return view('backend.library.experts.createOrEdit', compact('account', 'products', 'timezones', 'tz', 'user'));
	}

	/**
	 * Handles POST requests to update or create an AcAccount.
	 * 
	 * When updating an AcAccount:
	 * <br> -- The provided email must be not be in use by any other User.
	 * 
	 * When creating an AcAccount...
	 * <br> -- If a unique email is provided, then a new User will be created and associated with the new AcAccount.
	 * <br> -- If a existing User's email is provided, and that User does not already have an AcAccount, then that User will be associated with the new AcAccount.
	 * 
	 * @param Illuminate\Http\Request $request A POST request containing form data.
	 * @return Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request) {
		if ($request->post('account_id')) {
			$account = AcAccount::findOrFail($request->post('account_id'));
			$user = $account->user;
		} else {
			$account = null;
			$user = User::where('email', $request->post('email'))->first();
		}

		$validator = self::makeValidator($request, $user, $account);
		$validator->validate(); // redirects back if invalid
		$data = $validator->valid(); // array of validated input

		$updated_user    = self::updateOrCreateUser($data, $user);
		$updated_account = self::updateOrCreateAccount($data, $updated_user, $account);

		self::updateOrCreateProducts($data, $updated_account);
		self::updateOrCreatePrices($updated_account);

		return redirect()->route('admin.library.experts')->withFlashSuccess("Success");
	}

	/** 
	 * Delete the AcAccount and some of its uploaded files.
	 * 
	 * @param integer $id An AcAccount primary key.
	 * @return Illuminate\Http\RedirectResponse
	 */
	public function delete($id) {

		$account = AcAccount::findOrFail($id);
		AcPrice::where('account_id', $id)->delete();
		AcProduct::where('account_id', $id)->delete();

		// delete s3 directory
		Storage::disk('s3')->delete("photos/" . (string) $account->photo);
		Storage::disk('s3')->delete("overlays/" . (string) $account->icon);

		Log::debug('-----------------------------------------------------------');
		Log::debug('remove user role expert - not tested...');
		Log::debug('-----------------------------------------------------------');
		$account->user->removeRole('mentor');
		$account->user->assignRole('user');
		$account->delete();

		return redirect()->route('admin.library.experts')->withFlashSuccess("Success");
	}

	/**
	 * Handles POST requests, typically made via Axios or AJAX, for generation or validation of AcAccount slugs.
	 * 
	 * @param Request $request
	 * @return Illuminate\Http\JsonResponse
	 */
	public function slug(Request $request) {
		return $this->SlugifyAcAccount($request);
	}

	/**
	 * Returns a Validator object for use in the store() method.
	 * 
	 * @param Illuminate\Http\Request $request The request to be validated.
	 * @param App\Models\Auth\User|null $user [Optional] The User model that is or will be associated with the AcAccount.
	 * @param App\Models\AcAccount|null $account [Optional] The AcAccount model being edited.
	 * @return Illuminate\Validation\Validator
	 */
	protected static function makeValidator($request, $user = null, $account = null) {

		/*
		 * This new validation rule requires that an array's keys be in the rule's parameters.
		 */
		Validator::extend('keys_in', function($attribute, $value, $parameters, $validator) {
			return is_array($value) && empty(array_diff(array_keys($value), $parameters));
		});
		Validator::replacer('keys_in', function ($message, $attribute, $rule, $parameters) {
			return str_replace(':values', implode(', ', $parameters), $message);
		});

		if ($account) {
			/*
			 * When editing an existing AcAccount...
			 * The email must not be in use by another User.
			 * The slug must not be in use by another AcAccount.
			 * The products must exist and belong to the AcAccount.
			 */
			$extra_email_rule = Rule::unique(User::class)->ignoreModel($user);
			$extra_slug_rule = Rule::unique(AcAccount::class)->ignoreModel($account);
			//$extra_products_rule = 'keys_in:' . $account->products->pluck('id')->flatten()->implode(',');
		} else {
			/*
			 * When creating a new AcAccount...
			 * The email must not be in use by another User that has an AcAccount.
			 * The slug must not be in use by an AcAccount.
			 */
			$extra_email_rule = Rule::notIn(User::has('account')->get(['email'])->pluck('email')->all());
			$extra_slug_rule = Rule::unique(AcAccount::class);
			//$extra_products_rule = function(){}; // This closure rule is never invalid.
		}

		/*
		 * When editing or creating an AcAccount...
		 * The templates must exist in the product config file.
		 */
		$extra_products_rule = 'keys_in:' . collect(config('product.items'))->pluck('internal_id')->flatten()->implode(',');

		$rules = [
			'firstname'			=> 'required|max:191',
			'lastname'			=> 'required|max:191',
			'email'				=> ['required', 'email', $extra_email_rule],

			'slug'				=> ['required', 'max:191', $extra_slug_rule],
			'position'			=> 'nullable|max:191',
			'cv'				=> 'nullable|string',
			'bio'				=> 'nullable|string',

			'photoFile'			=> 'required',
			'iconFile'			=> 'nullable',
			'posterFile'		=> 'nullable',
			'videoFullFile'		=> 'nullable',
			'videoPreviewFile'	=> 'nullable',

			'asanaGID'			=> 'required|string',
			'nextcloudUsername'	=> 'required|string',
			'hoursWeek'			=> 'required|integer',
			'delay'				=> 'nullable|integer',
			'bookeduntil'		=> 'nullable|date',
			'timezone'			=> 'required|timezone',

			'products'			=> ['sometimes', 'array', $extra_products_rule],
			'products.*'		=> 'nullable|numeric',
			
			'account_id'		=> 'sometimes|required|integer',
		];
		
		$messages = [
			'email.not_in' => 'This :attribute belongs to an another contributor/expert account.',
			'email.unique' => 'This :attribute belongs to an another user.',
			'products.keys_in' => 'One or more product fields not found.'
		];

		$input = Arr::only($request->post(), array_keys($rules));

		return Validator::make($input, $rules, $messages);
	}

	/**
	 * Updates the provided User model or creates a new User.
	 * In the latter case, the created User will be marked as "confirmed" and given a hardcoded password.
	 * Either way, the User will be have the "mentor" role.
	 * 
	 * @param array $data Array containing validated input.
	 * @param App\Models\Auth\User|null $user [Optional] The User model to update.
	 * @return App\Models\Auth\User
	 */
	protected static function updateOrCreateUser($data, $user = null) {
		$attributes = [
			'first_name' => $data['firstname'],
			'last_name' => $data['lastname'],
			'email' => $data['email']
		];
		if ($user) {
			// Updating existing User...
			$user->fill($attributes);
			$user->save();
		} else {
			// Creating new User...
			$attributes += [
				'password' => 'Gfdhgfd7tg8r73h2h87',
				'confirmation_code' => md5(uniqid(mt_rand(), true)),
				'confirmed' => true
			];
			$user = User::create($attributes);
		}
		if (!$user->hasRole('mentor')) {
			$user->assignRole('mentor');
		}
		return $user;
	}

	/**
	 * Updates the provided AcAccount or creates a new AcAccount.
	 * The AcAccount will be associated with the User model provided.
	 * 
	 * @param array $data Array containing validated input.
	 * @param App\Models\Auth\User $user The User model to associate with the AcAccount.
	 * @param App\Models\AcAccount|null $account [Optional] The AcAccount model to update.
	 * @return App\Models\AcAccount
	 */
	protected static function updateOrCreateAccount($data, $user, $account = null) {
		$app_timezone = config('app.timezone', 'UTC');
		$attributes = Arr::only($data, [
			'slug',
			'position',
			'cv',
			'bio',
			'asanaGID',
			'nextcloudUsername',
			'hoursWeek',
			'delay',
		]);
		$attributes += [
			'user_id'		=> $user->id,
			'photo'			=> !empty($data['photoFile']) ? 'photos/' . $data['photoFile'] : null,
			'icon'			=> !empty($data['iconFile']) ? 'overlays/' . $data['iconFile'] : null,
			'poster'		=> !empty($data['posterFile']) ? 'photos/' . $data['posterFile'] : null,
			'video'			=> !empty($data['videoFullFile']) ? 'videos/' . $data['videoFullFile'] : null,
			'preview_video'	=> !empty($data['videoPreviewFile']) ? 'videos/' . $data['videoPreviewFile'] : null,
			'bookeduntil'	=> !empty($data['bookeduntil']) ? Carbon::parse($data['bookeduntil'], $data['timezone'])->setTimezone($app_timezone) : null,
		];
		if ($account) {
			// Updating existing AcAccount...
			$account->fill($attributes);
			$account->save();
		} else {
			// Creating new AcAccount...
			$attributes['ac_domain_id'] = config('app.fallback_domain_id', null);
			$account = AcAccount::create($attributes);
		}
		return $account;
	}

	protected static function updateOrCreateProducts($data, $account) {
		$currencies = AcCurrency::get();
		$templates = self::getTemplateProducts(null, false);
		$filters = array_merge(config('product.filters'), ['account_id']);
		$inputs = Arr::get($data, 'products', []);
		$count = 0;

		foreach($inputs as $input_id => $input_value) {
			$template = $templates->where('internal_id', $input_id)->first();
			if ($template) {
				$input = is_null($input_value) ? $template['price'] : $input_value;
				$base_currency = $currencies->where('id', $template['currency_id'])->first();
				$base_price = floatval($input) / floatval($base_currency->multiplier);
				foreach ($currencies as $currency) {
					$attributes = Arr::except($template, ['internal_id', 'models']);
					$attributes['account_id'] = $account->id;
					$attributes['currency_id'] = $currency->id;
					$attributes['price'] = $base_price * floatval($currency->multiplier);

					$match = Arr::only($attributes, $filters);
					$change = Arr::except($attributes, $filters);

					Model::unguard();
					AcProduct::updateOrCreate($match, $change);
					Model::reguard();

					$count++;
				}

			}
		}

		return $count;
	}

	protected static function updateOrCreatePrices($account) {
		return Artisan::call('db:account-prices', [
			'accounts' => Arr::wrap($account->id),
			'--domains' => Arr::wrap($account->ac_domain_id),
			'--force' => true
		]);
	}

	protected static function getTemplateProducts($account = null, $cast_to_objects = false) {
		$domain_ids = Arr::wrap(config('app.fallback_domain_id'));
		$filters = config('product.filters');
		$default = config('product.default');
		$items = config('product.items');
		$templates = [];

		foreach ($items as $item) {
			$template = array_merge($default, $item);
			if (empty($domain_ids) || in_array($template['domain_id'], $domain_ids)) {

				if ($account !== null) {
					$products = $account->products;
					foreach ($filters as $filter) {
						$products = $products->where($filter, $template[$filter]);
					}
					$template['models'] = $products;
				} else {
					$template['models'] = collect();
				}

				$templates[$template['internal_id']] = $cast_to_objects ? (object) $template : $template;

			}
		}

		return collect($templates);
	}

	/**
	 * Get an array of all timezones supported by PHP.
	 * The keys will be defined timezone identifiers.  
	 * The values will be the formatted datetime strings.
	 * 
	 * {@link https://www.php.net/manual/en/datetime.format.php}
	 * 
	 * @param string $format [Optional] The desired DateTime::format() parameter string.
	 * @return array
	 */
	protected static function getAllTimezones($format = '(P) e') {
		return timezone()->getTimezones(true)->transform(function($ts) use ($format) { return $ts->format($format); })->all();
	}
}
