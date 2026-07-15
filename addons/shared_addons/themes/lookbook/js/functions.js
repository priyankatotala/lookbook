
function show_error(jqXHR, status, errorThrown) {
    if(status=='error'){ status='danger';}
    if (jqXHR.getAllResponseHeaders()) {
        $.magnificPopup.open({
            items: {
                src: '<div class="alert-popup alert-error mfp-with-anim"><strong>' + status + '!</strong> ' + errorThrown + '</div>'
            },
            type: 'inline',
            removalDelay: 500,
            mainClass: 'mfp-3d-unfold',
            midClick: true,
            callbacks: {
                beforeOpen: function() {
                    this.st.mainClass = 'mfp-3d-unfold';
                }
            },
        });
    }
}
function show_message(type, message, modal) {
    if(type=='error'){ type='danger';}
    $.magnificPopup.open({
        items: {
            src: '<div class="white-popup alert-popup alert-' + type + ' mfp-with-anim">' + message + '</div>'
        },
        type: 'inline',
        removalDelay: 500,
        mainClass: 'mfp-3d-unfold',
        midClick: true,
        modal: (modal === undefined) ? false : modal,
        callbacks: {
            beforeOpen: function() {
                this.st.mainClass = 'mfp-3d-unfold';
            }
        },
    });
}
function show_message_inline(item, modal) {
    $.magnificPopup.open({
        items: {
            src: item
        },
        type: 'inline',
        removalDelay: 500,
        mainClass: 'mfp-3d-unfold',
        modal: (modal === undefined) ? false : modal,
        midClick: true,
        callbacks: {
            beforeOpen: function() {
                this.st.mainClass = 'mfp-3d-unfold';
            }
        },
    });
}
function deleteImage(input) {
    $('input[name=' + input + ']').val('');
}
function deleteLookBookPage() {
    var url = $('#modal input[name=url]').val();
    var name = $('#modal input[name=verify-delete]').val();
    if (name == 'DELETE') {
        $('.modal-delete').parent().append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
        $.ajax({
            url: url,
            dataType: "json",
            type: "GET",
            success: function(data) {
                if (data.status == 'success') {
                    $('#modal .alert').remove();
                    $('#modal').modal('hide');
                    location.reload();
                } else {
                    $('#modal .alert').remove();
                    $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
                            + '<button type="button" class="close" data-dismiss="alert">×</button>'
                            + '<h4 class="alert-heading">Oh snap! We got an error!</h4>'
                            + '<p>' + data.msg + '</p>'
                            + '</div>');
                    $('.loader').remove();
                }
            },
            error: function(x, e) {
                $('#modal .alert').remove();
                $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
                        + '<button type="button" class="close" data-dismiss="alert">×</button>'
                        + '<h4 class="alert-heading">Oh snap! We got an error!</h4>'
                        + '<p>' + x.responseText + ' : ' + e + '</p>'
                        + '</div>');
                $('.loader').remove();
            }
        });
    } else {
        $('.loader').remove();
        $('#modal .alert').remove();
        $('#modal .modal-body').prepend('<div class="alert alert-block alert-danger fade in">'
                + '<button type="button" class="close" data-dismiss="alert">×</button>'
                + '<h4 class="alert-heading">Oh snap! You got an error!</h4>'
                + '<p>Please type the word DELETE in uppercase if you want to delete this lightbox..</p>'
                + '</div>');
    }
}
function deleteLookBook() {
    var url = $('#modal input[name=url]').val();
    var name = $('#modal input[name=verify-delete]').val();
    if (name == 'DELETE') {
        $('.modal-delete').parent().append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>');
        $.ajax({
            url: url,
            dataType: "json",
            type: "GET",
            success: function(data) {
                if (data.status == 'success') {
                    $('#modal .alert').remove();
                    $('#modal').modal('hide');
                    location.reload();
                } else {
                    $('#modal .alert').remove();
                    $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
                            + '<button type="button" class="close" data-dismiss="alert">×</button>'
                            + '<h4 class="alert-heading">Oh snap! We got an error!</h4>'
                            + '<p>' + data.msg + '</p>'
                            + '</div>');
                    $('.loader').remove();
                }
            },
            error: function(x, e) {
                $('#modal .alert').remove();
                $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
                        + '<button type="button" class="close" data-dismiss="alert">×</button>'
                        + '<h4 class="alert-heading">Oh snap! We got an error!</h4>'
                        + '<p>' + x.responseText + ' : ' + e + '</p>'
                        + '</div>');
                $('.loader').remove();
            }
        });
    } else {
        $('.loader').remove();
        $('#modal .alert').remove();
        $('#modal .modal-body').prepend('<div class="alert alert-block alert-error fade in">'
                + '<button type="button" class="close" data-dismiss="alert">×</button>'
                + '<h4 class="alert-heading">Oh snap! You got an error!</h4>'
                + '<p>Please type the word DELETE in uppercase if you want to delete this lightbox..</p>'
                + '</div>');
    }
}
(function($) {
    $(function() {
        $('a.delete').click(function() {
            var url = $(this).attr('data-url');
            $('#modal input[name=url]').val(url);
        });        
    });
})(jQuery);