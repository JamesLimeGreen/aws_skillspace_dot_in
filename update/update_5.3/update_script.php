<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

// CREATING COUPONS TABLE
$watch_history_table_fields = array(
    'watch_history_id' => array(
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'course_id' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'student_id' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'watching_lesson_id' => array(
        'type' => 'INT',
        'constraint' => '11',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'date_added' => array(
        'type' => 'VARCHAR',
        'constraint' => '50',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    ),
    'date_updated' => array(
        'type' => 'VARCHAR',
        'constraint' => '50',
        'default' => null,
        'null' => TRUE,
        'collation' => 'utf8_unicode_ci'
    )
);
$CI->dbforge->add_field($watch_history_table_fields);
$CI->dbforge->add_key('watch_history_id', TRUE);
$attributes = array('collation' => "utf8_unicode_ci");
$CI->dbforge->create_table('watch_histories', TRUE);


// INSERT VERSION NUMBER INSIDE SETTINGS TABLE
$settings_data = array( 'value' => '5.3');
$CI->db->where('key', 'version');
$CI->db->update('settings', $settings_data);
?>
