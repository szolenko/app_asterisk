<?php
/*
* @version 0.1
*/
require_once ("app_asterisk_search.inc.php");

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

  // search filters
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
   $this->out['SRC']=$SRC;
  };

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

 global $save_qry;
  if ($save_qry) {
    $qry_cdr = $session->data['cdr_qry'];
  } else {
    $session->data['cdr_qry'] = $qry_cdr;
  }
  if (!$qry_cdr) $qry_cdr = "";

//FIELDS ORDER

global $sortby_cdr;
if (!$sortby_cdr) {
  $sortby_cdr = $col_calldate." DESC";
  } else {
	if ($session->data['sortby_cdr']=='CALLDATE') {
		if (Is_Integer(strpos($sortby_cdr, ' DESC'))) {
			$sortby_cdr=$col_calldate;
			$out['SORTBY']='CALLDATE';
		} else {
			$sortby_cdr=$col_calldate." DESC";
			$out['SORTBY']='CALLDATE DESC';
		}
	}
       if ($session->data['sortby_cdr']=='SRC') {
                if (Is_Integer(strpos($sortby_cdr, ' DESC'))) {
                        $sortby_cdr=$col_src;
			$out['SORTBY']='SRC';
                } else {
                        $sortby_cdr=$col_src." DESC";
			$out['SORTBY']='SRC DESC';
                }
        }
       if ($session->data['sortby_cdr']=='DST') {
                if (Is_Integer(strpos($sortby_cdr, ' DESC'))) {
                        $sortby_cdr=$col_dst;
			$out['SORTBY']='DST';
                } else {
                        $sortby_cdr=$col_dst." DESC";
			$out['SORTBY']='DST DESC';
                }
        }
       if ($session->data['sortby_cdr']=='DURATION') {
                if (Is_Integer(strpos($sortby_cdr, ' DESC'))) {
                        $sortby_cdr=$col_duration;
			$out['SORTBY']='DURATION';
                } else {
                        $sortby_cdr=$col_duration." DESC";
			$out['SORTBY']='DURATION DESC';
		}
	}
 $session->data['sortby_cdr']=$sortby_cdr;
};

//SEARCH RESULT
$ast_db = mysql_connect($db_host, $db_username, $db_password)
    or die("Could not connect: " . mysql_error());
mysql_select_db($db_name, $ast_db)
    or die("Could not select DB: " . mysql_error());
$qry = mysql_query("SELECT * FROM ".$db_table." WHERE 1 ".$qry_cdr." ORDER BY ".$sortby_cdr)
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
	$out['ARECORDS'][$i]['FILEDIR'] = $col_filedir;
	$out['ARECORDS'][$i]['FILENAME'] = $out['ARECORDS'][$i][$col_filename];
};

// PAGING 
 if ($out['ARECORDS'][0]['CALLDATE']){
    paging($out['ARECORDS'], $RECPERPAGE, $out); // search result paging
    $total=count($out['ARECORDS']);
};

mysql_close($ast_db);

?>
