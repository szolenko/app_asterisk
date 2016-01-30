<?php
/**
* Ukrainian language for module app_asterisk
*/


$dictionary=array(

/* general */
'ATITLE'=>'Asterisk дзвінки',
'ARECORDS'=>'Записи',
'ASETTINGS'=>'Налаштування',
'AHELP'=>'Допомога',
'ABOUT'=>'Про модуль',
'AHOST'=>'Сервер',
'APORT'=>'Порт',
'ABASE'=>'База',
'ATABLE'=>'Таблиця',
'AUSERNAME'=>'Користувач',
'APASSWORD'=>'Пароль',
'ACLOSE'=>'Закрити',
'AUPDATE'=>'Оновити',
'ADATE'=>'Дата',
'AFROM'=>'Від',
'ATO'=>'Кому',
'ARECORD'=>'Запис розмови',
'AFILEDIR'=>'Шлях',
'ANORECORD'=>'Запис відсутній',
'ANORECORDS'=>'Записи відсутні',
'APAGES'=>'Сторінки',
'ARECPERPAGE'=>'Записів на сторінці'

/* end module names */


);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
