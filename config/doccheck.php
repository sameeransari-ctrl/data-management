<?php
return [
    /**
     * DocCheck credintials
     */
    'client_id' => env('DOCCHECK_CLIENT_ID'),
    'client_secret' => env('DOCCHECK_CLIENT_SECRET'),
    'doccheck_api_url' => env('DOCCHECK_API_URL', 'https://login.doccheck.com/service/oauth/'),
    'grant_type' => env('DOCCHECK_ACCESS_TYPE', 'authorization_code'),
    'doccheck_api_response_time_out' => env('DOCCHECK_RESPONSE_TIME_OUT', 120), //in seconds

];