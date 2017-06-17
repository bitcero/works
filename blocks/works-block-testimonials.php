<?php
// $Id: pw_comments.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

function works_block_testimonials_show($options){
	global $xoopsModule, $xoopsModuleConfig;

	$db = XoopsDatabaseFactory::getDatabaseConnection();
	$mc = RMSettings::module_settings( 'works' );


	$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE status='public' AND `comment` != '' ORDER BY ".($options['type'] ? " created DESC " : " RAND() ");
	$sql.= " LIMIT 0,".$options['limit'];
	$result = $db->query($sql);
	$clients = array();
	while ($row = $db->fetchArray($result)){
		$work = new Works_Work();
		$work->assignVars($row);
		
		$rtn = array();
		$rtn['customer'] = $work->customer;
		$rtn['comment'] = $work->comment;
		$rtn['url'] = $work->url;
		$rtn['web'] = $work->web;

        $rtn['lang_cite'] = sprintf( __('%s from %s', 'works'), $work->customer, '<cite><a href="' . $work->url . '">' . $work->web . '</a>');
	
		$block['works'][] = $rtn;

	}
	
	return $block;

}


function works_block_testimonials_edit($options){

    ob_start();
    ?>

    <div class="row form-group">
        <div class="col-sm-4 col-md-3 col-sm-offset-1">
            <label for="comments-number"><?php _e('Comments number:', 'works'); ?></label>
        </div>
        <div class="col-sm-6 col-md-7">
            <input type="text" class="form-control" id="comments-number" name="options[limit]" value="<?php echo $options['limit']; ?>">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-4 col-md-3 col-sm-offset-1">
            <label for="comments-wtype"><?php _e('Work type:', 'works'); ?></label>
        </div>
        <div class="col-sm-6 col-md-7">
            <label class="radio-inline">
                <input type="radio" name="options[type]" value="0"<?php echo !$options['type'] ? ' checked' : ''; ?>>
                <?php _e('Random Works', 'works'); ?>
            </label>
            <label class="radio-inline">
                <input type="radio" name="options[type]" value="1"<?php echo $options['type'] ? ' checked' : ''; ?>>
                <?php _e('Recent Works', 'works'); ?>
            </label>
        </div>
    </div>

    <?php

    $form = ob_get_clean();

	return $form;
}

