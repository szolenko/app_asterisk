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

$parent_class_rec = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'Telephony'");
if ($parent_class_rec['ID'])
    {
	$class_rec = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'AsteriskAMI' AND PARENT_ID = '".$parent_class_rec['ID']."'");
	if ($class_rec['ID'])
	    {
		$obj_rec = SQLSelectOne("SELECT * FROM objects WHERE CLASS_ID='".$class_rec['ID']."'");
		if ($obj_rec['ID'])
		    {
			$amihost = getGlobal($obj_rec['TITLE'].'.amihost');
			$amiport = getGlobal($obj_rec['TITLE'].'.amiport');
			$amiusername = getGlobal($obj_rec['TITLE'].'.amiusername');
			$amipassword = getGlobal($obj_rec['TITLE'].'.amipassword');
		    }
	    }
    }

if (!$amihost)
    {
	echo date('Y-m-d H:i:s')." : AMI is not configured. No need to run cycle\n";
	exit;
    };


echo date("Y-m-d H:i:s") . " running " . basename(__FILE__) . PHP_EOL;
include('./lib/phpagi/phpagi-asmanager.php');
$manager = new AGI_AsteriskManager();
echo date('Y-m-d H:i:s')." : Connecting to AMI... ";
if (!$manager->connect($amihost.":".$amiport,$amiusername,$amipassword))
    {
	echo " ...Connection failed.\n";
	exit;
    } else {
	echo " ...Connection established.\n";
  	$manager->add_event_handler('*', 'dump_events');
    }

$manager->wait_response();

$latest_check=0;
$checkEvery=10; // poll every 1 seconds
while ($manager->Ping())
  {
    if ((time()-$latest_check)>$checkEvery)
	  {
		setGlobal((str_replace('.php', '', basename(__FILE__))) . 'Run', time(), 1);
		$latest_check=time();
  	  }
	if (file_exists('./reboot') || IsSet($_GET['onetime']))
	  {
    	$manager->disconnect();
    	$db->Disconnect();
    	exit;
  	  }
  }

DebMes("Unexpected close of cycle: " . basename(__FILE__));


function dump_events($ecode,$data,$server,$port)
  {
// Search exist event
	$parent_class_rec = SQLSelectOne("SELECT ID FROM classes WHERE TITLE LIKE 'Telephony'");
	if ($parent_class_rec['ID'])
	  {
		$class_rec = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'AsteriskAMI' AND PARENT_ID = '".$parent_class_rec['ID']."'");
		if ($class_rec['ID'])
		  {
			$object_rec = SQLSelectOne("SELECT * FROM objects WHERE CLASS_ID='" . $class_rec['ID'] . "' AND TITLE = 'Asterisk'");
			if ($object_rec['ID'])
			  {
				$method_rec=SQLSelectOne("SELECT * FROM methods WHERE OBJECT_ID='".$object_rec['ID']."' AND TITLE = 'Event".$data['Event']."'");
				if (!$method_rec['ID'])
				  {
// New event. Add method to class AsteriskAMI and object
// Add class method
					echo date('Y-m-d H:i:s')." : Receive new event. Add method Event".$data['Event']."\n";
					$class_method_rec['CLASS_ID'] = $class_rec['ID'];
					$class_method_rec['TITLE'] = 'Event'.$data['Event'];
					$class_method_rec['CODE'] = "echo date('Y-m-d H:i:s')".".\" : Event \".".'$params[\'Event\']'.".\" received. Process... \\n\"".";\n";
        				SQLInsert('methods', $class_method_rec);
// Add object method
					$method_rec['OBJECT_ID'] = $object_rec['ID'];
					$method_rec['TITLE'] = 'Event'.$data['Event'];
					$method_rec['CALL_PARENT'] .= "1";
					$method_rec['CODE'] .= "/**\n";
					$method_rec['CODE'] .= "Array received form Asterisk:\n";
					foreach ($data as $key => $value)
					     {
		  				$method_rec['CODE'] .= "	[".$key."] => ".$value." \n";
					     }
					$method_rec['CODE'] .= "*/\n";
        				SQLInsert('methods', $method_rec);
				 } else {
// Event already exist. Process event
  					    $method = $object_rec['TITLE'].".Event".$data['Event'];
					    callMethod($method, $data);
					}
			}
		    }
	    }
    }
