<?php

namespace App\Console\Commands;

use App\Models\AcLive;
use App\Services\Twitch\TwitchFacade as Twitch;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


class FetchTwitchChannels extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = "twitch:fetch-channels
		{--d|dry : Don't make any changes to the database.}
	";

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Updates the ac_live table with the configured Twitch user's followed channels.";

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$follows = $this->fetchFollows();
		$follows[] = $this->makeSelfFollowObject();
		$follows_ids = array_column($follows, 'to_id');

		$streams = $this->fetchStreams($follows_ids);
		$streams_ids = array_column($streams, 'user_id');

		$users_ids = array_diff($follows_ids, $streams_ids);
		$users = $this->fetchUsers($users_ids);
		
		if ($this->option('dry')) {
			$this->info('The database was not updated because the --dry option was enabled.');
		} else {
			$this->updateDatabase($follows, $streams, $users);
			$this->info('The database was updated.');
		}
		
		if ($this->isVerbosity('vvv')) {
			dump(compact('follows', 'streams', 'users'));
		}
		else if ($this->isVerbosity('v')) {
			$this->printFollows($follows);
			$this->printStreams($streams);
			$this->printUsers($users);
			$this->line('');
		}
	}

	protected function makeSelfFollowObject() {
		$id = (string) Twitch::config('user_id');
		$login = Twitch::config('user_login');
		return (object) [
			'from_id' => $id,
			'from_login' => $login,
			'from_name' => $login,
			'to_id' => $id,
			'to_login' => $login,
			'to_name' => $login,
			'followed_at' => now()->toIso8601ZuluString()
		];
	}

	protected function fetchFollows() {
		$response = Twitch::request('GET', 'users/follows', [
			'first' => 99,
			'from_id' => Twitch::config('user_id')
		]);
		$body = json_decode($response->getBody()->getContents());
		return Arr::wrap(optional($body)->data);
	}

	protected function fetchStreams($user_ids) {
		$response = Twitch::request('GET', 'streams', [
			'user_id' => array_values($user_ids)
		]);
		$body = json_decode($response->getBody()->getContents());
		return Arr::wrap(optional($body)->data);
	}

	protected function fetchUsers($user_ids) {
		$response = Twitch::request('GET', 'users', [
			'id' => array_values($user_ids)
		]);
		$body = json_decode($response->getBody()->getContents());
		return Arr::wrap(optional($body)->data);
	}

	protected function updateDatabase($follows, $streams, $users) {
		$thumbnail_fallback = Twitch::fallbackChannelImage();

		AcLive::whereNotIn('user_login', array_column($follows, 'to_login'))->delete();

		foreach($streams as $stream) {
			AcLive::updateOrCreate(['user_login' => $stream->user_login], [
				'user_name' => $stream->user_name,
				'thumbnail_url' => $stream->thumbnail_url,
				// 'thumbnail_offline' => null,
				'stream_id' => $stream->id,
				'game_id' => $stream->game_id,
				'game_name' => $stream->game_name,
				'title' => $stream->title,
				'viewer_count' => $stream->viewer_count,
				'isStreaming' => 1,
			]);
		}

		foreach($users as $user) {
			AcLive::updateOrCreate(['user_login' => $user->login], [
				'user_name' => $user->display_name,
				'thumbnail_url' => $user->offline_image_url ?: $thumbnail_fallback,
				'thumbnail_offline' => $user->offline_image_url ?: $thumbnail_fallback,
				'stream_id' => null,
				'game_id' => null,
				'game_name' => null,
				'title' => null,
				'viewer_count' => null,
				'isStreaming' => 0,
			]);
		}
	}

	protected function printFollows($follows) {
		if (!empty($follows)) {
			$rows = [];
			foreach($follows as $follow) {
				$rows[] = [
					'user_id' => $follow->to_id,
					'user_login' => $follow->to_login,
					'followed_at' => Carbon::parse($follow->followed_at, 'UTC')->tz(config('app.timezone', 'UTC'))->toDateTimeString()
				];
			}
			$this->line("\nFollows");
			$this->table(array_keys($rows[0]), $rows);
		}
	}

	protected function printStreams($streams) {
		if (!empty($streams)) {
			$rows = [];
			foreach($streams as $stream) {
				$rows[] = [
					'url' => "https://twitch.tv/{$stream->user_login}",
					'title' => Str::limit(Str::ascii($stream->title), 47, '...'),
					'viewers' => $stream->viewer_count,
					'started_at' => Carbon::parse($stream->started_at, 'UTC')->tz(config('app.timezone', 'UTC'))->toDateTimeString()
				];
			}
			$this->line("\nStreams");
			$this->table(array_keys($rows[0]), $rows);
		}
	}

	protected function printUsers($users) {
		if (!empty($users)) {
			$rows = [];
			foreach($users as $user) {
				$rows[] = [
					'url' => "https://twitch.tv/{$user->login}",
					'description' => Str::limit(Str::ascii($user->description), 47, '...'),
					'views' => $user->view_count,
					'created_at' => Carbon::parse($user->created_at, 'UTC')->tz(config('app.timezone', 'UTC'))->toDateTimeString()
				];
			}
			$this->line("\nUsers");
			$this->table(array_keys($rows[0]), $rows);
		}
	}

	protected function isVerbosity($verbosity = null) {
		return $this->getOutput()->getVerbosity() >= $this->parseVerbosity($verbosity);
	}
}
