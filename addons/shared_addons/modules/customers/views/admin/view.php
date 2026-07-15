<section class="title">
	<h4>Customers</h4>
</section>

<section class="item">
    	<div class="content">
	
		
            
            
            <div id="modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Are you sure?</h3>
  </div>
  <div class="modal-body">
      <p>If you want to delete this lightbox please write the word DELETE in the field:</p>
      <input type="text" name="verify-delete"/>
      <input type="hidden" name="url" />
  </div>
  <div class="modal-footer">
    <a href="#" class="btn modal-cancel">Cancel</a>
    <a href="#" class="btn btn-danger modal-delete">Delete</a>
  </div>
</div>
<header>
    <h1>My Lightboxes</h1>
    <div class="actions">
        <?php echo anchor('lightbox/add','<i class="icon-plus icon-white"></i> New Lightbox','class="btn btn-success new"'); ?>        
    </div>
</header>
<div id="dashboard">
    <div class="alert">
    <strong>Warning!</strong> When checking your Lightbox on your storefront please remember that Lightboxes are set to display one per day for each viewer. If you'd like to view it on your storefront more than once per day, please empty your browser's cookies. Alternatively the preview function in the edit window can be viewed as many times as you like.
    </div>
    <?php
    if ($lightboxes) {

        foreach ($lightboxes as $lightbox):
            $settings=json_decode($lightbox->settings);
            ?>
            <div class="row-fluid section">
                <div class="span2">
                    <?php $smodlightbox_image=$settings->smodlightbox_image;
                    if($smodlightbox_image){ echo '<img src="'.BASE_URL.'uploads/shops_lightbox/'.$smodlightbox_image->file_name.'?'.strtotime($lightbox->updated).'" id="smodlightbox_image_url" alt="image" width="100%" />';}?>
                </div>
                <div class="span6">
                    <div class="description">
                        <?php echo (isset($settings->smodlightbox_name)?$settings->smodlightbox_name:'No title');?>
                    </div>
                </div>
                <div class="span4 section-content">
                    <div class="control-group string required">
                        <div class="actions">
                            <?php echo anchor('admin/customers/edit_lightbox/'.$shop.'/'.$lightbox->id.'/'.sha1($lightbox->id.$this->config->item('encryption_key')),'<i class="icon-pencil icon-white"></i> Edit','class="btn btn-warning edit"');?>                            
                        </div>
                    </div>   
                </div>
            </div>
        <?php
        endforeach;
    }else {
        ?><div class="row-fluid">
            <div class="span12 no-results">
                <i class='ico icon-lightbox'></i>
                <h2>You haven't created any lightboxes yet!</h2>
                <a href='<?php echo site_url('lightbox/add '); ?>' title='Create one now'>Create one now!</a>                
            </div>
        </div>
        <?php
    }
    ?>
</div>
	</div>
</section>