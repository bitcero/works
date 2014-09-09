<?php
// $Id: categos.php 618 2011-03-04 05:41:51Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','categories');
include 'header.php';

function showCategories(){
	global $xoopsModule, $mc, $tpl, $db, $xoopsSecurity;
    
    define('RMCSUBLOCATION','catlist');
    
	$categories = array();
    $result = $db->query("SELECT * FROM ".$db->prefix("mod_works_categories")." ORDER BY position,status");
    while ($row = $db->fetchArray($result)){
        $cat = new Works_Category();
        $cat->assignVars($row);
        $link = PW_URL.'/'.($mc['permalinks'] ? 'category/'.$cat->nameid.'/' : 'category.php?id='.$cat->id());
        $categories[] = array(
            'id'        	=> $cat->id(),
            'link'      	=> $link,
            'name'      	=> $cat->name,
            'active'    	=> $cat->status == 'active' ? 1 : 0,
            'position'     	=> $cat->position,
            'works'     	=> $cat->works(),
            'nameid'    	=> $cat->nameid,
            'description'	=> $cat->description
        );
    }
    
    // Event
    $categories = RMEvents::get()->run_event('works.list.categories', $categories);

    $bc = RMBreadCrumb::get();
    $bc->add_crumb( __('Categories management', 'works' ) );

	RMTemplate::get()->assign('xoops_pagetitle', __('Works Categories','works'));
	RMTemplate::get()->add_style('admin.css', 'works');
    RMTemplate::get()->add_script( 'jquery.checkboxes.js', 'rmcommon', array( 'directory' => 'include' ) );
    RMTemplate::get()->add_script( 'admin_works.js', 'works' );
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar pw_message='".__('Do you really want to delete selected categories?','works')."';\n
        var pw_select_message = '".__('You must select some category before to execute this action!','works')."';</script>");
    xoops_cp_header();

    $works_extra_options = '';
    $works_extra_options = RMEvents::get()->run_event('works.more.options', $works_extra_options);
	
	include RMTemplate::get()->get_template("admin/works-categories.php", 'module', 'works');
	xoops_cp_footer();
}

function formCategory($edit = 0){
	global $mc, $xoopsModule, $db;
	
    define('RMCSUBLOCATION','addcat');

	RMTemplate::get()->assign('xoops_pagetitle',$edit?__('Edit Category','works'):__('Add Category','works'));

    $bc = RMBreadCrumb::get();
    $bc->add_crumb( __('Categories management', 'works' ), 'categories.php' );
    $bc->add_crumb( __('Add category', 'works' ) );

	RMTemplate::get()->header();

	$id = RMHttpRequest::get( 'id', 'integer', 0 );

	if ($edit){

		//Verificamos si la categoría es válida
		if ($id<=0)
            RMUris::redirect_with_message( __('You must provide an ID!', 'works'), 'categories.php', RMMSG_WARN );

		//Verificamos si la categoría existe
		$cat = new Works_Category($id);
		if ($cat->isNew())
            RMUris::redirect_with_message( __('Specified category was not found!','works'), 'categories.php', RMMSG_ERROR );

	}
	
	$form = new RMForm($edit ? __('Edit Category','works') : __('Add Category','works'),'frmNew','categories.php');

	$form->addElement(new RMFormText(__('Name','works'), 'name', 50, 150, $edit ? $cat->name : ''), true);

	if ($edit) $form->addElement(new RMFormText(__('Short name','works'), 'nameid', 50, 150, $cat->nameid), true);
	$form->addElement(new RMFormEditor(__('Description','works'), 'description', '100%','250px', $edit ? $cat->getVar('description', 'e') : ''));
	$form->addElement(new RMFormYesNo(__('Enable category','works'), 'status', $edit ? ($cat->status == 'active' ? 1 : 0 ) : 1));
	$form->addElement(new RMFormText(__('Display order','works'), 'position', 8, 3, $edit ? $cat->position : 0), true, 'num');

	
	$form->addElement(new RMFormHidden('action', $edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMFormHidden('id', $cat->id()));
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', $edit ? __('Save Changes!','works') : __('Add Now!','works'), 'submit');
	$ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'categos.php\';"');
	$form->addElement($ele);
    
    $form = RMEvents::get()->run_event('works.form.categories', $form);
    
	$form->display();
	
	xoops_cp_footer();
	
}

