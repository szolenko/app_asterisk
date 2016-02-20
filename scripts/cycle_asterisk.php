<?php
chdir(dirname(__FILE__) . '/../');
include_once("./config.php");
include_once("./lib/loader.php");
include_once("./lib/threads.php");
set_time_limit(0);

// connecting to database
$db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);
include_once("./load_settings.php");
include_once(DIR_MODULES . "control_modules/control_modules.class.php");
$ctl = new control_modules();

include_once(DIR_MODULES . 'app_asterisk/app_asterisk.class.php');
$asterisk = new app_asterisk();
$asterisk->getConfig();
$amihost = $asterisk->config['AMIHOST'];
$amiusername = $asterisk->config['AMIUSERNAME'];
$amipassword = $asterisk->config['AMIPASSWORD'];
if (!$amihost) {
  echo date('Y-m-d H:i:s')." : AMI is not configured. No need to run cycle\n";
   exit;
};


echo date("Y-m-d H:i:s") . " running " . basename(__FILE__) . PHP_EOL;
include(DIR_MODULES . 'app_asterisk/phpagi-asmanager.php');
$manager = new AGI_AsteriskManager();
echo date('Y-m-d H:i:s')." : Connecting to AMI...";
if (!$manager->connect($amihost,$amiusername,$amipassword)) {
    echo "...Connection failed.\n";
	exit;
	} else {
	  echo "...Connection established.\n";
  	  $manager->add_event_handler('*', 'dump_events');
  	  $manager->wait_response();
	}

$latest_check=0;
$checkEvery=5; // poll every 5 seconds
while ($manager) {
   setGlobal((str_replace('.php', '', basename(__FILE__))) . 'Run', time(), 1);
    if ((time()-$latest_check)>$checkEvery) {
	$latest_check=time();
	if (!$manager) {
	  echo date('Y-m-d H:i:s')." : Connection lost";
	}
   }
   if (file_exists('./reboot') || IsSet($_GET['onetime']))
   {
      $manager->disconnect();
      $db->Disconnect();
      exit;
   }
   sleep(1);
}

DebMes("Unexpected close of cycle: " . basename(__FILE__));


function dump_events($ecode,$data,$server,$port) {
    echo date('Y-m-d H:i:s');
    echo " : Event ".$data['Event'];
    echo " | Privilege ". $data['Privilege'];
    echo " | Status ". $data['Status']."\n";
}
