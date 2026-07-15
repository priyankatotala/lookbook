/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

(function($) {
    $(function() {
        $('button.change_status').click(function(){
            var btn=$(this);
            $(this).button('loading');
            var url=$(this).attr('data-url');
            var status=1;
            if($(this).hasClass('active')){
                status=0;
            }
            var data={
                'status':status
            };
            $.ajax({
                    url: url,
                    dataType: "json",
                    type: "POST",
                    data: data,
                    success: function(data) {
                        if(data.status=='success'){
                            btn.button('reset');
                            $('button.change_status').each(function(){
                                $(this).removeClass('active btn-success');
                                $(this).addClass('btn-inverse');
                                $(this).html('<i class="icon-eye-close icon-white"></i> Off');
                            });                            
                            if(status==1){
                                btn.removeClass('btn-inverse');
                                btn.addClass('active btn-success');
                                btn.html('<i class="icon-eye-open icon-white"></i> On');
                            }else{
                                btn.removeClass('active btn-success');
                                btn.addClass('btn-inverse');
                                btn.html('<i class="icon-eye-close icon-white"></i> Off');
                            }
                            location.reload();
                        }else{
                            /*$('#modal .alert').remove();
                            $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
                        +'<button type="button" class="close" data-dismiss="alert">×</button>'
                        +'<h4 class="alert-heading">Oh snap! We got an error!</h4>'
                        +'<p>'+data.msg+'</p>'
                      +'</div>');                                */
                        }
                    },
                    error: function(x, e) {
                        /*$('#modal .alert').remove();
                $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
        +'<button type="button" class="close" data-dismiss="alert">×</button>'
        +'<h4 class="alert-heading">Oh snap! We got an error!</h4>'
        +'<p>'+x.responseText+' : '+e+'</p>'
      +'</div>');*/
                    }
                });
            return false;
        });
        $('a.delete').click(function() {
            var url = $(this).attr('data-url');
            $('#modal input[name=url]').val(url);
        });
        $('a.modal-cancel').click(function() {
            $('#modal').modal('hide');
            return false;
        });
        $('a.modal-delete').click(function() {
            var url = $('#modal input[name=url]').val();
            var name = $('#modal input[name=verify-delete]').val();
            if(name=='DELETE'){
                $.ajax({
                    url: url,
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        if(data.status=='success'){
                            $('#modal .alert').remove();
                            $('#modal').modal('hide');
                            location.reload();
                        }else{
                            $('#modal .alert').remove();
                            $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
                        +'<button type="button" class="close" data-dismiss="alert">×</button>'
                        +'<h4 class="alert-heading">Oh snap! We got an error!</h4>'
                        +'<p>'+data.msg+'</p>'
                      +'</div>');                                
                        }
                    },
                    error: function(x, e) {
                        $('#modal .alert').remove();
                $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
        +'<button type="button" class="close" data-dismiss="alert">×</button>'
        +'<h4 class="alert-heading">Oh snap! We got an error!</h4>'
        +'<p>'+x.responseText+' : '+e+'</p>'
      +'</div>');
                    }
                });
            }else{
                $('#modal .alert').remove();
                $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
        +'<button type="button" class="close" data-dismiss="alert">×</button>'
        +'<h4 class="alert-heading">Oh snap! You got an error!</h4>'
        +'<p>Please type the word DELETE in uppercase if you want to delete this lightbox..</p>'
      +'</div>');                
            }
            return false;
        });
    });
})(jQuery);
            