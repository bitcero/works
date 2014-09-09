<?php
// $Id: pw_works.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

function works_block_items_show($options){
	global $xoopsModule, $xoopsModuleConfig;

	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwcategory.class.php';

	$db = XoopsDatabaseFactory::getDatabaseConnection();
	
	if (isset($xoopsModule) && ($xoopsModule->dirname()=='works')){
		$mc =& $xoopsModuleConfig;
	}else{
		$mc =& RMUtilities::module_config('works');
	}

	$sql = "SELECT * FROM ".$db->prefix('mod_works_works').' WHERE public=1';
	$sql1 = $options[1] ? " AND catego='".$options[1]."'" : '';
	$sql2 = $options[2] ? " AND  client='".$options[2]."'" : ''; 
	$sql3 = '';
	switch($options[0]){
		case 0:
			$sql3 .= " ORDER BY RAND()";
			break;
		case 1:
			$sql3 .= ($sql1 || $sql2 ? " AND " : " WHERE ")." mark=1 ORDER BY RAND()";
			break;
		case 2: 
			$sql3 .= " ORDER BY created DESC ";
			break;
	}
	
	$sql3 .= " LIMIT 0,".$options[3];

	$result = $db->query($sql.$sql1.$sql2.$sql3);
	$clients = array();
	$categos = array();
	while($row = $db->fetchArray($result)){

		$work = new Works_Work();
		$work->assignVars($row);

		if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client(), 1);
		$client =& $clients[$work->client()];

		if (!isset($categos[$work->category()])) $categos[$work->category()] = new Works_Category($work->category(), 1);
		$cat =& $categos[$work->category()];
		
		$rtn = array();
		$rtn['title'] = $work->title();
		if ($options[6]) $rtn['desc'] = substr($work->descShort(),0,50);
		$rtn['link'] = $work->link();
		$rtn['created'] = formatTimestamp($work->created(), 's');
		if ($options[5]) $rtn['image'] = XOOPS_UPLOAD_URL.'/works/ths/'.$work->image();
		$linkcat = XOOPS_URL.'/modules/works/'.($mc['urlmode'] ? 'cat/'.$cat->nameId() : 'catego.php?id='.$cat->nameId());
		$rtn['cat'] = sprintf(__('Category: %s','works'),'<a href="'.$cat->link().'">'.$cat->name().'</a>');
		$rtn['client'] = sprintf(__('Customer: %s','works'),$client->businessName());
		$block['works'][] = $rtn;

	}
	
	$block['cols'] = $options[4];
	$block['showdesc'] = $options[6];
	$block['showimg'] = $options[5];
	return $block;
}


function works_block_items_edit($options){
	global $db;

	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwcategory.class.php';
	//Tipo de Trabajo
	$form = new RMForm(__('Block Options','works'), 'form_options', '');
	$ele = new RMFormSelect(__('Works type','works'),'options[0]');
	$ele->addOption(0,__('Reandom works','works'),$options[0]==0 ? 1 : 0);
	$ele->addOption(1,__('Featured works','works'),$options[0]==1 ? 1 : 0);
	$ele->addOption(2,__('Recent works','works'),$options[0]==2 ? 1 : 0);

	$form->addElement($ele);

	//Obtenemos las categorías
	$ele = new RMFormSelect(__('Category','works'),'options[1]');
	$ele->addOption(0,__('All categories','works'));
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	$result = $db->query("SELECT * FROM ".$db->prefix('mod_works_categories')." WHERE active=1");
	while ($row = $db->fetchArray($result)){
		$cat = new Works_Category();
		$cat->assignVars($row);			

		$ele->addOption($cat->id(),$cat->name(),$options[1]==$cat->id() ? 1 : 0);
	}
	$form->addElement($ele,true);
	
	//Obtenemos los clientes
	$ele = new RMFormSelect(__('Customer','works'),'options[2]');
	$ele->addOption(0,__('All customers','works'));
	$result = $db->query("SELECT * FROM ".$db->prefix('mod_works_clients'));
	while ($row = $db->fetchArray($result)){
		$client = new PWClient();
		$client->assignVars($row);			
		$ele->addOption($client->id(),$client->name(),isset($ptions[2]) ? ($options[2]==$client->id() ? 1 : 0) : 0);
	}

	$form->addElement($ele,true);
	
	//Número de trabajos
	$form->addElement(new RMFormText(__('Works number','works'),'options[3]',5,5,isset($options[3]) ? $options[3] : ''),true);
	$form->addElement(new RMFormText(__('Columns','works'),'options[4]',5,5,isset($options[4]) ? $options[4] : ''),true);
	$form->addElement(new RMFormYesno(__('Show work image','works'),'options[5]',isset($options[5]) ? ($options[5] ? 1 : 0) : 0), true);
	$form->addElement(new RMFormYesno(__('Show description','works'),'options[6]',isset($options[6]) ? ($options[6] ? 1 : 0) : 0), true);

	return $form->render(false);

}
