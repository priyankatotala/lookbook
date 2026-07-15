<?php
echo form_open_multipart('lookbook/general_settings');
echo form_hidden('s', (isset($security)) ? $security : '');
?>
<header>
    <h1>General Settings</h1>
    <div class="actions">
        <?php echo form_submit('submit', 'Save', 'class="btn btn-default disabled"'); ?>
    </div>
</header>
<div class="row section">
    <div class="col-xs-3">
        <div class="description">
            <h1><label for="page_template_selected">Default Page Template</label></h1>
        </div>
    </div>
    <div class="col-xs-9 section-content">
        <div class="control-group string required">
            <div class="row controls">
                <div class="col-sm-4">
<?php echo form_dropdown('page_template_selected', $templates, set_value('page_template_selected', $page_template_selected), 'id="page_template_selected" class="form-control"'); ?>
                </div>
                <div class='col-sm-1'>
                    <button type='button' onclick='updateTemplates()' class='btn btn-sm'><span class='glyphicon glyphicon-refresh'></span></button>
                </div>
            </div>            
        </div>   
    </div>
</div>
<div class="row section" style="display:none;"><!-- Disabled -- Not In Use - 27/11/2018 Ivan ModMedia -->
    <div class="col-xs-3">
        <div class="description">
            <h1><label for="homepage_snippet">Homepage Snippet</label></h1>
        </div>
    </div>
    <div class="col-xs-9 section-content">
        <div class="control-group string required">
            <div class="row controls">
                <div class='col-sm-12'>
                    <?php echo form_radio(array('name'=>'homepage_snippet_display','id'=>'homepage_snippet_display_list','value'=>'list', 'checked'=>($homepage_snippet_display=='list')?"checked":false));?>
                    <?php echo form_label('Display Lookbooks List','homepage_snippet_display_list');?>
                </div>
            </div>            
            <div class="row controls">
                <div class='col-sm-8'>
                    <?php echo form_radio(array('name'=>'homepage_snippet_display','id'=>'homepage_snippet_display_single','value'=>'single', 'checked'=>($homepage_snippet_display=='single')?"checked":false));?>
                    <?php echo form_label('Display Lookbook:','homepage_snippet_display_single');?>
                </div>
                <div class="col-sm-4">
<?php echo form_dropdown('homepage_snippet_lookbook_single', $lookbooks, set_value('homepage_snippet_lookbook_single', $homepage_snippet_lookbook_single), 'id="homepage_snippet_lookbook_single" class="form-control"'); ?>
                </div>                
            </div>            
        </div>   
    </div>
</div>
<?php
echo form_close();
?>
<script type="text/javascript">
                    $(function() {
                        $('input, select').change(function(){
                            form_changed();
                        });
                        $('form').submit(function(){
                            $('input[name=submit]').addClass('disabled');
                            $('input[name=submit]').parent().append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
                        });
                    });
                    function form_changed(){
                        $('input[name=submit]').removeClass('btn-default disabled');
                        $('input[name=submit]').addClass('btn-success');
                    }
                    function updateTemplates() {
                        $('input[name=submit]').parent().append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
                        $.ajax({
                            url: BASE_URL + 'lookbook/updateTemplates',
                            dataType: 'json',
                            async: true,
                            success: function(data) {
                                $('#page_template').html('');
                                if(data.status=='error'){
                                    
                                }else{
                                    $.each(data.templates, function(key, value) {
                                        $('#page_template').append('<option value="' + key + '">' + value + '</option>');
                                    });                                    
                                }
                                $('ul.loader').remove();
                            },
                            error: function(x, e) {
                                show_error(x, e);
                                $('ul.loader').remove();
                            }
                        })
                    }
</script>