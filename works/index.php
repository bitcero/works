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

if($xoopsModuleConfig['permalinks']<=0){
    // PHP Default URLs mode
    $p = RMHttpRequest::request( 'p', 'string', 'home' );
    $id = RMHttpRequest::request( 'id', 'string', '' );

    switch($p){
        case 'category':
            include 'category.php';
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

$request = str_replace(XOOPS_URL, '', RMUris::current_url());
$request = str_replace("/modules/works/", '', $request);

if ($xoopsModuleConfig['permalinks']>0 && $xoopsModuleConfig['htbase']!='/' && $request!=''){
    $request = str_replace(rtrim($xoopsModuleConfig['htbase'],'/').'/', '', rtrim($request,'/').'/');
}
$yesquery = false;

// Allow to plugins to manage requests to module
$request = RMEvents::get()->run_event( 'works.parse.request', $request );

if (substr($request, 0, 1)=='?'){ $request = substr($request, 1); $yesquery=true; }
if ($request=='' || $request=='index.php'){
	require 'home.php';
	die();
}

$vars = array();
parse_str($request, $vars);

if (isset($vars['work'])){ $post = $vars['work']; require 'work.php'; die(); }
if (isset($vars['cat'])){ $category = $vars['cat']; require 'category.php'; die(); }

$vars = explode('/', rtrim($request,'/'));

foreach ($vars as $i => $v){
	if ($v=='page'){
		$page = $vars[$i+1];
        unset( $vars[$i], $vars[$i+1] );
		break;
	}
}
/**
 * Si el primer valor es category entonces se realiza la búsqueda por
 * categoría
 */
if ($vars[0]=='category'){
    array_shift( $vars );
	$id = implode( "/", $vars );
	require 'category.php';
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

RMFunctions::error_404( __('Document not found', 'docs'), 'docs' );
exit();

