<?php
echo form_open_multipart('lightbox/save_lightbox');
echo form_hidden('id', (isset($lightbox_id)) ? $lightbox_id : '');
echo form_hidden('s', (isset($security)) ? $security : '');
?>
<header>
    <h1>Lightbox options</h1>
    <div class="actions">
        <a href="#" class="btn btn-success preview"><i class='icon-eye-open icon-white'></i> Preview</a>
<?php echo form_submit('submit', 'Save', 'class="btn btn-success"'); ?>
    </div>
</header>
<div id="smodlightbox_name_row" class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_name">Name</label></h1>
            <p>Give a name to identify the lightbox.</p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls"><?php echo form_input(array('name' => 'smodlightbox_name', 'id' => 'smodlightbox_name', 'class' => 'input-xxlarge', 'value' => set_value('smodlightbox_name', $smodlightbox_name))); ?></div>            
        </div>   
    </div>
</div>
<div id="smodlightbox_image_row" class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_image">Image</label></h1>
            <p>Select an image to be uploaded to your theme and displayed on the lightbox.</p>
            <p><i>Suitable filetypes: jpeg, jpg, gif, png</i>
                <br/><br/>
                <i>Save the lightbox to preview this image.</i>
            </p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls"><?php echo form_upload(array('name' => 'smodlightbox_image', 'id' => 'smodlightbox_image')); ?></div>
            <?php if ($smodlightbox_image) {
                echo '<a href="' . BASE_URL . 'uploads/shops_lightbox/' . $smodlightbox_image->file_name . '?' . strtotime($updated) . '" id="smodlightbox_image_url"><img src="' . BASE_URL . 'uploads/shops_lightbox/' . $smodlightbox_image->file_name . '?' . strtotime($updated) . '" alt="image" width="150px" /><br/>' . '</a>';
            }
            ?>
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_image_link">Link to</label></h1>
            <p>Add an url to link the image on the lightbox.</p>
            <p>
                <i>Leave blank if you don't require a link</i>
            </p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required input-prepend">
            <div class="controls">
                <?php echo form_input(array('name' => 'smodlightbox_image_link', 'id' => 'smodlightbox_image_link', 'class' => 'input-xxlarge', 'value' => set_value('smodlightbox_image_link', $smodlightbox_image_link))); ?>
                <br/><br/>
                <ul class="unstyled inline">
                    <li><?php echo form_label(form_radio(array('name' => 'smodlightbox_link_target', 'id' => 'smodlightbox_link_target_top', 'value' => '_top', 'checked' => ($smodlightbox_link_target == '_top' || !$smodlightbox_link_target) ? 'true' : '')) . ' Open in the same window', 'smodlightbox_link_target_top');?></li>
                    <li><?php echo form_label(form_radio(array('name' => 'smodlightbox_link_target', 'id' => 'smodlightbox_link_target_blank', 'value' => '_blank', 'checked' => ($smodlightbox_link_target == '_blank') ? 'true' : '')) . ' Open in a new window', 'smodlightbox_link_target_blank');?></li>
                </ul>
            </div>  
        </div>
    </div>
</div>
<!--
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_width">Width</label></h1>
            <p>Set the width of your lightbox<br/></p><p><i>Leave blank to auto adjust to image size</i></p>
        </div>
    </div>
    <div class="span9 section-content">
        <label></label>
        <div class="control-group string required input-append">
            <div class="controls"><?php echo form_input(array('name' => 'smodlightbox_width', 'id' => 'smodlightbox_width', 'class' => 'input-small', 'value' => set_value('smodlightbox_width', ($smodlightbox_width) ? $smodlightbox_width : '' ))); ?><span class="add-on">px</span></div>
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_height">Height</label></h1>
            <p>Set the height of your lightbox<br/></p>
            <p>
                <i>Leave blank to auto adjust to image size</i>
            </p>
        </div>
    </div>
    <div class="span9 section-content">
        <label></label>
        <div class="control-group string required input-append">
            <div class="controls"><?php echo form_input(array('name' => 'smodlightbox_height', 'id' => 'smodlightbox_height', 'class' => 'input-small', 'value' => set_value('smodlightbox_height', ($smodlightbox_height) ? $smodlightbox_height : '' ))); ?><span class="add-on">px</span></div>
        </div>   
    </div>
