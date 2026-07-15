<?php
echo form_open_multipart('lookbook/save_page_look', 'name="save_page"');
echo form_hidden('id', (isset($id)) ? $id : '');
echo form_hidden('lookbook', (isset($lookbook->id)) ? $lookbook->id : '');
echo form_hidden('ls', (isset($ls)) ? $ls : '');
echo form_hidden('s', (isset($security)) ? $security : '');
?>
<header>
    <h1><?php
        if (isset($lookbook)) {
            echo anchor('lookbook/edit/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key')), $lookbook->title) . ' / ';
        }
        ?>New Page Look</h1>
    <div class="actions">
        <?php echo form_submit('submit', 'Save', 'class="btn btn-default disabled"'); ?>
    </div>
</header>
<?php
if (isset($image)) {
    $image = json_decode($image);
}
if (isset($default_icon_url)) {
    $default_icon_id=$default_icon;
    $default_icon_width=(isset($default_icon_width)?$default_icon_width:'');
    $default_icon_height=(isset($default_icon_height)?$default_icon_height:'');
}else{
    if (isset($icons[0])) { 
        $default_icon_id=$icons[0]->id;
        $default_icon_width=$icons[0]->width;
        $default_icon_height=$icons[0]->height;
    } else {
        $default_icon_id='';
        $default_icon_width='';
        $default_icon_height='';
    }
}
?>
<div id="upload-image">
    <h3>Please select your image for this page. Maximum file size allowed: 2MB<br/>
         <p align="center" style="line-height:1.5;font-size:14px;margin-top:10px;">
            For best results upload images that are the <u>same width</u> as your theme's page container width.
            <BR>
            We recommend sizes within 1000px width & 1000px height
            <BR><BR>
            Please avoid special characters such as <b>!@#$%^&*'"</b> in image file names, if experiencing troubles
            <BR>
            
         </p>
    </h3>
   
    <div class="fileinput <?php echo (isset($image)) ? 'fileinput-exists' : 'fileinput-new'; ?>" data-provides="fileinput">
        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
        <div><span class="btn btn-default btn-sm btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change Image</span><input type="file" name="main_picture"/></span>
        <span class="fileinput-filename"></span>
        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
        </div>
    </div>
</div>
<?php 
echo form_hidden('default_icon', $default_icon_id);
echo form_close(); ?>
<script>
     $(function(){
        $('input[name=main_picture]').change(function(){
           $('input[type=submit]').removeClass('disabled btn-default');
           $('input[type=submit]').addClass('btn-success');
        });
        $('form[name=save_page]').submit(function(){
            $('input[type=submit]').addClass('disabled');
            $('input[name=submit]').parent().append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
        })
     });             
</script>