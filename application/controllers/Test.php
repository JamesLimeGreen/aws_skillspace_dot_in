<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller{

	public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->session_data();
        
        $this->session->set_userdata("lang","en");
        $language=$this->uri->segment(2);
        $language =  $this->crud_model->get_lang_long($language)->row_array();
        if($language){
            $this->session->set_userdata('language', $language['language_name']);
        }
        
        //== set language =========
        if(@$_GET['lang'] == 'en' || @$_GET['language']=="english"){
          $this->session->set_userdata('language', 'english');
        }
        if(@$_GET['lang'] == 'af' || @$_GET['language']=="afrikaans"){
          $this->session->set_userdata('language', 'afrikaans');
        }
        if(@$_GET['lang'] == 'zu' || @$_GET['language']=="zulu"){
          $this->session->set_userdata('language', 'zulu');
        }
    }

	public function remote_image(){
		$page_data = array();
		$page_data['page_name'] = "test";
        $page_data['page_title'] = 'awesome';
		//$page_data['top_10_categories']= $this->crud_model->get_top_categories(12, 'sub_category_id'); 
        //$this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);

        echo '<img src="https://ik.imagekit.io/hydlcbl5qlg/public/misc/ds7ontffLJJXvlzVCZ7EKk3VbzkXsnJECP8TnfL6.jpg" style="width:100%">';
	}


	public function local_image(){
		$page_data = array();
		$page_data['page_name'] = "test";
        $page_data['page_title'] = 'awesome';
		//$page_data['top_10_categories']= $this->crud_model->get_top_categories(12, 'sub_category_id'); 
        //$this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);

        echo '<img src="https://skillspace.co.za/uploads/thumbnails/banner_thumbnails/e5e873e6105ebea561f0bfb10d3ed71d.jpg" style="width:100%">';
	}


	public function session_data()
    {
        // SESSION DATA FOR CART
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }

        // SESSION DATA FOR FRONTEND LANGUAGE
        if (!$this->session->userdata('language')) {
            $this->session->set_userdata('language', get_settings('language'));
        }
    }

}