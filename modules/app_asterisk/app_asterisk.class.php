<?
/**
* Asterisk
*
*
* @package project
* @author Alien <szolenko@gmail.com>
*/
//
//
class app_asterisk extends module {
/**
* Asterisk
*
* Module class constructor
*
* @access private
*/
function app_asterisk() {
  $this->name="app_asterisk";
  $this->title="<#LANG_ATITLE#>";
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
function saveParams() {
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
  if ($this->single_rec) {
   $out['SINGLE_REC']=1;
  }
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
  $out['A_HOST']=$this->config['A_HOST'];
  $out['A_PORT']=$this->config['A_PORT'];
  $out['A_BASE']=$this->config['A_BASE'];
  $out['A_TABLE']=$this->config['A_TABLE'];
  $out['A_USERNAME']=$this->config['A_USERNAME'];
  $out['A_PASSWORD']=$this->config['A_PASSWORD'];
  $out['A_FILEDIR']=$this->config['A_FILEDIR'];
  
  if (!$out['A_HOST']) {
    $out['A_HOST']='localhost';
  }

if (!$out['A_PORT']) {
    $out['A_PORT']='3306';
  }

if (!$out['A_BASE']) {
    $out['A_BASE']='asterisk';
  }

if (!$out['A_TABLE']) {
    $out['A_TABLE']='cdr';
  }
  
  if (!$out['A_USERNAME']) {
    $out['A_USERNAME']='root';
  }
  
  if (!$out['A_FILEDIR']) {
    $out['A_FILEDIR']='/cached/records/';
  }
  
if ($this->view_mode=='update_settings') {
   global $a_host;
   global $a_port;
   global $a_base;
   global $a_table;
   global $a_username;
   global $a_password;
   global $a_filedir;
   $this->config['A_HOST']=$a_host;
   $this->config['A_PORT']=$a_port;
   $this->config['A_BASE']=$a_base;
   $this->config['A_TABLE']=$a_table;
   $this->config['A_USERNAME']=$a_username;
   $this->config['A_PASSWORD']=$a_password;
   $this->config['A_FILEDIR']=$a_filedir;
   $this->saveConfig();
   $this->redirect("?"); 
  }

      require (DIR_MODULES.$this->name.'/search_records.inc.php');
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

    $asterisk = new app_asterisk();
    $asterisk->getConfig();

}
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($parent_name="") {
  parent::install($parent_name);
 }
// --------------------------------------------------------------------
}
?>
