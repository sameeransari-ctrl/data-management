<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DocCheckService
{
      
    protected $clientId;

    protected $clientSecret;

    protected $accessType;

    protected $docCheckAPIUrl;

    protected $timeOut;
        
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->clientId = config('doccheck.client_id');
        $this->clientSecret = config('doccheck.client_secret');
        $this->accessType = config('doccheck.grant_type');
        $this->docCheckAPIUrl = config('doccheck.doccheck_api_url');
        $this->timeOut = config('doccheck.doccheck_api_response_time_out');
    }

    /**
     * Method accessToken
     *
     * @param array $fields
     *
     * @return array
     */
    private function _accessToken(array $fields)
    {
        $accessTokenAPIUri = $this->docCheckAPIUrl.'access_token/';

        $response = Http::timeout($this->timeOut)->asForm()->post($accessTokenAPIUri, $fields);

        $accessTokenArray = json_decode($response, true);
        $accessTokenArray = $accessTokenArray ?? [];
        
        return $accessTokenArray;
    }
    
    /**
     * Method UserData
     *
     * @param string $code 
     *
     * @return array
     */
    public function userData(string $code):array
    {
        $docCheckUserArray = [];
        $fields = [
            'client_id' => $this->clientId,        
            'client_secret' => $this->clientSecret,        
            'code' => $code,        
            'grant_type' => $this->accessType,      
        ];
        $accessTokenArray = $this->_accessToken($fields);
       
        if ($accessTokenArray && !empty($accessTokenArray['access_token'])) {
            $docCheckAccessToken = $accessTokenArray['access_token'];

            $userDataAPIUri = $this->docCheckAPIUrl.'user_data/v2/';

            $response = Http::timeout($this->timeOut)->asForm()->post($userDataAPIUri, ['access_token' => $docCheckAccessToken]);
            $docCheckUserData = json_decode($response, true);
            $docCheckUserData = $docCheckUserData ?? [];
            if ($docCheckUserData && !empty($docCheckUserData['uniquekey'])) {
                $docCheckUserArray = $docCheckUserData;
            }
        }
        return $docCheckUserArray;
    }

    /**
     * Method checkToken
     *
     * @param string $accessToken
     *
     * @return void
     */
    public function checkToken(string $accessToken)
    {
        $checkTokenAPIUri = $this->docCheckAPIUrl.'access_token/checkToken.php';
        $response = Http::withHeaders(
            [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        )->post($checkTokenAPIUri);
        return $response;
    }
    
    /**
     * Method refreshToken
     *
     * @param $fields $fields [explicite description]
     *
     * @return void
     */
    public function refreshToken($fields)
    {
        $accessTokenAPIUri = $this->docCheckAPIUrl.'access_token/';
        $response = Http::post($accessTokenAPIUri, $fields);
        return $response;
    }

}
