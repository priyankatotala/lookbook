<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<header>
    <h1><?php echo anchor('lookbook/edit/' . $lookbook . '/' . sha1($lookbook . $this->config->item('encryption_key')), 'Lookbook') . ' / Edit Theme: ' . $theme->title; ?></h1>    
</header>
<div class="themes">
    <ul class="nav nav-tabs" id="theme-menu">
        <li class="active"><a href="#lightbox-settings" data-toggle="tab">Lightbox Settings</a></li>
        <li><a href="#slider-settings" data-toggle="tab">Slideshow Settings</a></li>
        <!--<li><a href="#editor" data-toggle="tab">Editor</a></li>-->
    </ul>
    <?php
    echo form_open_multipart('themes/save');
    echo form_hidden('id', $lookbook);
    echo form_hidden('theme', $theme->id);
    echo form_hidden('handle', $theme->handle);
    echo form_hidden('s', sha1($lookbook . $this->config->item('encryption_key')));
    ?>
    <div class="tab-content">
        <div class="tab-pane active" id="lightbox-settings">
            <div id="effects" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="display_effect">Diplay effect</label></h1>
                        <p>Select the effect you want to add to your lightbox.</p>
                    </div>
                </div>
                <div class="col-xs-9 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('display_effect', array('' => 'None', 'modlbx-mfp-move-horizontal' => 'Move Horizontal', 'modlbx-mfp-newspaper' => 'Newspaper', 'modlbx-mfp-move-from-top' => 'Move From Top', 'modlbx-mfp-3d-unfold' => '3d Unfold', 'modlbx-mfp-zoom-out' => 'Zoom Out', 'modlbx-mfp-zoom-in' => 'Zoom In', 'my-modlbx-mfp-slide-bottom' => 'Slide Bottom'), set_value('display_effect', $display_effect), 'id="display_effect" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('display_effect'); ?></div>
                    </div>   
                </div>
            </div>
            <div class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="overlay_background">Overlay Background</label></h1>
                        <p>Set the color and opacity for the overlay layer.<br/></p><p><b>Opacity Setting:</b><br/>(1 = Not transparent)<br/>(0.5 = 50% opacity)<br/>(0 = Transparent)</p>
                    </div>
                </div>
                <div class="col-xs-9 section-content">
                    <div class="control-group string required">
                        <div class="controls">
                            <div class="col-xs-3">
                                <?php echo form_input(array('name' => 'overlay_background', 'id' => 'overlay_background', 'class' => 'minicolor form-control', 'value' => set_value('overlay_background', ($overlay_background) ? $overlay_background : '#000000'))); ?>
                                <div class="error"><?php echo form_error('overlay_background'); ?></div>
                            </div>
                            <div class="col-xs-1">
                                <label for="opacity">Opacity</label>
                            </div>
                            <div class="col-xs-3">
                                <?php echo form_input(array('name' => 'opacity', 'id' => 'opacity', 'class' => 'form-control', 'value' => set_value('opacity', isset($opacity) ? $opacity : '0.85' ))); ?>
                                <div class="error"><?php echo form_error('opacity'); ?></div>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
            <div class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="content_background">Content Background</label></h1>
                        <p>Set the color in the lightbox.<br/>(Only relevant if image is smaller than the lightbox)</p>
                    </div>
                </div>
                <div class="col-xs-9 section-content">
                    <div class="control-group string required">
                        <div class="controls">
                            <div class="col-xs-3">
                                <?php echo form_input(array('name' => 'content_background', 'id' => 'content_background', 'class' => 'minicolor form-control', 'value' => set_value('content_background', ($content_background) ? $content_background : ''))); ?>                
                                <div class="error"><?php echo form_error('content_background'); ?></div>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>            
        </div>
        <div class="tab-pane" id="slider-settings">
            <div id="slider-animation-dir" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_animation">Animation</label></h1>
                        <p>Select the animation you want to add to the slideshow.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_animation', array('fade' => 'Fade', 'slide' => 'Slide'), set_value('slider_animation', $slider_animation), 'id="slider_animation" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_animation'); ?></div>
                    </div>   
                </div>
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_direction">Direction</label></h1>
                        <p>Select the direction you want to add to the slideshow.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_direction', array('horizontal' => 'Horizontal', 'vertical' => 'Vertical'), set_value('slider_direction', $slider_direction), 'id="slider_direction" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_direction'); ?></div>
                    </div>   
                </div>
            </div>
            <div id="slider-reverse-loop" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_reverse">Reverse</label></h1>
                        <p>Reverse the animation direction.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_reverse', array(0 => 'No', 1 => 'Yes'), set_value('slider_reverse', $slider_reverse), 'id="slider_reverse" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_reverse'); ?></div>
                    </div>   
                </div>
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_animation_loop">Slider Animation Loop</label></h1>
                        <p>Do you want the animation to loop?.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_animation_loop', array(0 => 'No', 1 => 'Yes'), set_value('slider_animation_loop', $slider_animation_loop), 'id="slider_animation_loop" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_animation_loop'); ?></div>
                    </div>   
                </div>
            </div>
            <div id="slider-smooth-height-slideshow" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_smooth_height">Smooth Height</label></h1>
                        <p>Allow height of the slider to animate smoothly in horizontal mode when images are in different heights.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_smooth_height', array(0 => 'No', 1 => 'Yes'), set_value('slider_smooth_height', $slider_smooth_height), 'id="slider_smooth_height" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_smooth_height'); ?></div>
                    </div>   
                </div>
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_slideshow">Slide Automatically</label></h1>
                        <p>Animate slider automatically.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_slideshow', array(0 => 'No', 1 => 'Yes'), set_value('slider_slideshow', $slider_slideshow), 'id="slider_slideshow" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_slideshow'); ?></div>
                    </div>   
                </div>
            </div>
            <div id="slider-slideshow-speed" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_speed">Slideshow speed</label></h1>
                        <p>Set the speed of the slideshow cycling, in milliseconds.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_input(array('name' => 'slider_speed', 'id' => 'slider_speed', 'class' => 'form-control', 'value' => set_value('slider_speed', isset($slider_speed) ? $slider_speed : 7000))); ?>                </div>
                        <div class="error"><?php echo form_error('slider_speed'); ?></div>
                    </div>   
                </div>
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_anim_speed">Slideshow Animation Speed</label></h1>
                        <p>Set the speed of animations, in milliseconds.</p>
                    </div>
                </div>
                <div class="col-xs-3 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_input(array('name' => 'slider_anim_speed', 'id' => 'slider_anim_speed', 'class' => 'form-control', 'value' => set_value('slider_anim_speed', isset($slider_anim_speed) ? $slider_anim_speed : 7000))); ?>                </div>
                        <div class="error"><?php echo form_error('slider_anim_speed'); ?></div>
                    </div>   
                </div>
            </div>
            <div id="slider-control-nav" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_control_nav">Show Control Navigation (Dots at the bottom)</label></h1>                        
                    </div>
                </div>
                <div class="col-xs-9 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_control_nav', array(0 => 'No', 1 => 'Yes'), set_value('slider_control_nav', $slider_control_nav), 'id="slider_control_nav" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_control_nav'); ?></div>
                    </div>   
                </div>
            </div>
            <div id="slider-control-nav-options" <?php if ($slider_control_nav != 1) { ?>style="display:none;"<?php } ?>>
                <div class="row section">
                    <div class="col-xs-8">
                        <div id="dots_settings_containter">
                            <h2>Dots size</h2>
                            <div class="row">
                                <div class="description">
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_width">Width</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_width', 'id' => 'slider_ct_width', 'class' => 'integer form-control', 'value' => set_value('slider_ct_width', ($slider_ct_width) ? $slider_ct_width : '11'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_width'); ?></div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_height">Height</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_height', 'id' => 'slider_ct_height', 'class' => 'integer form-control', 'value' => set_value('slider_ct_height', ($slider_ct_height) ? $slider_ct_height : '11'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_height'); ?></div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_shad">Inset Shadow</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_dropdown('slider_ct_shad', array(0 => 'No', 1 => 'Yes'), set_value('slider_ct_shad', $slider_ct_shad), 'id="slider_ct_shad" class="form-control"'); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_shad'); ?></div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_num">Show Numbers</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_dropdown('slider_ct_num', array(0 => 'No', 1 => 'Yes'), set_value('slider_ct_num', $slider_ct_num), 'id="slider_ct_num" class="form-control"'); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_num'); ?></div>
                                        </div> 
                                    </div>                                
                                </div>
                            </div> 
                            <br/>
                            <h2>Dots Margin</h2>
                            <div class="row">
                                <div class="description">
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_mg_top">Margin top</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_mg_top', 'id' => 'slider_ct_mg_top', 'class' => 'integer form-control', 'value' => set_value('slider_ct_mg_top', ($slider_ct_mg_top) ? $slider_ct_mg_top : '0'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_mg_top'); ?></div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_mg_left">Margin left</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_mg_left', 'id' => 'slider_ct_mg_left', 'class' => 'integer form-control', 'value' => set_value('slider_ct_mg_left', ($slider_ct_mg_left) ? $slider_ct_mg_left : '0'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_mg_left'); ?></div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_mg_right">Margin right</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_mg_right', 'id' => 'slider_ct_mg_right', 'class' => 'integer form-control', 'value' => set_value('slider_ct_mg_right', ($slider_ct_mg_right) ? $slider_ct_mg_right : '0'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_mg_right'); ?></div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_mg_bottom">Margin bottom</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_mg_bottom', 'id' => 'slider_ct_mg_bottom', 'class' => 'integer form-control', 'value' => set_value('slider_ct_mg_bottom', ($slider_ct_mg_bottom) ? $slider_ct_mg_bottom : '0'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_mg_bottom'); ?></div>
                                        </div> 
                                    </div>
                                </div>
                            </div> 
                            <br/>
                            <h2>Dots background</h2>
                            <div class="row">
                                <div class="description">
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_bg">Default</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_bg', 'id' => 'slider_ct_bg', 'class' => 'form-control', 'value' => set_value('slider_ct_bg', ($slider_ct_bg) ? $slider_ct_bg : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_bg'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_hover_bg">Roll Over</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_hover_bg', 'id' => 'slider_ct_hover_bg', 'class' => 'form-control', 'value' => set_value('slider_ct_hover_bg', ($slider_ct_hover_bg) ? $slider_ct_hover_bg : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_hover_bg'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_active_bg">Active</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_active_bg', 'id' => 'slider_ct_active_bg', 'class' => 'form-control', 'value' => set_value('slider_ct_active_bg', ($slider_ct_active_bg) ? $slider_ct_active_bg : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_active_bg'); ?></div>
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                            <h2>Dots Border Settings</h2>
                            <div class="row">
                                <div class="description">
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_bd_st">Border Style</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_dropdown('slider_ct_bd_st', array('dashed' => 'Dashed', 'dotted' => 'Dotted', 'none' => 'None', 'solid' => 'Solid'), set_value('slider_ct_bd_st', $slider_ct_bd_st), 'id="slider_ct_bd_st" class="form-control"'); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_bd_st'); ?></div>
                                        </div> 
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_bd_wd">Border Width</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_bd_wd', 'id' => 'slider_ct_bd_wd', 'class' => 'integer form-control', 'value' => set_value('slider_ct_bd_wd', ($slider_ct_bd_wd) ? $slider_ct_bd_wd : '0'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_bd_wd'); ?></div>
                                        </div>                         
                                    </div>
                                    <div class="col-sm-3">
                                        <h4><label for="slider_ct_bd_rad">Border Radius</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls input-group"><?php echo form_input(array('name' => 'slider_ct_bd_rad', 'id' => 'slider_ct_bd_rad', 'class' => 'integer form-control', 'value' => set_value('slider_ct_bd_rad', ($slider_ct_bd_rad) ? $slider_ct_bd_rad : '0'))); ?> <span class="input-group-addon">px</span></div>
                                            <div class="error"><?php echo form_error('slider_ct_bd_rad'); ?></div>
                                        </div> 
                                    </div>                                
                                </div>
                            </div> 
                            <br/>
                            <h2>Dots Border Color</h2>
                            <div class="row">
                                <div class="description">
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_bd">Default</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_bd', 'id' => 'slider_ct_bd', 'class' => 'form-control', 'value' => set_value('slider_ct_bd', ($slider_ct_bd) ? $slider_ct_bd : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_bd'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_hover_bd">Roll Over</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_hover_bd', 'id' => 'slider_ct_hover_bd', 'class' => 'form-control', 'value' => set_value('slider_ct_hover_bd', ($slider_ct_hover_bd) ? $slider_ct_hover_bd : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_hover_bd'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_active_bd">Active</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_active_bd', 'id' => 'slider_ct_active_bd', 'class' => 'form-control', 'value' => set_value('slider_ct_active_bd', ($slider_ct_active_bd) ? $slider_ct_active_bd : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_active_bd'); ?></div>
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                            <h2>Dots Text Color</h2>
                            <div class="row">
                                <div class="description">
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_txt">Default</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_txt', 'id' => 'slider_ct_txt', 'class' => 'form-control', 'value' => set_value('slider_ct_txt', ($slider_ct_txt) ? $slider_ct_txt : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_txt'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_hover_txt">Roll Over</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_hover_txt', 'id' => 'slider_ct_hover_txt', 'class' => 'form-control', 'value' => set_value('slider_ct_hover_txt', ($slider_ct_hover_txt) ? $slider_ct_hover_txt : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_hover_txt'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <h4><label for="slider_ct_active_txt">Active</label></h4>
                                        <div class="control-group string required">
                                            <div class="controls"><?php echo form_input(array('name' => 'slider_ct_active_txt', 'id' => 'slider_ct_active_txt', 'class' => 'form-control', 'value' => set_value('slider_ct_active_txt', ($slider_ct_active_txt) ? $slider_ct_active_txt : ''))); ?></div>
                                            <div class="error"><?php echo form_error('slider_ct_active_txt'); ?></div>
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div id="dots_preview">
                            <h1>Style Preview:</h1>
                            <ol id="dots_example">
                                <li><a class="active">1</a></li>
                                <li><a>2</a></li>
                                <li><a>3</a></li>
                                <li><a>4</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div id="slider-direction-nav" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_dir_nav">Show Direction Navigation (Previous and Next buttons)</label></h1>                        
                    </div>
                </div>
                <div class="col-xs-9 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_dir_nav', array(0 => 'No', 1 => 'Yes'), set_value('slider_dir_nav', $slider_dir_nav), 'id="slider_dir_nav" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_div_nav'); ?></div>
                    </div>   
                </div>
            </div>
            <div id="slider-direction-nav-options" <?php if ($slider_dir_nav != 1) { ?>style="display:none;"<?php } ?>>
                <div class="row section">
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_dir_nav_prev">Previous Button Text</label></h1>                        
                        </div>
                    </div>
                    <div class="col-xs-3 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_input(array('name' => 'slider_dir_nav_prev', 'id' => 'slider_dir_nav_prev', 'class' => 'form-control', 'value' => set_value('slider_dir_nav_prev', isset($slider_dir_nav_prev) ? $slider_dir_nav_prev : 'Previous'))); ?>                </div>
                            <div class="error"><?php echo form_error('slider_div_nav_prev'); ?></div>
                        </div>   
                    </div>
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_dir_nav_next">Next Button Text</label></h1>                        
                        </div>
                    </div>
                    <div class="col-xs-3 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_input(array('name' => 'slider_dir_nav_next', 'id' => 'slider_dir_nav_next', 'class' => 'form-control', 'value' => set_value('slider_dir_nav_next', isset($slider_dir_nav_next) ? $slider_dir_nav_next : 'Next'))); ?>                </div>
                            <div class="error"><?php echo form_error('slider_div_nav_next'); ?></div>
                        </div>   
                    </div>
                </div>
                <div class="row section">
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_dir_nav_use_image">Use image instead of text?</label></h1>                        
                        </div>
                    </div>
                    <div class="col-xs-9 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_dropdown('slider_dir_nav_use_image', array(0 => 'No', 1 => 'Yes'), set_value('slider_dir_nav_use_image', $slider_dir_nav_use_image), 'id="slider_dir_nav" class="form-control"'); ?></div>
                            <div class="error"><?php echo form_error('slider_dir_nav_use_image'); ?></div>
                        </div>   
                    </div>                    
                </div>
				 <div class="row section">
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_mobile_nav_use_image">Display Arrows/text on Mobile</label></h1>                        
                        </div>
                    </div>
                    <div class="col-xs-9 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_dropdown('slider_mobile_nav_use_image', array(0 => 'No', 1 => 'Yes'), set_value('slider_mobile_nav_use_image', $slider_mobile_nav_use_image), 'id="slider_dir_nav_mobile" class="form-control"'); ?></div>
                            <div class="error"><?php echo form_error('slider_mobile_nav_use_image'); ?></div>
                        </div>   
                    </div>                    
                </div>
                <div class="row section">
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_dir_nav_image">Upload image</label></h1>
                            <p>The image must have 4 arrows. (Top 2 default state, bottom 2 hover state)</p>
                            <p>Example of arrows image:</p>
                            <p><img src="<?php echo site_url('uploads/modlbx-arrows.png');?>" width="50"/></p>                                
                        </div>
                    </div>
                    <div class="col-xs-9 section-content">
                        <div class="fileinput <?php if ($slider_dir_nav_image) { ?>fileinput-exists<?php } else { ?>fileinput-new<?php } ?>" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 140px;">
                                <?php
                                if ($slider_dir_nav_image) {
                                    echo '<img src="' . $slider_dir_nav_image . '" alt="Feature image" style="max-width: 200px; max-height: 140px;"/>';
                                    echo form_hidden('old_image', $slider_dir_nav_image);
                                } else {
                                    echo form_hidden('old_image', '');
                                }
                                ?>
                            </div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="slider_dir_nav_image" id="slider_dir_nav_image"></span>
                                <button onclick="deleteImage('old_image');" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</button>
                            </div>
                        </div> 
                        <input type="hidden" name="slider_dir_nav_image_width" value="<?php echo isset($slider_dir_nav_image_width) ? $slider_dir_nav_image_width : ""; ?>"/>
                        <input type="hidden" name="slider_dir_nav_image_height" value="<?php echo isset($slider_dir_nav_image_height) ? $slider_dir_nav_image_height : ""; ?>"/>
                    </div>
                </div>
            </div>
            <div id="slider-carousel" class="row section">
                <div class="col-xs-3">
                    <div class="description">
                        <h1><label for="slider_carousel">Slideshow in Carousel Mode</label></h1>                        
                    </div>
                </div>
                <div class="col-xs-9 section-content">
                    <div class="control-group string required">
                        <div class="controls"><?php echo form_dropdown('slider_carousel', array(0 => 'No', 1 => 'Yes'), set_value('slider_carousel', $slider_carousel), 'id="slider_carousel" class="form-control"'); ?></div>
                        <div class="error"><?php echo form_error('slider_carousel'); ?></div>
                    </div>   
                </div>
            </div>
            <div id="slider-carousel-options" <?php if ($slider_carousel != 1) { ?>style="display:none;"<?php } ?>>
                <div class="row section">
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_car_width">Item Width</label></h1>
                            <p>Box-model width of individual carousel items, including horizontal borders and padding.</p>
                        </div>
                    </div>
                    <div class="col-xs-3 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_input(array('name' => 'slider_car_width', 'id' => 'slider_car_width', 'class' => 'form-control', 'value' => set_value('slider_car_width', isset($slider_car_width) ? $slider_car_width : 0))); ?>                </div>
                            <div class="error"><?php echo form_error('slider_car_width'); ?></div>
                        </div>   
                    </div>
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_car_margin">Carousel Item Margin</label></h1>
                            <p>Margin between carousel items.</p>
                        </div>
                    </div>
                    <div class="col-xs-3 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_input(array('name' => 'slider_car_margin', 'id' => 'slider_car_margin', 'class' => 'form-control', 'value' => set_value('slider_car_margin', isset($slider_car_margin) ? $slider_car_margin : 0))); ?>                </div>
                            <div class="error"><?php echo form_error('slider_car_margin'); ?></div>
                        </div>   
                    </div>
                </div>
                <div class="row section">
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_car_min">Min Items</label></h1>
                            <p>Minimum number of carousel items that should be visible. Items will resize fluidly when below this.</p>
                        </div>
                    </div>
                    <div class="col-xs-3 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_input(array('name' => 'slider_car_min', 'id' => 'slider_car_min', 'class' => 'form-control', 'value' => set_value('slider_car_min', isset($slider_car_min) ? $slider_car_min : 0))); ?>                </div>
                            <div class="error"><?php echo form_error('slider_car_min'); ?></div>
                        </div>   
                    </div>
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_car_max">Max Items</label></h1>
                            <p>Maxmimum number of carousel items that should be visible. Items will resize fluidly when above this limit.</p>
                        </div>
                    </div>
                    <div class="col-xs-3 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_input(array('name' => 'slider_car_max', 'id' => 'slider_car_max', 'class' => 'form-control', 'value' => set_value('slider_car_max', isset($slider_car_max) ? $slider_car_max : 0))); ?>                </div>
                            <div class="error"><?php echo form_error('slider_car_max'); ?></div>
                        </div>   
                    </div>
                </div>
                <div class="row section">
                    <div class="col-xs-3">
                        <div class="description">
                            <h1><label for="slider_car_move">Items to Move/Slide</label></h1>
                            <p>Number of carousel items that should move on animation. If 0, slider will move all visible items.</p>
                        </div>
                    </div>
                    <div class="col-xs-3 section-content">
                        <div class="control-group string required">
                            <div class="controls"><?php echo form_input(array('name' => 'slider_car_move', 'id' => 'slider_car_move', 'class' => 'form-control', 'value' => set_value('slider_car_move', isset($slider_car_move) ? $slider_car_move : 0))); ?>                </div>
                            <div class="error"><?php echo form_error('slider_car_move'); ?></div>
                        </div>   
                    </div>                    
                </div>
            </div>          
        </div>
        <div class="tab-pane" id="editor">
            <h2>Text editor</h2>
            <ul class="nav nav-tabs" id="editor-menu">
                <li class="active"><a href="#list" data-toggle="tab">page.lookbook-modmedia-list.liquid</a></li>
                <li><a href="#lookbook" data-toggle="tab">page.lookbook-modmedia-single.liquid</a></li>
            </ul>            
            <div class="tab-content">
                <div class="tab-pane active" id="list">
                    <textarea name='code' id='code' class="text-editor" style="height: 100%;">
                    </textarea>
                </div>
                <div class="tab-pane" id="lookbook">
                    <textarea name='code-single' id='code-single' class="text-editor" style="height: 100%;">
                    </textarea>
                </div>
            </div>            
        </div>
        <?php echo form_submit('save', 'Save', 'class="btn btn-success"'); ?>
        <?php echo form_close();
        ?>
    </div>
    <script>
        var editors = [];
        $(function() {
            var loaded = false;
            $('#theme-menu a, #editor-menu a').click(function(e) {
                $(this).tab('show');
                if (!loaded) {
                    $.ajax({
                        url: '<?php echo site_url('themes/get_code/' . $this->session->userdata('shop')); ?>',
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 'success') {
                                editors['code'].setValue(data.content_list);
                                editors['code'].refresh();
                                editors['code-single'].setValue(data.content_single);
                                editors['code-single'].refresh();
                                loaded = true;
                            }
                        }
                    });
                } else {
                    e.preventDefault()
                    editors['code'].refresh();
                    editors['code-single'].refresh();
                }
            });
            $(".text-editor").each(function() {
                var editor = CodeMirror.fromTextArea(document.getElementById($(this).attr('id')), {
                    lineNumbers: true,
                    mode: 'text/html'
                });
                editor.on('change', function(cm, change) {
                    cm.save();
                    //$('input[name=html_hidden]').val(cm.getValue());
                });
                editors[$(this).attr('id')] = editor;
            });
            $('.minicolor').minicolors({theme: 'bootstrap', opacity: true});
            $('#slider_ct_bg, #slider_ct_bd, #slider_ct_txt,#slider_ct_hover_bg, #slider_ct_hover_bd, #slider_ct_hover_txt, #slider_ct_active_bg, #slider_ct_active_bd, #slider_ct_active_txt').minicolors({
                theme: 'bootstrap',
                change: function(hex, opacity) {
                    update_dots();
                }
            });
            $('#dots_example li a').mouseover(function() {
                if ($('#slider_ct_hover_bg').val() != '') {
                    $(this).css('background', $('#slider_ct_hover_bg').val());
                } else {
                    $(this).css('background', 'none');
                }
                if ($('#slider_ct_hover_bd').val() != '') {
                    $(this).css('border-color', $('#slider_ct_hover_bd').val());
                } else {
                    $(this).css('border-color', 'none');
                }
                if ($('#slider_ct_hover_txt').val() != '') {
                    $(this).css('color', $('#slider_ct_hover_txt').val());
                } else {
                    $(this).css('color', 'none');
                }
            }).mouseout(function() {
                update_dots();
            });
            $('#dots_example li a').click(function() {
                $('#dots_example li a').removeClass('active');
                $(this).addClass('active');
                update_dots();
            });
            $('#slider_ct_bd_rad, #slider_ct_num, #slider_ct_shad, #slider_ct_bd_st, #slider_ct_bd_wd, #slider_ct_bd_rad, #slider_ct_bg, #slider_ct_bd, #slider_ct_txt, #slider_ct_hover_bg, #slider_ct_hover_bd, #slider_ct_hover_txt, #slider_ct_active_bg, #slider_ct_active_bd, #slider_ct_active_txt, #slider_ct_width, #slider_ct_height, #slider_ct_mg_top, #slider_ct_mg_left, #slider_ct_mg_right, #slider_ct_mg_bottom').change(function() {
                update_dots();
            });
            $('#slider_ct_num').change();
            $('#slider_control_nav').change(function() {
                if ($(this).val() == 1) {
                    $('#slider-control-nav-options').slideDown();
                } else {
                    $('#slider-control-nav-options').slideUp();
                }
            });
            $('#slider_dir_nav').change(function() {
                if ($(this).val() == 1) {
                    $('#slider-direction-nav-options').slideDown();
                } else {
                    $('#slider-direction-nav-options').slideUp();
                }
            });
            $('#slider_carousel').change(function() {
                if ($(this).val() == 1) {
                    $('#slider-carousel-options').slideDown();
                } else {
                    $('#slider-carousel-options').slideUp();
                }
            });
            function update_dots() {
                if ($('#slider_ct_bg').val() != '') {
                    $('#dots_example li a').css('background', $('#slider_ct_bg').val());
                } else {
                    $('#dots_example li a').css('background', 'none');
                }
                if ($('#slider_ct_bd').val() != '') {
                    $('#dots_example li a').css('border-color', $('#slider_ct_bd').val());
                } else {
                    $('#dots_example li a').css('border-color', 'initial');
                }
                if ($('#slider_ct_txt').val() != '') {
                    $('#dots_example li a').css('color', $('#slider_ct_txt').val());
                } else {
                    $('#dots_example li a').css('color', 'initial');
                }
                if ($('#slider_ct_active_bg').val() != '') {
                    $('#dots_example li a.active').css('background', $('#slider_ct_active_bg').val());
                } else {
                    $('#dots_example li a.active').css('background', 'none');
                }
                if ($('#slider_ct_active_bd').val() != '') {
                    $('#dots_example li a.active').css('border-color', $('#slider_ct_active_bd').val());
                } else {
                    $('#dots_example li a.active').css('border-color', 'initial');
                }
                if ($('#slider_ct_active_txt').val() != '') {
                    $('#dots_example li a.active').css('color', $('#slider_ct_active_txt').val());
                } else {
                    $('#dots_example li a.active').css('color', 'initial');
                }
                if ($('#slider_ct_num').val() == 1) {
                    $('#dots_example li a').css('text-indent', '0px');
                } else {
                    $('#dots_example li a').css('text-indent', '-9999px');
                }
                if ($('#slider_ct_shad').val() == 1) {
                    $('#dots_example li a').css('box-shadow', 'inset 1px 1px 3px rgba(0,0,0,0.8)');
                } else {
                    $('#dots_example li a').css('box-shadow', 'none');
                }
                $('#dots_example li').css('margin-top', $('#slider_ct_mg_top').val() + 'px');
                $('#dots_example li').css('margin-left', $('#slider_ct_mg_left').val() + 'px');
                $('#dots_example li').css('margin-right', $('#slider_ct_mg_right').val() + 'px');
                $('#dots_example li').css('margin-bottom', $('#slider_ct_mg_bottom').val() + 'px');
                $('#dots_example li a').css('width', $('#slider_ct_width').val() + 'px');
                $('#dots_example li a').css('height', $('#slider_ct_height').val() + 'px');
                $('#dots_example li a').css('border-radius', $('#slider_ct_bd_rad').val() + 'px');
                $('#dots_example li a').css('border-width', $('#slider_ct_bd_wd').val() + 'px');
                $('#dots_example li a').css('border-style', $('#slider_ct_bd_st').val());
            }
            //$('#myTab a.active').tab('show');
        });

        //var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('code'));
    </script>
</div>