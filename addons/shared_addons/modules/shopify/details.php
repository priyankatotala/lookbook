<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Module_Shopify  extends Module {

    public $version = '2.0';

    public function info() {
        return array(
            'name' => array(
                'en' => 'Shopify'
            ),
            'description' => array(
                'en' => 'Shopify settings'
            ),
            'frontend' => FALSE,
            'backend' => FALSE,
            'menu' => '', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
            'sections' => array(
            )
        );
    }

    public function install() {
        $this->db->where_in('slug',array('shopify_api_key','shopify_secret','shopify_scope','shopify_test_mode','shopify_price','shopify_charge_name','shopify_main_page_redirect'));
        $this->db->delete('settings');
        $shopify_settings[] = array(
            'slug' => 'shopify_api_key',
            'title' => 'SHOPIFY API KEY',
            'description' => 'Api Key of your Shopify APP.',
            '`default`' => '',
            '`value`' => '',
            'options' => '',
            'type' => 'text',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Shopify'
        );
        $shopify_settings[] = array(
            'slug' => 'shopify_secret',
            'title' => 'SHOPIFY SECRET',
            'description' => 'Secret of your Shopify APP.',
            '`default`' => '',
            '`value`' => '',
            'options' => '',
            'type' => 'text',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Shopify'
        );
        $shopify_settings[] = array(
            'slug' => 'shopify_scope',
            'title' => 'SHOPIFY SCOPE',
            'description' => 'Scopes separarted with comas (e.g: write_script_tags,read_products,read_content).',
            '`default`' => '',
            '`value`' => '',
            'options' => '',
            'type' => 'text',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Shopify'
        );
        $shopify_settings[] = array(
            'slug' => 'shopify_test_mode',
            'title' => 'Test mode',
            'description' => 'Set if the shop is in test mode.',
            '`default`' => '',
            '`value`' => '',
            'options' => 'test=Yes|null=No',
            'type' => 'select',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Shopify'
        );
        $shopify_settings[] = array(
            'slug' => 'shopify_price',
            'title' => 'Price',
            'description' => 'Set the price of the shop.',
            '`default`' => '',
            '`value`' => '',
            'options' => '',
            'type' => 'text',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'Shopify'
        );
        $shopify_settings[] = array(
            'slug' => 'shopify_charge_name',
            'title' => 'Charge Name',
            'description' => 'Set the name of the charge applied, this is what the customer will see in his Shopify invoice.',
            '`default`' => '',
            '`value`' => '',
            'options' => '',
            'type' => 'text',
            'is_required' => 0,
            'is_gui' => 1,
            'module' => 'Shopify'
        );
        $shopify_settings[] = array(
            'slug' => 'shopify_main_page_redirect',
            'title' => 'Main Page Redirect',
            'description' => 'Set the default controler and function to load after the user is logged in (eg. customers, if you want to redirect to an specific function then: customers/dashboard).',
            '`default`' => '',
            '`value`' => '',
            'options' => '',
            'type' => 'text',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'Shopify'
        );
        if ($this->db->insert_batch('settings', $shopify_settings)) {
            return TRUE;
        }
        return true;
    }

    public function uninstall() {
        $this->db->where_in('slug',array('shopify_api_key','shopify_secret','shopify_scope','shopify_test_mode','shopify_price','shopify_charge_name','shopify_main_page_redirect'));
        $this->db->delete('settings');
        
        // Put a check in to see if something failed, otherwise it worked
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