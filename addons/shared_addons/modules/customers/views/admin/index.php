<section class="title">
	<h4>Customers</h4>
</section>

<section class="item">
    	<div class="content">
	
		<?php template_partial('filters') ?>
	
		<?php echo form_open('admin/customers/action'); ?>
		
			<div id="filter-stage">
                                <?php template_partial('table'); ?>
                        </div>
		
			<div class="table_action_buttons">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('activate', 'delete') )) ?>
			</div>
	
		<?php echo form_close() ?>
	</div>
</section>