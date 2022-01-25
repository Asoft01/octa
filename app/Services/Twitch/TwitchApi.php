<?php

namespace App\Services\Twitch;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class TwitchApi {
	
	protected $access_token, $client, $configuration, $validated_at;

	public function __construct($config = []) {
		$this->configuration = array_merge([
			// 'client_id' => null,
			// 'client_secret' => null,
			// 'user_id' => null,
			// 'user_login' => null,
			'use_cache' => true,
			'validate_after' => 3600
		], $config);

		if ($this->config('use_cache')) {
			$this->access_token = Cache::get('twitchapi.access_token');
			$this->validated_at = Cache::get('twitchapi.validated_at');
		}
		
		$this->revalidate();
	}

	public function request($method, $uri, $query = []) {
		$options = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->access_token,
				'Client-Id' => $this->config('client_id'),
			],
			'query' => $query
		];
		return $this->client()->request($method, $uri, $options);
	}

	public function requestAccessToken() {
		$uri = 'https://id.twitch.tv/oauth2/token';
		$options = [
			'query' => [
				'client_id' => $this->config('client_id'),
				'client_secret' => $this->config('client_secret'),
				'grant_type' => 'client_credentials'
			]
		];
		$response = $this->client()->request('POST', $uri, $options);
		$body = json_decode($response->getBody()->getContents());

		$this->access_token = $body->access_token;
		$this->validated_at = now()->timestamp;

		if ($this->config('use_cache')) {
			Cache::put('twitchapi.access_token', $this->access_token, $body->expires_in);
			Cache::put('twitchapi.validated_at', $this->validated_at);
		}
	}

	public function validateAccessToken() {
		if ($this->access_token == null) {
			throw new Exception('Cannot validate null access token.');
		}

		$uri = 'https://id.twitch.tv/oauth2/validate';
		$options = [
			'headers' => [
				'Authorization' => 'OAuth ' . $this->access_token,
			],
			'http_errors' => false
		];
		$response = $this->client()->request('GET', $uri, $options);
		
		if ($response->getStatusCode() == 200) {
			$body = json_decode($response->getBody()->getContents());

			$this->validated_at = now()->timestamp;

			if ($this->config('use_cache')) {
				Cache::put('twitchapi.access_token', $this->access_token, $body->expires_in);
				Cache::put('twitchapi.validated_at', $this->validated_at);
			}

			return true;
		}

		return false;
	}

	public function client() {
		return $this->client ?: $this->client = new Client(['base_uri' => 'https://api.twitch.tv/helix/']);
	}

	public function config($key = null, $default = null) {
		return $key === null ? $this->configuration : Arr::get($this->configuration, $key, $default);
	}

	public function revalidate($force = false) {
		if ($force) {
			if (!$this->validateAccessToken()) {
				$this->requestAccessToken();
			}
		}
		else if ($this->access_token) {
			if (now()->subSeconds($this->config('validate_after'))->timestamp >= $this->validated_at) {
				if (!$this->validateAccessToken()) {
					$this->requestAccessToken();
				}
			}
		} else {
			$this->requestAccessToken();
		}
	}

	public function resizeChannelImage($url, $width, $height) {
		$pattern = '/-(\d{1,4}|\{width\})x(\d{1,4}|\{height\})(\.gif|\.jpeg|\.jpg|\.png)$/';
		$replace = '-'.$width.'x'.$height.'${3}';
		return preg_replace($pattern, $replace, $url);
	}

	public function fallbackChannelImage() {
		return 'https://static-cdn.jtvnw.net/cf_vods/d2nvs31859zcd8/562a9105f8b0a6076e26_offlinetv_42362232078_1618773637//thumb/thumb0-320x180.jpg';
	}
}