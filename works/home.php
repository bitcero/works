<?php
// $Id: home.php 560 2010-11-18 05:12:40Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('PW_LOCATION', 'index');

$GLOBALS['xoopsOption']['template_main'] = 'works-index.tpl';
$xoopsOption['module_subpage'] = 'index';
require __DIR__ . '/header.php';

Works_Functions::makeHeader();

$tpl->assign('works_subpage', 'index');

//Barra de Navegación
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_works') . " WHERE status='public'";

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
$url = $xoopsModuleConfig['permalinks'] ? XOOPS_URL . rtrim($xoopsModuleConfig['htbase'], '/') . '/page/{PAGE_NUM}/' : XOOPS_URL . '/modules/works/?page={PAGE_NUM}';
$nav->target_url($url);
$tpl->assign('navpage', $nav->render(false));

//Fin de barra de navegación

//Obtenemos los trabajos recientes
$sql = 'SELECT * FROM ' . $db->prefix('mod_works_works') . " WHERE status='public' ORDER BY created DESC LIMIT $start,$limit";
$result = $db->query($sql);

// Numero de resultados en esta página
$t = $db->getRowsNum($result);
$tpl->assign('page_total', $t);
$tpl->assign('per_col', ceil($t / 2));

$categos = [];
$clients = [];

while (false !== ($row = $db->fetchArray($result))) {
    $work = new Works_Work();
    $work->assignVars($row);

    $tpl->append('works', Works_Functions::render_data($work, $mc['desclen']));
}

$tpl->assign('lang_works', __('Our Work', 'works'));
$tpl->assign('lang_catego', __('Cetegory:', 'works'));
$tpl->assign('lang_date', __('Date:', 'works'));
$tpl->assign('lang_client', __('Customer:', 'works'));
$tpl->assign('lang_allsrecent', __('View all recent works', 'works'));
$tpl->assign('link_recent', PW_URL . ($mc['permalinks'] ? '/recent/' : '/recent.php'));
$tpl->assign('link_featured', PW_URL . ($mc['permalinks'] ? '/featured/' : '/featured.php'));
$tpl->assign('lang_featured', __('Featured', 'works'));

require __DIR__ . '/footer.php';
