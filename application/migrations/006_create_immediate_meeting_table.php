<?php
class Migration_Create_immediate_meeting_table extends CI_Migration
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
            ),'booking_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => TRUE
            ),'personal_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE
            ),'conference_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),'`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('personal_id');
        $this->dbforge->create_table('immediate_bookings');
    }
    function down()
    {
        $this->dbforge->drop_table('immediate_bookings');
    }
}