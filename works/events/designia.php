<?php

class WorksDesigniaPreload
{
    public function eventDesigniaGetNavItems(){
        echo '<li class=nav_item>
                    <a href="'.XOOPS_URL.'/modules/works/admin/">
                        <img src="'.XOOPS_URL.'/modules/works/images/workstool.png" alt="'.__('Portfolio','works').'" />
                        <p>'.__('Portfolio','works').'</p>
                    </a>
                </li>';
    }
}