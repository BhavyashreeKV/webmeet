<?php
class Migration_Update_bankid_username_in_im_gp_bookings_table extends CI_Migration
{
    function up()
    {
        $this->load->dbforge();
        $fields = array(
            'email' => array(
                'type' => 'varchar',
                'constraint' => 255,
                'after' => 'phone',
                'default'=> NULL
            ),
            'fullname' => array(
                'type' => 'varchar',
                'constraint' => 100,
                'after' => 'phone',
                'default'=> NULL
            ),
        );
        $this->dbforge->add_column('immediate_bookings', $fields); 

        $fields2 = array(
            'fullname' => array(
                'type' => 'varchar',
                'constraint' => 100,
                'after' => 'email',
                'default'=> NULL
            ),
        );
        $this->dbforge->add_column('group_bookings', $fields2); 
    }


    function down()
    {
        $this->dbforge->drop_column('immediate_bookings', 'email');
        $this->dbforge->drop_column('immediate_bookings', 'fullname');
        $this->dbforge->drop_column('group_bookings', 'fullname');
    }
}