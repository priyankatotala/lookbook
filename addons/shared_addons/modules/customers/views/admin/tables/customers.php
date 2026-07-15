<?php if (!empty($customers)): ?>
	<table border="0" class="table-list">
		<thead>
			<tr>
				<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
				<th><?php echo lang('customer_name_label');?></th>
				<th class="collapse"><?php echo lang('customer_email_label');?></th>
				<th><?php echo lang('customer_created');?></th>
				<th class="collapse"><?php echo lang('customer_status'); ?></th>
				<th width="300"></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php $n=$pagination['offset']; foreach ($customers as $customer): $n=$n+1; ?>
				<tr>
					<td class="align-center"><?php echo $n.' '; echo form_checkbox('action_to[]', $customer->id); ?></td>
					<td>
						<?php echo mailto($customer->email); ?>
					</td>
					<td class="collapse"><?php echo anchor('http://'.$customer->shop,$customer->shop,'target="_blank"'); ?></td>
					<td><?php echo $customer->created; ?></td>
                                        <td class="collapse"><?php if($customer->status==1){ echo 'Active';}else{ if($customer->status == 2){ echo 'Uninstalled'; }else{ echo 'Waiting for Acivations'; } } ?></td>
					<td class="actions">
                                            <?php echo anchor('admin/customers/check_shop_active/' . $customer->shop, 'Check if Active', array('class'=>'btn orange')); ?>                                               
                                                <?php echo anchor('admin/customers/login_as/' . $customer->shop, 'Login As', array('class'=>'btn green')); ?>                                               
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>