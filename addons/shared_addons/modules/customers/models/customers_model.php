<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customers_model extends CI_Model {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __contruct() {
        parent::construct();
    }
    public function read($shop){
        $this->db->where('shop',$shop);
        return $this->db->get('shops');
    }
    public function create($data){
        $this->db->where('shop',$data['shop']);
        $this->db->set('created','NOW()',false);
        return $this->db->insert('shops',$data);
    }
    public function update($data){
        $this->db->where('shop',$data['shop']);
        return $this->db->update('shops',$data);
    }
    public function get($base_where=array(),$limit=array()){
        if(isset($base_where['status'])){
            $this->db->where('status',$base_where['status']);
        }
        if(isset($base_where['keywords'])){
            $this->db->where("( shop like '%".$base_where['keywords']."%' or email like '%".$base_where['keywords']."%')",null,false);
        }
        if($limit){
            $this->db->limit($limit['limit'], $limit['offset']);
        }
        $this->db->order_by('email');
        $result=$this->db->get('shops')->result();
        return $result;
    }
    public function count($base_where=array()){
        $this->db->select('COUNT(*) as total',false);
        if(isset($base_where['status'])){
            $this->db->where('status',$base_where['status']);
        }
        if(isset($base_where['keywords'])){
            $this->db->where("( shop like '%".$base_where['keywords']."%' or email like '%".$base_where['keywords']."%')",null,false);
        }
        $this->db->order_by('email');
        return $this->db->get('shops')->row()->total;
    }
    public function events(){
        return $this->db->get('events')->result();
    }
    public function add_event($data){
        $this->db->set('date','NOW()',false);
        return $this->db->insert('events',$data);
    }
    public function insert_uninstall_webhooks($data){
        $this->db->set('date','NOW()',false);
        return $this->db->insert('uninstall_webhooks',$data);
    }
    public function get_uninstall_webhooks(){
        return $this->db->get('uninstall_webhooks')->result();
    }
    public function delete_uninstall_webhooks($id){
        $this->db->where('id',$id);
        return $this->db->delete('uninstall_webhooks');
    }
    public function check_special_charge($shop){
        $this->db->where('shop',$shop);
        $this->db->where('used',0);
        $return=$this->db->get('special_charges')->row();
        return $return;
    }
    public function update_special_charge($data){
        $this->db->where('shop',$data['shop']);
        $return=$this->db->update('special_charges',$data);
        return $return;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */