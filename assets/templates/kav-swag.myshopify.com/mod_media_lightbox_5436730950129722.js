if(typeof myAppJavaScriptmodLookbook != "function"){
var myAppJavaScriptmodLookbook = function($) {
var pages = [    
        {
        page: '1',
        image: {"file_name":"lookbook-modmedia-6434-page-68288.jpg","full_path":"https:\/\/cdn.shopify.com\/s\/files\/1\/0096\/9513\/3754\/t\/14\/assets\/lookbook-modmedia-6434-page-68288.jpg?2324","image_width":1000,"image_height":750,"file_ext":".jpg"},
        products: [
                        {
                item:1,
                top:541.50,
                left:489.00,
                link_target:'_self',
                linkto:1,
                url:"",
                custom_title:"",
                show_image:"1",
                show_title:"1",
                show_price:"1",
                icon:"https://cdn.shopify.com/s/files/1/0096/9513/3754/t/14/assets/lb_mm_circle_251162490_2552552550.png?2353",
                icon_width: "26",
                icon_height: "26",
                image: "https://cdn.shopify.com/s/files/1/0096/9513/3754/products/2291177_RTY16239_2.jpg?v=1562249923",
                handle: "egg-chair-reproduction"
                }
                        ]}
        ,        {
        page: '2',
        image: {"file_name":"lookbook-modmedia-6434-page-68290.jpg","full_path":"https:\/\/cdn.shopify.com\/s\/files\/1\/0096\/9513\/3754\/t\/14\/assets\/lookbook-modmedia-6434-page-68290.jpg?2383","image_width":1000,"image_height":750,"file_ext":".jpg"},
        products: [
                        {
                item:1,
                top:483.72,
                left:823.90,
                link_target:'_self',
                linkto:1,
                url:"",
                custom_title:"",
                show_image:"1",
                show_title:"1",
                show_price:"1",
                icon:"https://cdn.shopify.com/s/files/1/0096/9513/3754/t/14/assets/lb_mm_circle_251162490_2552552550.png?2353",
                icon_width: "26",
                icon_height: "26",
                image: "https://cdn.shopify.com/s/files/1/0096/9513/3754/products/2286802_Eames_Lounge_Chair_Black_Rosewood.jpg?v=1562248701",
                handle: "lounge-chair-ottoman-reproduction"
                }
                ,                {
                item:2,
                top:96.40,
                left:488.67,
                link_target:'_self',
                linkto:1,
                url:"",
                custom_title:"",
                show_image:"1",
                show_title:"1",
                show_price:"1",
                icon:"https://cdn.shopify.com/s/files/1/0096/9513/3754/t/14/assets/lb_mm_circle_251162490_2552552550.png?2353",
                icon_width: "26",
                icon_height: "26",
                image: "https://cdn.shopify.com/s/files/1/0096/9513/3754/products/2291715_CL2032J_1.png?v=1562250178",
                handle: "rope-single-pendant-light"
                }
                        ]}
        ,        {
        page: '3',
        image: {"file_name":"lookbook-modmedia-6434-page-68291.jpg","full_path":"https:\/\/cdn.shopify.com\/s\/files\/1\/0096\/9513\/3754\/t\/14\/assets\/lookbook-modmedia-6434-page-68291.jpg?2396","image_width":1000,"image_height":750,"file_ext":".jpg"},
        products: [
                        {
                item:1,
                top:555.07,
                left:753.68,
                link_target:'_self',
                linkto:1,
                url:"",
                custom_title:"",
                show_image:"1",
                show_title:"1",
                show_price:"1",
                icon:"https://cdn.shopify.com/s/files/1/0096/9513/3754/t/14/assets/lb_mm_circle_251162490_2552552550.png?2353",
                icon_width: "26",
                icon_height: "26",
                image: "https://cdn.shopify.com/s/files/1/0096/9513/3754/products/product-image-741422259.jpg?v=1551307843",
                handle: "mid-century-modern-design-end-table"
                }
                        ]}
        ,        {
        page: '4',
        image: {"file_name":"lookbook-modmedia-6434-page-68289.jpg","full_path":"https:\/\/cdn.shopify.com\/s\/files\/1\/0096\/9513\/3754\/t\/14\/assets\/lookbook-modmedia-6434-page-68289.jpg?2368","image_width":1000,"image_height":750,"file_ext":".jpg"},
        products: [
                        {
                item:1,
                top:500.71,
                left:262.17,
                link_target:'_self',
                linkto:1,
                url:"",
                custom_title:"",
                show_image:"1",
                show_title:"1",
                show_price:"1",
                icon:"https://cdn.shopify.com/s/files/1/0096/9513/3754/t/14/assets/lb_mm_circle_237102710_2552552550.png?2315",
                icon_width: "26",
                icon_height: "26",
                image: "https://cdn.shopify.com/s/files/1/0096/9513/3754/products/2287559_SUN_3465_1000x1045_5f37fd49-20f3-4693-8d95-3c18e80fd466.jpg?v=1562248920",
                handle: "industrial-wood-cart-coffee-table"
                }
                        ]}
        ];
var css = "#lookbook-modmedia-6434 .fflex-control-paging li a { } #lookbook-modmedia-6434 .fflex-control-paging li a:hover {} #lookbook-modmedia-6434 .fflex-control-paging li a.fflex-active {}";
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
var flexslider_container=$('<div  id="lookbook-modmedia-6434" class="lookbook-modmedia-container"></div>');
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
hovers.append('<div id="pin-'+page+'-'+products[prod].item+'" data-container="modmedia-lookbook-item-hover" class="lookbook-item pin-'+page+'-'+products[prod].item+'" data-item="'+prod+'" data-width="'+products[prod].icon_width+'" data-height="'+products[prod].icon_height+'" data-id="'+products[prod].item+'" data-top="'+products[prod].top+'" data-left="'+products[prod].left+'" data-image="'+products[prod].image+'" data-name="" data-linkto="'+products[prod].linkto+'" data-link_target="'+products[prod].link_target+'" data-url="'+products[prod].url+'" data-handle="'+products[prod].handle+'" data-price="" data-custom_title="'+products[prod].custom_title+'" style="top:'+products[prod].top+'px; left:'+products[prod].left+'px;">'+pin+'<div id="modhover-pin-'+page+'-'+products[prod].item+'" class="modhover-pin-'+page+'-'+products[prod].item+' modmedia-lookbook-item-hover ml-item-hover-kav-lookbook">'+display_image+display_name_price+'</div>'+'</div>');
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
animation: "slide",
animationLoop: true,
slideshow: false,
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
url:'/products/'+handle+'.js',
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