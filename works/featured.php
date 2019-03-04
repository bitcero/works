<?php
// $Id: featured.php 560 2010-11-18 05:12:40Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$GLOBALS['xoopsOption']['template_main'] = 'works-featured.tpl';
$xoopsOption['module_subpage'] = 'featured';
require __DIR__ . '/header.php';

Works_Functions::makeHeader();

//Barra de Navegación
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_works') . " WHERE status='public' AND featured=1";

list($num) = $db->fetchRow($db->query($sql));

$limit = $mc['num_recent'];
$limit = $limit <= 0 ? 10 : $limit;
if (!isset($page)) {
    $page = rmc_server_var($_GET, 'page', 1);
}

$tpages = ceil($num / $limit);
$page = $page > $tpages ? $tpages : $page;
$start = $num <= 0 ? 0 : ($page - 1) * $limit;
$start = $start < 0 ? 0 : $start;

$nav = new RMPageNav($num, $limit, $page, 5);
$url = $xoopsModuleConfig['permalinks'] ? XOOPS_URL . rtrim($xoopsModuleConfig['htbase'], '/') . '/featured/page/{PAGE_NUM}/' : XOOPS_URL . '/modules/works/featured.php?page={PAGE_NUM}';
$nav->target_url($url);
$tpl->assign('navpage', $nav->render(false));

$sql = 'SELECT * FROM ' . $db->prefix('mod_works_works') . " WHERE status='public' AND featured=1";
$sql .= " LIMIT $start,$limit";
$result = $db->query($sql);
$categos = [];
$clients = [];
while (false !== ($row = $db->fetchArray($result))) {
    $work = new Works_Work();
    $work->assignVars($row);

    $tpl->append('works', Works_Functions::render_data($work, $mc['desclen']));
}
$tpl->assign('lang_feats', __('Featured Works', 'works'));
$tpl->assign('lang_date', __('Date:', 'works'));
$tpl->assign('lang_catego', __('Category:', 'works'));
$tpl->assign('lang_client', __('Customer:', 'works'));
$tpl->assign('lang_rating', __('Our rate:', 'works'));
$thSize = $mc['image_ths'];
$tpl->assign('width', $thSize[0] + 20);
$tpl->assign('xoops_pagetitle', __('Featured Works', 'works') . ' &raquo; ' . $mc['title']);
$tpl->assign('lang_featured', __('Featured', 'works'));

require __DIR__ . '/footer.php';
