<?php

class Lookbook extends Public_Controller {

    public $validation = array(
        array(
            'field' => 'title',
            'label' => 'Title',
            'rules' => 'required'
        ),
        array(
            'field' => 'active',
            'label' => 'Active',
            'rules' => 'required'
        ),
        array(
            'field' => 'theme',
            'label' => 'Theme',
            'rules' => ''
        ),
        array(
            'field' => 'feature_image',
            'label' => 'Feature Image',
            'rules' => ''
        )
    );

    function __construct() {
        parent::__construct();
        $this->load->library('shopify/shopify');
        $this->load->model('lookbooks_model');
        $this->load->library('themes/lookbook_themes');
        if (($this->session->userdata('shop') && $this->session->userdata('status') == 'active') || $this->uri->segment(2) == 'verify_webhook' || $this->uri->segment(2) == 'product_create_webhook' || $this->uri->segment(2) == 'product_update_webhook' || $this->uri->segment(2) == 'product_delete_webhook' || $this->uri->segment(2) == 'product_photos' || $this->uri->segment(2) == 'load' || $this->uri->segment(2) == 'get_lookbooks') {
            
        } else {
            $this->session->sess_destroy();
            redirect('home');
        }
        
    }
    function index() {
        /*if (!$this->session->userdata('webhooks_created')) {
            $this->create_webhooks();
            $this->session->set_userdata('webhooks_created', 1);
        }*/
        $this->load->model('customers/customers_model');
        $shop = $this->customers_model->read($this->session->userdata('shop'))->row();
        
        $this->session->set_userdata('actual_theme', $shop->actual_theme);
        if (!$this->session->userdata('theme_on_store')) {
            $this->check_theme();
        }
        
        $this->session->set_userdata('page_template_selected', $shop->page_template_selected);
        $this->session->set_userdata('id', $shop->id);
        $display_mailchimp_add=false;
        if($shop->mailchimp_checked==0 && !$this->session->userdata('display_mailchimp_add')){
            $store_data=json_decode($shop->data);
            $store=$store_data->shop;
            $this->load->library('Mailchimp');
            $options=array('id'=>'c008db2546', 
                        'email'=>array(
                            'email'=>$store->email
                            ),
                        'merge_vars'=>array(
                            'mc_location'=>
                                array(
                                    'latitude'=>$store->latitude,
                                    'longitude'=>$store->longitude)
                            ),
                        'send_welcome'=>false
                        );
            $this->mailchimp->init(array('apikey'=>'572d83137149af9235eab139a7312d8e-us2'));
            try{
                $data=$this->mailchimp->call('/lists/subscribe', $options);
                if(isset($data['email'])){
                    $shop_data=array(
                        'shop'=>$this->session->userdata('shop'),
                        'mailchimp_checked'=>1
                    );
                    $this->customers_model->update($shop_data);
                }
                $display_mailchimp_add=true;
                $this->session->set_userdata('display_mailchimp_add',true);
            }catch(Mailchimp_HttpError $e){
                $shop_data=array(
                        'shop'=>$this->session->userdata('shop'),
                        'mailchimp_checked'=>1
                );
                $this->customers_model->update($shop_data);
                $this->session->set_userdata('display_mailchimp_add',true);
            }            
        }
        if(!$this->session->userdata('page_template_selected')){
            if(!$shop->page_template_selected){
               
                    $this->check_theme_dawn();
                    if($this->session->userdata('main_theme_dawn') == '887') {
                       $this->session->set_userdata('page_template_selected', 'templates/page.json'); 
                    }
                    else {
                        $this->session->set_userdata('page_template_selected', 'templates/page.liquid');
                    }
                
                
            }else{
                $this->session->set_userdata('page_template_selected', $shop->page_template_selected);
            }
        }
        $lookbooks = $this->lookbooks_model->list_lookbooks_shop($this->session->userdata('id'));
        
        /*$this -> refund_customer();*/
        $this->template
                ->set('display_mailchimp_add', $display_mailchimp_add)
                ->set('lookbooks', $lookbooks)
                ->set('shop', $shop)
                ->build('dashboard');
    }
    
