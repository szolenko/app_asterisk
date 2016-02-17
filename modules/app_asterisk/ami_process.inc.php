<?php
/*
* @version .1 beta
*/

echo date('Y-m-d H:i:s')." : Start\n";
include_once(DIR_MODULES.$this->name.'/phpagi.php');

echo date('Y-m-d H:i:s')." : Include phpagi\n";

$manager = new AGI_AsteriskManager();
$manager->connect(); // если нет файла phpagi.conf то тут можно указать хост, логин, пароль.
echo date('Y-m-d H:i:s')." : Connect manager\n";
$manager->add_event_handler('*', 'dump_events'); // цепляем хендлер на все события которые
                                                   // поступают из AMI и передаем управление
                                                   // функции описанной выше
$manager->wait_response();  // очень полезная вещь, благодаря этой функции скрипт будет
                              // ждать событий и не стопится в отличии от sleep()
$manager->disconnect();



function dump_events($ecode,$data,$server,$port) {
  echo date('Y-m-d H:i:s')." : received event '$ecode' from $server:$port\n";
//  echo($data);
}
