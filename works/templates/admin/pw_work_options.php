<ul class="work_options">
    <li class="item_edit"><a href="works.php?op=edit&amp;page=<?php echo $page; ?>&amp;id=<?php echo $work->id(); ?>"><?php _e('Edit', 'works'); ?></a></li>
    <li class="item_images"><a href="images.php?work=<?php echo $work->id(); ?>&amp;page=<?php echo $page; ?>"><?php _e('Images', 'works'); ?></a></li>
    <li class="item_fields"><a href="works.php?id=<?php echo $work->id(); ?>&amp;page=<?php echo $page; ?>&amp;op=meta"><?php _e('Custom Fields', 'works'); ?></a></li>
</ul>