<?php

class Themes extends Public_Controller {
    private $form_lightbox_slideshow = array(
        array(
            'field' => 'display_effect',
            'label' => 'Display Effect',
            'rules' => ''
        ),
        array(
            'field' => 'overlay_background',
            'label' => 'Overlay Background',
            'rules' => 'required'
        ),
        array(
            'field' => 'content_background',
            'label' => 'Content Background',
            'rules' => ''
        ),
        array(
            'field' => 'slider_animation',
            'label' => 'Animation',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_direction',
            'label' => 'Direction',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_reverse',
            'label' => 'Reverse',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_animation_loop',
            'label' => 'Animation Loop',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_smooth_height',
            'label' => 'Smooth Height',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_slideshow',
            'label' => 'Slide Automatically',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_speed',
            'label' => 'Slideshow Speed',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_anim_speed',
            'label' => 'Slideshow Animation Speed',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_control_nav',
            'label' => 'Show Control Navigation',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_width',
            'label' => 'Dots Width',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_height',
            'label' => 'Dots Height',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_top',
            'label' => 'Dots Margin top',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_left',
            'label' => 'Dots Margin left',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_right',
            'label' => 'Dots Margin right',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_bottom',
            'label' => 'Dots Margin bottom',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bd_wd',
            'label' => 'Dots Border Width',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bd_st',
            'label' => 'Dots Border Style',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bd_rad',
            'label' => 'Dots Border Radius',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_shad',
            'label' => 'Show Shadow',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_num',
            'label' => 'Show Numbers',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bg',
            'label' => 'Dots background',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_hover_bg',
            'label' => 'Dots hover background',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_active_bg',
            'label' => 'Dots active background',
            'rules' => 'required'
        ), 
        array(
            'field' => 'slider_ct_bd',
            'label' => 'Dots border color',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_hover_bd',
            'label' => 'Dots hover border',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_active_bd',
            'label' => 'Dots active border',
            'rules' => 'required'
        ), 
        array(
            'field' => 'slider_ct_txt',
            'label' => 'Dots text color',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_hover_txt',
            'label' => 'Dots hover text',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_active_txt',
            'label' => 'Dots active text',
            'rules' => 'required'
        ), 
        array(
            'field' => 'slider_dir_nav',
            'label' => 'Show Direction Navigation',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_dir_nav_prev',
            'label' => 'Previous Button Text',
            'rules' => ''
        ),
        array(
            'field' => 'slider_dir_nav_next',
            'label' => 'Next Button Text',
            'rules' => ''
        ),
        array(
            'field' => 'slider_dir_nav_use_image',
            'label' => 'Use image instead of text',
            'rules' => ''
        ),
        array(
            'field' => 'slider_mobile_nav_use_image',
            'label' => 'Display navigation arrow/text for mobile',
            'rules' => ''
        ),
        array(
            'field' => 'slider_dir_nav_image',
            'label' => 'Image',
            'rules' => ''
        ),
        array(
            'field' => 'slider_carousel',
            'label' => 'Slideshow in Carousel Mode',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_car_width',
            'label' => 'Item Width',
            'rules' => ''
        ),
        array(
            'field' => 'slider_car_margin',
            'label' => 'Carousel Item Margin',
            'rules' => ''
        ),
        array(
            'field' => 'slider_car_min',
            'label' => 'Min Items',
            'rules' => 'integer'
        ),
        array(
            'field' => 'slider_car_max',
            'label' => 'Max Items',
            'rules' => 'integer'
        ),
        array(
            'field' => 'slider_car_move',
            'label' => 'Items to Move/Slide',
            'rules' => 'integer'
        )
    );
    private $form_lightbox_list = array(
        array(
            'field' => 'display_effect',
            'label' => 'Display Effect',
            'rules' => ''
        ),
        array(
            'field' => 'overlay_background',
            'label' => 'Overlay Background',
            'rules' => 'required'
        ),
        array(
            'field' => 'content_background',
            'label' => 'Content Background',
            'rules' => ''
        )
    );
    private $form_slideshow = array(
        array(
            'field' => 'slider_animation',
            'label' => 'Animation',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_direction',
            'label' => 'Direction',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_reverse',
            'label' => 'Reverse',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_animation_loop',
            'label' => 'Animation Loop',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_smooth_height',
            'label' => 'Smooth Height',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_slideshow',
            'label' => 'Slide Automatically',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_speed',
            'label' => 'Slideshow Speed',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_anim_speed',
            'label' => 'Slideshow Animation Speed',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_control_nav',
            'label' => 'Show Control Navigation',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_width',
            'label' => 'Dots Width',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_height',
            'label' => 'Dots Height',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_top',
            'label' => 'Dots Margin top',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_left',
            'label' => 'Dots Margin left',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_right',
            'label' => 'Dots Margin right',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_mg_bottom',
            'label' => 'Dots Margin bottom',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bd_wd',
            'label' => 'Dots Border Width',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bd_st',
            'label' => 'Dots Border Style',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bd_rad',
            'label' => 'Dots Border Radius',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_shad',
            'label' => 'Show Shadow',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_num',
            'label' => 'Show Numbers',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_bg',
            'label' => 'Dots background',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_hover_bg',
            'label' => 'Dots hover background',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_active_bg',
            'label' => 'Dots active background',
            'rules' => 'required'
        ), 
        array(
            'field' => 'slider_ct_bd',
            'label' => 'Dots border color',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_hover_bd',
            'label' => 'Dots hover border',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_active_bd',
            'label' => 'Dots active border',
            'rules' => 'required'
        ), 
        array(
            'field' => 'slider_ct_txt',
            'label' => 'Dots text color',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_hover_txt',
            'label' => 'Dots hover text',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_ct_active_txt',
            'label' => 'Dots active text',
            'rules' => 'required'
        ), 
        array(
            'field' => 'slider_dir_nav',
            'label' => 'Show Direction Navigation',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_dir_nav_prev',
            'label' => 'Previous Button Text',
            'rules' => ''
        ),
        array(
            'field' => 'slider_dir_nav_next',
            'label' => 'Next Button Text',
            'rules' => ''
        ),
        array(
            'field' => 'slider_dir_nav_use_image',
            'label' => 'Use image instead of text',
            'rules' => ''
        ),
        array(
            'field' => 'slider_mobile_nav_use_image',
            'label' => 'Display navigation arrow/text for mobile',
            'rules' => ''
        ),
        array(
            'field' => 'slider_dir_nav_image',
            'label' => 'Image',
            'rules' => ''
        ),
        array(
            'field' => 'slider_carousel',
            'label' => 'Slideshow in Carousel Mode',
            'rules' => 'required'
        ),
        array(
            'field' => 'slider_car_width',
            'label' => 'Item Width',
            'rules' => ''
        ),
        array(
            'field' => 'slider_car_margin',
            'label' => 'Carousel Item Margin',
            'rules' => ''
        ),
        array(
            'field' => 'slider_car_min',
            'label' => 'Min Items',
            'rules' => 'integer'
        ),
        array(
            'field' => 'slider_car_max',
            'label' => 'Max Items',
            'rules' => 'integer'
        ),
        array(
            'field' => 'slider_car_move',
            'label' => 'Items to Move/Slide',
            'rules' => 'integer'
        )
    );
    private $form_list = array(
        
    );
    private $defaults = array(
        'display_effect'=>'',
        'overlay_background'=>'#000000',
        'content_background'=>'#FFFFFF',
        'slider_animation'=>'slide',
        'slider_direction'=>'horizontal',
        'slider_reverse'=>0,
        'slider_animation_loop'=>0,
        'slider_smooth_height'=>0,
        'slider_slideshow'=>0,
        'slider_speed'=>7000,
        'slider_anim_speed'=>600,
        'slider_control_nav'=>1,
        'slider_ct_mg_top'=>0,
        'slider_ct_mg_left'=>6,
        'slider_ct_mg_right'=>6,
        'slider_ct_mg_bottom'=>0,
        'slider_ct_width'=>11,
        'slider_ct_height'=>11,
        'slider_ct_bd_wd'=>0,
        'slider_ct_bd_st'=>'none',
        'slider_ct_bd_rad'=>20,
        'slider_ct_num'=>0,
        'slider_ct_shad'=>1,
        'slider_ct_bg'=>'#555555',
        'slider_ct_hover_bg'=>'#D4521F',
        'slider_ct_active_bg'=>'#ed6647',
        'slider_ct_bd'=>'#555555',
        'slider_ct_hover_bd'=>'#D4521F',
        'slider_ct_active_bd'=>'#ed6647',
        'slider_ct_txt'=>'#000000',
        'slider_ct_hover_txt'=>'#444444',
        'slider_ct_active_txt'=>'#EFEFEF',
        'slider_dir_nav'=>1,
        'slider_dir_nav_prev'=>'Previous',
        'slider_dir_nav_next'=>'Next',
        'slider_dir_nav_use_image'=>0,
        'slider_mobile_nav_use_image'=>1,
        'slider_dir_nav_image'=>'',
        'slider_carousel'=>0,
        'slider_car_width'=>0,
        'slider_car_margin'=>0,
        'slider_car_min'=>0,
        'slider_car_max'=>0,
        'slider_car_move'=>0
    );
    function __construct() {
        parent::__construct();
        $this->load->model('themes_model');
        $this->load->library('shopify/shopify');
        $this->load->library('lookbook_themes');
    }

