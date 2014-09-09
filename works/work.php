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

$xoopsOption['template_main'] = 'pw_work.html';
$xoopsOption['module_subpage'] = 'work';
include 'header.php';

$mc =& $xoopsModuleConfig;

if ($id==''){
	redirect_header(PW_URL.'/', 2, __('Work id not provided!','works'));
	die();
}

//Verificamos si el trabajo existe
$work = new Works_Work($id);
if($work->isNew()){
	redirect_header(PW_URL.'/', 2, __('Specified id does not exists!','works'));
	die();
}

if(!$work->isPublic() && !($xoopsUser && $xoopsUser->isAdmin())){
    redirect_header(PW_URL, 1, __('The requested content is not available!','works'));
    die();
}

if(!$work->isPublic()){
    $xoopsTpl->assign('lang_preview', __('You are in preview mode! This work is hidden for all other users.','works'));
}

$cat = new Works_Category($work->category());
$client = new PWClient($work->client());

$work_data = array(
	'id'=>$work->id(),
	'title'=>$work->title(),
	'desc'=>$work->desc(),
    'intro'=>$work->descShort(),
	'category'=>array(
        'name'=>$cat->name(),
        'description'=>$cat->desc(),
        'id'=>$cat->id(),
        'nameid'=>$cat->nameId(),
        'link' => $cat->link()
    ),
	'client'=>$client->businessName(),
	'site'=>$work->nameSite(),
	'url'=>formatURL($work->url()),
	'created'=>formatTimeStamp($work->created(),'s'),
	'start'=>formatTimeStamp($work->start(),'s'),
	'period'=>$work->period(),
	'cost'=>$mc['cost'] ? sprintf($mc['format_currency'],number_format($work->cost(),2)) : '',
	'mark'=>$work->mark(),
	'image'=>XOOPS_UPLOAD_URL.'/works/'.$work->image(),
    'thumb'=>XOOPS_UPLOAD_URL.'/works/ths/'.$work->image(),
	'comment'=>$work->comment(),
	'rating'=>Works_Functions::rating($work->rating()),
	'views'=>$work->views(),
    'metas'=>$work->get_metas(),
    'public'=>$work->isPublic(),
    'link' => $work->link()
);

$work_data = RMEvents::get()->run_event('works.work.data<{$work.l}',$work_data, $work);

$xoopsTpl->assign('work', $work_data);

$work->addView();

//Obtenemos todas las imágenes del trabajo
$sql = "SELECT * FROM ".$db->prefix('pw_images')." WHERE work=".$work->id();
$result = $db->query($sql);
while($row = $db->fetchArray($result)){
	$img = new PWImage();
	$img->assignVars($row);

	$tpl->append('images',array('id'=>$img->id(),'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$img->image(),
	'title'=>$img->title(),'desc'=>$img->desc(),'link_image'=>XOOPS_UPLOAD_URL.'/works/'.$img->image()));
}

RMEvents::get()->run_event('works.load.work.images', $work);

$tpl->assign('xoops_pagetitle', $work->title().' &raquo; '.$mc['title']);

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


$tpl->assign('lang_desc',__('Description','works'));
$tpl->assign('lang_catego',__('Category', 'works'));
$tpl->assign('lang_client',__('Customer','admin_works'));
$tpl->assign('lang_start',__('Begins','works'));
$tpl->assign('lang_period',__('Time length','works'));
$tpl->assign('lang_comment',__('Comment','works'));
$tpl->assign('lang_cost',__('Price','works'));
$tpl->assign('lang_others',__('Related Works','works'));
$tpl->assign('lang_date',__('Date','works'));
$tpl->assign('lang_images',__('Work Images','works'));
$tpl->assign('lang_site',__('Web site','works')); 
$tpl->assign('lang_mark',__('Featured','works'));
$tpl->assign('lang_rating',__('Our Rate','works'));
$tpl->assign('works_type', $mc['other_works']);
$tpl->assign('lang_views', __('Views','works'));

$imgSize = $mc['image_main'];
$thsSize = $mc['image_ths'];
$tpl->assign('widthimg',$thsSize[0]+10);
$tpl->assign('widthOther',$thsSize[0]+20);

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
