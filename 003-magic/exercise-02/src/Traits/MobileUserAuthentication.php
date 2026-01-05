<?php

namespace App\Traits;

trait MobileUserAuthentication
{
    private $mobileLogin = 'mobile_user';
    private $mobilePassword = 'mobile_pass';

    public function authenticate($login, $password)
    {
        return $this->mobileLogin === $login && $this->mobilePassword === $password;
    }
}
