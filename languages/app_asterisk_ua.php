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
'ATABLE'=>'Таблиця',
'ATABLE_CDR'=>'Таблиця CDR',
'AUSERNAME'=>'Користувач',
'APASSWORD'=>'Пароль',
'ACLOSE'=>'Закрити',
'ADD_CDR_TABLE'=>'Додати таблицю CDR',
'AUPDATE'=>'Оновити',
'ACALLDATE'=>'Дата',
'ASRC'=>'Від',
'ADST'=>'Кому',
'ARECORD'=>'Запис розмови',
'AFILEDIR_CDR'=>'Шлях до файлів CDR',
'AFILENAME'=>'Ім\'я файлу',
'ANORECORD'=>'Запис відсутній',
'ANORECORDS'=>'Записи відсутні',
'APAGES'=>'Сторінки',
'ARECPERPAGE'=>'Записів на сторінці',
'ADURATION'=>'Тривалість',
'AHELPHOST'=>'Налаштування серверу та бази даних Asterisk',
'AHELPAMI'=>'Налаштування AMI',
'AHELPTABLES_CDR'=>'Приєднана таблиця записів розмов серверу Asterisk',
'AHELPTABLE_CDR'=>'Налаштування найменувань стовбців таблиці записів розмов (чутливі до регістру). Шлях до файлів записів розмов'

/* end module names */


);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
