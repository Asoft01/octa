<?php
namespace App\Helpers\General;


use Carbon\Carbon;
use \Firebase\JWT\JWT;
use GuzzleHttp\Client;
/**
 * Class Timezone.
 */
class ZoomHelper
{


    public static  function create_meeting($datetime,$minutes,$topic='') {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

        $secret = 'huugTTBhBCBI6dHM6aIDIE24ylXFNPpW3UPi';
        $key='Tm-XecE9Qhuit_vYTVQTtQ';
        $payload = array(
            "iss" => $key,
            'exp' => time() + 3600,
        );
        $token= JWT::encode($payload, $secret);

        try {
            $response = $client->request('POST', '/v2/users/me/meetings', [
                "headers" => [
                    "Authorization" => "Bearer " . $token
                ],
                'json' => [
                    "topic" => $topic!=''?$topic:" Agora Meeting",
                    "type" => 2,
                    "start_time" => $datetime,//"2021-02-06T20:30:00",
                    "duration" => $minutes // 30 mins
                ],
            ]);

            $data = json_decode($response->getBody());

            return [
                'status'=>'success',
                'meeting_url'=>$data->join_url,
                'meeting_password'=>$data->password,
                'datetime'=>$datetime,
                'duration'=>$minutes
            ];


        } catch(Exception $e) {

            return [
                'status'=>'error'
            ];
        }
    }
}
