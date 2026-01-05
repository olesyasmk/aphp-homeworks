<?php

require_once 'autoload.php';

use App\Classes\MallardDuck;
use App\Classes\RubberDuck;
use App\Classes\DecoyDuck;
use App\Classes\FlyWithWings;

echo "--- Mallard Duck ---\n";
$mallard = new MallardDuck();
$mallard->display();
$mallard->performQuack();
$mallard->performFly();
$mallard->swim();

echo "\n--- Rubber Duck ---\n";
$rubber = new RubberDuck();
$rubber->display();
$rubber->performQuack();
$rubber->performFly();
$rubber->swim();

echo "\n--- Decoy Duck ---\n";
$decoy = new DecoyDuck();
$decoy->display();
$decoy->performQuack();
$decoy->performFly();
$decoy->swim();

echo "\n--- Model Duck (Dynamic behavior change) ---\n";
echo "Initial behavior:\n";
$decoy->performFly();
echo "Applying rocket powered fly behavior...\n";
$decoy->setFlyBehavior( new FlyWithWings() );
$decoy->performFly();
