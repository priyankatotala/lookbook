<?php
echo form_open_multipart('lookbook/save');
echo form_hidden('id', (isset($id)) ? $id : '');
echo form_hidden('s', (isset($security)) ? $security : '');
?>
<header>
    <h1><?php
        echo anchor('lookbook', 'Lookbooks') . ' / ';
        echo ($id) ? 'Edit' : 'New';
        ?> LookBook</h1>
    <div class="actions">
		<?php if($id){ $links="http://".$this->session->userdata('shop').'/pages/'.$shop_data->lookbooks_page_slug;?>
        <a href="<?php echo $links;?>" target="_blank" class="btn btn-success preview"><i class='icon-eye-open icon-white'></i> View All Lookbooks</a><?php } ?>
        <?php if($id){ if($theme_data['theme_lightbox_type']){ $link="http://".$this->session->userdata('shop').'/pages/'.$shop_data->lookbooks_page_slug;}else{ $link="http://".$this->session->userdata('shop').'/pages/'.$shopify_page_handle;}?>
        <a href="<?php echo $link;?>" target="_blank" class="btn btn-success preview"><i class='icon-eye-open icon-white'></i> View This Lookbook</a><?php } ?>
        <?php echo form_submit('submit', 'SAVE', 'class="btn btn-default disabled"'); ?>
    </div>
</header>
<!-- <?php echo $lookbooks_page_id;?> -->
<div id="title_row" class="row section">
    <div class="col-xs-3">
        <div class="description">
            <h1><label for="title">Title</label></h1>
            <p>Give your lookbook a title.</p>
        </div>
    </div>
    <div class="col-xs-9 section-content">
        <div class="control-group string required">
            <div class="controls"><?php echo form_input(array('name' => 'title', 'id' => 'title', 'class' => 'form-control', 'value' => set_value('title', $title))); ?></div>            
        </div>   
    </div>
