<?php
class Migration_Create_sms_logs_table extends CI_Migration
{
    function up()
    {
        $this->load->dbforge();
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),'to' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'content' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),'send' => array(
                'type' => 'TINYINT',
                'constraint' => 2,
                'unsigned' => TRUE,
            ),'send_time' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),'error_log' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),'`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sms_notification_logs');
    }
    function down()
    {
        $this->dbforge->drop_table('sms_notification_logs');
    }
}