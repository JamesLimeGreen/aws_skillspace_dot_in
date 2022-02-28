<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

// ADDING COUPON COLUMN IN PAYMENT TABLES
$coupon_column = array(
    'is_free' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => 0,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);

$this->dbforge->add_column('lesson', $coupon_column);


$data['value']    =   '[{"active":"1","key":"rzp_test_J60bqBOi1z1aF5","secret_key":"uk935K7p4j96UCJgHK8kAU4q","theme_color":"#c7a600"}]';
$data['key']    =   'razorpay_keys';
$this->db->insert('settings', $data);

$data['value'] = 'USD';
$data['key']    =   'razorpay_currency';
$this->db->insert('settings', $data);


// INSERT VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.1');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
