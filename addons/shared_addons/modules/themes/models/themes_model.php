<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Themes_model extends CI_Model {
    var $_errors=array();
    public function __contruct() {
        parent::construct();
    }
    public function get($id){
        $this->db->where('id',$id);
        return $this->db->get('themes')->row();
    }
    public function list_themes(){
        $this->db->order_by('title','asc');
        return $this->db->get('themes')->result();
    }
}