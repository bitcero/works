<h1 class="rmc_titles"><span style="background-position: -64px 0;">&nbsp;</span><?php _e('Customer Types','works'); ?></h1>

<div id="pw-right-table">
	<form name="frmTypes" id="frm-types" method="POST" action="types.php">
	<div class="pw_options">
		<select name="op" id="bulk-top">
			<option value=""><?php _e('Bulk actions...','works'); ?></option>
			<option value="edit"><?php _e('Edit','works'); ?></option>
			<option value="delete"><?php _e('Delete','works'); ?></option>
		</select>
		<input type="button" id="the-op-top" value="<?php _e('Apply','works'); ?>" onclick="before_submit('frm-types');" />
	</div>
	<table width="100%" cellspacing="0" class="outer">
		<thead>
		<tr class="head" align="center">
			<th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-types").toggleCheckboxes(":not(#checkall)");' /></th>
			<th width="30"><?php _e('ID','works'); ?></th>
			<th align="left"><?php _e('Name','works'); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr class="head" align="center">
			<th width="20"><input type="checkbox" name="checkall" id="checkall2" onclick='$("#frm-types").toggleCheckboxes(":not(#checkall2)");' /></th>
			<th width="30"><?php _e('ID','works'); ?></th>
			<th align="left"><?php _e('Name','works'); ?></th>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach($types as $type): ?>
		<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
			<td><input type="checkbox" name="ids[]" id="item-<?php echo $type['id']; ?>" value="<?php echo $type['id']; ?>" /></td>
			<td><strong><?php echo $type['id']; ?></strong></td>
			<td align="left"><?php echo $type['type']; ?>
				<span class="rmc_options">
					<a href="javascript:;" onclick="select_option(<?php echo $type['id']; ?>,'edit');"><?php _e('Edit','works'); ?></a> | 
					<a href="javascript:;" onclick="select_option(<?php echo $type['id']; ?>,'delete');"><?php _e('Delete','works'); ?></a>
				</span>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="pw_options">
		<select name="opb" id="bulk-bottom">
			<option value=""><?php _e('Bulk actions...','works'); ?></option>
			<option value="edit"><?php _e('Edit','works'); ?></option>
			<option value="delete"><?php _e('Delete','works'); ?></option>
		</select>
		<input type="button" id="the-op-bottom" value="<?php _e('Apply','works'); ?>" onclick="before_submit('frm-types');" />
	</div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>

<div id="pw-left-form">
	<form name="frmAddTypes" method="post" action="types.php" id="frm-add-types" onsubmit="$(this).validate();">
	<h3><?php _e('Add new type', 'works'); ?></h3>
	<label for="type-name"><?php _e('Type name','works'); ?></label>
	<input type="text" name="type[]" id="type-name" size="50" class="required" />
	<input type="submit" value="<?php _e('Add type','works'); ?>" />
	<input type="hidden" name="op" value="save" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>
