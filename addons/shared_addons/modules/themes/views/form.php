<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="themes">
    <ul class="nav nav-tabs" id="theme-menu">
        <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
        <li><a href="#editor" data-toggle="tab">Editor</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="settings"></div>
        <div class="tab-pane" id="editor">
            <h2>Text editor</h2>
            <ul class="nav nav-tabs" id="editor-menu">
                <li class="active"><a href="#list" data-toggle="tab">page.lookbook-modmedia-list.liquid</a></li>
                <li><a href="#lookbook" data-toggle="tab">page.lookbook-modmedia-single.liquid</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="list">
                    <textarea name='code' id='code' style="height: 100%;">
                        <?php echo $content; ?>
                    </textarea>
                </div>
                <div class="tab-pane" id="lookbook"></div>
            </div>            
        </div>
    </div>
    <script>
        $(function() {
            $('#theme-menu a, #editor-menu').click(function(e) {
                e.preventDefault()
                $(this).tab('show');
                editor.refresh();
            })
            //$('#myTab a.active').tab('show');
        })
        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            tabMode: 'indent',
            mode: 'text/html',
            styleActiveLine: true,
            matchBrackets: true,
            highlightSelectionMatches: true
        });
        editor.on('change', function(cm, change) {
            cm.save();
            console.log(cm.getValue());
            //$('input[name=smodlightbox_html_hidden]').val(cm.getValue());
        });
        //var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('code'));
    </script>
</div>