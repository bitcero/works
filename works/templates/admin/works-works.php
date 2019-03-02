<h1 class="cu-section-title"><?php _e('Works', 'works'); ?></h1>

<form name="frmWorks" id="frm-works" method="POST" action="works.php">
<div class="cu-bulk-actions">

    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <select name="op" id="bulk-top" class="form-control">
                    <option value=""><?php _e('Bulk actions...', 'works'); ?></option>
                    <option value="public"><?php _e('Visible', 'works'); ?></option>
                    <option value="nopublic"><?php _e('Hidden', 'works'); ?></option>
                    <option value="mark"><?php _e('Featured', 'works'); ?></option>
                    <option value="nomark"><?php _e('Normal', 'works'); ?></option>
                    <option value="delete"><?php _e('Delete', 'works'); ?></option>
                </select>
                <span class="input-group-btn">
                    <button type="button" id="the-op-top" onclick="before_submit('frm-works');" class="btn btn-default"><?php _e('Apply', 'works'); ?></button>
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <div class="dropdown">
                <button class="btn btn-blue-grey dropdown-toggle" type="button" id="works-select-status" data-toggle="dropdown">
                    <?php
                    switch ($show) {
                        case 'public':
                            $status_label = __('Public', 'works');
                            break;
                        case 'draft':
                            $status_label = __('Drafts', 'works');
                            break;
                        case 'private':
                            $status_label = __('Privates', 'works');
                            break;
                        case 'scheduled':
                            $status_label = __('Scheduled', 'works');
                            break;
                        default:
                            $status_label = __('All', 'works');
                            break;
                    }
                    ?>
                    <?php echo sprintf(__('Filter projects: %s', 'works'), '<strong>' . $status_label . '</strong>'); ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="worksMenu">
                    <li<?php if (!isset($show) || $show==''): ?> class="active"<?php endif; ?>>
                        <a href="works.php?page=<?php echo $page; ?>">
                            <?php echo $cuIcons->getIcon('svg-rmcommon-list'); ?>
                            <?php _e('Show all', 'works'); ?>
                        </a>
                    </li>
                    <li<?php if (isset($show) && $show=='public'): ?> class="active"<?php endif; ?>>
                        <a href="works.php?page=<?php echo $page; ?>&amp;show=public">
                            <?php echo $cuIcons->getIcon('svg-rmcommon-send'); ?>
                            <?php _e('Public', 'works'); ?>
                        </a>
                    </li>
                    <li<?php if (isset($show) && $show=='draft'): ?> class="active"<?php endif; ?>>
                        <a href="works.php?page=<?php echo $page; ?>&amp;show=draft">
                            <?php echo $cuIcons->getIcon('svg-rmcommon-document'); ?>
                            <?php _e('Drafts', 'works'); ?>
                        </a>
                    </li>
                    <li<?php if (isset($show) && $show=='private'): ?> class="active"<?php endif; ?>>
                        <a href="works.php?page=<?php echo $page; ?>&amp;show=private">
                            <?php echo $cuIcons->getIcon('svg-rmcommon-eye-slash'); ?>
                            <?php _e('Privates', 'works'); ?>
                        </a>
                    </li>
                    <li<?php if (isset($show) && $show=='scheduled'): ?> class="active"<?php endif; ?>>
                        <a href="works.php?page=<?php echo $page; ?>&amp;show=scheduled">
                            <?php echo $cuIcons->getIcon('svg-rmcommon-calendar'); ?>
                            <?php _e('Scheduled', 'works'); ?>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="col-md-6">
                <?php $nav->display(false); ?>
            </div>

        </div>
    </div>

