<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Charges_model extends CI_Model {

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
    public function read($id){
        $this->db->where('id',$id);
        return $this->db->get('special_charges')->row();
    }
    public function create($data){
        $this->db->set('created','NOW()',false);
        $result=$this->db->insert('special_charges',$data);
        if($result){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }
    public function update($data){
        $this->db->where('shop',$data['shop']);
        return $this->db->update('special_charges',$data);
    }
    public function get(){
        $this->db->order_by('shop');
        return $this->db->get('special_charges')->result();
    }
    public function check_special_charge($shop){
        $this->db->where('shop',$shop);
        $this->db->where('used',0);
        $return=$this->db->get('special_charges')->row();
        return $return;
    }
    //start..get shop details for activate special charges -shreeji
    public function get_shop_details($id){
        $this->db->where('id',$id);
        $this->db->where('used',0);
        $shop=$this->db->get('special_charges')->row();
        if(!empty($shop->shop)){
            $this->db->where('shop',$shop->shop);
            return $this->db->get('shops')->row();
        }else{
            return "0";
        }
    }  //end..called from admin_charges.php -shreeji 
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */