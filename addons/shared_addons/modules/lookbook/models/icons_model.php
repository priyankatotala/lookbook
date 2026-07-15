<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Icons_model extends CI_Model {
    public function create($data){
        $this->db->where('name',$data['name']);
        $this->db->where('shop',$data['shop']);
        $result=$this->db->get('shops_icons');
        if($result->num_rows() > 0){
            $result=$result->row();
            return $result->id;
        }else{
            $this->db->set('date','NOW()',false);
            $this->db->insert('shops_icons',$data);
            return $this->db->insert_id();
        }
    }
    public function delete_icon($id){
        $this->db->where('id',$id);
        return $this->db->delete('shops_icons');
    }
    public function list_icons($id){
        $this->db->where('shop',$id);
        $result=$this->db->get('shops_icons')->result();
        return $result;
    }
}