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
	$class_rec = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'Asterisk' AND PARENT_ID = '".$parent_class_rec['ID']."'");
	  if ($class_rec['ID'])
		{
		$obj_rec = SQLSelectOne("SELECT * FROM objects WHERE CLASS_ID='".$class_rec['ID']."'");
		if ($obj_rec['ID'])
		  {
			$amihost = getGlobal($obj_rec['TITLE'].'.amihost');
			$amiusername = getGlobal($obj_rec['TITLE'].'.amiusername');
			$amipassword = getGlobal($obj_rec['TITLE'].'.amipassword');
		  }
		}
	}

if (!$amihost) {
  echo date('Y-m-d H:i:s')." : AMI is not configured. No need to run cycle\n";
   exit;
};


echo date("Y-m-d H:i:s") . " running " . basename(__FILE__) . PHP_EOL;
include('./lib/phpagi/phpagi-asmanager.php');
$manager = new AGI_AsteriskManager();
echo date('Y-m-d H:i:s')." : Connecting to AMI...";
if (!$manager->connect($amihost,$amiusername,$amipassword)) {
    echo "...Connection failed.\n";
	exit;
	} else {
	  echo "...Connection established.\n";
  	  $manager->add_event_handler('*', 'dump_events');
	}

$latest_check=0;
$checkEvery=1; // poll every 1 seconds
while ($manager->Ping()) {
   setGlobal((str_replace('.php', '', basename(__FILE__))) . 'Run', time(), 1);
    if ((time()-$latest_check)>$checkEvery) {
	$latest_check=time();
	if (!$manager->Ping()) {
	  echo date('Y-m-d H:i:s')." : Connection lost";
	  exit;
	}
    $manager->wait_response();
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


function dump_events($ecode,$data,$server,$port)
  {
// Search exist event
	$parent_class_rec = SQLSelectOne("SELECT ID FROM classes WHERE TITLE LIKE 'Telephony'");
	if ($parent_class_rec['ID'])
	  {
		$class_rec = SQLSelectOne("SELECT * FROM classes WHERE TITLE LIKE 'Asterisk' AND PARENT_ID = '".$parent_class_rec['ID']."'");
		if ($class_rec['ID'])
		  {
			$object_rec = SQLSelectOne("SELECT * FROM objects WHERE CLASS_ID='" . $class_rec['ID'] . "' AND TITLE = '".$class_rec['TITLE']."'");
			if ($object_rec['ID'])
			  {
				$method_rec=SQLSelectOne("SELECT * FROM methods WHERE CLASS_ID='".$class_rec['ID']."' AND TITLE='".$data['Event']."'");
				if ($method_rec['ID'])
				  {
					// Event already exist. Process event
					$method = $object_rec['TITLE'].".".$method_rec['TITLE'];
					processEvent($method, $data);
				  } else {
					// New event. Add method to class Asterisk
					addEvent($class_rec['ID'], $data);
				  }
			  }
		  }
	}
  }

// Add new event as method in class Asterisk
function addEvent($class, $data)
  {
	  echo date('Y-m-d H:i:s');
	  echo " : Receive new event. Add Method: ".$data['Event']."\n";
      $rec = array();
	  $rec['CLASS_ID'] = $class;
      $rec['TITLE'] = $data['Event'];
	  $rec['CODE'] = "// Array received form Asterisk:\n";
	  $rec['CODE'] .= "/*\n";
	  foreach ($data as $key => $value)
		{
		  		 $rec['CODE'] .= "[".$key."] => ".$value." \n";
		}
		 $rec['CODE'] .= "*/\n";
         SQLInsert('methods', $rec);
  }

// Process event from method of class Asterisk
function processEvent($method, $data)
  {
	  callMethod($method, $data);
  }


