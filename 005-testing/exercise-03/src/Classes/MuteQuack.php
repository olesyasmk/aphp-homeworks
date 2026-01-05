<?php

namespace App\Classes;

use App\Interfaces\QuackBehavior;

class MuteQuack implements QuackBehavior
{
	public function quack()
	{
		echo "<< Silence >>\n";
	}
}