    /*public function refund_customer()
    {
       
        if ($this->session->userdata('shop') == "1st-man-1st-woman.myshopify.com")
        {
             $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            $data = $this->shopify->call('POST', '/admin/api/2024-01/application_credits.json', array('application_credit' => array('description'=>'application credit for refund' ,'amount'=>29.00)));
            if ($data['status_response'] == 'success') {
                var_dump(http_response_code());
            }
            else{

                echo "Soemthing is wrong";
            }

        }
        
        
    }*/
    
   
    public function check_theme() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $data = $this->shopify->call('GET', '/admin/api/2024-01/themes.json');
            if ($data['status_response'] == 'success') {
                $this->load->model('customers/customers_model');
                foreach ($data['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        if ($this->session->userdata('actual_theme') == '') {
                            $data = array(
                                'shop' => $this->session->userdata('shop'),
                                'actual_theme' => $theme['id']
                            );
                            $this->customers_model->update($data);
                            $this->session->set_userdata('theme_changed', 0);
                            $this->session->set_userdata('actual_theme',$theme['id']);
                        } else {
                            if ($this->session->userdata('actual_theme') != $theme['id']) {
                                $this->session->set_userdata('theme_changed', 1);
                            } else {
                                $this->session->set_userdata('theme_changed', 0);
                            }
                        }
                        $this->session->set_userdata('theme_on_store', $theme['id']);
                        return true;
                    }
                endforeach;
            }
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    public function check_theme_dawn() {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('GET', '/admin/api/2024-01/themes.json');
                if ($data['status_response'] == 'success') {
                    $this->load->model('customers/customers_model');
                    foreach ($data['themes'] as $theme):
                        if ($theme['role'] == 'main') {                        
                                $data = array(
                                    'shop' => $this->session->userdata('shop'),
                                    'main_theme_dawn' => $theme['theme_store_id']
                                );
                                $this->customers_model->update($data);
                               
                        
                            $this->session->set_userdata('main_theme_dawn', $theme['theme_store_id']);
                            return true;
                        }
                    endforeach;
                }
            } catch (ShopifyCurlException $e) {
                print_r($e->getMessage());
                return false;
            }
        }

    public function add() {
        if ($this->session->userdata('shop') && $this->session->userdata('status') == 'active') {
            $this->load->model('customers/customers_model');
            $query = $this->customers_model->read($this->session->userdata('shop'));
            if ($query->num_rows() > 0) {
                $customer = $query->row();
                $this->session->set_userdata('id', $customer->id);
                foreach ($this->validation as $val):
                    $settings[$val['field']] = '';
                endforeach;
                $updated = strtotime(now());
                if (!$customer->page_templates || (date('Y-m-d', strtotime($customer->page_templates_updated)) < date('Y-m-d'))) {
                    $templates = $this->getPageTemplates();
                } else {
                    $templates = json_decode($customer->page_templates);
                }
                $this->load->model('themes/themes_model');
                $theme_data = $this->themes_model->get('1');
                $theme = array(
                    'theme_image' => $theme_data->image,
                    'theme_title' => $theme_data->title,
                );
                $settings['theme'] = 1;
                $this->template
                        ->append_js('functions.js')
                        ->set('id', '')
                        ->set('security', '')
                        ->set('updated', $updated)
                        ->set('templates', $templates)
                        ->set('theme_data', $theme)
                        ->build('form', $settings);
            } else {
                $this->session->unset_userdata('shop');
                $this->session->unset_userdata('id');
                $this->session->sess_destroy();
                redirect('home');
            }
        } else {
            redirect('home');
        }
    }
    public function check_site(){
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        $this->load->model('customers/customers_model');
        $query = $this->customers_model->read($this->session->userdata('shop'));
            if ($query->num_rows() > 0) {
                $customer = $query->row();
            print_r($customer);
            $data = $this->shopify->call('GET', '/admin/api/2024-01/pages/' . $customer->lookbooks_page_id . '.json');
                if ($data['status_response'] == 'success') {
                    /*$data = $this->shopify->call('PUT', '/admin/api/2024-01/pages/' . $id . '.json', array('page' => array('title' => $title, 'template_suffix' => 'lookbooks-modmedia-single')));
                    if ($data['status_response'] == 'success') {
                        return $data;
                    } else {
                        return false;
                    }*/
                    echo 'exists';
                    print_r($data);
                }else{
                    /*$data = $this->shopify->call('POST', '/admin/api/2024-01/pages.json', array('page' => array('title' => $title, 'template_suffix' => 'lookbooks-modmedia-single')));
                        if ($data['status_response'] == 'success') {
                            return $data;
                        } else {
                            return false;
                        }*/
                    echo 'Nahh nope';
                }
            }
    }
    public function edit($id, $security) {
        if (sha1($id . $this->config->item('encryption_key')) == $security) {
            $this->load->model('customers/customers_model');
            $query = $this->customers_model->read($this->session->userdata('shop'));
            if ($query->num_rows() > 0) {
                $customer = $query->row();
                $this->session->set_userdata('id', $customer->id);
                $set = $this->lookbooks_model->get($id);
                $settings = (array) $set;
                $theme = array();
                if ($set) {
                    foreach ($this->validation as $val):
                        if (!isset($settings[$val['field']])) {
                            $settings[$val['field']] = '';
                        }
                    endforeach;
                    $updated = $set->updated;
                    if ($set->theme) {
                        $theme = array(
                            'theme_lightbox_type' => $set->theme_lightbox_type,
                            'theme_image' => $set->theme_image,
                            'theme_title' => $set->theme_title,
                        );
                    }
                } else {
                    foreach ($this->validation as $val):
                        $settings[$val['field']] = '';
                    endforeach;
                    $updated = strtotime(now());
                }
                $this->load->model('lookbooks_pages_model');
                $pages = $this->lookbooks_pages_model->list_pages_lookbook($id);
                if (!$customer->page_templates || (date('Y-m-d', strtotime($customer->page_templates_updated)) < date('Y-m-d'))) {
                    $templates = $this->getPageTemplates();
                } else {
                    $templates = json_decode($customer->page_templates);
                }
                $this->load->model('customers/customers_model');
                $shop = $this->customers_model->read($this->session->userdata('shop'))->row();
                $this->template
                        ->set('security', $security)
                        ->set('pages', $pages)
                        ->set('shop_data', $shop)
                        ->set('theme_data', $theme)
                        ->set('templates', $templates)
                        ->set('lookbooks_page_id',$customer->lookbooks_page_id)
                        ->set('updated', $updated)
                        ->build('form', $settings);
            } else {
                $this->session->unset_userdata('shop');
                $this->session->unset_userdata('id');
                $this->session->sess_destroy();
                redirect('home');
            }
        } else {
            redirect('home');
        }
    }
    public function create_single(){
        $this->lookbook_themes->create_page_lookbooks_single();
    }
    public function create_list(){
        $this->lookbook_themes->create_page_lookbooks_list();
    }
    public function save_cloud(){
        $id = $this->input->post('id');
        $security = $this->input->post('s');
        if ((!$id && !$security) || sha1($id . $this->config->item('encryption_key')) == $security) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->validation);
            if ($this->form_validation->run() == false) {
                if (!$id) {
                    $this->session->set_flashdata('error', validation_errors());
                    $this->add();
                } else {
                    $this->session->set_flashdata('error', validation_errors());
                    $this->edit($id, $security);
                }
            } else {
                $lookbook = $this->lookbooks_model->get($id);
                $remove_image = false;
                if (!empty($_FILES['feature_image']['name'])) {
                    $uploaded = $this->do_upload('feature_image', 'lookbook-' . sha1(trim($this->session->userdata('shop')) . $this->config->item('encryption_key')) . '-feature-image', $this->session->userdata('shop'));
                    if ($uploaded) {
                        if ($uploaded['status'] == 'success') {
                            $image = json_encode($uploaded['upload_data']);
                        } else {
                            $image = '';
                        }
                        $data = array(
                            'shop' => $this->session->userdata('id'),
                            'feature_image' => $image
                        );
                    } else {
                        $data = array(
                            'shop' => $this->session->userdata('id')
                        );
                    }
                } else {
                    if ($this->input->post('old_image')) {
                        $data = array(
                            'shop' => $this->session->userdata('id')
                        );
                    } else {
                        $data = array(
                            'shop' => $this->session->userdata('id')
                        );
                        if (isset($lookbook->feature_image)) {
                            $remove_image = true;
                        }
                    }
                }
                if (!$this->session->userdata('id')) {
                    $customer = $this->customers_model->read($this->session->userdata('shop'));
                    if ($customer) {
                        $customer = $customer->row();
                        $this->session->set_userdata('id', $customer->id);
                    }
                }
                if ($this->session->userdata('id')) {
                    $data['title'] = $this->input->post('title');
                    if ($this->input->post('theme')) {
                        $data['theme'] = $this->input->post('theme');
                    }
                    $data['active'] = $this->input->post('active');

                    if (isset($lookbook->title)) {
                        $title = $lookbook->title;
                    } else {
                        $title = '';
                    }
                    if ($this->input->post('title') != $title) {
                        if (!isset($lookbook->shopify_page_id)) {
                            $shopify_page = $this->create_shopify_page($this->input->post('title'));
                            if (isset($shopify_page['page']['id'])) {
                                $data['shopify_page_id'] = $shopify_page['page']['id'];
                                $data['shopify_page_handle'] = $shopify_page['page']['handle'];
                            }
                        } else {
                            if (!$result_shopify=$this->edit_shopify_page($lookbook->shopify_page_id, $this->input->post('title'))) {
                                $this->session->set_flashdata('error', 'Problems changing shopify lookbook page');
                                redirect('lookbook/edit/' . $id . '/' . $security, 'refresh');
                            }else{
                                if($result_shopify['page']['id']!=$lookbook->shopify_page_id){
                                    $data['shopify_page_id'] = $result_shopify['page']['id'];
                                    $data['shopify_page_handle'] = $result_shopify['page']['handle'];
                                }
                            }
                        }
                    } else {
                        $this->session->set_flashdata('success', "The lookbook has been saved successfully");
                    }

                    if ($lookbook) {
                        $data['id'] = $id;
                        if ($this->input->post('sort')) {
                            $data['sort'] = $this->input->post('sort');
                        }
                        if ($this->lookbooks_model->update($data)) {
                            if (isset($data['feature_image'])) {
                                if ($this->upload_feature_image_to_shopify($id)) {
                                    $success = true;
                                } else {
                                    $success = false;
                                }
                            } else {
                                if ($remove_image) {
                                    if ($this->remove_feature_image_in_shopify($id)) {
                                        $success = true;
                                    } else {
                                        $success = false;
                                    }
                                } else {
                                    $success = true;
                                }
                            }
                        } else {
                            $success = false;
                        }
                    } else {
                        if ($id = $this->lookbooks_model->insert($data)) {
                            if (isset($data['feature_image'])) {
                                if ($this->upload_feature_image_to_shopify($id)) {
                                    $success = true;
                                } else {
                                    $success = false;
                                }
                            }
                            $success = true;
                        } else {
                            $success = false;
                        }
                    }
                    if ($success) {
                        $this->session->set_flashdata('success', "The lookbook has been saved successfully");
                        
                    } else {
                        $this->session->set_flashdata('error', "You are trying to access in a non-permited way");
                    }
                } else {
                    $this->session->set_flashdata('error', "There was an error trying to save your lookbook, please contact us.");
                }
                if (!$security) {
                    $security = sha1($id . $this->config->item('encryption_key'));
                }
                redirect('lookbook/update_shop/' . $id . '/' . $security.'/'.time(), 'refresh');
            }
        } else {
            $this->session->set_flashdata('error', "You are trying to access in a non-permited way");
            redirect('dashboard');
        }
    }
    public function create_javas(){
        $lookbooks=$this->lookbooks_model->list_lookbooks_shop($this->session->userdata('id'));
        
        $this->lookbook_themes->create_list_javascript_test();
        print_r($lookbooks);
        echo 'Done';
    }
    public function theme_test(){
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
        $theme_id = $themes['themes'][0]['id'];
        $theme_mobile_id='';
        print_r($themes['themes']);
        foreach ($themes['themes'] as $theme):
            if ($theme['role'] == 'main') {
                $theme_id = $theme['id'];
            }else{
                if ($theme['role'] == 'mobile') {
                    $theme_mobile_id = $theme['id'];
                }
            }
        endforeach;
    }
    public function update_shop($id,$security){
        $this->lookbook_themes->create_javascript($id,$security);
        $this->lookbook_themes->create_list_javascript($id,$security);       
        $this->lookbook_themes->upload_lookbook_styles();
        $this->lookbook_themes->upload_lookbook_scripts();
        if ($this->lookbook_themes->create_page_lookbooks_single()) {
            if($this->lookbook_themes->create_page_lookbooks_list()){
                $this->session->set_flashdata('success', "The lookbook has been saved successfully");
            }else{
                $this->session->set_flashdata('error', 'Error: '.$this->lookbook_themes->get_error());
            }                            
        } else {
            $this->session->set_flashdata('error', 'Error: '.$this->lookbook_themes->get_error());
        }
        $this->load->model('customers/customers_model');
                $shop = $this->customers_model->read($this->session->userdata('shop'));
                $customer = $shop->row();
                if (!$customer->lookbooks_page_id) {
                    $data = $this->create_shopify_lookbooks_page('Lookbooks');
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'lookbooks_page_id' => $data['page']['id'],
                        'lookbooks_page_slug' => $data['page']['handle']
                    );
                    $this->customers_model->update($shop_data);
                } else {
                    if (!$customer->lookbooks_page_slug) {
                        $data = $this->get_shopify_lookbooks_page($customer->lookbooks_page_id);
                        $shop_data = array(
                            'shop' => $this->session->userdata('shop'),
                            'lookbooks_page_slug' => $data['page']['handle']
                        );
                        $this->customers_model->update($shop_data);
                    }else{
                        $data = $this->get_shopify_lookbooks_page($customer->lookbooks_page_id);
                        if($data['status_response']=='error'){
                            $data = $this->create_shopify_lookbooks_page('Lookbooks');
                            $shop_data = array(
                                'shop' => $this->session->userdata('shop'),
                                'lookbooks_page_id' => $data['page']['id'],
                                'lookbooks_page_slug' => $data['page']['handle']
                            );
                            $this->customers_model->update($shop_data);
                        }                        
                    }
                }
        redirect('lookbook/edit/' . $id . '/' . $security, 'refresh');
    }
    public function list_lookbooks_shop($id=null){
        $this->lookbook_themes->create_list_javascript($this->session->userdata('id'),sha1( $this->session->userdata('id') . $this->config->item('encryption_key')));
        $lookbooks = $this->lookbooks_model->list_lookbooks_shop_with_products($this->session->userdata('id'),1);
        print_r($lookbooks);
    }
    public function save_no() {
        $id = $this->input->post('id');
        $security = $this->input->post('s');
        if ((!$id && !$security) || sha1($id . $this->config->item('encryption_key')) == $security) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->validation);
            if ($this->form_validation->run() == false) {
                if (!$id) {
                    $this->session->set_flashdata('error', validation_errors());
                    $this->add();
                } else {
                    $this->session->set_flashdata('error', validation_errors());
                    $this->edit($id, $security);
                }
            } else {
                redirect('lookbook/edit/' . $id . '/' . $security, 'refresh');
            }
        } else {
            $this->session->set_flashdata('error', "You are trying to access in a non-permited way");
            redirect('dashboard');
        }
    }
    public function save() {
        $id = $this->input->post('id');
        $security = $this->input->post('s');
        if ((!$id && !$security) || sha1($id . $this->config->item('encryption_key')) == $security) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->validation);
            if ($this->form_validation->run() == false) {
                if (!$id) {
                   // $this->session->set_flashdata('error', validation_errors());
                    $this->add();
                } else {
                   // $this->session->set_flashdata('error', validation_errors());
                    $this->edit($id, $security);
                }
            } else {
                $lookbook = $this->lookbooks_model->get($id);
                $remove_image = false;
                if (!empty($_FILES['feature_image']['name'])) {
                    $uploaded = $this->do_upload('feature_image', 'lookbook-' . sha1(trim($this->session->userdata('shop')) . $this->config->item('encryption_key')) . '-feature-image', $this->session->userdata('shop'));
                    if ($uploaded) {
                        if ($uploaded['status'] == 'success') {
                            $image = json_encode($uploaded['upload_data']);
                        } else {
                            $image = '';
                        }
                        $data = array(
                            'shop' => $this->session->userdata('id'),
                            'feature_image' => $image
                        );
                    } else {
                        $data = array(
                            'shop' => $this->session->userdata('id')
                        );
                    }
                } else {
                    if ($this->input->post('old_image')) {
                        $data = array(
                            'shop' => $this->session->userdata('id')
                        );
                    } else {
                        $data = array(
                            'shop' => $this->session->userdata('id')
                        );
                        if (isset($lookbook->feature_image)) {
                            $remove_image = true;
                        }
                    }
                }
                if (!$this->session->userdata('id')) {
                    $customer = $this->customers_model->read($this->session->userdata('shop'));
                    if ($customer) {
                        $customer = $customer->row();
                        $this->session->set_userdata('id', $customer->id);
                    }
                }
                if ($this->session->userdata('id')) {
                    $data['title'] = $this->input->post('title');
                    if ($this->input->post('theme')) {
                        $data['theme'] = $this->input->post('theme');
                    }
                    $data['active'] = $this->input->post('active');

                    if (isset($lookbook->title)) {
                        $title = $lookbook->title;
                    } else {
                        $title = '';
                    }
                    if ($this->input->post('title')) {
                        if (!isset($lookbook->shopify_page_id)) {
                            $shopify_page = $this->create_shopify_page($this->input->post('title'));
                            if (isset($shopify_page['page']['id'])) {
                                $data['shopify_page_id'] = $shopify_page['page']['id'];
                                $data['shopify_page_handle'] = $shopify_page['page']['handle'];
                            }
                        } else {
                            if (!$result_shopify=$this->edit_shopify_page($lookbook->shopify_page_id, $this->input->post('title'))) {
                                $this->session->set_flashdata('error', 'Problems changing shopify lookbook page');
                                redirect('lookbook/edit/' . $id . '/' . $security, 'refresh');
                            }else{
                                if($result_shopify['page']['id']!=$lookbook->shopify_page_id){
                                    $data['shopify_page_id'] = $result_shopify['page']['id'];
                                    $data['shopify_page_handle'] = $result_shopify['page']['handle'];
                                }
                            }
                        }
                    } else {
                        $this->session->set_flashdata('success', "The lookbook has been saved successfully");
                    }

                    if ($lookbook) {
                        $data['id'] = $id;
                        if ($this->input->post('sort')) {
                            $data['sort'] = $this->input->post('sort');
                        }
                        if ($this->lookbooks_model->update($data)) {
                            if (isset($data['feature_image'])) {
                                if ($this->upload_feature_image_to_shopify($id)) {
                                    $success = true;
                                } else {
                                    $success = false;
                                }
                            } else {
                                if ($remove_image) {
                                    if ($this->remove_feature_image_in_shopify($id)) {
                                        $success = true;
                                    } else {
                                        $success = false;
                                    }
                                } else {
                                    $success = true;
                                }
                            }
                            $this->lookbook_themes->create_javascript($lookbook->id, sha1($lookbook->id . $this->config->item('encryption_key')));                            
                        } else {
                            $success = false;
                        }
                    } else {
                        if ($id = $this->lookbooks_model->insert($data)) {
                            if (isset($data['feature_image'])) {
                                if ($this->upload_feature_image_to_shopify($id)) {
                                    $success = true;
                                } else {
                                    $success = false;
                                }
                            }
                            $success = true;
                        } else {
                            $success = false;
                        }
                    }
                    if ($success) {
                        $this->lookbook_themes->upload_lookbook_styles();
                        $this->lookbook_themes->upload_lookbook_scripts();
                        if ($this->lookbook_themes->create_page_lookbooks_single()) {
                            if($this->lookbook_themes->create_page_lookbooks_list()){
                                $this->session->set_flashdata('success', "The lookbook has been saved successfully");
                            }else{
                                //check                         
                               // $this->session->set_flashdata('error', 'Error: '.$this->lookbook_themes->get_error());
                            }                            
                        } else {                            
                           // $this->session->set_flashdata('error', 'Error: '.$this->lookbook_themes->get_error());
                        }
                        $this->session->set_flashdata('success', "The lookbook has been saved successfully");
                        
                    } else {
                        $this->session->set_flashdata('error', "You are trying to access in a non-permited way");
                    }
                } else {
                    $this->session->set_flashdata('error', "There was an error trying to save your lookbook, please contact us.");
                }
                if (!$security) {
                    $security = sha1($id . $this->config->item('encryption_key'));
                }
                $this->load->model('customers/customers_model');
                $shop = $this->customers_model->read($this->session->userdata('shop'));
                $customer = $shop->row();
                if (!$customer->lookbooks_page_id) {
                    $data = $this->create_shopify_lookbooks_page('Lookbooks');
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'lookbooks_page_id' => $data['page']['id'],
                        'lookbooks_page_slug' => $data['page']['handle']
                    );
                    $this->customers_model->update($shop_data);
                } else {
                    if (!$customer->lookbooks_page_slug) {
                        $data = $this->get_shopify_lookbooks_page($customer->lookbooks_page_id);
                        $shop_data = array(
                            'shop' => $this->session->userdata('shop'),
                            'lookbooks_page_slug' => $data['page']['handle']
                        );
                        $this->customers_model->update($shop_data);
                    }else{
                        $data = $this->get_shopify_lookbooks_page($customer->lookbooks_page_id);
                        if($data['status_response']=='error'){
                            $data = $this->create_shopify_lookbooks_page('Lookbooks');
                            $shop_data = array(
                                'shop' => $this->session->userdata('shop'),
                                'lookbooks_page_id' => $data['page']['id'],
                                'lookbooks_page_slug' => $data['page']['handle']
                            );
                            $this->customers_model->update($shop_data);
                        }                        
                    }
                }
               redirect('lookbook/edit/' . $id . '/' . $security, 'refresh');
            }
        } else {
            $this->session->set_flashdata('error', "You are trying to access in a non-permited way");
            redirect('dashboard');
        }
    }
    public function verify_page(){
        $this->load->model('customers/customers_model');
        $shop = $this->customers_model->read($this->session->userdata('shop'));
                $customer = $shop->row();
        $data = $this->get_shopify_lookbooks_page($customer->lookbooks_page_id);
        print_r($data);
    }
    public function set_order($security=false){
        if($security==sha1($this->config->item('encryption_key').'set0rder')){
            $neworder=$this->input->post('order');
            if($this->lookbooks_model->update_order($this->input->post('order'))){
                $lookbooks=explode(',',$this->input->post('order'));
                if($lookbooks){
                    /*foreach($lookbooks as $lookbook):
                        $this->lookbook_themes->create_javascript($lookbook, sha1($lookbook . $this->config->item('encryption_key')));                            
                    endforeach;*/
                    $this->lookbook_themes->create_list_javascript(10, sha1(10 . $this->config->item('encryption_key')));
                    /*fix for lookbooks sort - start*/
                   
                    $this->lookbook_themes->create_page_lookbooks_list();
                                
                    /*fix for lookbooks sort - end*/   
                                                                
                }
                $json=array('status'=>'success','msg'=>'The lightboxes were ordered successfully');
                
            }else{
                $json=array('status'=>'error','msg'=>'There was an error trying to sort the lookbooks, please try again');
            }
        }else{
           $json=array('status'=>'error','msg'=>'You are trying to access to a restricted option');
        }
        echo json_encode($json);
    }
    public function upload_scripts(){
            $this->lookbook_themes->upload_lookbook_styles();
            $this->lookbook_themes->upload_lookbook_scripts();
            echo 'done';
    }
    public function getPageTemplates() {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
                if(isset($themes['themes'])){
                    $theme_id = $themes['themes'][0]['id'];
                    foreach ($themes['themes'] as $theme):
                        if ($theme['role'] == 'main') {
                            $theme_id = $theme['id'];
                        }
                    endforeach;
                    $data = $this->shopify->call('GET', '/admin/api/2024-01/themes/' . $theme_id . '/assets.json');
                    if ($data['status_response'] == 'success') {
                        $pages = array();
                        foreach ($data['assets'] as $asset) {
                            $content = explode('/', $asset['key']);
                            if ($content[0] == 'templates') {
                                if($this->session->userdata('main_theme_dawn') == '887'){
                                    if (strpos($content[1], 'page') !== false && $content[1] != 'page.lookbooks-modmedia-list.json' && $content[1] != 'page.lookbooks-modmedia-single.json') {
                                        $value = str_replace('.json', '', $content[1]);
                                        $pages[str_replace('page', 'a', $value)] = array(
                                            'key' => $asset['key'],
                                            'value' => $value
                                        );
                                    }
                                } else {
                                        if (strpos($content[1], 'page') !== false && $content[1] != 'page.lookbooks-modmedia-list.liquid' && $content[1] != 'page.lookbooks-modmedia-single.liquid') {
                                        $value = str_replace('.liquid', '', $content[1]);
                                        $pages[str_replace('page', 'a', $value)] = array(
                                            'key' => $asset['key'],
                                            'value' => $value
                                        );
                                    }
                                }
                                
                            }
                        }
                        ksort($pages);
                        $page_templates = array();
                        foreach ($pages as $page) {
                            $page_templates[$page['key']] = $page['value'];
                        }
                        $data = array(
                            'shop' => $this->session->userdata('shop'),
                            'page_templates' => json_encode($page_templates),
                            'page_templates_updated' => date('Y-m-d H:i:s')
                        );
                        $this->load->model('customers/customers_model');
                        $this->customers_model->update($data);
                        return $page_templates;
                    } else {
                        return false;
                    }
                }else{
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateTemplates() {
        $result = $this->getPageTemplates();
        if ($result) {
            $query = $this->customers_model->read($this->session->userdata('shop'));
            if ($query->num_rows() > 0) {
                $customer = $query->row();
                $templates = json_decode($customer->page_templates);
                $json = array('status' => 'success', 'templates' => $templates);
            } else {
                $json = array('status' => 'error');
            }
        } else {
            $json = array('status' => 'error');
        }
        echo json_encode($json);
    }

    private function upload_feature_image_to_shopify($id) {
        if ($id) {
            $lookbook = $this->lookbooks_model->get($id);
            if ($this->session->userdata('token')) {
                $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                try {
                    $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
                    $theme_id = $themes['themes'][0]['id'];
                    foreach ($themes['themes'] as $theme):
                        if ($theme['role'] == 'main') {
                            $theme_id = $theme['id'];
                        }
                    endforeach;
                    $image = json_decode($lookbook->feature_image);
                    if(file_exists('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $image->file_name)){
                        $data = $this->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'assets/lookbook-modmedia-' . $lookbook->id . '-feature' . $image->file_ext, 'src' => str_replace(FCPATH, site_url(), $image->full_path))));
                        if ($data['status_response'] == 'success') {
                            unlink('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $image->file_name);
                            $new_data = array(
                                'id' => $id,
                                'feature_image' => json_encode(array('file_name' => 'lookbook-modmedia-' . $lookbook->id . '-feature' . $image->file_ext, 'full_path' => $data['asset']['public_url'], 'image_width' => $image->image_width, 'image_height' => $image->image_height, 'file_ext' => $image->file_ext))
                            );
                            $this->lookbooks_model->update($new_data);
                            return $data;
                        } else {
                            return false;
                        }
                    }
                    return true;
                } catch (ShopifyCurlException $e) {
                    echo 'Curl ';
                    print_r($e->getMessage());
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function remove_feature_image_in_shopify($id) {
        if ($id) {
            $lookbook = $this->lookbooks_model->get($id);
            if ($this->session->userdata('token')) {
                $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                try {
                    $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
                    $theme_id = $themes['themes'][0]['id'];
                    foreach ($themes['themes'] as $theme):
                        if ($theme['role'] == 'main') {
                            $theme_id = $theme['id'];
                        }
                    endforeach;
                    $image = json_decode($lookbook->feature_image);
                    if(isset($image->file_ext)){
                    $data = $this->shopify->call('DELETE', '/admin/themes/' . $theme_id . '/assets.json?asset[key]=assets/lookbook-modmedia-' . $lookbook->id . '-feature' . $image->file_ext);
                    if ($data['status_response'] == 'success') {
                        $new_data = array(
                            'id' => $id,
                            'feature_image' => ''
                        );
                        $this->lookbooks_model->update($new_data);
                        return $data;
                    } else {
                        return false;
                    }}else{
                        return true;
                    }
                } catch (ShopifyCurlException $e) {
                    echo 'Curl ';
                    print_r($e->getMessage());
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function delete($id, $security) {
        if (sha1($id . $this->config->item('encryption_key')) == $security) {
            $lookbook = $this->lookbooks_model->get($id);
            if ($lookbook) {
                if ($lookbook->shopify_page_id) {
                    $page_deleted = $this->delete_lookbook_shopify($lookbook);
                } else {
                    $page_deleted = true;
                }
                if ($page_deleted) {
                    if ($this->lookbooks_model->delete($id)) {
                        if ($this->lookbook_themes->create_list_javascript($id, $security)) {
                                $json = array('status' => 'success');                            
                        }else{
                            $json = array('status' => 'error', 'msg' => 'There was an error trying to update the lookbooks code');
                        }                 
                    } else {
                        $json = array('status' => 'error', 'msg' => 'There was an error in the database');
                    }
                } else {
                    $json = array('status' => 'error', 'msg' => "Problems trying to delete the shopify page");
                }
            } else {
                $json = array('status' => 'error', 'msg' => "The lookbook doesn't exist");
            }
        } else {
            $json = array('status' => 'error', 'msg' => "You are trying to access in a non-permited way");
        }
        echo json_encode($json);
    }

    private function create_shopify_page($title) {
        if(strtolower($title)=='lookbooks'){
            $title=='Main Lookbook';
        }
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('POST', '/admin/pages.json', array('page' => array('title' => $title, 'body_html' => '', 'template_suffix' => 'lookbooks-modmedia-single')));
                if ($data['status_response'] == 'success') {
                    return $data;
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
        } else {
            echo 'What!!!';
        }
    }

    private function create_shopify_lookbooks_page($title) {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('POST', '/admin/pages.json', array('page' => array('title' => $title, 'body_html' => '', 'template_suffix' => 'lookbooks-modmedia-list')));
                if ($data['status_response'] == 'success') {
                    return $data;
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
        } else {
            echo 'What!!!';
        }
    }

    private function get_shopify_lookbooks_page($page_id) {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('GET', '/admin/api/2024-01/pages/' . $page_id . '.json');
                if ($data['status_response'] == 'success') {
                    if($data['page']['template_suffix'] != 'lookbooks-modmedia-list'){
                        return $this->update_shopify_lookbooks_page($page_id);
                    }else{
                        return $data;
                    }
                } else {
                    return $data;
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
        }
    }
    public function update_shopify_lookbooks_page($page_id){
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('PUT', '/admin/pages/' . $page_id . '.json', array('page' => array('template_suffix' => 'lookbooks-modmedia-list')));
                if ($data['status_response'] == 'success') {
                    return $data;
                } else {
                    return $data;
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
    }

    private function edit_shopify_page($id, $title) {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('GET', '/admin/api/2024-01/pages/' . $id . '.json');
                if ($data['status_response'] == 'success') {
                    $data = $this->shopify->call('PUT', '/admin/pages/' . $id . '.json', array('page' => array('title' => $title, 'template_suffix' => 'lookbooks-modmedia-single')));
                    if ($data['status_response'] == 'success') {
                        return $data;
                    } else {
                        return false;
                    }
                }else{
                    $data = $this->shopify->call('POST', '/admin/pages.json', array('page' => array('title' => $title, 'template_suffix' => 'lookbooks-modmedia-single')));
                        if ($data['status_response'] == 'success') {
                            return $data;
                        } else {
                            return false;
                        }
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
        } else {
            echo 'What!!!';
        }
    }

    private function delete_lookbook_shopify($lookbook) {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
                $theme_id = $themes['themes'][0]['id'];
                foreach ($themes['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        $theme_id = $theme['id'];
                    }
                endforeach;
                //Delete lookbook feature image
                $image = json_decode($lookbook->feature_image);
                $data = @$this->shopify->call('DELETE', '/admin/themes/' . $theme_id . '/assets.json?asset[key]=assets/lookbook-modmedia-' . $lookbook->id . '-feature' . $image->file_ext);
                if ($data['status_response'] == 'success') {
                    //Delete lookbook page
                    $data = $this->shopify->call('GET', '/admin/api/2024-01/pages/' . $lookbook->shopify_page_id . '.json');
                    if ($data['status_response'] == 'success') {
                        $data = $this->shopify->call('DELETE', '/admin/pages/' . $lookbook->shopify_page_id . '.json');
                        if ($data['status_response'] == 'success') {
                            $this->load->model('lookbooks_pages_model');
                            $pages = $this->lookbooks_pages_model->list_pages_lookbook($lookbook->id);
                            foreach ($pages as $page) {
                                $image = json_decode($page->image);
                                $data = $this->shopify->call('DELETE', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'assets/lookbook-modmedia-' . $page->lookbook . '-page-' . $page->id . $image->file_ext)));
                                if ($data['status_response'] == 'success') {
                                    $error = false;
                                } else {
                                    $error = true;
                                }
                            }
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                print_r($e->getMessage());
                return false;
            }
        }
    }

    private function delete_shopify_page($id) {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('GET', '/admin/api/2024-01/pages/' . $id . '.json');
                if ($data['status_response'] == 'success') {
                    $data = $this->shopify->call('DELETE', '/admin/pages/' . $id . '.json');
                    if ($data['status_response'] == 'success') {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            } catch (ShopifyCurlException $e) {
                print_r($e->getMessage());
                return false;
            }
        } else {
            echo 'What!!!';
        }
    }

    public function get_collections() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $collections = array();
            $data = $this->shopify->call('GET', '/admin/api/2024-01/smart_collections/count.json');
            if ($data['status_response'] == 'success') {
                echo $data['count'];
                if ($data['count'] > 50) {
                    $total = ceil($data['count'] / 50);
                } else {
                    $total = 1;
                }
                for ($n = 1; $n <= $total; $n++) {
                    $results = $this->shopify->call('GET', '/admin/api/2024-01/smart_collections.json', array('page' => $n, 'fields' => 'id,handle,title'));
                    if ($results['status_response'] == 'success') {
                        foreach ($results['smart_collections'] as $collection):
                            $collections[] = $collection;
                        endforeach;
                    }
                }
            }
            $data = $this->shopify->call('GET', '/admin/api/2024-01/custom_collections/count.json');
            if ($data['status_response'] == 'success') {
                echo $data['count'];
                if ($data['count'] > 50) {
                    $total = ceil($data['count'] / 50);
                } else {
                    $total = 1;
                }
                for ($n = 1; $n <= $total; $n++) {
                    $results = $this->shopify->call('GET', '/admin/api/2024-01/custom_collections.json', array('page' => $n, 'fields' => 'id,handle,title'));
                    if ($results['status_response'] == 'success') {
                        foreach ($results['custom_collections'] as $collection):
                            $collections[] = $collection;
                        endforeach;
                    }
                }
            }
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            return false;
        }
    }

    public function create_template() {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
                $theme_id = $themes['themes'][0]['id'];
                print_r($themes);
                foreach ($themes['themes'] as $theme):
                    if ($theme['role'] == 'main') {
                        $theme_id = $theme['id'];
                    }
                endforeach;

                $data = $this->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'templates/page.modmedia-lookbooks-list.liquid', 'src' => site_url('assets/templates/page.modmedia-lookbooks-list.liquid'))));
                if ($data['status_response'] == 'success') {
                    return $data;
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
        } else {
            echo 'What!!!';
        }
    }

    public function get_shop() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $collections = array();
            $data = $this->shopify->call('GET', '/admin/api/2024-01/shop.json');
            if ($data['status_response'] == 'success') {
                print_r($data);
            }
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            return false;
        }
    }
    public function update_products(){
		if($this->get_products_graphql()){          
            $json=array('status'=>'success','msg'=>'The products information was updated');
        }else{
            $json=array('status'=>'error','msg'=>'There was an error. Please try again');
        }
        echo json_encode($json);
    }
    public function get_products_test() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $products = array();
            $data = $this->shopify->call('GET', '/admin/api/2024-01/products/count.json');
            if ($data['status_response'] == 'success') {
                if ($data['count'] > 50) {
                    $total = ceil($data['count'] / 50);
                } else {
                    $total = 1;
                }
                if($total>1000){
                    $total=1000;
                }
                $CYCLE = 0.5;
                $start_time = time();
                
                if($this->session->userdata('shop')=="trendsnaps1.myshopify.com"){
                    $total=50;
                }echo $total."<===total===>".ceil($data['count'] / 50);
                for ($n = 1; $n <= $total; $n++) {
                    if($n!==1){
                        $stop_time = time();
                        echo "Last batch processing started at # ".$start_time.'<br/>';
                        echo "The time is now  # ".$stop_time.'<br/>';
                        $processing_duration = $stop_time - $start_time;
                        echo "The processing lasted # ".$processing_duration." seconds.<br/>";
                        if($this->shopify->callsLeft() < 20){
                            $wait_time = ceil($CYCLE - $processing_duration);
                            echo  "We have to wait # ".$wait_time."seconds then we will resume.<br/>";
                        }else{
                            $wait_time=0;
                            echo  "Not waiting.<br/>";
                        }
                        
                        if ($wait_time > 0){
                            sleep($wait_time);  
                        }
                        $start_time = time();
                    }
                   // echo 'Calls left '.$this->shopify->callsLeft()." <br/><br/>";
                    
                    $results = $this->shopify->call('GET', '/admin/api/2024-01/products.json', array('page' => $n, 'fields' => 'id,handle,title,product_type,vendor,images,published_at'));
                    if ($results['status_response'] == 'success') {
                        foreach ($results['products'] as $product):
                            $images = array();
                            foreach ($product['images'] as $image):
                                $images[] = $image['src'];
                            endforeach;
                            $products[] = array(
                                'shop' => $this->session->userdata('id'),
                                'handle' => $product['handle'],
                                'title' => $product['title'],
                                'product' => $product['id'],
                                'vendor' => $product['vendor'],
                                'type' => $product['product_type'],
                                'images' => json_encode($images),
                                'published_at' => $product['published_at']
                            );
                        endforeach;
                    }else{
                        echo "Error";
                        print_r($results);
                        return false;
                    }
                }
                /*$this->load->model('shop_products_model');
                if ($this->shop_products_model->update($products)) {
                    $this->load->model('customers/customers_model');
                    $updated = array(
                        'shop' => $this->session->userdata('shop'),
                        'products_updated' => date('Y-m-d H:i:s')
                    );
                    $this->customers_model->update($updated);
                    return true;
                } else {
                    return false;
                }*/
                print_r($products);
            }
        } catch (ShopifyCurlException $e) {

            print_r($e->getMessage());
            return false;
        }
    }
    
                    /* Old code for products update for "page" based pagination*/
    public function get_products_old() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $products = array();
            $data = $this->shopify->call('GET', '/admin/api/2024-01/products/count.json');
            if ($data['status_response'] == 'success') {
                if ($data['count'] > 50) {
                    $total = ceil($data['count'] / 50);
                } else {
                    $total = 1;
                }
                if($this->session->userdata('shop')== "trendsnaps1.myshopify.com" or $this->session->userdata('shop')== "newyork-dress.myshopify.com"){
                    $total=50;
                }
                
                $CYCLE = 0.5;
                $start_time = time();
                for ($n = 1; $n <= $total; $n++) {
                    if($n!==1){
                        $stop_time = time();
                        $processing_duration = $stop_time - $start_time;
                        if($this->shopify->callsLeft() < 20){
                            $wait_time = ceil($CYCLE - $processing_duration);
                        }else{
                            $wait_time=0;
                        }
                        if ($wait_time > 0){
                            sleep($wait_time);
                        }
                        $start_time = time();
                    }
                    $results = $this->shopify->call('GET', '/admin/api/2024-01/products.json', array('page' => $n, 'fields' => 'id,handle,title,product_type,vendor,images,published_at'));
                    if ($results['status_response'] == 'success') {
                        foreach ($results['products'] as $product):
                            $images = array();
                            foreach ($product['images'] as $image):
                                $images[] = $image['src'];
                            endforeach;
                                $products[] = array(
                                'shop' => $this->session->userdata('id'),
                                'handle' => $product['handle'],
                                'title' => $product['title'],
                                'product' => $product['id'],
                                'vendor' => $product['vendor'],
                                'type' => $product['product_type'],
                                'images' => json_encode($images),
                                'published_at' => $product['published_at']
                            );
                        endforeach;
                    }
                }
                $this->load->model('shop_products_model');
                if ($this->shop_products_model->update($products)) {
                    $this->load->model('customers/customers_model');
                    $updated = array(
                        'shop' => $this->session->userdata('shop'),
                        'products_updated' => date('Y-m-d H:i:s')
                    );
                    $this->customers_model->update($updated);
                    return true;
                } else {
                    return false;
                }
            }
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            return false;
        }
    }
    /* Old code for products update for "page" based pagination*/
	/* Graph QL logic START - New code for products update for "cursor" based pagination*/
