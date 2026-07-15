<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lookbooks_pages_model extends CI_Model {

    var $_errors = array();

    public function __contruct() {
        parent::construct();
    }

    public function get($id) {
        $this->db->select('lookbooks_pages.*, shops_icons.url as default_icon_url, shops_icons.width as default_icon_width, shops_icons.height as default_icon_height');
        $this->db->join('shops_icons', 'shops_icons.id=lookbooks_pages.default_icon', 'left');
        $this->db->where('lookbooks_pages.id', $id);
        return $this->db->get('lookbooks_pages')->row();
    }

    public function delete($id) {
        $this->db->trans_start();
        $this->db->where('page', $id);
        $this->db->delete('lookbooks_page_products');

        $this->db->where('id', $id);
        $this->db->delete('lookbooks_pages');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function update($data) {
        if (isset($data['id'])) {
            $this->db->set('updated', 'NOW()', false);
            $this->db->where('id', $data['id']);
            return $this->db->update('lookbooks_pages', $data);
        } else {
            $this->errors('The id is missing');
            return false;
        }
    }

    public function insert($data) {
        $this->db->set('date', 'NOW()', false);
        $this->db->set('updated', 'NOW()', false);
        if ($this->db->insert('lookbooks_pages', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function list_pages_lookbook($lookbook) {
        $this->db->where('lookbook', $lookbook);
        $this->db->order_by('lookbooks_pages.order', 'asc');
        return $this->db->get('lookbooks_pages')->result();
    }
    
    public function list_shop_lookbook_pages($shop) {
        $this->db->select('id', false);
        $this->db->from('lookbooks');
        $this->db->where('shop',$shop);
        $lookbooks=$this->db->get()->result();
        $look=array();
        foreach($lookbooks as $lookbook){
            $look[]=$lookbook->id;
        }
        if(count($look)>0){
            $this->db->where_in('lookbook', $look);
            $result=$this->db->get('lookbooks_pages')->result();
        }else{
            $result=false;
        }
        return $result;
    }

    public function list_pages_with_products_lookbook($lookbook) {
        $this->db->select('lookbooks_pages.*, lookbooks_page_products.id as shop_product_id, lookbooks_pages.id as page, lookbooks_page_products.image as pin_image, lookbooks_page_products.top, lookbooks_page_products.left, lookbooks_page_products.icon, lookbooks_page_products.product, shops_products.images, shops_products.title as product_title, shops_products.type, shops_products.vendor, shops_products.handle, shops_icons.url as icon_url, shops_icons.width as icon_width, shops_icons.height as icon_height, lookbooks_page_products.linkto, lookbooks_page_products.url as linkurl, lookbooks_page_products.show_image, lookbooks_page_products.show_title, lookbooks_page_products.show_price, lookbooks_page_products.link_target, lookbooks_page_products.custom_title');
        $this->db->join('lookbooks_page_products', 'lookbooks_page_products.page=lookbooks_pages.id', 'left');
        $this->db->join('shops_products', 'shops_products.product=lookbooks_page_products.product', 'left');
        $this->db->join('shops_icons', 'shops_icons.id=lookbooks_page_products.icon', 'left');
        $this->db->order_by('lookbooks_pages.order', 'asc');
        $this->db->where('lookbooks_pages.lookbook', $lookbook);
        $result = $this->db->get('lookbooks_pages')->result();
        $pages = array();
        foreach ($result as $page):
            $pages[$page->page]['info'] = (Object) array('image' => $page->image);
            if($page->shop_product_id){
                $pages[$page->page]['products'][] = (Object) array(
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
                $pages[$page->page]['products']=array();
            }
        endforeach;
        return $pages;
    }

    public function errors($message) {
        $this->_errors[] = $message;
    }

    public function get_errors() {
        return $this->_errors;
    }

    public function get_products($page) {
        $this->db->select('lookbooks_page_products.*,lookbooks_page_products.image as pin_image, shops_products.images, shops_products.title as product_title, shops_icons.url as icon_url, shops_icons.width as icon_width, shops_icons.height as icon_height');
        $this->db->where('page', $page);
        $this->db->join('shops_products', 'shops_products.product=lookbooks_page_products.product and shops_products.shop='.$this->session->userdata('id'), 'left');
        $this->db->join('shops_icons', 'shops_icons.id=lookbooks_page_products.icon', 'left');
        $query=$this->db->get('lookbooks_page_products')->result();
        return $query;
    }

    public function insert_products($points, $page) {
        if(!$points){
            $this->db->where('page', $page);
            $this->db->delete('lookbooks_page_products');
        }else{
            $points_to_update = array();
            $update_ids = array();
            $points_to_insert = array();
            foreach ($points as $point) {
                if ($point['id']) {
                    $points_to_update[] = $point;
                    $update_ids[] = $point['id'];
                } else {
                    unset($point['id']);
                    $points_to_insert[] = $point;
                }
            }
            $update_ids = array_unique($update_ids);
            if ($update_ids) {
                $this->db->where('page', $page);
                $this->db->where_not_in('id', $update_ids);
                $this->db->delete('lookbooks_page_products');
            }
            if ($points_to_update) {
                $this->db->update_batch('lookbooks_page_products', $points_to_update, 'id');
            }
            if ($points_to_insert) {
                $this->db->insert_batch('lookbooks_page_products', $points_to_insert);
            }
        }
        return true;
    }

}