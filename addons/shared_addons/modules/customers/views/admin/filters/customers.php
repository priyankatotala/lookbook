<fieldset id="filters">

    <?php echo form_open(''); ?>
    <?php echo form_hidden('f_module', $module_details['slug']); ?>
    <ul>
        <li><?php echo form_input(array('name' => 'f_keywords', 'placeholder' => 'Keywords','style'=>'width:505px;')); ?><li>
        </li><?php echo form_dropdown('f_status', $statuses, set_value('f_status','')); ?></li>    
        <li><div style="margin-top: 5px"><?php echo anchor(current_url(), 'Cancel', 'class="btn red"'); ?></div></li>
    </ul>
    <?php echo form_submit('filter','Filter');?>
    <?php echo form_close(); ?>
</fieldset>