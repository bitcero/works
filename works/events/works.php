<?php
// $Id: works.php 320 2010-04-28 02:48:54Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class WorksWorksPreload
{
	
	public function eventWorksDashboardRightWidgets($widgets){
		global $xoopsModule;
      
		if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='works'))
			return $widgets;
		
	    if (defined("WORKS_LOCATION") && WORKS_LOCATION=='dashboard'){
			include_once '../include/widgets.php';
			$widgets[] = works_widget_categories();
			$widgets[] = works_widget_types();
	    }
        
		return $widgets;
	}
	
}
