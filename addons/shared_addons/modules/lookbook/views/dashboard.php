<div class="alert alert-info">
    <div style="text-align: center"><b>IMPORTANT NOTE:</b> If you are unable to see your Lookbooks when previewing, please contact us as you may be using another app that is modifying Shopify's natural page coding. We can easily fix this for you.</div>
</div>

<header>
    <h1>Lookbooks</h1>
    <div class="actions">
        <?php echo anchor('#', 'Save Changes', 'id="update_sort" class="btn btn-warning"  data-loading-text="Saving changes..." onclick="return false;" style="display:none;"'); ?> <?php echo anchor('lookbook/general_settings', 'General Settings', 'class="btn btn-default"'); ?> <a href="http://<?php echo $this->session->userdata('shop') . '/pages/' . $shop->lookbooks_page_slug; ?>" target="_blank" class="btn btn-success preview"><i class='icon-eye-open icon-white'></i> View on your store</a>
        <?php echo anchor('lookbook/add', '<i class="icon-plus icon-white"></i> New Lookbook', 'class="btn btn-success new"'); ?>        
    </div>
</header>
<div id="dashboard">
    <?php
    if ($lookbooks) {
        ?>
        <div class="row">
            <div class="col-xs-12">
                <table id='lookbooks-list' class="table table-hover">
                    <thead>
                        <tr>
                            <th style='width:40px;'>Sort</th>
                            <th>Title</th>
                            <th>Pages</th>
                            <th>Theme</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id='lookbooks'>
                        <?php
                        $old_sort=array();
                        foreach ($lookbooks as $lookbook):
                            $old_sort[]=$lookbook->id;
                            $image = '';
                            if ($lookbook->feature_image) {
                                $image = json_decode($lookbook->feature_image);
                            }
                            ?>
                            <tr class='sortable' data-lookbook-id='<?php echo $lookbook->id;?>'>
                                <td style='width:40px;'>{{ theme:image file="sorting.png" alt="Sorting" }}</td>
                                <td><?php if (isset($image->full_path)) {
                                echo '<img src="' . $image->full_path . '" style="max-width:180px; max-height:100px;" alt="+"/>';
                            } ?> <?php echo anchor('lookbook/edit/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key')), $lookbook->title); ?></td>
                                <td><?php echo $lookbook->total_pages; ?></td>
                                <td><img src="uploads/themes/<?php echo $lookbook->theme_image; ?>" style="max-width:100px; max-height:100px;" alt=""/> <?php echo $lookbook->theme_title; ?></td>
                                <td><div class="control-group string required">
                                        <div class="actions">
                                            <!--<div class="make-switch" data-on="success" data-off="default">
                                                <input type="checkbox" name="status-<?php echo $lookbook->id; ?>" data-url="<?php echo 'lookbook/change_status/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key')); ?>" <?php if ($lookbook->active == 1) { ?>checked<?php } ?>>
                                            </div>-->
        <?php echo anchor('lookbook/edit/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key')), '<i class="icon-pencil icon-white"></i> Edit', 'class="btn btn-warning edit"'); ?>                                
        <?php echo anchor('#modal', '<i class="icon-remove icon-white"></i> Delete', 'class="btn btn-danger delete" data-toggle="modal" data-url="lookbook/delete/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key')) . '"'); ?>
                                        </div>
                                    </div>   
                                </td>
                            </tr>
        <?php
    endforeach;
    ?>
                    </tbody>
                </table>
            </div>
    <?php
} else {
    ?><div class="row">
                <div class="col-xs-12 no-results">
                    <i class='ico icon-lookbook'></i>
                    <h2>You haven't created any lookbooks yet!</h2>
                    <a href='<?php echo site_url('lookbook/add '); ?>' title='Create one now'>Create one now!</a>                
                </div>
            </div>
    <?php
}
?>

    </div>
</div>
<div id="modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure?</h4>
            </div>
            <div class="modal-body">
                <p>If you want to delete this lookbook please write the word DELETE in the field:</p>
                <input type="text" name="verify-delete" class="form-control"/>
                <input type="hidden" name="url" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="deleteLookBook();" class="btn btn-danger modal-delete">Delete</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type='hidden' name='sort'/>
<input type='hidden' name='old_sort' value='<?php echo join(',',$old_sort);?>'/>
<script>
    var ajax='';
    $(function() {
        $("#lookbooks").sortable({
            cancel: ".no-sortable",
            placeholder: "sort-placeholder",
            start: function(event, ui) {
                ui.helper.find('td').css({background: '#FFF'});
                ui.placeholder.html('<td colspan="8"><div class="placeholder-text" style="padding-top:' + ((ui.item.height() - 14) / 2) + 'px">Drop lookbook to reorder</div></td>');
                ui.placeholder.outerHeight(ui.item.height() + 'px');
                $("#lookbooks").addClass('is-sorting');
            },
            stop: function(event, ui) {
                $("#lookbooks").removeClass('is-sorting');
                var order = Array();
                $('#lookbooks .sortable').each(function() {
					// Commented this below line for the fix
                   // order.push($(this).attr('data-lookbook-id'));
					// Priyanka's fix for sorting problem for large number of lookbook 
					if(order.indexOf($(this).attr('data-lookbook-id')) === -1) {
                	order.push($(this).attr('data-lookbook-id'));
            		}
					// Priyanka's fix for sorting problem for large number of lookbook pages
                });
				console.log(order);
                if (order.length > 0) {
                    $('input[name=sort]').val(order.join(','));
                }
                if($('input[name=sort]').val()!=$('input[name=old_sort]').val()){
                    $('#update_sort').show();
                }else{
                    $('#update_sort').hide();
                }
            }, helper: function(e, tr)
            {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function(index)
                {
                    // Set helper cell sizes to match the original sizes
                    $(this).width($originals.eq(index).width());
                });
                return $helper;
            }
        });     
        $('#update_sort').click(function(){
            $(this).button('loading');
            ajax=$.ajax({
                url: '<?php echo site_url('lookbook/set_order/'.sha1($this->config->item('encryption_key').'set0rder'));?>',
                 dataType: "json",
                 type: "POST",
                 data: {
                  order:$('input[name=sort]').val()
                 },
                 success: function(data) {
                    if(data.status=='success'){
                         $('input[name=old_sort]').val($('input[name=sort]').val());
                         $('#update_sort').button('reset');
                         $('#update_sort').hide();
                     }
					 
                     show_message(data.status,data.msg);                     
                 },
                 error: function(jqXHR, status, errorThrown) {
                     show_error(jqXHR, status, errorThrown);
                 } 
             });
			
             return false;
        });
    });
</script>