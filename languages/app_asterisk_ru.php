<?php
/**
* Russian language for module app_asterisk
*/


$dictionary=array(

/* general */
'ATITLE'=>'Asterisk звонки',
'ARECORDS'=>'Записи',
'ASETTINGS'=>'Настройки',
'AHELP'=>'Помощь',
'ABOUT'=>'О модуле',
'AHOST'=>'Сервер',
'APORT'=>'Порт',
'ABASE'=>'База',
'ATABLE'=>'Таблица',
'AUSERNAME'=>'Пользователь',
'APASSWORD'=>'Пароль',
'ACLOSE'=>'Закрыть',
'AUPDATE'=>'Обновить',
'ADATE'=>'Дата',
'AFROM'=>'С',
'ATO'=>'На',
'ARECORD'=>'Запись',
'AFILEDIR'=>'Путь',
'ANORECORDS'=>'Записей нет',
'APAGES'=>'Cтраницы',
'ARECPERPAGE'=>'Записей на странице'
/* end module names */


);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
