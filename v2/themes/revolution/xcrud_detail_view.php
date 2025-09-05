<?php echo $this->render_table_name($mode); ?>
<div class="xcrud-top-actions btn-group revolution-actions">
    <?php 
    echo $this->render_button('save_return','save','list','btn btn-primary btn-revolution','fas fa-save','create,edit');
    echo $this->render_button('save_new','save','create','btn btn-default btn-revolution','fas fa-plus','create,edit');
    echo $this->render_button('save_edit','save','edit','btn btn-default btn-revolution','fas fa-edit','create,edit');
    echo $this->render_button('return','list','','btn btn-warning btn-revolution','fas fa-arrow-left'); ?>
</div>
<div class="xcrud-view revolution-form">
<?php echo $mode == 'view' ? $this->render_fields_list($mode,array('tag'=>'table','class'=>'table revolution-view-table')) : $this->render_fields_list($mode,'div','div','label','div'); ?>
</div>
<div class="xcrud-nav revolution-nav">
    <?php echo $this->render_benchmark(); ?>
</div>