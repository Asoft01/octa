<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use App\Models\AcLiveSchedule;
// use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as CalendarEvent;
// use Spatie\IcalendarGenerator\Enums\ParticipationStatus as CalendarEventParticipation;
use Spatie\CalendarLinks\Link as CalendarLink;

class LiveScheduleController extends Controller {

	public function invite($slug, $format) {
		$schedule = AcLiveSchedule::where('slug', $slug)->firstOrFail();
		// abort_if($schedule->ended_at < now(), 410); // HTTP Code 410 Gone
		switch ($format) {
			case 'google':		return $this->redirectInvite($schedule, 'google');
			case 'yahoo':		return $this->redirectInvite($schedule, 'yahoo');
			case 'outlook':		return $this->redirectInvite($schedule, 'webOutlook');
			case 'download':	return $this->downloadInvite($schedule);
			default:			abort(404);
		}
	}

	protected function redirectInvite($schedule, $function = 'google') {
		$invite = CalendarLink::create($schedule->title, $schedule->started_at, $schedule->ended_at)->description($schedule->description);
		return redirect()->away($invite->$function());
	}

	protected function downloadInvite($schedule) {
		$event = CalendarEvent::create($schedule->title)
			->description($schedule->description)
			->createdAt($schedule->updated_at)
			->startsAt($schedule->started_at)
			->endsAt($schedule->ended_at)
			->url(route('frontend.live'));
		$calendar = Calendar::create('Agora.community Live!')->event($event)->get();
		return response($calendar, 200, [
			'Content-Type' => 'text/calendar',
			'Content-Disposition' => "attachment; filename=\"{$schedule->slug}.ics\"",
			'charset' => 'utf-8',
		]);
	}

}
