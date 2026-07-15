<?php

class Admin extends Admin_Controller {

    protected $section = 'Customers';

    /**
     * Constructor method
     *
     * @access  public
     * @return  void
     */
    public $validation = array(
        array(
            'field' => 'smodlightbox_name',
            'label' => 'Name',
            'rules' => 'required'
        ),
        array(
            'field' => 'smodlightbox_transition',
            'label' => 'Transition',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_speed',
            'label' => 'Speed',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_scale_photos',
            'label' => 'Scale Photos',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_scrolling',
            'label' => 'Scrolling',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_opacity',
            'label' => 'Opacity',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_auto_open',
            'label' => 'Auto Open',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_overlay_close',
            'label' => 'Overlay Close',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_escape_close',
            'label' => 'Escape Key Close',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_fade_out',
            'label' => 'Fade Out',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_content_type',
            'label' => '-',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_open_website_url',
            'label' => 'Overlay Close',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_html',
            'label' => 'Fade Out',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_image',
            'label' => 'Image',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_width',
            'label' => 'Width',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_height',
            'label' => 'Width',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_mailchimp',
            'label' => 'Mailchimp integration',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_image_link',
            'label' => 'Link to',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_link_target',
            'label' => 'Open on?',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_image_url',
            'label' => 'Image',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_overlay_background',
            'label' => 'Overlay Background',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_content_background',
            'label' => 'Content Background',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_color',
            'label' => 'Subscribe Button Text Color',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_background',
            'label' => 'Subscribe Button Background',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_position',
            'label' => 'Subscribe Button Position',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_distance_top',
            'label' => 'Subscribe Button Distance Top',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_distance_left',
            'label' => 'Subscribe Button Distance Left',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_distance_right',
            'label' => 'Subscribe Button Distance Right',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_distance_bottom',
            'label' => 'Subscribe Button Distance Bottom',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_subscribe_border_color',
            'label' => 'Subscribe Button Border Color',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_delay_start',
            'label' => 'Delay Start',
            'rules' => ''
        ),
        array(
            'field' => 'smodlightbox_page',
            'label' => 'Page to show',
            'rules' => ''
        ),
        array(
            'field' => 'active',
            'label' => 'Activate',
            'rules' => 'required'
        )
    );

    public function __construct() {
        parent::__construct();
        $this->config->load('config');
        $this->lang->load('customers');
        $this->load->library('shopify/shopify');
        $this->load->model('customers_model');
        role_or_die('users', 'admin_profile_fields');
    }

    public function index() {

        $base_where = array();

        // ---------------------------
        // User Filters
        // ---------------------------
        // Determine active param
        if($this->input->post('f_status')!=''){
            $base_where['status'] = $this->input->post('f_status');
        }
        // Keyphrase param
        $base_where = $this->input->post('f_keywords') ? $base_where + array('keywords' => $this->input->post('f_keywords')) : $base_where;

        // Create pagination links
        $total=$this->customers_model->count($base_where);
        $pagination = create_pagination('admin/customers/index', $total,30,4);
        
        
        // Using this data, get the relevant results
        
        $customers = $this->customers_model->get($base_where, $pagination);
        $events = $this->customers_model->events();
        $statuses = array(
            '' => 'All',
            '1' => 'Active',
            '0' => 'Wating to Payment Activation',
            '2' => 'Uninstalled'
        );
        $this->template
                ->title($this->module_details['name'])
                ->set('customers', $customers)
                ->set('statuses', $statuses)
                ->set('pagination', $pagination)
                ->set_partial('filters', 'admin/filters/customers')
                ->set_partial('table', 'admin/tables/customers');

        $this->template->build('admin/index');
    }

    public function login_as($shop) {
        $customer = $this->customers_model->read($shop);
        if ($customer->num_rows() > 0) {
            $this->session->set_userdata('shop', $shop);
            $customer = $customer->row();
            $this->session->set_userdata('token', $customer->token);
            $this->session->set_userdata('status', 'active');
            $this->session->set_userdata('actual_theme', $customer->actual_theme);
            $this->session->set_userdata('page_template_selected', $customer->page_template_selected);
            $this->session->set_userdata('id', $customer->id);
            $this->session->unset_userdata('lookbooks_in_dashboard_checked');
            //$this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $customer->token, 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
            //$this->session->set_userdata('id', $customer->id);
            redirect('lookbook');
        } else {
            redirect('admin/customers');
        }
    }

