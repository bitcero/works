<?php
// $Id: types.php 618 2011-03-04 05:41:51Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','customertypes');
include 'header.php';


/**
* @desc Visualiza todos los tipos de cliente
**/
function showTypes(){
	global $db, $xoopsModule, $tpl, $xoopsSecurity;

    define('RMCSUBLOCATION','typeslist');
    
	$sql = "SELECT * FROM ".$db->prefix('pw_types');
	$result = $db->query($sql);
	while($row = $db->fetchArray($result)){
		$type = new PWType();
		$type->assignVars($row);

		$types[] = array('id'=>$type->id(),'type'=>$type->type());
	}

	Works_Functions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; ".__('Customer types','works'));
	RMTemplate::get()->assign('xoops_pagetitle',__('Customer types','works'));
	RMTemplate::get()->add_style('admin.css','works');
	RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
	RMTemplate::get()->add_script('../include/js/admin_works.js');
	RMTemplate::get()->add_head("<script type='text/javascript'>\nvar pw_message='".__('Do you really want to delete selected types?','works')."';\n
		var pw_select_message = '".__('You must select some type before to execute this action!','works')."';</script>");
	xoops_cp_header();
    include RMTemplate::get()->get_template("admin/pw_types.php",'module','works');
	xoops_cp_footer();

}

/**
* @desc Formulario de creación/edición de Tipos de cliente
**/
function formTypes($edit = 0){

	global $tpl, $xoopsModule;
    
    define('RMCSUBLOCATION','newtype');
    
	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;

	if ($edit){
		//Verificamos si nos proporcionaron al menos un tipo para editar
		if (!is_array($ids)){
			redirectMsg('./types.php',__('You must provide a type ID at least','works'),1);
			die();		
		}
	

		if (!is_array($ids)){
			$ids = array($ids);
		}
	}

	Works_Functions::toolbar();
	xoops_cp_location('<a href="./">'.$xoopsModule->name()."</a> &raquo; <a href='./types.php'>".__('Customer types','works')."</a> &raquo; ".($edit ? __('Edit type','works') : __('New type','works')));
	RMTemplate::get()->assign('xoops_pagetitle',__('Add Customers types','works'));
	xoops_cp_header();

	$form = new RMForm($edit ? __('Edit Type','works') : __('New Type','works'),'frmtype','types.php');
	
	$num = 10;	
	if ($edit){
		foreach ($ids as $k){
			//Verificamos si el tipo es válido
			if ($k<=0) continue;	

			//Verificamos si el tipo existe
			$type = new PWType($k);
			if ($type->isNew()) continue;

			$form->addElement(new RMFormText(__('Type name','works'),'type['.$type->id().']',50,100,$edit ? $type->type() : ''));


		}


	}else{
		for ($i=1; $i<=$num; $i++){
			$form->addElement(new RMFormText(__('Type name','works'),'type['.$i.']',50,100,$edit ? '' : ''));
		}
	}

	$form->addElement(new RMFormHidden('op',$edit ? 'saveedit' : 'save'));

	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', $edit ? __('Save Changes','works') : __('Save Customer Types','works'), 'submit');
	$ele->addButton('cancel', __('Cancel', 'works'), 'button', 'onclick="window.location=\'types.php\';"');
	$form->addElement($ele);


	$form->display();

	xoops_cp_footer();

}

/**
* @desc Almacena la información de los tipos en la base de datos
**/
function saveTypes($edit = 0){

	global $xoopsSecurity, $db;


	foreach ($_POST as $k => $v){
		$$k = $v;
	}

	
	if (!$xoopsSecurity->check()){
		redirectMsg('./types.php'.($edit ? '?op=edit&ids='.$ids : ''), __('Session token expired!','works'), 1);
		die();
	}


	$errors = '';
	foreach ($type as $k => $v){
			
		if ($v=='') continue;

		if ($edit){
			$tp = new PWType($k);
			
			//Verificamos si ya existe el nombre del tipo
			$tpe = new PWType($v);
			if (!$tpe->isNew() && $tp->id()!=$tpe->id()){
				$errors .= sprintf(__('Another type with same name already exists!','works'),$v);
				continue;
			}

		}else{
			//Verificamos si ya existe el nombre del tipo
			$tp = new PWType($v);
			if (!$tp->isNew()){
				$errors .= sprintf(__('Another type with same name already exists!','works'),$v);
				continue;
			}

		}
		
		$tp->setType($v);
		$tp->isNew() ? $tp->setCreated(time()) : '';
		if (!$tp->save()){
			$errors .= sprintf(__('Type "%s" could not be saved!','works'), $v);
		}
	}

	if ($errors!=''){
		redirectMsg('./types.php',__('Errors ocurred while saving types','works').$errors,1);
		die();
	}else{
		redirectMsg($return!='' ? XOOPS_URL.'/modules/works/'.urldecode($return) : './types.php',__('Types added successfully!','works'),0);
		die();
	}
	
}

/**
* @desc Elimina los tipos de cliente porporcionados
**/
function deleteTypes(){
    global $xoopsSecurity;
    
	$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verificamos que nos hayan proporcionado un tipo para eliminar
	if (!is_array($ids)){
		redirectMsg('./types.php', __('You have not selected any customer type to delete!','works'),1);
		die();
	}

	if (!$xoopsSecurity->check()){
	    redirectMsg('./types.php',__('Session token expired!','works'), 1);
		die();
	}

	$errors = '';
	foreach ($ids as $k){
	    //Verificamos si el tipo sea válido
		if ($k<=0){
		    $errors.=sprintf(__('Customer type id "%u" is not valid!','works'), $k);
			continue;
		}

		//Verificamos siel tipo existe
		$type = new PWType($k);
		if ($type->isNew()){
		    $errors.=sprintf(__('Customer type with id "%u" does not exists!','works'), $k);
			continue;
		}
		
		if (!$type->delete()){
		    $errors.=sprintf(__('Type %s could not be deleted!','works'),$type->type());
		}
	}
	
	if ($errors!=''){
	    redirectMsg('./types.php',__('Errors ocurred while trying to delete selected types').'<br />'.$errors,1);
		die();
	}else{
	    redirectMsg('./types.php',__('Customer types deleted successfully!','works'),0);
		die();
	}

}


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch($op){
	case 'new':
		formTypes();
		break;
	case 'edit':
		formTypes(1);
		break;
	case 'save':
		saveTypes();
		break;
	case 'saveedit':
		saveTypes(1);
		break;
	case 'delete':
		deleteTypes();
		break;
	default:
		showTypes();

}
?>
