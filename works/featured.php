<?php
// $Id: featured.php 560 2010-11-18 05:12:40Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'pw_featured.html';
$xoopsOption['module_subpage'] = 'featured';
include 'header.php';

Works_Functions::makeHeader();

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_works_works')." WHERE public=1 AND mark=1";
	
list($num)=$db->fetchRow($db->query($sql));
	
$limit = $mc['num_recent'];
$limit = $limit<=0 ? 10 : $limit;
if (!isset($page)) $page = rmc_server_var($_GET, 'page', 1);

$tpages = ceil($num/$limit);
$page = $page > $tpages ? $tpages : $page; 
$start = $num<=0 ? 0 : ($page - 1) * $limit;
$start = $start<0 ? 0 : $start;

$nav = new RMPageNav($num, $limit, $page, 5);
$url = $xoopsModuleConfig['urlmode'] ? XOOPS_URL.rtrim($xoopsModuleConfig['htbase'],'/').'/featured/page/{PAGE_NUM}/' : XOOPS_URL.'/modules/works/featured.php?page={PAGE_NUM}';
$nav->target_url($url);
$tpl->assign('navpage', $nav->render(false));

$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE public=1 AND mark=1";
$sql.= " LIMIT $start,$limit";
$result = $db->query($sql);
$categos = array();
$clients = array();
while ($row = $db->fetchArray($result)){
	$feat = new Works_Work();
	$feat->assignVars($row);

	if (!isset($categos[$feat->category()])) $categos[$feat->category()] = new Works_Category($feat->category());

	if (!isset($clients[$feat->client()])) $clients[$feat->client()] = new PWClient($feat->client());

	$tpl->append('featureds',array(
        'id'=>$feat->id(),
        'title'=>$feat->title(),
        'desc'=>$feat->descShort(),
	    'catego'=>$categos[$feat->category()]->name(),
        'client'=>$clients[$feat->client()]->name(),
        'link'=>$feat->link(),
	    'created'=>formatTimeStamp($feat->created(),'s'),
        'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$feat->image(),
	    'rating'=>Works_Functions::rating($feat->rating()),
        'linkcat'=>$categos[$feat->category()]->link()
    ));

}
$tpl->assign('lang_feats',__('Featured Works','works'));
$tpl->assign('lang_date',__('Date:','works'));
$tpl->assign('lang_catego',__('Category:','works'));
$tpl->assign('lang_client',__('Customer:','works'));
$tpl->assign('lang_rating',__('Our rate:','works'));
$thSize = $mc['image_ths'];
$tpl->assign('width',$thSize[0]+20);
$tpl->assign('xoops_pagetitle', __('Featured Works','works')." &raquo; ".$mc['title']);

include 'footer.php';
