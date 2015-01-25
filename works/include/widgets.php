<?php
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
load_mod_locale('works');

function works_widget_categories(){
	global $xoopsSecurity;
	
	$widget['title'] = __("Quick Categories", 'works');
	$widget['icon'] = '../images/cats16.png';
	ob_start();
?>
<form name="frm_categos" id="frm-categos" method="post" action="categories.php">
    <div class="form-group">
        <label for="title"><?php _e('Title','works'); ?></label>
        <input type="text" name="name" id="title" size="30" class="form-control">
    </div>

    <div class="form-group">
        <label for="description"><?php _e('Description','works'); ?></label>
        <textarea name="desc" id="description" class="form-control" rows="4"></textarea>
    </div>
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="return" value="<?php echo urlencode("admin/index.php"); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	<button type="submit" class="btn btn-primary"><?php _e('Create Category','works'); ?></button>
</form>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}

function works_widget_types(){
	global $xoopsSecurity;
	
	$widget['title'] = __("Quick Customers Types",'works');
	$widget['icon'] = '../images/types.png';
	ob_start();
?>
<form name="frm_types" id="frm-types" method="post" action="types.php">
    <div class="input-group">
        <label for="type-name" class="input-group-addon"><?php _e('Type name:','works'); ?></label>
        <input type="text" name="type[]" class="form-control" id="type-name" size="30" />
        <span class="input-group-btn">
            <button type="submit" class="btn btn-primary"><?php _e('Create Type','works'); ?></button>
        </span>
    </div>


	<input type="hidden" name="op" value="save" />
	<input type="hidden" name="return" value="<?php echo urlencode("admin/index.php"); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}
