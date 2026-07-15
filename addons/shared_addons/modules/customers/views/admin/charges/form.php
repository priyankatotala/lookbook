<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('customers_add_charge'); ?></h4>
        <?php echo form_open(uri_string(), 'class="crud" autocomplete="off"'); ?>

    <?php else: ?>
        <h4><?php echo sprintf(lang('customers_edit_charge_title'), $charge->shop); ?></h4>
        <?php echo form_open(uri_string(), 'class="crud"'); ?>
    <?php endif; ?>
</section>

<section class="item">
    <div class='content'>
    <div class="tabs">

        <!-- Content tab -->
        <div class="form_inputs">
            <fieldset>
                <ul>
                    <li class="even">
                        <label for="name">Shop: <span>*</span></label>
                        <div class="input">
                            <?php echo form_input(array('name'=>'shop','type'=>'text','id'=>'shop','value'=>set_value('shop',$charge->shop))); ?>
                        </div>
                    </li> 
                    <li class="even">
                        <label for="name">Description: <span>*</span></label>
                        <div class="input">
                            <?php echo form_input(array('name'=>'name','type'=>'text','id'=>'name','value'=>set_value('name',$charge->name))); ?>
                        </div>
                    </li> 
                    <li class="even">
                        <label for="price">Price: <span>*</span></label>
                        <div class="input">
                            <?php echo form_input(array('name'=>'price','type'=>'text','id'=>'price','value'=>set_value('price',$charge->price))); ?>
                        </div>
                    </li> 
                    <li class="even">
                        <label for="trial_days">Trial days: <span>*</span></label>
                        <div class="input">
                            <?php echo form_input(array('name'=>'trial_days','type'=>'text','id'=>'trial_days','value'=>set_value('trial_days',$charge->trial_days))); ?>
                        </div>
                    </li>
                    <li class="even">
                        <label for="test">Test: <span>*</span></label>
                        <div class="input">
                            <?php echo form_dropdown('test',array('0'=>'No','1'=>'Yes'),set_value('test',$charge->test)); ?>
                        </div>
                    </li>
                    <li class="even">
                        <label for="used">Used: <span>*</span></label>
                        <div class="input">
                            <?php echo form_dropdown('used',array('0'=>'No','1'=>'Yes'),set_value('used',$charge->used)); ?>
                        </div>
                    </li>
                </ul>
               
            </fieldset>
        </div>
    </div>
 <div class="buttons float-right padding-top">
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))); ?>
    </div>
    <?php echo form_close(); ?>
</div>
</section>