function saveCategory($edit = 0){
	global $db, $mc, $xoopsSecurity;

    $id = RMHttpRequest::post( 'id', 'integer', 0 );
    $position = RMHttpRequest::post( 'position', 'integer', 0 );
    $name = RMHttpRequest::post( 'name', 'string', '' );
    $nameid = RMHttpRequest::post( 'nameid', 'string', '' );
    $description = RMHttpRequest::post( 'description', 'string', '' );
    $return = RMHttpRequest::post( 'return', 'string', '' );
    $status = RMHttpRequest::post( 'status', 'integer', 1 );
    $status = $status == 1 ? 'active' : 'inactive';

	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$xoopsSecurity->check())
        RMUris::redirect_with_message( __('Session token expired!', 'works' ), 'categories.php?action='.($edit ? 'edit&id='.$id : 'new'), RMMSG_ERROR );

	if ($edit){

		//Verificamos si la categoría es válida
		if ($id<=0)
            RMUris::redirect_with_message( __('The category ID has not been specified!', 'works' ), 'categories.php', RMMSG_ERROR );

		//Verificamos si la categoría existe
		$cat = new Works_Category($id);
		if ($cat->isNew())
            RMUris::redirect_with_message( __('Specified category does not exists!','works'), 'categories.php', RMMSG_WARN );

		//Verificamos el nombre de la categoría
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_works_categories')." WHERE name='$name' AND id_cat<>'$id'";
		list($num) = $db->fetchRow($db->query($sql));
		if ($num>0)
            RMUris::redirect_with_message( __('Another category with same name already exists!','works'), 'categos.php?op=edit&id='.$id, RMMSG_WARN );

		if ($nameid){

			$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_works_categories')." WHERE nameid='$nameid' AND id_cat != ".$id;
			list( $num )=$db->fetchRow($db->queryF($sql));
			if ($num>0)
                RMUris::redirect_with_message( __('There are another category with same name id!','works'), 'categories.php?op=edit&id='.$id, RMMSG_WARN );

		}

	}else{

		$cat = new Works_Category();

	}

	//Genera el nombre identificador
	$found=false; 
	$i = 0;
	if ($name != $cat->name || empty( $nameid ) ){
		do{
			$nameid = TextCleaner::sweetstring($name) . ($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_:works_categories'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
		}while ($found==true);
	}


	$cat->setVar( 'name', $name );
	$cat->setVar( 'description', $description );
	$cat->setVar( 'position', $position );
	$cat->setVar( 'status', $status );
	$cat->setVar( 'nameid', $nameid );
	$cat->isNew() ? $cat->setVar( 'created', time() ) : '';
    
    $cat = RMEvents::get()->run_event('works.save.category', $cat);
    
	if (!$cat->save())
        RMUris::redirect_with_message( __('Errors occurs while trying to update database!','works').'<br />'.$cat->errors(), 'categories.php' . ($edit ? '?action=edit&id=' . $cat->id() : '' ), RMMSG_ERROR );
    else
        RMUris::redirect_with_message( __('Database updated successfully!','works'), $return!='' ? XOOPS_URL.'/modules/works/'.urldecode($return) : 'categories.php', RMMSG_SUCCESS );

}


/**
* @desc Elimina las categorías proporcionadas
**/
function deleteCategory(){

	global $xoopsModule, $db, $xoopsSecurity;

	$ids = RMHttpRequest::post( 'ids', 'array', array() );
	
	if ( !is_array( $ids ) || empty( $ids ) )
        RMUris::redirect_with_message(
            __('You must specify a ID from at least one category', 'works'),
            'categories.php',
            RMMSG_WARN
        );


    if (!$xoopsSecurity->check())
        RMUris::redirect_with_message(
            __('Session token expired', 'works'),
            'categories.php',
            RMMSG_ERROR
        );

	$errors = '';
	foreach ($ids as $k){
		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(__('Category ID "%s" is not valid!','works'), $k) .'<br>';
			continue;
		}

		//Verificamos si la categoría existe
		$cat = new Works_Category($k);
		if ($cat->isNew()){
			$errors.=sprintf( __('Category with ID "%s" does not exists!','works'), $k) . '<br>';
			continue;
		}
		    
        RMEvents::get()->run_event('works.delete.category', $cat);
            
		if (!$cat->delete()){
			$errors.=sprintf(__('Category "%s" could not be deleted!','works'), $cat->name );
		}

	}
	
	if ($errors!='')
        RMUris::redirect_with_message(
            __('Errors occurs while trying to delete categories').'<br />'.$errors,
            'categories.php',
            RMMSG_ERROR
        );

	else
        RMUris::redirect_with_message(
            __('Database updated successfully!','works'),
            'categories.php',
            RMMSG_SUCCESS
        );

}

