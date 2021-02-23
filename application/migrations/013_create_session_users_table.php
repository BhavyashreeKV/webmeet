<?php
class Migration_Create_session_users_table extends CI_Migration
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
            ),'meeting_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'personal_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),'platform' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),'role' => array(
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => TRUE
            ),'connection_time' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),'serverdata' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),'`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('meeting_id');
        $this->dbforge->add_key('personal_id');
        $this->dbforge->create_table('session_users');
    }
    function down()
    {
        $this->dbforge->drop_table('session_users');
    }
}