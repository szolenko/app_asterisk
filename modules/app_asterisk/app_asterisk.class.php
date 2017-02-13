<?php
/**
* Asterisk 
* @package project
* @author Sergii Zolenko <szolenko@gmail.com>
* @version 1.1.2 (13.02.2017)
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
  $class_rec = SQLSelectOne("SELECT * FROM classes WHERE TITLE = 'AsteriskAMI'");
  if ($class_rec['ID'])
	{
	  $obj_rec = SQLSelectOne("SELECT * FROM objects WHERE CLASS_ID='".$class_rec['ID']."'");
	  if ($obj_rec['ID'])
		{
		$ahost_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='ahost')");
		$out['AHOST'] = $ahost_rec['VALUE'];
		$abase_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='abase')");
		$out['ABASE'] = $abase_rec['VALUE'];
		$ausername_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='ausername')");
		$out['AUSERNAME'] = $ausername_rec['VALUE'];
		$apassword_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='apassword')");
		$out['APASSWORD'] = $apassword_rec['VALUE'];
		$amihost_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='amihost')");
		$out['AMIHOST'] = $amihost_rec['VALUE'];
		$amiport_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='amiport')");
		$out['AMIPORT'] = $amiport_rec['VALUE'];
		$amiusername_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='amiusername')");
		$out['AMIUSERNAME'] = $amiusername_rec['VALUE'];
		$amipassword_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='amipassword')");
		$out['AMIPASSWORD'] =  $amipassword_rec['VALUE'];
		$table_cdr_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='table_cdr')");
		$out['TABLE_CDR'] = $table_cdr_rec['VALUE'];
		$filedir_cdr_rec = SQLSelectOne("SELECT VALUE from pvalues where property_id = (SELECT ID FROM properties WHERE OBJECT_ID='".$obj_rec['ID']."' AND TITLE='filedir_cdr')");
		$out['FILEDIR_CDR'] = $filedir_cdr_rec['VALUE'];
		}
    }

if ($this->view_mode=='update_settings') {
	global $ahost;
	global $abase;
	global $ausername;
	global $apassword;
	global $amihost;
	global $amiport;
	global $amiusername;
	global $amipassword;
	global $table_cdr;
	global $filedir_cdr;

$class_rec = SQLSelectOne("SELECT ID FROM classes WHERE TITLE = 'AsteriskAMI'");
  if ($class_rec['ID'])
	{
	  $obj_rec = SQLSelectOne("SELECT * FROM objects WHERE CLASS_ID = '".$class_rec['ID']."'");
	  if ($obj_rec['ID'])
		{
		  $propName = array('ahost', 'abase', 'ausername', 'apassword', 'amihost', 'amiport','amiusername', 'amipassword', 'table_cdr', 'filedir_cdr');
		  $propValue = array($ahost, $abase, $ausername, $apassword, $amihost, $amiport, $amiusername, $amipassword, $table_cdr, $filedir_cdr);
		  for ($i = 0; $i < count($propName); $i++)
			{
  			  $prop_rec = SQLSelectOne("SELECT * FROM properties WHERE CLASS_ID='" . $class_rec['ID'] . "' AND OBJECT_ID='" . $obj_rec['ID'] . "' AND TITLE = '".DBSafe($propName[$i])."'");
    			    if ($prop_rec['ID'])
    			    {
				  $value_rec = SQLSelectOne("SELECT ID from pvalues where PROPERTY_NAME ='".$obj_rec['TITLE'].".".$propName[$i]."'");
				  if (!$value_rec['ID'])
				    {
      					$value_rec = array();
        				$value_rec['VALUE'] = $propValue[$i];
		      			$value_rec['PROPERTY_ID'] = $prop_rec['ID'];
        				$value_rec['OBJECT_ID'] = $obj_rec['ID'];
					$value_rec['PROPERTY_NAME'] = $obj_rec['TITLE'].".".$propName[$i];
					SQLInsert('pvalues', $value_rec);
				  } else
				    {
        				$value_rec['VALUE'] = $propValue[$i];
					SQLUpdate('pvalues', $value_rec);
				    }
        		    }
  			}
		}

	}
    $this->redirect("?");
}

if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source'])
    {
	$out['SET_DATASOURCE']=1;
    }

  if ($this->data_source=='app_asterisk' || $this->data_source=='')
    {
	if ($this->view_mode=='' || $this->view_mode=='app_asterisk_admin')
	    {
		$this->app_asterisk_admin($out);
	    }
    }

if ($this->data_source=='cdr_asterisk')
    {
        if ($this->view_mode=='' || $this->view_mode=='cdr_search')
	    {
                $this->cdr_search($out);
        	if ($this->mode=='cdr_delete')
		    {
                	$this->cdr_search($out);
        	    }
    	    }
        if ($this->view_mode=='log_search')
	    {
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
function usual(&$out)
    {
	$this->admin($out);
	if (isset($this->data_source) && !$_GET['data_source'] && !$_POST['data_source'])
	    {
		$out['SET_DATASOURCE']=1;
	    }
	if ($this->data_source=='cdr_asterisk' || $this->data_source=='')
	    {
    		if ($this->view_mode=='' || $this->view_mode=='cdr_search')
		    {
            		$this->cdr_search($out);
    		    }
	    }

    }

/**
* app_asterisk admin
*
* @access public
*/
 function app_asterisk_admin(&$out)
    {
    }

