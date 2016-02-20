<?php
/*
* @version .1 beta
*/

/*
echo date('Y-m-d H:i:s')." : Start\n";
include_once(DIR_MODULES.$this->name.'/phpagi.php');

echo date('Y-m-d H:i:s')." : Include phpagi\n";

$manager = new AGI_AsteriskManager();
$manager->connect(); // if not exist phpagi.conf send host,login,password
echo date('Y-m-d H:i:s')." : Connect manager\n";
$manager->add_event_handler('*', 'dump_events');
$manager->wait_response();
//$manager->disconnect();


function dump_events($ecode,$data,$server,$port) {
  echo date('Y-m-d H:i:s')." : received event '$ecode' from $server:$port\n";
  print_r($data);
}
*/