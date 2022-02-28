<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();


$data['value']    =   '';
$data['key']    =   'refund_policy';
$this->db->insert('frontend_settings', $data);

$data['value'] = '0';
$data['key']    =   'fb_social_login';
$this->db->insert('settings', $data);

$data['value'] = 'facebook-app-id';
$data['key']    =   'fb_app_id';
$this->db->insert('settings', $data);

$data['value'] = 'facebook-app-secret-key';
$data['key']    =   'fb_app_secret';
$this->db->insert('settings', $data);


// INSERT VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.2');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
