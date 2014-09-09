<?php
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

global $xoopsDB;

$id = RMHttpRequest::request( 'id', 'integer', 0 );
global $xoopsModuleConfig;
$mc = $xoopsModuleConfig;

$work = new Works_Work( $id );

$categories = array();
$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("mod_works_categories")." ORDER BY position,status");
while ($row = $xoopsDB->fetchArray($result)){
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

ob_start();
include RMTemplate::get()->get_template( 'widgets/works-widget-categories.php', 'module', 'works' );
$content = ob_get_clean();

$widget = array(

    'title'     => __('Categories', 'works'),
    'content'   => $content,
    'icon'      => 'fa fa-folder'

);

return $widget;