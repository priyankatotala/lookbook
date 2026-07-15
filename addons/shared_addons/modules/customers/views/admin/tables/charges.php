<?php if (!empty($charges)): ?>
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
                <th>id</th>
                <th class="collapse">Shop</th>
                <th>Description</th>
                <th class="collapse">Price</th>
                <th>Trial days</th>
                <th>Test</th>
                <th>Used</th>
                <th width="300"></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="9">
                    <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($charges as $charge): ?>
                <tr>
                    <td class="align-center"><?php echo form_checkbox('action_to[]', $charge->id); ?></td>
                    <td><?php echo $charge->id; ?></td>
                    <td>
                        <?php echo $charge->shop; ?>
                    </td>
                    <td class="collapse"><?php echo $charge->name; ?></td>
                    <td><?php echo $charge->price; ?></td>
                    <td class="collapse"><?php echo $charge->trial_days; ?></td>
                    <td class="collapse"><?php echo $charge->test ? lang('global:yes') : lang('global:no'); ?></td>
                    <td class="collapse"><?php echo $charge->used ? lang('global:yes') : lang('global:no'); ?></td>
                    <td class="actions">
                        <?php echo anchor('admin/customers/charges/edit/' . $charge->id, 'Edit', array('class' => 'btn orange')); ?>                                               
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>