<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_Customers  extends Module {

    public $version = '1.0';
    public function info() {
        return array(
            'name' => array(
                'en' => 'Customers'
            ),
            'description' => array(
                'en' => 'Customers'
            ),
            'frontend' => TRUE,
            'backend' => TRUE,
            'menu' => FALSE, // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
            'sections' => array(
                'Customers'=>array(
                    'name'=>'customer:customers',
                    'uri'=>'admin/customers'                        
                ),
                'customer:charges'=>array(
                    'name'=>'customer:charges',
                    'uri'=>'admin/customers/charges',
                        'shortcuts' => array(
                            'create' => array(
                                'name'  => 'customer:add_charge',
                                'uri'   => 'admin/customers/charges/create',
                                'class' => 'add'
                                )
                            )
                )
            ),
            'skip_xss' => 1
        );
    }
    public function admin_menu(&$menu)
    {
        $info=$this->info();
        if(isset($info['name'][CURRENT_LANGUAGE])){
            $name=$info['name'][CURRENT_LANGUAGE];
        }else{
            $name=$info['name']['en'];
        }
        $menu[$name]= 'admin/customers';
        add_admin_menu_place($name, 10);
    }
    public function install() {
        return TRUE;
    }

    public function uninstall() {
        return TRUE;
    }

    public function upgrade($old_version) {
        // Your Upgrade Logic
        return TRUE;
    }

    public function help() {
        // Return a string containing help info
        return "Here you can enter HTML with paragrpah tags or whatever you like";

        // You could include a file and return it here.
        return $this->load->view('help', NULL, TRUE); // loads modules/sample/views/help.php
    }

}

/* End of file details.php */