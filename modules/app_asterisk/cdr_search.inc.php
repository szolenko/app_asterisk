<?php
/*
* @version 0.1
*/
require ("app_asterisk_search.inc.php");

$db_host = $this->config['AHOST'];
$db_name = $this->config['ABASE'];
$db_username = $this->config['AUSERNAME'];
$db_password = $this->config['APASSWORD'];
$db_table = $out['RESULT'][0]['TABLE'];
$col_calldate = $out['RESULT'][0]['CALLDATE'];
$col_src = $out['RESULT'][0]['SRC'];
$col_dst = $out['RESULT'][0]['DST'];
$col_duration = $out['RESULT'][0]['DURATION'];
$col_filedir = $out['RESULT'][0]['FILEDIR'];
$col_filename = $out['RESULT'][0]['FILENAME'];

global $session;
 if ($this->owner->name=='panel') {
     $out['CONTROLPANEL']=1;
  }
  $qry_cdr="1";

  // search filters
  //searching 'CALLDATE' (datetime)
  global $calldate;
  if ($calldate!='') {
    $qry_cdr.=" AND ".$col_calldate." LIKE '%".DBSafe($calldate)."%'";
    $out['calldate']=$calldate;
  }

  //searching 'SRC' (varchar)
  global $src;
  if ($src!='') {
   $qry_cdr.=" AND ".$col_src." LIKE '%".DBSafe($src)."%'";
   $out['src']=$src;
  }

  //searching 'DST' (varchar)
  global $dst;
  if ($dst!='') {
   $qry_cdr.=" AND ".$col_dst." LIKE '%".DBSafe($dst)."%'";
   $out['dst']=$dst;
  }

  //searching 'DURATION' (varchar)
  global $duration;
  if ($duration!='') {
   $qry_cdr.=" AND ".$col_duration." >= '%".DBSafe($duration)."%'";
   $out['duration']=$duration;
  }


  //searching 'RECPERPAGE' (varchar)
  global $recperpage;
  if ($recperpage!='') {
   $out['RECPERPAGE']=$recperpage;
 }

  global $save_qry;
  if ($save_qry) {
    $qry_cdr=$session->data['asrerisk_qry'];
  } else {
    $session->data['asterisk_qry']=$qry_cdr;
  }
  if (!$qry_cdr) $qry_cdr="1";

//FIELDS ORDER

 global $sortby_asterisk;
  if (!$sortby_asterisk) {
   $sortby_asterisk=$session->data['asterisk_sort'];
  } else {
   if ($session->data['asterisk_sort']==$sortby_asterisk) {
    if (Is_Integer(strpos($sortby_asterisk, ' DESC'))) {
     $sortby_asterisk=str_replace(' DESC', '', $sortby_asterisk);
    } else {
     $sortby_asterisk=$sortby_asterisk." DESC";
    }
   }
   $session->data['asterisk_sort']=$sortby_asterisk;
  }
  $sortby_asterisk=$col_calldate." DESC";
  $out['SORTBY']=$sortby_asterisk;


//SEARCH RESULT
$ast_db = mysql_connect($db_host, $db_username, $db_password)
    or die("Could not connect: " . mysql_error());
mysql_select_db($db_name, $ast_db)
    or die("Could not select DB: " . mysql_error());
$qry = mysql_query("SELECT * FROM ".$db_table." WHERE ".$qry_cdr." ORDER BY ".$sortby_asterisk)
    or die(mysql_error());

while ($res_cdr[] = mysql_fetch_array($qry,MYSQL_ASSOC)) {
$out['ARECORDS'] = $res_cdr;
};

$total = count($out['ARECORDS']);
for ($i=0;$i<$total;$i++){
	$out['ARECORDS'][$i]['CALLDATE'] = $out['ARECORDS'][$i][$col_calldate];
	$out['ARECORDS'][$i]['SRC'] = $out['ARECORDS'][$i][$col_src];
	$out['ARECORDS'][$i]['DST'] = $out['ARECORDS'][$i][$col_dst];
	$out['ARECORDS'][$i]['DURATION'] = $out['ARECORDS'][$i][$col_duration];
	$out['ARECORDS'][$i]['FILEDIR'] = $out['ARECORDS'][$i][$col_filedir];
	$out['ARECORDS'][$i]['FILENAME'] = $out['ARECORDS'][$i][$col_filename];
};

// PAGING 
 if ($out['ARECORDS'][0]['CALLDATE']){
    paging($out['ARECORDS'], $recperpage, $out); // search result paging
    $total=count($out['ARECORDS']);
};

mysql_close($ast_db);

?>
