<h1 class="rmc_titles"><?php _e('Dashboard','works'); ?></h1>

<div class="row" data-boxes="load" data-news="load" data-module="works" data-target="#works-news-content">

    <div class="col-md-6 col-lg-5">

        <!-- Quick view -->
        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('Module Status','works'); ?></h3>
            </div>
            <div class="box-content collapsable" id="wk-status">

                <table class="table">
                    <tr>
                        <td>
                            <a href="categories.php"><?php echo sprintf( __('%u Categories', 'works'), $categories ); ?></a>

                        </td>
                        <td>
                            <a href="customers.php"><?php echo sprintf( __('%u Customers', 'works'), $customers ); ?></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="types.php"><?php echo sprintf( __('%u Work types', 'works'), $types ); ?></a>

                        </td>
                        <td>
                            <a href="works.php"><?php echo sprintf( __('%u Works', 'works'), $works ); ?></a>
                        </td>
                    </tr>
                    <tfoot>
                    <tr>
                        <td colspan="2">
                            <small><?php _e('Current version:','works'); ?> <strong><?php echo RMModules::get_module_version( 'works', false ); ?></strong></small>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>

        <div data-box="box-center">
            <!-- Drafts -->
            <div class="cu-box">
                <div class="box-header">
                    <span class="fa fa-caret-up box-handler"></span>
                    <h3><?php _e('Works saved as draft','works'); ?></h3>
                </div>
                <div class="box-content collapsable" id="wk-drafts">
                    <?php foreach ($works_pending as $w): ?>
                        <div class="wk-item">
                            <strong><a href="works.php?op=edit&amp;id=<?php echo $w['id']; ?>"><?php echo $w['title']; ?></a></strong>
                            <span class="wk-dates"><?php echo $w['date']; ?></span>
                            <span class="wk-descriptions"><?php echo $w['desc']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="box-footer">
                    <a href="works.php"><?php _e('View all works','works'); ?></a>
                </div>
            </div>

            <?php foreach($widgets_left as $widget): ?>
                <div class="cu-box">
                    <div class="box-header">
                        <span class="fa fa-caret-up box-handler"></span>
                        <h3><?php echo $widget['title']; ?></h3>
                    </div>
                    <div class="box-content collapsable" id="wk-drafts">
                        <?php echo $widget['content']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="col-md-6 col-lg-7">

        <?php foreach($widgets_right as $widget): ?>
            <div class="cu-box">
                <div class="box-header">
                    <span class="fa fa-caret-up box-handler"></span>
                    <h3><?php echo $widget['title']; ?></h3>
                </div>
                <div class="box-content collapsable" id="wk-drafts">
                    <?php echo $widget['content']; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="cu-box">
            <div class="box-header">
                <span class="fa fa-caret-up box-handler"></span>
                <h3><?php _e('Works News','qpages'); ?></h3>
            </div>
            <div class="box-content" id="works-news-content">

            </div>
        </div>

    </div>

</div>
