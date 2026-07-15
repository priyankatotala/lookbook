<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lookbook_Themes {

    private $_ci;
    private $error_message='';
    
    public function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->helper('file');
        $this->_ci->load->library('shopify/shopify');
        $this->_ci->load->model('customers/customers_model');
    }

    //Creates the .liquid file to be uploaded in the Shopify template for the Lookbook List
    public function create_page_lookbooks_list() {

        $this->_ci->load->model('customers/customers_model');
        $shop = $this->_ci->customers_model->read($this->_ci->session->userdata('shop'))->row();

        $theme_name = $shop->main_theme_dawn;
        $this->_ci->load->model('lookbook/lookbooks_model');
        $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            /*$themes = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes.json', array('fields' => 'id,name,role'));
            $theme_id = $themes['themes'][0]['id'];
            foreach ($themes['themes'] as $theme):
                if ($theme['role'] == 'main') {
                    $theme_id = $theme['id'];
                }
            endforeach;*/
            $theme_id=$this->_ci->session->userdata('theme_on_store');
            
            $data = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => $this->_ci->session->userdata('page_template_selected'))));

            $this->_ci->load->model('lookbook/lookbooks_model');
                $lookbooks = $this->_ci->lookbooks_model->list_lookbooks_shop_with_products($this->_ci->session->userdata('id'),1);
                $pages='{';
                if($lookbooks){
                    foreach($lookbooks as $look):
                        $pages=$pages."'".$look['lookbook']->shopify_page_handle."':{{ pages.".$look['lookbook']->shopify_page_handle.".content | json }},";
                    endforeach;
                }
                $pages=substr($pages,0,strlen($pages)-1);
                $pages=$pages.'}';


                if($theme_name == '887'){

                if ($data['status_response'] == 'success') {
                $content = $data['asset']['value'];

                $page_list_content = "{
                                      \"sections\": {
                                        \"main\": {
                                          \"type\": \"main-page\",
                                          \"settings\": {
                                          }
                                        },
                                        \"lookbooks-modmedia-list\": {
                                          \"type\": \"lookbooks-modmedia-list\",
                                          \"settings\": {
                                          }
                                        }
                                      },
                                      \"order\": [
                                        \"main\",
                                        \"lookbooks-modmedia-list\"
                                      ]
                                    }";
             
                
                  
                  $inner_content = "<div class='lookbook-spe-content'><div id=\"modmedia-lookbooks\" class=\"list\">
                        <div class=\"look-container\"><div id=\"lookbook-modmedia-container\">
                        Loading...
                        </div>
                        </div></div>";
                 
                  
                  $inner_content .="{{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
                    {{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
                    <script type=\"text/javascript\">
                    var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
                    var pages_content = ".$pages.";
                        var s = document.createElement(\"script\");
                        s.type = \"text/javascript\";
                        s.src = \"" . str_replace('http:','https:',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_list_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5)) . ".js?t=".time()) . "\";
                        document.getElementsByTagName(\"head\")[0].appendChild(s);
                    </script></div>";
                                    
               
                  
                    
                
                if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                    mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
                }
                $fp = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-list.json', "wb");
                $fp1 = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-list.liquid', "wb");
                        if (fwrite($fp, $page_list_content)) {
                            fwrite($fp1, $inner_content);
                            fclose($fp1);
                            fclose($fp);
                            return $this->upload_page_lookbooks_list();
                        } else {
                            fclose($fp);
                            $this->error_message="The file cannot be created.";
                            return false;
                        }
            } else {
                //$this->error_message="Shopify didn't retrieve the data of the page template selected: ".$this->_ci->session->userdata('page_template_selected');
                echo "check";
                $this->error_message="The data of the selected page template ".$this->_ci->session->userdata('page_template_selected')." is not retrieved";
                return false;
            }


                }
                else {
            if ($data['status_response'] == 'success') {
                $content = $data['asset']['value'];
                if(stripos($content,'{{ page.content }}') === false && stripos($content,'{{page.content}}') === false){
                                      $content .="<div class='lookbook-spe-content'>{% include 'process-html' with page.content %}</div>";
                                      $inner_content = "<div id=\"modmedia-lookbooks\" class=\"list\">
                                <div class=\"look-container\"><div id=\"lookbook-modmedia-container\">
                                Loading...
                                </div>
                    </div></div>";
                                     
                                      
                                      $inner_content .="{{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
                    {{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
                    <script type=\"text/javascript\">
                    var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
                    var pages_content = ".$pages.";
                        var s = document.createElement(\"script\");
                        s.type = \"text/javascript\";
                        s.src = \"" . str_replace('http:','https:',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_list_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5)) . ".js?t=".time()) . "\";
                        document.getElementsByTagName(\"head\")[0].appendChild(s);
                    </script>";}
                else
                {
                  $inner_content = "<div id=\"modmedia-lookbooks\" class=\"list\">
                            <div id=\"lookbook-modmedia-container\">
                            Loading...
                            </div>
                </div>";
                                  $inner_content .="<div class='lookbook-spe-content'>{{ page.content }}</div>";
                                  $inner_content .="{{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
                {{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
                <script type=\"text/javascript\">
                var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
                var pages_content = ".$pages.";
                    var s = document.createElement(\"script\");
                    s.type = \"text/javascript\";
                    s.src = \"" . str_replace('http:','https:',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_list_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5)) . ".js?t=".time()) . "\";
                    document.getElementsByTagName(\"head\")[0].appendChild(s);
                </script>";}
               
                if(stripos($content,'{{ page.content }}')===false && stripos($content,'{{page.content}}') === false){
                    $content = str_replace("{% include 'process-html' with page.content %}", $inner_content, $content);
                }else{
                    $content = str_replace("{{ page.content }}", $inner_content, $content);
                    $content = str_replace("{{page.content}}", $inner_content, $content);
                }
                if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                    mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
                }
                $fp = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-list.liquid', "wb");
                if (fwrite($fp, $content)) {
                    fclose($fp);
                    return $this->upload_page_lookbooks_list();
                } else {
                    fclose($fp);
                    $this->error_message="The file cannot be created.";
                    return false;
                }
            } else {
                //$this->error_message="Shopify didn't retrieve the data of the page template selected: ".$this->_ci->session->userdata('page_template_selected');
                echo "check";
                $this->error_message="The hello data of the selected page template ".$this->_ci->session->userdata('page_template_selected')." is not retrieved";
                return false;
            }

        }






        } catch (ShopifyCurlException $e) {
            $this->error_message="Curl: ".$e->getResponse();
            return false;
        }
    }
    public function create_lookbook_snippet($settings,$customer) {
        $this->_ci->load->model('lookbook/lookbooks_model');
        $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $theme_id=$this->_ci->session->userdata('actual_theme');
            
            $data = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => $this->_ci->session->userdata('page_template_selected'))));
            $this->_ci->load->model('lookbook/lookbooks_model');
                $lookbooks = $this->_ci->lookbooks_model->list_lookbooks_shop_with_products($this->_ci->session->userdata('id'),1);
                $pages='{';
                if($lookbooks){
                    foreach($lookbooks as $look):
                        $pages=$pages."'".$look['lookbook']->shopify_page_handle."':{{ pages.".$look['lookbook']->shopify_page_handle.".content | json }},";
                    endforeach;
                }
                $pages=substr($pages,0,strlen($pages)-1);
                $pages=$pages.'}';
            if ($data['status_response'] == 'success') {
                $content = $data['asset']['value'];
                
                if($settings->homepage_snippet_display=='list'){
                $inner_content = "<div id=\"modmedia-lookbooks\" class=\"list\">
            <div id=\"lookbook-modmedia-container\">
            </div>
            {{ page.".$customer->lookbooks_page_slug.".content }}
                {{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
{{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
</div>
<script type=\"text/javascript\">
var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
var pages_content = ".$pages.";
    var s = document.createElement(\"script\");
    s.type = \"text/javascript\";
    s.src = \"" . str_replace('http:','http',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_list_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) . ".js?t=".time())) . "\";
    document.getElementsByTagName(\"head\")[0].appendChild(s);
                </script>";}else{
                    $lookbook=$this->_ci->lookbooks_model->get($settings->homepage_snippet_lookbook_single);
                $inner_content = "<div id=\"modmedia-lookbooks\" class=\"single\">
            <div id=\"lookbook-modmedia-container\">
            </div>            
            {{ page.".$lookbook->shopify_page_handle.".content }}
                {{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
{{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
</div>
<script type=\"text/javascript\">
    var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
    var s = document.createElement(\"script\");
    s.type = \"text/javascript\";
    s.src = \"" . str_replace('http:','',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) .$lookbook->shopify_page_id .".js?t=".time())) . "\";
    document.getElementsByTagName(\"head\")[0].appendChild(s);
</script>";
                }
                if(stripos($content,'{{ page.content }}')===false && stripos($content,'{{page.content}}')===false){
                    $content = str_replace("{% include 'process-html' with page.content %}", $inner_content, $content);
                }else{
                    $content = str_replace("{{ page.content }}", $inner_content, $content);
                    $content = str_replace("{{page.content}}", $inner_content, $content);
                }
                if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                    mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
                }
                $fp = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/lookbook-snippet.liquid', "wb");
                if (fwrite($fp, $content)) {
                    fclose($fp);
                    return $this->upload_snippet();
                } else {
                    fclose($fp);
                    $this->error_message="The file cannot be created.";
                    return false;
                }
            } else {
                //$this->error_message="Shopify didn't retrieve the data of the page template selected: ".$this->_ci->session->userdata('page_template_selected');
                
                $this->error_message="The data of the selected page template ".$this->_ci->session->userdata('page_template_selected')."is not retrieved. Please clear your browser's cache and try.";
                return false;
            }
        } catch (ShopifyCurlException $e) {
            $this->error_message="Curl: ".$e->getResponse();
            return false;
        }
    }
    //Upload the Lookbooks List .liquid file to the store
    private function upload_snippet() {
        if ($this->_ci->session->userdata('token')) {
            $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $themes = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes.json', array('fields' => 'id,name,role'));
                $theme_id = $themes['themes'][0]['id'];
                $theme_mobile_id='';
                foreach ($themes['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        $theme_id = $theme['id'];
                    }else{
                        if ($theme['role'] == 'mobile') {
                            $theme_mobile_id = $theme['id'];
                        }
                    }
                endforeach;
                $data = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'snippets/lookbook-snippet.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbook-snippet.liquid?t='.time()))));
                if ($data['status_response'] == 'success') {
                    if($theme_mobile_id!=''){
                        $data2 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'snippets/lookbook-snippet.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbook-snippet.liquid?t='.time()))));
                    }
                    return $data;
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                $this->error_message="Curl: ".$e->getResponse();
                return false;
            }
        }
    }
    public function get_error(){
        return $this->error_message;
    }
    

     //Creates the .liquid file to be uploaded in the Shopify template for the Lookbook pages
    public function create_page_lookbooks_single() {

        $this->_ci->load->model('customers/customers_model');
        $shop = $this->_ci->customers_model->read($this->_ci->session->userdata('shop'))->row();
        $theme_name = $shop->main_theme_dawn;

        $this->_ci->load->model('lookbook/lookbooks_model');
        $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $theme_id=$this->_ci->session->userdata('theme_on_store');
            $data = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => $this->_ci->session->userdata('page_template_selected'))));

            if($theme_name == '887'){


            if ($data['status_response'] == 'success') {
                $content = $data['asset']['value'];
                

                $page_single_content = "{
                                      \"sections\": {
                                        \"main\": {
                                          \"type\": \"main-page\",
                                          \"settings\": {
                                          }
                                        },
                                        \"lookbooks-modmedia-single\": {
                                          \"type\": \"lookbooks-modmedia-single\",
                                          \"settings\": {
                                          }
                                        }
                                      },
                                      \"order\": [
                                        \"main\",
                                        \"lookbooks-modmedia-single\"
                                      ]
                                    }";
             
                  $content .="";
                  $inner_content = "<div class=\"lookbook-spe-content\"><div id=\"modmedia-lookbooks\" class=\"single\">
            <div class=\"look-container\"><div id=\"lookbook-modmedia-container\">
            </div></div>";
                 
                  
                  $inner_content .="{{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
                                    {{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
                                    </div>
                                    <script type=\"text/javascript\">
                                        var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
                                        var s = document.createElement(\"script\");
                                        s.type = \"text/javascript\";
                                        s.src = \"" . str_replace('http:','https:',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) . "{{page.id}}.js?t=".time())) . "\";
                                        document.getElementsByTagName(\"head\")[0].appendChild(s);
                                    </script></div>";
                
                                                
               
                if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                    mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
                }
                $fp = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-single.json', "wb");
                $fp1 = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-single.liquid', "wb");
                if (fwrite($fp, $page_single_content)) {
                    fwrite($fp1, $inner_content);
                    fclose($fp1);
                    fclose($fp);
                    return $this->upload_page_lookbooks_single();
                } else {
                    fclose($fp);
                    $this->error_message="The file cannot be created.";
                    return false;
                }
            } else {
                //$this->error_message="Shopify didn't retrieve the data of the page template selected: ".$this->_ci->session->userdata('page_template_selected');
                $this->error_message="The data of the selected page template ".$this->_ci->session->userdata('page_template_selected')." is not retrieved. Please clear your browser's cache and try.";
                return false;
            }

            }
            else {

            if ($data['status_response'] == 'success') {
                $content = $data['asset']['value'];
                if(stripos($content,'{{ page.content }}') === false && stripos($content,'{{page.content}}') === false){
                  $content .="<div class='lookbook-spe-content'>{% include 'process-html' with page.content %}</div>";
                  $inner_content = "<div id=\"modmedia-lookbooks\" class=\"single\">
            <div class=\"look-container\"><div id=\"lookbook-modmedia-container\">
            </div></div>";
                 
                  
                  $inner_content .="{{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
{{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
</div>
<script type=\"text/javascript\">
    var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
    var s = document.createElement(\"script\");
    s.type = \"text/javascript\";
    s.src = \"" . str_replace('http:','https:',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) . "{{page.id}}.js?t=".time())) . "\";
    document.getElementsByTagName(\"head\")[0].appendChild(s);
</script>";}
                else
                {
                   $inner_content = "<div id=\"modmedia-lookbooks\" class=\"single\">
            <div id=\"lookbook-modmedia-container\">
            </div>";
                 
                  $inner_content .="<div class='lookbook-spe-content'>{{ page.content }}</div>";
                  $inner_content .="{{ 'lookbooks-modmedia-styles.css' | asset_url | stylesheet_tag }}
{{ 'lookbooks-modmedia-scripts.js' | asset_url | script_tag }}
</div>
<script type=\"text/javascript\">
    var modlookbook_money_with_currency_format = \"{{ shop.money_with_currency_format }}\";
    var s = document.createElement(\"script\");
    s.type = \"text/javascript\";
    s.src = \"" . str_replace('http:','https:',site_url("assets/templates/".$this->_ci->session->userdata('shop')."/mod_media_lightbox_" . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) . "{{page.id}}.js?t=".time())) . "\";
    document.getElementsByTagName(\"head\")[0].appendChild(s);
</script>";}
            
                if(stripos($content,'{{ page.content }}')===false && stripos($content,'{{page.content}}')===false){
                    $content = str_replace("{% include 'process-html' with page.content %}", $inner_content, $content);
                }else{
                    $content = str_replace("{{ page.content }}", $inner_content, $content);
                    $content = str_replace("{{page.content}}", $inner_content, $content);
                }
                if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                    mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
                }
                $fp = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-single.liquid', "wb");
                if (fwrite($fp, $content)) {
                    fclose($fp);
                    return $this->upload_page_lookbooks_single();
                } else {
                    fclose($fp);
                    $this->error_message="The file cannot be created.";
                    return false;
                }
            } else {
                //$this->error_message="Shopify didn't retrieve the data of the page template selected: ".$this->_ci->session->userdata('page_template_selected');
                $this->error_message="The data of the selected page template ".$this->_ci->session->userdata('page_template_selected')." is not retrieved. Please clear your browser's cache and try.";
                return false;
            }

        }


        } catch (ShopifyCurlException $e) {
            $this->error_message="Curl: ".$e->getResponse();
            return false;
        }
    }

    //Upload the css styles.
    public function upload_lookbook_styles() {
        if ($this->_ci->session->userdata('token')) {
            $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $content = $this->_ci->load->view('themes/themes/lookbooks-modmedia-styles',array(),true);
                if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                    mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
                }
                $fp = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-styles.css.liquid', "wb");
                if (fwrite($fp, $content)) {
                    fclose($fp);                    
                } else {
                    fclose($fp);
                    $this->error_message="The file cannot be created.";
                    return false;
                }
                $themes = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes.json', array('fields' => 'id,name,role'));
                $theme_id = $themes['themes'][0]['id'];
                $theme_mobile_id='';
                foreach ($themes['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        $theme_id = $theme['id'];
                    }else{
                        if ($theme['role'] == 'mobile') {
                            $theme_mobile_id = $theme['id'];
                        }
                    }
                endforeach;
                $data = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'assets/lookbooks-modmedia-styles.css.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-styles.css.liquid'))));
                if ($data['status_response'] == 'success') {
                    if($theme_mobile_id !=''){
                        $data2 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'assets/lookbooks-modmedia-styles.css.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-styles.css.liquid'))));
                    }
                    return $data;
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                $this->error_message="Curl: ".$e->getResponse();
                return false;
            }
        } else {
            return false;
        }
    }
    public function upload_lookbook_scripts() {
        if ($this->_ci->session->userdata('token')) {
            $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $content = $this->_ci->load->view('themes/themes/lookbooks-modmedia-scripts',array(),true);
                if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                    mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
                }
                $fp = fopen(FCPATH . "assets/templates/" . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-scripts.js.liquid', "wb");
                if (fwrite($fp, $content)) {
                    fclose($fp);                    
                } else {
                    fclose($fp);
                    $this->error_message="The file cannot be created.";
                    return false;
                }
                $themes = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes.json', array('fields' => 'id,name,role'));
                $theme_id = $themes['themes'][0]['id'];
                $theme_mobile_id='';
                foreach ($themes['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        $theme_id = $theme['id'];
                    }else{
                        if ($theme['role'] == 'mobile') {
                            $theme_mobile_id = $theme['id'];
                        }
                    }
                endforeach;
                $data = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'assets/lookbooks-modmedia-scripts.js.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-scripts.js.liquid?u='.time()))));
                if ($data['status_response'] == 'success') {
                    if($theme_mobile_id !=''){
                        $data2 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'assets/lookbooks-modmedia-scripts.js.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-scripts.js.liquid?t='.time()))));
                    }
                    return $data;
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                $this->error_message="Curl: ".$e->getResponse();
                return false;
            }
        } else {
            return false;
        }
    }
    
   //Upload the Lookbook pages .liquid file to the store
    private function upload_page_lookbooks_single() {
        if ($this->_ci->session->userdata('token')) {
            $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $themes = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes.json', array('fields' => 'id,name,role,theme_store_id'));
                $theme_id = $themes['themes'][0]['id'];
                $theme_mobile_id='';
                foreach ($themes['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        $theme_id = $theme['id'];
                        $theme_name = $theme['theme_store_id'];
                    }else{
                        if ($theme['role'] == 'mobile') {
                            $theme_mobile_id = $theme['id'];
                        }
                    }
                endforeach;

                if($theme_name == '887'){
                 $data = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-single.json', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-single.json?u='.time()))));

                 $data1 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'sections/lookbooks-modmedia-single.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-single.liquid?u='.time()))));

                if ($data['status_response'] == 'success') {
                    if($theme_mobile_id !=''){
                        $data2 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-single.json', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-single.json?u='.time()))));

                        $data3 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'sections/lookbooks-modmedia-single.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-single.liquid?u='.time()))));
                    }
                    return $data;
                } else {
                    return false;
                }


                } else {
                $data = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-single.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-single.liquid?u='.time()))));
                if ($data['status_response'] == 'success') {
                    if($theme_mobile_id !=''){
                        $data2 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-single.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-single.liquid?u='.time()))));
                    }
                    return $data;
                } else {
                    return false;
                }

            }
            } catch (ShopifyCurlException $e) {
                $this->error_message="Curl: ".$e->getResponse();
                return false;
            }
        } else {
            return false;
        }
    }

    //Upload the Lookbooks List .liquid file to the store
    private function upload_page_lookbooks_list() {
        if ($this->_ci->session->userdata('token')) {
            $this->_ci->shopify->init(array('shop_domain' => $this->_ci->session->userdata('shop'), 'token' => $this->_ci->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $themes = $this->_ci->shopify->call('GET', '/admin/api/2023-07/themes.json', array('fields' => 'id,name,role,theme_store_id'));
                $theme_id = $themes['themes'][0]['id'];
                $theme_mobile_id='';
                foreach ($themes['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        $theme_id = $theme['id'];
                        $theme_name = $theme['theme_store_id'];
                    }else{
                        if ($theme['role'] == 'mobile') {
                            $theme_mobile_id = $theme['id'];
                        }
                    }
                endforeach;
                

                if($theme_name == '887'){
                    $data = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-list.json', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-list.json?u='.time()))));
                    $data1 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'sections/lookbooks-modmedia-list.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/lookbooks-modmedia-list.liquid?u='.time()))));
                if ($data['status_response'] == 'success') {
                    if($theme_mobile_id !=''){
                        $data2 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-list.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-list.liquid?u='.time()))));
                    }
                    return $data;
                } else {
                    return false;
                }


                } 
                else {
                $data = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-list.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-list.liquid?u='.time()))));
                if ($data['status_response'] == 'success') {
                    if($theme_mobile_id !=''){
                        $data2 = $this->_ci->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-list.liquid', 'src' => site_url('assets/templates/' . $this->_ci->session->userdata('shop') . '/page.lookbooks-modmedia-list.liquid?u='.time()))));
                    }
                    return $data;
                } else {
                    return false;
                }
            }
            } catch (ShopifyCurlException $e) {
                $this->error_message="Curl: ".$e->getResponse();
                return false;
            }
        }
    }

    // Creates the javascript file to be loaded in the store for the Lookbook Pages
    public function create_javascript($id, $security) {
        if (sha1($id . $this->_ci->config->item('encryption_key')) == $security) {
            $this->_ci->load->model('lookbook/lookbooks_model');
            $lookbook = $this->_ci->lookbooks_model->get($id);
            $this->_ci->load->helper('file');
            $this->_ci->load->model('lookbook/lookbooks_pages_model');
            $lookbook_data = $this->_ci->lookbooks_model->get($id,1);
            if(!$lookbook_data){
                $data='';
            }else{
                $lookbook_pages = $this->_ci->lookbooks_pages_model->list_pages_with_products_lookbook($id);
                $settings = array(
                    'pages' => $lookbook_pages,
                    'lookbook'=>$lookbook_data
                );
                if (!$lookbook_data->theme_handle) {
                    $data = $this->compress($this->_ci->load->view('themes/themes/single/list', $settings, true));
                } else {
                   // $data = $this->compress($this->_ci->load->view('themes/themes/single/' . $lookbook_data->theme_handle, $settings, true));
                    $data = $this->_ci->load->view('themes/themes/single/'.$lookbook_data->theme_handle, $settings, true);
                }
            }
            if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
            }
            if (write_file(FCPATH . 'assets/templates/' . $this->_ci->session->userdata('shop') . '/mod_media_lightbox_' . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) . $lookbook->shopify_page_id . '.js', $data)) {
                return $this->create_list_javascript($id, $security) ;
            } else {
                $this->_ci->session->set_flashdata('error', "The lookbook was saved but the js wasn't generated");
                return false;
            }
        } else {
            return false;
        }
    }

    // Creates the javascript file to be loaded in the store for the Lookbooks List
    public function create_list_javascript($id, $security) {
        if (sha1($id . $this->_ci->config->item('encryption_key')) == $security) {
            $this->_ci->load->model('lookbook/lookbooks_model');
            $lookbooks = $this->_ci->lookbooks_model->list_lookbooks_shop_with_products($this->_ci->session->userdata('id'),1);
            //print_r($lookbooks);
            $settings = array(
                'lookbooks' => $lookbooks
            );
            //$data = $this->compress($this->_ci->load->view('themes/themes/lists/list', $settings, true));
            $data = $this->_ci->load->view('themes/themes/lists/list', $settings, true);
            if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
            }
            if (write_file(FCPATH . 'assets/templates/' . $this->_ci->session->userdata('shop') . '/mod_media_lightbox_list_' . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) . '.js', $data)) {
                $this->_ci->session->set_flashdata('success', "The lookbook was saved");
                return true;
            } else {
                $this->_ci->session->set_flashdata('error', "The lookbook was saved but the js wasn't generated");
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function create_list_javascript_test() {
            $this->_ci->load->model('lookbook/lookbooks_model');
            $lookbooks = $this->_ci->lookbooks_model->list_lookbooks_shop_with_products($this->_ci->session->userdata('id'),1);
            //print_r($lookbooks);
            $settings = array(
                'lookbooks' => $lookbooks
            );
            //$data = $this->compress($this->_ci->load->view('themes/themes/lists/list', $settings, true));
            $data = $this->_ci->load->view('themes/themes/lists/list', $settings, true);
            if (!is_dir('assets/templates/' . $this->_ci->session->userdata('shop'))) {
                mkdir('./assets/templates/' . $this->_ci->session->userdata('shop'), 0777, TRUE);
            }
            if (write_file(FCPATH . 'assets/templates/' . $this->_ci->session->userdata('shop') . '/mod_media_lightbox_list_' . substr(sha1($this->_ci->session->userdata('shop') . $this->_ci->config->item('encryption_key')), 0, 5) . '.js', $data)) {
                $this->_ci->session->set_flashdata('success', "The lookbook was saved");
                return true;
            } else {
                $this->_ci->session->set_flashdata('error', "The lookbook was saved but the js wasn't generated");
                return false;
            }        
    }

    public function create_uninstaled_javascript($shop) {
        $this->_ci->load->model('lookbook/lookbooks_model');
        $data = $this->compress($this->_ci->load->view('themes/themes/uninstalled', null, true));

        if (!is_dir('assets/templates/' . $shop)) {
            mkdir('./assets/templates/' . $shop, 0777, TRUE);
        }
        $map = directory_map('./assets/templates/' . $shop, FALSE, TRUE);
        foreach ($map as $file):
            if (strpos($file, '.liquid') === false) {
                if (write_file(FCPATH . 'assets/templates/' . $shop . '/' . $file, $data)) {
                    $this->_ci->session->set_flashdata('success', "The lookbook was saved");
                } else {
                    $this->_ci->session->set_flashdata('error', "The lookbook was saved but the js wasn't generated");
                }
            } else {
                unlink('./assets/templates/' . $shop . '/' . $file);
            }
        endforeach;
        return true;
    }

    public function compress($buffer) {
        // remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        // remove tabs, spaces, newlines, etc.
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        return $buffer;
    }

}

?>