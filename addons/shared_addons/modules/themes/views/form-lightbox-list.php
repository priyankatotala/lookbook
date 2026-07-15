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
        <!--<li><a href="#editor" data-toggle="tab">Editor</a></li>-->
    </ul>
    <?php
    echo form_open('themes/save');
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
                                <?php echo form_input(array('name' => 'opacity', 'id' => 'opacity', 'class' => 'form-control', 'value' => @set_value('opacity', ($opacity) ? $opacity : '0.85' ))); ?>
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
        });

        //var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('code'));
    </script>
</div>