<?php
/**
* Asterisk 
* @package project
* @author Sergii Zolenko <szolenko@gmail.com>
* @version 0.1 beta (Feb 08, 2016)
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
$this->getConfig();
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

if ($this->view_mode=='update_settings') {
	global $ahost;
	$this->config['AHOST']=$ahost;
	global $abase;
	$this->config['ABASE']=$abase;
	global $ausername;
	$this->config['AUSERNAME']=$ausername;
	global $apassword;
	$this->config['APASSWORD']=$apassword;
	$this->saveConfig();
	$this->redirect("?");
}
if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source']) {
	$out['SET_DATASOURCE']=1;
}
  if ($this->data_source=='app_asterisk' || $this->data_source=='') {
	if ($this->view_mode=='' || $this->view_mode=='search_cdr_tables') {
		$this->search_cdr_tables($out);
	}
	if ($this->view_mode=='edit_cdr_tables') {
		$this->edit_cdr_tables($out, $this->id);
	}
	if ($this->view_mode=='delete_cdr_tables') {
		$this->delete_cdr_tables($this->id);
		$this->redirect("?");
	}
  }

  if ($this->data_source=='cdr_asterisk') {
        if ($this->view_mode=='' || $this->view_mode=='cdr_search') {
                $this->cdr_search($out);
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
* app_asterisk search
*
* @access public
*/
 function search_cdr_tables(&$out) {
  require(DIR_MODULES.$this->name.'/cdr_tables_search.inc.php');
 }
/**
* app_asterisk edit/add
*
* @access public
*/
 function edit_cdr_tables(&$out, $id) {
  require(DIR_MODULES.$this->name.'/cdr_tables_edit.inc.php');
 }
/**
* app_asterisk delete record
*
* @access public
*/
 function delete_cdr_tables($id) {
  $rec=SQLSelectOne("SELECT * FROM app_asterisk_t_cdr WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM app_asterisk_t_cdr WHERE ID='".$rec['ID']."'");
 }
/**
* cdr search
*
* @access public
*/
 function cdr_search(&$out) {
  require(DIR_MODULES.$this->name.'/cdr_search.inc.php');
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
  SQLExec('DROP TABLE IF EXISTS app_asterisk_t_cdr');
  parent::uninstall();
 }
/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall() {
/*
app_asterisk - 
*/
  $data = <<<EOD
 app_asterisk_t_cdr: ID int(10) unsigned NOT NULL auto_increment
 app_asterisk_t_cdr: TABLE varchar(255) NOT NULL DEFAULT ''
 app_asterisk_t_cdr: CALLDATE varchar(255) NOT NULL DEFAULT ''
 app_asterisk_t_cdr: SRC varchar(255) NOT NULL DEFAULT ''
 app_asterisk_t_cdr: DST varchar(255) NOT NULL DEFAULT ''
 app_asterisk_t_cdr: DURATION varchar(255) NOT NULL DEFAULT ''
 app_asterisk_t_cdr: FILEDIR varchar(255) NOT NULL DEFAULT ''
 app_asterisk_t_cdr: FILENAME varchar(255) NOT NULL DEFAULT ''
EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
