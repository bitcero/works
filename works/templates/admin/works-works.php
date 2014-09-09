<h1 class="rmc_titles"><span style="background-position: -32px -32px;">&nbsp;</span><?php _e('Works','works'); ?></h1>
<form name="frmWorks" id="frm-works" method="POST" action="works.php">
<div class="pw_options">
    <?php $nav->display(false); ?>
    <select name="op" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','works'); ?></option>
        <option value="public"><?php _e('Visible','works'); ?></option>
        <option value="nopublic"><?php _e('Hidden','works'); ?></option>
        <option value="mark"><?php _e('Featured','works'); ?></option>
        <option value="nomark"><?php _e('Normal','works'); ?></option>
        <option value="delete"><?php _e('Delete','works'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','works'); ?>" onclick="before_submit('frm-works');" /> &nbsp;
    <a href="works.php?page=<?php echo $page; ?>"<?php if(!isset($show) || $show==''): ?> style="font-weight: bold;"<?php endif; ?>><?php _e('Show all','works'); ?></a> |
    <a href="works.php?page=<?php echo $page; ?>&amp;show=public"<?php if(isset($show) && $show=='public'): ?> style="font-weight: bold;"<?php endif; ?>><?php _e('Published', 'works'); ?></a> |
    <a href="works.php?page=<?php echo $page; ?>&amp;show=hidden"<?php if(isset($show) && $show=='hidden'): ?> style="font-weight: bold;"<?php endif; ?>><?php _e('Hidden','works'); ?></a>
</div>
<table class="outer" cellspacing="0" widht="100%">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-works").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','works'); ?></th>
        <th align="left"><?php _e('Name','works'); ?></th>
        <th align="left"><?php _e('Description','works'); ?></th>
        <th><?php _e('Category','works'); ?></th>
        <th><?php _e('Customer','works'); ?></th>
        <th><?php _e('Start date','works'); ?></th>
        <th><?php _e('Featured','works'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-works").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30"><?php _e('ID','works'); ?></th>
        <th align="left"><?php _e('Name','works'); ?></th>
        <th align="left"><?php _e('Description','works'); ?></th>
        <th><?php _e('Category','works'); ?></th>
        <th><?php _e('Customer','works'); ?></th>
        <th><?php _e('Start date','works'); ?></th>
        <th><?php _e('Featured','works'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($works)): ?>
    <tr class="even">
        <td colspan="8" align="center"><?php _e('There are not works registered yet!','works'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($works as $work): ?>
	<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $work['id']; ?>" id="item-<?php echo $work['id']; ?>" /></td>
		<td><strong><?php echo $work['id']; ?></strong></td>
		<td align="left">
            <?php if(!$work['public']): ?>
                <?php echo $work['title']; ?>
                <em>[Hidden]</em>
            <?php else: ?>
                <strong><?php echo $work['title']; ?></strong>
            <?php endif; ?>
            <span class="rmc_options">
            <a href="./works.php?op=edit&amp;id=<?php echo $work['id']; ?>&amp;page=<?php echo $page; ?>"><?php _e('Edit','admin_mywords'); ?></a> |
            <a href="javascript:;" onclick="select_option(<?php echo $work['id']; ?>,'delete','frm-works');"><?php echo _e('Delete','works'); ?></a> |
            <a href="./images.php?work=<?php echo $work['id']; ?>"><?php _e('Images','works'); ?></a> |
            <a href="./works.php?page=<?php echo $page; ?>&amp;id=<?php echo $work['id']; ?>&op=meta"><?php _e('Custom Fields','works'); ?></a>
            </span>
        </td>
        <td align="left"><?php echo $work['description']; ?></td>
		<td><?php echo $work['catego']; ?></td>
		<td><?php echo $work['client']; ?></td>
		<td><?php echo $work['start']; ?></td>
		<td><?php if($work['mark']): ?><img src="<?php echo XOOPS_URL; ?>/modules/works/images/ok.png" /><?php else: ?><img src="<?php echo XOOPS_URL; ?>/modules/works/images/no.png" /><?php endif; ?></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<div class="pw_options">
    <?php $nav->display(false); ?>
    <select name="opb" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','works'); ?></option>
        <option value="public"><?php _e('Visible','works'); ?></option>
        <option value="nopublic"><?php _e('Hidden','works'); ?></option>
        <option value="mark"><?php _e('Featured','works'); ?></option>
        <option value="nomark"><?php _e('Normal','works'); ?></option>
        <option value="delete"><?php _e('Delete','works'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','works'); ?>" onclick="before_submit('frm-works');" />
</div>
<input type="hidden" name="pag" value="<?php echo $page ?>" />
<input type="hidden" name="show" value="<?php echo $show ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
