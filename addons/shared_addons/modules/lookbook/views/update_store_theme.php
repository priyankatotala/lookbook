<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$additional="";
$item=1;
?>
<div class="progress progress-striped">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
    0%
  </div>
</div>
<button id="start-update" onclick="updateItems();" class="btn btn-info" data-loading-text="Updating...">Start Update Now!</button>
<br/>
<br/>
<h2>Items to Update:</h2>
<p><br/>
<h3>Templates</h3>
<br/>
<table class="table">
    <tr>
        <th>Name</th>
        <th>Status</th>
    </tr>
    <tr>
        <td>page.lookbooks-modmedia-list.liquid</td>
        <td id="item-0"><span class="label label-warning">Pending</span></td>
    </tr>
    <tr>
        <td>page.lookbooks-modmedia-single.liquid</td>
        <td id="item-1"><span class="label label-warning">Pending</span></td>
    </tr>
</table>
<?php if($lookbooks){?>
<h3>Lookbooks Featured Images</h3>
<br/>
<table class="table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($lookbooks as $lookbook):
            $image=json_decode($lookbook->feature_image);
            if(isset($image->full_path)){
                $additional=$additional.",{ 'name':'".$image->file_name."','src':'".$image->full_path."','type':'lookbook','id':'".$lookbook->id."'}";
                $item=$item+1;
            }
            ?>
        <tr>
            <td><?php if(isset($image->full_path)){?><img src="<?php echo $image->full_path;?>" width="120" alt=""/><?php }?></td>
            <td id="<?php if(isset($image->full_path)){ echo 'item-'.$item;}?>"><span class="label label-warning">Pending</span></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php }?>
<?php if($lookbooks_pages){?>
<h3>Lookbooks Pages Images</h3>
<br/>
<table class="table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($lookbooks_pages as $page):
            $image=json_decode($page->image);
            if(isset($image->full_path)){
                $additional=$additional.",{ 'name':'".$image->file_name."','src':'".$image->full_path."','type':'lookbook_page','id':'".$page->id."'}";
                $item=$item+1;
            }
            ?>
        <tr>
            <td><?php if(isset($image->full_path)){?><img src="<?php echo $image->full_path;?>" width="120" alt=""/><?php }?></td>
            <td id="<?php if(isset($image->full_path)){ echo 'item-'.$item;}?>"><span class="label label-warning">Pending</span></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php }?>
</p>
<script>
    var toupdate=[{
      'name':'page.lookbooks-modmedia-list.liquid',
      'src':''
    },{
      'name':'page.lookbooks-modmedia-single.liquid',
      'src':''
    }<?php echo $additional;?>];
    function updateItems(){
        $('#start-update').button('loading');
        $('.progress').addClass('active');
        $('.progress-bar').attr('aria-valuenow',0);
        $('.progress-bar').css({'width':'0%'});
        $('.progress-bar').text('0%');
        var item=0;
        updateItem(item);        
    }
    function updateItem(item){
        console.log(item)
        $.ajax({
                type: "POST",
                url: "<?php echo site_url('lookbook/update_store_item');?>",
                dataType: "json",
                data: toupdate[item],
                async:true,
                success:function(data){
                    if(data.status=='success'){
                        $('.progress-bar').attr('aria-valuenow',(((item+1)/toupdate.length)*100));
                        $('.progress-bar').css({'width':(((item+1)/toupdate.length)*100)+'%'});
                        $('.progress-bar').text((((item+1)/toupdate.length)*100)+'%');
                        $('#item-'+item).html('<span class="label label-success">Updated</span>');
                    }else{
                        $('#item-'+item).html('<span class="label label-danger">Error</span><br/>'+data.msg);
                    }
                    item=item+1;
                    if(item < toupdate.length){
                        updateItem(item);
                    }else{
                        $.ajax({
                            url:'<?php echo site_url('lookbook/set_actual_theme/'.sha1('update_' . $this->config->item('encryption_key') . '_theme'));?>',
                            type:'GET',
                            dataType: 'json',
                            success:function(data){
                                if(data.status=='success'){
                                    $('#content').prepend('<div class="panel panel-success">'+data.msg+'</div>');
                                }else{
                                    $('#content').prepend('<div class="panel panel-danger">'+data.msg+'</div>');
                                }
                                $('#start-update').button('reset');
                                $('#start-update').remove();
                                $('.progress').removeClass('active');
                            },error:function(x,e){
                                //console.log(x);
                                //console.log(e);
                            }
                        });
                    }
                },
                error:function(x,e){
                    $('.progress-bar').attr('aria-valuenow',(((item+1)/toupdate.length)*100));
                    $('.progress-bar').css({'width':(((item+1)/toupdate.length)*100)+'%'});
                    $('.progress-bar').text((((item+1)/toupdate.length)*100)+'%');
                    //console.log(x);
                    //console.log(e);
                    $('#item-'+item).html('<span class="label label-danger">Error</span><br/>'+x.responseText);
                    item=item+1;
                    if(item < toupdate.length){
                        updateItem(item);
                    }
                }
            });
    }
    $(function(){
        $('.alert-warning').remove();
    });
    </script>