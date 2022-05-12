<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Social_login_modal extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function fb_validate_login($access_token = "", $fb_user_id = "")
    {
        require_once APPPATH . '/libraries/facebook-sdk-5.x/autoload.php'; // change path as needed

        $fb = new \Facebook\Facebook([
            'app_id' => get_settings('fb_app_id'),
            'app_secret' => get_settings('fb_app_secret'),
            'default_graph_version' => 'v2.10',
        ]);

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            $response = $fb->get('/me?fields=id,first_name,last_name,email,link,picture', $access_token);
        } catch (\Facebook\Exceptions\FacebookResponseException$e) {
            // When Graph returns an error
            //   echo 'Graph returned an error: ' . $e->getMessage();
            //   exit;
        } catch (\Facebook\Exceptions\FacebookSDKException$e) {
            // When validation fails or other local issues
            //   echo 'Facebook SDK returned an error: ' . $e->getMessage();
            //   exit;
        }

        $user = $response->getGraphUser();
        // print_r($user);
        // die();
        try {
            if (isset($user['email'])) {
                if (filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                    $email = $user['email'];
                    $fb_user_id = $user['id'];
                    $first_name = $user['first_name'];
                    $last_name = $user['last_name'];

                    $firebaseUser = null;
                    $customClaims = null;
                    try {
                        //code...
                        $firebaseUser = $this->user_model->firebaseAuth->getUserByEmail($email);
                        $customClaims = $firebaseUser ? $firebaseUser->customClaims : null;
                    } catch (\Throwable$th) {
                        //throw $th;
                    }

                    if ($firebaseUser && $firebaseUser->uid && $customClaims['status'] === 1) {
                        $this->session->set_userdata('user_id', $firebaseUser->uid);
                        $this->session->set_userdata('role_id', $customClaims['role_id']);
                        $this->session->set_userdata('role', get_user_role_by_role_id($customClaims['role_id']));
                        $this->session->set_userdata('name', $customClaims['first_name'] . ' ' . $customClaims['last_name']);
                        $this->session->set_userdata('is_instructor', $customClaims['is_instructor']);
                        $this->session->set_userdata('fb_login', 1);
                        if ($customClaims['role_id'] == 1) {
                            $this->session->set_userdata('admin_login', '1');
                        } else if ($customClaims['role_id'] == 2) {
                            $this->session->set_userdata('user_login', '1');
                        }

                        // //stored the fb social data
                        // $social_login_data = json_encode(array('fb_user_id' => $fb_user_id, 'access_token' => $access_token));
                        // $this->db->where('id', $row->id);
                        // $this->db->update('users', array('social_login_data' => $social_login_data));
                    } else {
                        // //stored the fb social data
                        // $social_login_data = json_encode(array('fb_user_id' => $fb_user_id, 'access_token' => $access_token));
                        // $data['social_login_data'] = $social_login_data;

                        $data['first_name'] = $first_name;
                        $data['last_name'] = $last_name;
                        $data['email'] = $email;
                        $data['password'] = sha1(random(30));
                        $data['status'] = 1;

                        $data['wishlist'] = json_encode(array());
                        $data['watch_history'] = json_encode(array());
                        $data['date_added'] = strtotime(date("Y-m-d H:i:s"));
                        $social_links = array(
                            'facebook' => "",
                            'twitter' => "",
                            'linkedin' => "",
                        );
                        $data['social_links'] = json_encode($social_links);
                        $data['role_id'] = 2;

                        // Add paypal keys
                        $paypal_info = array();
                        $paypal['production_client_id'] = "";
                        array_push($paypal_info, $paypal);
                        $data['paypal_keys'] = json_encode($paypal_info);
                        // Add Stripe keys
                        $stripe_info = array();
                        $stripe_keys = array(
                            'public_live_key' => "",
                            'secret_live_key' => "",
                        );
                        array_push($stripe_info, $stripe_keys);
                        $data['stripe_keys'] = json_encode($stripe_info);

                        $validity = !$firebaseUser || !$firebaseUser->uid;
                        if ($validity == true) {

                            // Create new user to firebase
                            $userProperties = [
                                'email' => $data['email'],
                                'phoneNumber' => $data['mobile'],
                                'emailVerified' => true,
                                'password' => $data['password'],
                                'displayName' => $data['first_name'] . " " . $data['last_name'],
                                'disabled' => false,
                            ];
                            try {
                                $createdUser = $this->user_model->firebaseAuth->createUser($userProperties);

                                $this->user_model->firebaseAuth->setCustomUserClaims($createdUser->uid, [
                                    'role_id' => $data['role_id'],
                                    'paypal_keys' => $data['paypal_keys'],
                                    'verification_code' => $data['verification_code'],
                                    'wishlist' => $data['wishlist'],
                                    'watch_history' => $data['watch_history'],
                                    'date_added' => $data['date_added'],
                                    'social_links' => $data['social_links'],
                                    'production_client_id' => $data['production_client_id'],
                                    'paypal_keys' => $data['paypal_keys'],
                                    'stripe_keys' => $data['stripe_keys'],
                                    'first_name' => $data['first_name'],
                                    'last_name' => $data['last_name'],
                                    'status' => $data['status'],
                                    'is_instructor' => 0,
                                ]);
                            } catch (\Throwable$th) {

                            }
                            //login
                            try {
                                $firebaseUser = $this->user_model->firebaseAuth->getUserByEmail($email);
                                $customClaims = $firebaseUser ? $firebaseUser->customClaims : null;
                            } catch (\Throwable$th) {
                                //throw $th;
                            }

                            if ($customClaims) {
                                $this->session->set_userdata('user_id', $firebaseUser->uid);
                                $this->session->set_userdata('role_id', $customClaims['role_id']);
                                $this->session->set_userdata('role', get_user_role_by_role_id($customClaims['role_id']));
                                $this->session->set_userdata('name', $customClaims['first_name'] . ' ' . $customClaims['last_name']);
                                $this->session->set_userdata('is_instructor', $customClaims['is_instructor']);
                                $this->session->set_userdata('fb_login', 1);

                                if ($customClaims['role_id'] == 1) {
                                    $this->session->set_userdata('admin_login', '1');
                                } else if ($customClaims['role_id'] == 2) {
                                    $this->session->set_userdata('user_login', '1');
                                }
                            } else {
                                $this->session->set_flashdata('error_message', "Cannot create account");
                                redirect(site_url('home/login'), 'refresh');
                            }

                        } else {
                            $this->session->set_flashdata('error_message', get_phrase('email_duplication'));
                            redirect(site_url('home/login'), 'refresh');
                        }
                    }

                    $this->session->set_flashdata('flash_message', get_phrase('welcome') . ' ' . $customClaims['first_name'] . ' ' . $customClaims['last_name']);

                    if ($this->session->userdata('url_history')) {
                        redirect($this->session->userdata('url_history'), 'refresh');
                    }
                    redirect(site_url(), 'refresh');
                } else {
                    $this->session->set_flashdata('error_message', get_phrase('invalid_email_address'));
                    redirect(site_url('home/login'), 'refresh');
                }
            } else {
                $this->delete_app($fb_user_id, $access_token);
                $this->session->set_flashdata('error_message', get_phrase('email_access_permission_is_required'));
                redirect(site_url('home/login'), 'refresh');
            }
        } catch (\Throwable$th) {
            die("Facebook Error " . $th->getMessage());
        }

    }

    public function delete_app($fb_user_id = "", $access_token = "")
    {
        if ($fb_user_id != "" && $access_token != "") {
            require_once APPPATH . '/libraries/facebook-sdk-5.x/autoload.php'; // change path as needed

            $fb = new \Facebook\Facebook([
                'app_id' => get_settings('fb_app_id'),
                'app_secret' => get_settings('fb_app_secret'),
                'default_graph_version' => 'v2.10',
            ]);
            $fbApp = new Facebook\FacebookApp(get_settings('fb_app_id'), get_settings('fb_app_secret'));

            $request = new Facebook\FacebookRequest($fbApp, $access_token, 'DELETE', $fb_user_id . "/permissions");
            try
            {
                $response = $fb->getClient()->sendRequest($request);
            } catch (Facebook\Exceptions\FacebookResponseException$ex) {
                // When Graph returns an error
                // echo("Error - graph returned an error: " . $ex->getMessage() );
                // exit();
            } catch (Facebook\Exceptions\FacebookSDKException$ex) {
                // When validation fails or other local issues
                // echo("Error - Facebook SDK returned an error: " . $ex->getMessage() );
                // exit();
            }
        }
    }

}
