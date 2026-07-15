<div id="content">
    <?php if(isset($errors)){?>
        <div class="text-center">The Account Owner needs to confirm the charge. If you are the account owner please click on Confirm Charge:</div>
        <div class="text-center"><a href="<?php echo $confirmation_url;?>" class="btn btn-success">Confirm Charge</a></div>
        <div class="text-center">If you are not please send the following link to the Account owner:<br/><?php echo $confirmation_url;?></div>
    <?php }else{?>
    <div class="text-center">Your charge #<?php echo $charge;?> has return the status: <span class='label <?php if($status =='pending' || $status == 'expired'){ echo 'label-warning';}else{ echo 'label-danger';}?>'><?php echo $status;?></label></div>
    <div class="text-center"><a href='<?php echo $redirect;?>'>Activate</a></div>
    <?php }?>
</div>       