<form name="frmWorkCategories" id="work-categories" method="post" action="works.php">
    <?php $existing = $work->categories( 'id' ); ?>
    <div class="works-w-categories-container">
        <?php foreach( $categories as $category ): ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="cats[]" value="<?php echo $category['id']; ?>"<?php echo in_array( $category['id'], $existing ) ? ' checked' : ''; ?>>
                <?php echo $category['name']; ?>
            </label>
        </div>
        <?php endforeach; ?>
    </div>

    <span class="help-block text-center">
        <?php _e('Select all categories that you want to assign to this project.', 'works'); ?>
    </span>

</form>