/**
* log search
*
* @access public
*/
 function log_search(&$out)
    {
    }

/**
* cdr search
*
* @access public
*/
 function cdr_search(&$out)
    {
	require(DIR_MODULES.$this->name.'/cdr_search.inc.php');
    }

function processSubscription($event, $details='')
  {
	$this->getConfig();
	if ($event=='SAY')
	  {
		$level=$details['level'];
		$message=$details['message'];
  		//...
	  }
  }

/**
* Install
*
* Module installation routine
*
* @access private
*/

// Add classes and object
 function install($data='') {
  $parent_class_rec = SQLSelectOne("SELECT * FROM classes WHERE TITLE = 'Telephony'");
  if (!$parent_class_rec['ID'])
	{
  	    $parent_class_rec = array();
    	    $parent_class_rec['TITLE'] = 'Telephony';
    	    $parent_class_rec['DESCRIPTION'] = "Класс телефонии";
    	    $parent_class_rec['ID'] = SQLInsert('classes', $parent_class_rec);
	}
  $class_rec = SQLSelectOne("SELECT ID FROM classes WHERE TITLE = 'AsteriskAMI' AND PARENT_ID = '".$parent_class_rec['ID']."'");
	if (!$class_rec['ID'])
	  {
	    $class_rec = array();
    	    $class_rec['TITLE'] = 'AsteriskAMI';
    	    $class_rec['PARENT_ID'] = $parent_class_rec['ID'];
    	    $class_rec['PARENT_LIST'] = $parent_class_rec['ID'];
    	    $class_rec['DESCRIPTION'] = "Класс ip-телефонии Asterisk";
    	    $class_rec['ID'] = SQLInsert('classes', $class_rec);
  	  }
  $obj_rec = SQLSelectOne("SELECT ID FROM objects WHERE CLASS_ID = '".$class_rec['ID']."'");
  if (!$obj_rec['ID'])
	{
  	    $obj_rec = array();
    	    $obj_rec['TITLE'] = $this->title;
	    $obj_rec['CLASS_ID'] = $class_rec['ID'];
    	    $obj_rec['DESCRIPTION'] = "Платформа IP-телефонии Asterisk";
    	    $obj_rec['ID'] = SQLInsert('objects', $obj_rec);
	}

// Add properties
  $propName = array('status', 'ahost', 'abase', 'ausername', 'apassword', 'amihost', 'amiport','amiusername', 'amipassword', 'table_cdr', 'filedir_cdr');
  $propDescription = array('Статус cервера: 1 - включен, 0 - выключен', 'Mysql сервер', 'Mysql база', 'Mysql пользователь', 'Mysql пароль', 'Хост AMI', 'Порт AMI', 'Пользователь AMI', 'Пароль AMI', 'Имя таблицы CDR', 'Путь к файлам записей разговоров');
  for ($i = 0; $i < count($propName); $i++)
	{
  	  $prop_rec = SQLSelectOne("SELECT ID FROM properties WHERE CLASS_ID='" . $class_rec['ID'] . "' AND TITLE LIKE '" . DBSafe($propName[$i]) . "'");
      if (!$prop_rec['ID'])
    	{
      	  $prop_rec = array();
          $prop_rec['CLASS_ID'] = $class_rec['ID'];
          $prop_rec['OBJECT_ID'] = $obj_rec['ID'];
          $prop_rec['TITLE'] = $propName[$i];
          $prop_rec['DESCRIPTION'] = $propDescription[$i];
          $prop_rec['ID'] = SQLInsert('properties', $prop_rec);
         }
    }

// Add class methods
$class_method_rec = SQLSelectOne("SELECT ID FROM methods WHERE CLASS_ID='" . $class_rec['ID'] . "' AND TITLE LIKE 'Action'");
if (!$class_method_rec['ID'])
    {
	$class_method_rec = array();
    	$class_method_rec['CLASS_ID'] = $class_rec['ID'];
    	$class_method_rec['TITLE'] = 'Action';
    	$class_method_rec['DESCRIPTION'] = 'Команды серверу Asterisk';
    	$class_method_rec['ID'] = SQLInsert('methods', $class_method_rec);
    }

// Add object methods
$method_rec = SQLSelectOne("SELECT ID FROM methods WHERE OBJECT_ID='" . $obj_rec['ID'] . "' AND TITLE LIKE 'Action'");
if (!$method_rec['ID'])
    {
	$method_rec = array();
        $method_rec['OBJECT_ID'] = $obj_rec['ID'];
        $method_rec['TITLE'] = "Action";
	$method_rec['CALL_PARENT'] = "1";
	$method_rec['CODE']  = '$command = $params'."['command'];\n";
	$method_rec['CODE'] .= '$option = $params'."['option'];\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= '$amihost = $this'."->getProperty('amihost');\n";
	$method_rec['CODE'] .= '$amiport = $this'."->getProperty('amiport');\n";
	$method_rec['CODE'] .= '$amiusername = $this'."->getProperty('amiusername');\n";
	$method_rec['CODE'] .= '$amipassword = $this'."->getProperty('amipassword');\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= "include_once ('./lib/phpagi/phpagi-asmanager.php');\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= 'if (!$params'."['command']) {\n";
	$method_rec['CODE'] .= "  DebMes (\" Asterisk : Can't process empty command\");\n";
	$method_rec['CODE'] .= "  exit;\n";
	$method_rec['CODE'] .= "};\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= 'if (!$amihost) '."{\n";
	$method_rec['CODE'] .= "  DebMes (\" Asterisk : Can't process command - AMI is not configured\");\n";
	$method_rec['CODE'] .= "  exit;\n";
	$method_rec['CODE'] .= "};\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= '$com_man'." = new AGI_AsteriskManager();\n";
	$method_rec['CODE'] .= 'if (!$com_man->connect($amihost.'."\":\"".'.$amiport, $amiusername, $amipassword'."))\n";
	$method_rec['CODE'] .= "  {\n";
	$method_rec['CODE'] .= "	DebMes (\" Asterisk : Can't connect to AMI $amihost\");\n";
	$method_rec['CODE'] .= "        exit;\n";
	$method_rec['CODE'] .= "    };\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= '$response = $com_man->$command($option);'."\n";
	$method_rec['CODE'] .= '$com_man->disconnect();'."\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= 'if ($response['."'Response'] != 'Success')\n";
	$method_rec['CODE'] .= "  {\n";
	$method_rec['CODE'] .= "	DebMes (\" Asterisk : Can't process command => \".".'$response'."['Message']);\n";
	$method_rec['CODE'] .= "  };\n";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= "return ".'$response'.";";
	$method_rec['CODE'] .= "\n";
	$method_rec['CODE'] .= "// For debug\n";
	$method_rec['CODE'] .= "// DebMes (\" Asterisk : Process command ".'$command'."\");\n";
	$method_rec['CODE'] .= "// DebMes (".'$response'.");\n";
        $method_rec['ID'] = SQLInsert('methods', $method_rec);
    }
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
  SQLExec("delete from pvalues where property_id in (select id FROM properties where object_id in (select id from objects where class_id in (select id from classes where title = 'AsteriskAMI')))");
  SQLExec("delete from properties where object_id in (select id from objects where class_id = (select id from classes where title = 'AsteriskAMI'))");
  SQLExec("delete from methods where class_id = (select id from classes where title = 'AsteriskAMI')");
  SQLExec("delete from methods where object_id in (select id from objects where class_id = (select id from classes where title = 'AsteriskAMI'))");
  SQLExec("delete from objects where class_id = (select id from classes where title = 'AsteriskAMI')");
  SQLExec("delete from classes where title = 'AsteriskAMI'");
  parent::uninstall();
 }

/**
* dbInstall
*
* Database installation routine
*
* @access private
*/
 function dbInstall()
    {
    }

// --------------------------------------------------------------------
}
