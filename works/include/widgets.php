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

