<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Models\AcDomain;
use App\Models\AcContent;
use App\Models\AcContentUserVote;
use App\Models\AcReview;
use App\Models\AcVideo;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ContentFilterController extends Controller {

	/* 	
		public function index(Request $request, AcDomain $domain) {
			//$parameters = $this->getParameters($request, $domain);
			//$count = $this->getQuery($parameters)->count();
			//dump($parameters, $count);
			$title = 'All Contents';
			$accounts = self::getAccountsArray($domain);
			$types = [
				'MorphAsset' => 'MorphAsset',
				'MorphReview' => 'MorphReview',
				'MorphVideo' => 'MorphVideo'
			];
			
			return view('frontend.ac.contents', [
				'title' => 'All Contents',
				'heading' => 'All Contents',
				'filters' => [
					'accounts' => ['name' => 'accounts', 'collection' => $accounts],
					'types' => ['name' => 'types', 'collection' => $types]
				],
				'requires' => [],
				'sorting' => [
					'Contributor' => 'sort_contributor',
					'Date' => 'ac_contents.display_start',
					'Duration' => 'sort_duration',
					'Thumbs' => 'sort_thumbs',
					'Title' => 'ac_contents.title'
				],
				'use_categories' => true,
				'use_alltags' => true
			]);
		}
	*/

	public function query(Request $request, AcDomain $domain) {
		$parameters = $this->getParameters($request, $domain);
		$query = $this->getQuery($parameters);

		$parameters->total = $query->count();
		$parameters->pages = ceil($parameters->total / $parameters->limit);

		$contents = $query->offset($parameters->offset)->limit($parameters->limit)->get();

		$view = view('frontend.includes.videoitem', ['show_category' => true, 'lazyload' => false]);
		$html = '';

		foreach($contents as $content) {
			$html .= $parameters->before . $view->with('content', $content)->render() . $parameters->after;
		}

		$parameters->html = preg_replace('/\s+/S', ' ', $html);

		return response()->json((array) $parameters);
	}

	protected function getParameters($request, $domain) {
		$page = intval($request->page, 10) ?: 1;
		$limit = intval($request->show, 10) ?: 10;
		$offset = max(0, $page - 1) * $limit;

		return (object) [
			'contributors' => $request->contributors,
			'after' => $request->post('after', '</div>'),
			'before' => $request->post('before', '<div class="col col-12 col-md-6 col-lg-3">'),
			'categories' => $request->categories,
			'direction' => Arr::wrap($request->direction),
			'domain' => $domain->id,
			'limit' => $limit,
			'offset' => $offset,
			'order' => head(Arr::wrap($request->order)),
			'page' => $page,
			// 'pages' => null, // value assigned after query is run
			'requires' => $request->requires,
			'search' => $request->search,
			'show' => $limit,
			'tagids' => $request->tagids,
			'types' => $request->types,
			// 'total' => null, // value assigned after query is run
		];
	}

	protected function getQuery($parameters) {
		return AcContent::query()
			->with(['categories.domains', 'contentable.mentor.user'])
			->isAvailable()
			->when($parameters->requires, function($query, $requires) {
				foreach($requires as $filter => $value) {
					switch ($filter) {
						case 'contributors':
							$morphs = ['App\Models\AcReview', 'App\Models\AcVideo', 'App\Models\AcPlaylist'];
							$query->whereHasMorph('contentable', $morphs, function($query) use ($value) {
								$column = $query->qualifyColumn('mentor_id');
								$query->whereIn($column, Arr::wrap($value));
							});
							break;
						case 'categories':
							$query->whereHas('categories', function($query) use ($value) {
								$column = $query->qualifyColumn($query->getModel()->getKeyName());
								$query->whereIn($column, Arr::wrap($value));
							});
							break;
						case 'types':
							$column = $query->qualifyColumn('contentable_type');
							$query->whereIn($column, Arr::wrap($value));
							break;
						case 'tags':
						case 'tagids':
							$query->whereHas('tags', function($query) use ($value) {
								$column = $query->qualifyColumn($query->getModel()->getKeyName());
								$query->whereIn($column, Arr::wrap($value));
							});
							break;
					}
				}
			})
			->where(function($query) use ($parameters) {
				$column = $query->qualifyColumn('contentable_type');
				$query->whereDomain($parameters->domain)->orWhere($column, 'MorphAsset')->orWhere($column, 'MorphPlaylist');
			})
			->when($parameters->contributors, function($query, $contributors) {
				$query->where(function($query) use ($contributors) {
					$morphs = ['App\Models\AcReview', 'App\Models\AcVideo'];
					$query
						->whereHasMorph('contentable', $morphs, function($query) use ($contributors) {
							$column = $query->qualifyColumn('mentor_id');
							$query->whereIn($column, $contributors);
						})
						->orWhereHasMorph('contentable', ['App\Models\AcPlaylist'], function($query) use ($contributors) {
							$query->whereHas('mentor', function($query) use ($contributors) {
								$column = $query->qualifyColumn('id');
								$query->whereIn($column, $contributors);
							});
						});
				});
			})
			->when($parameters->categories, function($query, $categories) {
				$query->whereHas('categories', function($query) use ($categories) {
					$column = $query->qualifyColumn($query->getModel()->getKeyName());
					$query->whereIn($column, $categories);
				});
			})
			->when($parameters->search, function($query, $search) {
				$query->where(function($query) use ($search) {
					$query->where('title', 'LIKE', "%{$search}%")->orWhere('description', 'LIKE', "%{$search}%");
				});
			})
			->when($parameters->tagids, function($query, $tags) {
				$query->whereHas('tags', function($query) use ($tags) {
					$column = $query->qualifyColumn($query->getModel()->getKeyName());
					$query->whereIn($column, $tags);
				});
			})
			->when($parameters->types, function($query, $types) {
				$column = $query->qualifyColumn('contentable_type');
				$query->whereIn($column, $types);
			})
			->when($parameters->order, function($query, $order) use ($parameters) {
				switch($order) {
					case 'sort_contributor':
						$sql = function($table) {
							return User::query()
								->selectRaw('CONCAT(`users`.`first_name`, " ", `users`.`last_name`)')
								->join('ac_accounts', 'ac_accounts.user_id', '=', 'users.id')
								->join($table, "{$table}.mentor_id", '=', 'ac_accounts.id')
								->whereRaw("`{$table}`.`id` = `ac_contents`.`contentable_id`")
								->toSql();
						};
						$bool_assets  = "`ac_contents`.`contentable_type`='MorphAsset'";
						$bool_reviews = "`ac_contents`.`contentable_type`='MorphReview'";
						$sql_assets  = 'NULL';
						$sql_reviews = '(' . $sql('ac_reviews') . ')';
						$sql_videos  = '(' . $sql('ac_videos')  . ')';
						$query->select([
							'ac_contents.*',
							DB::raw("IF($bool_assets, $sql_assets, IF($bool_reviews, $sql_reviews, $sql_videos)) AS `$order`")
						]);
						break;
					case 'sort_duration':
						$sql = function($class, $table) {
							return $class::query()
							->selectRaw("`{$table}`.`length`")
							->whereRaw("`{$table}`.`id` = `ac_contents`.`contentable_id`")
							->toSql();
						};
						$bool_assets  = "`ac_contents`.`contentable_type`='MorphAsset'";
						$bool_reviews = "`ac_contents`.`contentable_type`='MorphReview'";
						$sql_assets  = 'NULL';
						$sql_reviews = '(' . $sql(AcReview::class, 'ac_reviews') . ')';
						$sql_videos  = '(' . $sql(AcVideo::class, 'ac_videos')   . ')';
						$query->select([
							'ac_contents.*',
							DB::raw("IF($bool_assets, $sql_assets, IF($bool_reviews, $sql_reviews, $sql_videos)) AS `$order`")
						]);
						break;
					case 'sort_thumbs':
						$sql_upvotes = AcContentUserVote::query()
							->selectRaw('COUNT(`ac_content_user_votes`.`state`)')
							->whereRaw('`ac_content_user_votes`.`content_id` = `ac_contents`.`id`')
							->whereRaw('`ac_content_user_votes`.`state` = 1')
							->toSql();
						$query->select([
							'ac_contents.*',
							DB::raw("($sql_upvotes) AS `$order`")
						]);
						break;
				}
				$direction = Arr::get($parameters->direction, $order, 'desc');
				$query->orderBy($order, $direction);
			})
			->displayOrder();
	}

	public static function getAccountsArray($domain) {
		return $domain->accounts()->with('user.roles')->get()
			->filter(function($account) { return $account->user->hasRole(['mentor', 'contributor']); })
			->keyBy('id')
			->map(function($account) { return $account->getContributor(); })
			->sortBy(function($name) { return mb_strtolower($name); })
			->all();
	}

}
