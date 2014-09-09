<h1 class="rmc_titles"><?php _e('Works Categories','works'); ?></h1>

<form name="frmCategos" id="frm-categos" method="POST" action="categories.php">

    <div class="row">
        <div class="col-sm-5">
            <div class="cu-bulk-actions">
                <select name="action" id="bulk-top" class="form-control">
                    <option value=""><?php _e('Bulk actions...','works'); ?></option>
                    <option value="update"><?php _e('Save changes','works'); ?></option>
                    <option value="delete"><?php _e('Delete','works'); ?></option>
                    <option value="active"><?php _e('Enable categories','works'); ?></option>
                    <option value="desactive"><?php _e('Disable categories','works'); ?></option>
                </select>
                <button type="button" id="the-op-top" class="btn btn-default" onclick="before_submit('frm-categos');"><?php _e('Apply','works'); ?></button>
                <?php echo $works_extra_options; ?>
            </div>
        </div>
        <div class="col-sm-7">
            <ul class="nav nav-pills">
                <li><a href="categories.php?action=new"><span class="fa fa-plus"></span> <?php _e('New Category', 'works'); ?></a></li>
            </ul>
        </div>
    </div>

<table class="table table-bordered" cellspacing="1">
    <thead>
	<tr>
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30" class="text-center"><?php _e('ID','works'); ?></th>
		<th align="left"><?php _e('Name','works'); ?></th>
        <th class="text-center"><?php _e('Short name','works'); ?></th>
        <th><?php _e('Description','works'); ?></th>
		<th class="text-center"><?php _e('Works','works'); ?></th>
		<th class="text-center"><?php _e('Active','works'); ?></th>
		<th class="text-center"><?php _e('Order','works'); ?></th>
	</tr>
    </thead>
    
    <tfoot>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30" class="text-center"><?php _e('ID','works'); ?></th>
        <th align="left"><?php _e('Name','works'); ?></th>
        <th class="text-center"><?php _e('Short name','works'); ?></th>
        <th><?php _e('Description','works'); ?></th>
        <th class="text-center"><?php _e('Works','works'); ?></th>
        <th class="text-center"><?php _e('Active','works'); ?></th>
        <th class="text-center"><?php _e('Order','works'); ?></th>
    </tr>
    </tfoot>
    
    <tbody>
    <?php if ( empty( $categories ) ): ?>
    <tr>
        <td class="text-center" colspan="8"><span class="label label-info"><?php _e('There are not categories yet!', 'works'); ?></span></td>
    </tr>
    <?php endif; ?>
	<?php foreach($categories as $cat): ?>
	<tr class="text-center" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $cat['id']; ?>" id="item-<?php echo $cat['id']; ?>" /></td>
		<td><strong><?php echo $cat['id']; ?></strong></td>
		<td class="text-left"><a href="<?php echo $cat['link']; ?>"><?php echo $cat['name']; ?></a>
        <span class="cu-item-options">
            <a href="categories.php?action=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','works'); ?></a> |
            <a href="#" onclick="select_option(<?php echo $cat['id']; ?>,'delete','frm-categos'); return false;"><?php _e('Delete','works'); ?></a>
        </span>
        </td>
        <td><?php echo $cat['nameid']; ?></td>
        <td class="text-left"><?php echo $cat['description']; ?></td>
		<td><?php echo $cat['works']; ?></td>
		<td><?php if($cat['active']): ?><img src="<?php echo PW_URL; ?>/images/ok.png" /><?php else: ?><img src="<?php echo PW_URL; ?>/images/no.png" /><?php endif; ?></td>
		<td><input type="text" name="order[<?php echo $cat['id']; ?>]" value="<?php echo $cat['position']; ?>" size="3" style="text-align: center;" /></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="cu-bulk-actions">
	<select name="actionb" id="bulk-bottom" class="form-control">
		<option value=""><?php _e('Bulk actions...','works'); ?></option>
		<option value="update"><?php _e('Save changes','works'); ?></option>
		<option value="delete"><?php _e('Delete','works'); ?></option>
		<option value="active"><?php _e('Enable categories','works'); ?></option>
		<option value="desactive"><?php _e('Disable categories','works'); ?></option>
	</select>
	<button type="button" id="the-op-bottom" onclick="before_submit('frm-categos');" class="btn btn-default"><?php _e('Apply','works'); ?></button>
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
