<?php
set_include_path(realpath(dirname(__FILE__).DIRECTORY_SEPARATOR . '..') . PATH_SEPARATOR . get_include_path());
require_once('vendor/autoload.php');
require_once('BaseProvider.php');
require_once('BaseClient.php');

/**
 * Please configure the following settings
 */

define("URL", 'https://oauth.redoo.network/');

/**
 * Key to use for encryption
 */
define('HASHSALT', 'df\3WMW:N2dzsG29{(2t');
define('CRYPTKEY', '6Y=2dk&ZZp:TdL6j&69h');

define('PRIVATEKEY', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'privatekey.pem');
define('PUBLICKEY', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'publickey.pem');

/**
 *
 */
define('HASHITERATE', 5);

define("MINUTES_TO_LOGIN", 15);

/**
 * Expert Settings
 */
define('DATADIR', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' ). DIRECTORY_SEPARATOR);

define('PROVIDERDIR', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'provider' ). DIRECTORY_SEPARATOR);

/**
 * Do not change something below this line
 *
 ******************************************************************************************************************
 */
use phpseclib\Crypt\AES;
@mkdir(DATADIR);
function getFilename($hash, $_internal_counter = 0) {
    if($_internal_counter == HASHITERATE) {
        return $hash;
    }

    return getFilename(hash('sha256', $hash.HASHSALT), $_internal_counter + 1);
}

/**
 * @param $id
 */
function getFileContent($id) {

    $filepath = DATADIR . '_'.getFilename($id);
    if(file_exists($filepath) === false) die('Expired');

    $content = file_get_contents($filepath);

    $cipher = new AES(); // could use AES::MODE_CBC
    $cipher->setKey(CRYPTKEY);

    $cipher->setIV('aabb-123');

    $return = json_decode($cipher->decrypt(base64_decode(trim($content))), true);

    if(empty($return)) {
        die('Expired');
    }

    return $return;
}

/**
 * @param $provider string
 * @return Provider
 */
function getProvider($provider, $parameter) {
    $providerPath = PROVIDERDIR . $provider . DIRECTORY_SEPARATOR . 'constant.php';
    if(file_exists($providerPath)) {
        require_once($providerPath);
    }

    $providerPath = PROVIDERDIR . $provider . DIRECTORY_SEPARATOR . 'Provider.php';
    require_once($providerPath);

    $class = new \Provider($parameter);

    return $class;
}

function forward_token_clientsystem($forward_url, $tokenData) {
    $_POST = $tokenData;

    require_once('forward.php');
}