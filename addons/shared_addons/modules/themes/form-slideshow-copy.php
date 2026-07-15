<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<header>
    <h1><?php echo anchor('lookbook/edit/' . $lookbook . '/' . sha1($lookbook . $this->config->item('encryption_key')), 'Lookbook') . ' / Edit Theme: ' . $theme->title; ?></h1>    
</header>
<div class="themes">
    <ul class="nav nav-tabs" id="theme-menu">
        <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
        <li><a href="#editor" data-toggle="tab">Editor</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="settings">
            
        </div>
        <div class="tab-pane" id="editor">
            <h2>Text editor</h2>
            <ul class="nav nav-tabs" id="editor-menu">
                <li class="active"><a href="#list" data-toggle="tab">page.lookbook-modmedia-list.liquid</a></li>
                <li><a href="#lookbook" data-toggle="tab">page.lookbook-modmedia-single.liquid</a></li>
            </ul>            
            <div class="tab-content">
                <div class="tab-pane active" id="list">
                    <textarea name='code' id='code' class="text-editor" style="height: 100%;">
                    </textarea>
                </div>
                <div class="tab-pane" id="lookbook">
                    <textarea name='code-single' id='code-single' class="text-editor" style="height: 100%;">
                    </textarea>
                </div>
            </div>            
        </div>
    </div>
    <script>
        var editors = [];
        $(function() {
            var loaded = false;
            $('#theme-menu a, #editor-menu a').click(function(e) {
                $(this).tab('show');
                if (!loaded) {
                    $.ajax({
                        url: '<?php echo site_url('themes/get_code/' . $this->session->userdata('shop')); ?>',
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == 'success') {
                                editors['code'].setValue(data.content_list);
                                editors['code'].refresh();
                                editors['code-single'].setValue(data.content_single);
                                editors['code-single'].refresh();
                                loaded = true;
                            }
                        }
                    });
                } else {
                    e.preventDefault()
                    editors['code'].refresh();
                    editors['code-single'].refresh();
                }                
            });
            $(".text-editor").each(function() {
                var editor = CodeMirror.fromTextArea(document.getElementById($(this).attr('id')), {
                    lineNumbers: true,
                    mode: 'text/html'
                });
                editor.on('change', function(cm, change) {
                    cm.save();
                    //$('input[name=smodlightbox_html_hidden]').val(cm.getValue());
                });
                editors[$(this).attr('id')] = editor;
            });
            //$('#myTab a.active').tab('show');
        })

        //var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('code'));
    </script>
</div>