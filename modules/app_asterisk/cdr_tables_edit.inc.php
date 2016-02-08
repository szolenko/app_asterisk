<?php
/*
* @version 0.1
*/
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }
  $table_name='app_asterisk_t_cdr';
  $rec=SQLSelectOne("SELECT * FROM $table_name WHERE ID='$id'");
  if ($this->mode=='update') {
   $ok=1;

  //updating 'table' (varchar)
   global $table;
   $rec['TABLE']=$table;
   if ($rec['TABLE']=='') {
    $out['ERR_TABLE']=1;
    $ok=0;
   }
  //updating 'calldate' (varchar)
   global $calldate;
   $rec['CALLDATE']=$calldate;
  //updating 'src' (varchar)
   global $src;
   $rec['SRC']=$src;
  //updating 'dst' (varchar)
   global $dst;
   $rec['DST']=$dst;
  //updating 'duration' (varchar)
   global $duration;
   $rec['DURATION']=$duration;
  //updating 'filedir' (varchar)
   global $filedir;
   $rec['FILEDIR']=$filedir;
  //updating 'filename' (varchar)
   global $filename;
   $rec['FILENAME']=$filename;
  //UPDATING RECORD
   if ($ok) {
    if ($rec['ID']) {
     SQLUpdate($table_name, $rec); // update
    } else {
     $new_rec=1;
     $rec['ID']=SQLInsert($table_name, $rec); // adding new record
    }
    $out['OK']=1;
   } else {
    $out['ERR']=1;
   }
  }
  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
