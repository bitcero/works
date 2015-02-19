<h1 class="cu-section-title">
    <?php echo $edit ? __('Edit Work Item', 'works') : __('Create Work Item', 'works'); ?>
</h1>

<form name="formAdd" id="frm-add-work" method="post" action="works.php">

    <div class="form-group form-group-lg">
        <input type="text" name="title" class="form-control input-lg" id="work-title" value="<?php echo $edit ? $work->title : ''; ?>" placeholder="<?php _e('Work title...', 'works'); ?>" required>
    </div>

    <?php if( isset( $additional_fields['title'] ) && is_array( $additional_fields['title'] ) && !empty( $additional_fields['title'] )): echo implode( "\n", $additional_fields['title'] ); endif; ?>

    <div class="form-group" id="permalink-container">
        <label for="work-title-id"><?php _e('Permalink', 'works'); ?></label>
        <div class="input-group">
            <span class="input-group-addon">
                <?php echo PW_PUBLIC_URL; ?>/
            </span>
            <input type="text" class="form-control" name="titleid" id="work-title-id" value="<?php echo $work->titleid; ?>">
        </div>

    </div>

    <?php if( isset( $additional_fields['permalink'] ) && is_array( $additional_fields['permalink'] ) && !empty( $additional_fields['permalink'] )): echo implode( "\n", $additional_fields['permalink'] ); endif; ?>

    <div class="form-group">
        <?php echo $editor->render(); ?>
    </div>

    <?php if( isset( $additional_fields['editor'] ) && is_array( $additional_fields['editor'] ) && !empty( $additional_fields['editor'] )): echo implode( "\n", $additional_fields['editor'] ); endif; ?>

    <!-- Project images -->
    <div class="cu-box">
        <div class="box-header">
            <span class="fa fa-caret-up box-handler"></span>
            <h3><?php _e('Project Images', 'works'); ?></h3>
        </div>
        <div class="box-content">

            <div class="work-images" id="work-images-container">
                <?php
                $images = $work->images();
                foreach( $images as $idi => $image ): ?>
                <span data-id="existing-<?php echo $idi; ?>" style="background-image: url('<?php echo RMCURL; ?>/include/resizer.php?src=<?php echo $image['url']; ?>&w=110&h=110');">
                    <a href="#"><span class="fa fa-times"></span></a>
                </span>
                <input type="hidden" name="images[]" id="image-existing-<?php echo $idi; ?>" value="<?php echo $image['url']; ?>|<?php echo $image['title']; ?>">
                <?php endforeach; ?>
            </div>
            <div class="form-group">
                <button
                    type="button"
                    class="btn btn-success"
                    id="add-images"
                    onclick="launch_image_manager($(this));"
                    data-id="work-images-container"
                    data-type="external"
                    data-target="work_add_images"
                    data-title="<?php _e('Select work images', 'works'); ?>"
                    data-multiple="1"><span class="fa fa-image"></span> <?php _e('Add Image', 'works'); ?></button>
                <button type="button" class="btn btn-info" id="add-videos"><span class="fa fa-video-camera"></span> <?php _e('Add Video', 'works'); ?></button>
            </div>

        </div>
    </div>

    <?php if( isset( $additional_fields['images'] ) && is_array( $additional_fields['images'] ) && !empty( $additional_fields['images'] )): echo implode( "\n", $additional_fields['images'] ); endif; ?>

    <div class="cu-box">
        <div class="box-header">
            <span class="fa fa-caret-up box-handler"></span>
            <h3><?php _e('Customer Data', 'works'); ?></h3>
        </div>
        <div class="box-content">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="work-customer-name"><?php _e('Customer name', 'works'); ?></label>
                        <div class="input-group">
                            <input type="text" name="customer_name" id="work-customer-name" class="form-control" value="<?php echo $edit ? $work->customer : ''; ?>">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info"><span class="fa fa-user"></span></button>
                        </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="work-web"><?php _e('Website', 'works'); ?></label>
                                <input type="text" name="web" id="work-web" class="form-control" value="<?php echo $edit ? $work->web : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="work-url"><?php _e('URL', 'works'); ?></label>
                                <input type="text" name="url" id="work-url" class="form-control" value="<?php echo $edit ? $work->url : ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="work-customer-comment"><?php _e('Customer comment', 'works'); ?></label>
                        <textarea name="customer_comment" class="form-control" rows="5"><?php echo $edit ? $work->getVar('comment','e') : ''; ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php if( isset( $additional_fields['customer'] ) && is_array( $additional_fields['customer'] ) && !empty( $additional_fields['customer'] )): echo implode( "\n", $additional_fields['customer'] ); endif; ?>

    <!-- Basic SEO -->
    <div class="cu-box">
        <div class="box-header">
            <span class="fa fa-caret-up box-handler"></span>
            <h3><?php _e('SEO Options', 'works'); ?></h3>
        </div>
        <div class="box-content">

            <div class="form-group">
                <label for="seo-title">
                    <?php _e('Custom Title','works'); ?><br>
                </label>
                <small class="help-block"><?php _e('Insert here your own custom title for SEO purposes. This custom title will be used in title tag.','works'); ?></small>
                <input type="text" class="form-control" name="seo_title" id="seo-title" value="<?php echo $edit ? $work->getVar('seo_title') : ''; ?>">
            </div>

            <div class="form-group">
                <label for="seo-description"><?php _e('Meta Description','works'); ?></label>
                <textarea class="form-control" name="seo_description" id="seo-description" rows="4" cols="45"><?php echo $edit ? $work->getVar('seo_description','e') : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="seo-keywords"><?php _e('Meta Keywords','works'); ?></label>
                <input type="text" class="form-control" name="seo_keywords" id="seo-keywords" value="<?php echo $edit ? $work->getVar('seo_keywords','e') : ''; ?>">
            </div>

        </div>
    </div>
    <!-- // End Basic SEO -->

    <?php if( isset( $additional_fields['seo'] ) && is_array( $additional_fields['seo'] ) && !empty( $additional_fields['seo'] )): echo implode( "\n", $additional_fields['seo'] ); endif; ?>

    <div class="cu-box">
        <div class="box-header">
            <span class="fa fa-caret-up box-handler"></span>
            <h3>
                <?php _e('Custom Data','works'); ?>
            </h3>
        </div>
        <div class="box-content collapsable">
            <?php $work_metas = $work->get_meta(); ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?php _e('Field name:','qpages'); ?></label>
                        <?php if(count($work_metas)>0): ?>
                            <select name="dmeta_name" class="form-control" id="dmeta-sel">
                                <?php foreach($work_metas as $meta => $value): ?>
                                    <option value="<?php echo $meta; ?>"><?php echo $meta; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" class="form-control" name="dmeta_name" id="dmeta" value="" size="30" style="display: none;" />
                            <a href="#" class="btn btn-default" id="add-meta-button"><span class="fa fa-plus"></span> <?php _e('Add New','qpages'); ?></a>
                        <?php else: ?>
                            <input type="text" class="form-control" name="dmeta_name" id="dmeta" value="" size="30" />
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label><?php _e('Field value:','qpages'); ?></label>
                        <textarea name="dmeta_value" class="form-control" id="dvalue" rows="3" cols="50"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">&nbsp;</div>
                <div class="col-md-8"><button type="button" id="add-field" class="btn btn-success"><?php _e('Add Field','qpages'); ?></button></div>
            </div>
            <hr>

            <h4><?php _e('Existing custom data', 'works'); ?></h4>

            <div id="existing-meta">
                <div class="row">
                    <div class="col-sm-4">
                        <strong><?php _e('Field name','qpages'); ?></strong>
                    </div>
                    <div class="col-sm-8">
                        <strong><?php _e('Field value','qpages'); ?></strong>
                    </div>
                </div>
                
                <?php foreach($work_metas as $meta => $value): ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="meta_name[]" value="<?php echo $meta; ?>" class="form-control" /><br>
                                <button type="button" class="btn btn-warning btn-xs delete-meta "><?php _e('Delete','dtransport'); ?></button>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <textarea name="meta_value[]" class="form-control"><?php echo $value; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        </div>
    </div>

    <?php if( isset( $additional_fields['meta'] ) && is_array( $additional_fields['meta'] ) && !empty( $additional_fields['meta'] )): echo implode( "\n", $additional_fields['meta'] ); endif; ?>

    <input type="hidden" name="action" id="works-action" value="<?php echo $edit ? 'saveedited' : 'save'; ?>">
    <?php if ( isset($pageNum) ): ?>
        <input type="hidden" name="page" value="<?php echo $pageNum; ?>">
    <?php endif; ?>
    <?php if($edit): ?><input type="hidden" name="id" value="<?php echo $work->id(); ?>" id="work-id"><?php endif; ?>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>

</form>