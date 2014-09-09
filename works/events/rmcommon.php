<?php
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class WorksRmcommonPreload
{

    public function eventRmcommonLoadRightWidgets($widgets){
        global $xoopsModule;

        if (!isset($xoopsModule) || $xoopsModule->getVar('dirname')!='works')
            return $widgets;

        if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='new-work'){

            $widgets[] = include_once(PW_PATH . '/widgets/visibility.php');
            $widgets[] = include_once(PW_PATH . '/widgets/categories.php');
            $widgets[] = include_once(PW_PATH . '/widgets/image.php');

        }

        return $widgets;
    }

}