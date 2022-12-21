<?php
require_once('C:/laragon/www/kapp/hash/php-kaltura-client/KalturaClient.php');

//Load config details from INI file
$config = parse_ini_file(dirname(__file__) . DIRECTORY_SEPARATOR . 'config.ini');

date_default_timezone_set($config['timezone']); //set the expected timezone

$partnerId = $config['PID'];
$appTokenId = $config['appTokenId'];
$appToken = $config['token'];


echo "PID " . $partnerId;
echo "appTokenId " . $appTokenId;
echo "appToken " . $appToken;


//Configure Kaltura client
$config = new KalturaConfiguration($partnerId);
$config->serviceUrl = 'http://www.kaltura.com';
$client = new KalturaClient($config);


echo date('r') . ": Creating widget session\n";

//Start widget session and add the ks to the Client object
$widgetKs = startWidgetSession($partnerId,$client);
$client->setKs($widgetKs);

//Compute hash
$hash = getHash($widgetKs, $appToken);

echo 'HASH: ' . $hash;

echo date('r') . ": Creating app token session\n";

//Start App Token session and update the ks on the client object to be the app token ks
$ks = startSession($appTokenId,$hash,$client);
$client->setKs($ks);

echo date('r') . ": Verifying session...\n";

if(testSession($client)){
	echo date('r') . ": Success! ks = " . $ks . "\n";
}else{
	echo date('r') . ": Error, ks unable to do a baseEntry.list action\n";
}


function startWidgetSession($partnerId,$client){
	$widgetId = '_' . $partnerId;
	$session = '';
	$expiry = '86400';

	try {
		$result = $client->session->startWidgetSession($widgetId, $expiry);
		$session = $result->ks;
	} catch (Exception $e) {
		echo $e->getMessage();
	}

	return $session;

}

function getHash($wKs,$token){
	$theHash = hash('SHA1', $wKs . $token);
	return $theHash;
}


function startSession($id,$hash,$client){
	$id = $id;
	$tokenHash = $hash;
	$userId = "";
	$type = KalturaSessionType::ADMIN;
	$expiry = 0;
	$sessionPrivileges = "";
	$session = '';

	try {
		$result = $client->appToken->startSession($id, $tokenHash, $userId, $type, $expiry, $sessionPrivileges);
		$session = $result->ks;
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	return $session;

}


function testSession($client){
	$filter = new KalturaBaseEntryFilter();
	$pager = new KalturaFilterPager();

	try {
		$result = $client->baseEntry->listAction($filter, $pager);
		//var_dump($result);
		if($result->totalCount > 10){
			return true;
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	return false;

}



?>
