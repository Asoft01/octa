<?php

namespace App\Http\Controllers\Backend\Library;

use App\Http\Controllers\Backend\Library\Traits\FindOrCreateAcTags;
use App\Http\Controllers\Controller;
use App\DataTables\ContentsDataTable;
use App\DataTables\PlaylistsDataTable;
use App\Models\AcCategory;
use App\Models\AcContent;
use App\Models\AcDomain;
use App\Models\AcPlaylist;
use App\Models\AcTag;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Backend resource controller for AcPlaylist.
 * 
 * @author Marek Philibert <marekphilibert@gmail.com>
 * @uses App\Http\Controllers\Backend\Library\Traits\FindOrCreateAcTags
 */
class PlaylistController extends Controller {

	use FindOrCreateAcTags;

	/**
	 * Display a table with all the AcPlaylists.
	 * 
	 * @param App\DataTables\PlaylistsDataTable $datatable
	 * @return mixed
	 */
	public function index(PlaylistsDataTable $datatable) {
		return $datatable->render('backend.library.index');
	}

	/**
	 * Display a table with all the AcContents.
	 * Since $datatable->render() returns a JSON response,
	 * the datatable can be used in other views as long as
	 * its script knows to call this route instead of the
	 * default.
	 * 
	 * @param App\DataTables\PlaylistsDataTable $datatable
	 * @return mixed
	 */
	public function contents(ContentsDataTable $datatable) {
		return $datatable->render('backend.library.index');
	}

	/**
	 * Display form used to create a new AcPlaylist.
	 * 
	 * @return Illuminate\View\View
	 */
	public function create(ContentsDataTable $datatable) {
		$users = User::with(['account'])->get();
		return view('backend.library.playlists.createOrEdit', [
			'categories' => AcCategory::get(['title','id'])->pluck('title', 'id')->sort()->all(),
			'domains' => AcDomain::get(['title','id'])->pluck('title', 'id')->sortKeys()->all(),
			'tags' => AcTag::get(['title','id'])->pluck('title','id')->sort()->all(),
			'users' => [
				'Account Users' => $users->whereNotNull('account')->pluck('account.full_name', 'id')->sort()->all(),
				// 'Other Users' => $users->whereNull('account')->pluck('name', 'id')->sort()->all()
			],
			'datatable' => $datatable
		]);
	}

	/**
	 * Display form used to edit existing AcPlaylist.
	 * 
	 * The blade template used is the same as the one the create() method uses.
	 * The difference is that the view returned by this method will contain a hidden "id" field.
	 * 
	 * @param integer $id An AcPlaylist primary key.
	 * @return Illuminate\View\View
	 */
	public function edit(ContentsDataTable $datatable, $id) {
		$users = User::with(['account'])->get();
		return view('backend.library.playlists.createOrEdit', [
			'categories' => AcCategory::get(['title','id'])->pluck('title', 'id')->sort()->all(),
			'domains' => AcDomain::get(['title','id'])->pluck('title', 'id')->sortKeys()->all(),
			'model' => AcPlaylist::with(['content.tags', 'contents', 'user'])->findOrFail($id),
			'tags' => AcTag::get(['title','id'])->pluck('title','id')->sort()->all(),
			'users' => [
				'Account Users' => $users->whereNotNull('account')->pluck('account.full_name', 'id')->sort()->all(),
				// 'Other Users' => $users->whereNull('account')->pluck('name', 'id')->sort()->all()
			],
			'datatable' => $datatable
		]);
	}

	/**
	 * Handles POST requests to update or create an AcPlaylist.
	 * 
	 * @param Illuminate\Http\Request $request A POST request containing form data.
	 * @return Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request) {
		$model = $request->post('id') ? AcPlaylist::findOrFail($request->post('id')) : null;

		$validator = $this->makeValidator($request, $model);
		$validator->validate(); // redirects back if invalid
		$data = $validator->valid(); // array of validated input

		$playlist = $this->updateOrCreatePlaylist($data, $model);
		$this->updateOrCreateContent($data, $playlist);

		return redirect()->route('admin.library.playlists')->withFlashSuccess('Success');
	}

	/** 
	 * Delete the AcPlaylist and related rows.
	 * 
	 * @param integer $id An AcPlaylist primary key.
	 * @return Illuminate\Http\RedirectResponse
	 */
	public function delete($id) {
		$playlist = AcPlaylist::with(['content'])->findOrFail($id);

		if ($playlist->poster) {
			// Storage::disk('s3')->delete("photos/" . (string) $playlist->poster);
		}

		$playlist->content->categories()->detach();
		$playlist->content->favorites()->delete();
		$playlist->content->metrics()->delete();
		$playlist->content->tags()->detach();
		$playlist->content->watchlistitems()->delete();
		$playlist->content->votes()->delete();
		$playlist->content->delete();

		$playlist->contents()->detach();
		$playlist->delete();

		return redirect()->route('admin.library.playlists')->withFlashSuccess("Success");
	}