public function get_products_graphql() {
	$this->shopify->init(array(
		'shop_domain' => $this->session->userdata('shop'), 
		'token' => $this->session->userdata('token'), 
		'api_key' => Settings::get('shopify_api_key'), 
		'secret' => Settings::get('shopify_secret')
	));

    try {
        $products = [];
        $nextCursor = null;

        do {
            // GraphQL query for paginated products
            $queryPart = ' query($cursor: String) { products(first: 250, after: $cursor) { edges { cursor node { id handle title vendor productType publishedAt images(first: 10) {nodes { id url height width }  } } } pageInfo { hasNextPage } }  } ';

            $graphqlQuery = trim($queryPart);

            // Use the Shopify API client to make the call
            $response = $this->shopify->call('POST', '/admin/api/2024-10/graphql.json', [
                'query' => $graphqlQuery,
                'variables' => ['cursor' => $nextCursor]
            ]);

            // Check for errors
            if ($response['status_response'] !== 'success' || !isset($response['data']['products'])) {
                throw new Exception('Failed to fetch products');
            }

            $productsData = $response['data']['products'];

            foreach ($productsData['edges'] as $productEdge) {
                $product = $productEdge['node'];
                $string = $product['id'];
                if (preg_match('/Product\/(\d+)/', $string, $matches)) {
                    $productId = $matches[1];					
                } else {
                    $productId = '';
                }
                $images = array_map(function ($imageEdge) {
                    return $imageEdge['url'];
                }, $product['images']['nodes']);

                $products[] = array(
                    'shop' => $this->session->userdata('id'),
                    'handle' => $product['handle'],
                    'title' => $product['title'],
                    'product' => $productId,
                    'vendor' => $product['vendor'],
                    'type' => $product['productType'],
                    'images' => json_encode($images),
                    'published_at' => $product['publishedAt']
                );
            }

            // Update the cursor for pagination
            $nextCursor = $productsData['pageInfo']['hasNextPage'] 
                ? end($productsData['edges'])['cursor'] 
                : null;

        } while ($nextCursor);

        // Save products to the database
        $this->load->model('shop_products_model');
        if ($this->shop_products_model->update($products)) {
            $this->load->model('customers/customers_model');
            $updated = array(
                'shop' => $this->session->userdata('shop'),
                'products_updated' => date('Y-m-d H:i:s')
            );
            $this->customers_model->update($updated);
            return true;
        } else {
            return false;
        }

    } catch (Exception $e) {
        print_r($e->getMessage());
        return false;
    }
}
	
	/* Graph QL logic END - New code for products update for "cursor" based pagination*/
	
    /* New code for products update for "cursor" based pagination*/
	
    public function get_products() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {               
                               
            //$products = array();
         
            //$product_ids = [];
            $nextPageToken = null;
                    
                do{                 
                $response = $this->shopify->call1('GET', '/admin/api/2024-01/products.json?limit=250&page_info='.$nextPageToken.'&rel=next' );           

                foreach($response['resource'] as $product):$images = array();          
                                       
                            foreach ($product['images'] as $image):
                                $images[] = $image['src'];
                            endforeach;
                                $products[] = array(
                                'shop' => $this->session->userdata('id'),
                                'handle' => $product['handle'],
                                'title' => $product['title'],
                                'product' => $product['id'],
                                'vendor' => $product['vendor'],
                                'type' => $product['product_type'],
                                'images' => json_encode($images),
                                'published_at' => $product['published_at']
                            ); 
                    
                  endforeach;     
                                                 
                    $nextPageToken= isset($response['next']['page_token']) ? $response['next']['page_token'] : null;                    
                                    
            }while($nextPageToken != null);
                
                $this->load->model('shop_products_model');        
                if ($this->shop_products_model->update($products)) {
                    $this->load->model('customers/customers_model');
                    $updated = array(
                        'shop' => $this->session->userdata('shop'),
                        'products_updated' => date('Y-m-d H:i:s')
                    );
                   $this->customers_model->update($updated);
                    return true;
                } else {
                    return false;
                }
    
            
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            return false;
        }
    }
    
    /* New code for products update for "cursor" based pagination*/
    
    /* New code for products update for "cursor" based pagination -2 */
    public function get_products1() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        
             $data = $this->shopify->call('GET', '/admin/api/2024-01/products/count.json');
            $ids = [];
