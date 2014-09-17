<?php
// $Id: header.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../../include/cp_header.php';

define('PW_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname());
define('PW_URL', XOOPS_URL . '/modules/works' );
define('PW_PUBLIC_URL', $xoopsModuleConfig['permalinks'] ? XOOPS_URL . '/' . trim( $xoopsModuleConfig['htbase'], '/') :  XOOPS_URL.'/modules/works');

# Definimos el motor de plantillas si no existe
$mc =& $xoopsModuleConfig;
$myts =& MyTextSanitizer::getInstance();

$tpl = RMTemplate::get();
$db = XoopsDatabaseFactory::getDatabaseConnection();

# Asignamos las variables básicas a SMARTY
$tpl->assign('pw_url',PW_URL);
$tpl->assign('pw_path',PW_PATH);

// Directorios
if (!file_exists(XOOPS_UPLOAD_PATH.'/works')) mkdir(XOOPS_UPLOAD_PATH.'/works');
if (!file_exists(XOOPS_UPLOAD_PATH.'/works/ths')) mkdir(XOOPS_UPLOAD_PATH.'/works/ths');

RMTemplate::get()->add_script(PW_URL.'/include/js/admin_works.js');
