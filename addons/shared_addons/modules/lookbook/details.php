<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_Lookbook  extends Module {

    public $version = '1.0';

    public function info() {
        return array(
            'name' => array(
                'en' => 'Modify Lookbook'
            ),
            'description' => array(
                'en' => 'Lookbook for shopify'
            ),
            'frontend' => TRUE,
            'backend' => FALSE,
            'menu' => FALSE, // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
            'sections' => array(
            )
        );
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