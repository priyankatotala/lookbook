<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!-- Meta data -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300" rel="stylesheet" type="text/css"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/img/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/css/jquery.minicolors.css" rel="stylesheet" type="text/css"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/css/flysc.css" rel="stylesheet" type="text/css"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/css/jquery.highlight.css" rel="stylesheet" type="text/css"/>
        <link href="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/js/libs/jquery.js" type="text/javascript"></script>
        <script src="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/js/libs/jquery-ui.js" type="text/javascript"></script>
        <script src="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="https://modapps2.com.au/modifylightbox/addons/shared_addons/themes/smodlightbox/js/jquery.highlight.js" type="text/javascript"></script>
    </head>
    <body>
        <div id='wrapper' class='container'>
            <div id="header">
                <div class="container">

                </div>
            </div>
            <div class="container main_content">
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-error">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } else { ?>
                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                <!-- Content -->
                <div id="content">
                    <div class="alert alert-error">
                        <b><?php echo $data['response_headers']['http_status_message'];?></b>
                        <div><?php echo $data['response_headers']['status'];?></div>
                        <div>Response: <?php print_r($data['response']);?></div>
                        <div>Method: <?php print_r($data['method']);?></div>
                        <div>Path: <?php print_r($data['path']);?></div>
                        <div><?php echo isset($data['response_headers']['www-authenticate'])?$data['response_headers']['www-authenticate']:'';?></div>
                    </div>
                </div>                
            </div>
            <!-- Footer -->
            <div id="footer" class="row">

            </div>
        </div>
    </body>
</html>