</div>
-->
<?php 
    echo form_hidden('smodlightbox_width');
    echo form_hidden('smodlightbox_height');    
    echo form_hidden('smodlightbox_width_db',($smodlightbox_width) ? $smodlightbox_width : '');
    echo form_hidden('smodlightbox_height_db',($smodlightbox_height) ? $smodlightbox_height : '' );    
?>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_overlay_background">Overlay Background</label></h1>
            <p>Set the color and opacity for the overlay layer.<br/></p><p><b>Opacity Setting:</b><br/>(1 = Not transparent)<br/>(0.5 = 50% opacity)<br/>(0 = Transparent)</p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls">
<?php echo form_input(array('name' => 'smodlightbox_overlay_background', 'id' => 'smodlightbox_overlay_background', 'class' => 'color input-small', 'value' => set_value('smodlightbox_overlay_background', ($smodlightbox_overlay_background) ? $smodlightbox_overlay_background : '#000000'))); ?>
                <label for="smodlightbox_opacity">Opacity</label>
<?php echo form_input(array('name' => 'smodlightbox_opacity', 'id' => 'smodlightbox_opacity', 'class' => 'input-mini', 'value' => set_value('smodlightbox_opacity', ($smodlightbox_opacity) ? $smodlightbox_opacity : '0.85' ))); ?>
            </div>
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_content_background">Content Background</label></h1>
            <p>Set the color in the lightbox.<br/>(Only relevant if image is smaller than the lightbox)</p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls">
<?php echo form_input(array('name' => 'smodlightbox_content_background', 'id' => 'smodlightbox_content_background', 'class' => 'color input-small', 'value' => set_value('smodlightbox_content_background', ($smodlightbox_content_background) ? $smodlightbox_content_background : '#FFFFFF'))); ?>                
            </div>
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_delay_start">Delay Start</label></h1>
            <p>Set the time in seconds for the lightbox to display after the page is completely loaded.</p>
            <p><b>Note: </b>This delay will be in the preview.</p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls input-append"><?php echo form_input(array('name' => 'smodlightbox_delay_start', 'id' => 'smodlightbox_delay_start', 'class' => 'input-small', 'value' => set_value('smodlightbox_delay_start', ($smodlightbox_delay_start) ? $smodlightbox_delay_start : '' ))); ?><span class="add-on">seconds</span></div>
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="active">Activate</label></h1>
            <p>Turn on or off the lightbox on your website.</p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls"><?php echo form_dropdown('active', array('1' => 'Yes', '0' => 'No'), set_value('active', $active), 'id="active"'); ?></div>
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span12">
        <h1>Mailchimp integration</h1>
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_mailchimp">Your Mailchimp link</label></h1>
            <p>Mailchimp integration <a href='https://modapps2.com.au/modifylightbox/blog/2013/07/mailchimp-integration-instructions' target='blank'><i class='icon-question-sign'></i></a></p>
            <p>
                <i>Leave blank if you don't require a Mailchimp subscription form</i>
            </p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls"><?php echo form_input(array('name' => 'smodlightbox_mailchimp', 'id' => 'smodlightbox_mailchimp', 'class' => 'input-xxlarge', 'value' => set_value('smodlightbox_mailchimp', $smodlightbox_mailchimp))); ?></div>            
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_subscribe_background">Subscribe Button Style</label></h1>
            <p>Set the color for Subscribe button.</p>
        </div>
    </div>
    <div class="span9 section-content">
        <div class="control-group string required">
            <div class="controls">
                <ul class='inline'>
                    <li>
                        <label for="smodlightbox_subscribe_color" class='inline'>Text Color</label><?php echo form_input(array('name' => 'smodlightbox_subscribe_color', 'id' => 'smodlightbox_subscribe_color', 'class' => 'color input-small', 'value' => set_value('smodlightbox_subscribe_color', ($smodlightbox_subscribe_color) ? $smodlightbox_subscribe_color : '#FFFFFF'))); ?>
                    </li>
                    <li>
                        <label for="smodlightbox_subscribe_background" class='inline'>Background</label><?php echo form_input(array('name' => 'smodlightbox_subscribe_background', 'id' => 'smodlightbox_subscribe_background', 'class' => 'color input-small', 'value' => set_value('smodlightbox_subscribe_background', ($smodlightbox_subscribe_background) ? $smodlightbox_subscribe_background : '#96bf48'))); ?>
                    </li>
                    <li>
                        <label for="smodlightbox_subscribe_border_color" class='inline'>Border Color</label><?php echo form_input(array('name' => 'smodlightbox_subscribe_border_color', 'id' => 'smodlightbox_subscribe_border_color', 'class' => 'color input-small', 'value' => set_value('smodlightbox_subscribe_border_color', ($smodlightbox_subscribe_border_color) ? $smodlightbox_subscribe_border_color : '#7ea13d'))); ?>
                    </li>
                </ul>
            </div>
        </div>   
    </div>
