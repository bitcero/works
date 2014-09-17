<?php
// $Id: work.php 1072 2012-09-23 20:31:52Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!defined('XOOPS_ROOT_PATH'))
    require '../../mainfile.php';

$xoopsOption['template_main'] = 'works-item.tpl';
$xoopsOption['module_subpage'] = 'work';
include 'header.php';

$mc =& $xoopsModuleConfig;

if ($id=='')
    RMUris::redirect_with_message(
        __('No project has been specified', 'works'), PW_URL, RMMSG_WARN
    );

//Verificamos si el trabajo existe
$work = new Works_Work($id);
if($work->isNew()){
    Works_Functions::send_404_status();
    die();
}

/**
 * Check access to project
 */
if(!Works_Functions::is_allowed( $work )){
    redirect_header(PW_URL, 1, __('The requested content is not available!','works'));
    die();
}

if( $work->status == 'draft' ){
    $xoopsTpl->assign('lang_preview', __('You are in preview mode! This work is hidden for all other users.','works'));
}

$image = new RMImage();

$work_data = array(
	'id'            => $work->id(),
	'title'         => $work->title,
	'description'   => $work->description,
	'customer'      => $work->customer,
	'web'           => $work->web,
	'url'           => $work->url,
	'created'       => formatTimeStamp($work->created,'s'),
	'featured'      => $work->featured,
	'image'         => $image->load_from_params( $work->image ),
    'thumb'         => $image->get_by_size( 300 ),
	'comment'       => $work->comment,
	'rating'        => $work->rating,
	'views'         => $work->views,
    'metas'         => $work->get_meta(),
    'link'          => $work->permalink(),
    'images'        => $work->images(),
    'categories'    => $work->categories( 'objects' )
);

$work_data = RMEvents::get()->run_event('works.work.data',$work_data, $work);

$xoopsTpl->assign('work', $work_data);

$work->addView();

$tpl->assign('xoops_pagetitle', $work->title . ' &raquo; ' . $mc ['title']);

/**
* Otros trabajos
**/
if ($mc['other_works']>0){
	if ($mc['other_works']==2){ //Trabajos destacados
		$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE public=1 AND mark=1 AND id_work<>'".$work->id()."' ORDER BY RAND() LIMIT 0,".$mc['num_otherworks'];
	}elseif($mc['other_works']==1){ //Misma categoría
		$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE public=1 AND catego=".$work->category()." AND id_work<>'".$work->id()."' ORDER BY RAND() LIMIT 0,".$mc['num_otherworks'];
	}
	$result = $db->query($sql);
	$categos = array();
	$clients = array();
	while ($row = $db->fetchArray($result)){
		$wk = new Works_Work();
		$wk->assignVars($row);

		if (!isset($categos[$wk->category()])) $categos[$wk->category()] = new Works_Category($wk->category());

		if (!isset($clients[$wk->client()])) $clients[$wk->client()] = new PWClient($wk->client());
        echo "1 - ";
		$tpl->append('other_works',array(
            'id'=>$wk->id(),
            'title'=>$wk->title(),
            'desc'=>$wk->descShort(),
            'linkcat'=>$categos[$wk->category()]->link(),
		    'catego'=>$categos[$wk->category()]->name(),
            'client'=>$clients[$wk->client()]->name(),
            'link'=>$wk->link(),
		    'created'=>formatTimeStamp($wk->created(),'s'),
            'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$wk->image(),
            'views'=>$wk->views(),
            'metas'=>$wk->get_metas()
        ));
	}
	
	RMEvents::get()->run_event('works.load.other.works', $work);
	
}


$tpl->assign('lang_categories',__('Categories:', 'works'));
$tpl->assign('lang_customer',__('Customer:','admin_works'));
$tpl->assign('lang_site',__('Web site:','works'));
$tpl->assign('lang_featured',__('Featured','works'));
$tpl->assign('lang_views', __('Views:','works'));
$tpl->assign('lang_comment',__('Comment','works'));
$tpl->assign('lang_cost',__('Price','works'));
$tpl->assign('lang_others',__('Related Works','works'));
$tpl->assign('lang_date',__('Date','works'));
$tpl->assign('lang_images',__('Work Images','works'));
$tpl->assign('lang_rating',__('Our Rate','works'));
$tpl->assign('works_type', $mc['other_works']);


Works_Functions::makeHeader();

RMFunctions::get_comments('works','work='.$work->id());
// Comments form
RMFunctions::comments_form('works', 'work='.$work->id(), 'module', PW_ROOT.'/class/workscontroller.php');

// Professional Works uses LightBox plugin to show
// work images.
if (RMFunctions::plugin_installed('lightbox')){
	RMLightbox::get()->add_element('#pw-work-images a');
	RMLightbox::get()->render();
}

include 'footer.php';
