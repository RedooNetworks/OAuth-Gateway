<?php
require_once('./config/config.php');

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED);

session_start();

if(!empty($_REQUEST['state'])) {
    $_REQUEST['step'] = 'token';
}

if(empty($_REQUEST['step'])) {
    $Step = 'init';
} else {
    $Step = $_REQUEST['step'];
}

switch($Step) {
	case 'refresh':

		$provider = getProvider($_REQUEST['provider'], array());
		$tokenData = $provider->refreshToken($_REQUEST['token']);
		
		echo json_encode($tokenData);
		exit();
		break;
    case 'init':
        $id = strtolower($_REQUEST['m']);
        $id = preg_replace('/[^a-z0-9-_]/', '', $id);

        $stateString = md5(time().mt_rand(10000,99999));
		$_SESSION['OAUTHSTATE'] = $stateString;		
        $_SESSION[$stateString] = $id;

        $data = getFileContent($id);
        $provider = getProvider($data['type'], $data['parameter']);

        $provider->init($stateString);
        exit();

        break;
    case 'token':
		if(empty($_REQUEST['state'])) {
			$_REQUEST['state'] = $_SESSION['OAUTHSTATE'];
		}

        $stateString = $_REQUEST['state'];
        $id = $_SESSION[$stateString];
        $id = preg_replace('/[^a-z0-9-_]/', '', $id);

        $data = getFileContent($id);
        $provider = getProvider($data['type'], $data['parameter']);

        $tokenData = $provider->getToken();

        @unlink(DATADIR . getFilename($id));

        forward_token_clientsystem($data['url'], $tokenData);
		
		unset($_SESSION['OAUTHSTATE']);
		session_destroy();
		
        $files = glob(DATADIR . '_*');
        foreach($files as $file) {
            if(filemtime($file) < time() - (60 * MINUTES_TO_LOGIN)) {
                unlink($file);
            }
        }

        break;
    default:
        exit();
}


