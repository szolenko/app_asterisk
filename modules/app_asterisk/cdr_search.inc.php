<?php
/*
* @version 0.1
*/
 global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }

$db_host = $this->config['AHOST'];
$db_name = $this->config['ABASE'];
$db_username = $this->config['AUSERNAME'];
$db_password = $this->config['APASSWORD'];
$db_table = $this->config['TABLE_CDR']; //Пока пишу напрямую - потом создать настройку
$cdr_filedir = '/cached/records';
$filedir_cdr = $this->config['FILEDIR_CDR'];
if (!$qry_cdr) $qry_cdr = "1";

// SEARCH FILTERS
  //searching 'CALLDATE' (datetime)
  global $calldate;
  if ($calldate!='') {
    $qry_cdr.=" AND calldate LIKE '%".DBSafe($calldate)."%'";
    $out['CALLDATE']=$calldate;
  }
  //searching 'SRC' (varchar)
  global $src;
  if ($src!='') {
   $qry_cdr.=" AND src LIKE '%".DBSafe($src)."%'";
   $out['SRC'] = $src;
  }

  //searching 'DST' (varchar)
  global $dst;
  if ($dst!='') {
   $qry_cdr.=" AND dst LIKE '%".DBSafe($dst)."%'";
   $out['DST'] = $dst;
  }

  //searching 'DURATION' (varchar)
  global $duration;
  if ($duration!='') {
   $qry_cdr.=" AND duration >= ".DBSafe($duration);
   $out['DURATION']=$duration;
  }
//searching 'RECPERPAGE' (varchar)
  global $recperpage;
  if ($recperpage!='') {
    $out['RECPERPAGE'] = $recperpage;
    $session->data['recperpage'] = $recperpage;
  } 

if (!$session->data['recperpage']) {
 $session->data['recperpage'] = 30;
}

// QUERY READY
 global $save_qry_cdr;
  if ($save_qry_cdr) {
    $qry_cdr = $session->data['qry_cdr'];
    } else {
    $session->data['qry_cdr'] = $qry_cdr;
  };

//FIELDS ORDER
  global $sortby_cdr;
  if (!$sortby_cdr) {
   $sortby_cdr=$session->data['table_cdr_sort'];
  } else {
   if ($session->data['table_cdr_sort']==$sortby_cdr) {
    if (Is_Integer(strpos($sortby_cdr, ' DESC'))) {
     $sortby_cdr=str_replace(' DESC', '', $sortby_cdr);
    } else {
     $sortby_cdr=$sortby_cdr." DESC";
    }
   }
   $session->data['table_cdr_sort']=$sortby_cdr;
  }
  if (!$sortby_cdr) $sortby_cdr="calldate DESC";
  $out['SORTBY_CDR']=$sortby_cdr;



//SEARCH RESULT
$ast_db = mysql_connect($db_host, $db_username, $db_password)
    or die("Could not connect: " . mysql_error());
mysql_select_db($db_name, $ast_db)
    or die("Could not select DB: " . mysql_error());
$qry = mysql_query("SELECT * FROM ".$db_table." WHERE ".$qry_cdr." ORDER BY ".$sortby_cdr)
    or die(mysql_error());
mysql_close($ast_db);

while ($res_cdr[] = mysql_fetch_array($qry,MYSQL_ASSOC)) {
$out['CDRRECORDS'] = $res_cdr;
$out['FILEDIR_CDR'] = $filedir_cdr;
};

// PAGING 
 if ($out['CDRRECORDS'][0]['calldate']){
   paging($out['CDRRECORDS'], $session->data['recperpage'], $out); // search result paging
};

// DELETE CDR RECORD
global $u_id;
if ($this->mode=='cdr_delete') {
if ($u_id) {
$ast_db = mysql_connect($db_host, $db_username, $db_password)
    or die("Could not connect: " . mysql_error());
mysql_select_db($db_name, $ast_db)
    or die("Could not select DB: " . mysql_error());
$qry = mysql_query("SELECT * FROM ".$db_table." WHERE uniqueid='".$u_id."'")
    or die(mysql_error());
  // some action for related tables
if ($qry) {  mysql_query("DELETE FROM ".$db_table." WHERE uniqueid='".$u_id."'");}
mysql_close($ast_db);
 }
}
?>
