<?php
// $Id: catego.php 618 2011-03-04 05:41:51Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'works-category.tpl';
$xoopsOption['module_subpage'] = 'category';
include 'header.php';

Works_Functions::makeHeader();

$mc =& $xoopsModuleConfig;

if ($id==''){
	header( 'location: '.PW_URL);
	die();
}

//Verificamos si la categoría existe
$cat = new Works_Category($id);
if ($cat->isNew())
    RMUris::redirect_with_message(
        __( 'Specified category does not exists!','works'), PW_URL, RMMSG_WARN
    );

RMEvents::get()->run_event( 'works.starting.categories', $cat);

// Category
$tpl->assign( 'category', array( 'id'=>$cat->id(),'name'=>$cat->name,'nameid'=>$cat->nameid,'description'=>$cat->desc ) );

//Barra de Navegación
$sql = "SELECT COUNT(*) FROM ".$db->prefix( 'mod_works_works')." as w, ".$db->prefix( 'mod_works_categories_rel')." as r
        WHERE r.category = ". $cat->id() . " AND w.id_work = r.work AND w.status = 'public'";

list($num)=$db->fetchRow($db->query($sql));

$limit = $mc['num_recent'];
$limit = $limit<=0 ? 10 : $limit;
if (!isset($page)) $page = RMHttpRequest::get( 'page', 'integer', 1 );

$tpages = ceil($num/$limit);
$page = $page > $tpages ? $tpages : $page; 
$start = $num<=0 ? 0 : ($page - 1) * $limit;
$start = $start<0 ? 0 : $start;

$nav = new RMPageNav($num, $limit, $page, 5);
$url = $cat->permalink().($mc['permalinks'] ? 'page/{PAGE_NUM}' : '&page={PAGE_NUM}');
$nav->target_url($url);
$tpl->assign( 'navpage', $nav->render(false));

$sql = str_replace( "COUNT(*)", 'w.*', $sql);
$sql.= " ORDER BY w.created DESC LIMIT $start,$limit";
$result = $db->query($sql);

// Numero de resultados en esta página
$t = $db->getRowsNum($result);
$tpl->assign( 'page_total', $t);
$tpl->assign( 'per_col', ceil($t/2));

while ($row = $db->fetchArray($result)){
	$work = new Works_Work();
	$work->assignVars($row);

	$tpl->append( 'works', Works_Functions::render_data( $work, $mc['desclen'] ) );

}
$tpl->assign( 'lang_works',sprintf( __( 'Works in "%s"','works' ),$cat->name ) );
$tpl->assign( 'xoops_pagetitle', sprintf( __( 'Works in "%s"','works' ),$cat->name )." &raquo; ".$mc['title'] );
$tpl->assign( 'lang_catego',__( 'Category:','works' ) );
$tpl->assign( 'lang_date',__( 'Date:','works' ) );
$tpl->assign( 'lang_client',__( 'Customer:','works' ) );
$tpl->assign( 'lang_rating',__( 'Our rate:','works' ) );
$tpl->assign( 'lang_featured', __( 'Featured','works' ) );

RMBreadCrumb::get()->add_crumb( __( 'Portfolio','works'), PW_URL );
RMBreadCrumb::get()->add_crumb( $cat->getVar( 'name'), PW_URL );

include 'footer.php';
