<?php


abstract class BaseProvider
{
	private $_Parameter = array();
	
	public function __construct($parameter) {
		$this->_Parameter = $parameter;
	}
	protected function getParameter() {
		return $this->_Parameter;
	}

    /**
     * @param $stateString
     * @return void
     */
    abstract public function init($stateString);

    /**
     * @return mixed
     */
    abstract public function getToken();
    
	/**
	 *  @brief Brief description
	 *  
	 *  @param [in] $refresh_token Refresh token to get new access token
	 *  @return Return description
	 *  
	 *  @details More details
	 */
	abstract public function refreshToken($refresh_token);
}