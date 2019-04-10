<?php
ini_set('display_errors', 1);
error_reporting(-1);

require_once('./config/config.php');

use phpseclib\Crypt\AES;
use phpseclib\Crypt\Random;

$hash = hash('sha256', $_POST['url'].microtime(false).'asdFGH-123');

if(empty($_POST['provider'])) exit();
if(empty($_POST['url'])) exit();

$_POST['provider'] = strtolower($_POST['provider']);

$provider = preg_replace('/[^a-z0-9-_]/', '', $_POST['provider']);

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

if(file_exists(PROVIDERDIR . $provider) === false) {
    echo 'Provider mismatch';
	exit();
}

$cipher = new AES(); // could use AES::MODE_CBC
$cipher->setKey(CRYPTKEY);

$cipher->setIV('aabb-123');

$dataArray = array(
	'url' => $_POST['url'],
	'type' => $provider,
);
if(!empty($_POST['parameter'])) {
	$dataArray['parameter'] = $_POST['parameter'];
} else {
	$dataArray['parameter'] = array();
}

$data = json_encode(
    $dataArray
);

file_put_contents(DATADIR . '_'.getFilename($hash), base64_encode($cipher->encrypt($data)));

echo URL . '/request.php?m='.$hash;

$files = glob(DATADIR . '_*');

foreach($files as $file) {
    if(filemtime($file) < time() - (60 * MINUTES_TO_LOGIN)) {
        unlink($file);
    }
}