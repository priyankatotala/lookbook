<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<ul class="list-inline">
    <?php if($product){
        $images=json_decode($product->images);
        $i=0;
        if(count($images)>0){
            foreach($images as $image):
        ?>
    <li><button onclick="selectPhoto(<?php echo $i;?>)"><img src="<?php echo $image;?>" alt=""/></button></li>
        <?php $i++; endforeach;}else{ echo 'No images';}}?>
</ul>