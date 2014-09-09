<?php
// $Id: catego.php 618 2011-03-04 05:41:51Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'pw_catego.html';
$xoopsOption['module_subpage'] = 'category';
include 'header.php';

Works_Functions::makeHeader();

$mc =& $xoopsModuleConfig;

if ($id==''){
	header('location: '.PW_URL);
	die();
}

//Verificamos si la categoría existe
$cat = new Works_Category($id);
if ($cat->isNew()){
	redirect_header(PW_URL.'/', 2, __('Specified category does not exists!','works'));
	die();
}

RMEvents::get()->run_event('works.starting.categories', $cat);

// Category
$tpl->assign('category', array('id'=>$cat->id(),'title'=>$cat->name(),'name'=>$cat->nameId(),'desc'=>$cat->desc()));

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_works_works')." WHERE public=1 AND catego='".$cat->id()."'";

list($num)=$db->fetchRow($db->query($sql));

$limit = $mc['num_recent'];
$limit = $limit<=0 ? 10 : $limit;
if (!isset($page)) $page = rmc_server_var($_GET, 'page', 1);

$tpages = ceil($num/$limit);
$page = $page > $tpages ? $tpages : $page; 
$start = $num<=0 ? 0 : ($page - 1) * $limit;
$start = $start<0 ? 0 : $start;

$nav = new RMPageNav($num, $limit, $page, 5);
$url = $cat->link().($mc['urlmode'] ? 'page/{PAGE_NUM}' : '&page={PAGE_NUM}');
$nav->target_url($url);
$tpl->assign('navpage', $nav->render(false));


$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE public=1 AND catego='".$cat->id()."'";
$sql.= " ORDER BY created DESC LIMIT $start,$limit";
$result = $db->query($sql);

// Numero de resultados en esta página
$t = $db->getRowsNum($result);
$tpl->assign('page_total', $t);
$tpl->assign('per_col', ceil($t/2));

$categos = array();
$clients = array();
while ($row = $db->fetchArray($result)){
	$work = new Works_Work();
	$work->assignVars($row);

	if (!isset($categos[$work->category()])) $categos[$work->category()] = new Works_Category($work->category());

	if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client());

	$tpl->append('works',array(
        'id'=>$work->id(),'title'=>$work->title(),
        'desc'=>$work->descShort(),
	    'catego'=>$categos[$work->category()]->name(),
        'client'=>$clients[$work->client()]->name(),
        'link'=>$work->link(),
        'time'=>$work->created(),
        'modified_time'=>$work->getVar('modified'),
        'modified' => formatTimeStamp($work->getVar('modified'),'s'),
	    'created'=>formatTimeStamp($work->created(),'s'),
        'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$work->image(),
	    'rating'=>Works_Functions::rating($work->rating()),
        'featured'=>$work->mark(),
        'linkcat'=>$categos[$work->category()]->link(),
        'metas'=>$work->get_metas()
    ));
}
$tpl->assign('lang_works',sprintf(__('Works in "%s"','works'),$cat->name()));
$tpl->assign('xoops_pagetitle', sprintf(__('Works in "%s"','works'),$cat->name())." &raquo; ".$mc['title']);
$tpl->assign('lang_catego',__('Category:','works'));
$tpl->assign('lang_date',__('Date:','works'));
$tpl->assign('lang_client',__('Customer:','works'));
$tpl->assign('lang_rating',__('Our rate:','works'));
$thSize = $mc['image_ths'];
$tpl->assign('width',$thSize[0]+20);
$tpl->assign('lang_featured', __('Featured','works'));

RMBreadCrumb::get()->add_crumb(__('Portfolio','works'), PW_URL);
RMBreadCrumb::get()->add_crumb($cat->getVar('name'), PW_URL);

include 'footer.php';
