<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lookbooks_model extends CI_Model {
    var $_errors=array();
    public function __contruct() {
        parent::construct();
    }
    public function get($id,$active=null){
        $this->db->select('lookbooks.*, count(default_lookbooks_pages.lookbook) as total_pages, themes.title as theme_title, themes.image as theme_image, themes.handle as theme_handle, themes.lightbox_type as theme_lightbox_type');
        $this->db->where('lookbooks.id',$id);
        $this->db->join('lookbooks_pages','lookbooks_pages.lookbook = lookbooks.id','left');
        $this->db->join('themes','themes.id = lookbooks.theme','left');
        $this->db->group_by('lookbooks.id, lookbooks_pages.lookbook');
        if($active){
            $this->db->where('active',1);
        }
        $this->db->order_by('lookbooks.order','asc');
        return $this->db->get('default_lookbooks')->row();
    }
    public function update_order($sort){
        if($sort){
            $orders=explode(',',$sort);
			 if($orders){
                $sorting=array();
                $n=1;
                foreach($orders as $order){
                    $sorting[]=array(
                        'id'=>$order,
                        'order'=>$n
                    );
                    $n=$n+1;
                }
                if($this->db->update_batch('lookbooks',$sorting, 'id')){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }
    public function update($data){
        if(isset($data['id'])){
            if(isset($data['sort'])){
                $orders=explode(',',$data['sort']);
                $sorting=array();
                $n=1;
                foreach($orders as $order){
                    $sorting[]=array(
                        'id'=>$order,
                        'order'=>$n
                    );
                    $n=$n+1;
                }
                $this->db->update_batch('lookbooks_pages',$sorting, 'id');
                unset($data['sort']);
            }
            $this->db->set('updated','NOW()',false);
            $this->db->where('id',$data['id']);
            return $this->db->update('lookbooks',$data);
        }else{
            $this->errors('The id is missing');
            return false;
        }
    }
    public function insert($data){
        $this->db->set('date','NOW()',false);
        $this->db->set('updated','NOW()',false);
        if($this->db->insert('lookbooks',$data)){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }
    public function delete($id){
        $this->db->trans_start();
            $this->db->where('default_lookbooks_page_products.page IN (SELECT id FROM default_lookbooks_pages WHERE default_lookbooks_pages.lookbook = '.$id.')');
            $this->db->delete('lookbooks_page_products');            
            
            $this->db->where('lookbook',$id);
            $this->db->delete('lookbooks_pages');

            $this->db->where('id',$id);
            $this->db->delete('lookbooks');
        $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                return false;
            }else{
                return true;
            }
    }
    public function list_lookbooks_shop($shop){
        $this->db->select('lookbooks.*, count(default_lookbooks_pages.lookbook) as total_pages, themes.title as theme_title, themes.image as theme_image, themes.handle as theme_handle');
        $this->db->where('shop',$shop);
        $this->db->join('lookbooks_pages','lookbooks_pages.lookbook = lookbooks.id','left');
        $this->db->join('themes','themes.id = lookbooks.theme','left');
        $this->db->group_by('lookbooks.id, lookbooks_pages.lookbook');
        $this->db->order_by('lookbooks.order','asc');
        $result=$this->db->get('lookbooks')->result();
        return $result;
    }
    public function copy_lookbook($from, $to){

        $lookbook_insert = array();
        $lookbook_pages_insert = array();
        $lookbooks_pages_products_insert = array();

        $this->db->where('shop', $from);
        $lookbooks_result = $this->db->get('lookbooks')->result();

        $lookbooks_id = array();
        foreach($lookbooks_result as $lookbook):
            $lookbooks_insert = array(
                'title' => $lookbook->title,
                'feature_image' => $lookbook->feature_image,
                'shop' => $to,
                'theme' => $lookbook->theme,
                'theme_settings' => $lookbook->theme_settings,
                'page_template' => $lookbook->page_template,
                'active' => $lookbook->active
            );
            echo 'Inserting lookbooks <br/>';
            print_r($lookbooks_insert);

            $this->db->set('date','NOW()',false);
            $this->db->set('updated','NOW()',false);
            $this->db->insert('lookbooks', $lookbooks_insert);
            $look_id = $this->db->insert_id();
            //$look_id ='Generated';

            $this->db->where('lookbook', $lookbook->id);
            $pages_result = $this->db->get('lookbooks_pages')->result();


            foreach($pages_result as $page):
                $lookbooks_pages_insert =array(
                    'lookbook' => $look_id,
                    'image' => $page->image,
                    'default_icon' => $page->default_icon,
                    'order' => $page->order
                );
                echo '<br/>Inserting pages<br/>';
                echo '<!-- ';
                print_r($lookbooks_pages_insert);
                echo '-->';
                $this->db->set('date','NOW()',false);
                $this->db->set('updated','NOW()',false);
                $this->db->insert('lookbooks_pages', $lookbooks_pages_insert);
                $page_i_id = $this->db->insert_id();
                //$page_i_id='Generated';

                $this->db->where('page', $page->id);
                $products_page = $this->db->get('lookbooks_page_products')->result();

                $lookbooks_page_products = array();
                foreach($products_page as $product):
                    if($product->product){
                        $this->db->where('product', $product->product);
                        $product_data = $this->db->get('shops_products')->row();
                    }else{
                        $product_data = null;
                    }
                    if($product_data){
                        $products_to_ids = array();
                        $this->db->where('handle', $product_data->handle);
                        $this->db->where('shop', $to);
                        $product_to = $this->db->get('shops_products')->row();
                        if($product_to){
                            $lookbooks_page_products[] = array(
                                'page' => $page_i_id,
                                'product' => $product_to->product,
                                'image' => $product->image,
                                'top' => $product->top,
                                'left' => $product->left,
                                'show_image' => $product->show_image,
                                'show_title' => $product->show_title,
                                'show_price' => $product->show_price,
                                'icon' => $product->icon,
                                'linkto' => $product->linkto,
                                'link_target' => $product->link_target,
                                'url' => $product->url,
                                'custom_title' => $product->custom_title
                            );
                        }else{
                            $lookbooks_page_products[] = array(
                                'page' => $page_i_id,
                                'product' => $product->product,
                                'image' => $product->image,
                                'top' => $product->top,
                                'left' => $product->left,
                                'show_image' => $product->show_image,
                                'show_title' => $product->show_title,
                                'show_price' => $product->show_price,
                                'icon' => $product->icon,
                                'linkto' => $product->linkto,
                                'link_target' => $product->link_target,
                                'url' => $product->url,
                                'custom_title' => $product->custom_title
                            );
                        }
                    }else{
                        $lookbooks_page_products[] = array(
                            'page' => $page_i_id,
                            'product' => $product->product,
                            'image' => $product->image,
                            'top' => $product->top,
                            'left' => $product->left,
                            'show_image' => $product->show_image,
                            'show_title' => $product->show_title,
                            'show_price' => $product->show_price,
                            'icon' => $product->icon,
                            'linkto' => $product->linkto,
                            'link_target' => $product->link_target,
                            'url' => $product->url,
                            'custom_title' => $product->custom_title
                        );
                    }                    
                endforeach;
                if($lookbooks_page_products){
                    $this->db->insert_batch('lookbooks_page_products',$lookbooks_page_products );
                    echo '<br>Inserting products batch</br>';
                    //print_r($lookbooks_page_products);
                }
            endforeach;    
            $lookbooks_id[] = $lookbook->id;
        endforeach;
        print_r($lookboks_id);
        echo 'Done';
    }
    public function list_lookbooks_shop_with_products($shop, $active=null){
        $this->db->select('lookbooks.*, lookbooks_page_products.id as shop_product_id, lookbooks_pages.id as page, lookbooks_pages.image,  lookbooks_page_products.image as pin_image, lookbooks_page_products.top, lookbooks_page_products.left, lookbooks_page_products.icon, lookbooks_page_products.product, shops_products.images, shops_products.title as product_title, shops_products.type, shops_products.vendor, shops_products.handle, themes.title as theme_title, themes.image as theme_image, themes.handle as theme_handle, shops_icons.url as icon_url, shops_icons.width as icon_width, shops_icons.height as icon_height, lookbooks_page_products.linkto, lookbooks_page_products.url as linkurl, lookbooks_page_products.show_image, lookbooks_page_products.show_title, lookbooks_page_products.show_price, lookbooks_page_products.link_target,lookbooks_page_products.custom_title');
        $this->db->where('lookbooks.shop',$shop);
        $this->db->join('lookbooks_pages','lookbooks_pages.lookbook = lookbooks.id','left');
        $this->db->join('themes','themes.id = lookbooks.theme','left');
        $this->db->join('lookbooks_page_products', 'lookbooks_page_products.page=lookbooks_pages.id', 'left');
        $this->db->join('shops_products', 'shops_products.product=lookbooks_page_products.product', 'left');
        $this->db->join('shops_icons', 'shops_icons.id=lookbooks_page_products.icon', 'left');
        if($active){
            $this->db->where('lookbooks.active',1);
        }
        $this->db->order_by('lookbooks.order','asc');
        $this->db->order_by('lookbooks_pages.order','asc');
        $result=$this->db->get('lookbooks')->result();
        
        $lookbooks = array();
        foreach ($result as $page):
            $lookbooks[$page->id]['lookbook']=(Object) array(
              'id'=>$page->id,
              'title'=>$page->title,
              'feature_image'=>$page->feature_image,
              'theme_handle'=>$page->theme_handle,
              'theme_settings'=>$page->theme_settings,
              'shopify_page_handle'=>$page->shopify_page_handle
            );
            $lookbooks[$page->id]['pages'][$page->page]['info'] = (Object) array('image' => $page->image);
            if($page->shop_product_id){
                $lookbooks[$page->id]['pages'][$page->page]['products'][] = (Object) array(
                            'images' => $page->images,
                            'product' => $page->product,
                            'type' => $page->type,
                            'vendor' => $page->vendor,
                            'handle' => $page->handle,
                            'pin_image' => $page->pin_image,
                            'product_title' => $page->product_title,
                            'icon' => $page->icon,
                            'linkto' => $page->linkto,
                            'show_image'=>$page->show_image,
                            'show_title'=>$page->show_title,
                            'show_price'=>$page->show_price,
                            'link_target'=>$page->link_target,
                            'url' => $page->linkurl,
                            'custom_title' => $page->custom_title,
                            'icon_url' => $page->icon_url,
                            'icon_width' => $page->icon_width,
                            'icon_height' => $page->icon_height,
                            'left' => $page->left,
                            'top' => $page->top
                );
            }else{
                $lookbooks[$page->id]['pages'][$page->page]['products'] = (Object) array();
            }
        endforeach;
        return $lookbooks;
    }
    public function errors($message){
        $this->_errors[]=$message;        
    }
    public function get_errors(){
        return $this->_errors;
    }
    public function insert_webhook($data){
        return $this->db->insert('webhooks',$data);
    }
}