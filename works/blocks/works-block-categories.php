<?php
// $Id: pw_cats.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

function works_block_categories_show($options){
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
	
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	$result = $db->query("SELECT * FROM ".$db->prefix("mod_works_categories")." ORDER BY name");
	
	$block = array();
	
	while($row = $db->fetchArray($result)){
        $cat = new Works_Category();
        $cat->assignVars($row);
		$ret = array();
		$ret['name'] = $row['name'];
		$ret['link'] = $cat->link();
		$block['categos'][] = $ret;
	}
	
	return $block;
}