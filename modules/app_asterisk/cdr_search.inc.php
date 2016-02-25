<?php
/*
* @version 1.0
*/
 global $session;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }

$class_rec = SQLSelectOne("SELECT ID FROM classes WHERE TITLE LIKE '".$this->title."'");
if ($class_rec['ID'])
  {
	$obj_rec = SQLSelectOne("SELECT ID FROM objects WHERE CLASS_ID='".$class_rec['ID']."'");
	if ($obj_rec['ID'])
	{
		$ahost_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='ahost')");
		$db_host = $ahost_rec['VALUE'];
		$abase_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='abase')");
		$db_name = $abase_rec['VALUE'];
		$ausername_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='ausername')");
		$db_username = $ausername_rec['VALUE'];
		$apassword_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='apassword')");
		$db_password = $apassword_rec['VALUE'];
		$db_table_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='table_cdr')");
		$db_table = $db_table_rec['VALUE'];
		$filedir_cdr_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='filedir_cdr')");
		$filedir_cdr = $filedir_cdr_rec['VALUE'];
	}
  }

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
if ($db_table){
  $ast_db = mysql_connect($db_host, $db_username, $db_password) or die("Could not connect: " . mysql_error());
  mysql_select_db($db_name, $ast_db) or die("Could not select DB: " . mysql_error());
  $qry = mysql_query("SELECT * FROM ".$db_table." WHERE ".$qry_cdr." ORDER BY ".$sortby_cdr) or die(mysql_error());
  mysql_close($ast_db);
  } else {
	Debmes ("Asterisk table CDR not present");
}

while ($res_cdr[] = mysql_fetch_array($qry,MYSQL_ASSOC)) {
$out['CDRRECORDS'] = $res_cdr;
$out['FILEDIR_CDR'] = $filedir_cdr;
};

// PAGING 

 if ($out['CDRRECORDS'][0]['calldate']){
   paging($out['CDRRECORDS'], $session->data['recperpage'], $out); // search result paging
};
global $page;
if (!$page) {
    $page = $this->session['NUM'];
    $out['NUM'] = $page;
  } else {
    $this->session['NUM'] = $page;
    $out['NUM'] = $page;
}




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
