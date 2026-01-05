<?php

namespace App\Classes;

use App\Traits\AppUserAuthentication;
use App\Traits\MobileUserAuthentication;

class AuthService
{
	use AppUserAuthentication, MobileUserAuthentication {
		AppUserAuthentication::authenticate insteadof MobileUserAuthentication;
		AppUserAuthentication::authenticate as authenticateApp;
		MobileUserAuthentication::authenticate as authenticateMobile;
	}
	
	public function checkUser( $login, $password )
	{
		if ( $this->authenticateApp( $login, $password ) ) {
			echo "Пользователь авторизован как «пользователь приложения»\n";
			
			return;
		}
		
		if ( $this->authenticateMobile( $login, $password ) ) {
			echo "Пользователь авторизован как «пользователь мобильного приложения»\n";
			
			return;
		}
		
		echo "Ошибка авторизации: неверный логин или пароль\n";
	}
}
