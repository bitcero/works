<?php
// $Id: index.php 618 2011-03-04 05:41:51Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../mainfile.php';
load_mod_locale('works');

if($xoopsModuleConfig['urlmode']<=0){
    // PHP Default URLs mode
    $p = rmc_server_var($_REQUEST, 'p', 'home');
    $id = rmc_server_var($_REQUEST, 'id', '');

    switch($p){
        case 'category':
            include 'catego.php';
            break;
        case 'work':
            include 'work.php';
            break;
        default:
            include $p.'.php';
            break;
    }
    
    if (trim($p)!='') exit();
    
}

$request = str_replace(XOOPS_URL, '', RMFunctions::current_url());
$request = str_replace("/modules/works/", '', $request);

if ($xoopsModuleConfig['urlmode']>0 && $xoopsModuleConfig['htbase']!='/' && $request!=''){
    $request = str_replace(rtrim($xoopsModuleConfig['htbase'],'/').'/', '', rtrim($request,'/').'/');
}

$yesquery = false;

if (substr($request, 0, 1)=='?'){ $request = substr($request, 1); $yesquery=true; }
if ($request=='' || $request=='index.php'){
	require 'home.php';
	die();
}

$vars = array();
parse_str($request, $vars);

if (isset($vars['work'])){ $post = $vars['work']; require 'work.php'; die(); }
if (isset($vars['cat'])){ $category = $vars['cat']; require 'catego.php'; die(); }

$vars = explode('/', rtrim($request,'/'));

foreach ($vars as $i => $v){
	if ($v=='page'){
		$page = $vars[$i+1];
		break;
	}
}

/**
 * Si el primer valor es category entonces se realiza la búsqueda por
 * categoría
 */
if ($vars[0]=='category'){
	$id = $vars[1];
	require 'catego.php';
	die();
}

if ($vars[0]=='recent'){
	$categotype = 1;
	require 'recent.php';
	die();
}

if ($vars[0]=='featured'){
	require 'featured.php';
	die();
}

if ($vars[0]=='page'){
	$page = $vars[1];
	require 'home.php';
	exit();
}

/**
* Work
*/
if (!empty($vars[0])){
	$id = $vars[0];
	require 'work.php';
	exit();
}

if ($yesquery){
	require 'home.php';
	exit();
}

header("HTTP/1.0 404 Not Found");
if (substr(php_sapi_name(), 0, 3) == 'cgi')
      header('Status: 404 Not Found', TRUE);
  	else
      header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

echo "<h1>ERROR 404. Document not Found</h1>";
exit();

