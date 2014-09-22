<?php
// $Id: pw_works.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

function works_block_items_show($options){
	global $xoopsModule, $xoopsModuleConfig;

	$db = XoopsDatabaseFactory::getDatabaseConnection();
	
	$mc = RMSettings::module_settings( 'works' );

    if ( $options['category'] > 0 ){

        $sql = "SELECT w.* FROM " . $db->prefix( "mod_works_works" ) . " as w, " . $db->prefix( "mod_works_categories_rel") . " as r
                WHERE r.category = " . $options['category'] . " AND w.id_work = r.work AND w.status = 'public' ";

    } else {

        $sql = "SELECT w.* FROM " . $db->prefix( "mod_works_works" ) . " as w WHERE w.status = 'public' ";

    }

    switch( $options['type'] ){
        case 'recent':
            $sql .= "ORDER BY w.created DESC";
            break;
        case 'featured':
            $sql .= "AND featured = 1 ORDER BY RAND()";
            break;
        case 'random':
        default:
            $sql .= "ORDER BY RAND()";
            break;
    }

    $sql .= " LIMIT 0, " . ($options['limit'] > 0 ? $options['limit'] : 3);

	$result = $db->query($sql);
    $block = array();

	while($row = $db->fetchArray($result)){

		$work = new Works_Work();
		$work->assignVars($row);

        $tf = new RMTimeFormatter( 0, $options['format'] );

        $block['works'][] = array(
            'title'         => $work->title,
            'link'          => $work->permalink(),
            'description'   => $options['description'] ? TextCleaner::getInstance()->truncate( $work->description, $options['len'] ) : '',
            'created'       => sprintf( __('Created on %s', 'works'), $tf->format( $work->created ) ),
            'updated'       => $tf->format( $work->modified ),
            'image'         => $options['image'] ? RMImage::get()->load_from_params( $work->image ) : '',
            'customer'      => $work->customer,
            'web'           => $work->web,
            'url'           => $work->url,
            'views'         => $work->views,
            'meta'          => $work->get_meta()
        );

	}

    $block['options'] = array(
        'display'   => $options['display'],
        'width'     => $options['width'],
        'height'    => $options['height'],
        'grid'      => $options['grid'],
        'col'       => 12 / $options['grid']
    );

    RMTemplate::get()->add_style( 'blocks.css', 'works' );

	return $block;
}


function works_block_items_edit($options){

    $db = XoopsDatabaseFactory::getDatabaseConnection();

	ob_start(); ?>

    <div class="form-group">
        <label for="works-type"><?php _e('Works type:', 'works'); ?></label>
        <select class="form-control" name="options[type]" id="works-type">
            <option value="random"<?php echo $options['type']=='random' ? ' selected' : ''; ?>><?php _e('Random works', 'works'); ?></option>
            <option value="featured"<?php echo $options['type']=='featured' ? ' selected' : ''; ?>><?php _e('Featured works', 'works'); ?></option>
            <option value="recent"<?php echo $options['type']=='recent' ? ' selected' : ''; ?>><?php _e('Recent works', 'works'); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label for="works-category"><?php _e('Works from category:', 'works'); ?></label>
        <select name="options[category]" id="works-category" class="form-control">
            <option value="0"<?php echo $options['category'] <= 0 ? ' selected' : ''; ?>><?php _e('All categories', 'works'); ?></option>
            <?php
            $result = $db->query("SELECT * FROM ".$db->prefix('mod_works_categories')." WHERE status='active'");
            while ($row = $db->fetchArray($result)){
                $cat = new Works_Category();
                $cat->assignVars($row);

                echo '<option value="'.$cat->id().'"'.($options['category'] == $cat->id() ? ' selected' : '').'>'. $cat->name .'</option>';
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="works-limit"><?php _e('Number of works to show:', 'works'); ?></label>
        <input type="text" class="form-control" name="options[limit]" id="works-limit" value="<?php echo $options['limit']; ?>">
    </div>

    <div class="form-group">
        <label for="works-image"><?php _e('Show work image:', 'works'); ?></label><br>
        <label class="radio-inline">
            <input type="radio" name="options[image]" value="1"<?php echo $options['image'] ? ' checked' : ''; ?>>
            <?php _e('Yes', 'works'); ?>
        </label>
        <label class="radio-inline">
            <input type="radio" name="options[image]" value="0"<?php echo !$options['image'] ? ' checked' : ''; ?>>
            <?php _e('No', 'works'); ?>
        </label>
    </div>

    <div class="form-group">
        <label for="works-width"><?php _e('Image width:', 'works'); ?></label>
        <input type="text" class="form-control" name="options[width]" id="works-width" value="<?php echo $options['width'] > 0 ? $options['width'] : 70; ?>">
    </div>

    <div class="form-group">
        <label for="works-height"><?php _e('Image height:', 'works'); ?></label>
        <input type="text" class="form-control" name="options[height]" id="works-height" value="<?php echo $options['height'] > 0 ? $options['height'] : 70; ?>">
    </div>

    <div class="form-group">
        <label for="works-description"><?php _e('Show work description:', 'works'); ?></label><br>
        <label class="radio-inline">
            <input type="radio" name="options[description]" value="1"<?php echo $options['description'] ? ' checked' : ''; ?>>
            <?php _e('Yes', 'works'); ?>
        </label>
        <label class="radio-inline">
            <input type="radio" name="options[description]" value="0"<?php echo !$options['description'] ? ' checked' : ''; ?>>
            <?php _e('No', 'works'); ?>
        </label>
    </div>

    <div class="form-group">
        <label for="works-len"><?php _e('Description length:', 'works'); ?></label>
        <input type="text" class="form-control" name="options[len]" id="works-len" value="<?php echo $options['len'] > 0 ? $options['len'] : 70; ?>">
    </div>

    <div class="page-header">
        <h4><?php _e('Visualization options', 'works'); ?></h4>
    </div>

    <div class="form-group">
        <label for="works-display"><?php _e('Content layout:', 'works'); ?></label>
        <select name="options[display]" id="works-display" class="form-control">
            <option value="list"<?php echo $options['display'] != 'grid' ? ' selected' : ''; ?>><?php _e('Show as list', 'works'); ?></option>
            <option value="grid"<?php echo $options['display'] == 'grid' ? ' selected' : ''; ?>><?php _e('Show as grid', 'works'); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label for="works-grid"><?php _e('Grid columns:', 'works'); ?></label>
        <select name="options[grid]" id="works-grid" class="form-control">
            <option value="1"<?php echo $options['grid'] == 1 ? ' selected' : ''; ?>><?php _e('One', 'works'); ?></option>
            <option value="2"<?php echo $options['grid'] == 2 ? ' selected' : ''; ?>><?php _e('Two', 'works'); ?></option>
            <option value="3"<?php echo $options['grid'] == 3 ? ' selected' : ''; ?>><?php _e('Three', 'works'); ?></option>
            <option value="4"<?php echo $options['grid'] == 4 ? ' selected' : ''; ?>><?php _e('Four', 'works'); ?></option>
            <option value="6"<?php echo $options['grid'] == 6 ? ' selected' : ''; ?>><?php _e('Six', 'works'); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label for="works-format"><?php _e('Dates format:', 'works'); ?></label>
        <input type="text" class="form-control" name="options[format]" id="works-format" value="<?php echo $options['format'] != '' ? $options['format'] : "%d% %T% %Y%"; ?>">
    </div>


    <?php

    $form = ob_get_clean();

    return $form;

}
