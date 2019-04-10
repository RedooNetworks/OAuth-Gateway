<?php
require_once('Client.php');

class Provider extends BaseProvider
{

    public function init($stateString) {
        $client = new \Client([
            'clientId'          => CLIENT_ID,
            'clientSecret'      => CLIENT_SECRET,
            'redirectUri'       => URL.'/request.php',
        ]);

        header('Location:'.$client->getAuthorizationUrl(array(
            'state' => $stateString,
        )));
    }

    public function getToken() {
        $client = new \Client([
            'clientId'          => CLIENT_ID,
            'clientSecret'      => CLIENT_SECRET,
            'redirectUri'       => URL.'/request.php',
        ]);

        $token = $client->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        $tokenData = $token->jsonSerialize();
        $tokenData['clientid'] = CLIENT_ID;
        $tokenData['created'] = time();
        $tokenData['expires_in'] = $token->getExpires();

        return $tokenData;
    }



}