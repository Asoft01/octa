<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcLiveSchedule extends Model {
	protected $dates = ['eventDatetime', 'created_at', 'updated_at'];
	protected $table = 'ac_live_schedules';
	protected $guarded = [];
	
	public function account() {
		return $this->belongsTo('App\Models\AcAccount');
	}

	public function getDurationAttribute() {
		return $this->eventDuration;
	}

	public function getDatetimeAttribute() {
		return $this->eventDatetime;
	}

	public function getStartedAtAttribute() {
		return $this->eventDatetime;
	}
	
	public function getEndedAtAttribute() {
		return $this->eventDatetime->addMinutes($this->eventDuration);
	}
}
