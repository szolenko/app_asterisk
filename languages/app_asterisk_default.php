<?php
/**
* Default language for module app_asterisk
*/


$dictionary=array(

/* general */
'ARECORDS'=>'Records',
'AHELP'=>'Help',
'ABOUT'=>'About',
'AHOST'=>'Host',
'APORT'=>'Port',
'ABASE'=>'Base',
'ATABLE'=>'Table',
'ATABLE_CDR'=>'CDR Table',
'AUSERNAME'=>'User',
'APASSWORD'=>'Password',
'ACLOSE'=>'Close',
'ADD_CDR_TABLE'=>'Add CDR Table',
'AUPDATE'=>'Update',
'ACALLDATE'=>'Date',
'ASRC'=>'From',
'ADST'=>'To',
'ARECORD'=>'Call record',
'AFILEDIR_CDR'=>'CDR Directory',
'AFILENAME'=>'File name',
'ANORECORD'=>'No record',
'ANORECORDS'=>'No records',
'APAGES'=>'Pages',
'ARECPERPAGE'=>'Records per page',
'ADURATION'=>'Duration',
'AHELPHOST'=>'Asterisk server and database settings',
'AHELPAMI'=>'AMI settings',
'AHELPTABLES_CDR'=>'Connected CDR server Asterisk table',
'AHELPTABLE_CDR'=>'Settings Astersk CDR table column names (case sensitive). Path to call records files'

/* end module names */


);

foreach ($dictionary as $k=>$v) {
 if (!defined('LANG_'.$k)) {
  define('LANG_'.$k, $v);
 }
}
