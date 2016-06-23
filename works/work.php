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

if( $work->status == 'draft' )
    $xoopsTpl->assign('lang_preview', __('You are in preview mode! This work is hidden for all other users.','works'));
elseif ( $work->status == 'scheduled' )
    $xoopsTpl->assign('lang_preview', __('You are in preview mode! This work is a scheduled work and is hidden for all other users.','works'));
elseif ( $work->status == 'private' )
    $xoopsTpl->assign('lang_preview', __('This is a private work. Only you and users that belong to authorized groups, can view this.','works'));

$image = new RMImage();

$work_data = array(
	'id'            => $work->id(),
	'title'         => $work->title,
	'description'   => $work->description,
	'customer'      => $work->customer,
	'web'           => $work->web,
	'url'           => $work->url,
	'created'       => $work->created,
	'featured'      => $work->featured,
	'image'         => $image->load_from_params( $work->image ),
    'thumb'         => $image->get_by_size( 300 ),
	'comment'       => $work->comment,
	'rating'        => $work->rating,
	'views'         => $work->views,
    'metas'         => $work->get_meta(),
    'link'          => $work->permalink(),
    'images'        => $work->images(),
    'categories'    => $work->categories( 'objects' ),
    'status'        => $work->status
);

$work_data = RMEvents::get()->run_event('works.render.data',$work_data, $work);

$xoopsTpl->assign('work', $work_data);

$work->addView();

$xoopsTpl->assign('xoops_pagetitle', $work->seo_title != '' ? $work->seo_title : $work->title );
//$tpl->assign('xoops_pagetitle', $work->title . ' &raquo; ' . $mc ['title']);

/**
* Otros trabajos
**/

if ( $mc['other_works'] > 0 ){
	if ($mc['other_works']==2){ //Trabajos destacados
		$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE status='public' AND featured = 1 AND id_work != '".$work->id()."' ORDER BY RAND() LIMIT 0,".$mc['num_otherworks'];
	}elseif($mc['other_works']==1){ //Misma categoría
        $cats = $work->categories( 'id' );
        $sql = "SELECT w.* FROM " . $db->prefix('mod_works_works') . " as w LEFT JOIN
                " . $db->prefix('mod_works_categories_rel') . " as c ON (c.work = w.id_work) WHERE c.category IN (".implode(",", $cats).") AND c.work != " . $work->id() .
                " AND w.status = 'public' ORDER BY RAND() LIMIT 0, $mc[num_otherworks]";
		//$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE status='public' AND catego=".$work->category()." AND id_work<>'".$work->id()."' ORDER BY RAND() LIMIT 0,".$mc['num_otherworks'];
	}
	$result = $db->query($sql);

	while ($row = $db->fetchArray($result)){
		$wk = new Works_Work();
		$wk->assignVars($row);
		$tpl->append('other_works',array(
            'id'            => $wk->id(),
            'title'         => $wk->title,
            'description'   => TextCleaner::getInstance()->truncate( $wk->description, 40, '...'),
            'customer'      => $wk->customer,
            'web'           => $wk->web,
            'url'           => $wk->url,
            'link'          => $wk->permalink(),
		    'created'       => formatTimeStamp($wk->created,'s'),
            'image'         => RMImage::get()->load_from_params( $wk->image ),
            'views'         => $wk->views,
            'metas'         => $wk->get_meta()
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
$tpl->assign('lang_comments',__('Comments by Users','works'));
$tpl->assign('lang_others',__('Related Works','works'));
$tpl->assign('lang_date',__('Date','works'));
$tpl->assign('lang_images',__('Work Images','works'));
$tpl->assign('lang_rating',__('Our Rate','works'));
$tpl->assign('works_location', 'work-details');


Works_Functions::makeHeader();

$common->comments()->load([
    'url' => XOOPS_ROOT_PATH . '/modules/works/work.php',
    'identifier' => 'work=' . $work->id(),
    'object' => 'works',
    'type' => 'module',
    'assign' => true
]);

// Comments form
$xoopsTpl->assign('comments_form', $common->comments()->form([
    'url' => XOOPS_ROOT_PATH . '/modules/works/work.php',
    'object' => 'works',
    'type' => 'module',
    'identifier' => 'work=' . $work->id(),
    'file' => MW_PATH . '/class/workscontroller.php'
]));


// Basic SEO
$rmf = RMFunctions::get();
$description = $work->getVar('seo_description','e');
$keywords = $work->getVar('seo_keywords', 'e');
$rmf->add_keywords_description($description!='' ? $description : '', $keywords!='' ? $keywords : '');

// Professional Works uses LightBox plugin to show
// work images.
if (RMFunctions::plugin_installed('lightbox')){
	RMLightbox::get()->add_element('.work-image-item');
    RMLightbox::get()->add_option( 'rel', 'work-image-item' );
	RMLightbox::get()->render();
}

include 'footer.php';
