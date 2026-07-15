<?php

class Customers extends Public_Controller {

    function __construct() {
        parent::__construct();
        $this->config->load('config');
        $this->load->library('shopify/shopify');
        $this->load->model('customers_model');
        /*$ua=$this->getBrowser();
        $yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
        print_r($yourbrowser);*/
    }
    // now try it        
function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 
function page_response(){
    print_r($this->input->post());
    print_r($this->input->get());
}
function index() {
     $error = false;
     $shop = ($this->input->post('shop')) ? $this->input->post('shop') : $this->input->get('shop');
     $code = ($this->input->post('code')) ? $this->input->post('code') : $this->input->get('code');
     if($shop == 'knyew.myshopify.com'|| $shop=='diegotest.myshopify.com'){
         $this->index_two();
     }else{
     if ($shop || $code) {
         if ($shop && !$code) {
             $this->shopify->init(array('shop_domain' => $shop, 'token' => "", 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
             if (substr(BASE_URL, strlen(BASE_URL) - 1, 1) == '/') {
                 $pageURL = substr(BASE_URL, 0, strlen(BASE_URL) - 1);
             } else {
                 $pageURL = BASE_URL;
             }
             //echo $this->shopify->getAuthorizeUrl(Settings::get('shopify_scope'));
             redirect($this->shopify->getAuthorizeUrl(Settings::get('shopify_scope'), $pageURL));
         }else{
             $customer = $this->customers_model->read($this->input->get('shop'));
             if ($customer->num_rows() == 0) {
                 //Create new user
                 $this->shopify->init(array('shop_domain' => $shop, 'token' => "", 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                 $this->session->set_userdata('token', $this->shopify->getAccessToken($code));
                 $this->shopify->init(array('shop_domain' => $shop, 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                 $this->session->set_userdata('shop', $shop);
                 $customer_data = $this->shopify->call('GET', '/admin/api/2023-07/shop.json', 'email');
                 $shop_data = array(
                     'shop' => $shop,
                     'email' => $customer_data['shop']['email'],
                     'token' => $this->session->userdata('token'),
                     'data' => json_encode($customer_data)
                 );
                 $this->customers_model->create($shop_data);
                 $this->session->set_userdata('page_template_selected', 'templates/page.liquid');

                 $event = array(
                     'description' => 'Customer Created -> Creating payment',
                     'data' => json_encode($shop_data)
                 );
                 $this->customers_model->add_event($event);
                 $customer = $this->customers_model->read($shop)->row();
                 $this->session->set_userdata('shop',$shop);
                 if ($this->create_charge()) {

                 } else {
                     $this->template->build('error');
                 }
             }else{
                 //Check shop status
                 $customer = $customer->row();
                 $this->session->set_userdata('shop', $shop);
                 $new_token=true;
                 if ($customer->token) {
                     $this->session->set_userdata('token', $customer->token);
                     $this->shopify->init(array('shop_domain' => $customer->shop, 'token' => $customer->token, 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                     $customer_data = $this->shopify->call('GET', '/admin/api/2023-07/shop.json', 'email');
                     if($customer_data['status_response']=='success'){
                         $new_token=false;
                     }
                 }
                 if($new_token){
                     //Updating token
                     $this->shopify->init(array('shop_domain' => $customer->shop, 'token' => "", 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                     $this->session->set_userdata('token', $this->shopify->getAccessToken($code));
                     $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                     if ($this->session->userdata('token')) {
                         $shop_data = array(
                             'shop' => $this->session->userdata('shop'),
                             'token' => $this->session->userdata('token')
                         );
                         $this->customers_model->update($shop_data);
                     } else {
                         $error = true;
                         $this->_error_mesage = 'No token was created';
                     }
                 }

                 if (!$error) {

                    // $charge_id = $customer->charge_id;
                    
                    //      if($charge_id != 'NULL'){
                     if ($this->check_charge($customer)) {
                         if($customer->page_template_selected){
                             $this->session->set_userdata('page_template_selected', $customer->page_template_selected);
                         }else{
                             $this->session->set_userdata('page_template_selected', 'templates/page.liquid');
                         }
                     } else {
                         $this->template->build('error', array('msg' => 'No charge ' . $this->_error_message));
                     }
                 //}
                 } else {
                     $this->template->build('error');
                 }

               

             }
             if (!$customer->script_tag_id) {
                 $data=$this->add_webhook();
                 if(isset($data['id'])){
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'script_tag_id' => $data['id']
                    );
                    $this->customers_model->update($shop_data);                    
                 }
             }
             redirect(Settings::get('shopify_main_page_redirect'));
         }
     } else {
         if ($this->session->userdata('shop')) {
             if (Settings::get('shopify_main_page_redirect') == 'customers') {
                 $this->template->build('index');
             } else {
                  redirect(Settings::get('shopify_main_page_redirect'));
             }
         } else {
             $this->template
                     ->set_layout('home.html')
                     ->build('index');
         }
     }
     }
 }

 private function check_charges(){
     $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges.json');
     print_r($data);
 }
 private function check_charge_new($charge_id) {
        $status=array();
        if ($charge_id == 'FREE') {
            $this->session->set_userdata('status', 'active');
            $status=array('status'=>'active','redirect'=>Settings::get('shopify_main_page_redirect'));
        } else {
                $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '.json');
                print_r($data);
                if ($data['status_response'] == 'success') {
                    if ($data['recurring_application_charge']['status'] == 'active') {
                        $this->session->set_userdata('status', 'active');
                        $status=array('status'=>'active','redirect'=>Settings::get('shopify_main_page_redirect'));
                        print_r($data);
                    } else { // If not active, we create a new charge
                        if ($data['recurring_application_charge']['status'] == 'cancelled') {
                            $event = array(
                                'description' => 'Customer Re-Join -> Creating new charge',
                                'data' => json_encode($data)
                            );
                            $this->customers_model->add_event($event);
                            $status=array('status'=>'cancelled','redirect'=>$data['recurring_application_charge']['decorated_return_url']);                            
                        } else { // If the payment hasn't been activated we will show the page to the user.
                            if ($data['recurring_application_charge']['status'] == 'pending') {
                                $status=array('status'=>'pending','redirect'=>$data['recurring_application_charge']['confirmation_url']);                            
                            } else {
                                if ($data['recurring_application_charge']['status'] == 'declined') {
                                    $shop_data = array(
                                        'shop' => $this->session->userdata('shop'),
                                        'token' => '',
                                        'charge_data' => json_encode($data),
                                        'charge_status' => '',
                                        'status' => 2
                                    );
                                    $this->customers_model->update($shop_data);
                                    $status=array('status'=>'declined','redirect'=>current_url());                                                                        
                                }else{
                                    $status=array('status'=>$data['recurring_application_charge']['status'],'redirect'=>isset($data['recurring_application_charge']['decorated_return_url'])?$data['recurring_application_charge']['decorated_return_url']:current_url());                                                                        
                                }
                            }
                        }
                    }
                } else {
                    /*$shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'token' => '',
                        'charge_id' => '',
                        'charge_data' => json_encode($data),
                        'charge_status' => '',
                        'status' => 2
                    );
                    $this->customers_model->update($shop_data);*/
                    print_r($data);
                    $status=array('status'=>'error','redirect'=>current_url());                                                                            
                }            
        }
        return $status;
    }
    private function check_charge($customer) {
        $charge_id = $customer->charge_id;
        if ($charge_id == 'FREE') {
            $this->session->set_userdata('status', 'active');
            redirect(Settings::get('shopify_main_page_redirect'));
        } else {
            if ($charge_id) { //If a charge already exist we check if stills active
                $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '.json');
                if ($data['status_response'] == 'success') {
                    if ($data['recurring_application_charge']['status'] == 'active') {
                        $this->session->set_userdata('status', 'active');
                        redirect(Settings::get('shopify_main_page_redirect'));
                    } else { // If not active, we create a new charge
                        if ($data['recurring_application_charge']['status'] == 'cancelled') {
                            $event = array(
                                'description' => 'Customer Re-Join -> Creating new charge',
                                'data' => json_encode($data)
                            );
                            $this->customers_model->add_event($event);
                            if ($this->create_charge()) {
                                redirect(Settings::get('shopify_main_page_redirect'));
                            } else {
                                $this->template->build('error');
                            }
                        } else { // If the payment hasn't been activated we will show the page to the user.
                            if ($data['recurring_application_charge']['status'] == 'pending') {
                                redirect($data['recurring_application_charge']['confirmation_url']);
                            } else {
                                if ($data['recurring_application_charge']['status'] == 'declined') {
                                    $shop_data = array(
                                        'shop' => $this->session->userdata('shop'),
                                        'token' => '',
                                        'charge_id' => '',
                                        'charge_data' => json_encode($data),
                                        'charge_status' => '',
                                        'status' => 2
                                    );
                                    $this->customers_model->update($shop_data);
                                    $this->create_charge();
                                    //redirect(current_url());
                                }
                            }
                        }
                    }
                } else {
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'token' => '',
                        'charge_id' => '',
                        'charge_data' => json_encode($data),
                        'charge_status' => '',
                        'status' => 2
                    );
                    $this->customers_model->update($shop_data);

                    redirect(current_url());
                }
            } else {
                if ($this->create_charge()) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    private function create_charge() {
        //Create charge
        if ($this->session->userdata('shop')) {
            $pageURL = site_url('customers/activate');
            $special_charge = $this->customers_model->check_special_charge($this->session->userdata('shop'));
            $type = 'normal';
            if ($special_charge) {
                if ($special_charge->price > 0) {
                    if ($special_charge->test) {
                        $data = $this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges.json', array('recurring_application_charge' => array('name' => $special_charge->name, 'price' => $special_charge->price, 'return_url' => $pageURL, 'trial_days' => $special_charge->trial_days, 'test' => true)));
                    } else {
                        $data = $this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges.json', array('recurring_application_charge' => array('name' => $special_charge->name, 'price' => $special_charge->price, 'return_url' => $pageURL, 'trial_days' => $special_charge->trial_days)));
                    }
                } else {
                    $type = 'free';
                }
            } else {
                if (Settings::get('shopify_price') > 0) {
                    if (Settings::get('shopify_test_mode') == 'test') {
                        $data = $this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges.json', array('recurring_application_charge' => array('name' => Settings::get('shopify_charge_name'), 'price' => Settings::get('shopify_price'), 'return_url' => $pageURL, 'trial_days' => 7, 'test' => true)));
                    } else {
                        $data = $this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges.json', array('recurring_application_charge' => array('name' => Settings::get('shopify_charge_name'), 'price' => Settings::get('shopify_price'), 'return_url' => $pageURL, 'trial_days' => 7)));
                    }
                } else {
                    $type = 'free';
                }
            }
            if ($type == 'free') {
                $shop_data = array(
                    'shop' => $this->session->userdata('shop'),
                    'script_tag_id' => '',
                    'charge_id' => 'FREE',
                    'charge_data' => '',
                    'charge_status' => 'active',
                    'status' => 1
                );
                $this->customers_model->update($shop_data, true);
                $event = array(
                    'description' => 'Free instalation created'
                );
                $this->customers_model->add_event($event);
                $data_charge=array(
                    'shop'=>$this->session->userdata('shop'),
                    'used'=>1
                );
                $this->load->model('charges_model');
                $this->charges_model->update($data_charge);
                $this->session->set_userdata('status', 'active');
                return true;
            } else {
                if ($data['status_response'] == 'error') {
                    $event = array(
                        'description' => 'Error creating recurring payment',
                        'data' => json_encode($data)
                    );
                    $this->customers_model->add_event($event);
                    $this->template
                            ->build('error', array('data' => $data));
                } else {
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'script_tag_id' => '',
                        'charge_id' => $data['recurring_application_charge']['id'],
                        'charge_data' => json_encode($data),
                        'charge_status' => $data['recurring_application_charge']['status'],
                        'status' => 0
                    );
                    $this->customers_model->update($shop_data);
                    $event = array(
                        'description' => 'Recurring payment created, wating for activation',
                        'data' => json_encode($data)
                    );
                    $this->customers_model->add_event($event);
                    redirect($data['recurring_application_charge']['confirmation_url']);
                }
            }
        } else {
            $this->_error_message = 'No shop selected';
            return false;
        }
    }

    private function check_special_charge($shop) {
        if ($special_charge=$this->customers_model->check_special_charge($shop)) {
            return $special_charge;
            
            
        } else {
            return false;
        }
    }

    public function activate($charge_id=null) {
        if (!$charge_id) {
            $charge_id = $this->input->get('charge_id');
        }
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $activate=$this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '/activate.json');
            if($activate['status_response']=='error'){
                $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '.json');
                if($data['status_response']=='error'){
                    $this->template->build('activation_error',array('charge'=>$charge_id,'errors'=>$activate['response']['errors'],'confirmation_url'=>''));
                }else{
                    $this->template->build('activation_error',array('charge'=>$charge_id,'errors'=>$activate['response']['errors'],'confirmation_url'=>$data['recurring_application_charge']['decorated_return_url']));
                }
            }else{
                $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '.json');
                if($data['status_response']=='error'){
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'script_tag_id' => '',
                        'charge_id' => '',
                        'charge_data' => json_encode($data),
                        'charge_status' => ''
                    );
                    $this->customers_model->update($shop_data);
                    $this->template->buid('activation_error',array('data'=>$data));
                }else{
                    if ($data['recurring_application_charge']['status'] == 'active') {
                        $shop_data = array(
                            'shop' => $this->session->userdata('shop'),
                            'script_tag_id' => '',
                            'charge_id' => $charge_id,
                            'charge_data' => json_encode($data),
                            'charge_status' => $data['recurring_application_charge']['status'],
                            'status' => 1
                        );
                        $this->session->set_userdata('status', 'active');
                        $this->customers_model->update($shop_data);
                        $data_charge=array(
                            'shop'=>$this->session->userdata('shop'),
                            'used'=>1
                        );
                        $this->load->model('charges_model');
                        $this->charges_model->update($data_charge);
                        $customer = $this->customers_model->read($this->session->userdata('shop'));
                        if ($customer->num_rows() > 0) {
                            $customer = $customer->row();
                            $this->session->set_userdata('id', $customer->id);
                        }
                        $event = array(
                            'description' => 'Customer Activated',
                            'data' => json_encode($shop_data)
                        );
                        $this->customers_model->add_event($event);
                        if ($data = $this->add_webhook()) {
                            $event = array(
                                'description' => 'Webhook Uninstall created',
                                'data' => json_encode($data)
                            );
                            $this->customers_model->add_event($event);
                            redirect(Settings::get('shopify_main_page_redirect'));
                        } else {
                            $event = array(
                                'description' => 'Error Webhook Uninstall',
                                'data' => json_encode($data)
                            );
                            $this->customers_model->add_event($event);
                            redirect(Settings::get('shopify_main_page_redirect'));
                        }
                    } else {
                        $shop_data = array(
                            'shop' => $this->session->userdata('shop'),
                            'script_tag_id' => '',
                            'charge_data' => json_encode($data),
                            'charge_status' => $data['status']
                        );
                        $this->customers_model->update($shop_data);
                        if ($data['recurring_application_charge']['status'] == 'declined') {
                            redirect('customers/inactive');
                        } else {
                            $this->template->build('activation_error',array('data'=>$data));
                        }
                    }
                }
            }
            
        } catch (ShopifyApiException $e) {
            print_r($e->getResponse());
            log_message('error', 'Api error: ' . $e->getResponse()); //check error.log to see the result
            return false;
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            log_message('error', 'Curl error: ' . $e->getMessage()); //check error.log to see the result
            return false;
        }
    }
public function activate_old($charge_id=null) {
        if (!$charge_id) {
            $charge_id = $this->input->get('charge_id');
        }
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '/activate.json');
            $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '.json');
            if($data['status_response']=='error'){
                $shop_data = array(
                    'shop' => $this->session->userdata('shop'),
                    'script_tag_id' => '',
                    'charge_id' => '',
                    'charge_data' => json_encode($data),
                    'charge_status' => ''
                );
                $this->customers_model->update($shop_data);
                $this->create_charge();
            }else{
                if ($data['recurring_application_charge']['status'] == 'active') {
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'script_tag_id' => '',
                        'charge_id' => $charge_id,
                        'charge_data' => json_encode($data),
                        'charge_status' => $data['status'],
                        'status' => 1
                    );
                    $this->session->set_userdata('status', 'active');
                    $this->customers_model->update($shop_data);
                    $data_charge=array(
                        'shop'=>$this->session->userdata('shop'),
                        'used'=>1
                    );
                    $this->load->model('charges_model');
                    $this->charges_model->update($data_charge);
                    $customer = $this->customers_model->read($this->session->userdata('shop'));
                    if ($customer->num_rows() > 0) {
                        $customer = $customer->row();
                        $this->session->set_userdata('id', $customer->id);
                    }
                    $event = array(
                        'description' => 'Customer Activated',
                        'data' => json_encode($shop_data)
                    );
                    $this->customers_model->add_event($event);
                    if ($data = $this->add_webhook()) {
                        $event = array(
                            'description' => 'Webhook Uninstall created',
                            'data' => json_encode($data)
                        );
                        $this->customers_model->add_event($event);
                        redirect(Settings::get('shopify_main_page_redirect'));
                    } else {
                        $event = array(
                            'description' => 'Error Webhook Uninstall',
                            'data' => json_encode($data)
                        );
                        $this->customers_model->add_event($event);
                        redirect(Settings::get('shopify_main_page_redirect'));
                    }
                } else {
                    $shop_data = array(
                        'shop' => $this->session->userdata('shop'),
                        'script_tag_id' => '',
                        'charge_id' => '',
                        'charge_data' => json_encode($data),
                        'charge_status' => $data['status']
                    );
                    $this->customers_model->update($shop_data);
                    if ($data['recurring_application_charge']['status'] == 'declined') {
                        redirect('customers/inactive');
                    } else {
                        $this->create_charge();
                    }
                }
            }
        } catch (ShopifyApiException $e) {
            print_r($e->getResponse());
            log_message('error', 'Api error: ' . $e->getResponse()); //check error.log to see the result
            return false;
        } catch (ShopifyCurlException $e) {
            print_r($e->getMessage());
            log_message('error', 'Curl error: ' . $e->getMessage()); //check error.log to see the result
            return false;
        }
    }
    public function add_webhook() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $webhooks = $this->shopify->call('GET', '/admin/api/2023-07/webhooks.json?address=' . urlencode(BASE_URL . 'customers/uninstall'));
            if(isset($webhooks['webhooks'])){
                foreach($webhooks['webhooks'] as $webhook):
                    if($webhook['address']==urlencode(BASE_URL . 'customers/uninstall')){
                        return $webhook;
                    }
                endforeach;
            }
            $data = $this->shopify->call('POST', '/admin/api/2023-07/webhooks.json', array(
                'webhook' => array('topic' => "app/uninstalled",
                    'address' => BASE_URL . 'customers/uninstall',
                    'format' => 'json')));
             return $data;                       
        } catch (ShopifyApiException $e) {
            log_message('error', 'Api error: ' . $e->getResponse()); //check error.log to see the result
            return false;
        } catch (ShopifyCurlException $e) {
            log_message('error', 'Curl error: ' . $e->getMessage()); //check error.log to see the result
            return false;
        }
    }

    public function uninstall() {
        try {
            $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
            $data = file_get_contents('php://input');
            $verified = $this->verify_webhook($data, $hmac_header);
            $info = json_decode($data);
            if (isset($info->myshopify_domain)) {
                $customer = array(
                    'shop' => $info->myshopify_domain,
                    'data' => $data,
                );
                if ($this->customers_model->insert_uninstall_webhooks($customer)) {
                    $status = 'Successfully updated';
                } else {
                    log_message('error', 'Webhook: error updatind in database' . $data); //check error.log to see the result
                }
            } else {
                log_message('error', 'Webhook: error no domain info ' . $data); //check error.log to see the result                
            }
        } catch (Exception $e) {
            log_message('error', 'Webhook: Wrong way ' . join('->', $e)); //check error.log to see the result
        }
        echo 'Ok';
    }

    public function process_uninstall_queue() {
        $webhooks = $this->customers_model->get_uninstall_webhooks();
        if ($webhooks) {
            foreach ($webhooks as $webhook) {
                $customer = array(
                    'shop' => $webhook->shop,
                    'script_tag_id' => '',
                    'charge_id' => NULL,
                    'product_create_webhook' => NULL,
                    'product_update_webhook' => NULL,
                    'product_delete_webhook' => NULL,
                    'charge_status' => 'cancelled',
                    'status' => 2
                );
                $data = $webhook->data;
                if ($this->customers_model->update($customer)) {
                    $this->load->library('themes/lookbook_themes');
                    $this->lookbook_themes->create_uninstaled_javascript($webhook->shop);
                    $this->customers_model->delete_uninstall_webhooks($webhook->id);
                    $status = 'Successfully updated';
                } else {
                    $status = 'Error updating';
                }
                $event = array(
                    'description' => 'App uninstalled',
                    'data' => $data . ' status_' . $status
                );
                $this->customers_model->add_event($event);
                //echo 'Ok';
            }
        }
    }

    private function verify_webhook($data, $hmac_header) {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, Settings::get('shopify_secret'), true));
        return ($hmac_header == $calculated_hmac);
    }

    public function inactive() {
        $this->template->build('inactive');
    }

    public function logout() {
        $this->session->unset_userdata('shop');
        $this->session->sess_destroy();
        redirect('');
    }

    public function get_webhooks() {
        $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        try {
            $data = $this->shopify->call('GET', '/admin/api/2023-07/webhooks.json');
            // Pages
            print_r($data);
        } catch (ShopifyApiException $e) {
            /*
              $e->getMethod() -> http method (GET, POST, PUT, DELETE)
              $e->getPath() -> path of failing request
              $e->getResponseHeaders() -> actually response headers from failing request
              $e->getResponse() -> curl response object
              $e->getParams() -> optional data that may have been passed that caused the failure

             */
            echo 'Api ';
            print_r($e->getResponse());
            return false;
        } catch (ShopifyCurlException $e) {
            // $e->getMessage() returns value of curl_errno() and $e->getCode() returns value of curl_ error()
            echo 'Curl ';
            print_r($e->getMessage());
            return false;
        }
    }
    
    public function customer_data_request() {
        try {
            $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
            $data = file_get_contents('php://input');
            $verified = $this->verify_webhook($data, $hmac_header);
            $info = json_decode($data);
            if (isset($info->myshopify_domain)) {
                $customer = array(
                    'shop' => $info->myshopify_domain,
                    'data' => $data,
                );
                return $customer;
            } else {
                log_message('error', 'Webhook: error no domain info ' . $data); //check error.log to see the result                
            }
        } catch (Exception $e) {
            log_message('error', 'Webhook: Wrong way ' . join('->', $e)); //check error.log to see the result
        }
        echo 'Ok';
       // echo 'Ok';
    }
    
    public function customer_data_delete() {
        try {
            $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
            $data = file_get_contents('php://input');
            $verified = $this->verify_webhook($data, $hmac_header);
            $info = json_decode($data);
            if (isset($info->myshopify_domain)) {
                $customer = array(
                    'shop' => $info->myshopify_domain,
                    'data' => $data,
                );
                if ($this->customers_model->insert_uninstall_webhooks($customer)) {
                    $status = 'Successfully updated';
                } else {
                    log_message('error', 'Webhook: error updatind in database' . $data); //check error.log to see the result
                }
            } else {
                log_message('error', 'Webhook: error no domain info ' . $data); //check error.log to see the result                
            }
        } catch (Exception $e) {
            log_message('error', 'Webhook: Wrong way ' . join('->', $e)); //check error.log to see the result
        }
        echo 'Ok';
    }
    
    public function shop_data_delete() {
        try {
            $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
            $data = file_get_contents('php://input');
            $verified = $this->verify_webhook($data, $hmac_header);
            $info = json_decode($data);
            if (isset($info->myshopify_domain)) {
                $customer = array(
                    'shop' => $info->myshopify_domain,
                    'data' => $data,
                );
                if ($this->customers_model->insert_uninstall_webhooks($customer)) {
                    $status = 'Successfully updated';
                } else {
                    log_message('error', 'Webhook: error updatind in database' . $data); //check error.log to see the result
                }
            } else {
                log_message('error', 'Webhook: error no domain info ' . $data); //check error.log to see the result                
            }
        } catch (Exception $e) {
            log_message('error', 'Webhook: Wrong way ' . join('->', $e)); //check error.log to see the result
        }
        echo 'Ok';
        
    }

}