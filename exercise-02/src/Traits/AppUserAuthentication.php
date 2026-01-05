<?php

namespace App\Traits;

trait AppUserAuthentication
{
    private $appLogin = 'admin';
    private $appPassword = 'password123';


    public function authenticate($login, $password)
    {
        return $this->appLogin === $login && $this->appPassword === $password;
    }
}
