<?php

namespace App\Models;

use App\Models\AcContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AcPlaylist extends Model {

	public function getMorphClass() {
		return 'MorphPlaylist';
	}

	public function content(){
		return $this->morphOne('App\Models\AcContent', 'contentable');
	}

	public function contents() {
		return $this->belongsToMany('App\Models\AcContent', 'ac_content_playlists', 'playlist_id', 'content_id')->withPivot('display_order');
	}

	public function mentor() {
		return $this->belongsTo('App\Models\AcAccount', 'user_id', 'user_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\Auth\User', 'user_id');
	}

	public function getImage($cotd = false) {
		$contents = $this->contents->sortBy('pivot.display_order');
		if ($cotd) {
			return $this->poster ?: $contents->pluck('contentable.poster_cotd')->first() ?: $contents->pluck('contentable.poster')->first();
		}
		return $this->poster ?: $contents->pluck('contentable.poster')->first();
	}

	public function sortContents($sort = 'pivot.display_order') {
		$this->setRelation('contents', $this->contents->sortBy($sort)->values());
		return $this;
	}

	public function hasWatchedContents() {
		return Auth::check() && $this->contents->whereNotNull('metrics')->isNotEmpty();
	}

	public function getCurrentAttribute() {
		return Auth::check()
			? $this->contents->whereNotNull('metrics')->sortBy('metrics.0.updated_at')->last()
			: $this->contents->sortBy('pivot.display_order')->first();
	}
}
