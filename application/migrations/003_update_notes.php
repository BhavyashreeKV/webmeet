<?php
class Migration_Update_notes extends CI_Migration
{

    function create_notes_viewed()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'note_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => TRUE
            ),
            'admin_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => TRUE
            ),
            'action' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('notes_viewedby');
    }
    
    function up()
    {
        $this->create_notes_viewed();
    }
    function down()
    {
        $this->dbforge->drop_table('notes_viewedby');
    }
}