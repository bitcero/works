<?php
/*
Professional Works
Module for personals and professionals portfolios
Author:     Eduardo Cortes <i.bitcero@gmail.com>
Email:      i.bitcero@gmail.com
Website:    http://eduardocortes.mx
License:    GPL 2.0
-------------------------------------------------
PLEASE: DO NOT MODIFY ABOVE LINES
*/

function works_block_details_show($options)
{
    global $id, $xoopsModule, $xoopsOption;

    /* This block only works in "works" module and in "details" page */
    if (!$xoopsModule || $xoopsModule->getVar('dirname') != 'works') {
        return;
    }

    if ($xoopsOption['module_subpage'] != 'work') {
        return;
    }

    $work = new Works_Work($id);
    if ($work->isNew()) {
        return;
    }

    $tf = new RMTimeFormatter(0, __('%d% %M% %Y%', 'works'));

    $block = array(
        'title'         => $work->title,
        'description'   => $options['description'] ? TextCleaner::getInstance()->truncate($work->description, $options['len']) : '',
        'image'         => RMImage::get()->load_from_params($work->image),
        'customer'      => isset($options['details']['customer']) ? $work->customer : '',
        'web'           => isset($options['details']['web']) ? $work->web : '',
        'url'           => isset($options['details']['web']) ? $work->url : '',
        'views'         => isset($options['details']['hits']) ? sprintf(__('%u times', 'works'), $work->views) : '',
        'comments'      => $work->comments,
        'categories'    => isset($options['details']['cats']) ? $work->categories('objects') : array(),
        'created'       => isset($options['details']['created']) ? $tf->format($work->created) : '',
        'modified'      => isset($options['details']['updated']) ? $tf->format($work->modified) : '',
        'lang'          => array(

            'categories'    => __('Categories', 'works'),
            'customer'      => __('Customer', 'works'),
            'website'       => __('Website', 'works'),
            'hits'          => __('Hits', 'works'),
            'created'       => __('Created', 'works'),
            'updated'       => __('Last update', 'works')

        )
    );

    if ($options['custom']) {
        $meta = array();

        foreach ($options['fields']['names'] as $key => $name) {
            $value = $work->get_meta($name);
            if ($value === false) {
                continue;
            }

            $meta[] = array(
                'caption'   => $options['fields']['titles'][$key],
                'value'     => $work->get_meta($name)
            );
        }

        if (!empty($meta)) {
            $block = array_merge($block, array('meta' => $meta));
        }
    }

    // Add styles
    RMTemplate::get()->add_style('blocks.css', 'works');

    return $block;
}

function works_block_details_edit($options)
{
    ob_start(); ?>

    <div class="row">

        <div class="col-md-6">

            <div class="form-group">
                <label><?php _e('Show description', 'works'); ?></label><br>
                <label class="radio-inline">
                    <input type="radio" value="1" name="options[description]"<?php echo $options['description'] ? ' checked' : ''; ?>>
                    <?php _e('Yes', 'works'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" value="0" name="options[description]"<?php echo !$options['description'] ? ' checked' : ''; ?>>
                    <?php _e('No', 'works'); ?>
                </label>
            </div>

            <div class="form-group">
                <label for="desc-len"><?php _e('Description length:', 'works'); ?></label>
                <input type="text" class="form-control" name="options[len]" value="<?php echo $options['len']; ?>" id="desc-len">
            </div>

            <div class="form-group">
                <label><?php _e('Other details to show:', 'works'); ?></label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="options[details][cats]" value="1"<?php echo isset($options['details']['cats']) ? ' checked' : ''; ?>>
                        <?php _e('Categories', 'works'); ?>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="options[details][customer]" value="1"<?php echo isset($options['details']['customer']) ? ' checked' : ''; ?>>
                        <?php _e('Customer', 'works'); ?>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="options[details][web]" value="1"<?php echo isset($options['details']['web']) ? ' checked' : ''; ?>>
                        <?php _e('Website', 'works'); ?>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="options[details][hits]" value="1"<?php echo isset($options['details']['hits']) ? ' checked' : ''; ?>>
                        <?php _e('Hits', 'works'); ?>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="options[details][created]" value="1"<?php echo isset($options['details']['created']) ? ' checked' : ''; ?>>
                        <?php _e('Creation date', 'works'); ?>
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="options[details][updated]" value="1"<?php echo isset($options['details']['updated']) ? ' checked' : ''; ?>>
                        <?php _e('Last update', 'works'); ?>
                    </label>
                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="form-group">
                <label><?php _e('Show custom fields', 'works'); ?></label><br>
                <label class="radio-inline">
                    <input type="radio" value="1" name="options[custom]"<?php echo $options['custom'] ? ' checked' : ''; ?>>
                    <?php _e('Yes', 'works'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" value="0" name="options[custom]"<?php echo !$options['custom'] ? ' checked' : ''; ?>>
                    <?php _e('No', 'works'); ?>
                </label>
            </div>

            <div class="table-responsive">
                <table class="table" id="block-fields">
                    <thead>
                    <tr>
                        <th><?php _e('Field', 'works'); ?></th>
                        <th><?php _e('Title', 'works'); ?></th>
                        <th style="width: 30px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($options['fields']['names'] as $id => $name): ?>
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="options[fields][names][]" value="<?php echo $name; ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="options[fields][titles][]" value="<?php echo $options['fields']['titles'][$id]; ?>">
                        </td>
                        <td>
                            <span class="btn btn-danger custom-delete"><span class="fa fa-times"></span></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" class="text-center">
                            <button type="button" id="block-add-field" class="btn btn-info"><span class="fa fa-plus"></span> <?php _e('Add field', 'works'); ?></button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </div>

    <script type="text/javascript">
        $(document).ready( function() {

            var html = '<tr><td>' +
                '<input type="text" class="form-control" name="options[fields][names][]" value="">' +
                '</td><td>' +
                '<input type="text" class="form-control" name="options[fields][titles][]" value="">' +
                '</td><td>' +
                '<span class="btn btn-danger custom-delete"><span class="fa fa-times"></span></span></td></tr>';

            $("#block-add-field").click( function(){

                $("#block-fields > tbody").append(html);

            });

            $("body").on('click', '.custom-delete', function(){

                $(this).parent().parent().remove();

            });

        } );
    </script>


    <?php

    $form = ob_get_clean();

    return $form;
}
