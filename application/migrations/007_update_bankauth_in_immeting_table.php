<?php
class Migration_Update_bankauth_in_immeting_table extends CI_Migration
{
    function up()
    {
        $this->load->dbforge();
        $fields = array(
            'auth_patient' => array(
                'type' => 'TINYINT',
                'constraint' => 2,
                'after' => 'booking_id',
                'default'=> '1'
            )
        );
        $this->dbforge->add_column('immediate_bookings', $fields); 
    }


    function down()
    {
        $this->dbforge->drop_column('immediate_bookings', 'auth_patient');
    }
}