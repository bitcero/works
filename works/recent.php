<?php
// $Id: recent.php 560 2010-11-18 05:12:40Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'pw_recent.html';
$xoopsOption['module_subpage'] = 'recent';
include 'header.php';

Works_Functions::makeHeader();

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_works_works')." WHERE public=1";
	
list($num)=$db->fetchRow($db->query($sql));
	
$limit = $mc['num_recent'];
$limit = $limit<=0 ? 10 : $limit;
if (!isset($page)) $page = rmc_server_var($_GET, 'page', 1);

$tpages = ceil($num/$limit);
$page = $page > $tpages ? $tpages : $page; 
$start = $num<=0 ? 0 : ($page - 1) * $limit;
$start = $start<0 ? 0 : $start;

$nav = new RMPageNav($num, $limit, $page, 5);
$url = $xoopsModuleConfig['urlmode'] ? XOOPS_URL.rtrim($xoopsModuleConfig['htbase'],'/').'/recent/page/{PAGE_NUM}/' : XOOPS_URL.'/modules/works/recent.php?page={PAGE_NUM}';
$nav->target_url($url);
$tpl->assign('navpage', $nav->render(false));

$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE public=1 ORDER BY created DESC";
$sql.= " LIMIT $start,$limit";
$result = $db->query($sql);
$categos = array();
$clients = array();
while ($row = $db->fetchArray($result)){
	$recent = new Works_Work();
	$recent->assignVars($row);

	if (!isset($categos[$recent->category()])) $categos[$recent->category()] = new Works_Category($recent->category());

	if (!isset($clients[$recent->client()])) $clients[$recent->client()] = new PWClient($recent->client());

	$tpl->append('recents',array(
        'id'=>$recent->id(),'title'=>$recent->title(),
        'desc'=>$recent->descShort(),
	    'catego'=>$categos[$recent->category()]->name(),
        'client'=>$clients[$recent->client()]->name(),
        'link'=>$recent->link(),
	    'created'=>formatTimeStamp($recent->created(),'s'),
        'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$recent->image(),
	    'rating'=>Works_Functions::rating($recent->rating()),
        'featured'=>$recent->mark(),
        'linkcat'=>$categos[$recent->category()]->link()
    ));

}
$tpl->assign('lang_recents',__('Recent Works','works'));
$tpl->assign('xoops_pagetitle',__('Recent Works','works')." &raquo; ".$mc['title']);
$tpl->assign('lang_date',__('Date:','works'));
$tpl->assign('lang_catego',__('Category:','works'));
$tpl->assign('lang_client',__('Customer:','works'));
$tpl->assign('lang_rating',__('Our Rate:','works'));
$thSize = $mc['image_ths'];
$tpl->assign('width',$thSize[0]+20);
$tpl->assign('lang_featured', __('Featured','works'));

include 'footer.php';
