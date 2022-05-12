<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        //$this->output->cache(1);

        die("Home Login test");
        /*
        try {
        $factory = (new Factory)
        ->withServiceAccount('application/config/skill-space-c31a2-firebase-adminsdk-n7zyj-119c815543.json')
        ->withDatabaseUri('gs://skill-space-c31a2.appspot.com');
        $auth = $factory->createAuth();
        $user = $auth->getUserByEmail('sukhdev.patidar99@gmail.com');
        die("User info " . json_encode($user));
        } catch (\Throwable$th) {
        die("Error" . $th->getMessage());
        }
         */

        // Your own constructor code
        //$this->load->database();
        //date_default_timezone_set("Asia/Kolkata");

        $this->load->library('session');

        // $this->load->library('stripe');
        /*cache control*/

        //$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        //$this->output->set_header('Pragma: no-cache');

        // CHECK CUSTOM SESSION DATA

        $this->session_data();

        $this->session->set_userdata("lang", "en");

        $language = $this->uri->segment(1);

        $language = $this->crud_model->get_lang_long($language)->row_array();
        if ($language) {
            $this->session->set_userdata('language', $language['language_name']);
        }

        //== set language =========
        if (@$_GET['lang'] == 'en' || @$_GET['language'] == "english") {
            $this->session->set_userdata('language', 'english');
        }
        if (@$_GET['lang'] == 'af' || @$_GET['language'] == "afrikaans") {
            $this->session->set_userdata('language', 'afrikaans');
        }
        if (@$_GET['lang'] == 'zu' || @$_GET['language'] == "zulu") {
            $this->session->set_userdata('language', 'zulu');
        }
    }

    public function index()
    {
        $this->home();

    }

    public function verification_code()
    {
        if (!$this->session->userdata('register_email')) {
            redirect(site_url('home/sign_up'), 'refresh');
        }
        $page_data['page_name'] = "verification_code";
        $page_data['page_title'] = site_phrase('verification_code');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function home()
    {

        try {
            $page_data['page_name'] = "home";
            $page_data['page_title'] = site_phrase('home');
            $current_language = $this->session->userdata("language");
            $page_data['top_10_categories'] = $this->crud_model->get_top_categories(12, 'sub_category_id');
            $page_data['latest_blogs'] = $this->crud_model->get_latest_blogs(4, $current_language)->result_array();
            $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
        } catch (\Throwable$th) {
            die("Home" . $th->getMessage());
        }
    }

    public function shopping_cart()
    {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }
        $page_data['page_name'] = "shopping_cart";
        $page_data['page_title'] = site_phrase('shopping_cart');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function courses()
    {
        if (!$this->session->userdata('layout')) {
            $this->session->set_userdata('layout', 'list');
        }
        $layout = $this->session->userdata('layout');
        $selected_category_id = "all";
        $selected_price = "all";
        $selected_level = "all";
        $selected_language = "all";
        $selected_rating = "all";
        // Get the category ids
        if (isset($_GET['category']) && !empty($_GET['category'] && $_GET['category'] != "all")) {
            $selected_category_id = $this->crud_model->get_category_id($_GET['category']);
        }

        // Get the selected price
        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $selected_price = $_GET['price'];
        }

        // Get the selected level
        if (isset($_GET['level']) && !empty($_GET['level'])) {
            $selected_level = $_GET['level'];
        }

        // Get the selected language
        $current_session = $this->session->get_userdata();
        if (isset($_GET['language']) && !empty($_GET['language']) && $_GET['language'] != "all") {
            $selected_language = $_GET['language'];
            $this->session->set_userdata('language', $selected_language);
        } else if (!empty($current_session['language'])) {
            $selected_language = $current_session['language'];
        }

        // Get the selected language
        if (isset($_GET['language']) && !empty($_GET['language'])) {
            $selected_language = $_GET['language'];
        }

        // Get the selected rating
        if (isset($_GET['rating']) && !empty($_GET['rating'])) {
            $selected_rating = $_GET['rating'];
        }

        if ($selected_category_id == "all" && $selected_price == "all" && $selected_level == 'all' && $selected_rating == 'all' || !empty($this->uri->segment(3))) {

            if (!addon_status('scorm_course')) {
                $this->db->where('course_type', 'general');
            }
            $this->db->where('status', 'active');
            if (isset($_GET['language']) && !empty($_GET['language']) && $_GET['language'] == "all") {
                $total_rows = $this->db->get('course')->num_rows();
            } else {
                $total_rows = $this->db->where(['language' => $selected_language])->get('course')->num_rows();
            }

            $config = array();
            $config = pagintaion($total_rows, 6);
            $config['base_url'] = site_url('home/courses/');
            $this->pagination->initialize($config);
            if (!addon_status('scorm_course')) {
                $this->db->where('course_type', 'general');
            }
            $this->db->where('status', 'active');

            if (isset($_GET['language']) && !empty($_GET['language']) && $_GET['language'] == "all") {
                $page_data['courses'] = $this->db->get('course', $config['per_page'], $this->uri->segment(3))->result_array();
            } else {
                $page_data['courses'] = $this->db->where(['language' => $selected_language])->get('course', $config['per_page'], $this->uri->segment(3))->result_array();
            }

            $page_data['total_result'] = $total_rows;

        } else {
            $courses = $this->crud_model->filter_course($selected_category_id, $selected_price, $selected_level, $selected_language, $selected_rating);

            $page_data['courses'] = $courses;
            $page_data['total_result'] = count($courses);
        }

        $page_data['page_name'] = "courses_page";
        $page_data['page_title'] = site_phrase('courses');
        $page_data['layout'] = $layout;
        $page_data['selected_category_id'] = $selected_category_id;
        $page_data['selected_price'] = $selected_price;
        $page_data['selected_level'] = $selected_level;
        $page_data['selected_language'] = $selected_language;
        $page_data['selected_rating'] = $selected_rating;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function set_layout_to_session()
    {
        $layout = $this->input->post('layout');
        $this->session->set_userdata('layout', $layout);
    }

    public function course($slug = "", $course_id = "")
    {
        $this->access_denied_courses($course_id);
        $page_data['course_id'] = $course_id;
        $page_data['page_name'] = "course_page";
        $page_data['page_title'] = site_phrase('course');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function instructor_page($instructor_id = "")
    {
        if (!empty($instructor_id)) {
            $page_data['page_name'] = "instructor_page";
            $page_data['page_title'] = site_phrase('instructor_page');
            $page_data['instructor_details'] = $this->user_model->get_all_user($instructor_id)->row_array();
            if ($page_data['instructor_details']) {
                $page_data['instructor_id'] = $instructor_id;
                $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
            } else {
                return redirect(base_url('home'));
            }
        } else {
            return redirect(base_url('home'));
        }
    }

    public function my_courses()
    {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }

        $page_data['page_name'] = "my_courses";
        $page_data['page_title'] = site_phrase("my_courses");
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function my_messages($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }
        if ($param1 == 'read_message') {
            $page_data['message_thread_code'] = $param2;
        } elseif ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', site_phrase('message_sent'));
            redirect(site_url('home/my_messages/read_message/' . $message_thread_code), 'refresh');
        } elseif ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2); //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', site_phrase('message_sent'));
            redirect(site_url('home/my_messages/read_message/' . $param2), 'refresh');
        }
        $page_data['page_name'] = "my_messages";
        $page_data['page_title'] = site_phrase('my_messages');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function my_notifications()
    {
        $page_data['page_name'] = "my_notifications";
        $page_data['page_title'] = site_phrase('my_notifications');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function my_wishlist()
    {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }
        $my_courses = $this->crud_model->get_courses_by_wishlists();
        $page_data['my_courses'] = $my_courses;
        $page_data['page_name'] = "my_wishlist";
        $page_data['page_title'] = site_phrase('my_wishlist');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function purchase_history()
    {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }

        $total_rows = $this->crud_model->purchase_history($this->session->userdata('user_id'))->num_rows();
        $config = array();
        $config = pagintaion($total_rows, 10);
        $config['base_url'] = site_url('home/purchase_history');
        $this->pagination->initialize($config);
        $page_data['per_page'] = $config['per_page'];

        if (addon_status('offline_payment') == 1):
            $this->load->model('addons/offline_payment_model');
            $page_data['pending_offline_payment_history'] = $this->offline_payment_model->pending_offline_payment($this->session->userdata('user_id'))->result_array();
        endif;

        $page_data['page_name'] = "purchase_history";
        $page_data['page_title'] = site_phrase('purchase_history');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function profile($param1 = "")
    {
        if ($this->session->userdata('user_login') != true) {
            redirect(site_url('home'), 'refresh');
        }

        if ($param1 == 'user_profile') {
            $page_data['page_name'] = "user_profile";
            $page_data['page_title'] = site_phrase('user_profile');
        } elseif ($param1 == 'user_credentials') {
            $page_data['page_name'] = "user_credentials";
            $page_data['page_title'] = site_phrase('credentials');
        } elseif ($param1 == 'user_photo') {
            $page_data['page_name'] = "update_user_photo";
            $page_data['page_title'] = site_phrase('update_user_photo');
        }
        $page_data['user_details'] = $this->user_model->get_user($this->session->userdata('user_id'));
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function update_profile($param1 = "")
    {
        if ($param1 == 'update_basics') {
            $this->user_model->edit_user($this->session->userdata('user_id'));
            redirect(site_url('home/profile/user_profile'), 'refresh');
        } elseif ($param1 == "update_credentials") {
            $this->user_model->update_account_settings($this->session->userdata('user_id'));
            redirect(site_url('home/profile/user_credentials'), 'refresh');
        } elseif ($param1 == "update_photo") {
            if (isset($_FILES['user_image']) && $_FILES['user_image']['name'] != "") {
                unlink('uploads/user_image/' . $this->db->get_where('users', array('id' => $this->session->userdata('user_id')))->row('image') . '.jpg');
                $data['image'] = md5(rand(10000, 10000000));
                $this->db->where('id', $this->session->userdata('user_id'));
                $this->db->update('users', $data);
                $this->user_model->upload_user_image($data['image']);
            }
            $this->session->set_flashdata('flash_message', site_phrase('updated_successfully'));
            redirect(site_url('home/profile/user_photo'), 'refresh');
        }
    }

    public function handleWishList($return_number = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            echo false;
        } else {
            if (isset($_POST['course_id'])) {
                $course_id = $this->input->post('course_id');
                $this->crud_model->handleWishList($course_id);
            }
            if ($return_number == 'true') {
                echo sizeof($this->crud_model->getWishLists());
            } else {
                $this->load->view('frontend/' . get_frontend_settings('theme') . '/wishlist_items');
            }
        }
    }
    public function handleCartItems($return_number = "")
    {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }

        $course_id = $this->input->post('course_id');
        $previous_cart_items = $this->session->userdata('cart_items');
        if (in_array($course_id, $previous_cart_items)) {
            $key = array_search($course_id, $previous_cart_items);
            unset($previous_cart_items[$key]);
        } else {
            array_push($previous_cart_items, $course_id);
        }

        $this->session->set_userdata('cart_items', $previous_cart_items);
        if ($return_number == 'true') {
            echo sizeof($previous_cart_items);
        } else {
            $this->load->view('frontend/' . get_frontend_settings('theme') . '/cart_items');
        }
    }

    public function handleCartItemForBuyNowButton()
    {
        if (!$this->session->userdata('cart_items')) {
            $this->session->set_userdata('cart_items', array());
        }

        if (!empty($this->input->post('course_id'))) {
            $course_id = $this->input->post('course_id');
            $previous_cart_items = $this->session->userdata('cart_items');
            if (!in_array($course_id, $previous_cart_items)) {
                array_push($previous_cart_items, $course_id);
            }
            $this->session->set_userdata('cart_items', $previous_cart_items);
            $this->load->view('frontend/' . get_frontend_settings('theme') . '/cart_items');
        } else {
            return redirect(base_url('home'));
        }
    }

    public function refreshWishList()
    {
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/wishlist_items');
    }

    public function refreshShoppingCart()
    {
        $page_data['coupon_code'] = $this->input->post('couponCode');
        $page_data['discount_percentage'] = 0;
        $page_data['coupon_id'] = 0;
        $page_data['coupon_status'] = 0;
        $page_data['status_msg'] = "";

        if (!empty($page_data['coupon_code'])) {
            $coupon = $this->crud_model->get_single_coupon($page_data['coupon_code']);
            if ($coupon) {
                if (strtotime(date("d-M-Y")) <= $coupon['expiry_date']) {
                    if ($coupon) {
                        $page_data['discount_percentage'] = $coupon['discount_percentage'];
                        $page_data['coupon_id'] = $coupon['id'];
                        $page_data['coupon_status'] = 1;
                        $page_data['status_msg'] = "Coupon Applied.";
                    }
                } else {
                    $page_data['coupon_status'] = 2;
                    $page_data['status_msg'] = "Coupon Expired.";
                }
            } else {
                $page_data['coupon_status'] = 2;
                $page_data['status_msg'] = "Invalid Coupon.";
            }

        }

        $this->load->view('frontend/' . get_frontend_settings('theme') . '/shopping_cart_inner_view', $page_data);
    }

    //this is only for elegant
    public function refreshShoppingCartItem()
    {
        $page_data['coupon_code'] = $this->input->post('couponCode');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/cart_items', $page_data);
    }

    public function isLoggedIn()
    {
        if ($this->session->userdata('user_login') == 1) {
            echo true;
        } else {
            if (isset($_GET['url_history']) && !empty($_GET['url_history'])) {
                $this->session->set_userdata('url_history', base64_decode($_GET['url_history']));
            }
            echo false;
        }
    }

    //choose payment gateway
    public function payment()
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('login', 'refresh');
        }

        $page_data['total_price_of_checking_out'] = $this->session->userdata('total_price_of_checking_out');
        $page_data['discount_price'] = $this->session->userdata('discount_price');
        $page_data['coupon_id'] = $this->session->userdata('coupon_id');
        $page_data['page_title'] = site_phrase("payment_gateway");
        $this->load->view('payment/index', $page_data);
    }

    // SHOW PAYPAL CHECKOUT PAGE
    public function paypal_checkout($payment_request = "only_for_mobile")
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'true') {
            redirect('home', 'refresh');
        }

        //checking price
        if ($this->session->userdata('total_price_of_checking_out') == $this->input->post('total_price_of_checking_out')):
            $total_price_of_checking_out = $this->input->post('total_price_of_checking_out');
        else:
            $total_price_of_checking_out = $this->session->userdata('total_price_of_checking_out');
        endif;
        $page_data['payment_request'] = $payment_request;
        $page_data['user_details'] = $this->user_model->get_user($this->session->userdata('user_id'));
        $page_data['amount_to_pay'] = $total_price_of_checking_out;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/paypal_checkout', $page_data);
    }

    // PAYPAL CHECKOUT ACTIONS
    public function paypal_payment($user_id = "", $amount_paid = "", $paymentID = "", $paymentToken = "", $payerID = "", $payment_request_mobile = "")
    {
        $paypal_keys = get_settings('paypal');
        $paypal = json_decode($paypal_keys);

        if ($paypal[0]->mode == 'sandbox') {
            $paypalClientID = $paypal[0]->sandbox_client_id;
            $paypalSecret = $paypal[0]->sandbox_secret_key;
        } else {
            $paypalClientID = $paypal[0]->production_client_id;
            $paypalSecret = $paypal[0]->production_secret_key;
        }

        //THIS IS HOW I CHECKED THE PAYPAL PAYMENT STATUS
        $status = $this->payment_model->paypal_payment($paymentID, $paymentToken, $payerID, $paypalClientID, $paypalSecret);
        if (!$status) {
            $this->session->set_flashdata('error_message', site_phrase('an_error_occurred_during_payment'));
            redirect('home/shopping_cart', 'refresh');
        }
        $this->crud_model->enrol_student($user_id);
        $this->crud_model->course_purchase($user_id, 'paypal', $amount_paid);
        $this->email_model->course_purchase_notification($user_id, 'paypal', $amount_paid);
        $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
        if ($payment_request_mobile == 'true'):
            $course_id = $this->session->userdata('cart_items');
            redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
        else:
            $this->session->set_userdata('cart_items', array());
            redirect('home/my_courses', 'refresh');
        endif;
    }

    // SHOW STRIPE CHECKOUT PAGE
    public function stripe_checkout($payment_request = "only_for_mobile")
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'true') {
            redirect('home', 'refresh');
        }

        //checking price
        $total_price_of_checking_out = $this->session->userdata('total_price_of_checking_out');

        $page_data['discount_price'] = $this->session->userdata('discount_price');
        $page_data['coupon_id'] = $this->session->userdata('coupon_id');

        $page_data['payment_request'] = $payment_request;
        $page_data['user_details'] = $this->user_model->get_user($this->session->userdata('user_id'));
        $page_data['amount_to_pay'] = $total_price_of_checking_out;

        $this->load->view('payment/stripe/stripe_checkout', $page_data);
    }

    // STRIPE CHECKOUT ACTIONS
    public function stripe_payment($user_id = "", $payment_request_mobile = "", $session_id = "")
    {
        //THIS IS HOW I CHECKED THE STRIPE PAYMENT STATUS
        $response = $this->payment_model->stripe_payment($user_id, $session_id);

        if ($response['payment_status'] === 'succeeded') {
            // STUDENT ENROLMENT OPERATIONS AFTER A SUCCESSFUL PAYMENT
            $check_duplicate = $this->crud_model->check_duplicate_payment_for_stripe($response['transaction_id'], $session_id);

            if ($check_duplicate == false):

                $this->crud_model->course_purchase($user_id, 'stripe', $response['paid_amount'], $response['transaction_id'], $session_id);
                $this->crud_model->enrol_student($user_id);
                $this->email_model->course_purchase_notification($user_id, 'stripe', $response['paid_amount']);
            else:
                //duplicate payment
                $this->session->set_flashdata('error_message', site_phrase('session_time_out'));
                redirect('home/shopping_cart', 'refresh');
            endif;

            if ($payment_request_mobile == 'true'):
                $course_id = $this->session->userdata('cart_items');
                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
            else:
                $this->session->set_userdata('cart_items', array());
                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                redirect('home/my_courses', 'refresh');
            endif;
        } else {
            if ($payment_request_mobile == 'true'):
                $course_id = $this->session->userdata('cart_items');
                $this->session->set_flashdata('flash_message', $response['status_msg']);
                redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/error', 'refresh');
            else:
                $this->session->set_flashdata('error_message', $response['status_msg']);
                redirect('home/shopping_cart', 'refresh');
            endif;
        }
    }

    public function razorpay_checkout($payment_request = "only_for_mobile")
    {
        if ($this->session->userdata('user_login') != 1 && $payment_request != 'true') {
            redirect('home', 'refresh');
        }

        $total_price_of_checking_out = $this->session->userdata('total_price_of_checking_out');
        $discount_price = $this->session->userdata('discount_price');
        $coupon_id = $this->session->userdata('coupon_id');

        $page_data['payment_request'] = $payment_request;
        $page_data['user_details'] = $this->user_model->get_user($this->session->userdata('user_id'));
        $page_data['amount_to_pay'] = $total_price_of_checking_out;
        $page_data['discount_price'] = $discount_price;
        $page_data['coupon_id'] = $coupon_id;

        $this->load->view('payment/razorpay/razorpay_checkout', $page_data);
    }

    // PAYPAL CHECKOUT ACTIONS
    public function razorpay_payment($payment_request_mobile = "")
    {

        $response = array();

        if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['amount']) && !empty($_GET['amount'])) {

            $user_id = $_GET['user_id'];
            $amount = $_GET['amount'];
            $razorpay_order_id = $_GET['razorpay_order_id'];
            $payment_id = $_GET['payment_id'];
            $signature = $_GET['signature'];

            //THIS IS HOW I CHECKED THE PAYPAL PAYMENT STATUS
            $status = $this->payment_model->razorpay_payment($razorpay_order_id, $payment_id, $amount, $signature);

            if ($status == 1) {
                $payment_key['payment_id'] = $payment_id;
                $payment_key['razorpay_order_id'] = $razorpay_order_id;
                $payment_key['signature'] = $signature;
                $payment_key = json_encode($payment_key);

                $this->crud_model->course_purchase($user_id, 'razorpay', $amount, $payment_key);
                $this->crud_model->enrol_student($user_id);
                $this->email_model->course_purchase_notification($user_id, 'razorpay', $amount);
                $this->session->set_flashdata('flash_message', site_phrase('payment_successfully_done'));
                if ($payment_request_mobile == 'true'):
                    $course_id = $this->session->userdata('cart_items');
                    redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/paid', 'refresh');
                else:
                    $this->session->set_userdata('cart_items', array());
                    redirect('home/my_courses', 'refresh');
                endif;
            } else {
                if ($payment_request_mobile == 'true'):
                    $course_id = $this->session->userdata('cart_items');
                    $this->session->set_flashdata('flash_message', $response['status_msg']);
                    redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/error', 'refresh');
                else:
                    $this->session->set_flashdata('error_message', site_phrase('payment_failed') . '! ' . site_phrase('something_is_wrong'));
                    redirect('home/shopping_cart', 'refresh');
                endif;
            }
        } else {
            if ($payment_request_mobile == 'true'):
                $course_id = $this->session->userdata('cart_items');
                $this->session->set_flashdata('flash_message', $response['status_msg']);
                redirect('home/payment_success_mobile/' . $course_id[0] . '/' . $user_id . '/error', 'refresh');
            else:
                $this->session->set_flashdata('error_message', site_phrase('payment_failed') . '! ' . site_phrase('something_is_wrong'));
                redirect('home/shopping_cart', 'refresh');
            endif;
        }
    }

    public function lesson($slug = "", $course_id = "", $lesson_id = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            if ($this->session->userdata('admin_login') != 1) {
                redirect('home', 'refresh');
            }
        }

        $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();

        //this function saved current lesson id and return previous lesson id if $lesson_id param is empty
        $lesson_id = $this->crud_model->update_watch_history($course_id, $lesson_id);

        if ($course_details['course_type'] == 'general') {
            $sections = $this->crud_model->get_section('course', $course_id);
            if ($sections->num_rows() > 0) {
                $page_data['sections'] = $sections->result_array();
                if ($lesson_id == "") {
                    $default_section = $sections->row_array();
                    $page_data['section_id'] = $default_section['id'];
                    $lessons = $this->crud_model->get_lessons('section', $default_section['id']);
                    if ($lessons->num_rows() > 0) {
                        $default_lesson = $lessons->row_array();
                        $lesson_id = $default_lesson['id'];
                        $page_data['lesson_id'] = $default_lesson['id'];
                    }
                } else {
                    $page_data['lesson_id'] = $lesson_id;
                    $section_id = $this->db->get_where('lesson', array('id' => $lesson_id))->row()->section_id;
                    $page_data['section_id'] = $section_id;
                }
            } else {
                $page_data['sections'] = array();
            }
        } else if ($course_details['course_type'] == 'scorm') {
            $this->load->model('addons/scorm_model');
            $scorm_course_data = $this->scorm_model->get_scorm_curriculum_by_course_id($course_id);
            $page_data['scorm_curriculum'] = $scorm_course_data->row_array();
        }

        // Check if the lesson contained course is purchased by the user
        if (isset($page_data['lesson_id']) && $page_data['lesson_id'] > 0 && $course_details['course_type'] == 'general') {
            if ($this->session->userdata('role_id') != 1 && $course_details['user_id'] != $this->session->userdata('user_id')) {
                if (!is_purchased($course_id)) {
                    redirect(site_url('home/course/' . slugify($course_details['title']) . '/' . $course_details['id']), 'refresh');
                }
            }
        } else if ($course_details['course_type'] == 'scorm' && $scorm_course_data->num_rows() > 0) {
            if ($this->session->userdata('role_id') != 1 && $course_details['user_id'] != $this->session->userdata('user_id')) {
                if (!is_purchased($course_id)) {
                    redirect(site_url('home/course/' . slugify($course_details['title']) . '/' . $course_details['id']), 'refresh');
                }
            }
        } else {
            if (!is_purchased($course_id)) {
                redirect(site_url('home/course/' . slugify($course_details['title']) . '/' . $course_details['id']), 'refresh');
            }
        }

        $page_data['course_details'] = $course_details;
        $page_data['course_id'] = $course_id;
        $page_data['page_name'] = 'lessons';
        $page_data['page_title'] = $course_details['title'];
        $this->load->view('lessons/index', $page_data);
    }

    public function my_courses_by_category()
    {
        if (!empty($this->input->post('category_id'))) {
            $category_id = $this->input->post('category_id');
            $course_details = $this->crud_model->get_my_courses_by_category_id($category_id)->result_array();
            $page_data['my_courses'] = $course_details;
            $this->load->view('frontend/' . get_frontend_settings('theme') . '/reload_my_courses', $page_data);
        } else {
            return redirect(base_url('home'));
        }
    }

    public function search($search_string = "")
    {
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $search_string = $_GET['query'];
            $page_data['courses'] = $this->crud_model->get_courses_by_search_string($search_string)->result_array();
            $page_data['total_result'] = count($page_data['courses']);
        } else {
            $this->session->set_flashdata('error_message', site_phrase('no_search_value_found'));
            redirect(site_url(), 'refresh');
        }

        if (!$this->session->userdata('layout')) {
            $this->session->set_userdata('layout', 'list');
        }

        $page_data['layout'] = $this->session->userdata('layout');
        $page_data['page_name'] = 'courses_page';
        $page_data['search_string'] = $search_string;
        $page_data['page_title'] = site_phrase('search_results');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }
    public function my_courses_by_search_string()
    {
        $search_string = $this->input->post('search_string');
        $course_details = $this->crud_model->get_my_courses_by_search_string($search_string)->result_array();
        $page_data['my_courses'] = $course_details;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/reload_my_courses', $page_data);
    }

    public function get_my_wishlists_by_search_string()
    {
        $search_string = $this->input->post('search_string');
        $course_details = $this->crud_model->get_courses_of_wishlists_by_search_string($search_string);
        $page_data['my_courses'] = $course_details;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/reload_my_wishlists', $page_data);
    }

    public function reload_my_wishlists()
    {
        $my_courses = $this->crud_model->get_courses_by_wishlists();
        $page_data['my_courses'] = $my_courses;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/reload_my_wishlists', $page_data);
    }

    public function get_course_details()
    {
        $course_id = $this->input->post('course_id');
        $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
        echo $course_details['title'];
    }

    public function rate_course()
    {
        $data['review'] = $this->input->post('review');
        $data['ratable_id'] = $this->input->post('course_id');
        $data['ratable_type'] = 'course';
        $data['rating'] = $this->input->post('starRating');
        $data['date_added'] = strtotime(date('D, d-M-Y'));
        $data['user_id'] = $this->session->userdata('user_id');
        $this->crud_model->rate($data);
    }

    public function about_us()
    {
        $page_data['page_name'] = 'about_us';
        $page_data['page_title'] = site_phrase('about_us');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function terms_and_condition()
    {
        $page_data['page_name'] = 'terms_and_condition';
        $page_data['page_title'] = site_phrase('terms_and_condition');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function refund_policy()
    {
        $page_data['page_name'] = 'refund_policy';
        $page_data['page_title'] = site_phrase('refund_policy');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function privacy_policy()
    {
        $page_data['page_name'] = 'privacy_policy';
        $page_data['page_title'] = site_phrase('privacy_policy');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }
    public function cookie_policy()
    {
        $page_data['page_name'] = 'cookie_policy';
        $page_data['page_title'] = site_phrase('cookie_policy');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    // Version 1.1
    public function dashboard($param1 = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }

        if ($param1 == "") {
            $page_data['type'] = 'active';
        } else {
            $page_data['type'] = $param1;
        }

        $page_data['page_name'] = 'instructor_dashboard';
        $page_data['page_title'] = site_phrase('instructor_dashboard');
        $page_data['user_id'] = $this->session->userdata('user_id');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function create_course()
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }

        $page_data['page_name'] = 'create_course';
        $page_data['page_title'] = site_phrase('create_course');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function edit_course($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }

        if ($param2 == "") {
            $page_data['type'] = 'edit_course';
        } else {
            $page_data['type'] = $param2;
        }
        $page_data['page_name'] = 'manage_course_details';
        $page_data['course_id'] = $param1;
        $page_data['page_title'] = site_phrase('edit_course');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function course_action($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }

        if ($param1 == 'create') {
            if (isset($_POST['create_course'])) {
                $this->crud_model->add_course();
                redirect(site_url('home/create_course'), 'refresh');
            } else {
                $this->crud_model->add_course('save_to_draft');
                redirect(site_url('home/create_course'), 'refresh');
            }
        } elseif ($param1 == 'edit') {
            if (isset($_POST['publish'])) {
                $this->crud_model->update_course($param2, 'publish');
                redirect(site_url('home/dashboard'), 'refresh');
            } else {
                $this->crud_model->update_course($param2, 'save_to_draft');
                redirect(site_url('home/dashboard'), 'refresh');
            }
        }
    }

    public function sections($action = "", $course_id = "", $section_id = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }

        if ($action == "add") {
            $this->crud_model->add_section($course_id);
        } elseif ($action == "edit") {
            $this->crud_model->edit_section($section_id);
        } elseif ($action == "delete") {
            $this->crud_model->delete_section($course_id, $section_id);
            $this->session->set_flashdata('flash_message', site_phrase('section_deleted'));
            redirect(site_url("home/edit_course/$course_id/manage_section"), 'refresh');
        } elseif ($action == "serialize_section") {
            $container = array();
            $serialization = json_decode($this->input->post('updatedSerialization'));
            foreach ($serialization as $key) {
                array_push($container, $key->id);
            }
            $json = json_encode($container);
            $this->crud_model->serialize_section($course_id, $json);
        }
        $page_data['course_id'] = $course_id;
        $page_data['course_details'] = $this->crud_model->get_course_by_id($course_id)->row_array();
        return $this->load->view('frontend/' . get_frontend_settings('theme') . '/reload_section', $page_data);
    }

    public function manage_lessons($action = "", $course_id = "", $lesson_id = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }
        if ($action == 'add') {
            $this->crud_model->add_lesson();
            $this->session->set_flashdata('flash_message', site_phrase('lesson_added'));
        } elseif ($action == 'edit') {
            $this->crud_model->edit_lesson($lesson_id);
            $this->session->set_flashdata('flash_message', site_phrase('lesson_updated'));
        } elseif ($action == 'delete') {
            $this->crud_model->delete_lesson($lesson_id);
            $this->session->set_flashdata('flash_message', site_phrase('lesson_deleted'));
        }
        redirect('home/edit_course/' . $course_id . '/manage_lesson');
    }

    public function lesson_editing_form($lesson_id = "", $course_id = "")
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }
        $page_data['type'] = 'manage_lesson';
        $page_data['course_id'] = $course_id;
        $page_data['lesson_id'] = $lesson_id;
        $page_data['page_name'] = 'lesson_edit';
        $page_data['page_title'] = site_phrase('update_lesson');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function download($filename = "")
    {
        $tmp = explode('.', $filename);
        $fileExtension = strtolower(end($tmp));
        $yourFile = base_url() . 'uploads/lesson_files/' . $filename;
        $file = @fopen($yourFile, "rb");

        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($yourFile));
        while (!feof($file)) {
            print(@fread($file, 1024 * 8));
            ob_flush();
            flush();
        }
    }

    // Version 1.3 codes
    public function get_enrolled_to_free_course($course_id)
    {
        if ($this->session->userdata('user_login') == 1) {
            $this->crud_model->enrol_to_free_course($course_id, $this->session->userdata('user_id'));
            redirect(site_url('home/my_courses'), 'refresh');
        } else {
            redirect(site_url('login'), 'refresh');
        }
    }

    // Version 1.4 codes
    public function login()
    {
        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        } elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }
        $page_data['page_name'] = 'login';
        $page_data['page_title'] = site_phrase('login');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function sign_up()
    {
        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        } elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }
        $page_data['page_name'] = 'sign_up';
        $page_data['page_title'] = site_phrase('sign_up');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function forgot_password()
    {
        if ($this->session->userdata('admin_login')) {
            redirect(site_url('admin'), 'refresh');
        } elseif ($this->session->userdata('user_login')) {
            redirect(site_url('user'), 'refresh');
        }
        $page_data['page_name'] = 'forgot_password';
        $page_data['page_title'] = site_phrase('forgot_password');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function submit_quiz($from = "")
    {
        $submitted_quiz_info = array();
        $container = array();
        $course_id = $this->input->post('course_id');
        $quiz_id = $this->input->post('lesson_id');
        $quiz_questions = $this->crud_model->get_quiz_questions($quiz_id)->result_array();
        $total_correct_answers = 0;
        foreach ($quiz_questions as $quiz_question) {
            $submitted_answer_status = 0;
            $correct_answers = json_decode($quiz_question['correct_answers']);
            $submitted_answers = array();
            foreach ($this->input->post($quiz_question['id']) as $each_submission) {
                if (isset($each_submission)) {
                    array_push($submitted_answers, $each_submission);
                }
            }
            sort($correct_answers);
            sort($submitted_answers);
            if ($correct_answers == $submitted_answers) {
                $submitted_answer_status = 1;
                $total_correct_answers++;
            }
            $container = array(
                "question_id" => $quiz_question['id'],
                'submitted_answer_status' => $submitted_answer_status,
                "submitted_answers" => json_encode($submitted_answers),
                "correct_answers" => json_encode($correct_answers),
            );
            array_push($submitted_quiz_info, $container);
        }

        $this->save_quiz_result($course_id, $quiz_id, $total_correct_answers);

        $page_data['submitted_quiz_info'] = $submitted_quiz_info;
        $page_data['total_correct_answers'] = $total_correct_answers;
        $page_data['total_questions'] = count($quiz_questions);
        $page_data['course_id'] = $course_id;
        $page_data['quiz_id'] = $quiz_id;
        if ($from == 'mobile') {
            $this->load->view('mobile/quiz_result', $page_data);
        } else {
            $this->load->view('lessons/quiz_result', $page_data);
        }
    }

    public function save_quiz_result($course_id = "", $quiz_id = "", $obtained_marks = '')
    {
        $student_id = $this->session->userdata('user_id');
        $this->db->where('course_id', $course_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get('watch_histories');
        if ($query->num_rows() > 0) {
            $quiz_result = array();
            $previous_result = json_decode($query->row('quiz_result'), 1);
            if (is_array($previous_result) && count($previous_result) > 0) {
                $quiz_result = $previous_result;
            }
            $quiz_result[$quiz_id] = $obtained_marks;

            $data['date_updated'] = time();
            $data['quiz_result'] = json_encode($quiz_result);

            $this->db->where('course_id', $course_id);
            $this->db->where('student_id', $student_id);
            $this->db->update('watch_histories', $data);
        } else {
            $data['course_id'] = $course_id;
            $data['student_id'] = $student_id;
            $data['watching_lesson_id'] = $quiz_id;
            $data['date_added'] = time();
            $data['quiz_result'] = json_encode(array($quiz_id => $obtained_marks));
            $this->db->insert('watch_histories', $data);
        }
    }

    private function access_denied_courses($course_id)
    {
        $course_details = $this->crud_model->get_course_by_id($course_id)->row_array();
        if ($course_details['status'] == 'draft' && $course_details['user_id'] != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error_message', site_phrase('you_do_not_have_permission_to_access_this_course'));
            redirect(site_url('home'), 'refresh');
        } elseif ($course_details['status'] == 'pending') {
            if ($course_details['user_id'] != $this->session->userdata('user_id') && $this->session->userdata('role_id') != 1) {
                $this->session->set_flashdata('error_message', site_phrase('you_do_not_have_permission_to_access_this_course'));
                redirect(site_url('home'), 'refresh');
            }
        } else if (!$course_details) {
            redirect(site_url('home'), 'refresh');
        }
    }

    public function invoice($purchase_history_id = '')
    {
        if ($this->session->userdata('user_login') != 1) {
            redirect('home', 'refresh');
        }
        $purchase_history = $this->crud_model->get_payment_details_by_id($purchase_history_id);
        if ($purchase_history['user_id'] != $this->session->userdata('user_id')) {
            redirect('home', 'refresh');
        }
        $page_data['payment_info'] = $purchase_history;
        $page_data['page_name'] = 'invoice';
        $page_data['page_title'] = 'invoice';
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    /** COURSE COMPARE STARTS */
    public function compare()
    {
        $course_id_1 = (isset($_GET['course-id-1']) && !empty($_GET['course-id-1'])) ? $_GET['course-id-1'] : null;
        $course_id_2 = (isset($_GET['course-id-2']) && !empty($_GET['course-id-2'])) ? $_GET['course-id-2'] : null;
        $course_id_3 = (isset($_GET['course-id-3']) && !empty($_GET['course-id-3'])) ? $_GET['course-id-3'] : null;

        $page_data['page_name'] = 'compare';
        $page_data['page_title'] = site_phrase('course_compare');
        $page_data['courses'] = $this->crud_model->get_courses()->result_array();
        $page_data['course_1_details'] = $course_id_1 ? $this->crud_model->get_course_by_id($course_id_1)->row_array() : array();
        $page_data['course_2_details'] = $course_id_2 ? $this->crud_model->get_course_by_id($course_id_2)->row_array() : array();
        $page_data['course_3_details'] = $course_id_3 ? $this->crud_model->get_course_by_id($course_id_3)->row_array() : array();
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }
    /** COURSE COMPARE ENDS */

    public function page_not_found()
    {
        $page_data['page_name'] = '404';
        $page_data['page_title'] = site_phrase('404_page_not_found');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    // AJAX CALL FUNCTION FOR CHECKING COURSE PROGRESS
    public function check_course_progress($course_id)
    {
        echo course_progress($course_id);
    }

    // This is the function for rendering quiz web view for mobile
    public function quiz_mobile_web_view($lesson_id = "")
    {
        $data['lesson_details'] = $this->crud_model->get_lessons('lesson', $lesson_id)->row_array();
        $data['page_name'] = 'quiz';
        $this->load->view('mobile/index', $data);
    }

    // CHECK CUSTOM SESSION DATA
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

    // SETTING FRONTEND LANGUAGE
    public function site_language()
    {
        // $selected_language = $this->input->post('language');
        // $this->session->set_userdata('language', $selected_language);
        // echo true;

        $selected_language = $this->input->post('language');
        $this->session->set_userdata('language', $selected_language);
        $language = $this->crud_model->get_lang_sort($this->input->post('language'))->row_array();
        if ($language) {
            echo $language['lang_sort'];
        }
    }

    //FOR MOBILE
    public function course_purchase($auth_token = '', $course_id = '')
    {
        $this->load->model('jwt_model');
        if (empty($auth_token) || $auth_token == "null") {
            $page_data['cart_item'] = $course_id;
            $page_data['user_id'] = '';
            $page_data['is_login_now'] = 0;
            $page_data['enroll_type'] = null;
            $page_data['page_name'] = 'shopping_cart';
            $this->load->view('mobile/index', $page_data);
        } else {

            $logged_in_user_details = json_decode($this->jwt_model->token_data_get($auth_token), true);

            if ($logged_in_user_details['user_id'] > 0) {

                $credential = array('id' => $logged_in_user_details['user_id'], 'status' => 1, 'role_id' => 2);
                $query = $this->db->get_where('users', $credential);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $page_data['cart_item'] = $course_id;
                    $page_data['user_id'] = $row->id;
                    $page_data['is_login_now'] = 1;
                    $page_data['enroll_type'] = null;
                    $page_data['page_name'] = 'shopping_cart';

                    $cart_item = array($course_id);
                    $this->session->set_userdata('cart_items', $cart_item);
                    $this->session->set_userdata('user_login', '1');
                    $this->session->set_userdata('user_id', $row->id);
                    $this->session->set_userdata('role_id', $row->role_id);
                    $this->session->set_userdata('role', get_user_role('user_role', $row->id));
                    $this->session->set_userdata('name', $row->first_name . ' ' . $row->last_name);
                    $this->load->view('mobile/index', $page_data);
                }
            }
        }
    }

    //FOR MOBILE
    public function get_enrolled_to_free_course_mobile($course_id = "", $user_id = "", $get_request = "")
    {
        if ($get_request == "true") {
            $this->crud_model->enrol_to_free_course_mobile($course_id, $user_id);
        }
    }

    //FOR MOBILE
    public function payment_success_mobile($course_id = "", $user_id = "", $enroll_type = "")
    {
        if ($course_id > 0 && $user_id > 0):
            $page_data['cart_item'] = $course_id;
            $page_data['user_id'] = $user_id;
            $page_data['is_login_now'] = 1;
            $page_data['enroll_type'] = $enroll_type;
            $page_data['page_name'] = 'shopping_cart';

            $this->session->unset_userdata('user_id');
            $this->session->unset_userdata('role_id');
            $this->session->unset_userdata('role');
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('user_login');
            $this->session->unset_userdata('cart_items');

            $this->load->view('mobile/index', $page_data);
        endif;
    }

    //FOR MOBILE
    public function payment_gateway_mobile($course_id = "", $user_id = "")
    {
        if ($course_id > 0 && $user_id > 0):
            $page_data['page_name'] = 'payment_gateway';
            $this->load->view('mobile/index', $page_data);
        endif;
    }

    public function peach_payment_checkout()
    {
        $user_id = $this->session->userdata('user_id');
        $amount = $this->session->userdata('total_price_of_checking_out');
        $discount_price = $this->session->userdata('discount_price');

        $amount = number_format((float) ($amount - $discount_price), 2, '.', '');
        $peach_payment = json_decode(get_settings('peach_payment_keys'));
        if ($peach_payment[0]->testmode == 'on') {
            $entityId = trim($peach_payment[0]->test_entity_id);
            $accessToken = trim($peach_payment[0]->test_access_token);
            $url = "https://test.oppwa.com/v1/checkouts";
        } else {
            $entityId = trim($peach_payment[0]->live_entity_id);
            $accessToken = trim($peach_payment[0]->live_access_token);
            $url = "https://oppwa.com/v1/checkouts";
        }
        $currency = get_settings('peach_payment_currency');
        $data = "entityId=$entityId" .
            "&amount=$amount" .
            "&currency=$currency" .
            "&paymentType=DB";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization:Bearer $accessToken"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $page_data['checkout_info'] = json_decode($responseData);

        $this->load->view('frontend/' . get_frontend_settings('theme') . '/peach_payment_checkout', $page_data);
    }

    // PEACH CHECKOUT ACTIONS
    public function peach_payment()
    {

        if (!empty($_GET['resourcePath'])) {
            $payment_res = $this->verifyPeachPayment($_GET['resourcePath']);
            //echo "<pre>"; print_r($payment_res); exit;
            if ($payment_res['result']['code'] == '000.000.000' || $payment_res['result']['code'] == '000.000.100' || $payment_res['result']['code'] == '000.100.110') {
                $user_id = $this->session->userdata('user_id');
                $amount = $this->session->userdata('total_price_of_checking_out');

                $this->crud_model->course_purchase($user_id, 'peach_payment', $amount);
                $this->crud_model->enrol_student($user_id);
                $this->email_model->course_purchase_notification($user_id, 'peach_payment', $amount);
                $this->session->set_flashdata('flash_message', get_phrase('payment_successfully_done'));
                $this->session->set_userdata('cart_items', array());
                redirect('home/my_courses', 'refresh');
            } else {
                $this->session->set_flashdata('error_message', 'Payment could not be processed. Please try again');
                redirect(site_url('home'), 'refresh');
            }

        } else {
            $this->session->set_userdata('cart_items', array());
            redirect('home', 'refresh');
        }
    }

    private function verifyPeachPayment($resourcePath)
    {
        $peach_payment = json_decode(get_settings('peach_payment_keys'));

        if ($peach_payment[0]->testmode == 'on') {
            $url = "https://test.oppwa.com";
            $entityId = trim($peach_payment[0]->test_entity_id);
            $accessToken = trim($peach_payment[0]->test_access_token);
        } else {
            $url = "https://oppwa.com";
            $entityId = trim($peach_payment[0]->live_entity_id);
            $accessToken = trim($peach_payment[0]->live_access_token);
        }

        $url = $url . '' . $resourcePath;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);

    }

    public function go_course_playing_page($course_id = "")
    {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where('course_id', $course_id);
        $row = $this->db->get('enrol')->num_rows();

        if ($this->session->userdata('role_id') == 1 || $row > 0) {
            echo 1;
        } else {
            echo 0;
        }

    }

    public function preview_free_lesson($lesson_id = "")
    {
        $page_data['lesson'] = $this->crud_model->get_free_lessons($lesson_id);
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/preview_free_lesson', $page_data);
    }

    public function getFeaturedCourse1()
    {

        $output = array("status" => 0, "msg" => "", "data" => "");

        $cart_items = $this->session->userdata('cart_items');

        $current_language = $this->session->userdata("language");
        $category = $this->input->post("category");

        $latest_courses = $this->crud_model->get_latest_10_course($current_language, $category);

        if (count($latest_courses) > 0) {

            $html = '';

            foreach ($latest_courses as $latest_course):

                $lessons = $this->crud_model->get_lessons('course', $latest_course['id']);
                $course_duration = $this->crud_model->get_total_duration_of_lesson_by_course_id($latest_course['id']);

                $html .= '<div class="col-sm-3 course-box-wrap">';

                $html .= '<span class="courses_badge_wishlist text-primary" onclick="handleWishList(this)" id="' . $latest_course['id'] . '"><i class="fa fa-heart"></i></span>
									                            <span class="courses_badge_cart text-primary"  id="' . $latest_course['id'] . '" onclick="handleCartItems(this)"><i class="fa fa-cart-plus"></i></span>';

                $html .= '<a onclick="return check_action(this);" href="' . site_url('home/course/' . rawurlencode(slugify($latest_course['title'])) . '/' . $latest_course['id']) . '" class="has-popover">';

                $html .= '<div class="course-box">';

                $html .= '<div class="course-image">';

                $html .= '<img src="' . base_url() . 'assets/frontend/default/img/course_thumbnail_placeholder.jpg" data-src="' . $this->crud_model->get_course_thumbnail_url($latest_course['id']) . '" alt="' . $latest_course['title'] . '" class="img-fluid lazy"></div>';

                $html .= '<div class="course-details">';

                $html .= '<h5 class="title">' . ellipsis($latest_course['title'], 32) . '</h5>';

                $html .= '<div class="rating">';

                $total_rating = $this->crud_model->get_ratings('course', $latest_course['id'], true)->row()->rating;
                $number_of_ratings = $this->crud_model->get_ratings('course', $latest_course['id'])->num_rows();
                if ($number_of_ratings > 0) {
                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                } else {
                    $average_ceil_rating = 0;
                }

                $html .= '<div class="d-inline-block">';

                $html .= '<span class="badge badge-sub-warning text-11px">' . site_phrase($latest_course['level']) . '</span> &nbsp;&nbsp;';

                $html .= '<span class="badge bg-secondary-start"><i class="fa fa-star star-text"></i> ' . $average_ceil_rating . '</span>';

                $html .= '</div></div>';

                $html .= '<div class="d-flex text-dark">';

                $html .= '<div class="">';

                $html .= '<i class="fas fa-book-reader text-14px"></i>';

                $html .= '<span class="text-muted text-12px" > ' . $lessons->num_rows() . ' ' . site_phrase('lectures') . '</span></div>';

                $html .= '<div class="ms-3">';

                $html .= '<i class="fas fa-clock text-14px"></i>';

                $html .= '<span class="text-muted text-12px"> ' . $course_duration . '</span></div></div>';

                $html .= '<div class="row mt-3">';

                $html .= '<div class="col-6">';

                $html .= '</div>';

                $html .= '<div class="col-6 text-end">';
                $compare_url = "'" . site_url('home/compare?course-1=' . rawurlencode(slugify($latest_course['title'])) . '&&course-id-1=' . $latest_course['id']) . "'";

                $html .= '</div></div>';

                $html .= '<div class="d-block">';

                $html .= '<div class="floating-user d-inline-block">';

                if ($latest_course['multi_instructor']):
                    $instructor_details = $this->user_model->get_multi_instructor_details_with_csv($latest_course['user_id']);
                    $margin = 0;
                    foreach ($instructor_details as $key => $instructor_detail) {

                        $html .= '<img style="margin-left: ' . $margin . 'px;" class="position-absolute" src="' . $this->user_model->get_user_image_url($instructor_detail['id']) . '" width="25px" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $instructor_detail['first_name'] . ' ' . $instructor_detail['last_name'] . '" onclick="return check_action(this,' . site_url('home/instructor_page/' . $instructor_detail['id']) . ');">';

                        $margin = $margin + 17;
                    } else :
                    $user_details = $this->user_model->get_all_user($latest_course['user_id'])->row_array();
                    $html .= '<img src="' . $this->user_model->get_user_image_url($user_details['id']) . '" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $user_details['first_name'] . ' ' . $user_details['last_name'] . '"  onclick="return check_action(this,' . site_url('home/instructor_page/' . $user_details['id']) . ');">';
                endif;
                $html .= '</div></div>';

                $html .= '<div class="d-block">';
                if ($latest_course['is_free_course'] == 1):
                    $html += '<p class="price text-right d-inline-block float-start">' . site_phrase('free') . '</p>';
                else:
                    if ($latest_course['discount_flag'] == 1):
                        $html .= '<p class="price text-right d-inline-block float-start">' . currency($latest_course['discounted_price']) . '  <small>' . currency($latest_course['price']) . '</small></p>';
                    else:
                        $html .= '<p class="price text-right d-inline-block float-start">' . currency($latest_course['price']) . '</p>';
                    endif;
                endif;
                $html .= '<div class="learn-more-btn"><button class="btn btn-primary">Learn More</button></div>';

                $html .= '</div>  </div></div></a>';

                $html .= '<div class="webui-popover-content">';

                $html .= '<div class="course-popover-content">';

                if ($latest_course['last_modified'] == ""):
                    $html .= '<div class="last-updated fw-500">' . site_phrase('last_updated') . ' ' . date('D, d-M-Y', $latest_course['date_added']) . '</div>';
                else:
                    $html .= '<div class="last-updated">' . site_phrase('last_updated') . ' ' . date('D, d-M-Y', $latest_course['last_modified']) . '</div>';
                endif;

                $html .= '<div class="course-title">';

                $html .= '<a class="text-decoration-none text-15px" href="' . site_url('home/course/' . rawurlencode(slugify($latest_course['title'])) . '/' . $latest_course['id']) . '">' . $latest_course['title'] . '</a></div>';

                $html .= '<div class="course-meta">';
                if ($latest_course['course_type'] == 'general'):
                    $html .= '<span class=""><i class="fas fa-play-circle"></i>' . $this->crud_model->get_lessons('course', $latest_course['id'])->num_rows() . ' ' . site_phrase('lessons') . '</span>';

                    $html .= '<span class=""><i class="far fa-clock"></i>' . $course_duration . '</span>';

                elseif ($latest_course['course_type'] == 'scorm'):
                    $html .= '<span class="badge bg-light">' . site_phrase('scorm_course') . '</span>';

                endif;
                $html .= '<span class=""><i class="fas fa-closed-captioning"></i>' . ucfirst($latest_course['language']) . '</span> </div>';

                $html .= '<div class="course-subtitle">' . $latest_course['short_description'] . '</div>';

                $html .= '<div class="what-will-learn">';

                $html .= '<ul>';

                $outcomes = json_decode($latest_course['outcomes']);
                foreach ($outcomes as $outcome):
                    $html .= '<li>' . $outcome . '</li>';
                endforeach;

                $html .= '</ul>';

                $html .= '</div>';

                $html .= '<div class="popover-btns">';

                if (is_purchased($latest_course['id'])):
                    $html .= '<div class="purchased"><a href="' . site_url('home/my_courses') . '">' . site_phrase('already_purchased') . '</a></div>';
                else:
                    if ($latest_course['is_free_course'] == 1):
                        if ($this->session->userdata('user_login') != 1) {
                            $url = "#";
                        } else {
                            $url = site_url('home/get_enrolled_to_free_course/' . $latest_course['id']);
                        }
                        $html .= '<a href="' . $url . '" class="btn blue radius-10" onclick="handleEnrolledButton()">' . site_phrase('get_enrolled') . '</a>';

                    else:
                        $html .= '<button type="button" class="btn red add-to-cart-btn';
                        if (in_array($latest_course['id'], $cart_items)) {$html .= 'addedToCart';}

                        $html .= ' big-cart-button-' . $latest_course['id'] . '" id="' . $latest_course['id'] . '" onclick="handleCartItems(this)">';

                        if (in_array($latest_course['id'], $cart_items)) {
                            $html .= site_phrase('added_to_cart');
                        } else {
                            $html .= site_phrase('add_to_cart');
                        }

                        $html .= '</button>';

                    endif;
                    $html .= '<button type="button" class="wishlist-btn ';
                    if ($this->crud_model->is_added_to_wishlist($latest_course['id'])) {$html .= 'active';}
                    $html .= '" title="Add to wishlist" onclick="handleWishList(this)" id="' . $latest_course['id'] . '"><i class="fas fa-heart"></i></button>';
                endif;

                $html .= '</div></div></div></div>';
            endforeach;
            $output['status'] = 1;
            $output['data'] = $html;
        } else {
            $output['status'] = 0;
        }
        echo json_encode($output);

    }

    public function getFeaturedCourse()
    {

        $output = array("status" => 0, "msg" => "", "data" => array());

        $cart_items = $this->session->userdata('cart_items');

        $current_language = $this->session->userdata("language");
        $category = $this->input->post("category");
        $this->db->cache_on();
        $latest_courses = $this->crud_model->get_latest_10_course($current_language, $category);

        if (count($latest_courses) > 0) {

            foreach ($latest_courses as $latest_course):

                $lessons = $this->crud_model->get_lessons('course', $latest_course['id']);
                $course_duration = $this->crud_model->get_total_duration_of_lesson_by_course_id($latest_course['id']);

                $total_rating = $this->crud_model->get_ratings('course', $latest_course['id'], true)->row()->rating;
                $number_of_ratings = $this->crud_model->get_ratings('course', $latest_course['id'])->num_rows();
                if ($number_of_ratings > 0) {
                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                } else {
                    $average_ceil_rating = 0;
                }

                $instructors['instructor'] = array();

                if ($latest_course['multi_instructor']):
                    $instructor_details = $this->user_model->get_multi_instructor_details_with_csv($latest_course['user_id']);
                    $margin = 0;
                    foreach ($instructor_details as $key => $instructor_detail) {
                        $instructors['instructor'][] = array("InsImage" => $this->user_model->get_user_image_url($instructor_detail['id']), "Margin" => $margin, "InstructorUrl" => site_url('home/instructor_page/' . $instructor_detail['id']), "InstructorName" => $instructor_detail['first_name'] . ' ' . $instructor_detail['last_name']);

                        $margin = $margin + 17;
                    } else :
                    $user_details = $this->user_model->get_all_user($latest_course['user_id'])->row_array();
                    $instructors['instructor'] = array("InsImage" => $this->user_model->get_user_image_url($user_details['id']), "InstructorName" => $user_details['first_name'] . ' ' . $user_details['last_name'], "InstructorUrl" => site_url('home/instructor_page/' . $user_details['id']));

                endif;

                $output['data'][] = array("CourseId" => $latest_course['id'], "SingleCourseUrl" => site_url('home/course/' . rawurlencode(slugify($latest_course['title'])) . '/' . $latest_course['id']), "NoOfLession" => $lessons->num_rows(), "Lectures" => site_phrase('lectures'), "ImageUrl" => $this->crud_model->get_course_thumbnail_url($latest_course['id']), "AltCourseTitle" => $latest_course['title'], "CourseTitle" => ellipsis($latest_course['title'], 32), "AverageRating" => $average_ceil_rating, "Level" => site_phrase($latest_course['level']), "CourseDuration" => $course_duration, "CompareUrl" => site_url('home/compare?course-1=' . rawurlencode(slugify($latest_course['title'])) . '&&course-id-1=' . $latest_course['id']), "IsMultiInstructor" => $latest_course['multi_instructor'], "MultiInstructor" => $instructors, "IsFree" => $latest_course['is_free_course'], "Free" => site_phrase('free'), "DiscountFlag" => $latest_course['discount_flag'], "DiscountPrice" => currency($latest_course['discounted_price']), "Price" => currency($latest_course['price']), "LastModified" => $latest_course['last_modified'], "LastUpdated" => site_phrase('last_updated'), "LastAddedDateTime" => date('D, d-M-Y', $latest_course['date_added']), "LastUpdatedDate" => date('D, d-M-Y', $latest_course['last_modified']), "CourseType" => $latest_course['course_type'], "ScornCourse" => site_phrase('scorm_course'), "Lessons" => site_phrase('lessons'), "Language" => ucfirst($latest_course['language']), "ShortDescription" => $latest_course['short_description'], "Outcomes" => json_decode($latest_course['outcomes']), "IsPurchased" => is_purchased($latest_course['id']), "MyCourseUrl" => site_url('home/my_courses'), "AlreadyPurchased" => site_phrase('already_purchased'), "IsFreeCourse" => $latest_course['is_free_course'], "IsFreeUrl" => $this->session->userdata('user_login') ? "#" : site_url('home/get_enrolled_to_free_course/' . $latest_course['id']), "checkAddToCart" => in_array($latest_course['id'], $cart_items) ? site_phrase('added_to_cart') : site_phrase('add_to_cart'), "addedToCartClass" => in_array($latest_course['id'], $cart_items) ? "addedToCart" : "", "IsAddedToWishlist" => $this->crud_model->is_added_to_wishlist($latest_course['id']) ? "active" : "");

            endforeach;
            $output['status'] = 1;

        } else {
            $output['status'] = 0;
        }
        echo json_encode($output);

    }

    public function test()
    {
        //$this->db->query('ALTER TABLE `blogs` ADD `keywords` TEXT NOT NULL AFTER `title`');
        // $sql="Select * from blogs";
        // $query = $this->db->query($sql);
        // var_dump($query->result_array());
    }

}
