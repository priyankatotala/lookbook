if(typeof modlookbooks != "array"){
var modlookbooks = [    
    {  
        id:'3339',
        
        title:'DAMASCUS%20x%20PKSOUND%20COLLABORATION',
        feature_image:{"file_name":"lookbook-modmedia-3339-feature.jpg","full_path":"https:\/\/cdn.shopify.com\/s\/files\/1\/0823\/2953\/t\/2\/assets\/lookbook-modmedia-3339-feature.jpg?11057647093854776144","image_width":2000,"image_height":1335,"file_ext":".jpg"},
        handle:'damascus-x-pksound-collaboration',
        theme: 'list',
        theme_settings:'',
        pages:[
                          ],
              pages_count:13,
              max_width:20}
    ,    {  
        id:'3313',
        
        title:'DAMASCUS%20x%20DIESELBOY%20DTHWLVS',
        feature_image:{"file_name":"lookbook-modmedia-3313-feature.jpg","full_path":"https:\/\/cdn.shopify.com\/s\/files\/1\/0823\/2953\/t\/2\/assets\/lookbook-modmedia-3313-feature.jpg?6124477287171398753","image_width":3273,"image_height":2184,"file_ext":".jpg"},
        handle:'dthwlvs',
        theme: 'list',
        theme_settings:'',
        pages:[
                          ],
              pages_count:14,
              max_width:20}
    ,    {  
        id:'4751',
        
        title:'DAMASCUS%20X%20PALEHORSE%20-%20SAMSARA%20',
        feature_image:{"file_name":"lookbook-modmedia-4751-feature.jpg","full_path":"https:\/\/cdn.shopify.com\/s\/files\/1\/0823\/2953\/t\/2\/assets\/lookbook-modmedia-4751-feature.jpg?17148244207192332777","image_width":2000,"image_height":1722,"file_ext":".jpg"},
        handle:'damascus-x-palehorse-samsara',
        theme: 'list',
        theme_settings:'',
        pages:[
                          ],
              pages_count:11,
              max_width:20}
    ];
    
    
    var css = "#lookbook-modmedia-4751 .fflex-control-paging li a { } #lookbook-modmedia-4751 .fflex-control-paging li a:hover {} #lookbook-modmedia-4751 .fflex-control-paging li a.fflex-active {}";
var style = document.createElement('style');

style.type = 'text/css';
if (style.styleSheet){
style.styleSheet.cssText = css;
} else {
style.appendChild(document.createTextNode(css));
}
document.getElementsByTagName("head")[0].appendChild(style);


var myAppJavaScriptmodLookbook = function($) {
if((typeof Shopify) == 'undefined'){
if ((typeof Shopify) == 'undefined') {
  var Shopify = {};
}
/* ---------------------------------------------------------------------------
* Money format handler themes/list/list.php
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
    $('#lookbook-modmedia-container').html("");
        var ul=$('<ul></ul>');
        $('#lookbook-modmedia-container').append(ul);
        $('#lookbook-modmedia-container').addClass('lookbook-modmedia-container');
        $.each(modlookbooks, function(index){
              var list=$('<li></li>');
              var feature_image=modlookbooks[index].feature_image;
              var image='';
              if(feature_image.full_path !== undefined){
                image='<img data-src="'+feature_image.full_path+'" alt="'+decodeURIComponent(modlookbooks[index].title)+'" class="feature" style=""/>';
              }else{
                image=decodeURIComponent(modlookbooks[index].title);
              }
              if(modlookbooks[index].theme == "lightbox-slideshow" || modlookbooks[index].theme == "lightbox-list"){
              var lookbook_id=modlookbooks[index].id;
                var link="<div class='lookbook-list-item-container "+modlookbooks[index].handle+"'><a href='#lookbook-modmedia-"+modlookbooks[index].id+"' class='lookbook-magnific-lightbox'>"+image+"</a></div>";
                var slideshow_container=$('<div id="lookbook-modmedia-'+modlookbooks[index].id+'" class="modlbx-mfp-with-anim modlbx-mfp-hide lookbook-modmedia-popup '+modlookbooks[index].handle+'" style="max-width:'+modlookbooks[index].max_width+'px; background:'+modlookbooks[index].theme_settings.content_background+';" data-display_effect="'+modlookbooks[index].theme_settings.display_effect+'" data-content_bg="'+modlookbooks[index].theme_settings.content_background+'" data-opacity="'+modlookbooks[index].theme_settings.opacity+'" data-overlay_bg="'+modlookbooks[index].theme_settings.overlay_background+'"></div>');
                var flexslider_container=$('<div class="lookbook-modmedia-container"></div>');
                var flexslider=$('<div class="lookbook-modmedia-flexslider" data-index="'+index+'"></div>');
                var ul_pages=$('<ul class="lookbook_slides '+modlookbooks[index].theme+'"></ul>');
                var pages=modlookbooks[index].pages;
                var list_pages='';
                $.each(pages, function(page_index){
                      list_pages=$('<li></li>');
                      var page_image='';
                      var page_image_width=0;
                      var page_image_height=0;
                      if(pages[page_index].image['full_path']!== undefined){
                        page_image='<img data-src="' + pages[page_index].image['full_path'] + '" class="page_image" alt="Main image"/>';
                        page_image_width=pages[page_index].image['image_width'];
                        page_image_height=pages[page_index].image['image_height'];
                      }
                      var modimage=$('<div class="modmedia-image" data-width="'+page_image_width + '" data-height="' + page_image_height + '">'+page_image+'</div>');
                      var hovers=$('<div class="hovers"></div>');
                      var products=pages[page_index].products;
                      var page=pages[page_index].page;
                      $.each(products, function(prod){
                        var display_image='';
                        if(products[prod].show_image == 1){
                            display_image='<div class="modmedia-lookbook-product-image"><img style="max-width:150px; max-height:150px;" data-src="' + products[prod].image + '" alt="' + products[prod].handle + '" class="page_product_image"/></div>';
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
                        var pin='<a href="'+products[prod].url+'" target="'+products[prod].link_target+'"><img data-src="'+products[prod].icon+'" alt="+" class="page_icon_image"/></a>';
                        hovers.append('<div id="pin-'+page+'-'+products[prod].item+'" data-container="modmedia-lookbook-item-hover" class="lookbook-item pin-'+page+'-'+products[prod].item+'" data-item="'+prod+'" data-width="'+products[prod].icon_width+'" data-height="'+products[prod].icon_height+'" data-id="'+products[prod].item+'" data-top="'+products[prod].top+'" data-left="'+products[prod].left+'" data-image="'+products[prod].image+'" data-name="" data-linkto="'+products[prod].linkto+'" data-link_target="'+products[prod].link_target+'" data-url="'+products[prod].url+'" data-handle="'+products[prod].handle+'" data-price="" data-custom_title="'+products[prod].custom_title+'" style="top:'+products[prod].top+'px; left:'+products[prod].left+'px;">'+pin+'<div id="modhover-pin-'+page+'-'+products[prod].item+'" class="modhover-pin-'+page+'-'+products[prod].item+' modmedia-lookbook-item-hover ml-item-hover-'+modlookbooks[index].handle+'">'+display_image+display_name_price+'</div>'+'</div>');
                      });
                      modimage.append(hovers);
                      list_pages.append(modimage);                      
                      ul_pages.append(list_pages);                      
                });
                if(modlookbooks[index].theme == "lightbox-slideshow"){
                    flexslider_container.append(flexslider);
                    slideshow_container.append(flexslider_container);
                    flexslider.append(ul_pages);                    
                }else{
                    slideshow_container.append(flexslider_container);
                    flexslider_container.append(ul_pages);                    
                }
                list.append(link);                
                list.append(slideshow_container);
              }else{
                var url='/pages/'+modlookbooks[index].handle;
                var link="";
                var pages_count=modlookbooks[index].pages_count;
                if(pages_count > 0){
                    link= "<a href='"+url+"'>"+image+"</a>";
                }else{
                    link=image;
                }
                console.log(pages);
                list.append(link);
              }
              if(pages_content[modlookbooks[index].handle]!== undefined){
                list.append(pages_content[modlookbooks[index].handle]);
              }
              ul.append(list);              
        });
        var total_feature_images=$('img.feature').length;
        var total_page_images=$('img.page_image').length;
        var loaded_feature_images=0;
        var loaded_page_images=0;
        var images_loader=[];
        $.fn.load_icon_products_images=function(){
            $('img.page_icon_image').each(function(){
                $(this).load(function () {
                      
                }).error(function () {
                }).attr('src', $(this).attr('data-src'));
            });
            $('img.page_product_image').each(function(){
                $(this).load(function () {
                      
                }).error(function () {
                }).attr('src', $(this).attr('data-src'));
            });
        }
        $.fn.load_content_images=function(){
            $('img.page_image').each(function(){
                $(this).load(function () {
                    loaded_page_images=loaded_page_images+1;
                    if(loaded_page_images==total_page_images){
                        $.fn.load_icon_products_images();
                     }                
                }).error(function () {
                loaded_page_images=loaded_page_images+1;
                    if(loaded_page_images==total_page_images){
                        $.fn.load_icon_products_images();
                     }
                }).attr('src', $(this).attr('data-src'));
            });
        }
        $.fn.load_feature_images=function(){
            $('img.feature').each(function(){
                $(this).load(function () {
                     loaded_feature_images=loaded_feature_images+1;
                     if(loaded_feature_images==total_feature_images){
                        $.fn.load_content_images();
                     }
                }).error(function () {
                    loaded_feature_images=loaded_feature_images+1;
                    if(loaded_feature_images==total_feature_images){
                        $.fn.load_content_images();
                     }
                }).attr('src', $(this).attr('data-src'));
            });
        };    
        $.fn.load_feature_images();
        $.fn.load_content_images();
        $(window).load(function() {
                    $('#lookbook-modmedia-3339 .lookbook-modmedia-flexslider').flexslider({
                namespace: "fflex-",
                multipleKeyboard: true,
                selector: ".lookbook_slides > li",
                useCSS: false,
                animation: "slide",
                animationLoop: true,
                slideshow: false,
                                                                                                                                                                                start: function() {
                    $(window).resize();                
                },
                added: function() {
                    $(window).resize();                
                },
                change: function() {
                    $(window).resize();                
                },
                before:function(){
                    $('.modmedia-lookbook-item-hover').hide();
                }
            });
                    $('#lookbook-modmedia-3313 .lookbook-modmedia-flexslider').flexslider({
                namespace: "fflex-",
                multipleKeyboard: true,
                selector: ".lookbook_slides > li",
                useCSS: false,
                animation: "slide",
                animationLoop: true,
                slideshow: false,
                                                                                                                                                                                start: function() {
                    $(window).resize();                
                },
                added: function() {
                    $(window).resize();                
                },
                change: function() {
                    $(window).resize();                
                },
                before:function(){
                    $('.modmedia-lookbook-item-hover').hide();
                }
            });
                    $('#lookbook-modmedia-4751 .lookbook-modmedia-flexslider').flexslider({
                namespace: "fflex-",
                multipleKeyboard: true,
                selector: ".lookbook_slides > li",
                useCSS: false,
                animation: "slide",
                animationLoop: true,
                slideshow: false,
                                                                                                                                                                                start: function() {
                    $(window).resize();                
                },
                added: function() {
                    $(window).resize();                
                },
                change: function() {
                    $(window).resize();                
                },
                before:function(){
                    $('.modmedia-lookbook-item-hover').hide();
                }
            });
                });
        var positionMarks = function(){
            $('.modmedia-image').each(function() {
                var image_width = $(this).attr('data-width');
                var image_height = $(this).attr('data-height');
                if($(this).width() > 0){
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
                }else{
                /*setTimeout(function(){
                    positionMarks();
                    },1000); */
                }
            });
        };
              /* shreeji add code start for mobile device */
        $('.lookbook-item').click(function(){
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
        $('a.lookbook-magnific-lightbox').each(function(){
        var id=$(this).attr('href').replace('#','');
            var overlay_bg=$(this).parent().parent().find('.lookbook-modmedia-popup').attr('data-overlay_bg');
            var opacity=$(this).parent().parent().find('.lookbook-modmedia-popup').attr('data-opacity')*1;
            var content_bg=$(this).parent().parent().find('.lookbook-modmedia-popup').attr('data-content_bg');
            var display_effect=$(this).parent().parent().find('.lookbook-modmedia-popup').attr('data-display_effect');
            /*var display_effect='';*/
            if(!display_effect){
                display_effect='modlbx-mfp-3d-unfold';
            }
            $(this).magnificPopup({
                    type: 'inline',
                    removalDelay: 500,
                    mainClass: display_effect,
                    midClick: true,
                    callbacks: {
                        beforeOpen: function() {
                        $(this.bgOverlay).css({background:overlay_bg, opacity:opacity});
                            this.st.mainClass = display_effect;
                        },
                        open: function() {
                        $(window).resize(); 
                            positionMarks();                            
                        }
                    }
                });
            });
                $(window).resize(function() {
                    $('.lookbook-item').show(0);
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
        });        
        $(window).resize();
          $.positionBox=function(e, obj, hover_product){
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
            hover_product.fadeIn();            
        };
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
                }
                $.positionBox(e, obj, hover_product);
            }
        };

        $('.lookbook-item').mouseover(function(e) {

            var thise=e;
            var thisobj=$(this);
            var item=$(this).attr('id');
            var hover_product=$('.modhover-'+item);
            
            $('.modmedia-lookbook-item-hover:not('+'.modhover-'+item+')').fadeOut();
            if($(this).attr('data-linkto')==1)
            {
                if($(this).find('.modhover-'+item).length>0){
                    if($('.modhover-'+item).length>1){
                        $('.modhover-'+item).eq(1).remove();
                        hover_product=$('.modhover-'+item);
                    }
                    hover_product.appendTo($('body'));
                }
                if($(this).attr('data-handle')!='')
                {                
                    if($(this).attr('data-name')=='')
                    {
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
                            error:function(){
                                $('.loading-look-product').remove();
                            }
                        });
                    }
                    else {
                        hover_product.find('.name').html($(this).attr('data-name'));
                        /*hover_product.find('.price').html(Shopify.formatMoney($(this).attr('data-price'),modlookbook_money_with_currency_format));                */
                        hover_product.find('.price').html($(this).attr('data-price'));
                    }                
                }
                $.check_loaded(e, thisobj, '');
            }
            else 
            {
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
                    $.check_loaded(e, thisobj, 'name');
                }
                $('#modhover-'+item).css("visibility","hidden");
                console.log("hidden");
                
            }
        });        
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
            hover_product.fadeIn();
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