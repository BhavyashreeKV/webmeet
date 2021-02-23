<?php
class Migration_Update_bookings_by_meeting_type extends CI_Migration
{
    function up()
    {
        $fields = array(
            'meeting_type' => array(
              'type' => 'TINYINT',
              'constraint' => 2,
              'after' => 'meeting_id',
              'default' =>'1',
              'comment' => '1-normal_meeting, 2- Immediate Meeting'
            )
          ); 
        $this->dbforge->add_column('bookings', $fields); 

        $this->db->query("ALTER TABLE `bookings` CHANGE `patient_id` `patient_id` INT(11) UNSIGNED NULL DEFAULT NULL");

        // $this->db->query("ALTER TABLE `bookings` ADD `meeting_type` TINYINT(2) NULL DEFAULT '1' COMMENT '1-normal_meeting, 2- Immediate Meeting' AFTER `meeting_id`");
        
    }
    function down()
    {
        $this->dbforge->drop_column('bookings', 'meeting_type');
    }
}