    public function index() {
        $themes=$this->themes_model->list_themes();
        $data=array(
            'lookbook'=>$this->input->post('lookbook'),
            'themes'=>$themes
        );
        echo $this->load->view('list',$data, true);
    }
    public function create_mains(){
        $this->lookbook_themes->create_magnific_javascript();
        $this->lookbook_themes->create_flexslider_javascript();
        echo 'Cool';
    }
    public function edit($theme, $lookbook=null, $security=null) {
        if(!$security){
            $this->session->set_flashdata('error','You need to create the lookbook first to edit the theme');
            redirect('lookbook/add');
        }else{
            if(!file_exists(FCPATH . "assets/templates/" . $this->session->userdata('shop') . '/page.lookbooks-modmedia-list.liquid')){
                $this->lookbook_themes->create_page_lookbooks_list($lookbook);
            }
            if(!file_exists(FCPATH . "assets/templates/" . $this->session->userdata('shop') . '/page.lookbooks-modmedia-single.liquid')){
               $this->lookbook_themes->create_page_lookbooks_single($lookbook);
            }
            $theme=$this->themes_model->get($theme);
            $this->load->model('lookbook/lookbooks_model');
            $lookbook_data=$this->lookbooks_model->get($lookbook);
            if($lookbook_data->theme_settings){
                $settings=json_decode($lookbook_data->theme_settings);
            }else{
                $settings=(object) array();            
            }
            $validation_name='form_'.str_replace('-','_',$theme->handle);
            foreach ($this->{$validation_name} as $val):
                if(!isset($settings->{$val['field']})){
                    $settings->{$val['field']}=$this->defaults[$val['field']];
                }                    
            endforeach;
            $this->template
                    ->append_css('codemirror.css')
                    ->append_css('jquery.minicolors.css')
                    ->append_js('libs/jquery.minicolors.min.js')
                    ->append_js('libs/codemirror/codemirror.js')
                    ->append_js('libs/codemirror/mode/xml/xml.js')
                    ->append_js('libs/codemirror/mode/htmlmixed/htmlmixed.js')
                    ->append_js('libs/codemirror/mode/liquid/liquid-mode.js')
                    ->append_js('libs/codemirror/mode/javascript/javascript.js')
                    ->append_js('libs/codemirror/mode/css/css.js')
                    ->append_js('libs/codemirror/mode/vbscript/vbscript.js')
                    ->append_js('libs/codemirror/addon/mode/multiplex.js')
                    ->set('theme', $theme)
                    ->set('lookbook',$lookbook)
                    ->build('form-'.$theme->handle,$settings);
        }
    }
    public function save(){
        $lookbook=$this->input->post('id');
        $theme=$this->input->post('theme');
        $handle='form_'.str_replace('-','_',$this->input->post('handle'));
        $s=$this->input->post('s');
        if(sha1($lookbook.$this->config->item('encryption_key'))==$s){
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->{$handle});
            if ($this->form_validation->run() == false) {
                    $this->session->set_flashdata('error','Please verify the empty fields on your form');
                    $this->edit($theme, $lookbook, $s);                
            } else {
                $settings=$this->input->post();
                unset($settings['id']);
                unset($settings['s']);
                unset($settings['theme']);
                unset($settings['save']);
                if (!empty($_FILES['slider_dir_nav_image']['name'])) {
                    $image_data=$this->do_upload_arrows('slider_dir_nav_image', 'lookbook-modmedia-'.$lookbook.'-arrows', $this->session->userdata('shop'));
                    $settings['slider_dir_nav_image'] = $image_data['image']; 
                    $settings['slider_dir_nav_image_width']= $image_data['width'];
                    $settings['slider_dir_nav_image_height'] = $image_data['height'];
                } else {
                    if ($this->input->post('old_image')) {
                        $settings['slider_dir_nav_image']=$this->input->post('old_image');
                    } else {
                        $settings['slider_dir_nav_image']='';
                    }                    
                }
                $data=array(
                    'id'=>$lookbook,
                    'theme'=>$theme,
                    'theme_settings'=>json_encode($settings)
                );
                $this->load->model('lookbook/lookbooks_model');
                if($this->lookbooks_model->update($data)){
                    $this->lookbook_themes->create_javascript($lookbook,$s);
                    $this->lookbook_themes->upload_lookbook_styles();
                    $this->lookbook_themes->upload_lookbook_scripts();                    
                    $this->session->set_flashdata('success','The theme settings were saved successfully');
                }else{
                    $this->session->set_flashdata('error','There was an error trying to save the theme');
                }
                redirect('themes/edit/'.$theme.'/'.$lookbook.'/'.sha1($lookbook.$this->config->item('encryption_key')));
            }
        }else{
            echo 'You are attemting to access with wrong data';
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
            $return_data=array(
                'width'=>$upload_data['image_width'],
                'height'=>$upload_data['image_height'],
                'image'=>$this->upload_image_to_shopify($upload_data['file_name'])
            );
            return $return_data;
        }
    }
    private function do_upload_arrows($field = 'image', $name = '', $folder = '') {
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
            $file_name = str_replace('.', '-', str_replace(' ', '', $this->input->post('email'))) ;
        } else {
            $file_name = str_replace('.', '-', str_replace(' ', '', $name)) ;
        }
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field)) {
            $errors = array('status' => 'error', 'msg' => $this->upload->display_errors());
            return $errors;
        } else {
            $upload_data = $this->upload->data();
            $return_data=array(
                'width'=>$upload_data['image_width'],
                'height'=>$upload_data['image_height'],
                'image'=>$this->upload_image_to_shopify($upload_data['file_name'])
            );
            return $return_data;
        }
    }
    private function upload_image_to_shopify($icon) {
        if ($icon) {
            if ($this->session->userdata('token')) {
                $this->shopify->init(array('shop_domain' => $this->session->userdata('shop'), 'token' => $this->session->userdata('token'), 'api_key' => Settings::get('shopify_api_key'), 'secret' => Settings::get('shopify_secret')));
                try {
                    $themes = $this->shopify->call('GET', '/admin/api/2023-07/themes.json', array('fields' => 'id,name,role'));
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
                    $data = $this->shopify->call('PUT', '/admin/themes/' . $theme_id . '/assets.json', array('asset' => array('key' => 'assets/' . $icon, 'src' => site_url('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon))));
                    if ($data['status_response'] == 'success') {
                        if($theme_mobile_id != ''){
                            $data2 = $this->shopify->call('PUT', '/admin/themes/' . $theme_mobile_id . '/assets.json', array('asset' => array('key' => 'assets/' . $icon, 'src' => site_url('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon))));
                        }
                        if (file_exists('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon)) {
                            unlink('uploads/lookbooks/' . $this->session->userdata('shop') . '/' . $icon);
                        }
                        return $data['asset']['public_url'];
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
    public function get_javascript($slug, $return = false){
        
    }
    public function get_code($shop){
        $content_list = file_get_contents(FCPATH . "assets/templates/" . $shop . '/page.lookbooks-modmedia-list.liquid');
        $content_single = file_get_contents(FCPATH . "assets/templates/" . $shop . '/page.lookbooks-modmedia-single.liquid');
        $settings=array(
            'status'=>'success',
            'content_list'=>  $content_list,
            'content_single'=> $content_single
        );
        echo json_encode($settings);
    }
    private function install_theme(){
        
    }
    public function save_template(){
        
    }
}