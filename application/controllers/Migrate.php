<?php 
class Migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        //load database and base libraries, helpers and models
        $this->load->database();
        
        $this->load->library(array('session'));
        $this->load->helper(array('url', 'file'));
       
    }

    public function index($version = NULL) {
        $data = array(
            'status' => 'success',
            'header' => 'Success',
            'information' => ''
        );
        $this->config->load('migration');
        $migrationEnabled = $this->config->item('migration_enabled');

        if ($migrationEnabled) {
            $this->load->library('migration');
    
            if ($version != NULL) {
                if ($this->migration->version($version) === FALSE) {
                    $data['status'] = 'danger';
                    $data['header'] = 'Migration failed';
                    $data['information'] = $this->migration->error_string();
                }
                else {
                    $data['information'] = 'Database migrated to version ' . $version . '.';
                }
            }
            else {
                if ($this->migration->latest() === FALSE) {
                    $data['status'] = 'danger';
                    $data['header'] = 'Migration failed';
                    $data['information'] = $this->migration->error_string();
                }
                else {
                    $data['information'] = 'Database migrated to the latest version.';
                }
            }
        }
        else {
            $data['status'] = 'danger';
            $data['header'] = 'Migration failed';
            $data['information'] = 'Migrations are not enabled. Please enable in the configuration file.';
        }

        $this->load->view('migrate/migrate_index', $data);
    }
}