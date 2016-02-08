<?php
/**
* Russian language for module app_asterisk
*/


$dictionary=array(

/* general */
'ARECORDS'=>'Записи',
'AHELP'=>'Помощь',
'ABOUT'=>'О модуле',
'AHOST'=>'Сервер',
'APORT'=>'Порт',
'ABASE'=>'База',
'ATABLE'=>'Таблица',
'AUSERNAME'=>'Пользователь',
'APASSWORD'=>'Пароль',
'ACLOSE'=>'Закрыть',
'ADD_CDR_TABLE'=>'Добавить таблицу CDR',
'AUPDATE'=>'Обновить',
'ACALLDATE'=>'Дата',
'ASRC'=>'С',
'ADST'=>'На',
'ARECORD'=>'Запись разговора',
'AFILEDIR'=>'Путь',
'AFILENAME'=>'Имя файла',
'ANORECORD'=>'Записи нет',
'ANORECORDS'=>'Записей нет',
'APAGES'=>'Cтраницы',
'ARECPERPAGE'=>'Записей на странице',
'ADURATION'=>'Длительность',
'AHELPHOST'=>'Настройки сервера и базы данных Asterisk.',
'AHELPTABLES_CDR'=>'Подключенные таблицы записей разговоров сервера Asterisk.',
'AHELPTABLE_CDR'=>'Настройки наименования колонок таблицы записей разговоров (чувствительны к регистру). Путь к файлам записей разговоров.'
/* end module names */


);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