    public function view($shop) {
        if ($shop) {
            $query = $this->customers_model->read($shop);
            if ($query->num_rows() > 0) {
                $customer = $query->row();
                $lightboxes = $this->lightbox_model->list_lightboxes_shop($customer->id);
                $lightboxes_dropdown = array();
                $active_lightbox = '';
                if ($lightboxes) {
                    foreach ($lightboxes as $lbx) {
                        $settings = json_decode($lbx->settings);
                        $lightboxes_dropdown[$lbx->id] = (isset($settings->smodlightbox_name) ? $settings->smodlightbox_name : 'No title');
                        if ($lbx->active == 1) {
                            $active_lightbox = $lbx->id;
                        }
                    }
                }
                $this->template
                        ->set('shop', $shop)
                        ->set('lightboxes', $lightboxes)
                        ->set('lightboxes_dropdown', $lightboxes_dropdown)
                        ->set('active_lightbox', $active_lightbox)
                        ->build('admin/view');
            } else {
                redirect('customers/admin');
            }
        } else {
            redirect('customers/admin');
        }
    }

    public function update_order() {
        $banners = $this->input->post('banners');
        $error = false;
        if ($banners) {
            $n = 1;
            foreach ($banners as $banner) {
                $data = array('id' => $banner['id'], 'order' => $banner['order']);
                $action = $this->banners_model->update($data);
                if (!$action) {
                    $error = true;
                }
            }
            if (!$error) {
                $json = array('status' => 'success', 'msg' => 'The banners order was updated');
            } else {
                $json = array('status' => 'error', 'msg' => 'There were problems');
            }
        } else {
            $json = array('status' => 'error', 'msg' => 'There were no changes in the order');
        }
        echo json_encode($json);
    }

    public function insert() {
        if ($this->input->post('name')) {
            $data = array('name' => $this->input->post('name'), 'path' => $this->input->post('path'), 'file_id' => $this->input->post('file_id'), 'group' => $this->input->post('group'));
            if ($this->banners_model->insert($data)) {
                $json = array('status' => 'success', 'msg' => 'The banner was added');
            } else {
                $json = array('status' => 'error', 'msg' => 'There were problems');
            }
        } else {
            $json = array('status' => 'error', 'msg' => 'There were problems');
        }
        echo json_encode($json);
    }

    function add() {
        $this->template->build('admin/form');
    }

    public function action() {
        // Determine the type of action
        switch ($this->input->post('btnAction')) {
            case 'delete':
                $this->delete();
                break;
            default:
                redirect('admin/banners');
                break;
        }
    }

    /**
     * Delete an existing widget area
     * 
     * @return void
     */
    public function delete($id = 0) {

        $ids = ($id > 0) ? array($id) : $this->input->post('action_to');

        if (!empty($ids)) {
            $deleted = 0;
            $to_delete = 0;
            $deleted_ids = array();
            foreach ($ids as $id) {
                if ($this->banners_model->delete($id)) {
                    $deleted_ids[] = $id;
                    $deleted++;
                }
                $to_delete++;
            }

            if ($to_delete > 0) {
                // Fire an event. One or more users have been deleted. 
                //Events::trigger('service_deleted', $deleted_ids);
                $status = 'success';
                $message = lang('success_label');
                $this->session->set_flashdata('success', sprintf(lang('banners_mass_delete_success'), $deleted, $to_delete));
            }
        }
        // The array of id's to delete is empty
        else {
            $status = 'error';
            $message = lang('general_error_label');
            $this->session->set_flashdata('error', lang('banners_mass_delete_error'));
        }

        if ($this->input->is_ajax_request()) {
            $data = array();

            $data['messages'][$status] = $message;
            $message = $this->load->view('admin/partials/notices', $data, TRUE);

            return $this->template->build_json(array(
                        'status' => $status,
                        'message' => $message
            ));
        }

        redirect('admin/banners');
    }

