<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();


// INSERT VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '4.6');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
