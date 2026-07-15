<ul>
    <?php
    if ($pages) {
        foreach ($pages as $page):
            ?>
            <li><?php
                $image = json_decode($page['info']->image);
                echo '<div class="modmedia-image" data-width="' . $image->image_width . '" data-height="' . $image->image_height . '"><img src="' . $image->full_path. '" alt="Main image"/>';
                ?>
                <div class="modmedia-lookbook-item-hover"></div>
                <div id="hovers">
                    <?php
                    $item = 1;
                    $products = $page['products'];
                    if ($products) {
                        foreach ($products as $product):
                            $images = json_decode($product->images);
                            ?>
                            <div id="pin-<?php echo $item; ?>" class="lookbook-item" data-width="26" data-height="26" data-id="<?php echo $item; ?>" data-top="<?php echo $product->top; ?>" data-image="<?php echo (isset($images[$product->pin_image]))? $images[$product->pin_image] : '//cdn.shopify.com/s/assets/image-upload-899ed12e0ca7dd5ac65df627c3b5bf54.png'; ?>" data-name="<?php echo $product->product_title; ?>" data-left="<?php echo $product->left; ?>" style="top:<?php echo $product->top; ?>px; left:<?php echo $product->left; ?>px;"><img src="<?php echo $product->icon_url;?>" alt="+"/></div>
                            <?php
                            $item++;
                        endforeach;
                    }
                    ?>
                </div>  
                <?php echo '</div>';?>
            </li>
        <?php endforeach;
    }
    ?>
</ul>
<script type="text/javascript">
    $(function() {
        $(window).resize(function() {
            $('.modmedia-image').each(function() {
                var image_width = $(this).attr('data-width');
                var image_height = $(this).attr('data-height');
                var ratio = Math.round(($(this).width() / image_width) * 1000) / 1000;
                $(this).parent().find('.lookbook-item').each(function() {
                    $(this).width($(this).attr('data-width') * ratio);
                    $(this).height($(this).attr('data-height') * ratio);
                    var top = Math.round($(this).attr('data-top') / (image_height) * 1000) / 1000;
                    var left = Math.round($(this).attr('data-left') / (image_width) * 1000) / 1000;
                    $(this).css({
                        top: top * ratio * image_height,
                        left: left * ratio * image_width
                    });
                });
            });
        });
        $(window).resize();
        $('.lookbook-item').mouseover(function() {
            var parent = $(this).parent().parent();
            var image_width = parent.attr('data-width');
            var ratio = Math.round((parent.width() / image_width) * 1000) / 1000;
            parent.find('.modmedia-lookbook-item-hover').html('<div class="modmedia-lookbook-product-image"><img style="max-width:150px; max-height:150px;" src="' + $(this).attr('data-image') + '" alt="' + $(this).attr('data-name') + '"/></div><div>' + $(this).attr('data-name') + '</div>');
            var lookbook_item = $(this);
            setPosition(parent, $(this));
            parent.find('.modmedia-lookbook-item-hover').show('fast', function() {
                setPosition(parent, lookbook_item);
            });            
        });
        $('.lookbook-item').mouseout(function() {
            var parent = $(this).parent().parent();
            parent.find('.modmedia-lookbook-item-hover').hide();
        });
    });
    function setPosition(parent, lookbook_item) {
        var top_middle=false;
        if ((lookbook_item.position().top - parent.find('.modmedia-lookbook-item-hover').outerHeight()) < 0) {
            if((lookbook_item.position().top + lookbook_item.outerHeight()) > parent.outerHeight()){
                var top = parent.outerHeight()-parent.find('.modmedia-lookbook-item-hover').outerHeight();
                top_middle=true;
            }else{
                var top = lookbook_item.position().top + lookbook_item.outerHeight();
            }
        } else {
            if((lookbook_item.position().top + parent.find('.modmedia-lookbook-item-hover').outerHeight()) >  parent.outerHeight()){
                var top = parent.outerHeight() - parent.find('.modmedia-lookbook-item-hover').outerHeight() - 5;
                top_middle=true;
            }else{
                var top = lookbook_item.position().top - parent.find('.modmedia-lookbook-item-hover').outerHeight();
            }
        }
        if ((lookbook_item.position().left + parent.find('.modmedia-lookbook-item-hover').outerWidth()) < parent.outerWidth()) {
                if(top_middle){
                    var left = lookbook_item.position().left + lookbook_item.outerWidth();
                }else{
                    var left = lookbook_item.position().left;
                }
        } else {
            if((lookbook_item.position().left + lookbook_item.outerWidth() - parent.find('.modmedia-lookbook-item-hover').outerWidth()) < 0){
                    if(top_middle){
                        var left = lookbook_item.position().left + lookbook_item.outerWidth() - parent.find('.modmedia-lookbook-item-hover').outerWidth();
                    }else{
                        var left = parent.outerWidth() - parent.find('.modmedia-lookbook-item-hover').outerWidth();                
                    }                    
            }else{
                if(top_middle){
                    var left = lookbook_item.position().left - parent.find('.modmedia-lookbook-item-hover').outerWidth();
                }else{
                    var left = lookbook_item.position().left + lookbook_item.outerWidth() - parent.find('.modmedia-lookbook-item-hover').outerWidth();
                }
            }
        }
        parent.find('.modmedia-lookbook-item-hover').css({
            top: top,
            left: left
        });
    }
</script>