<?php
/**
* Ukrainian language for module app_asterisk
*/


$dictionary=array(

/* general */
'ARECORDS'=>'Записи',
'AHELP'=>'Допомога',
'ABOUT'=>'Про модуль',
'AHOST'=>'Сервер',
'APORT'=>'Порт',
'ABASE'=>'База',
'ATYPE'=>'Тип таблиці',
'ATABLE'=>'Таблиця',
'AUSERNAME'=>'Користувач',
'APASSWORD'=>'Пароль',
'ACLOSE'=>'Закрити',
'ADD_CDR_TABLE'=>'Додати таблицю CDR',
'AUPDATE'=>'Оновити',
'ACALLDATE'=>'Дата',
'ASRC'=>'Від',
'ADST'=>'Кому',
'ARECORD'=>'Запис розмови',
'AFILEDIR'=>'Шлях',
'AFILENAME'=>'Ім\'я файлу',
'ANORECORD'=>'Запис відсутній',
'ANORECORDS'=>'Записи відсутні',
'APAGES'=>'Сторінки',
'ARECPERPAGE'=>'Записів на сторінці',
'ADURATION'=>'Тривалість',
'AHELPHOST'=>'Налаштування серверу та бази даних Asterisk.',
'AHELPTABLE_CDR'=>'Налаштування найменувань стовбців таблиці записів розмов (чутливі до регістру). Шлях до файлів записів розмов.'

/* end module names */


);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
