<?php

error_reporting(0);
ini_set('display_errors', 0);



header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');                  
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Pragma: no-cache'); 
header('Expires: 0'); 



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/// includ database si ma conectez ///

include 'db.inc.php';
$dbhost = 'localhost';
$dbuser = 'doarbitcoin';
$dbpass = 'gigikent39';
$dbname = 'doarbitcoin';
$db = new db($dbhost, $dbuser, $dbpass, $dbname);

/// includ database si ma conectez ///


$service = $db->query('SELECT * FROM users limit 1000')->fetchAll();

	foreach ($service as $row) {
	
	    $rows[$row['username']] = $row['hex'];
	}

$rows = array('names' => $rows);

//afisam
echo json_encode($rows);



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// exemplu
//{
//  "names": {
//    "Lenuta": "7ef534f919116c4940bb589098359bcde61801f63f01e2be34d6d277bece028f",
//    "Vasile": "753d025936c8c3238b1b2b2f748be6df92743c2201e5198946e9d6a29156793f"
//  }
//}


$db->close();

?>