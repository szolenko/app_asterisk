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

// Select CDR tables
$res=SQLSelect("SELECT * FROM `app_asterisk_t_cdr` WHERE 1");
if ($res[0]['ID']) {
  $tot=count($res);
    for($i=0;$i<$tot;$i++) {
      // some action for every record if required
    }
  $out['TABLES_CDR']=$res;
}

// Select CDR tables collumn names & records path
$db_table = $out['TABLES_CDR'][0]['TABLE'];
$col_calldate = $out['TABLES_CDR'][0]['CALLDATE'];
$col_src = $out['TABLES_CDR'][0]['SRC'];
$col_dst = $out['TABLES_CDR'][0]['DST'];
$col_duration = $out['TABLES_CDR'][0]['DURATION'];
$col_filedir = $out['TABLES_CDR'][0]['FILEDIR'];
$col_filename = $out['TABLES_CDR'][0]['FILENAME'];

if (!$qry_cdr) $qry_cdr = "1";

// SEARCH FILTERS
  //searching 'CALLDATE' (datetime)
  global $CALLDATE;
  if ($CALLDATE!='') {
    $qry_cdr.=" AND ".$col_calldate." LIKE '%".DBSafe($CALLDATE)."%'";
    $out['CALLDATE']=$CALLDATE;
  }
  //searching 'SRC' (varchar)
  global $SRC;
  if ($SRC!='') {
   $qry_cdr.=" AND ".$col_src." LIKE '%".DBSafe($SRC)."%'";
   $out['SRC'] = $SRC;
  }

  //searching 'DST' (varchar)
  global $DST;
  if ($DST!='') {
   $qry_cdr.=" AND ".$col_dst." LIKE '%".DBSafe($DST)."%'";
   $out['DST']=$DST;
  }
  //searching 'DURATION' (varchar)
  global $DURATION;
  if ($DURATION!='') {
   $qry_cdr.=" AND ".$col_duration." >= ".DBSafe($DURATION);
   $out['DURATION']=$DURATION;
  }
//searching 'RECPERPAGE' (varchar)
  global $RECPERPAGE;
  if ($RECPERPAGE!='') {
    $out['RECPERPAGE'] = $RECPERPAGE;
  }

// QUERY READY
 global $save_qry_cdr;
  if ($save_qry_cdr) {
    $qry_cdr = $session->data['qry_cdr'];
    } else {
    $session->data['qry_cdr'] = $qry_cdr;
  };

//FIELDS ORDER
global $sortby;
if ($sortby) {
	if ($sortby == $session->data['sortby']) {
		if (Is_Integer(strpos($session->data['sortby_cdr'], ' DESC'))) {
			$sortby_cdr=str_replace(' DESC', '', $session->data['sortby_cdr']);
	                $out['SORTBY'] = $sortby;
			} else {
			$sortby_cdr=$session->data['sortby_cdr']." DESC";
			$out['SORTBY'] = $sortby." DESC";
		};
                $session->data['sortby'] = $sortby;
                $session->data['sortby_cdr'] = $sortby_cdr;
	} else {
		switch ($sortby) {
			case "CALLDATE":
				$sortby_cdr = $col_calldate." DESC";
			break;
                        case "SRC": 
                                $sortby_cdr = $col_src." DESC";
                        break;
                        case "DST": 
                                $sortby_cdr = $col_dst." DESC";
                        break;
                        case "DURATION": 
                                $sortby_cdr = $col_duration." DESC";
                        break;
		};
		$out['SORTBY'] = $sortby." DESC";
		$session->data['sortby'] = $sortby;
		$session->data['sortby_cdr'] = $sortby_cdr;
        }
} else {
	$sortby = $session->data['sortby'];
	$sortby_cdr = $session->data['sortby_cdr'];
	$out['SORTBY'] = $sortby;
};

if (!$sortby_cdr) {
	$sortby_cdr = $col_calldate." DESC";
        $session->data['sortby_cdr'] = $col_calldate." DESC";
        $session->data['sortby'] = "CALLDATE DESC";
        $out['SORTBY'] = "CALLDATE DESC";
};

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
};

$total = count($out['CDRRECORDS']);
for ($i=0;$i<$total;$i++){
	$out['CDRRECORDS'][$i]['CALLDATE'] = $out['CDRRECORDS'][$i][$col_calldate];
	unset($out['CDRRECORDS'][$i][$col_calldate]);
	$out['CDRRECORDS'][$i]['SRC'] = $out['CDRRECORDS'][$i][$col_src];
	unset($out['CDRRECORDS'][$i][$col_src]);
	$out['CDRRECORDS'][$i]['DST'] = $out['CDRRECORDS'][$i][$col_dst];
	unset($out['CDRRECORDS'][$i][$col_dst]);
	$out['CDRRECORDS'][$i]['DURATION'] = $out['CDRRECORDS'][$i][$col_duration];
	unset($out['CDRRECORDS'][$i][$col_duration]);
	$out['CDRRECORDS'][$i]['FILEDIR'] = $col_filedir;
	$out['CDRRECORDS'][$i]['FILENAME'] = $out['CDRRECORDS'][$i][$col_filename];
	unset($out['CDRRECORDS'][$i][$col_filename]);
};

// PAGING 
 if ($out['CDRRECORDS'][0]['CALLDATE']){
   paging($out['CDRRECORDS'], $RECPERPAGE, $out); // search result paging
   $total=count($out['CDRRECORDS']);
};

?>
