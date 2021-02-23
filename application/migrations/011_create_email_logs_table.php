<?php
class Migration_Create_email_logs_table extends CI_Migration
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
            ),'from' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),'to' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),'content' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),'send' => array(
                'type' => 'TINYINT',
                'constraint' => 2,
                'unsigned' => TRUE,
            ),'error_log' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),'`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('email_logs');


}
    function down()
    {
        $this->dbforge->drop_table('email_logs');
        
    }
}