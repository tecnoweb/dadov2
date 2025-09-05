<?php echo $this->render_table_name(); ?>
<?php if ($this->is_create or $this->is_csv or $this->is_print){?>
        <div class="xcrud-top-actions revolution-actions">
            <div class="btn-group pull-right revolution-btn-group">
                <?php echo $this->print_button('btn btn-default btn-revolution','fas fa-print');
                echo $this->csv_button('btn btn-default btn-revolution','fas fa-file-csv'); ?>
            </div>
            <?php echo $this->add_button('btn btn-success btn-revolution','fas fa-plus'); ?>
            <div class="clearfix"></div>
        </div>
<?php } ?>
        <div class="xcrud-list-container revolution-list-container">
        <table class="xcrud-list table table-striped table-hover table-bordered revolution-table">
            <thead class="revolution-thead">
                <?php echo $this->render_grid_head('tr', 'th'); ?>
            </thead>
            <tbody class="revolution-tbody">
                <?php echo $this->render_grid_body('tr', 'td'); ?>
            </tbody>
            <tfoot class="revolution-tfoot">
                <?php echo $this->render_grid_footer('tr', 'td'); ?>
            </tfoot>
        </table>
        </div>
        <div class="xcrud-nav revolution-nav">
            <?php echo $this->render_limitlist(true); ?>
            <?php echo $this->render_pagination(); ?>
            <?php echo $this->render_search(); ?>
            <?php echo $this->render_benchmark(); ?>
        </div>