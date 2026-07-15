<?php

class Admin_charges extends Admin_Controller {

    protected $section = 'customer:charges';
    
    /**
     * Constructor method
     *
     * @access  public
     * @return  void
     */
    public $validation = array(
        array(
            'field' => 'shop',
            'label' => 'Shop',
            'rules' => 'required'
        ),
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required'
        ),
        array(
            'field' => 'price',
            'label' => 'Price',
            'rules' => 'required'
        ),
        array(
            'field' => 'trial_days',
            'label' => 'Trial Days',
            'rules' => 'required'
        ),
        array(
            'field' => 'test',
            'label' => 'Test',
            'rules' => 'required'
        ),
        array(
            'field' => 'used',
            'label' => 'Used',
            'rules' => 'required'
        )
    );
     public function __construct() {
        parent::__construct();
        $this->config->load('config');
        $this->lang->load('customers');
        $this->load->library('shopify/shopify');
        $this->load->model('charges_model');
        $this->load->model('customers_model');
        role_or_die('users', 'admin_profile_fields');
    }

    public function index() {
        $charges=$this->charges_model->get();
            $this->template
                    ->set('charges',$charges)
                    ->set_partial('table','admin/tables/charges')
                    ->build('admin/charges/index');
    }
    
    
    public function create(){
        $this->form_validation->set_rules($this->validation);
        if($this->form_validation->run()===false){
            $charge=(object) array();
            foreach($this->validation as $val):
                $charge->{$val['field']}='';
            endforeach;
            $this->template
                    ->set('charge',$charge)
                    ->build('admin/charges/form');
        }else{
            $charge=array();
            foreach($this->validation as $val):
                $charge[$val['field']]=$this->input->post($val['field']);
            endforeach;
            if($id=$this->charges_model->create($charge)){
                $this->session->set_flashdata('success','The Charges were saved successfully');
                redirect('admin/customers/charges/edit/'.$id);
            }else{
                $this->session->set_flashdata('error',"The charge couldn't be created");
                redirect('admin/customers/charges');
            }
        }
    }
    public function edit($id){
        if(!$id){
            redirect('admin/customers/charges');
        }else{
            $this->form_validation->set_rules($this->validation);
            if($this->form_validation->run()===false){
                $charge=$this->charges_model->read($id);
                $this->template
                        ->set('charge',$charge)
                        ->build('admin/charges/form');
            }else{
                $charge=array();
                foreach($this->validation as $val):
                    $charge[$val['field']]=$this->input->post($val['field']);
                endforeach;
                if($this->charges_model->update($charge)){
                    $this->session->set_flashdata('success','The Charges were saved successfully');
                    redirect('admin/customers/charges/edit/'.$id);
                }else{
                    $this->session->set_flashdata('error',"The charge couldn't be created");
                    redirect('admin/customers/charges');
                }
            }
        }
    }
     public function action(){
       
        switch ($this->input->post('btnAction')) {
            case 'activate':
                $this->activate();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                redirect('admin/customers/charges');
                break;
        }
    }
    public function delete(){
        echo "NOT DELETED..";
    }
    public function activate() 
    {
        $ids = $this->input->post('action_to');
        foreach ($ids as $id) 
        {
            $shop = $this->charges_model->get_shop_details($id);
            if($shop!="0") 
            {
                $this->shopify->init(array('shop_domain' => $shop->shop, 'token' => $shop->token, 'api_key' => $this->config->item('SHOPIFY_API_KEY'), 'secret' => $this->config->item('SHOPIFY_SECRET')));
                try 
                {
                    $pageURL = site_url('customers/activate');
                    $special_charge = $this->customers_model->check_special_charge($shop->shop);
                    $type = 'normal';
                    if ($special_charge) 
                    {
                        if ($special_charge->price > 0) 
                        {
                            if ($special_charge->test == 0) 
                            {
                                $data = $this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges.json', array('recurring_application_charge' => array('name' => $special_charge->name, 'price' => $special_charge->price, 'return_url' => $pageURL, 'trial_days' => $special_charge->trial_days)));
                            } 
                            else 
                            {
                                $data = $this->shopify->call('POST', '/admin/api/2023-07/recurring_application_charges.json', array('recurring_application_charge' => array('name' => $special_charge->name, 'price' => $special_charge->price, 'return_url' => $pageURL, 'trial_days' => $special_charge->trial_days,'test' => true)));
                            }
                        } 
                        else
                        {
                            $type = 'free';
                        }
                        if ($type == 'free') 
                        {
                            $shop_data = array(
                                'shop' => $shop->shop,
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
                                'shop'=>$shop->shop,
                                'used'=>1
                            );
                            $this->load->model('charges_model');
                            $this->charges_model->update($data_charge);
                            $this->session->set_userdata('status', 'active');
                            echo  '<script> alert ("Free installation has been applied");</script>';
                            //redirect ($data['recurring_application_charge']['confirmation_url']);
                            echo "<script type=\"text/javascript\">
                                    window.open('https://modapps2.com.au/lookbook', '_blank')
                                  </script>";                            
                        } 
                        else 
                        {
                            if ($data['status_response'] == 'error') 
                            {
                                $event = array(
                                    'description' => 'Error creating recurring payment',
                                    'data' => json_encode($data)
                                );
                                $this->customers_model->add_event($event);
                                $this->template
                                        ->build('error', array('data' => $data));
                            } 
                            else 
                            {
                                $shop_data = array(
                                    'shop' => $shop->shop,
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
                    }
                } 
                catch(Exception $e)
                {
                    echo $e->getMessage();
                }
            }       
           else
           {
                echo '<script> alert ("already applied charges");</script>';
                echo "<script type=\"text/javascript\">
                                    window.open('https://modapps2.com.au/lookbook', '_blank')
                                  </script>";  
           } 
        }
    }       
}
