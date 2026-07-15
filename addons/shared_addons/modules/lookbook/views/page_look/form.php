<?php
echo form_open_multipart('lookbook/save_page_look', 'name="save_page"');
echo form_hidden('id', (isset($id)) ? $id : '');
echo form_hidden('lookbook', (isset($lookbook->id)) ? $lookbook->id : '');
echo form_hidden('ls', (isset($ls)) ? $ls : '');
echo form_hidden('s', (isset($security)) ? $security : '');
echo form_hidden('redirect_to');
?>
<header>
    <h1><?php
        if (isset($lookbook)) {
            echo anchor('lookbook/edit/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key')), $lookbook->title) . ' / ';
        }
        ?>Page Look</h1>
    <div class="actions">
        <?php echo anchor('lookbook/edit/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key')),'Return to Lookbook','class="btn-warning btn"');?> <?php echo form_submit('submit', 'Save', 'class="btn btn-success"'); ?> <a href="javascript:add_new_page();" class="btn btn-success add_new_page">Add new page look +</a>
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
<div id="editor-menu-bar">
    <div class="row">
        <div class="col-xs-12">
            <ul class="list-inline">
                <?php if (isset($id)) { ?><li><button type="button" id="set-default-icon" onclick="showIcons()" class="btn btn-default btn-sm"><?php
                    if ($default_icon_url) {
                        echo img(array('src' => $default_icon_url, 'alt' => '+', 'style' => 'max-width:15px; max-height:15px;'));
                    } else {
						if (isset($icons[0])) {
							$default_icon_url=$icons[0]->url;
                        	echo img(array('src' => $icons[0]->url, 'alt' => '+', 'style' => 'max-width:15px; max-height:15px;'));
						} else {
							$default_icon_url='';
                        	echo img(array('src' => $default_icon_url, 'alt' => '+', 'style' => 'max-width:15px; max-height:15px;'));
						}
                        
                    }
                    ?> Set Default Icon</button></li>
                    <li><button type="button" onclick='addProduct()' class="btn btn-default btn-sm">Add Product</button></li><?php } ?>
                <li class="change_background">
                    <div class="fileinput <?php echo (isset($image)) ? 'fileinput-exists' : 'fileinput-new'; ?>" data-provides="fileinput">
                        <span class="btn btn-default btn-sm btn-file" style="display:none;"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change Image</span><input type="file" name="main_picture"/></span>
                        <span class="fileinput-filename"></span>
                        <span class="info-text">Please Note: Your products must be visible on your store to add a pin to them.</span>
                        <!--a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a-->
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="editor-wrapper">
    <div class="row">
        <div class="col-lg-10 col-md-9">
            <div id="editor-container">
                <div id="main-editor">
                    <?php
                    if (isset($image)) {
                        if ($image) {
                            echo '<img id="main-image" data-width="' . $image->image_width . '" data-height="' . $image->image_height . '" src="' . str_replace(FCPATH, BASE_URL, $image->full_path) . '" alt="Main image"/>';
                        }
                    }
                    ?>
                    <div id="hovers">
                        <?php
                        $item = 1;
                        if ($products) {
                            foreach ($products as $product):
                                $images = json_decode($product->images);
                                ?>
                                <div id="pin-<?php echo $item; ?>" class="lookbook-item" data-width="<?php echo $product->icon_width; ?>" data-height="<?php echo $product->icon_height; ?>" data-product="<?php echo $product->product; ?>" data-id="<?php echo $item; ?>" data-top="<?php echo $product->top; ?>" data-image="<?php echo $product->pin_image; ?>" data-name="<?php echo $product->product_title; ?>" data-left="<?php echo $product->left; ?>" style="top:<?php echo $product->top; ?>px; left:<?php echo $product->left; ?>px;"><img src="<?php echo $product->icon_url;?>" alt="+"/></div>
                                <?php
                                $item++;
                            endforeach;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3">
            <div id="pin-details">
            <ul id="pin-tabs" class="nav nav-tabs">   
                <li class="active"><a href="#link-product" data-toggle="tab">Product</a></li>
                <li><a href="#link-url" data-toggle="tab">Options</a></li>                
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="link-product">
                  <div class="photo"></div>
                <div class="photo_select"><button class="btn btn-default btn-sm" type="button" onclick="changePhoto()"><span class="glyphicon glyphicon-camera"></span> Change Photo</button></div>
                <div class="section first">
                    <div class="name"></div>
                    <div class="button"><button class="btn btn-default btn-sm" type="button" onclick="changeIcon()">Change Icon</button></div>
                </div>
                <div class="section">
                    <div class="button"><button id="select_product" class="btn btn-success" type="button" onclick="change_product()">Select Product</button></div>
                    <div class="button"><button id="delete-product" class="btn btn-danger" type="button" onclick="delete_product()">Delete Product</button></div>
                </div>
                <div class="section last">
                    <?php if (isset($image)) { ?>
                        Page look size: <br/><?php echo $image->image_width; ?> x <?php echo $image->image_height; ?> px
                    <?php } ?>
                </div>
              </div>
              <div class="tab-pane" id="link-url">
                  <div class="section first">
                    <div>Links to:</div>
                    <div><label for="sel-product"><input type='radio' name="linkto" id='sel-product' checked="checked" value="1"/> Product</label></div>
                    <div><label for="sel-website"><input type='radio' name="linkto" id='sel-website' value="2"/> URL</label></div>
                    <div id="link-website-content">
                        URL: <input type="text" placeholder="http://www.yourwebsite.com" name="link-url"/>
                    </div>
                    <div id="custom-title">
                        Custom Title: <input type="text" placeholder="" name="custom_title"/>
                        <br/><span class='explanation'><i>If you want to display the normal product title leave blank this field.</i></span>
                    </div>
                  </div>
                  <div class="section">
                      Display options:
                      <div><label for="show_image"><input type='checkbox' name="show_image" id='show_image' checked="checked" value="1"/> Show image</label></div>
                      <div><label for="show_title"><input type='checkbox' name="show_title" id='show_title' checked="checked" value="1"/> Show product title</label></div>
                      <div><label for="show_price"><input type='checkbox' name="show_price" id='show_price' checked="checked" value="1"/> Show price and compare at price</label></div>
                      <div><label for="link_target">Link Target</label><?php echo form_dropdown('link_target',array('_blank'=>'New page','_self'=>'Same page'),'_self');?></div>
                  </div>
              </div>                
            </div>
        </div>
    </div>
</div>
<div id="hovers-form">
    <?php
    $item = 1;
    if ($products) {
        foreach ($products as $product):
            ?>
            <input type="hidden" id="id-<?php echo $item; ?>" name="point[<?php echo $item; ?>][id]" value="<?php echo $product->id; ?>"/>
            <input type="hidden" id="top-<?php echo $item; ?>" name="point[<?php echo $item; ?>][top]" value="<?php echo $product->top; ?>"/>
            <input type="hidden" id="left-<?php echo $item; ?>" name="point[<?php echo $item; ?>][left]" value="<?php echo $product->left; ?>"/>
            <input type="hidden" id="icon-<?php echo $item; ?>" name="point[<?php echo $item; ?>][icon]" value="<?php echo $product->icon; ?>"/>
            <input type="hidden" id="image-<?php echo $item; ?>" name="point[<?php echo $item; ?>][image]" value="<?php echo $product->pin_image; ?>"/>
            <input type="hidden" id="product-<?php echo $item; ?>" name="point[<?php echo $item; ?>][product]" value="<?php echo $product->product; ?>"/>
            <input type="hidden" id="linkto-<?php echo $item; ?>" name="point[<?php echo $item; ?>][linkto]" value="<?php echo $product->linkto; ?>"/>
            <input type="hidden" id="url-<?php echo $item; ?>" name="point[<?php echo $item; ?>][url]" value="<?php echo $product->url; ?>"/>
            <input type="hidden" id="custom_title-<?php echo $item; ?>" name="point[<?php echo $item; ?>][custom_title]" value="<?php echo (isset($product->custom_title))? $product->custom_title:''; ?>"/>
            <input type="hidden" id="show_image-<?php echo $item; ?>" name="point[<?php echo $item; ?>][show_image]" value="<?php echo $product->show_image; ?>"/>
            <input type="hidden" id="show_title-<?php echo $item; ?>" name="point[<?php echo $item; ?>][show_title]" value="<?php echo $product->show_title; ?>"/>
            <input type="hidden" id="show_price-<?php echo $item; ?>" name="point[<?php echo $item; ?>][show_price]" value="<?php echo $product->show_price; ?>"/>
            <input type="hidden" id="link_target-<?php echo $item; ?>" name="point[<?php echo $item; ?>][link_target]" value="<?php echo $product->link_target; ?>"/>
            <?php
            $item++;
        endforeach;
    }
    ?>
</div>
<?php 
echo form_hidden('default_icon', $default_icon_id);
echo form_close(); ?>
<div id="product-details" class="white-popup mfp-with-anim mfp-hide">
    <div class="search">
        <ul class="list-inline">
            <li><?php echo form_open('lookbook/search_products', 'search_products'); ?>
        <?php echo form_input(array('name' => 'search', 'placeholder' => 'search')); ?>
        <?php echo form_close(); ?></li>
            <li><button class="btn btn-sm btn-success" type="button" onclick="update_products();">Update Products</button></li>
        </ul>
        <div id="search-loader"></div>
        <div id="search-results">
            <?php echo $search; ?>
        </div>
    </div>
</div>
<div id="product-photos" class="white-popup mfp-with-anim mfp-hide">
    <div class="search">
        <div id="photos-results">            
        </div>
    </div>
</div>
<div id="product-icons" class="white-popup mfp-with-anim mfp-hide">
    <div class="search">
        <ul id="icons-list" class="list-inline">
            <?php
            foreach ($icons as $icon):
                echo '<li><div class="icon" id="icon-'.$icon->id.'"><button type="button" class="btn btn-default btn-sm" onclick="selectIcon(' ."'". $icon->url ."'". ','."'".$icon->id."'".','."'".$icon->width."'".','."'".$icon->height."'".')"><img src="' . $icon->url . '" alt="+"/></button></div><button type="button" class="btn btn-danger btn-sm" onclick="deleteIcon(' . $icon->id . ')"><span class="glyphicon glyphicon-trash"></span></button></li>';
            endforeach;
            ?>
        </ul>
        <div>
            <button type="button" class="add_new_icon btn btn-sm btn-default" onclick="addNewIcon()">Add new icon</button>
            <div id="new-icon">
                <button type="button" class="btn btn-sm btn-default" onclick="backToIcons()">Back</button>
                <ul class="nav nav-tabs" id="editor-menu">
                    <li class="active"><a href="#circle-icon" data-toggle="tab">Circle icon</a></li>
                    <li><a href="#square-icon" data-toggle="tab">Square icon</a></li>
                    <li><a href="#upload-icon" data-toggle="tab">Upload your icon</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="circle-icon">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-3">
                                    <ul class="list-unstyled">
                                        <li><label>Circle Background: </label><input name="circle-color" class="minicolor form-control" value="#000000"/></li>
                                        <li><label>Cross color: </label><input name="circle-cross-color" class="minicolor form-control" value="#FFFFFF"/></li>
                                    </ul>
                                </div>
                                <div class="col-xs-2">
                                    <label>Preview: </label>
                                    <div class="icon">
                                        <div class="circle-preview">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="button" class="btn btn-sm btn-success" onclick="addSelectedIcon('circle')">Add this icon</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="square-icon">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-3">
                                    <ul class="list-unstyled">
                                        <li><label>Square Background: </label><input name="square-color" class="minicolor form-control" value="#000000"/></li>
                                        <li><label>Cross color: </label><input name="square-cross-color" class="minicolor form-control" value="#FFFFFF"/></li>
                                    </ul>
                                </div>
                                <div class="col-xs-2">
                                    <label>Preview: </label>
                                    <div class="icon">
                                        <div class="square-preview">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button type="button" class="btn btn-sm btn-success" onclick="addSelectedIcon('square')">Add this icon</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="upload-icon"><?php
                        echo form_open_multipart('lookbook/upload_icon', 'name="new_icon" target="upload_icon"');
                        ?>
                        <div class="row">
                            <div class="col-xs-6">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                        <div>
                                            <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="icon_image"></span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success">Upload</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <iframe name="upload_icon" id="upload_icon" class="upload-icon-frame"></iframe>
                            </div>
                        </div>
                        
                        
                        <?php
                        echo form_close();
                        ?></div>
                </div>                       
            </div>
        </div>        
    </div>
</div>
<div id="products-json"><?php echo ($products)?json_encode($products):''; ?></div>
<script>
                    var image_width =<?php echo (isset($image->image_width)) ? $image->image_width : 0; ?>;
                    var image_height =<?php echo (isset($image->image_height)) ? $image->image_height : 0; ?>;
                    var current_pin = '';
                    var products = [];
                    var default_icon_url='<?php echo $default_icon_url;?>';
                    var default_icon_id='<?php echo $default_icon_id;?>';
                    var default_icon_width='<?php echo $default_icon_width;?>';
                    var default_icon_height='<?php echo $default_icon_height;?>';
                    var item =<?php echo $item; ?>;
                    var change_icon=false;
                    function update_products(){
                        if($('#search-loader .loader').length == 0){
                            $('#search-loader').html('');
                            $('#search-loader').append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
                            $.ajax({
                               url:'<?php echo site_url('lookbook/update_products');?>', 
                               dataType:'json',
                               type:'GET',
                               success: function(data) {
                                    if(data.status=='success'){
                                        $('#product-details form').submit();
                                        $('#search-loader').html('<div class="alert alert-success">'+data.msg+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
                                    }else{
                                        $('#search-loader').html('<div class="alert alert-error">'+data.msg+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
                                    }
                                },
                                error:function(x,e,text){
                                    $('#search-loader').html('<div class="alert alert-error"><b>Error: </b>'+text+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
                                }
                            });
                        }
                    }
                    function select_product(id, images, name) {
                        //    '//cdn.shopify.com/s/assets/image-upload-899ed12e0ca7dd5ac65df627c3b5bf54.png'
                        var prod = {
                            id: '',
                            product: id,
                            images: images,
                            product_title: decodeURIComponent(name)
                        };
                        products[current_pin - 1] = prod;
                        $('#product-' + current_pin).val(id);
                        $('#pin-' + current_pin).attr('data-name', decodeURIComponent(name));
                        $('#pin-' + current_pin).attr('data-product', id);
                        $('#pin-' + current_pin).attr('data-image', '0');
                        $('#pin-' + current_pin).mousedown();
                        $.magnificPopup.close();

                    }
                    function showIcons() {
                        $.magnificPopup.open({
                            items: {
                                src: '#product-icons',
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
                    }
                    function changeIcon(){
                        change_icon=true;
                        showIcons();
                    }
                    function addNewIcon() {
                        $('#new-icon').slideDown();
                        $('#icons-list').slideUp();
                        $('.add_new_icon').slideUp();
                    }
                    function backToIcons() {
                        $('#new-icon').slideUp();
                        $('#icons-list').slideDown();
                        $('.add_new_icon').slideDown();
                    }
                    function addUploadedIconToList(url, id, width, height){
                        var li = $('<li></li>');
                        li.append('<div class="icon" id="icon-'+id+'"><button type="button" class="btn btn-default btn-sm" onclick="selectIcon(' +"'"+ url +"'"+ ','+"'"+id+"'"+','+"'"+width+"'"+','+"'"+height+"'"+')"><img src="' + url + '" alt="+"/></button></div><button type="button" class="btn btn-danger btn-sm" onclick="deleteIcon(' + id + ')"><span class="glyphicon glyphicon-trash"></span></button>');
                        $('#icons-list').append(li);
                        $('#new-icon .loader').remove();
                        backToIcons();
                    }
                    function deleteIcon(id){
                        $('#new-icon').prepend('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
                        $.ajax({
                            url: '<?php echo site_url('lookbook/delete_icon'); ?>/' + id,
                            dataType: "json",
                            type: "GET",
                            async: true,
                            success: function(data) {
                                if (data.status == 'success') {
                                    $('#icon-'+id).parent().remove();
                                } else {
                                    show_message('error', data.msg);
                                }
                                $('#new-icon .loader').remove();
                            },
                            error: function(jqXHR, status, errorThrown) {
                                show_error(jqXHR, status, errorThrown);
                                $('#new-icon .loader').remove();
                            }
                        });
                    }
                    function selectIcon(icon_url, icon_id, icon_width, icon_height) {
                        if(change_icon){
                            $('#icon-' + current_pin).val(icon_id);
                            $('#pin-'+current_pin).find('img').attr('src',icon_url);
                            $('#pin-'+current_pin).attr('data-width',icon_width);
                            $('#pin-'+current_pin).attr('data-height',icon_height);
                            $(window).resize();
                            change_icon=false;
                        }else{
                            default_icon_url=icon_url;
                            default_icon_id=icon_id;
                            default_icon_width=icon_width;
                            default_icon_height=icon_height;
                            $('#set-default-icon img').attr('src',default_icon_url);
                            $('input[name=default_icon]').val(default_icon_id);
                        }
                        $.magnificPopup.close();
                    }
                    function addProduct() {
                        $new = $('<div id="pin-' + item + '" class="lookbook-item" data-width="'+default_icon_width+'" data-height="'+default_icon_height+'"><img src="'+default_icon_url+'" alt="+" /></div>');
                        $('#hovers').append($new);
                        
                        var ratio = Math.round(($('#main-image').width() / image_width) * 1000) / 1000;
                        /*$new.width($new.attr('data-width') * ratio);
                         $new.height($new.attr('data-height') * ratio);*/
                        $new.show();
                        $new.width(0);
                        $new.height(0);
                        $new.animate({
                            width: $new.attr('data-width') * ratio,
                            height: $new.attr('data-height') * ratio,
                        }, 1000, "easeOutElastic", function() {
                        });

                        var top = (image_height / 2) - ($new.height() / 2);
                        var left = (image_width / 2) - ($new.width() / 2);
                        $new.css({
                            top: top * ratio,
                            left: left * ratio
                        });
                        $new.attr('data-top', top);
                        $new.attr('data-left', left);
                        $new.attr('data-id', item);
                        $('#hovers-form').append('<input type="hidden" id="id-' + item + '" name="point[' + item + '][id]" value=""/>');
                        $('#hovers-form').append('<input type="hidden" id="top-' + item + '" name="point[' + item + '][top]" value="' + top + '"/>');
                        $('#hovers-form').append('<input type="hidden" id="left-' + item + '" name="point[' + item + '][left]" value="' + left + '"/>');
                        $('#hovers-form').append('<input type="hidden" id="icon-' + item + '" name="point[' + item + '][icon]" value="' + default_icon_id + '"/>');
                        $('#hovers-form').append('<input type="hidden" id="image-' + item + '" name="point[' + item + '][image]" value="0"/>');
                        $('#hovers-form').append('<input type="hidden" id="product-' + item + '" name="point[' + item + '][product]" value=""/>');                        
                        $('#hovers-form').append('<input type="hidden" id="linkto-' + item + '" name="point[' + item + '][linkto]" value="1"/>');
                        $('#hovers-form').append('<input type="hidden" id="url-' + item + '" name="point[' + item + '][url]" value=""/>');
                        $('#hovers-form').append('<input type="hidden" id="custom_title-' + item + '" name="point[' + item + '][custom_title]" value=""/>');
                        $('#hovers-form').append('<input type="hidden" id="show_image-' + item + '" name="point[' + item + '][show_image]" value="1"/>');
                        $('#hovers-form').append('<input type="hidden" id="show_title-' + item + '" name="point[' + item + '][show_title]" value="1"/>');
                        $('#hovers-form').append('<input type="hidden" id="show_price-' + item + '" name="point[' + item + '][show_price]" value="1"/>');
                        $('#hovers-form').append('<input type="hidden" id="link_target-' + item + '" name="point[' + item + '][link_target]" value="_self"/>');
                        item = item + 1;
                        current_pin = item;
                        $new.mousedown(function() {
                            update_pin_details(this);
                        });
                        $new.draggable({stack: '#main-editor', containment: '#main-editor', stop: function(event, ui) {
                                drag_pin(this, ui);
                            }});
                        return false;
                    }
                    function addSelectedIcon(shape) {
                        if (shape == 'circle') {
                            var bg = $('input[name=circle-color]').minicolors('rgbObject');
                            var cross = $('input[name=circle-cross-color]').minicolors('rgbObject');
                            var url = '<?php echo site_url('lookbook/add_icon/circle/'); ?>/' + (bg.r + '_' + bg.g + '_' + bg.b + '_' + (100 - Math.round(bg.a * 100))) + '/' + (cross.r + '_' + cross.g + '_' + cross.b + '_' + (100 - Math.round(cross.a * 100)));
                        } else {
                            var bg = $('input[name=square-color]').minicolors('rgbObject');
                            var cross = $('input[name=square-cross-color]').minicolors('rgbObject');
                            var url = '<?php echo site_url('lookbook/add_icon/square/'); ?>/' + (bg.r + '_' + bg.g + '_' + bg.b + '_' + (100 - Math.round(bg.a * 100))) + '/' + (cross.r + '_' + cross.g + '_' + cross.b + '_' + (100 - Math.round(cross.a * 100)));
                        }
                        $('#new-icon').prepend('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
                        $.ajax({
                            url: url,
                            dataType: "json",
                            type: "GET",
                            async: true,
                            success: function(data) {
                                if (data.status == 'success') {
                                    var li = $('<li></li>');
                                    li.append('<div class="icon" id="icon-'+data.id+'"><button type="button" class="btn btn-default btn-sm" onclick="selectIcon(' +"'"+ data.url +"'"+ ','+"'"+data.id+"'"+','+"'"+data.width+"'"+','+"'"+data.height+"'"+')"><img src="' + data.url + '" alt="+"/></button></div><button type="button" class="btn btn-danger btn-sm" onclick="deleteIcon(' + data.id + ')"><span class="glyphicon glyphicon-trash"></span></button>');
                                    $('#icons-list').append(li);
                                    backToIcons();
                                    //show_message('success',data.msg,true);
                                } else {
                                    show_message('error', data.msg);
                                }
                                $('#new-icon .loader').remove();
                            },
                            error: function(jqXHR, status, errorThrown) {
                                show_error(jqXHR, status, errorThrown);
                                $('#new-icon .loader').remove();
                            }
                        });
                    }
                    function change_product() {
                        $.magnificPopup.open({
                            items: {
                                src: '#product-details',
                                type: 'inline'
                            },
                            removalDelay: 500,
                            mainClass: 'mfp-3d-unfold',
                            midClick: true,
                            callbacks: {
                                beforeOpen: function() {
                                    this.st.mainClass = 'mfp-3d-unfold';
                                }
                            },
                            focus: 'input[name="search"]'
                        });
                    }
                    function add_new_page(){
                        $('input[name=redirect_to]').val('<?php echo 'lookbook/add_page/' . $lookbook->id . '/' . sha1($lookbook->id . $this->config->item('encryption_key'));?>');
                        $('input[name=submit]').click();
                        return false;
                    }
                    function changePhoto() {
                        $.magnificPopup.open({
                            items: {
                                src: '#product-photos',
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
                        var product = $('#pin-' + current_pin).attr('data-product');
                        var url = '<?php echo site_url('lookbook/product_photos'); ?>/' + product;
                        $.ajax({
                            url: url,
                            dataType: "html",
                            type: "GET",
                            success: function(data) {
                                $('#photos-results').html(data);
                            },
                            error: function(jqXHR, status, errorThrown) {
                                show_error(jqXHR, status, errorThrown);
                            }
                        });
                        return false;
                    }
                    function selectPhoto(number, image) {
                        $('#pin-' + current_pin).attr('data-image', number);
                        $('#image-' + current_pin).val(number);
                        $('#pin-' + current_pin).mousedown();
                        $.magnificPopup.close();
                    }
                    function update_pin_details(obj) {
                        current_pin = $(obj).attr('data-id');
                        $('.lookbook-item').removeClass('active');
                        $(obj).addClass('active');
                        if ($(obj).attr('data-image')) {
                            if(products[current_pin - 1].images){
                                var images = $.parseJSON(products[current_pin - 1].images);
                            }else{
                                var images = '';
                            }
                            var image = '';
                            if (images[$(obj).attr('data-image')] === undefined) {
                                image = '//cdn.shopify.com/s/assets/image-upload-899ed12e0ca7dd5ac65df627c3b5bf54.png';
                            } else {
                                image = images[$(obj).attr('data-image')];
                            }
                            $('#select_product').removeClass('btn-success').addClass('btn-warning').text('Change Product');
                            $('#pin-details .photo').html('<img src="' + image + '" style="max-width:100%; max-height:100%;" alt="image"/>');
                            $('#pin-details .name').html($(obj).attr('data-name'));
                        } else {
                            $('#select_product').removeClass('btn-warning').addClass('btn-success').text('Select Product');
                            $('#pin-details .photo').html('');
                            $('#pin-details .name').html('');
                        }
                        if($('#linkto-'+current_pin).val()==2){
                            $('#link-website-content').show();
                             $("#custom-title,label[for='show_image'],label[for='show_title'],label[for='show_price']").css("display","none");
                        }else{
                            $('#link-website-content').hide();
                             $("#custom-title,label[for='show_image'],label[for='show_title'],label[for='show_price']").css("display","inline");
                        }
                        $('#pin-details input[name=show_image]').prop('checked',($('#show_image-'+current_pin).val()==1)? true:false);
                        $('#pin-details input[name=show_title]').prop('checked',($('#show_title-'+current_pin).val()==1)? true:false);
                        $('#pin-details input[name=show_price]').prop('checked',($('#show_price-'+current_pin).val()==1)? true:false);
                        $('#pin-details input[name=linkto][value='+$('#linkto-'+current_pin).val()+']').prop('checked',true);
                        $('#pin-details input[name=link-url]').val($('#url-'+current_pin).val());
                        $('#pin-details input[name=custom_title]').val($('#custom_title-'+current_pin).val());
                        $('#pin-details select[name=link_target]').val(($('#link_target-'+current_pin).val())?$('#link_target-'+current_pin).val():'_self');
                        $('#pin-details .nav-tabs').fadeIn();
                        $('#pin-details .photo_select').fadeIn();
                        $('#pin-details .section').fadeIn();
                    }
                    function clear_pin_details() {
                        $('#pin-tabs a:first').tab('show');
                        $('.lookbook-item').removeClass('active');
                        current_pin = '';
                        $('#pin-details .photo').html('');
                        $('#pin-details .nav-tabs').hide();
                        $('#pin-details .name').html('');
                        $('#pin-details .section').hide();
                        $('#pin-details .section.last').show();
                        $('#pin-details .photo_select').hide();
                        $('#select_product').removeClass('btn-warning').addClass('btn-success').text('Select Product');
                    }
                    function delete_product() {
                        $('#pin-' + current_pin).remove();
                        $('#id-' + current_pin).remove();
                        $('#top-' + current_pin).remove();
                        $('#left-' + current_pin).remove();
                        $('#icon-' + current_pin).remove();
                        $('#image-' + current_pin).remove();
                        $('#product-' + current_pin).remove();
                        $('#linkto-' + current_pin).remove();
                        $('#url-' + current_pin).remove();
                        $('#custom_title-' + current_pin).remove();
                        $('#show_image-' + current_pin).remove();
                        $('#show_title-' + current_pin).remove();
                        $('#show_price-' + current_pin).remove();
                        $('#link_target-' + current_pin).remove();
                        clear_pin_details();
                    }
                    function drag_pin(obj, ui) {
                        var ratio = Math.round(($('#main-image').width() / image_width) * 1000) / 1000;
                        $(obj).attr('data-top', ui.position.top / ratio);
                        $(obj).attr('data-left', ui.position.left / ratio);
                        $('#top-' + $(obj).attr('data-id')).val(ui.position.top / ratio);
                        $('#left-' + $(obj).attr('data-id')).val(ui.position.left / ratio);
                        current_pin = $(obj).attr('data-id');
                    }
                    $(function() {
                        $('#link-url input[type=radio]').change(function(){
                           if($('#link-url input[type=radio]:checked').val()==1){
                               $('#link-website-content').hide();
                               $("#custom-title,label[for='show_image'],label[for='show_title'],label[for='show_price']").css("display","inline");
                           }else{
                                $('#link-website-content').show();   
                                $("#custom-title,label[for='show_image'],label[for='show_title'],label[for='show_price']").css("display","none");
                           }
                           $('#linkto-' + current_pin).val($('#link-url input[type=radio]:checked').val());                           
                        });
                        $('#link-url input[name=link-url]').change(function(){
                             $('#url-' + current_pin).val($('input[name=link-url]').val());
                        });
                        $('#link-url input[name=show_image]').change(function(){
                             $('#show_image-' + current_pin).val(($(this).is(':checked'))?1:0);
                        });
                        $('#link-url input[name=show_title]').change(function(){
                             $('#show_title-' + current_pin).val(($(this).is(':checked'))?1:0);
                        });
                        $('#link-url input[name=show_price]').change(function(){
                             $('#show_price-' + current_pin).val(($(this).is(':checked'))?1:0);
                        });
                        $('#link-url select[name=link_target]').change(function(){
                             $('#link_target-' + current_pin).val($(this).val());
                        });
                        $('input[name=custom_title]').change(function(){
                            $('#custom_title-'+current_pin).val($(this).val());                            
                        });
                        $('form[name=new_icon]').submit(function(){
                           $('#new-icon').prepend('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>'); 
                        });
                        $('.minicolor').minicolors({theme: 'bootstrap', opacity: true});
                        $('input[name=circle-color], input[name=circle-cross-color]').blur(function() {
                            var bg = $('input[name=circle-color]').minicolors('rgbObject');
                            var cross = $('input[name=circle-cross-color]').minicolors('rgbObject');
                            var url = '<?php echo site_url('lookbook/create_icon/circle/'); ?>/' + (bg.r + '_' + bg.g + '_' + bg.b + '_' + (100 - Math.round(bg.a * 100))) + '/' + (cross.r + '_' + cross.g + '_' + cross.b + '_' + (100 - Math.round(cross.a * 100))) + '/true';
                            $('#circle-icon .circle-preview').html('<img src="' + url + '" alt="+"/>');
                        });
                        $('input[name=square-color], input[name=square-cross-color]').blur(function() {
                            var bg = $('input[name=square-color]').minicolors('rgbObject');
                            var cross = $('input[name=square-cross-color]').minicolors('rgbObject');
                            var url = '<?php echo site_url('lookbook/create_icon/square/'); ?>/' + (bg.r + '_' + bg.g + '_' + bg.b + '_' + (100 - Math.round(bg.a * 100))) + '/' + (cross.r + '_' + cross.g + '_' + cross.b + '_' + (100 - Math.round(cross.a * 100))) + '/true';
                            $('#square-icon .square-preview').html('<img src="' + url + '" alt="+"/>');
                        });
                        $('input[name=circle-color]').blur();
                        $('input[name=square-color]').blur();
                        if($('#products-json').text()){
                            products = $.parseJSON($('#products-json').text());
                        }
                        $('#products-json').remove();
                        $.each(products, function(key, value) {
                            var images = $.parseJSON(value.images);
                        });
                        $('#main-image').click(function() {
                            clear_pin_details();
                        });
                        $(document).on('click', '#search-results .pagination a', function() {
                            var url = $(this).attr('href');
                            $.ajax({
                                url: url,
                                dataType: "html",
                                type: "GET",
                                success: function(data) {
                                    $('#search-results').html(data);
                                },
                                error: function(jqXHR, status, errorThrown) {
                                    show_error(jqXHR, status, errorThrown);
                                }
                            });
                            return false;
                        });
                        $('#product-details form').submit(function() {
                            var url = $(this).attr('action');
                            var data={
                                    k: escape(encodeURIComponent($('input[name=search]').val()))
                                };
                            $.ajax({
                                url: url,
                                dataType: "html",
                                type: "POST",
                                data: data,
                                success: function(data) {
                                    $('#search-results').html(data);
                                },
                                error: function(jqXHR, status, errorThrown) {
                                    show_error(jqXHR, status, errorThrown);
                                }
                            });
                            return false;
                        });
                        /*$('button.add_product').click(function() {
                         
                         });*/
                        function initialize() {
                            var ratio = Math.round(($('#main-image').width() / image_width) * 1000) / 1000;
                            $('.lookbook-item').each(function() {
                                $(this).width(0);
                                $(this).height(0);
                                var top = $(this).attr('data-top') / (image_height);
                                var left = $(this).attr('data-left') / (image_width);
                                $(this).css({
                                    top: top * ratio * image_height,
                                    left: left * ratio * image_width
                                });
                                var $new=$(this);
                                $new.show('fast');
                                $(this).animate({
                                    width: $new.attr('data-width') * ratio,
                                    height: $new.attr('data-height') * ratio,
                                }, 1000, "easeOutElastic", function() {
                                });

                                $(this).mousedown(function() {
                                    update_pin_details(this);
                                });
                                $(this).draggable({stack: '#main-editor', containment: '#main-editor', stop: function(event, ui) {
                                        drag_pin(this, ui);
                                    }});
                            });
                        }
                        initialize();
                        $(window).load(function() {
                            initialize();
                        });
                        $(window).resize(function() {
                            var ratio = Math.round(($('#main-image').width() / image_width) * 1000) / 1000;
                            $('.lookbook-item').each(function() {
                                $(this).width($(this).attr('data-width') * ratio);
                                $(this).height($(this).attr('data-height') * ratio);
                                var top = Math.round($(this).attr('data-top') / (image_height) * 1000) / 1000;
                                var left = Math.round($(this).attr('data-left') / (image_width) * 1000) / 1000;
                                $('#top-' + $(this).attr('data-id')).val(Math.round(top * image_height * 1000) / 1000);
                                $('#left-' + $(this).attr('data-id')).val(Math.round(left * image_width * 1000) / 1000);
                                $(this).css({
                                    top: top * ratio * image_height,
                                    left: left * ratio * image_width
                                });
                            });
                        });
                    });
<?php if (isset($lookbook->id)) { ?>
                        $("input[name=main_picture]").change(function() {
                            $('input[name=submit]').click();
                        });
<?php } ?>
</script>