</div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php _e('Existing Projects', 'works'); ?></h3>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th width="20" class="text-center"><input type="checkbox" id="checkall" onclick='$("#frm-works").toggleCheckboxes(":not(#checkall)");' /></th>
                    <th width="30" class="text-center"><?php _e('ID', 'works'); ?></th>
                    <th><?php _e('Name', 'works'); ?></th>
                    <th class="text-center"><?php _e('Customer', 'works'); ?></th>
                    <th class="text-center"><?php _e('Categories', 'works'); ?></th>
                    <th class="text-center"><?php _e('Created', 'works'); ?></th>
                    <th class="text-center"><?php _e('Modified', 'works'); ?></th>
                    <th class="text-center"><?php _e('Featured', 'works'); ?></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th width="20" class="text-center"><input type="checkbox" id="checkall" onclick='$("#frm-works").toggleCheckboxes(":not(#checkall)");' /></th>
                    <th width="30" class="text-center"><?php _e('ID', 'works'); ?></th>
                    <th><?php _e('Name', 'works'); ?></th>
                    <th class="text-center"><?php _e('Customer', 'works'); ?></th>
                    <th class="text-center"><?php _e('Categories', 'works'); ?></th>
                    <th class="text-center"><?php _e('Created', 'works'); ?></th>
                    <th class="text-center"><?php _e('Modified', 'works'); ?></th>
                    <th class="text-center"><?php _e('Featured', 'works'); ?></th>
                </tr>
                </tfoot>
                <tbody>
                <?php if (empty($works)): ?>
                    <tr class="text-center">
                        <td colspan="8"><span class="text-info"><?php _e('There are not works registered yet!', 'works'); ?></span></td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($works as $work): ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="ids[]" value="<?php echo $work['id']; ?>" id="item-<?php echo $work['id']; ?>" /></td>
                        <td class="text-center"><strong><?php echo $work['id']; ?></strong></td>
                        <td>
                            <?php switch ($work['status']) {
                                case 'public':
                                    echo '<span class="fa fa-globe"></span>';
                                    break;
                                case 'draft':
                                    echo '<span class="fa fa-edit"></span>';
                                    break;
                                case 'private':
                                    echo '<span class="fa fa-eye-slash"></span>';
                                    break;
                                case 'scheduled':
                                    echo '<span class="fa fa-calendar"></span>';
                                    break;

                            } ?>
                            <?php echo $work['title']; ?>

                            <span class="cu-item-options">
            <a href="works.php?action=edit&amp;id=<?php echo $work['id']; ?>&amp;page=<?php echo $page; ?>"><?php _e('Edit', 'admin_mywords'); ?></a>
            <a href="#" onclick="select_option(<?php echo $work['id']; ?>,'delete','frm-works'); return false;"><?php echo _e('Delete', 'works'); ?></a>
                                <?php if ($work['status'] == 'scheduled' || $work['status'] == 'draft'): ?>
                                    <a href="<?php echo $work['url']; ?>" target="_blank"><?php _e('Preview', 'works'); ?></a>
                                <?php else: ?>
                                    <a href="<?php echo $work['url']; ?>" target="_blank"><?php _e('View', 'works'); ?></a>
                                <?php endif; ?>
            </span>
                        </td>
                        <td align="left"><small><?php echo $work['customer']; ?></small></td>
                        <td class="text-center">
                            <small><?php foreach ($work['categories'] as $id => $cat): ?>
                                    <?php echo $cat; ?>,
                                <?php endforeach; ?></small>
                        </td>
                        <td class="text-center">
                            <small><?php echo $work['created']; ?></small>
                        </td>
                        <td class="text-center"><small><?php echo $work['modified']; ?></small></td>
                        <td class="text-center">
                            <?php if ($work['featured']): ?><img src="<?php echo XOOPS_URL; ?>/modules/works/images/ok.png" /><?php else: ?><img src="<?php echo XOOPS_URL; ?>/modules/works/images/no.png" /><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<div class="cu-bulk-actions">
    <div class="row">

        <div class="col-md-5">
            <div class="input-group">
                <select name="opb" id="bulk-bottom" class="form-control">
                    <option value=""><?php _e('Bulk actions...', 'works'); ?></option>
                    <option value="public"><?php _e('Visible', 'works'); ?></option>
                    <option value="nopublic"><?php _e('Hidden', 'works'); ?></option>
                    <option value="mark"><?php _e('Featured', 'works'); ?></option>
                    <option value="nomark"><?php _e('Normal', 'works'); ?></option>
                    <option value="delete"><?php _e('Delete', 'works'); ?></option>
                </select>
                <span class="input-group-btn">
                    <button type="button" id="the-op-bottom" onclick="before_submit('frm-works');" class="btn btn-default"><?php _e('Apply', 'works'); ?></button>
                </span>
            </div>
        </div>

        <div class="col-md-7">
            <?php $nav->display(false); ?>
        </div>

    </div>


</div>
<input type="hidden" name="pag" value="<?php echo $page ?>" />
<input type="hidden" name="show" value="<?php echo $show ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
