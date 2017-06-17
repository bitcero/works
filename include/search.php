<?php
// $Id: search.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* @desc Realiza una búsqueda en el módulo desde EXM
*/
function pwSearch($queryarray, $andor, $limit, $offset, $userid){
    global $myts;
    
    include_once (XOOPS_ROOT_PATH."/modules/works/class/pwwork.class.php");

    $mc = RMSettings::module_settings( 'works' );
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $sql = "SELECT a.* FROM ".$db->prefix('mod_works_works')." a INNER JOIN ".$db->prefix('mod_works_clients')." b ON (a.public=1 AND a.client=b.id_client AND (";
    $sql1 = '';
    
    if (is_array($queryarray)){
	    foreach($queryarray as $k){
			$sql1 .= ($sql1=='' ? "" : "$andor"). " (a.title LIKE '%$k%' OR a.short LIKE '%$k%' OR b.name LIKE '%$k%' OR b.business_name LIKE '%$k%') ";
	    }
	}
	
    $sql1 .="))";
    
    $sql1.= " GROUP BY a.id_work ORDER BY a.created DESC LIMIT $offset, $limit";
    $result = $db->queryF($sql.$sql1);
    
    $ret = array();
    while ($row = $db->fetchArray($result)){
	
	$work = new Works_Work();
	$work->assignVars($row);

	$rtn = array();
	$rtn['image'] = 'images/works.png';
	
	
        $rtn['title'] = $work->title();
        $rtn['time'] = $work->created();
	$rtn['uid'] = '';
        $rtn['desc'] = $work->descShort();
        $rtn['link'] = $work->link();
        $ret[] = $rtn;
    }
    
    return $ret;
}
