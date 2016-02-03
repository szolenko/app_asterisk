<?php
/**
* Asterisk 
* @package project
* @author Sergii Zolenko <szolenko@gmail.com>
* @version 0.1 (Feb 01, 2016)
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
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
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
  if ($this->view_mode=='' || $this->view_mode=='search_app_asterisk') {
   $this->search_app_asterisk($out);
  }
  if ($this->view_mode=='edit_app_asterisk') {
   $this->edit_app_asterisk($out, $this->id);
  }
  if ($this->view_mode=='delete_app_asterisk') {
   $this->delete_app_asterisk($this->id);
   $this->redirect("?");
  }
  if ($this->view_mode=='search_cdr') {
   $this->search_cdr($out, $this->id);
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
}
/**
* app_asterisk search
*
* @access public
*/
 function search_app_asterisk(&$out) {
  require(DIR_MODULES.$this->name.'/app_asterisk_search.inc.php');
 }
/**
* app_asterisk edit/add
*
* @access public
*/
 function edit_app_asterisk(&$out, $id) {
  require(DIR_MODULES.$this->name.'/app_asterisk_edit.inc.php');
 }
/**
* app_asterisk delete record
*
* @access public
*/
 function delete_app_asterisk($id) {
  $rec=SQLSelectOne("SELECT * FROM app_asterisk WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM app_asterisk WHERE ID='".$rec['ID']."'");
 }
/**
* cdr search
*
* @access public
*/
 function search_cdr(&$out) {
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
  SQLExec('DROP TABLE IF EXISTS app_asterisk');
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
 app_asterisk: ID int(10) unsigned NOT NULL auto_increment
 app_asterisk: TYPE varchar(255) NOT NULL DEFAULT ''
 app_asterisk: TABLE varchar(255) NOT NULL DEFAULT ''
 app_asterisk: CALLDATE varchar(255) NOT NULL DEFAULT ''
 app_asterisk: SRC varchar(255) NOT NULL DEFAULT ''
 app_asterisk: DST varchar(255) NOT NULL DEFAULT ''
 app_asterisk: DURATION varchar(255) NOT NULL DEFAULT ''
 app_asterisk: FILEDIR varchar(255) NOT NULL DEFAULT ''
 app_asterisk: FILENAME varchar(255) NOT NULL DEFAULT ''
EOD;
  parent::dbInstall($data);
 }
// --------------------------------------------------------------------
}
