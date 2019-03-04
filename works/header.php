<?php
// $Id: header.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require  dirname(dirname(__DIR__)) . '/header.php';
$mc = &$xoopsModuleConfig;
$xmh = '';

define('PW_URL', XOOPS_URL . ($xoopsModuleConfig['permalinks'] ? rtrim($xoopsModuleConfig['htbase'], '/') : '/modules/works'));
define('PW_ROOT', XOOPS_ROOT_PATH . '/modules/works');

$tpl = $xoopsTpl;
$db = XoopsDatabaseFactory::getDatabaseConnection();

RMTemplate::get()->add_style('main.min.css', 'works');