    /**
     * Edit widget area
     * 
     * @return void
     */
    public function edit($id = 0) {
        $data = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->validation_rules);
        if ($id) {
            if ($this->form_validation->run()) {
                $input = array(
                    'id' => $id,
                    'group' => $this->input->post('group')
                );

                if ($this->banners_model->update($input)) {
                    $status = 'success';
                    $message = lang('success_label');
                } else {
                    $status = 'error';
                    $message = lang('error_label');
                }

                if ($this->input->is_ajax_request()) {
                    $data = array();

                    $data['messages'][$status] = $message;
                    $message = $this->load->view('admin/partials/notices', $data, TRUE);

                    return $this->template->build_json(array(
                                'status' => $status,
                                'message' => $message,
                                'html' => ($status === 'success' ? $this->index() : NULL)
                    ));
                }

                if ($status === 'success') {
                    $this->session->set_flashdata($status, $message);

                    redirect('admin/banners');
                    return;
                }

                $data['messages'][$status] = $message;
            } elseif (validation_errors()) {
                if ($this->input->is_ajax_request()) {
                    $status = 'error';
                    $message = $this->load->view('admin/partials/notices', array(), TRUE);

                    return $this->template->build_json(array(
                                'status' => $status,
                                'message' => $message
                    ));
                }
            }
            $banner = $this->banners_model->read($id);
            $groups = $this->banners_model->list_groups_dropdown();

            $this->template
                    ->set('groups', $groups)
                    ->set('banner', $banner)
                    ->build('admin/form');
        }
    }

    public function upload() {
        // this is just a safeguard if they circumvent the JS permissions
        if (!in_array('upload', Files::allowed_actions())) {
            show_error(lang('files:no_permissions'));
        }
        $input = $this->input->post();
        if (!$input['folder_id']) {
            $result = Files::create_folder(null, 'Banners');
            $input['folder_id'] = $result['data']['id'];
        }
        if ($input['folder_id'] AND $input['name']) {
            $result = Files::upload($input['folder_id'], $input['name'], 'file', $input['width'], $input['height'], $input['ratio']);
            $result['data']['group'] = $input['group'];
            $result['status'] AND Events::trigger('file_uploaded', $result['data']);

            echo json_encode($result);
        }
    }

    public function check_active() {
        $error_message = '';
        $shops = $this->customers_model->get_active();
        foreach ($shops as $shop) {
            $this->shopify->init(array('shop_domain' => $shop->shop, 'token' => $shop->token, 'api_key' => $this->config->item('SHOPIFY_API_KEY'), 'secret' => $this->config->item('SHOPIFY_SECRET')));
            try {
                $charge_id = $shop->charge_id;
                if ($charge_id) { //If a charge already exist we check if stills active
                    $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '.json');
                    if ($data['status'] != 'active') {
                        $shop_data = array(
                            'shop' => $shop->shop,
                            'charge_data' => json_encode($data),
                            'charge_status' => $data['status']
                        );
                        $this->customers_model->update($shop_data);
                    }
                    echo $shop->shop . ' -> ' . $data['status'] . '<br/>';
                } else {
                    echo $shop->shop . ' -> No charge id <br/>';
                }
            } catch (ShopifyApiException $e) {
                $error_message = $error_message . ' Api ' . $e->getResponse();
                $errors = $e->getResponse();
                if ($errors['errors'] == '[API] Invalid API key or access token (unrecognized login or wrong password)') {
                    $shop_data = array(
                        'shop' => $shop->shop,
                        'charge_id' => '',
                        'charge_status' => 'uninstall',
                        'status' => '2'
                    );
                    $this->customers_model->update($shop_data);
                }
                echo $shop->shop . ' -> uninstall<br/>';
            } catch (ShopifyCurlException $e) {
                $error_message = $error_message . ' Curl ' . $e->getMessage();
                echo $error_message;
            }
        }
    }

    public function check_shop_active($shop) {
        $error_message = '';
        $shop = $this->customers_model->read($shop)->row();
        $this->shopify->init(array('shop_domain' => $shop->shop, 'token' => $shop->token, 'api_key' => $this->config->item('SHOPIFY_API_KEY'), 'secret' => $this->config->item('SHOPIFY_SECRET')));
        try {
            $charge_id = $shop->charge_id;
            if ($charge_id) { //If a charge already exist we check if stills active
                if ($charge_id != 'FREE') {
                    $data = $this->shopify->call('GET', '/admin/api/2023-07/recurring_application_charges/' . $charge_id . '.json');
                    if ($data['status_response'] == 'error') {
                        echo 'The app was uninstalled <br/>';
                        $customer = array(
                            'shop' => $shop->shop,
                            'script_tag_id' => '',
                            'charge_id' => '',
                            'charge_status' => 'Cancelled',
                            'status' => 2
                        );
                        if ($this->customers_model->update($customer)) {
                            $this->load->library('themes/lookbook_themes');
                            $this->lookbook_themes->create_uninstaled_javascript($shop->shop);
                            $status = 'Successfully updated';
                        } else {
                            $status = 'Error updating';
                        }
                        $event = array(
                            'description' => 'App uninstalled',
                            'data' => $data . ' status_' . $status
                        );
                        $this->customers_model->add_event($event);
                        echo 'Ok';
                    } else {
                        echo 'Active';
                    }
                } else {
                    echo 'Installed for free';
                }
            } else {
                echo $shop->shop . ' -> No charge id <br/>';
                /* $shop_data = array(
                  'shop' => $shop->shop,
                  'charge_id'=>$last['id'],
                  'charge_data' => json_encode($last),
                  'charge_status' => $last['status']
                  );
                  $this->customers_model->update($shop_data); */
            }
        } catch (ShopifyApiException $e) {
            $error_message = $error_message . ' Api ' . $e->getResponse();
            $errors = $e->getResponse();
            print_r($errors);
            $shop_data = array(
                'shop' => $shop->shop,
                'charge_status' => 'uninstall',
                'status' => '2'
            );
            $this->customers_model->update($shop_data);
            echo $shop->shop . ' -> error, uninstall<br/>';
        } catch (ShopifyCurlException $e) {
            $error_message = $error_message . ' Curl ' . $e->getMessage();
            echo $error_message;
        }
    }

    public function edit_lightbox($shop, $lightbox_id, $security) {
        if (sha1($lightbox_id . $this->config->item('encryption_key')) == $security) {
            if ($shop) {
                $query = $this->customers_model->read($shop);
                if ($query->num_rows() > 0) {
                    $customer = $query->row();
                    $set = $this->lightbox_model->get_lightbox($lightbox_id);

                    if ($set) {
                        $settings = (array) json_decode($set->settings);
                        foreach ($this->validation as $val):
                            if (!isset($settings[$val['field']])) {
                                $settings[$val['field']] = '';
                            }
                        endforeach;
                        $updated = $set->updated;
                    } else {
                        foreach ($this->validation as $val):
                            $settings[$val['field']] = '';
                        endforeach;
                        $updated = strtotime(now());
                    }
                    //$pages=$this->get_pages();
                    $this->template
                            ->append_metadata('<link href="' . base_url() . 'lightbox/load_asset/colorbox" rel="stylesheet" />')
                            ->set('lightbox_id', $set->id)
                            ->set('security', $security)
                            ->set('updated', $updated)
                            //->set('pages',$pages)
                            //->append_js('functions_codemirror.js')
                            ->build('admin/form', $settings);
                }else {
                    redirect('admin/customers');
                }
            } else {
                redirect('admin/customers');
            }
        } else {
            redirect('admin/customers');
        }
    }
    function check_page_template($store){
        $shop=$this->customers_model->read($store)->row();
        $shop->page_template_selected;
        $this->shopify->init(array('shop_domain' => $shop->shop, 'token' => $shop->token, 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        $theme_id=$shop->actual_theme;
        $data = $this->shopify->call('GET', '/admin/api/2023-07/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'templates/page.lookbooks-modmedia-single.liquid')));
        if ($data['status_response'] == 'success') {
            $content = htmlspecialchars($data['asset']['value']);
            echo $content;
        }else{
            print_r($data);
        }
    }
    function check_pages($store){
        $shop=$this->customers_model->read($store)->row();
        $this->load->model('lookbook/lookbooks_model');
        $lookbooks=$this->lookbooks_model->list_lookbooks_shop($shop->id);
        foreach($lookbooks as $lookbook):
            print_r($lookbook);
        $this->shopify->init(array('shop_domain' => $shop->shop, 'token' => $shop->token, 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        $theme_id=$shop->actual_theme;
        //$data = $this->shopify->call('GET', '/admin/api/2023-07/pages/' . $lookbook->shopify_page_id . '.json');
        $data = $this->shopify->call('GET', '/admin/api/2023-07/pages.json');
        if ($data['status_response'] == 'success') {
            print_r($data);
        }else{
            print_r($data);
        }
        endforeach;
    }
    function check_script_tags($store){
        $shop=$this->customers_model->read($store)->row();
        $shop->page_template_selected;
        $this->shopify->init(array('shop_domain' => $shop->shop, 'token' => $shop->token, 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
        $data = $this->shopify->call('GET', '/admin/api/2023-07/script_tags.json');
        echo $shop->script_tag_id;
        echo '<br/>';
        if ($data['status_response'] == 'success') {
            print_r($data);            
        }else{
            print_r($data);            
        }
    }
}