	/**
	 * Handles POST requests, typically made via Axios or AJAX, for generation or validation of AcContent slugs.
	 * 
	 * @param Request $request
	 * @return Illuminate\Http\JsonResponse
	 */
	public function slug(Request $request) {
		$content = (string) $request->post('content');
		$generate = (bool) $request->post('generate');
		$slug = $generate ? Str::slug($content) : $content;
		$exists = AcContent::where('slug', $slug)->exists();
		return response()->json([
			'slug' => $slug,
			'exists' => $exists
		]);
	}

	/**
	 * Dump AcPlaylist for debugging.
	 * 
	 * @param integer $id An AcPlaylist primary key.
	 */
	public function dump($id) {
		$model = AcPlaylist::where('id', $id)->with(['content', 'contents', 'mentor', 'user'])->first();
		echo '<style>body { background-color: #18171B; }</style>';
		dump($model);
	}

	/**
	 * Returns a Validator object for use in the store() method.
	 * 
	 * @param Illuminate\Http\Request $request The request to be validated.
	 * @param App\Models\AcPlaylist|null $model [Optional] The AcPlaylist model being edited.
	 * @return Illuminate\Validation\Validator
	 */
	protected function makeValidator($request, $model = null) {
		if ($model) {
			$extra_slug_rule = Rule::unique(AcContent::class)->ignoreModel($model->content);
		} else {
			$extra_slug_rule = Rule::unique(AcContent::class);
		}

		$rules = [
			'id' => 'sometimes|required|exists:ac_playlists,id',
			'user_id' => 'required|exists:users,id',
			'domain_id' => 'required|exists:ac_domains,id',

			'categories' => 'nullable|exists:ac_categories,id',
			'contents' => 'nullable',
			'tags' => 'nullable',

			'slug' => ['required', 'max:191', $extra_slug_rule],

			'title' => 'required|string|max:191',
			'title_cotd' => 'nullable|string|max:191',

			'description' => 'nullable|string',
			'description_cotd' => 'nullable|string|max:400',

			'display_start' => 'nullable|date',
			'display_end' => 'nullable|date',
			'cotd_start' => 'nullable|date',

			'posterFile' => 'nullable',
		];

		$messages = [];

		$input = Arr::only($request->post(), array_keys($rules));

		return Validator::make($input, $rules, $messages);
	}

	/**
	 * Updates the provided AcPlaylist model or creates a new AcPlaylist.
	 * 
	 * @param array $data Array containing validated input.
	 * @param App\Models\AcPlaylist|null $model [Optional] The AcPlaylist model to update.
	 * @return App\Models\AcPlaylist
	 */
	protected function updateOrCreatePlaylist($data, $model = null) {
		if ($model === null) {
			$model = new AcPlaylist();
		}
		$model->user_id = $data['user_id'];
		$model->poster = !empty($data['posterFile']) ? "photos/{$data['posterFile']}" : null;
		$model->save();
		return $model;
	}

	/**
	 * Updates the provided AcPlaylist model's polymorphic relation (AcContent) or creates a new AcContent.
	 * 
	 * @param array $data Array containing validated input.
	 * @param App\Models\AcPlaylist|null $playlist [Optional] The AcPlaylist model to update.
	 * @return App\Models\AcContent
	 */
	protected function updateOrCreateContent($data, $playlist) {
		$attributes = Arr::only($data, [
			'domain_id',
			'title',
			'title_cotd',
			'description',
			'description_cotd',
			'slug',
			'display_start',
			'display_end',
			'display_order',
			'cotd_start'
		]);
		$basics = ['contentable_type' => 'MorphPlaylist', 'isPublic' => 1];
		$categories = Arr::wrap(Arr::get($data, 'categories', []));
		// $categories = [AcCategory::where('title', 'Playlists')->first()->id];
		$contents = $this->getPlaylistContentsSync(explode(',', $data['contents']));
		$tags = $this->FindOrCreateAcTags($data['tags'], $data['domain_id'])->pluck('id')->all();

		if ($playlist->content) {
			$playlist->content->fill($attributes);
		} else {
			$playlist->content()->create($attributes + $basics);
			$playlist->unsetRelation('content');
			$playlist->load('content');
		}

		$playlist->content->save();
		$playlist->content->categories()->sync($categories);
		$playlist->content->tags()->sync($tags);

		$playlist->contents()->sync($contents);

		return $playlist->content;
	}

	/**
	 * Returns an array that can be used with the sync() method.
	 * 
	 * @param array $ids Array containing AcPlaylist IDs.
	 * @return array
	 */
	protected function getPlaylistContentsSync($ids) {
		$values = Collection::times(count($ids), function($n) {
			return ['display_order' => $n];
		});
		return collect($ids)->combine($values)->all();
	}

}
