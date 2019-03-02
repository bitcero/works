<form name="frmWorkVisibility" id="work-visibility" method="post" action="works.php">
    <div class="form-group">
        <label for="work-status"><?php _e('Save work as:', 'works'); ?></label>
        <select name="status" id="work-status" class="form-control">
            <option value="draft"<?php echo $work->status == 'draft' ? ' selected' : ''; ?>><?php _e('Draft', 'works'); ?></option>
            <option value="public"<?php echo $work->status == 'public' || $work->status == '' ? ' selected' : ''; ?>><?php _e('Public', 'works'); ?></option>
            <option value="private"<?php echo $work->status == 'private' ? ' selected' : ''; ?>><?php _e('Private', 'works'); ?></option>
            <option value="scheduled"<?php echo $work->status == 'scheduled' ? ' selected' : ''; ?>><?php _e('Scheduled', 'works'); ?></option>
        </select>
    </div>

    <div class="form-group" id="visibility-groups"<?php echo $work->status != 'private' ? ' style="display: none;"' : ''; ?>>
        <label><?php _e('Allowed groups:', 'works'); ?></label>
        <?php
        $groups = new RMFormGroups('', 'groups', 1, 1, 1, $work->groups);
        echo $groups->render();
        ?>
    </div>

    <div class="form-group" id="visibility-schedule"<?php echo $work->status != 'scheduled' ? ' style="display: none;"' : ''; ?>>
        <label for="work-schedule"><?php _e('Publish at:', 'works'); ?></label>
        <input type="text" name="schedule" id="work-schedule" class="form-control" value="<?php echo $work->schedule; ?>">
    </div>

    <div class="form-group">
        <label for="work-featured"><?php _e('Work type:', 'works'); ?></label>
        <select name="featured" id="work-featured" class="form-control">
            <option value="0"<?php echo $work->featured ? '' : ' selected'; ?>><?php _e('Normal', 'works'); ?></option>
            <option value="1"<?php echo $work->featured ? ' selected' : ''; ?>><?php _e('Featured', 'works'); ?></option>
        </select>
    </div>

    <hr>

    <div class="form-group text-center">
        <button type="button" id="work-submit-forms" class="btn btn-primary">
            <?php if ($work->status == 'draft'): ?>
                <?php _e('Save as draft', 'works'); ?></button>
            <?php elseif ($work->status == 'public' || $work->status == 'private'): ?>
                <?php _e('Save and publish', 'works'); ?></button>
            <?php else: ?>
                <?php _e('Save scheduled', 'works'); ?></button>
            <?php endif; ?>
    </div>

</form>