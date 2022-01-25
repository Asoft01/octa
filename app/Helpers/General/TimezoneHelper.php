<?php

namespace App\Helpers\General;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonTimeZone;

/**
 * Class Timezone.
 */
class TimezoneHelper
{
    /**
     * @param Carbon $date
     * @param string $format
     *
     * @return Carbon
     */
    public function convertToLocal(Carbon $date, $format = 'D M j G:i:s T Y'): string
    {
        return $date->setTimezone(auth()->user()->timezone ?? config('app.timezone'))->format($format);
    }

    public function getLocalTimezone(): string
    {
        return auth()->user()->timezone ?? config('app.timezone');
    }

    /**
     * @param Carbon $date
     * @param string $format
     * @param string $timezone
     *
     * @return Carbon
     */
    public function convertToLocalFromTimeZone($timezone,Carbon $date, $format = 'D M j G:i:s T Y'): string
    {
        return $date->setTimezone($timezone ?? config('app.timezone'))->format($format);
    }

    /**
     * @param $date
     *
     * @return Carbon
     */
    public function convertFromLocal($date): Carbon
    {
        return Carbon::parse($date, auth()->user()->timezone)->setTimezone('UTC');
    }

	/**
	 * Returns a collection of CarbonImmutable objects, each representing a different timezone.
	 * By default, the collection is sorted by timezone name.
	 * 
	 * @param boolean $sort_by_offset [optional] If true, multisorts collection by timezone offset and name.
	 * @return Illuminate\Support\Collection
	 */
	public function getTimezones($sort_by_offset = false) {
		$timestamps = array_map(function($id) {
			return CarbonImmutable::now($id);
		}, CarbonTimeZone::listIdentifiers());

		if ($sort_by_offset) {
			return collect($timestamps)->keyBy('tzName')->sort(function($a, $b) {
				return $a->offset <=> $b->offset ?: $a->tzName <=> $b->tzName;
			});
		} else {
			return collect($timestamps)->keyBy('tzName')->sortKeys();
		}
	}
}
