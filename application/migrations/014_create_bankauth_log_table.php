<?php
class Migration_Create_bankauth_log_table extends CI_Migration
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
            ),'personal_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'meeting_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'autostarttoken' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'request' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),'response' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),'error_log' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),'`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('personal_id');
        $this->dbforge->create_table('bankauth_log');
    }
    function down()
    {
        $this->dbforge->drop_table('bankauth_log');
    }
}