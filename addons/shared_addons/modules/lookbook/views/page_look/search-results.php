<table class="table table-striped">
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Handle</th>
        <th>Vendor</th>
        <th>Type</th>
        <th></th>
    </tr>
    <?php foreach($products as $product):?>
    <tr>
        <td><?php if($product->images){ $images=json_decode($product->images); ?><img src="<?php echo (isset($images[0]))? $images[0] : '//cdn.shopify.com/s/assets/image-upload-899ed12e0ca7dd5ac65df627c3b5bf54.png';?>" style="max-width: 80px; max-height: 80px;"/><?php }?></td>
        <td><?php echo $product->title;?></td>
        <td><?php echo $product->handle;?></td>
        <td><?php echo $product->vendor;?></td>
        <td><?php echo $product->type;?></td>
        <td><button onclick='select_product("<?php echo $product->product;?>","<?php echo addslashes($product->images); ?>","<?php echo rawUrlEncode($product->title);?>");' class="btn btn-default">Select</button></td>
    </tr>
    <?php endforeach;?>
</table>
<div class="pagination-container">
    <?php echo $this->pagination->create_links();?>
</div>