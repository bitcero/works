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
    public static function eventRmcommonLoadRightWidgets($widgets)
    {
        global $xoopsModule;

        if (!isset($xoopsModule) || 'works' !== $xoopsModule->getVar('dirname')) {
            return $widgets;
        }

        if (defined('RMCSUBLOCATION') && RMCSUBLOCATION === 'new-work') {
            $widgets[] = include PW_PATH . '/widgets/visibility.php';
            $widgets[] = include PW_PATH . '/widgets/categories.php';
            $widgets[] = include PW_PATH . '/widgets/image.php';
        }

        return $widgets;
    }
}
