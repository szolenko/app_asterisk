<?php
/**
* Default language for module app_asterisk
*/


$dictionary=array(

/* general */
'ATITLE'=>'Asterisk CDR',
'ARECORDS'=>'Records',
'ASETTINGS'=>'Settings',
'AHELP'=>'Help',
'ABOUT'=>'About',
'AHOST'=>'Host',
'APORT'=>'Port',
'ABASE'=>'Base',
'ATABLE'=>'Table',
'AUSERNAME'=>'User',
'APASSWORD'=>'Password',
'ACLOSE'=>'Close',
'AUPDATE'=>'Update',
'ADATE'=>'Date',
'AFROM'=>'From',
'ATO'=>'To',
'ARECORD'=>'Call record',
'AFILEDIR'=>'Directory',
'ANORECORD'=>'No record',
'ANORECORDS'=>'No records',
'APAGES'=>'Pages',
'ARECPERPAGE'=>'Records per page'

/* end module names */


);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
