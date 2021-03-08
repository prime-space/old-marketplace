<?php

class HttpAuth 
{
    /**
     * @var
     */
    public $login = 'steamsells';

    /**
     * @var
     */
    public $password = 'steamsells';

    /**
     * @throws Exception
     */
    public function check()
    {
       
        if($this->hasCredentials() && $this->validCredentials()) {
	} else {
            header('WWW-Authenticate: Basic realm="steamsells.ru"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Access denied';
            exit;
        }
    }

    /**
     * @return bool
     */
    private function hasCredentials()
    {
	if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
		list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		return true;
	}
	return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function validCredentials()
    {
        return $_SERVER['PHP_AUTH_USER'] == $this->login && $_SERVER['PHP_AUTH_PW'] == $this->password;
    }
}