</div>
<div class="row-fluid section">
    <div class="span3">
        <div class="description">
            <h1><label for="smodlightbox_subscribe_position">Subscribe Button Position</label></h1>
            <p>Set the position of mailchimp form.</p>
        </div>
    </div>
    <div class="span2 section-content">
        <div id="position_mailchimp">
            <div class="top_row">
                <div class="position top_left <?php echo ($smodlightbox_subscribe_position=='top_left')?'active':'';?>"></div>
                <div class="position top_center <?php echo ($smodlightbox_subscribe_position=='top_center')?'active':'';?>"></div>
                <div class="position top_right <?php echo ($smodlightbox_subscribe_position=='top_right')?'active':'';?>"></div>
            </div>
            <div class="center_row">
                <div class="position center_left <?php echo ($smodlightbox_subscribe_position=='center_left')?'active':'';?>"></div>
                <div class="position center_center <?php echo ($smodlightbox_subscribe_position=='center_center')?'active':'';?>"></div>
                <div class="position center_right <?php echo ($smodlightbox_subscribe_position=='center_right')?'active':'';?>"></div>
            </div>
            <div class="bottom_row">
                <div class="position bottom_left <?php echo ($smodlightbox_subscribe_position=='bottom_left' || $smodlightbox_subscribe_position=='')?'active':'';?>"></div>
                <div class="position bottom_center <?php echo ($smodlightbox_subscribe_position=='bottom_center')?'active':'';?>"></div>
                <div class="position bottom_right <?php echo ($smodlightbox_subscribe_position=='bottom_right')?'active':'';?>"></div>
            </div>
        </div>
        <?php echo form_hidden('smodlightbox_subscribe_position',($smodlightbox_subscribe_position)?$smodlightbox_subscribe_position:'bottom_left'); ?>
    </div>
    <div class="span7 section-content">
        <div id="position_margins">
            <div id="position_margin_top">
                <div class="input-prepend input-append">
                    <span class="add-on">Top </span>
                    <input class="span4" name="smodlightbox_subscribe_distance_top" type="text" value="<?php echo ($smodlightbox_subscribe_distance_top)?$smodlightbox_subscribe_distance_top:'20'; ?>" />
                    <span class="add-on">px</span>
                </div>
            </div>
            <div id="position_margin_left">
                <div class="input-prepend input-append">
                    <span class="add-on">Left </span>
                    <input class="span4" name="smodlightbox_subscribe_distance_left" type="text" value="<?php echo ($smodlightbox_subscribe_distance_left)?$smodlightbox_subscribe_distance_left:'20'; ?>" />
                    <span class="add-on">px</span>
                </div>
            </div>
            <div id="position_margin_right">
                <div class="input-prepend input-append">
                    <span class="add-on">Right </span>
                    <input class="span4" name="smodlightbox_subscribe_distance_right" type="text" value="<?php echo ($smodlightbox_subscribe_distance_right)?$smodlightbox_subscribe_distance_right:'20'; ?>" />
                    <span class="add-on">px</span>
                </div>
            </div>
            <div id="position_margin_bottom">
                <div class="input-prepend input-append">
                    <span class="add-on">Bottom </span>
                    <input class="span4" name="smodlightbox_subscribe_distance_bottom" type="text" value="<?php echo ($smodlightbox_subscribe_distance_bottom)?$smodlightbox_subscribe_distance_bottom:'20'; ?>" />
                    <span class="add-on">px</span>
                </div>
            </div>
        </div>
    </div>
