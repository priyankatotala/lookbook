<?php if (!$this->session->userdata('shop')) { ?>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <p>Enter the store you want to create lookbook for:</p> 
        <form action="" method="post">
            <div class="input-group">
                <input id="shop" class="form-control" placeholder="example.myshopify.com" name="shop" size="45" type="text" value="" /> 
            <span class="input-group-btn">
              <input name="commit" type="submit" value="Install or Login" class="btn btn-default" /> 
            </span>
          </div><!-- /input-group -->          
        </form>
        </div>
    </div>
<?php } ?>
<script>
    $(function(){
       $('form').submit(function(){
          $('input[type=submit]').parent().append('<ul class="loader"><li></li><li></li><li></li><li></li><li></li></ul>'); 
       });
    });
</script>