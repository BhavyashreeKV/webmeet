<?php
class Migration_Update_patient extends CI_Migration
{
    function up()
    {
        $fields = array(
            'added_by' => array(
              'type' => 'INT',
              'constraint' => 11,
              'after' => 'profile_img'
            )
          ); 
        $this->dbforge->add_column('users', $fields);         
    }
    function down()
    {
        $this->dbforge->drop_column('users', 'added_by');
    }
}