</div>
<fieldset style="display:none;">
    <legend>Container</legend>
    <table>
        <tr>
            <td><label for="smodlightbox_transition">Transition</label></td>
            <td><?php echo form_dropdown('smodlightbox_transition', array('elastic' => 'Elastic', 'fade' => 'Fade', 'none' => 'None'), set_value('smodlightbox_transition', $smodlightbox_transition), 'id="smodlightbox_transition"'); ?></td>	      
        </tr>
        <tr>
            <td><label for="smodlightbox_speed">Speed <small>(in Seconds)</small></label></td>
            <td><?php echo form_input(array('name' => 'smodlightbox_speed', 'id' => 'smodlightbox_speed', 'value' => set_value('smodlightbox_speed', ($smodlightbox_speed == '') ? '350' : $smodlightbox_speed ))); ?></td></tr>
        <tr>
            <td><label for="smodlightbox_scale_photos">Scale Photos</label></td>
            <td><?php echo form_dropdown('smodlightbox_scale_photos', array('1' => 'Yes', '0' => 'No'), set_value('smodlightbox_scale_photos', $smodlightbox_scale_photos), 'id="smodlightbox_scale_photos"'); ?></td>	      
        </tr>
        <tr>
            <td><label for="smodlightbox_scrolling">Scrolling</label></td>
            <td><?php echo form_dropdown('smodlightbox_scrolling', array('1' => 'Yes', '0' => 'No'), set_value('smodlightbox_scrolling', $smodlightbox_scrolling), 'id="smodlightbox_scrolling"'); ?></td>	      
        </tr>
        <tr>
            <td><label for="smodlightbox_auto_open">Auto Open</label></td>
            <td><?php echo form_dropdown('smodlightbox_auto_open', array('1' => 'Yes', '0' => 'No'), set_value('smodlightbox_auto_open', $smodlightbox_auto_open), 'id="smodlightbox_auto_open"'); ?></td>	      
        </tr>
        <tr>
            <td><label for="smodlightbox_overlay_close">Overlay Close</label></td>
            <td><?php echo form_dropdown('smodlightbox_overlay_close', array('1' => 'Yes', '0' => 'No'), set_value('smodlightbox_overlay_close', $smodlightbox_overlay_close), 'id="smodlightbox_overlay_close"'); ?></td>	      
        </tr>
        <tr>
            <td><label for="smodlightbox_escape_close">Close on Esc</label></td>
            <td><?php echo form_dropdown('smodlightbox_escape_close', array('1' => 'Yes', '0' => 'No'), set_value('smodlightbox_escape_close', $smodlightbox_escape_close), 'id="smodlightbox_escape_close"'); ?></td>	      
        </tr>
        <tr>
            <td><label for="smodlightbox_fade_out">Fade Out</label></td>
            <td><?php echo form_input(array('name' => 'smodlightbox_fade_out', 'id' => 'smodlightbox_fade_out', 'value' => set_value('smodlightbox_fade_out', ($smodlightbox_fade_out == '') ? '300' : $smodlightbox_fade_out ))); ?></td>	      
        </tr>
    </table>
</fieldset>
<footer>
    <div class="alert alert-info">
        <strong>Hey Modify user!</strong> Do you thing we need to add more options? <a href="<?php echo site_url('contact');?>" target="_blank">Let us know by sending a message here.</a>
</div>
    <div class="actions">
        <a href="#" class="btn btn-success preview"><i class='icon-eye-open icon-white'></i> Preview</a>
        <?php echo form_submit('submit', 'Save', 'class="btn btn-success"'); ?>
    </div>
</footer>
<?php echo form_close();
?>