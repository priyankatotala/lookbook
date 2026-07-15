<?php
/*$days = 60;
$expires = 60 * 60 * 24 * $days;
header("Pragma: public");
header("Cache-Control: max-age=" . $expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');*/
$theme_settings = json_decode($lookbook->theme_settings);
?>
if(typeof myAppJavaScriptmodLookbook != "function"){
var myAppJavaScriptmodLookbook = function($) {
var pages = [    
<?php
if ($pages) {
    $n = 1;
    foreach ($pages as $page):
        if ($n > 1) {
            echo ',';
        }
        ?>
        {
        page: '<?php echo $n; ?>',
        image: <?php echo $page['info']->image; ?>,
        products: [
        <?php
        $products = $page['products'];
        $item = 1;
        if ($products) {
            foreach ($products as $product):
                $images = json_decode($product->images);
                if ($item > 1) {
                    echo ',';
                }
                ?>
                {
                item:<?php echo $item; ?>,
                top:<?php echo $product->top; ?>,
                left:<?php echo $product->left; ?>,
                link_target:'<?php echo $product->link_target; ?>',
                linkto:<?php echo $product->linkto; ?>,
                url:"<?php echo $product->url; ?>",
                custom_title:"<?php echo $product->custom_title; ?>",
                show_image:"<?php echo $product->show_image; ?>",
                show_title:"<?php echo $product->show_title; ?>",
                show_price:"<?php echo $product->show_price; ?>",
                icon:"<?php echo $product->icon_url; ?>",
                icon_width: "<?php echo $product->icon_width; ?>",
                icon_height: "<?php echo $product->icon_height; ?>",
                image: "<?php echo (isset($images[$product->pin_image])) ? $images[$product->pin_image] : '//cdn.shopify.com/s/assets/image-upload-899ed12e0ca7dd5ac65df627c3b5bf54.png'; ?>",
                handle: "<?php echo $product->handle; ?>"
                }
                <?php
                $item++;
            endforeach;
        } else {
            ?>
        <?php } ?>
        ]}
        <?php
        $n++;
    endforeach;
}
?>
];
<?php
$css = "#lookbook-modmedia-" . $lookbook->id . " .fflex-control-paging li a { ";
if (isset($theme_settings->slider_ct_width)) {
    $css.='width: ' . $theme_settings->slider_ct_width . 'px; ';
}
if (isset($theme_settings->slider_ct_height)) {
    $css.='height: ' . $theme_settings->slider_ct_height . 'px; ';
}
if (isset($theme_settings->slider_ct_shad)) {
    if ($theme_settings->slider_ct_shad == 0) {
        $css.='-webkit-box-shadow: none; -moz-box-shadow: none; -o-box-shadow: none; box-shadow: none; ';
    } else {
        $css.='-webkit-box-shadow: inset 1px 1px 3px rgba(0,0,0,0.8); -moz-box-shadow: inset 1px 1px 3px rgba(0,0,0,0.8); -o-box-shadow: inset 1px 1px 3px rgba(0,0,0,0.8); box-shadow: inset 1px 1px 3px rgba(0,0,0,0.8); ';
    }
}
if (isset($theme_settings->slider_ct_num)) {
    if ($theme_settings->slider_ct_num == 1) {
        $css.='text-indent: 0px; ';
    } else {
        $css.='text-indent: -9999px; ';
    }
}
if (isset($theme_settings->slider_ct_mg_top)) {
    $css.='margin-top: ' . $theme_settings->slider_ct_mg_top . 'px; ';
}
if (isset($theme_settings->slider_ct_mg_left)) {
    $css.='margin-top: ' . $theme_settings->slider_ct_mg_left . 'px; ';
}
if (isset($theme_settings->slider_ct_mg_right)) {
    $css.='margin-top: ' . $theme_settings->slider_ct_mg_right . 'px; ';
}
if (isset($theme_settings->slider_ct_mg_bottom)) {
    $css.='margin-top: ' . $theme_settings->slider_ct_mg_bottom . 'px; ';
}
if (isset($theme_settings->slider_ct_bg)) {
    $css.='background: ' . $theme_settings->slider_ct_bg . '; ';
}
if (isset($theme_settings->slider_ct_bd_st)) {
    $css.='border-style: ' . $theme_settings->slider_ct_bd_st . '; ';
}
if (isset($theme_settings->slider_ct_bd_wd)) {
    $css.='border-width: ' . $theme_settings->slider_ct_bd_wd . 'px; ';
}
if (isset($theme_settings->slider_ct_bd_rad)) {
    $css.='-webkit-border-radius: ' . $theme_settings->slider_ct_bd_rad . 'px; -moz-border-radius: ' . $theme_settings->slider_ct_bd_rad . 'px; -o-border-radius: ' . $theme_settings->slider_ct_bd_rad . 'px; border-radius: ' . $theme_settings->slider_ct_bd_rad . 'px; ';
}
if (isset($theme_settings->slider_ct_bd)) {
    $css.='border-color: ' . $theme_settings->slider_ct_bd . '; ';
}
if (isset($theme_settings->slider_ct_txt)) {
    $css.='color: ' . $theme_settings->slider_ct_txt . '; ';
}
$css.="} #lookbook-modmedia-" . $lookbook->id . " .fflex-control-paging li a:hover {";
if (isset($theme_settings->slider_ct_hover_bg)) {
    $css.=' background: ' . $theme_settings->slider_ct_hover_bg . '; ';
}
if (isset($theme_settings->slider_ct_hover_bd)) {
    $css.='border-color: ' . $theme_settings->slider_ct_hover_bd . '; ';
}
if (isset($theme_settings->slider_ct_hover_txt)) {
    $css.='color: ' . $theme_settings->slider_ct_hover_txt . '; ';
}
$css.="} #lookbook-modmedia-" . $lookbook->id . " .fflex-control-paging li a.fflex-active {";
if (isset($theme_settings->slider_ct_active_bg)) {
    $css.=' background: ' . $theme_settings->slider_ct_active_bg . '; ';
}
if (isset($theme_settings->slider_ct_active_bd)) {
    $css.='border-color: ' . $theme_settings->slider_ct_active_bd . '; ';
}
if (isset($theme_settings->slider_ct_active_txt)) {
    $css.='color: ' . $theme_settings->slider_ct_active_txt . '; ';
}
$css.="}";
if (isset($theme_settings->slider_dir_nav_use_image)) {
    if ($theme_settings->slider_dir_nav_use_image == 1) {
        $css.="#lookbook-modmedia-" . $lookbook->id . " .fflex-direction-nav .fflex-next{";
        $css.='text-indent:-9999px; text-align:left; background:url(' . $theme_settings->slider_dir_nav_image . '); width: ' . round($theme_settings->slider_dir_nav_image_width / 2, 0) . 'px; height:' . round($theme_settings->slider_dir_nav_image_height / 2, 0) . 'px; margin-top:-'.round($theme_settings->slider_dir_nav_image_height / 4, 0).'px; background-position:-'.round($theme_settings->slider_dir_nav_image_width / 2, 0).'px 0px;';
        $css.="}";
        $css.="#lookbook-modmedia-" . $lookbook->id . " .fflex-direction-nav .fflex-next:hover{";
        $css.='background-position:-'.round($theme_settings->slider_dir_nav_image_width / 2, 0).'px -' . round($theme_settings->slider_dir_nav_image_height / 2, 0) . 'px;';
        $css.="}";
        $css.="#lookbook-modmedia-" . $lookbook->id . " .fflex-direction-nav .fflex-prev{";
        $css.='text-indent:-9999px; text-align:left; background:url(' . $theme_settings->slider_dir_nav_image . '); width: ' . round($theme_settings->slider_dir_nav_image_width / 2, 0) . 'px; height:' . round($theme_settings->slider_dir_nav_image_height / 2, 0) . 'px; margin-top:-'.round($theme_settings->slider_dir_nav_image_height / 4, 0).'px; background-position:0 0;';
        $css.="}";
        $css.="#lookbook-modmedia-" . $lookbook->id . " .fflex-direction-nav .fflex-prev:hover{";
        $css.='background-position:0 -' . round($theme_settings->slider_dir_nav_image_height / 2, 0) . 'px;';
        $css.="}";
    }
}
?>
var css = "<?php echo str_replace(array("\r\n", "\n"), '', $css); ?>";
var style = document.createElement('style');

style.type = 'text/css';
if (style.styleSheet){
style.styleSheet.cssText = css;
} else {
style.appendChild(document.createTextNode(css));
}
document.getElementsByTagName("head")[0].appendChild(style);

if((typeof Shopify) == 'undefined'){
if ((typeof Shopify) == 'undefined') {
var Shopify = {};
}
/* ---------------------------------------------------------------------------
* Money format handler single/slideshow
* ---------------------------------------------------------------------------*/
Shopify.money_format = "$ {{amount}}";
Shopify.formatMoney = function(cents, format) {
if (typeof cents == 'string') cents = cents.replace('.','');
var value = '';
var patt = /\{\{\s*(\w+)\s*\}\}/;
var formatString = (format || this.money_format);

function addCommas(moneyString) {
return moneyString.replace(/(\d+)(\d{3}[\.,]?)/,'$1,$2');
}

switch(formatString.match(patt)[1]) {
case 'amount':
value = addCommas(floatToString(cents/100.0, 2));
break;
case 'amount_no_decimals':
value = addCommas(floatToString(cents/100.0, 0));
break;
case 'amount_with_comma_separator':
value = floatToString(cents/100.0, 2).replace(/\./, ',');
break;
case 'amount_no_decimals_with_comma_separator':
value = addCommas(floatToString(cents/100.0, 0)).replace(/\./, ',');
break;
}
return formatString.replace(patt, value);
};

function floatToString(numeric, decimals) {  
var amount = numeric.toFixed(decimals).toString();  
if(amount.match(/^\.\d+/)) {return "0"+amount; }
else { return amount; }
};

}
(function ($, document, undefined) {
$.modresize=function(){
$('.modmedia-image').each(function() {
var image_width = $(this).attr('data-width');
var image_height = $(this).attr('data-height');
var ratio = Math.round(($(this).width() / image_width) * 1000) / 1000;
if(ratio <0.5){ ratio_resize=0.5;}else{ ratio_resize=ratio;}
$(this).parent().find('.lookbook-item').each(function() {
$(this).width($(this).attr('data-width') * ratio_resize);
$(this).height($(this).attr('data-height') * ratio_resize);
var top = Math.round($(this).attr('data-top') / (image_height) * 1000) / 1000;
var left = Math.round($(this).attr('data-left') / (image_width) * 1000) / 1000;
$(this).css({
top: top * ratio * image_height,
left: left * ratio * image_width
});
});
});
};
var flexslider_container=$('<div  id="lookbook-modmedia-<?php echo $lookbook->id; ?>" class="lookbook-modmedia-container"></div>');
var flexsliderwr=$('<div class="lookbook-modmedia-flexslider"></div>');
var ul_pages=$('<ul class="lookbook_slides"></ul>');
$('#lookbook-modmedia-container').append(flexslider_container);
var list_pages='';
if(pages.length == 0){
ul_pages.append('<li>There is no pages in this Lookbook.</li>');
}else{
$.each(pages, function(page_index){
list_pages=$('<li></li>');
var page_image='';
var page_image_width=0;
var page_image_height=0;
if(pages[page_index].image['full_path']!== undefined){
page_image='<img src="' + pages[page_index].image['full_path'] + '" alt="Main image"/>';
page_image_width=pages[page_index].image['image_width'];
page_image_height=pages[page_index].image['image_height'];
}
var modimage=$('<div class="modmedia-image" data-width="'+page_image_width + '" data-height="' + page_image_height + '" style="max-width:'+page_image_width + 'px;">'+page_image+'</div>');
var hovers=$('<div class="hovers"></div>');
var products=pages[page_index].products;
var page=pages[page_index].page;
$.each(products, function(prod){
var display_image='';
if(products[prod].show_image == 1){
display_image='<div class="modmedia-lookbook-product-image"><img style="max-width:150px; max-height:150px;" src="' + products[prod].image + '" alt="' + products[prod].handle + '"/></div>';
}
var display_name_price='';
if(products[prod].show_title == 1){
display_name_price='<div class="name"></div>';
}
if(products[prod].show_price == 1){
display_name_price+='<div class="price"></div>';
}
if(display_name_price!=''){
display_name_price='<div class="name-price">'+display_name_price+'</div>';
}
var pin='<a href="'+products[prod].url+'" target="'+products[prod].link_target+'"><img src="'+products[prod].icon+'" alt="+"/></a>';
hovers.append('<div id="pin-'+page+'-'+products[prod].item+'" data-container="modmedia-lookbook-item-hover" class="lookbook-item pin-'+page+'-'+products[prod].item+'" data-item="'+prod+'" data-width="'+products[prod].icon_width+'" data-height="'+products[prod].icon_height+'" data-id="'+products[prod].item+'" data-top="'+products[prod].top+'" data-left="'+products[prod].left+'" data-image="'+products[prod].image+'" data-name="" data-linkto="'+products[prod].linkto+'" data-link_target="'+products[prod].link_target+'" data-url="'+products[prod].url+'" data-handle="'+products[prod].handle+'" data-price="" data-custom_title="'+products[prod].custom_title+'" style="top:'+products[prod].top+'px; left:'+products[prod].left+'px;">'+pin+'<div id="modhover-pin-'+page+'-'+products[prod].item+'" class="modhover-pin-'+page+'-'+products[prod].item+' modmedia-lookbook-item-hover ml-item-hover-<?php echo $lookbook->shopify_page_handle; ?>">'+display_image+display_name_price+'</div>'+'</div>');
});
modimage.append(hovers);
list_pages.append(modimage);                      
ul_pages.append(list_pages);              
});
}
flexslider_container.append(flexsliderwr);
flexsliderwr.append(ul_pages);
$('.lookbook-modmedia-flexslider').flexslider({
namespace: "fflex-",
selector: ".lookbook_slides > li",   
animation: "<?php echo isset($theme_settings->slider_animation) ? $theme_settings->slider_animation : "slide"; ?>",
animationLoop: <?php echo isset($theme_settings->slider_animation_loop) ? ($theme_settings->slider_animation_loop == 1) ? "true" : "false" : "true"; ?>,
slideshow: <?php echo isset($theme_settings->slider_slideshow) ? ($theme_settings->slider_slideshow == 1) ? "true" : "false" : "false"; ?>,
<?php if (isset($theme_settings->slider_direction)) { ?>
    direction: "<?php echo ($theme_settings->slider_direction) ? $theme_settings->slider_direction : "horizontal"; ?>",
<?php } ?>
<?php if (isset($theme_settings->slider_reverse)) { ?>
    reverse: <?php echo ($theme_settings->slider_reverse == 1) ? "true" : "false"; ?>,
<?php } ?>
<?php if (isset($theme_settings->slider_smooth_height)) { ?>
    smoothHeight: <?php echo ($theme_settings->slider_smooth_height == 1) ? "true" : "false"; ?>,
<?php } ?>
<?php if (isset($theme_settings->slider_speed)) { ?>
    slideshowSpeed: <?php echo ($theme_settings->slider_speed != "") ? $theme_settings->slider_speed : 7000; ?>,
<?php } ?>
<?php if (isset($theme_settings->slider_anim_speed)) { ?>
    animationSpeed: <?php echo ($theme_settings->slider_anim_speed != "") ? $theme_settings->slider_anim_speed : 600; ?>,
<?php } ?>
<?php if (isset($theme_settings->slider_control_nav)) { ?>
    controlNav: <?php echo ($theme_settings->slider_control_nav == 1) ? "true" : "false"; ?>,
<?php } ?>
<?php if (isset($theme_settings->slider_dir_nav)) { ?>
    directionNav: <?php echo ($theme_settings->slider_dir_nav == 1) ? "true" : "false"; ?>,
<?php } ?>
<?php if (isset($theme_settings->slider_dir_nav_prev)) { ?>
    prevText: "<?php echo $theme_settings->slider_dir_nav_prev; ?>",
<?php } ?>
<?php if (isset($theme_settings->slider_dir_nav_next)) { ?>
    nextText: "<?php echo $theme_settings->slider_dir_nav_next; ?>",
<?php } ?>
<?php if(isset($theme_settings->slider_carousel) && $theme_settings->slider_carousel == 1){?>
                itemWidth:<?php echo ($theme_settings->slider_car_width);?>,
                itemMargin:<?php echo ($theme_settings->slider_car_margin);?>,
                minItems:<?php echo ($theme_settings->slider_car_min);?>,
                maxItems:<?php echo ($theme_settings->slider_car_max);?>,
                move:<?php echo ($theme_settings->slider_car_move);?>,
<?php }?>
start:function(){
$(window).resize();
},
before:function(){
$('.modmedia-lookbook-item-hover').hide();                
}
});
$( "#lookbook-modmedia-container img" ).load(function() {
$(window).resize();
}); 
$(window).resize(function() {
$.modresize();
});
$(window).load(function() {
$.modresize();
});
$.check_loaded=function(e, obj, value){
var item=obj.attr('id');
var hover_product=$('.modhover-'+item);
if(obj.attr('data-loaded')=='1'){
$.positionBox(e, obj, hover_product);
}else{
if(hover_product.find('.name')){
if(value=='name'){
obj.attr('nameloaded',1);
}
}else{
obj.attr('nameloaded',1);
}
if(hover_product.find('.price')){
if(value=='price'){
obj.attr('priceloaded',1);
}
}else{
obj.attr('priceloaded',1);
}
if(obj.attr('nameloaded') == 1 && obj.attr('priceloaded') == 1){
obj.removeAttr('nameloaded');
obj.removeAttr('priceloaded');
obj.removeAttr('imageloaded');
obj.attr('data-loaded',1);
obj.parent().find('.loading-look-product').remove();
$.positionBox(e, obj, hover_product);
}
}
};
/* shreeji add code start for mobile device */
    $('.lookbook-item').click(function() {
        if($("#"+$(this).attr('id')+' a').attr('href')==""||$("#"+$(this).attr('id')+' a').attr('href')=="undefined"){
            return false;
        } else{
            var item=$(this).attr('id');
            var link=$('<a href ="'+$(this).attr('data-url')+'" target="'+$(this).attr('data-link_target')+'"></a>');
            link[0].click();
            return true;
        }

    });   
/* shreeji add code end for mobile device */
/* Mobile arrow turn off/on */
var browserWidth = $(window).width();
if (browserWidth <= 767  ){
    <?php if (isset($theme_settings->slider_mobile_nav_use_image)) {
       if ($theme_settings->slider_mobile_nav_use_image == 0) { ?>
        console.log("Do not display arrows");
        $('.lookbook-modmedia-container .fflex-direction-nav a').css('display','none');
      <?php }else{?>
         console.log("Display arrows");
         $('.lookbook-modmedia-container .fflex-direction-nav a').css('display','block');
        <?php }?>
      <?php }?>
}  
$('.lookbook-item').mouseover(function(e) {
var thise=e;
var thisobj=$(this);
var item=$(this).attr('id');
var hover_product=$('.modhover-'+item);
if($(this).find('.modhover-'+item).length>0){
if($('.modhover-'+item).length>1){
$('.modhover-'+item).eq(1).remove();
hover_product=$('.modhover-'+item);
}
hover_product.appendTo($('body'));
}
$('.modmedia-lookbook-item-hover:not('+'.modhover-'+item+')').fadeOut();
if($(this).attr('data-handle')!=''){
if($(this).attr('data-name')==''){
var loadingtxt=$('<div class="loading-look-product">Loading...</div>');
$(this).parent().append(loadingtxt);
loadingtxt.css({
top:$(this).position().top + 20,
left:$(this).position().left + 20,
position:'absolute'
});
hover_product.find('.name').hide();
hover_product.find('.price').hide();
var handle=$(this).attr('data-handle');
$.ajax({
url:'<?php echo '/products/'; ?>'+handle+'.js',
type:'GET',
dataType:'json',
success:function(data){
if(hover_product.find('.name')){
if($('#'+item).attr('data-custom_title') != ''){
$('#'+item).attr('data-name',$('#'+item).attr('data-custom_title'));
}else{
$('#'+item).attr('data-name',data.title);
}
hover_product.find('.name').text($('#'+item).attr('data-name'));
hover_product.find('.name').fadeIn();
$.check_loaded(e, thisobj, 'name');
}
if(hover_product.find('.price')){
var price='';
if(data.price_varies){
price='<span class="from">from:</span> <span class="price_value">'+Shopify.formatMoney(data.price_min,modlookbook_money_with_currency_format)+'</span>';
}else{
price='<span class="price_value">'+Shopify.formatMoney(data.price,modlookbook_money_with_currency_format)+'</span>';
}
if(data.compare_at_price > 0){
if(data.compare_at_price_varies){
if(data.price_min != data.compare_at_price_min || data.price_max != data.compare_at_price_max){
price=price+'<em class="compare_at_price">'+Shopify.formatMoney(data.compare_at_price_min,modlookbook_money_with_currency_format)+'</em>';
}
}else{
if(data.price != data.compare_at_price){
price=price+'<em class="compare_at_price">'+Shopify.formatMoney(data.compare_at_price,modlookbook_money_with_currency_format)+'</em>';
}
}
}
$('#'+item).attr('data-price',price);
hover_product.find('.price').html(price);
hover_product.find('.price').fadeIn();
$.check_loaded(e, thisobj, 'price');
}
if($('#'+item).attr('data-linkto')=="1"){
$('#'+item).attr('data-url',data.url);
$('#'+item+' a').attr('href',data.url);
}
hover_product.wrapInner('<a href="'+data.url+'" target="'+$('#'+item).attr('data-link_target')+'"></a>');
$(window).resize();
},
error:function(x,e){
thisobj.parent().find('.loading-look-product').remove();
}
});
}else{
hover_product.find('.name').html($(this).attr('data-name'));
hover_product.find('.price').html($(this).attr('data-price'));
$(window).resize();
}                
}else{

                if($(this).find('.modhover-'+item).length>0){
                    if($('.modhover-'+item).length>1){
                        $('.modhover-'+item).eq(1).remove();
                        hover_product=$('.modhover-'+item);
                    }
                    hover_product.appendTo($('body'));
                }
                if($(this).attr('data-custom_title')!=''){              
                    hover_product.find('.name').hide();
                    hover_product.find('.price').hide();
                    hover_product.find('.name').text($(this).attr('data-custom_title'));
                    hover_product.find('.name').fadeIn();
                    thisobj.attr('data-loaded',1);
                }
                /*added by shree for iphone*/
                if($(this).attr('data-handle')==''){
                  if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) 
                      window.location=$(this).attr('data-url');
                }
                /*added by shree for iphone*/
            }
$.check_loaded(e, thisobj, '');
});
$.positionBox=function(e, obj, hover_product){
hover_product.css({
visibility: "hidden",
display: "block"
});
var win = $(window);
var viewport = {
top : win.scrollTop(),
left : win.scrollLeft()
};
viewport.right = viewport.left + win.width();
viewport.bottom = viewport.top + win.height();

var left=e.pageX+ (obj.outerWidth()/2);
var top=e.pageY + (obj.outerHeight()/2);

hover_product.css({
position:'absolute',
top: top,
left: left                
});

var bounds = hover_product.offset();
bounds.right = bounds.left + hover_product.outerWidth();
bounds.bottom = bounds.top + hover_product.outerHeight();
if(bounds.right > win.width()){
left=e.pageX - (bounds.right-viewport.right); 
}

if(bounds.bottom > win.height()){
newtop=e.pageY - hover_product.outerHeight() - (obj.outerHeight()/2);
if(newtop > viewport.top){
top=newtop;
}
}
hover_product.css({
position:'absolute',
top: top,
left: left
});
hover_product.css({
visibility: "visible",
display: "block"
});
hover_product.fadeIn();
obj.mousemove();
};
$('.lookbook-item').mousemove(function(e){
var item=$(this).attr('id');
var hover_product=$('.modhover-'+item);

var win = $(window);
var viewport = {
top : win.scrollTop(),
left : win.scrollLeft()
};
viewport.right = viewport.left + win.width();
viewport.bottom = viewport.top + win.height();

var left=e.pageX+ ($(this).outerWidth()/2);
var top=e.pageY + ($(this).outerHeight()/2);

hover_product.css({
position:'absolute',
top: top,
left: left
});

var bounds = hover_product.offset();
bounds.right = bounds.left + hover_product.outerWidth();
bounds.bottom = bounds.top + hover_product.outerHeight();
if(bounds.right > win.width()){
left=e.pageX - (bounds.right-viewport.right); 
}

if(bounds.bottom > win.height()){
newtop=e.pageY - hover_product.outerHeight() - ($(this).outerHeight()/2);
if(newtop > viewport.top){
top=newtop;
}
}
hover_product.css({
position:'absolute',
top: top,
left: left
});
});
var timers={};
$('.lookbook-item').mouseout(function() {
var item=$(this).attr('id');
var hover_product=$('.modhover-'+item);
timers['i-'+item]=setTimeout(function(){ hover_product.fadeOut();},200);
});
$('.modmedia-lookbook-item-hover').mouseover(function(){
var id=$(this).attr('id');
$('.'+id).fadeIn(0);
var item=id.replace('modhover-','');
if((typeof timers['i-'+item]) !== 'undefined'){
clearTimeout(timers['i-'+item]);
}
$('.modmedia-lookbook-item-hover:not('+'.'+id+')').fadeOut();            
});
$('.modmedia-lookbook-item-hover').mouseout(function(){
var id=$(this).attr('id');
var item=id.replace('modhover-','');
timers['i-'+item]=setTimeout(function(){ $('#'+id).fadeOut();},100);
});
})(modlookbookjQuery, document);
};
set_modlookbookjquery();
}