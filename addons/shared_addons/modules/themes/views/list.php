<div id="themes-list">
<div class='row'>
    <?php
    if ($themes) {
        $n=1;
        foreach ($themes as $theme):
            ?>
            <div class='col-md-6'><div class="theme">
                    <img src="<?php echo site_url('uploads/themes/'.$theme->image); ?>" alt="<?php echo $theme->title; ?>"/>   
                    <h3><?php echo $theme->title; ?></h3>
                    <button type="button" onclick="selectTheme('<?php echo $theme->id; ?>', '<?php echo $theme->title; ?>','<?php echo site_url('uploads/themes/'.$theme->image); ?>','<?php echo site_url('themes/edit/'.$theme->id.'/'.$lookbook.'/'.sha1($lookbook.$this->config->item('encryption_key')));?>')" class="btn btn-default">Select Theme</button>
                </div>
            </div>
            <?php
            $n++;
            if($n%2){
                echo '</div><div class="row">';
            }
        endforeach;
    }
    ?>
</div>
    </div>