<?php

namespace App\Http\Middleware;

use App\Models\AcDomain;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class DomainMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		// Use forced domain if specified in configuration
		$force_id = config('app.force_domain_id');
		if ($force_id !== null) {
			$domain = AcDomain::findOrFail($force_id);
			$request->domain = $domain;
			App::instance(AcDomain::class, $domain);
			return $next($request);
		}

		// Use domain if URL contains a subdomain
		$host = $request->getHost();
		$base = config('app.base_url');
		$prefix = Str::endsWith($host, $base) ? Str::beforeLast($host, $base) : null;
		if ($prefix) {
			$slug = Str::replaceLast('.', '', $prefix);
			$domain = AcDomain::where('slug', $slug)->firstOrFail();
			$request->domain = $domain;
			App::instance(AcDomain::class, $domain);
			return $next($request);
		}

		// Redirect to fallback domain if specified in configuration
		$fallback_id = config('app.fallback_domain_id');
		if ($fallback_id !== null) {
			$domain = AcDomain::findOrFail($fallback_id);
			$url = parse_url($request->fullUrl());
			$url['host'] = "{$domain->slug}.{$base}";
			return redirect(build_url($url));
		}

		// No alternative but to return error 404
		abort(404);
	}

}