$nextPageToken = null;
  do{
    $response = $this->shopify->call1('GET', '/admin/api/2024-01/products.json?limit=250&page_info='.$nextPageToken.'&rel=next' );
      
     foreach($response['resource'] as $product){
        array_push($ids, $product['id']);
     }
           // $nextPageToken = $response['next']['page_token'];
      $nextPageToken= isset($response['next']['page_token']) ? $response['next']['page_token'] : null;
    // print_r($nextPageToken);exit();
    }while($nextPageToken != null);
            
                    
                
              
    }
    /* New code for products update for "cursor" based pagination -2 */
    public function get_products2() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
             $data = $this->shopify->call('GET', '/admin/api/2024-01/products/count.json');
            //print_r($data);exit();
            
                     $products = array();
         
            $product_ids = [];
$nextPageToken = null;
  do{
    $response = $this->shopify->call1('get','products.json?limit=250&page_info='.$nextPageToken.'&rel=next');
      //print_r(sizeof($response));exit();
     foreach($response['resource'] as $product):$images = array();          
                                       
                            foreach ($product['images'] as $image):
                                $images[] = $image['src'];
                            endforeach;
                                $products[] = array(
                                'shop' => $this->session->userdata('id'),
                                'handle' => $product['handle'],
                                'title' => $product['title'],
                                'product' => $product['id'],
                                'vendor' => $product['vendor'],
                                'type' => $product['product_type'],
                                'images' => json_encode($images),
                                'published_at' => $product['published_at']
                            ); 
                    
                  endforeach;
        
                $this->load->model('shop_products_model');
        
                if ($this->shop_products_model->update($products)) {
                    $this->load->model('customers/customers_model');
                    $updated = array(
                        'shop' => $this->session->userdata('shop'),
                        'products_updated' => date('Y-m-d H:i:s')
                    );
                   $this->customers_model->update($updated);
                    return true;
                } else {
                    return false;
                }
                 
            
      $nextPageToken= isset($response['next']['page_token']) ? $response['next']['page_token'] : null;
      print_r($nextPageToken);exit();
    }while($nextPageToken != null);
                    
                 
        
    
            
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            return false;
        }
    }
    
    

    

    

                    

    public function add_page($lookbook, $security) {
		echo "<script>console.log('testing the value ');</script>";
       echo "<script>console.log('".sha1($lookbook . $this->config->item('encryption_key'))."==".$security."');</script>";
        if (sha1($lookbook . $this->config->item('encryption_key')) == $security) {
            $this->load->model('customers/customers_model');
            $query = $this->customers_model->read($this->session->userdata('shop'));
            if ($query->num_rows() > 0) {
                $customer = $query->row();
                if (date('Y-m-d', strtotime($customer->products_updated)) < date('Y-m-d')) {	
					$this->get_products_graphql();
                }
                $this->check_product_updates();
                $this->session->set_userdata('id', $customer->id);
                foreach ($this->validation as $val):
                    $settings[$val['field']] = '';
                endforeach;
                $updated = strtotime(now());
                $lookbook = $this->lookbooks_model->get($lookbook);
                $this->load->model('icons_model');
                $icons = $this->icons_model->list_icons($this->session->userdata('id'));
                if (!$icons) {
                    $this->create_icon('circle', '237_102_71_0', '255_255_255_0', false);
                    $name = 'lb_mm_circle_237102710_2552552550.png';
                    if ($icon = $this->upload_icon_to_shopify($name, 26, 26)) {
                        $icons = $this->icons_model->list_icons($this->session->userdata('id'));
                    }
                }
                if (isset($icons[0])) { 
                    $settings['default_icon_url'] = $icons[0]->url;
                    $settings['default_icon'] = $icons[0]->id;
                }
                
                $this->template
                        ->append_js('functions.js')
                        ->append_css('jquery.minicolors.css')
                        ->append_js('libs/jquery.minicolors.min.js')
                        ->set('lookbook', $lookbook)
                        ->set('ls', $security)
                        ->set('updated', $updated)
                        ->set('icons', $icons)
                        ->set('products', '')
                        ->build('page_look/step1', $settings);
            } else {
                $this->session->unset_userdata('shop');
                $this->session->unset_userdata('id');
                $this->session->sess_destroy();
                redirect('home');
            }
        } else {
            redirect('home');
        }
    }

    public function edit_page($id, $security) {
		//$graphQlResponse = $this->get_products_graphql();	
		
        if (sha1($id . $this->config->item('encryption_key')) == $security) {
            $this->load->model('customers/customers_model');
            $query = $this->customers_model->read($this->session->userdata('shop'));
            if ($query->num_rows() > 0) {
                $customer = $query->row();
                if (date('Y-m-d', strtotime($customer->products_updated)) < date('Y-m-d')) {
					$this->get_products_graphql();
                }
                $this->check_product_updates();
                $this->session->set_userdata('id', $customer->id);
                $updated = strtotime(now());
                $this->load->model('lookbooks_pages_model');
                $settings = (array) $page = $this->lookbooks_pages_model->get($id);
                $lookbook = $this->lookbooks_model->get($settings['lookbook']);
                $this->load->model('icons_model');
                $icons = $this->icons_model->list_icons($this->session->userdata('id'));
                if (!$icons) {
                    $this->create_icon('circle', '237_102_71_0', '255_255_255_0', false);
                    $name = 'lb_mm_circle_237102710_2552552550.png';
                    if ($icon = $this->upload_icon_to_shopify($name, 26, 26)) {
                        $icons = $this->icons_model->list_icons($this->session->userdata('id'));
                    }
                }
                $products = $this->lookbooks_pages_model->get_products($id);
                $settings['lookbook'] = $lookbook;
                $this->template
                        ->append_js('functions.js')
                        ->append_css('jquery.minicolors.css')
                        ->append_js('libs/jquery.minicolors.min.js')
                        ->set('id', $id)
                        ->set('security', $security)
                        ->set('ls', sha1($lookbook->id . $this->config->item('encryption_key')))
                        ->set('lookbook_info', $lookbook)
                        ->set('icons', $icons)
                        ->set('updated', $updated)
                        ->set('products', $products)
                        ->set('search', $this->search_products(true))
                        ->build('page_look/form', $settings);
            } else {
                $this->session->unset_userdata('shop');
                $this->session->unset_userdata('id');
                $this->session->sess_destroy();
                redirect('home');
            }
        } else {
            redirect('home');
        }
    }

    private function check_product_updates() {
        $this->load->model('shop_products_model');
        return $this->shop_products_model->check_products_changes($this->session->userdata('shop'), $this->session->userdata('id'));
    }

    public function delete_page($lookbook, $id, $security) {
        if (sha1($id . $this->config->item('encryption_key')) == $security) {
            $this->load->model('lookbooks_pages_model');
            if ($this->lookbooks_pages_model->delete($id)) {
                $this->lookbook_themes->create_javascript($lookbook, sha1($lookbook . $this->config->item('encryption_key')));
                $json = array('status' => 'success');
            } else {
                $json = array('status' => 'error', 'msg' => 'There was an error in the database');
            }
        } else {
            $json = array('status' => 'error', 'msg' => 'There is missing data');
        }
        echo json_encode($json);
    }

    public function save_page_look() {
        $id = $this->input->post('id');
        if ($id) {
            if (sha1($id . $this->config->item('encryption_key')) != $this->input->post('s')) {
                return false;
            }
        }
        $lookbook = $this->input->post('lookbook');
        $ls = $this->input->post('ls');
        if (sha1($lookbook . $this->config->item('encryption_key')) == $ls) {
            if (!empty($_FILES['main_picture']['name'])) {
                $uploaded = $this->do_upload_page_image('main_picture', 'lookbook-' . sha1(trim($this->session->userdata('email')) . now() . $this->config->item('encryption_key')), $this->session->userdata('shop'));
                if ($uploaded) {
                    if ($uploaded['status'] == 'success') {
                        $image = json_encode($uploaded['upload_data']);
                        $data = array(
                            'lookbook' => $lookbook,
                            'default_icon' => $this->input->post('default_icon'),
                            'image' => $image
                        );
                    } else {
                        $data = array(
                            'lookbook' => $lookbook,
                            'default_icon' => $this->input->post('default_icon'),
                        );
                        if (!$id) {
                            $this->session->set_flashdata('error', $uploaded['msg']);
                            redirect('lookbook/add_page/' . $lookbook . '/' . sha1($lookbook . $this->config->item('encryption_key')));
                        }
                    }
                } else {
                    $data = array(
                        'lookbook' => $lookbook,
                        'default_icon' => $this->input->post('default_icon')
                    );
                }
            } else {
                $data = array(
                    'lookbook' => $lookbook,
                    'default_icon' => $this->input->post('default_icon')
                );
            }
            $this->load->model('lookbooks_pages_model');
            if ($id) {
                $data['id'] = $id;
                $status = $this->lookbooks_pages_model->update($data);
                if (isset($data['image'])) {
                    if ($this->upload_page_image_to_shopify($id)) {
                        $success = true;
                    } else {
                        $success = false;
                    }
                } else {
                    $success = true;
                }
            } else {
                $status = $this->lookbooks_pages_model->insert($data);
                if (isset($data['image'])) {
                    if ($this->upload_page_image_to_shopify($status)) {
                        $success = true;
                    } else {
                        $success = false;
                    }
                } else {
                    $success = true;
                }
            }
            if ($status) {
                if (!$id) {
                    $id = $status;
                }
                $points = $this->input->post('point');
                if ($points) {
                    foreach ($points as $point) {
                        $new_point = array(
                            'id' => $point['id'],
                            'page' => $id,
                            'top' => round($point['top'], 2),
                            'left' => round($point['left'], 2),
                            'icon' => $point['icon'],
                            'image' => $point['image'],
                            'linkto' => $point['linkto'],
                            'url' => $point['url'],
                            'custom_title' => $point['custom_title'],
                            'show_image' => $point['show_image'],
                            'show_title' => $point['show_title'],
                            'show_price' => $point['show_price'],
                            'link_target' => $point['link_target'],
                            'product' => ($point['product']) ? $point['product'] : null
                        );
                        $point_data[] = $new_point;
                    }
                } else {
                    $point_data = array();
                }
                if ($this->lookbooks_pages_model->insert_products($point_data, $id)) {
                    $this->lookbook_themes->create_javascript($lookbook, sha1($lookbook . $this->config->item('encryption_key')));
                    /*fix for lookbooks product update 01/05/2019- start*/
                   
                    $this->lookbook_themes->create_page_lookbooks_single();
                                
                    /*fix for lookbooks product update 01/05/2019- end*/   
                    $this->session->set_flashdata('success', 'The page look has been saved successfully');
                } else {
                    $this->session->set_flashdata('error', 'There were errors trying to save the data.');
                }
            } else {
                $this->session->set_flashdata('error', 'There were errors trying to save the data.');
            }
            if ($this->input->post('redirect_to')) {
                redirect($this->input->post('redirect_to'), 'refresh');
            } else {
                redirect('lookbook/edit_page/' . $id . '/' . sha1($id . $this->config->item('encryption_key')), 'refresh');
            }
        }
    }

    public function load($type, $shop, $code, $security, $page = null) {
        if (sha1($shop . $this->config->item('encryption_key') . $code) == $security) {
            if ($type == 'single') {
                if (file_exists(FCPATH . 'assets/templates/' . $shop . '/mod_media_lightbox_' . $page . '.js')) {
                    redirect(site_url('assets/templates/' . $shop . '/mod_media_lightbox_' . $page . '.js'));
                } else {
                    return false;
                }
            } else {
                if (file_exists(FCPATH . 'assets/templates/' . $shop . '/mod_media_lightbox_list.js')) {
                    redirect(site_url('assets/templates/' . $shop . '/mod_media_lightbox_list.js'));
                } else {
                    return false;
                }
            }
        }
    }

    private function upload_page_image_to_shopify($id) {
        if ($id) {
            $page = $this->lookbooks_pages_model->get($id);
            if ($this->session->userdata('token')) {
                $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                try {
                    $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
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
                    $image = json_decode($page->image);
                    $data = $this->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'assets/lookbook-modmedia-' . $page->lookbook . '-page-' . $page->id . $image->file_ext, 'src' => str_replace(FCPATH, site_url(), $image->full_path))));
                    if ($data['status_response'] == 'success') {
                        if($theme_mobile_id!=''){
                            $data2 = $this->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'assets/lookbook-modmedia-' . $page->lookbook . '-page-' . $page->id . $image->file_ext, 'src' => str_replace(FCPATH, site_url(), $image->full_path))));
                            if ($data2['status_response'] == 'success') {
                                
                            }
                        }
                        unlink('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $image->file_name);
                        $new_data = array(
                            'id' => $id,
                            'image' => json_encode(array('file_name' => 'lookbook-modmedia-' . $page->lookbook . '-page-' . $page->id . $image->file_ext, 'full_path' => $data['asset']['public_url'], 'image_width' => $image->image_width, 'image_height' => $image->image_height, 'file_ext' => $image->file_ext))
                        );
                        $this->lookbooks_pages_model->update($new_data);
                        return $data;
                    } else {
                        return false;
                    }
                } catch (ShopifyCurlException $e) {
                    echo 'Curl ';
                    print_r($e->getMessage());
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    private function do_upload($field = 'image', $name = '', $folder = '') {
        $config['upload_path'] = 'uploads/lookbooks/' . $folder;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '2000';
        $config['remove_spaces'] = true;
        $config['overwrite'] = true;
        if (!is_dir('uploads/lookbooks')) {
            mkdir('uploads/lookbooks', 0777, TRUE);
        }
        if (!is_dir('uploads/lookbooks/' . $folder)) {
            mkdir('uploads/lookbooks/' . $folder, 0777, TRUE);
        }
        if ($name == '' || !$name) {
            $config['overwrite'] = false;
            $file_name = str_replace('.', '-', str_replace(' ', '', $this->input->post('email'))) . '.jpg';
        } else {
            $file_name = str_replace('.', '-', str_replace(' ', '', $name)) . '.jpg';
        }
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field)) {
            $errors = array('status' => 'error', 'msg' => $this->upload->display_errors());
            return $errors;
        } else {
            $upload_data = $this->upload->data();
            $data = array('status' => 'success', 'upload_data' => $upload_data);
            return $data;
            /* $config2['image_library'] = 'gd2';
              $config2['source_image'] = 'uploads/lookbooks/' . $folder . '/' . $upload_data['file_name'];
              $config2['new_image'] = 'uploads/lookbooks/' . $folder . '/thumb_' . $upload_data['file_name'];
              $config2['width'] = $upload_data['image_width'];
              $config2['height'] = $upload_data['image_height'];
              $config2['quality'] = 30;
              $this->load->library('image_lib');
              $this->image_lib->initialize($config2);

              if ($resize = $this->image_lib->resize()) {
              $data = array('status' => 'success', 'upload_data' => $upload_data);
              return $data;
              }
             */
        }
    }

    private function do_upload_page_image($field = 'image', $name = '', $folder = '') {
        $config['upload_path'] = 'uploads/lookbooks/' . $folder;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '2000';
        $config['remove_spaces'] = true;
        $config['overwrite'] = true;
        if (!is_dir('uploads/lookbooks')) {
            mkdir('uploads/lookbooks', 0777, TRUE);
        }
        if (!is_dir('uploads/lookbooks/' . $folder)) {
            mkdir('uploads/lookbooks/' . $folder, 0777, TRUE);
        }
        if ($name == '' || !$name) {
            $config['overwrite'] = false;
            $file_name = str_replace('.', '-', str_replace(' ', '', $this->input->post('email')));
        } else {
            $file_name = str_replace('.', '-', str_replace(' ', '', $name));
        }
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field)) {
            $errors = array('status' => 'error', 'msg' => $this->upload->display_errors());
            return $errors;
        } else {
            $upload_data = $this->upload->data();
            $data = array('status' => 'success', 'upload_data' => $upload_data);
            return $data;
            /* $config2['image_library'] = 'gd2';
              $config2['source_image'] = 'uploads/lookbooks/' . $folder . '/' . $upload_data['file_name'];
              $config2['new_image'] = 'uploads/lookbooks/' . $folder . '/thumb_' . $upload_data['file_name'];
              $config2['width'] = $upload_data['image_width'];
              $config2['height'] = $upload_data['image_height'];
              $config2['quality'] = 30;
              $this->load->library('image_lib');
              $this->image_lib->initialize($config2);

              if ($resize = $this->image_lib->resize()) {
              $data = array('status' => 'success', 'upload_data' => $upload_data);
              return $data;
              }
             */
        }
    }

    public function search_products($return = false) {
        $this->load->library('pagination');
        $offset = $this->input->get('o');       
        $keywords = $this->input->get('k');
        if (!$keywords) {
            $keywords = $this->input->post('k');
        }
        $this->load->model('shop_products_model');
        $params = array(
            'keywords' => mysql_real_escape_string(urldecode($keywords))
        );

        $config['per_page'] = 10;
        $params['limit'] = $config['per_page'];
        $params['offset'] = $offset;
        $products = $this->shop_products_model->get($this->session->userdata('id'), $params);
        $extra = '';
        if ($keywords) {
            $extra = 'k=' . $keywords;
        }
        $config['base_url'] = site_url('lookbook/search_products?' . $extra);
        $config['total_rows'] = $this->shop_products_model->count($this->session->userdata('id'), $params);
        $config['uri_segment'] = 3;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'o';

        $config['num_links'] = 5;
        //print_r($config);
        $this->pagination->initialize($config);

        $data = array(
            'products' => $products
        );
        if ($return === true) {
            return $this->load->view('page_look/search-results', $data, true);
        } else {
            $this->load->view('page_look/search-results', $data);
        }
    }

    public function preview() {
        $lookbooks = $this->lookbooks_model->list_lookbooks_shop_with_products($this->session->userdata('id'));
        $this->template
                ->append_css('modmedia-lookbook.css')
                ->append_css('magnific-modlbox.css')
                ->append_css('flexslider.css')
                ->append_js('libs/jquery.magnific.min.js')
                ->append_js('libs/jquery.flexslider-min.js')
                ->set('lookbooks', $lookbooks)
                ->build('preview-lookbooks-list');
    }

    public function preview_lookbook($id, $security) {
        if (sha1($id . $this->config->item('encryption_key')) == $security) {
            $this->load->model('lookbooks_pages_model');
            $lookbook_pages = $this->lookbooks_pages_model->list_pages_with_products_lookbook($id);
            $this->template
                    ->append_css('modmedia-lookbook.css')
                    ->set('pages', $lookbook_pages)
                    ->build('preview-lookbook');
        }
    }

    private function verify_webhook($data, $hmac_header) {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, Settings::get('shopify_secret'), true));
        return ($hmac_header == $calculated_hmac);
    }

    public function create_webhooks() {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $this->load->model('customers/customers_model');
                $shop = $this->customers_model->read($this->session->userdata('shop'))->row();
                $webhooks = array();
                if (!$shop->product_create_webhook) {
                    $webhook = $this->shopify->call('GET', '/admin/api/2024-01/webhooks.json?address=' . urlencode(BASE_URL . 'lookbook/product_create_webhook'));
                    if (count($webhook['webhooks']) > 0) {
                        $webhooks['product_create_webhook'] = $webhook['webhooks'][0]['id'];
                    } else {
                        $data = $this->shopify->call('POST', '/admin/webhooks.json', array("webhook" => array("topic" => "products/create", "address" => site_url('lookbook/product_create_webhook'), "format" => "json")));
                        $webhooks['product_create_webhook'] = $data['webhook']['id'];
                    }
                }
                /*if (!$shop->product_update_webhook) {
                    $webhook = $this->shopify->call('GET', '/admin/api/2024-01/webhooks.json?address=' . urlencode(BASE_URL . 'lookbook/product_update_webhook'));
                    if (count($webhook['webhooks']) > 0) {
                        $webhooks['product_update_webhook'] = $webhook['webhooks'][0]['id'];
                    } else {
                        $data = $this->shopify->call('POST', '/admin/api/2024-01/webhooks.json', array("webhook" => array("topic" => "products/update", "address" => site_url('lookbook/product_update_webhook'), "format" => "json")));
                        $webhooks['product_update_webhook'] = $data['webhook']['id'];
                    }
                }*/
                if (!$shop->product_delete_webhook) {
                    $webhook = $this->shopify->call('GET', '/admin/api/2024-01/webhooks.json?address=' . urlencode(BASE_URL . 'lookbook/product_delete_webhook'));
                    if (count($webhook['webhooks']) > 0) {
                        $webhooks['product_delete_webhook'] = $webhook['webhooks'][0]['id'];
                    } else {
                        $data = $this->shopify->call('POST', '/admin/webhooks.json', array("webhook" => array("topic" => "products/delete", "address" => site_url('lookbook/product_delete_webhook'), "format" => "json")));
                        $webhooks['product_delete_webhook'] = $data['webhook']['id'];
                    }
                }
                if ($webhooks) {
                    $this->load->model('customers/customers_model');
                    $webhooks['shop'] = $this->session->userdata('shop');
                    return $this->customers_model->update($webhooks);
                } else {
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    public function products_webhook() {
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $data = file_get_contents('php://input');
        $verified = $this->verify_webhook($data, $hmac_header);
        $data = array(
            'info' => $data,
            'verified' => var_export($verified, true)
        );
        $this->lookbooks_model->insert_webhook($data);
    }

    public function product_create_webhook() {
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $data = file_get_contents('php://input');
        $verified = $this->verify_webhook($data, $hmac_header);
        $data = array(
            'type' => 'create',
            'shop' => $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'],
            'product' => $_SERVER['HTTP_X_SHOPIFY_PRODUCT_ID'],
            'info' => $data,
            'verified' => var_export($verified, true)
        );
        $this->lookbooks_model->insert_webhook($data);
        echo 'Ok';
    }

    public function product_update_webhook() {
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $data = file_get_contents('php://input');
        $verified = $this->verify_webhook($data, $hmac_header);
        $data = array(
            'type' => 'update',
            'shop' => $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'],
            'product' => $_SERVER['HTTP_X_SHOPIFY_PRODUCT_ID'],
            'info' => $data,
            'verified' => var_export($verified, true)
        );
        $this->lookbooks_model->insert_webhook($data);
        echo 'Ok';
    }

    public function product_delete_webhook() {
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $data = file_get_contents('php://input');
        $verified = $this->verify_webhook($data, $hmac_header);
        $data = array(
            'type' => 'delete',
            'shop' => $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'],
            'product' => $_SERVER['HTTP_X_SHOPIFY_PRODUCT_ID'],
            'info' => $data,
            'verified' => var_export($verified, true)
        );
        $this->lookbooks_model->insert_webhook($data);
        echo 'Ok';
    }

    public function add_icon($shape, $background, $cross) {
        if ($this->create_icon($shape, $background, $cross, false)) {
            $name = 'lb_mm_' . $shape . '_' . str_replace('_', '', $background) . '_' . str_replace('_', '', $cross) . '.png';
            if ($icon = $this->upload_icon_to_shopify($name, 26, 26)) {
                $json = array('status' => 'success', 'msg' => 'The icon was created successfully', 'url' => $icon['url'], 'id' => $icon['id'], 'width' => $icon['width'], 'height' => $icon['height']);
            } else {
                $json = array('status' => 'error', 'msg' => 'There was a problem uploading the icon to shopify');
            }
        } else {
            $json = array('status' => 'error', 'msg' => 'There was a problem creating the icon');
        }
        echo json_encode($json);
    }

    public function delete_icon($id = false) {
        if ($id) {
            $this->load->model('icons_model');
            if ($this->icons_model->delete_icon($id)) {
                $json = array('status' => 'success', 'msg' => 'The icon was deleted successfully');
            } else {
                $json = array('status' => 'error', 'msg' => 'There was a problem deleting the icon');
            }
        } else {
            $json = array('status' => 'error', 'msg' => 'You need to select an icon to delete first');
        }
        echo json_encode($json);
    }

    private function upload_icon_to_shopify($icon, $width = 26, $height = 26) {
        if ($icon) {
            if ($this->session->userdata('token')) {
                $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                try {
                    $themes = $this->shopify->call('GET', '/admin/api/2024-01/themes.json', array('fields' => 'id,name,role'));
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
                    //echo $theme_id;
                    $data = $this->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'assets/' . $icon, 'src' => site_url('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon))));
                    
                    
                    if ($data['status_response'] == 'success') {
                        //echo "Array: " . implode(', ', $data);
                        if($theme_mobile_id != ''){
                            $data2 = $this->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'assets/' . $icon, 'src' => site_url('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon))));
                        }
                        if (file_exists('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon)) {
                            unlink('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon);
                        }
                        $this->load->model('icons_model');
                        $new_icon = array(
                            'name' => $icon,
                            'shop' => $this->session->userdata('id'),
                            'width' => $width,
                            'height' => $height,
                            'url' => $data['asset']['public_url']
                        );
                        if ($id = $this->icons_model->create($new_icon)) {
                            $new_icon['id'] = $id;
                            return $new_icon;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } catch (ShopifyCurlException $e) {
                    echo 'Curl ';
                    print_r($e->getMessage());
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function get_lookbooks() {
        try {
            $data = file_get_contents('php://input');
            $parameters = $this->input->get();

            $signature = $parameters['signature'];
            unset($parameters['signature']);
            ksort($parameters);
            $text = '';
            foreach ($parameters as $key => $value):
                $text = $text . $key . '=' . $value;
            endforeach;
            $text = hash_hmac('sha256', $text, Settings::get('shopify_secret'));
            if ($signature == $text) {
                header("Content-type: application/liquid");
                echo "Ok Title: {% include 'social-sharing' %}";
            } else {
                echo 'nana {{ page.title }}';
            }
        } catch (Exception $e) {
            echo 'error';
            log_message('error', 'Webhook: Wrong way ' . join('->', $e)); //check error.log to see the result
        }
    }
    
    public function get_lookbooks2() {
        try {
            $data = file_get_contents('php://input');
            $parameters = $this->input->get();

            $signature = $parameters['signature'];
            unset($parameters['signature']);
            ksort($parameters);
            $text = '';
            foreach ($parameters as $key => $value):
                $text = $text . $key . '=' . $value;
            endforeach;
            $text = hash_hmac('sha256', $text, Settings::get('shopify_secret'));
            if ($signature == $text) {
                header("Content-type: application/liquid");
                echo "Ok Title: {% include 'social-sharing' %}";
            } else {
                echo 'nana {{ page.title }}';
            }
        } catch (Exception $e) {
            echo 'error';
            log_message('error', 'Webhook: Wrong way ' . join('->', $e)); //check error.log to see the result
        }
    }

    public function metafields() {
        if ($this->session->userdata('token')) {
            $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            try {
                $data = $this->shopify->call('POST', '/admin/metafields.json', array('metafield' => array('namespace' => 'lookbook_mm', 'key' => 'lookbook_1', 'owner_resource' => 'lookbook_mm', 'value' => 'Yeah 2', 'value_type' => 'string')));
                if ($data['status_response'] == 'success') {
                    echo 'success';
                    print_r($data);
                } else {
                    echo 'error';
                    return false;
                }
            } catch (ShopifyCurlException $e) {
                echo 'Curl ';
                print_r($e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    public function upload_icon() {
        $field = 'icon_image';
        $name = 'lb_mm_custom_' . $_FILES[$field]['name'];
        $name_parts = explode('.', $name);
        $file_name = $name_parts[0];
        $folder = $this->session->userdata('shop');
        $config['upload_path'] = 'uploads/lookbooks/' . $folder;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '1000';
        $config['remove_spaces'] = true;
        $config['overwrite'] = true;
        if (!is_dir('uploads/lookbooks')) {
            mkdir('uploads/lookbooks', 0777, TRUE);
        }
        if (!is_dir('uploads/lookbooks/' . $folder)) {
            mkdir('uploads/lookbooks/' . $folder, 0777, TRUE);
        }
        $config['file_name'] = $file_name = $name_parts[0] . '.png';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field)) {
            $errors = array('status' => 'error', 'msg' => $this->upload->display_errors());
            echo "<script>window.parent.show_message('error', '" . $this->upload->display_errors() . "');</script>";
        } else {
            $upload_data = $this->upload->data();
            if ($icon = $this->upload_icon_to_shopify($upload_data['file_name'], $upload_data['image_width'], $upload_data['image_height'])) {
                echo "<script>window.parent.addUploadedIconToList('" . $icon['url'] . "', '" . $icon['id'] . "', '" . $icon['width'] . "', '" . $icon['height'] . "');</script>";
            }
        }
    }

    public function product_photos($product = null) {
        $this->load->model('shop_products_model');
        $data = array(
            'product' => $this->shop_products_model->read($product)
        );
        echo $this->load->view('product_photos', $data, true);
    }

    public function update_store_theme() {
        $this->lookbook_themes->upload_lookbook_styles();
        $this->lookbook_themes->upload_lookbook_scripts();
        $this->load->model('customers/customers_model');
        $shop = $this->customers_model->read($this->session->userdata('shop'))->row();
        $lookbooks = $this->lookbooks_model->list_lookbooks_shop($shop->id);
        $this->load->model('lookbook/lookbooks_pages_model');
        $lookbooks_pages = $this->lookbooks_pages_model->list_shop_lookbook_pages($shop->id);
        $this->template
                ->set('shop', $shop)
                ->set('lookbooks', $lookbooks)
                ->set('lookbooks_pages', $lookbooks_pages)
                ->build('update_store_theme');
    }

    public function update_store_item() {
        $name = $this->input->post('name');
        if ($name == 'page.lookbooks-modmedia-list.liquid') {
            if ($this->lookbook_themes->create_page_lookbooks_list()) {
                $json = array('status' => 'success', 'msg' => 'The template was successfully updated ' . $name);
            } else {
                $json = array('status' => 'error', 'msg' => 'There was an error trying to update the template');
            }
        } else {
            if ($name == 'page.lookbooks-modmedia-single.liquid') {
                if ($this->lookbook_themes->create_page_lookbooks_single()) {
                    $json = array('status' => 'success', 'msg' => 'The template was successfully updated ' . $name);
                } else {
                    $json = array('status' => 'error', 'msg' => 'There was an error trying to update the template');
                }
            } else {
                $src = $this->input->post('src');
                try {
                    $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                    $data = $this->shopify->call('GET', '/admin/api/2024-01/themes/' . $this->session->userdata('theme_on_store') . '/assets.json', array('asset' => array('key' => 'assets/' . $name)));
                    if ($data['status_response'] == 'success') {
                        if (isset($data['asset'])) {
                            $id = $this->input->post('id');
                            $type = $this->input->post('type');
                            if ($type == 'lookbook') {
                                $lookbook = $this->lookbooks_model->get($id);
                                $image = json_decode($lookbook->feature_image);
                                $image->full_path = $data['asset']['public_url'];
                                $lookbook_data = array(
                                    'id' => $lookbook->id,
                                    'feature_image' => json_encode($image)
                                );
                                $this->lookbooks_model->update($lookbook_data);
                                $json = array('status' => 'success', 'msg' => 'The Lookbook Feature Image was successfully updated');
                            } else {
                                $this->load->model('lookbooks_pages_model');
                                $lookbook_page = $this->lookbooks_pages_model->get($id);
                                $image = json_decode($lookbook_page->image);
                                $image->full_path = $data['asset']['public_url'];
                                $lookbook_page_data = array(
                                    'id' => $lookbook_page->id,
                                    'image' => json_encode($image)
                                );
                                $this->lookbooks_pages_model->update($lookbook_page_data);
                                $json = array('status' => 'success', 'msg' => 'The Lookbook Page Image was successfully updated');
                            }
                        } else {
                            $json = array('status' => 'error', 'msg' => 'The Image was not uploaded');
                        }
                    } else {
                        if ($data['response_headers']['http_status_code'] == '404') {
                            $data = $this->shopify->call('PUT', '/admin/themes/' . $this->session->userdata('theme_on_store') . '/assets.json', array('asset' => array('key' => 'assets/' . $name, 'src' => $src)));
                            if (isset($data['asset'])) {
                                $id = $this->input->post('id');
                                $type = $this->input->post('type');
                                if ($type == 'lookbook') {
                                    $lookbook = $this->lookbooks_model->get($id);
                                    $image = json_decode($lookbook->feature_image);
                                    $image->full_path = $data['asset']['public_url'];
                                    $lookbook_data = array(
                                        'id' => $lookbook->id,
                                        'feature_image' => json_encode($image)
                                    );
                                    $this->lookbooks_model->update($lookbook_data);
                                    $json = array('status' => 'success', 'msg' => 'The Lookbook Feature Image was successfully updated');
                                } else {
                                    $this->load->model('lookbooks_pages_model');
                                    $lookbook_page = $this->lookbooks_pages_model->get($id);
                                    $image = json_decode($lookbook_page->image);
                                    $image->full_path = $data['asset']['public_url'];
                                    $lookbook_page_data = array(
                                        'id' => $lookbook_page->id,
                                        'image' => json_encode($image)
                                    );
                                    $this->lookbooks_pages_model->update($lookbook_page_data);
                                    $json = array('status' => 'success', 'msg' => 'The Lookbook Page Image was successfully updated');
                                }
                            } else {
                                $json = array('status' => 'error', 'msg' => 'The Image was not uploaded');
                            }
                        }
                    }
                } catch (ShopifyCurlException $e) {
                    $json = array('status' => 'error', 'msg' => 'Curl: ' . $e->getMessage());
                }
            }
        }
        echo json_encode($json);
    }

    public function set_actual_theme($security = null) {
        if ($security == sha1('update_' . $this->config->item('encryption_key') . '_theme')) {
            $this->load->model('customers/customers_model');
            $data = array(
                'shop' => $this->session->userdata('shop'),
                'actual_theme' => $this->session->userdata('theme_on_store')
            );
            if ($this->customers_model->update($data)) {
                $this->session->set_userdata('actual_theme', $this->session->userdata('theme_on_store'));
                $this->session->set_userdata('theme_changed', 0);
                
                $json = array('status' => 'success', 'msg' => 'The theme was successfully updated');
            } else {
                $json = array('status' => 'error', 'msg' => 'There was some errors trying to update the theme, please try again');
            }
        } else {
            $json = array('status' => 'error', 'msg' => 'You have no permissions to access this page');
        }
        echo json_encode($json);
    }

    public function update_store_theme_single() {
        //if($this->lookbook_themes->create_page_lookbooks_single() && $this->lookbook_themes->create_page_lookbooks_list()){
        $this->load->model('customers/customers_model');
        $shop = $this->customers_model->read($this->session->userdata('shop'))->row();
        $lookbooks = $this->lookbooks_model->list_lookbooks_shop($shop->id);
        echo 'Copying images<br/><br/>';

        $lookbooks_pages = $this->lookbooks_pages_model->list_shop_lookbook_pages($shop->id);
        print_r($lookbooks_pages);
        /* $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
          try {
          foreach($lookbooks as $lookbook):
          $image=json_decode($lookbook->feature_image);
          $data = $this->shopify->call('GET', '/admin/api/2024-01/themes/'.$this->session->userdata('theme_on_store').'/assets.json', array('asset' => array('key' => 'assets/'.$image->file_name)));
          if ($data['status_response'] == 'success') {
          echo 'success';
          print_r($data);
          } else {
          echo 'error';
          if($data['response_headers']['http_status_code']=='404'){
          $data = $this->shopify->call('PUT', '/admin/api/2024-01/themes/'.$this->session->userdata('theme_on_store').'/assets.json', array('asset' => array('key' => 'assets/'.$image->file_name,'src'=>$image->full_path)));
          print_r($data);
          $image->full_path=$data['asset']['public_url'];
          $lookbook_data=array(
          'id'=>$lookbook->id,
          'feature_image'=>json_encode($image)
          );
          $this->lookbooks_model->update($lookbook_data);
          }
          }
          endforeach;


          } catch (ShopifyCurlException $e) {
          echo 'Curl ';
          print_r($e->getMessage());
          return false;
          } */
        /* }else{
          echo 'error';
          } */
    }

    public function general_settings() {
        $this->load->library('form_validation');
        $this->load->model('customers/customers_model');
        $rules = array(
            array(
                'field' => 'page_template_selected',
                'label' => 'Default template',
                'rules' => 'required'
            )
        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === false) {
            $customer = $this->customers_model->read($this->session->userdata('shop'))->row();
            if (!$customer->page_templates || (date('Y-m-d', strtotime($customer->page_templates_updated)) < date('Y-m-d'))) {
                $templates = $this->getPageTemplates();
            } else {
                $templates = json_decode($customer->page_templates);
            }
            if(!$templates){
                $templates=array(''=>'');
            }
            echo $this->session->userdata('shop');
            $lookbooks_result=$this->lookbooks_model->list_lookbooks_shop($this->session->userdata('id'));
            $lookbooks=array();
            foreach($lookbooks_result as $look):
                $lookbooks[$look->id]=$look->title;
            endforeach;
            $settings=json_decode($customer->settings);
            if(isset($settings->homepage_snippet_lookbook_single)){
                $homepage_snippet_lookbook_single=$settings->homepage_snippet_lookbook_single;
            }else{
                $homepage_snippet_lookbook_single='';
            }
            if(isset($settings->homepage_snippet_display)){
                $homepage_snippet_display=$settings->homepage_snippet_display;
            }else{
                $homepage_snippet_display='';
            }
            $this->template
                    ->set('security', sha1($this->config->item('encryption_key') . 'general-settings'))
                    ->set('page_template_selected', $customer->page_template_selected)
                    ->set('lookbooks', $lookbooks)
                    ->set('homepage_snippet_lookbook_single',$homepage_snippet_lookbook_single)
                    ->set('homepage_snippet_display',$homepage_snippet_display)
                    ->set('templates', $templates)
                    ->build('general_settings');
        } else {
            $data = array(
                'shop' => $this->session->userdata('shop'),
                'page_template_selected' => $this->input->post('page_template_selected'),
                'settings'=>json_encode($this->input->post())
            );
            if ($this->customers_model->update($data)) {
                $this->session->set_userdata('page_template_selected', $this->input->post('page_template_selected'));
                if ($this->lookbook_themes->create_page_lookbooks_single() && $this->lookbook_themes->create_page_lookbooks_list()) {
                    $customer = $this->customers_model->read($this->session->userdata('shop'))->row();
                    if ($this->lookbook_themes->create_lookbook_snippet(json_decode($data['settings']),$customer)) {
                        $this->session->set_flashdata('success', 'The settings were saved successfully');
                    }else{
                        $this->session->set_flashdata('error', $this->lookbook_themes->get_error());
                    }
                } else {
                    $this->session->set_flashdata('error', $this->lookbook_themes->get_error());
                }
                redirect('lookbook');
            } else {
                $this->session->set_flashdata('error', 'There was an error trying to save the settings, please try again');
                redirect('lookbook/general_settings');
            }
        }
    }
    public function copy_lookbook($from_shop, $to_shop){
        $this->lookbooks_model->copy_lookbook($from_shop, $to_shop);        
    }
    public function create_icon($shape, $background = null, $cross = null, $return = false) {
        // Create a image from file.
        $name = 'lb_mm_' . $shape . '_' . str_replace('_', '', $background) . '_' . str_replace('_', '', $cross);
        $im = imagecreatetruecolor(26, 26);
        imagesavealpha($im, true);
        imageantialias($im, false);
        $col = imagecolorallocatealpha($im, 255, 255, 255, 127);
        imagefill($im, 0, 0, $col);
        // choose a color for the ellipse
        if ($background) {
            $background = explode('_', $background);
            if (count($background) == 4) {
                $colorArc = $background;
                $background = imagecolorallocatealpha($im, $background[0], $background[1], $background[2], $background[3]);
            } else {
                $background = imagecolorallocatealpha($im, 0, 255, 255, 0);
                $colorArc = array(237, 102, 71, 0);
            }
        } else {
            $background = imagecolorallocatealpha($im, 0, 255, 255, 0);
            $colorArc = array(237, 102, 71, 0);
        }
        if ($cross) {
            $cross = explode('_', $cross);
            if (count($cross) == 4) {
                $cross = imagecolorallocatealpha($im, $cross[0], $cross[1], $cross[2], $cross[3]);
            } else {
                $cross = imagecolorallocatealpha($im, 255, 255, 255, 0);
            }
        } else {
            $cross = imagecolorallocatealpha($im, 255, 255, 255, 0);
        }
        /* $black = imagecolorallocate($im, 0, 0, 0);
          imagecolortransparent($im, $black); */
        // Make the background transparent
        // draw the white ellipse
        //imagefilledellipse($im, 13, 13, 24, 24, $ellipseColor);
        if ($shape == 'circle') {
            $this->imageSmoothArc($im, 12, 12, 23, 23, $colorArc, 0, 2 * M_PI);
        } else {
            imagefilledrectangle($im, 0, 0, 26, 26, $background);
        }
        imagefilledrectangle($im, 12, 7, 13, 18, $cross);
        imagefilledrectangle($im, 7, 12, 18, 13, $cross);

        // Output the image.
        //header("Content-type: image/png");
        if ($return) {
            header("Content-type: image/png");
            imagepng($im);
        } else {
            if (file_exists('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $name . '.png')) {
                unlink('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $name . '.png');
            }
            if (imagepng($im, 'uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $name . '.png')) {
                return true;
            } else {
                return false;
            }
        }
        imagedestroy($im);
    }

    function imageSmoothArcDrawSegment(&$img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $start, $stop, $seg) {
        // Originally written from scratch by Ulrich Mierendorff, 06/2006
        // Rewritten and improved, 04/2007, 07/2007
        // Please do not use THIS function directly. Scroll down to imageSmoothArc(...).

        $fillColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], $color[3]);

        $xStart = abs($a * cos($start));
        $yStart = abs($b * sin($start));
        $xStop = abs($a * cos($stop));
        $yStop = abs($b * sin($stop));
        $dxStart = 0;
        $dyStart = 0;
        $dxStop = 0;
        $dyStop = 0;
        if ($xStart != 0)
            $dyStart = $yStart / $xStart;
        if ($xStop != 0)
            $dyStop = $yStop / $xStop;
        if ($yStart != 0)
            $dxStart = $xStart / $yStart;
        if ($yStop != 0)
            $dxStop = $xStop / $yStop;
        if (abs($xStart) >= abs($yStart)) {
            $aaStartX = true;
        } else {
            $aaStartX = false;
        }
        if ($xStop >= $yStop) {
            $aaStopX = true;
        } else {
            $aaStopX = false;
        }
        //$xp = +1; $yp = -1; $xa = +1; $ya = 0;
        for ($x = 0; $x < $a; $x += 1) {
            /* $y = $b * sqrt( 1 - ($x*$x)/($a*$a) );

              $error = $y - (int)($y);
              $y = (int)($y);

              $diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error ); */

            $_y1 = $dyStop * $x;
            $_y2 = $dyStart * $x;
            if ($xStart > $xStop) {
                $error1 = $_y1 - (int) ($_y1);
                $error2 = 1 - $_y2 + (int) $_y2;
                $_y1 = $_y1 - $error1;
                $_y2 = $_y2 + $error2;
            } else {
                $error1 = 1 - $_y1 + (int) $_y1;
                $error2 = $_y2 - (int) ($_y2);
                $_y1 = $_y1 + $error1;
                $_y2 = $_y2 - $error2;
            }
            /*
              if ($aaStopX)
              $diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
              if ($aaStartX)
              $diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
             */

            if ($seg == 0 || $seg == 2) {
                $i = $seg;
                if (!($start > $i * M_PI / 2 && $x > $xStart)) {
                    if ($i == 0) {
                        $xp = +1;
                        $yp = -1;
                        $xa = +1;
                        $ya = 0;
                    } else {
                        $xp = -1;
                        $yp = +1;
                        $xa = 0;
                        $ya = +1;
                    }
                    if ($stop < ($i + 1) * (M_PI / 2) && $x <= $xStop) {
                        $diffColor1 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error1);
                        $y1 = $_y1;
                        if ($aaStopX)
                            imageSetPixel($img, $cx + $xp * ($x) + $xa, $cy + $yp * ($y1 + 1) + $ya, $diffColor1);
                    } else {
                        $y = $b * sqrt(1 - ($x * $x) / ($a * $a));
                        $error = $y - (int) ($y);
                        $y = (int) ($y);
                        $diffColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error);
                        $y1 = $y;
                        if ($x < $aaAngleX)
                            imageSetPixel($img, $cx + $xp * $x + $xa, $cy + $yp * ($y1 + 1) + $ya, $diffColor);
                    }
                    if ($start > $i * M_PI / 2 && $x <= $xStart) {
                        $diffColor2 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error2);
                        $y2 = $_y2;
                        if ($aaStartX)
                            imageSetPixel($img, $cx + $xp * $x + $xa, $cy + $yp * ($y2 - 1) + $ya, $diffColor2);
                    } else {
                        $y2 = 0;
                    }
                    if ($y2 <= $y1)
                        imageLine($img, $cx + $xp * $x + $xa, $cy + $yp * $y1 + $ya, $cx + $xp * $x + $xa, $cy + $yp * $y2 + $ya, $fillColor);
                }
            }

            if ($seg == 1 || $seg == 3) {
                $i = $seg;
                if (!($stop < ($i + 1) * M_PI / 2 && $x > $xStop)) {
                    if ($i == 1) {
                        $xp = -1;
                        $yp = -1;
                        $xa = 0;
                        $ya = 0;
                    } else {
                        $xp = +1;
                        $yp = +1;
                        $xa = 1;
                        $ya = 1;
                    }
                    if ($start > $i * M_PI / 2 && $x < $xStart) {
                        $diffColor2 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error2);
                        $y1 = $_y2;
                        if ($aaStartX)
                            imageSetPixel($img, $cx + $xp * $x + $xa, $cy + $yp * ($y1 + 1) + $ya, $diffColor2);
                    } else {
                        $y = $b * sqrt(1 - ($x * $x) / ($a * $a));
                        $error = $y - (int) ($y);
                        $y = (int) $y;
                        $diffColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error);
                        $y1 = $y;
                        if ($x < $aaAngleX)
                            imageSetPixel($img, $cx + $xp * $x + $xa, $cy + $yp * ($y1 + 1) + $ya, $diffColor);
                    }
                    if ($stop < ($i + 1) * M_PI / 2 && $x <= $xStop) {
                        $diffColor1 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error1);
                        $y2 = $_y1;
                        if ($aaStopX)
                            imageSetPixel($img, $cx + $xp * $x + $xa, $cy + $yp * ($y2 - 1) + $ya, $diffColor1);
                    } else {
                        $y2 = 0;
                    }
                    if ($y2 <= $y1)
                        imageLine($img, $cx + $xp * $x + $xa, $cy + $yp * $y1 + $ya, $cx + $xp * $x + $xa, $cy + $yp * $y2 + $ya, $fillColor);
                }
            }
        }

        ///YYYYY

        for ($y = 0; $y < $b; $y += 1) {
            /* $x = $a * sqrt( 1 - ($y*$y)/($b*$b) );

              $error = $x - (int)($x);
              $x = (int)($x);

              $diffColor = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error );
             */
            $_x1 = $dxStop * $y;
            $_x2 = $dxStart * $y;
            if ($yStart > $yStop) {
                $error1 = $_x1 - (int) ($_x1);
                $error2 = 1 - $_x2 + (int) $_x2;
                $_x1 = $_x1 - $error1;
                $_x2 = $_x2 + $error2;
            } else {
                $error1 = 1 - $_x1 + (int) $_x1;
                $error2 = $_x2 - (int) ($_x2);
                $_x1 = $_x1 + $error1;
                $_x2 = $_x2 - $error2;
            }
            /*
              if (!$aaStopX)
              $diffColor1 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error1 );
              if (!$aaStartX)
              $diffColor2 = imageColorExactAlpha( $img, $color[0], $color[1], $color[2], 127-(127-$color[3])*$error2 );
             */

            if ($seg == 0 || $seg == 2) {
                $i = $seg;
                if (!($start > $i * M_PI / 2 && $y > $yStop)) {
                    if ($i == 0) {
                        $xp = +1;
                        $yp = -1;
                        $xa = 1;
                        $ya = 0;
                    } else {
                        $xp = -1;
                        $yp = +1;
                        $xa = 0;
                        $ya = 1;
                    }
                    if ($stop < ($i + 1) * (M_PI / 2) && $y <= $yStop) {
                        $diffColor1 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error1);
                        $x1 = $_x1;
                        if (!$aaStopX)
                            imageSetPixel($img, $cx + $xp * ($x1 - 1) + $xa, $cy + $yp * ($y) + $ya, $diffColor1);
                    }
                    if ($start > $i * M_PI / 2 && $y < $yStart) {
                        $diffColor2 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error2);
                        $x2 = $_x2;
                        if (!$aaStartX)
                            imageSetPixel($img, $cx + $xp * ($x2 + 1) + $xa, $cy + $yp * ($y) + $ya, $diffColor2);
                    } else {
                        $x = $a * sqrt(1 - ($y * $y) / ($b * $b));
                        $error = $x - (int) ($x);
                        $x = (int) ($x);
                        $diffColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error);
                        $x1 = $x;
                        if ($y < $aaAngleY && $y <= $yStop)
                            imageSetPixel($img, $cx + $xp * ($x1 + 1) + $xa, $cy + $yp * $y + $ya, $diffColor);
                    }
                }
            }

            if ($seg == 1 || $seg == 3) {
                $i = $seg;
                if (!($stop < ($i + 1) * M_PI / 2 && $y > $yStart)) {
                    if ($i == 1) {
                        $xp = -1;
                        $yp = -1;
                        $xa = 0;
                        $ya = 0;
                    } else {
                        $xp = +1;
                        $yp = +1;
                        $xa = 1;
                        $ya = 1;
                    }
                    if ($start > $i * M_PI / 2 && $y < $yStart) {
                        $diffColor2 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error2);
                        $x1 = $_x2;
                        if (!$aaStartX)
                            imageSetPixel($img, $cx + $xp * ($x1 - 1) + $xa, $cy + $yp * $y + $ya, $diffColor2);
                    }
                    if ($stop < ($i + 1) * M_PI / 2 && $y <= $yStop) {
                        $diffColor1 = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error1);
                        $x2 = $_x1;
                        if (!$aaStopX)
                            imageSetPixel($img, $cx + $xp * ($x2 + 1) + $xa, $cy + $yp * $y + $ya, $diffColor1);
                    } else {
                        $x = $a * sqrt(1 - ($y * $y) / ($b * $b));
                        $error = $x - (int) ($x);
                        $x = (int) ($x);
                        $diffColor = imageColorExactAlpha($img, $color[0], $color[1], $color[2], 127 - (127 - $color[3]) * $error);
                        $x1 = $x;
                        if ($y < $aaAngleY && $y < $yStart)
                            imageSetPixel($img, $cx + $xp * ($x1 + 1) + $xa, $cy + $yp * $y + $ya, $diffColor);
                    }
                }
            }
        }
    }

    function imageSmoothArc(&$img, $cx, $cy, $w, $h, $color, $start, $stop) {
        // Originally written from scratch by Ulrich Mierendorff, 06/2006
        // Rewritten and improved, 04/2007, 07/2007
        // compared to old version:
        // + Support for transparency added
        // + Improved quality of edges & antialiasing
        // note: This function does not represent the fastest way to draw elliptical
        // arcs. It was written without reading any papers on that subject. Better
        // algorithms may be twice as fast or even more.
        // what it cannot do: It does not support outlined arcs, only filled
        // Parameters:
        // $cx      - Center of ellipse, X-coord
        // $cy      - Center of ellipse, Y-coord
        // $w       - Width of ellipse ($w >= 2)
        // $h       - Height of ellipse ($h >= 2 )
        // $color   - Color of ellipse as a four component array with RGBA
        // $start   - Starting angle of the arc, no limited range!
        // $stop    - Stop     angle of the arc, no limited range!
        // $start _can_ be greater than $stop!
        // If any value is not in the given range, results are undefined!
        // This script does not use any special algorithms, everything is completely
        // written from scratch; see http://de.wikipedia.org/wiki/Ellipse for formulas.

        while ($start < 0)
            $start += 2 * M_PI;
        while ($stop < 0)
            $stop += 2 * M_PI;

        while ($start > 2 * M_PI)
            $start -= 2 * M_PI;

        while ($stop > 2 * M_PI)
            $stop -= 2 * M_PI;


        if ($start > $stop) {
            $this->imageSmoothArc($img, $cx, $cy, $w, $h, $color, $start, 2 * M_PI);
            $this->imageSmoothArc($img, $cx, $cy, $w, $h, $color, 0, $stop);
            return;
        }

        $a = 1.0 * round($w / 2);
        $b = 1.0 * round($h / 2);
        $cx = 1.0 * round($cx);
        $cy = 1.0 * round($cy);

        $aaAngle = atan(($b * $b) / ($a * $a) * tan(0.25 * M_PI));
        $aaAngleX = $a * cos($aaAngle);
        $aaAngleY = $b * sin($aaAngle);

        $a -= 0.5; // looks better...
        $b -= 0.5;

        for ($i = 0; $i < 4; $i++) {
            if ($start < ($i + 1) * M_PI / 2) {
                if ($start > $i * M_PI / 2) {
                    if ($stop > ($i + 1) * M_PI / 2) {
                        $this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $start, ($i + 1) * M_PI / 2, $i);
                    } else {
                        $this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $start, $stop, $i);
                        break;
                    }
                } else {
                    if ($stop > ($i + 1) * M_PI / 2) {
                        $this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $i * M_PI / 2, ($i + 1) * M_PI / 2, $i);
                    } else {
                        $this->imageSmoothArcDrawSegment($img, $cx, $cy, $a, $b, $aaAngleX, $aaAngleY, $color, $i * M_PI / 2, $stop, $i);
                        break;
                    }
                }
            }
        }
    }

}