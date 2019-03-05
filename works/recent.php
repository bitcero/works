<?php
// $Id: recent.php 560 2010-11-18 05:12:40Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$GLOBALS['xoopsOption']['template_main'] = 'works-recent.tpl';
$xoopsOption['module_subpage']           = 'recent';
require __DIR__ . '/header.php';

Works_Functions::makeHeader();

//Barra de Navegación
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_works') . " WHERE status='public'";

list($num) = $db->fetchRow($db->query($sql));

$limit = $mc['num_recent'];
$limit = $limit <= 0 ? 10 : $limit;
if (!isset($page)) {
    $page = RMHttpRequest::get('page', 'integer', 1);
}

$tpages = ceil($num / $limit);
$page   = $page > $tpages ? $tpages : $page;
$start  = $num <= 0 ? 0 : ($page - 1) * $limit;
$start  = $start < 0 ? 0 : $start;

$nav = new RMPageNav($num, $limit, $page, 5);
$url = $xoopsModuleConfig['permalinks'] ? XOOPS_URL . rtrim($xoopsModuleConfig['htbase'], '/') . '/recent/page/{PAGE_NUM}/' : XOOPS_URL . '/modules/works/recent.php?page={PAGE_NUM}';
$nav->target_url($url);
$tpl->assign('navpage', $nav->render(false));

$sql    = 'SELECT * FROM ' . $db->prefix('mod_works_works') . " WHERE status='public' ORDER BY created DESC";
$sql    .= " LIMIT $start, $limit";
$result = $db->query($sql);

while (false !== ($row = $db->fetchArray($result))) {
    $work = new Works_Work();
    $work->assignVars($row);

    $tpl->append('works', Works_Functions::render_data($work, $mc['desclen']));
}
$tpl->assign('lang_recents', __('Recent Works', 'works'));
$tpl->assign('xoops_pagetitle', __('Recent Works', 'works') . ' &raquo; ' . $mc['title']);
$tpl->assign('lang_date', __('Date:', 'works'));
$tpl->assign('lang_catego', __('Category:', 'works'));
$tpl->assign('lang_client', __('Customer:', 'works'));
$tpl->assign('lang_rating', __('Our Rate:', 'works'));
$thSize = $mc['image_ths'];
$tpl->assign('lang_featured', __('Featured', 'works'));
$tpl->assign('lang_featured', __('Featured', 'works'));

require __DIR__ . '/footer.php';
