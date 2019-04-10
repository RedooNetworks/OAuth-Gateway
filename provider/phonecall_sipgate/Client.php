<?php

class Client extends BaseClient
{

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://login.sipgate.com/auth/realms/third-party/protocol/openid-connect/auth';
    }
	
    protected function getDefaultScopes()
    {
        return array('all');
    }


    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://login.sipgate.com/auth/realms/third-party/protocol/openid-connect/token';
    }

}