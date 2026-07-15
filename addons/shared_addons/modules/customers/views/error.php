<div id="content">
    <div class="alert alert-error">
        <b><?php echo $data['response_headers']['http_status_message']; ?></b>
        Please check if you are the Account Manager to activate the App.
        <?php if(isset($data['response']['errors'])){
            echo 'Posible Errors:';
            if(is_array($data['response']['errors'])){
                foreach($data['response']['errors'] as $key => $value):
                    if(is_array($value)){
                        echo '<br/>'.$key.':'.join(',',$value);
                    }else{
                        echo '<br/>'.$key.':'.$value;
                    }
                endforeach;                
            }else{
                echo $data['response']['errors'];
            }
            }?>
        <!-- <?php print_r($data);?> -->
    </div>
</div>           