/**
* @desc Actualiza el orden de las categorías
**/
function updateCategory(){
	global $xoopsSecurity;

	$orders = RMHttpRequest::post( 'order', 'array', array() );

    if ( !is_array( $orders ) || empty( $orders ) )
        RMUris::redirect_with_message(
            __('You must select at least one category to update.', 'works'),
            'categories.php',
            RMMSG_WARN
        );

	if (!$xoopsSecurity->check())
        RMUris::redirect_with_message(
            __('Session token expired!', 'works' ),
            'categories.php',
            RMMSG_WARN
        );

	$errors = '';
	foreach ($orders as $k => $v){

		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(__('Category ID "%s" is not valid!','works'), $k).'<br>';
			continue;
		}

		//Verificamos si la categoría existe
		$cat = new Works_Category($k);
		if ($cat->isNew()){
			$errors.=sprintf(__('Specified category with ID "%s" does not exists!','works'), $k).'<br>';
			continue;
		}

        // Si la posición proporcionada es igual a la existente omitimos
		if ($cat->position == $v ) continue;

		$cat->position = $v;
        
        RMEvents::get()->run_event('works.update.category', $cat);
        
		if ( !$cat->save() )
			$errors.=sprintf(__('Changes for category "%s" could not be saved!','works'), $cat->name ).'<br>';

	}

	if ($errors!='')
        RMUris::redirect_with_message(
            __('Errors occurs while trying to save changes','works').'<br>'.$errors,
            'categories.php',
            RMMSG_ERROR
        );
	else
        RMUris::redirect_with_message(
            __('Database updated successfully!','works'),
            'categories.php',
            RMMSG_SUCCESS
        );

}

/**
* @desc Activa/desactiva las categorías especificadas
**/
function activeCategory($act = 'active'){

	global $xoopsSecurity;

	$ids = RMHttpRequest::post( 'ids', 'array', array() );

    if (!$xoopsSecurity->check())
        RMUris::redirect_with_message(
            __('Session token expired!', 'works' ),
            'categories.php',
            RMMSG_WARN
        );

    if ( !is_array( $ids ) || empty( $ids ) )
        RMUris::redirect_with_message(
            __('You must select at least one category to update.', 'works'),
            'categories.php',
            RMMSG_WARN
        );

	$errors = '';
	foreach ($ids as $k){
		
		//Verificamos si la categoría es válida
		if ($k<=0){
			$errors.=sprintf(__('Category ID "%s" is not valid!','works'), $k) . '<br>';
			continue;
		}

		//Verificamos si la categoría existe
		$cat = new Works_Category($k);
		if ($cat->isNew()){
			$errors.=sprintf(__('Specified category with ID "%s" does not exists!','works'), $k) . '<br>';
			continue;
		}
		

		$cat->status = $act;
        
        RMEvents::get()->run_event('works.activate.category', $cat);
        
		if (!$cat->save())
			$errors.=sprintf(__('Category "%s" could not be saved!','works'), $cat->name ) . '<br>';

	}

	if ($errors!='')
        RMUris::redirect_with_message(
            __('Errors occurs while trying to save changes','works').'<br>'.$errors,
            'categories.php',
            RMMSG_ERROR
        );
	else
        RMUris::redirect_with_message(
            __('Database updated successfully!','works'),
            'categories.php',
            RMMSG_SUCCESS
        );
	

}


$action = RMHttpRequest::request( 'action', 'string', '' );

switch($action){
	case 'new':
		formCategory();
		break;
	case 'edit':
		formCategory(1);
		break;
	case 'save':
		saveCategory();
		break;
	case 'saveedit':
		saveCategory(1);
		break;
	case 'delete':
		deleteCategory();
		break;
	case 'update':
		updateCategory();
		break;
	case 'active':
		activeCategory( 'active' );
		break;
	case 'desactive':
		activeCategory( 'inactive' );
		break;
	default:
		showCategories();
		break;
}
