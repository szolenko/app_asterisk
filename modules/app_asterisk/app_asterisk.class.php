<?php
/**
* Asterisk 
* @package project
* @author Sergii Zolenko <szolenko@gmail.com>
* @version 0.2 beta (Feb 08, 2016)
*/
//
//
class app_asterisk extends module {
/**
* app_asterisk
*
* Module class constructor
*
* @access private
*/
function app_asterisk() {
  $this->name="app_asterisk";
  $this->title="Asterisk";
  $this->module_category="<#LANG_SECTION_APPLICATIONS#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->data_source)) {
  $p["data_source"]=$this->data_source;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $data_source;
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($data_source)) {
   $this->data_source=$data_source;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['DATA_SOURCE']=$this->data_source;
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {

// LOG
global $ajax; 
global $filter;
if ($ajax) {
    header ("HTTP/1.0: 200 OK\n");
    header ('Content-Type: text/html; charset=utf-8');
    $limit=50;
// Find last midifed
$filename=ROOT.'debmes/log_*-cycle_asterisk.php.txt';
foreach(glob($filename) as $file) {      
    $LastModified[] = filemtime($file);
    $FileName[] = $file;
}
$files = array_multisort($LastModified, SORT_NUMERIC, SORT_ASC, $FileName);
$lastIndex = count($LastModified) - 1;
// Open file
$data=LoadFile( $FileName[$lastIndex] );    
$lines=explode("\n", $data);
$lines=array_reverse($lines);
$res_lines=array();
$total=count($lines);
$added=0;
for($i=0;$i<$total;$i++) {
    if (trim($lines[$i])=='') {
	continue;
    }
    if ($filter && preg_match('/'.preg_quote($filter).'/is', $lines[$i])) {
	$res_lines[]=$lines[$i];
	$added++;
	} elseif (!$filter) {
	    $res_lines[]=$lines[$i];
	    $added++;
    }
    if ($added>=$limit) {
	break;
    }
}
echo implode("<br/>", $res_lines);
exit;
}

$this->getConfig();
//Database
  $out['AHOST']=$this->config['AHOST'];
	if (!$out['AHOST']) {
		$out['AHOST']='localhost';
	}	
  $out['ABASE']=$this->config['ABASE'];
	if (!$out['ABASE']) {
	  $out['ABASE']='asterisk';
  }
  $out['AUSERNAME']=$this->config['AUSERNAME'];
	if (!$out['AUSERNAME']) {
	  $out['AUSERNAME']='root';
  }
  $out['APASSWORD']=$this->config['APASSWORD'];
// AMI
  $out['AMIHOST']=$this->config['AMIHOST'];
  $out['AMIUSERNAME']=$this->config['AMIUSERNAME'];
  $out['AMIPASSWORD']=$this->config['AMIPASSWORD'];

//TABLES
  $out['TABLE_CDR']=$this->config['TABLE_CDR'];
  $out['FILEDIR_CDR']=$this->config['FILEDIR_CDR'];

if ($this->view_mode=='update_settings') {
	global $ahost;
	$this->config['AHOST']=$ahost;
	global $abase;
	$this->config['ABASE']=$abase;
	global $ausername;
	$this->config['AUSERNAME']=$ausername;
	global $apassword;
	$this->config['APASSWORD']=$apassword;
	global $amihost;
	$this->config['AMIHOST']=$amihost;
	global $amiusername;
	$this->config['AMIUSERNAME']=$amiusername;
	global $amipassword;
	$this->config['AMIPASSWORD']=$amipassword;
    global $table_cdr;
    $this->config['TABLE_CDR']=$table_cdr;
    global $filedir_cdr;
    $this->config['FILEDIR_CDR']=$filedir_cdr;
	$this->saveConfig();
	$this->redirect("?");
}
if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
	$out['SET_DATASOURCE']=1;
}
  if ($this->data_source=='app_asterisk' || $this->data_source=='') {
	if ($this->view_mode=='' || $this->view_mode=='app_asterisk_admin') {
		$this->app_asterisk_admin($out);
	}
  }

if ($this->data_source=='cdr_asterisk') {
        if ($this->view_mode=='' || $this->view_mode=='cdr_search') {
                $this->cdr_search($out);
        		if ($this->mode=='cdr_delete') {
                	$this->cdr_search($out);
        		}
        }
        if ($this->view_mode=='log_search') {
                $this->log_search($out);
        }

}

}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
  $this->admin($out);
if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
	$out['SET_DATASOURCE']=1;
}
  if ($this->data_source=='cdr_asterisk' || $this->data_source=='') {
        if ($this->view_mode=='' || $this->view_mode=='cdr_search') {
                $this->cdr_search($out);
        }
  }

}

/**
* app_asterisk admin
*
* @access public
*/
 function app_asterisk_admin(&$out) {
 }

/**
* log search
*
* @access public
*/
 function log_search(&$out) {
 }

/**
* cdr search
*
* @access public
*/
 function cdr_search(&$out) {
  require(DIR_MODULES.$this->name.'/cdr_search.inc.php');
 }

 function processSubscription($event, $details='') {
 $this->getConfig();
  if ($event=='SAY') {
   $level=$details['level'];
   $message=$details['message'];
   //...
  }
 }

 function processCycle() {
//  $this->getConfig();
//  require(DIR_MODULES.$this->name.'/ami_process.inc.php');
 }

/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();
 }
/**
* Uninstall
*
* Module uninstall routine
*
* @access public
*/
 function uninstall() {
  parent::uninstall();
 }
// --------------------------------------------------------------------
}
