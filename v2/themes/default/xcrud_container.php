<div class="xcrud<?php echo $this->is_rtl ? ' xcrud_rtl' : ''?>" data-instance="<?php echo $this->instance_name ?>">
    <?php echo $this->render_table_name(false, 'div', true)?>
    <div class="xcrud-container"<?php echo ($this->start_minimized) ? ' style="display:none;"' : '' ?>>
        <div class="xcrud-ajax" id="xcrud-<?php echo $this->instance_name ?>">
            <?php echo $this->render_view() ?>
        </div>
        <div class="xcrud-overlay"></div>
    </div>
</div>