<?php
class Migration_Update_cron_in_immediate_email_table extends CI_Migration
{
    function up()
    {
        $this->load->dbforge();
        $fields = array(
            'immediate_email' => array(
                'type' => 'TINYINT',
                'constraint' => 2,
                'after' => 'send',
                'default'=> '0'
            ),
            'pivot_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'send',
                'default'=> '0'
            ),
            'send_attempt' => array(
                'type' => 'TINYINT',
                'constraint' => 2,
                'after' => 'send',
                'default'=> '0'
            ),
            'is_failed' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'after' => 'queued',
                'default'=> '0'
            )
        );
        $this->dbforge->add_column('chron_email_queue', $fields); 
    }


    function down()
    {
        $this->dbforge->drop_column('chron_email_queue', 'immediate_email');
    }
}