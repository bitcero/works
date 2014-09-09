<?php
// $Id: pw_comments.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

function works_block_testimonials_show($options){
	global $xoopsModule, $xoopsModuleConfig;

	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';

	$db = XoopsDatabaseFactory::getDatabaseConnection();
	if (isset($xoopsModule) && ($xoopsModule->dirname()=='works')){
		$mc =& $xoopsModuleConfig;
	}else{
		$mc =& RMUtilities::module_config('works');
	}


	$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE comment<>'' ORDER BY ".($options[1] ? " created DESC " : " RAND() ");
	$sql.= " LIMIT 0,".$options[0];
	$result = $db->query($sql);
	$clients = array();
	while ($row = $db->fetchArray($result)){
		$work = new Works_Work();
		$work->assignVars($row);
		
		if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client(), 1);
		$client =& $clients[$work->client()];
		
		$rtn = array();
		$rtn['client'] = $client->businessName();
		$rtn['link'] = $work->link();
		$rtn['comment'] = $work->comment();
	
		$block['works'][] = $rtn;

	}
	
	return $block;

}


function works_block_testimonials_edit($options, &$form){
	global $db;
	
	$form = new RMForm(__('Block Options','works'));
	$form->addElement(new RMFormText(__('Comments number','works'),'options[0]',5,5,$options[0] ? $options[0] : 3),true);
	$ele = new RMFormSelect(__('Works type','works'),'options[1]');
	$ele->addOption(0,__('Random','works'),$options[1]==0 ? 1 : 0);
	$ele->addOption(1,__('Recent works','works'), $options[1]==1 ? 1 :0);
	$form->addElement($ele);

	return $form->render(false);
}

