<?php

require_once __DIR__ . '/autoload.php';

use App\Classes\Person;
use App\Classes\PeopleList;

header( 'Content-Type: text/plain; charset=utf-8' );

$person = new Person( "Ivan", 25, "vanya_pro" );
echo "Original object:\n" . $person . "\n";

$serialized = serialize( $person );
echo "Serialized string:\n" . $serialized . "\n\n";

echo "--- Replacement (Same length: vanya_pro -> vanya_new) ---\n";
$newSerializedSame = str_replace( "vanya_pro", "vanya_new", $serialized );
echo "Modified string (same length):\n" . $newSerializedSame . "\n";

try {
	$unserializedSame = unserialize( $newSerializedSame );
	echo "Restored object (same length):\n" . $unserializedSame . "\n";
}
catch ( \Exception $e ) {
	echo "Error during unserialize (same length): " . $e->getMessage() . "\n";
}

echo "\n--- Replacement (Different length: vanya_pro -> ivan) ---\n";
echo "Note: This will likely fail because PHP serialization stores string lengths.\n";

$newSerializedDiff = str_replace( "vanya_pro", "ivan", $serialized );

echo "Modified string (diff length):\n" . $newSerializedDiff . "\n";

$unserializedDiff = @unserialize( $newSerializedDiff );

if ( $unserializedDiff === false ) {
	echo "Unserialize FAILED as expected (length mismatch).\n";
}
else {
	echo "Restored object (diff length):\n" . $unserializedDiff . "\n";
}

echo "\n--- Correcting Different length (ivan) ---\n";

$oldVal = "vanya_pro";
$newVal = "ivan";
$oldLen = strlen( $oldVal );
$newLen = strlen( $newVal );

$correctedSeralized = str_replace(
	's:' . $oldLen . ':"' . $oldVal . '"',
	's:' . $newLen . ':"' . $newVal . '"',
	$serialized
);

try {
	$unserializedCorrected = unserialize( $correctedSeralized );
	echo "Restored object (corrected length):\n" . $unserializedCorrected . "\n";
}
catch ( \Exception $e ) {
	echo "Correction failed: " . $e->getMessage() . "\n";
}

echo "--- Iterator Demonstration ---\n";
$list = new PeopleList();
$list->addPerson( new Person( "Alice", 22, "alice_99" ) );
$list->addPerson( new Person( "Bob", 30, "bob_builder" ) );
$list->addPerson( new Person( "Charlie", 28, "char_star" ) );

foreach ( $list as $p ) {
	echo $p;
}