</div>
<div id="image_row" class="row section">
    <div class="col-xs-3">
        <div class="description">
            <h1><label for="feature_image">Feature Image</label></h1>
            <p>Maximum file size: 2MB</p>
            <p>Avoid special characters such as <b>_-!@#$%^&*'"</b> in image file names</p>
        </div>
    </div>
    <div class="col-xs-3 section-content">
        <div class="fileinput <?php if ($feature_image) { ?>fileinput-exists<?php } else { ?>fileinput-new<?php } ?>" data-provides="fileinput">
            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">

            </div>
            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                <?php
                if ($feature_image) {
                    $image = json_decode($feature_image);
                    echo '<img src="' . $image->full_path . '" alt="Feature image"/>';
                    echo form_hidden('old_image', $image->full_path);
                } else {
                    echo form_hidden('old_image', '');
                }
                ?>
            </div>
            <div>
                <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="feature_image" id="feature_image"></span>
                <button onclick="deleteImage('old_image');" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</button>
            </div>
        </div>        
    </div>
    <div class="col-xs-3">
        <div class="description">
            <h1><label for="theme">Theme</label></h1>
        </div>
    </div>
    <div class="col-xs-3 section-content">
        <div class="theme">
            <div id="theme-image"><?php echo (isset($theme_data['theme_image'])) ? '<img src="' . site_url('uploads/themes/' . $theme_data['theme_image']) . '" alt="' . $theme_data['theme_title'] . '"/>' : ''; ?></div>
            <h3 id="theme-title"><?php echo (isset($theme_data['theme_title'])) ? $theme_data['theme_title'] : ''; ?></h3>
            <input id="theme" type="hidden" name="theme" value="<?php echo $theme; ?>"/>
            <input id="old_theme" type="hidden" name="old_theme" value="<?php echo $theme; ?>"/>
            <button onclick="showThemes()" type="button" class="btn btn-default" id="select-theme"><?php
                if ($theme) {
                    echo 'Change Theme';
                } else {
                    echo 'Select a theme';
                }
                ?></button>
            <?php if($id){?>
            <a href="<?php echo site_url('themes/edit/' . $theme . '/' . $id.'/'.sha1($id.$this->config->item('encryption_key'))); ?>" class="btn btn-warning" id="edit-theme" style="<?php
               if (!$theme) {
                   echo 'display:none;';
               }
            ?>">Edit Theme</a><?php }?>
        </div>        
    </div>
</div>
<div id="active_row" class="row section">
    <div class="col-xs-3">
        <div class="description">
            <h1><label for="active">Active</label></h1>
        </div>
    </div>
    <div class="col-xs-9 section-content">
        <div class="control-group string required">
            <div class="row controls">
                <div class="col-sm-4">
<?php echo form_dropdown('active', array('1' => 'Yes', '0' => 'No'), set_value('active', $active), 'id="active" class="form-control"'); ?>
                </div>
            </div>            
        </div>   
    </div>
</div>
<?php
echo form_hidden('sort');
echo form_close();
?>
<div id="modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure?</h4>
            </div>
            <div class="modal-body">
                <p>If you want to delete this page please write the word DELETE in the field:</p>
                <input type="text" name="verify-delete" class="form-control"/>
                <input type="hidden" name="url" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="deleteLookBookPage();" class="btn btn-danger modal-delete">Delete</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                <?php if (isset($pages)) { ?>
                
    <div id="page_looks" class="row section">
		
        <div class="col-xs-12">
            <div class="row">
				<p> <strong>Note: To ensure your Lookbooks display as best they can, use images that are the <b>same size</b> for all of your pages.</strong></p>
				<p>For best results upload images that are the <b>same width</b> as your theme's page container width. &nbsp; <small><B>(Recommended Dimensions: 1000px x 1000px)</B></small></p><BR>
				
                <ul id="pages" class="list-inline">
                    <?php
                    if ($pages) {
                        $n = 1;
                        foreach ($pages as $page):
                            $n = $n + 1;
                            ?>
                            <li class="col-sm-2 sortable" data-page-id="<?php echo $page->id; ?>">
                                <div class="page_look">
                                        <?php
                                        $image = json_decode($page->image);
                                        echo '<img src="' . str_replace(FCPATH, BASE_URL, $image->full_path) . '" alt="Main image"/>';
                                        ?>
                                    <div class="actions" ><a href="<?php echo site_url('lookbook/edit_page/' . $page->id . '/' . sha1($page->id . $this->config->item('encryption_key'))); ?>" class="btn btn-small edit_page"><i class="glyphicon glyphicon-pencil"></i></a>
                            <?php echo anchor('#modal', '<i class="glyphicon glyphicon-trash"></i>', 'class="btn delete" data-toggle="modal" data-url="' . site_url("lookbook/delete_page/" . $page->lookbook . '/' . $page->id . '/' . sha1($page->id . $this->config->item('encryption_key'))) . '"'); ?></div>
                                </div>                
                            </li>
            <?php
        endforeach;
    }
    ?>
                    <li class="col-sm-2 no-sortable">
                        <div class="">
                            <a href="<?php echo site_url('lookbook/add_page/' . $id . '/' . $security); ?>" class="btn btn-success add_new_page">Add new page look +</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>    
    </div>
<?php } ?>
<div id="themes" class="white-popup mfp-with-anim mfp-hide">
    <div class="search">
        <div id="themes-results"></div>
    </div>
</div>
<script type="text/javascript">
                    $(function() {
                        $("#pages").sortable({
                            cancel: "li.no-sortable",
                            placeholder: "col-sm-2 sort-placeholder",
                            start: function(event, ui) {
                                ui.placeholder.html('<div class="placeholder-text" style="padding-top:' + ((ui.item.height() - 14) / 2) + 'px">Drop image to reorder</div>');
                                console.log(ui.item.height());
                                ui.placeholder.outerHeight(ui.item.height() + 'px');
                                $("#pages").addClass('is-sorting');
                            },
                            stop: function(event, ui) {
                                $("#pages").removeClass('is-sorting');
                                var order = Array();
                                $('#pages .sortable').each(function() {
									// Commented this below line for the fix
                                    //order.push($(this).attr('data-page-id'));
									// Priyanka's fix for sorting problem for large number of lookbook pages
									if(order.indexOf($(this).attr('data-page-id')) === -1) {
                                    order.push($(this).attr('data-page-id'));
                                    }
									// Priyanka's fix for sorting problem for large number of lookbook pages
                                });
								console.log(order);
                                if (order.length > 0) {
                                    $('input[name=sort]').val(order.join(','));
                                }
                                form_changed();
                            }
                        });
                        $('a.add_new_page, a.edit_page').click(function(){
                            $('input[name=submit]').parent().append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
                        });
                        $('input, select').change(function(){
                            form_changed();
                        });
                        resizeHeight();
                        $('#pages img').load(function() {
                            resizeHeight();
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
                    function resizeHeight() {
                        var maxHeight = 0;
                        $('#page_looks li .page_look').each(function() {
                            if ($(this).outerHeight() > maxHeight) {
                                maxHeight = $(this).outerHeight()
                            }
                        });
                        $('#page_looks li .page_look').each(function() {
                            $(this).css({'min-height': maxHeight});
                        });
                    }
                    function showThemes() {
                        $.magnificPopup.open({
                            items: {
                                src: '#themes',
                                type: 'inline'
                            },
                            removalDelay: 500,
                            mainClass: 'mfp-3d-unfold',
                            midClick: true,
                            callbacks: {
                                beforeOpen: function() {
                                    this.st.mainClass = 'mfp-3d-unfold';
                                }
                            }
                        });
                        if ($('#themes-results').text() == "") {
                            var url = '<?php echo site_url('themes/index'); ?>';
                            $.ajax({
                                url: url,
                                data:{
                                    lookbook:'<?php echo $id;?>'
                                },
                                dataType: "html",
                                type: "POST",
                                success: function(data) {
                                    $('#themes-results').html(data);
                                },
                                error: function(jqXHR, status, errorThrown) {
                                    show_error(jqXHR, status, errorThrown);
                                }
                            });
                        }
                        return false;
                    }
                    function selectTheme(id, title, image, url) {
                        $('#theme').val(id);
                        $('#theme-image').html('<img src="' + image + '" alt="' + title + '"/>');
                        $('#theme-title').html(title);
                        $('#theme-image').show();
                        $('#theme-title').show();
                        $('#select-theme').text('Change Theme');
                        $('#edit-theme').attr('href',url);
                        form_changed();
                        $.magnificPopup.close();
                    }

</script>