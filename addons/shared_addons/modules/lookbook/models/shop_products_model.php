<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shop_products_model extends CI_Model {

    var $_errors = array();

    public function __contruct() {
        parent::construct();
    }
    public function read($product){
        $this->db->where('product',$product);
        return $this->db->get('shops_products')->row();
    }
    public function get($shop, $params = array()) {
        $extra = '';
        if (isset($params['vendor'])) {
            $extra = "vendor like '%" . $params['vendor'] . "%'";
        }
        if (isset($params['type'])) {
            if ($extra) {
                $extra = $extra . " or type like '%" . $params['type'] . "%'";
            } else {
                $extra = "type like '%" . $params['type'] . "%'";
            }
        }
        if (isset($params['title'])) {
            if ($extra) {
                $extra = $extra . " or title like '%" . $params['title'] . "%'";
            } else {
                $extra = "title like '%" . $params['title'] . "%'";
            }
        }
        if (isset($params['keywords'])) {
            if ($extra) {
                $extra = $extra . " or title like '%" . $params['keywords'] . "%' or vendor like '%" . $params['keywords'] . "%' or type like '%" . $params['keywords'] . "%'";
            } else {
                $extra = "title like '%" . $params['keywords'] . "%' or vendor like '%" . $params['keywords'] . "%' or type like '%" . $params['keywords'] . "%'";
            }
        }
        if (isset($params['limit'])) {
            $this->db->limit($params['limit'], $params['offset']);
        }
        if ($extra) {
            //$this->db->where("shop = '" . $shop . "' and (" . $extra . ") and published_at is not null");
            $this->db->where("shop = '" . $shop . "' and (" . $extra . ")");
        } else {
            $this->db->where('shop', $shop);
            //$this->db->where('published_at is not null', null,false);
        }
        return $this->db->get('shops_products')->result();
    }

    public function count($shop, $params = array()) {
        $this->db->select('count(*) as total');
		
        $extra = '';
        if (isset($params['vendor'])) {
            $extra = "vendor like '%" . $params['vendor'] . "%'";
        }
        if (isset($params['type'])) {
            if ($extra) {
                $extra = $extra . " or type like '%" . $params['type'] . "%'";
            } else {
                $extra = "type like '%" . $params['type'] . "%'";
            }
        }
        if (isset($params['title'])) {
            if ($extra) {
                $extra = $extra . " or title like '%" . $params['title'] . "%'";
            } else {
                $extra = "title like '%" . $params['title'] . "%'";
            }
        }
        if (isset($params['keywords'])) {
            if ($extra) {
                $extra = $extra . " or title like '%" . $params['keywords'] . "%' or vendor like '%" . $params['keywords'] . "%' or type like '%" . $params['keywords'] . "%'";
            } else {
                $extra = "title like '%" . $params['keywords'] . "%' or vendor like '%" . $params['keywords'] . "%' or type like '%" . $params['keywords'] . "%'";
            }
        }
        if ($extra) {
            $this->db->where("shop = '" . $shop . "' and (" . $extra . ")");
        } else {
            $this->db->where('shop', $shop);
        }
        $result = $this->db->get('shops_products')->row();
        return $result->total;
		
    }

    public function update($products) {
        if ($products) {
            $this->db->where('shop', $products[0]['shop']);
            $this->db->delete('shops_products');

            return $this->db->insert_batch('shops_products', $products);
        } else {

            return false;
        }
    }

    public function errors($message) {
        $this->_errors[] = $message;
    }

    public function get_errors() {
        return $this->_errors;
    }

    public function check_products_changes($shop, $shop_id) {
        $this->db->where('shop', $shop);
        $result = $this->db->get('webhooks')->result();
        foreach ($result as $item) {
            $prod = json_decode($item->info);
            if ($item->type == 'create') {
                $this->db->where('shop',$shop_id);
                $this->db->where('product',$prod->id);
                $this->db->delete('shops_products');
                $images = array();
                if ($prod->images) {
                    foreach ($prod->images as $image) {
                        $images[] = $image->src;
                    }
                }
                $data = array(
                    'shop' => $shop_id,
                    'product' => $prod->id,
                    'handle' => $prod->handle,
                    'title' => $prod->title,
                    'type' => $prod->product_type,
                    'vendor' => $prod->vendor,
                    'images' => json_encode($images),
                    'published_at' => $prod->published_at
                );
                $this->db->insert('shops_products', $data);
            } else {
                if ($item->type == 'update') {
                    $images = array();
                    if (isset($prod->images)) {
                        foreach ($prod->images as $image) {
                            $images[] = $image->src;
                        }
                    }
                    $data = array(
                        'product' => $prod->id,
                        'handle' => $prod->handle,
                        'title' => $prod->title,
                        'type' => $prod->product_type,
                        'vendor' => $prod->vendor,
                        'images' => json_encode($images),
                        'published_at' => $prod->published_at
                    );
                    $this->db->where('shop', $shop_id);
                    $this->db->where('product', $prod->id);
                    $this->db->update('shops_products', $data);
                } else {
                    if ($item->type == 'delete') {
                        $this->db->where('product', $prod->id);
                        $this->db->delete('shops_products');
                    }
                }
            }
        }
        $this->db->where('shop', $shop);
        return $this->db->delete('webhooks');
    }

}