<?php
/*
* @version 0.1 (wizard)
*/

global $session;
 if ($this->owner->name=='panel') {
     $out['CONTROLPANEL']=1;
  }
  $qry="1";

  // search filters
  //searching 'CALLDATE' (datetime)
  global $calldate;
  if ($calldate!='') {
    $qry.=" AND calldate LIKE '%".DBSafe($calldate)."%'";
    $out['calldate']=$calldate;
  }

  //searching 'SRC' (varchar)
  global $src;
  if ($src!='') {
   $qry.=" AND src LIKE '%".DBSafe($src)."%'";
   $out['src']=$src;
  }

  //searching 'DST' (varchar)
  global $dst;
  if ($dst!='') {
   $qry.=" AND dst LIKE '%".DBSafe($dst)."%'";
   $out['dst']=$dst;
  }

  //searching 'DURATION' (varchar)
  global $duration;
  if ($duration!='') {
   $qry.=" AND duration >= '%".DBSafe($duration)."%'";
   $out['duration']=$duration;
  }


  //searching 'RECPERPAGE' (varchar)
  global $recperpage;
  if ($recperpage!='') {
   $out['recperpage']=$recperpage;
  }

//  global $save_qry;
//  if ($save_qry) {
//    $qry=$session->data['asrerisk_qry'];
//  } else {
//    $session->data['asterisk_qry']=$qry;
//  }
//  if (!$qry) $qry="1";

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
  $sortby_asterisk="calldate DESC";
  $out['SORTBY']=$sortby_asterisk;


//SEARCH RESULT
$db_host = $this->config['A_HOST'];
$db_port = $this->config['A_PORT'];
$db_name = $this->config['A_BASE'];
$db_username = $this->config['A_USERNAME'];
$db_password = $this->config['A_PASSWORD'];
$db_table = $this->config['A_TABLE'];
$ast_db = mysql_connect($db_host, $db_username, $db_password)
    or die("Could not connect: " . mysql_error());
mysql_select_db($db_name, $ast_db)
    or die("Could not select DB: " . mysql_error());
$qry = mysql_query("SELECT * FROM $db_table WHERE $qry ORDER BY ".$sortby_asterisk)
    or die(mysql_error());

while ($res[] = mysql_fetch_row($qry,MYSQL_ASSOC)) {
    $out['ARECORDS'] = $res;
}

 if ($out['ARECORDS'][0]['calldate']){
    paging($out['ARECORDS'], $recperpage, $out); // search result paging
    $total=count($res);
};

mysql_close($ast_db);

?>