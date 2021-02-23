<?php 
class Migration_Create_base extends CI_Migration
{
    function create_tables()
    {
        $this->load->dbforge();
        /* Add Admin Table */
        $admin_fields = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'1',
                'null' => TRUE
            ),
            'fullname' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE
            ),
            'privilege' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'profile_img' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'auth_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'last_login_date' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE
            ),
            'last_logged_in_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'last_logout_date' => array(
                'type' => 'TIMESTAMP',
                'null' => TRUE
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            
        );
        $this->dbforge->add_field($admin_fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('admin');

        /* Add Seetings Tbl */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'code' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'settings_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'setting' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'default' => '1',
            ),
            'sequence' => array(
                'type' => 'INT',
                'constraint' => '11',
                'default' => '0',
            ),
            'options' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => TRUE
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('settings');

        /* Create Users Tbl */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'type' => array(
                'type' => 'ENUM("1","2")',
                'default' => '1',
            ),
            'personal_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'hsaid' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'1',
            ),
            'firstname' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'lastname' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE
            ),
            'privilege' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'profile_img' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'auth_key' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'last_logged_in' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'otp' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'resend_otp_count' => array(
                'type' => 'TINYINT',
                'constraint' => '4',
                'null' => FALSE,
                'default' =>'0',
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('personal_id');
        $this->dbforge->add_key('hsaid');
        $this->dbforge->create_table('users');

        /* create Booking Tbl */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'patient_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ),
            'doctor_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ),
            'meeting_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'booking_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'start_datetime' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'end_datetime' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'ENUM("new","rescheduled","cancelled","completed","missed")',
                'default' => 'new',
            ),
            'created_by' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'created_by_type' => array(
                'type' => 'ENUM("admin","behandlare")',
                'default' => 'admin',
            ),
            'notify' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'0',
            ),
            'ov_sess' => array(
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => TRUE
            ),
            'doc_sess' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'0',
            ),
            'pat_sess' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'0',
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('bookings');

        /* Create Queue Email Table */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'meeting_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'send_datetime' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'from_email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'to_email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'subject' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'queued' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'0',
            ),
            'send' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'0',
            ),
            '`created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('chron_email_queue');

        /* Create Email Templates Table */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'from_email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'subject' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'enabled' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'1',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('email_templates');

        /* Create Notes Table */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'doctor_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'meeting_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'notes' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('notes');

        /* Create Ratings Table */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'booking_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'rating' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
            ),
            'review' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'type' => array(
                'type' => 'ENUM("Doctor", "Patient")',
                'default' => 'Doctor',
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ratings');

        /* Create SMS Template Table */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sms_templates');
        
        /*Create SMS Notification Alert */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'null' => FALSE,
                'auto_increment' => TRUE
            ),
            'meeting_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint'=> '1',
                'default' =>'1',
            ),
            'batch_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'message_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'notification' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            '`added_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sms_notification_alert');

    }

    function insert_default_data()
    {
        /* Insert Admin Table Data to Login to Admin Panel */
        $this->db->query("INSERT INTO `admin` (`id`, `status`, `fullname`, `username`, `password`, `email`, `phone`, `privilege`, `profile_img`, `auth_key`, `last_login_date`, `last_logged_in_ip`, `last_logout_date`, `added_date`) VALUES
            (1, 1, 'Master - ADMIN', 'test-admin', '938fa428023f8033c86e74754c43821d0aa45945', 'example@pointservices.se', '9876543210', '[\"admin\",\"booker\"]', 'bb1b65df8ca644666896b5aa470246d2.jpg', '', '', '', '', '');");

        /* Insert Default Settings */
        $this->db->query("INSERT INTO `settings` (`id`, `code`, `name`, `settings_key`, `setting`, `type`, `status`, `sequence`, `options`) VALUES
        (1, 'email', 'Email Protocol', 'email_protocol', 'smtp', 'text', 1, 1, ''),
        (2, 'email', 'Email Host', 'email_host', '', 'text', 1, 2, ''),
        (3, 'email', 'Email Port', 'email_port', '587', 'text', 1, 0, ''),
        (4, 'email', 'Email Username', 'email_username', '', 'text', 1, 0, ''),
        (5, 'email', 'Email Password', 'email_password', '', 'password', 1, 0, ''),
        (6, 'website', 'Company Name', 'company_name', 'Kognitiva WebMeeting', 'text', 0, 1, ''),
        (7, 'website', 'Site Logo', 'site_logo', '7c0ca8e0f34f78cc200881d819139619.svg', 'file', 0, 2, ''),
        (8, 'website', 'Fav Icon', 'fav_icon', 'da2e31127116490c2925ae5d81e77c73.ico', 'file', 0, 3, ''),
        (9, 'website', 'Copyrights', 'copyrights', '© 2019 All Rights are reserved.', 'text', 0, 4, ''),
        (10, 'website', 'Enable Multifactor', 'enable_multifactor', '', 'checkbox', 0, 9, ''),
        (11, 'website', 'SMS Username', 'sms_username', '', 'text', 0, 7, ''),
        (12, 'website', 'SMS Secret', 'sms_secret', '', 'password', 0, 8, ''),
        (13, 'website', 'Send SMS', 'send_sms', '', 'checkbox', 0, 6, ''),
        (14, 'website', 'Send Email Notification', 'send_email_notification', '0', 'checkbox', 0, 6, ''),
        (15, 'website', 'Enable 2-Way authentication', 'enable_2-way_authentication', '1', 'checkbox', 1, 10, ''),
        (16, 'email', 'smtp crypto', 'smtp_crypto', '1', 'checkbox', 1, 6, '');");

        /* Insert Patient and Treatment Specialist */
        $this->db->query("INSERT INTO `users` (`id`, `type`, `personal_id`, `hsaid`, `status`, `firstname`, `lastname`, `password`, `email`, `phone`, `privilege`, `auth_key`, `profile_img`, `added_date`) VALUES
        (1, '2', '78945613', 'NULL', 1, 'test 2', 'Dev', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'example1@mail.com', '', NULL, '', 'f722747e11f9ae60457953f41545c10d.jpg', ''),
        (2, '1', 'behandlare', '87542', 1, 'Garry Doc', 'Dill', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'example2@mail.com', '919043930388', 'own_patient', '', 'f693f64d943cb06c4957c2b09acfc554.jpg', '');");

        /* Insert Default Email Templates */
        $this->db->query("INSERT INTO `email_templates` (`id`, `name`, `subject`, `message`, `from_email`, `enabled`) VALUES
        (1, 'Booking - Meeting assigned to Doctor', '{status} Meeting - {date_time} - {meeting_id}', '<p>Hello Behandlare,</p>\r\n\r\n<p>You have assigned to a {status} meeting {meeting_id} on {date_time} with {patient_name}.</p>\r\n\r\n<p>Regards,<br />\r\n{company_name} Team</p>\r\n\r\n<p>For further clarification please contact <a href=\"mailto:kognitiva@mail.com\">kognitiva@mail.com</a></p>', 'noreply@kognitiva.se',  1),
        (2, 'Booking - Remainder Email of Today\'s meeting to Doctor', 'Reminder Meeting - {date_time} - {meeting_id}', '<p>Hello Behandlare,</p>\r\n\r\n<p>This is a Remainder email for the&nbsp;meeting {meeting_id} on {date_time} with {patient_name}.</p>\r\n\r\n<p>Regards,<br />\r\n{company_name} Team</p>\r\n\r\n<p>For further clarification please contact&nbsp;<a href=\"mailto:kognitiva@mail.com\">kognitiva@mail.com</a></p>', 'noreply@kognitiva.se', 1),
        (3, 'Booking - Meeting Assigned to Patient', '{status} Meeting - {date_time} - {meeting_id}', '<p>Hello {patient_name},</p>\r\n\r\n<p>You have assigned to a new meeting {meeting_id} on {date_time} with {doctor_name}.</p>\r\n\r\n<p>Regards,<br />\r\n{company_name} Team</p>\r\n\r\n<p>For further clarification please contact&nbsp;<a href=\"mailto:kognitiva@mail.com\">kognitiva@mail.com</a></p>', 'noreply@kognitiva.se', 1),
        (4, 'Booking - Remainder Email of Today\'s meeting to Patient', 'Reminder Meeting - {date_time} - {meeting_id}', '<p>Hello {patient_name},</p>\r\n\r\n<p>This is a Remainder email for the&nbsp;meeting {meeting_id} on {date_time} with {doctor_name}.</p>\r\n\r\n<p>Regards,<br />\r\n{company_name} Team</p>\r\n\r\n<p>For further clarification please contact&nbsp;<a href=\"mailto:kognitiva@mail.com\">kognitiva@mail.com</a></p>', 'noreply@kognitiva.se', 1),
        (5, 'Booking - Cancelled Meeting - Doctor', 'Meeting Cancelled - {date_time} - {meeting_id}', '<p>Hello Behandlare</p>\r\n\r\n<p>Your meeting {meeting_id} - {date_time} has been cancelled.</p>\r\n\r\n<p>Regards,<br />\r\n{company_name} Team</p>\r\n\r\n<p>For further clarification please contact&nbsp;<a href=\"mailto:kognitiva@mail.com\">kognitiva@mail.com</a></p>', 'noreply@kognitiva.se', 1),
        (6, 'Booking - Cancelled Meeting - Patient', 'Meeting Cancelled - {date_time} - {meeting_id}', '<p>Dear {patient_name}</p>\r\n\r\n<p>Your meeting {meeting_id} - {date_time} has been cancelled.</p>\r\n\r\n<p>Regards,<br />\r\n{company_name} Team</p>\r\n\r\n<p>For further clarification please contact&nbsp;<a href=\"mailto:kognitiva@mail.com\">kognitiva@mail.com</a></p>', 'noreply@kognitiva.se', 1),
        (7, 'Two Factor Authentication - OTP Login Template', 'Login Otp - Kognitiva web meeting', '<p>Dear {fullname},<br />Please find your One Time Password {otp_number} to login into the application.</p><p>Regards,<br />\r\n{company_name} Team</p><p>For further clarification please contact&nbsp;<a href=\"mailto:kognitiva@mail.com\">kognitiva@mail.com</a></p>', 'noreply@kognitiva.se', 1);
        ");

        /* Insert the SMS Tempates to tbl */
        $this->db->query("INSERT INTO `sms_templates` (`id`, `name`, `message`, `added_date`) VALUES
        (1, 'Meetings Scheduled - Treatment Specialist Template', 'You are assinged to {status} meeting({meeting_id}) {patient_name}({mobile_no}) on {date_time}', ''),
        (2, 'Meetings Scheduled - Patient Template', 'You are assinged to {status} meeting({meeting_id}) {doctor_name}({mobile_no}) on {date_time}', ''),
        (3, 'Remainder SMS - Treatment Spcialist Template', 'This is a remainder alert for the Meeting({meeting_id}) with {patient_name} on {date_time}', ''),
        (4, 'Remainder SMS - Patient Template', 'This is a remainder alert for the Meeting({meeting_id}) with {doctor_name} on {date_time}', ''),
        (5, 'Meeting Cancelled - Template', 'Your meeting({meeting_id}) with the {user_name} on {date_time} has been cancelled.', ''),
        (6, 'Two Factor Authentication - OTP Login Template', 'Dear {fullname}, Your OTP is {otp_number}. Please use it to login into the application.', '');");

    }

    public function up()
    {
        $this->create_tables();
        $this->insert_default_data();
    }
    public function down()
    {
        $this->dbforge->drop_table('ratings');
        $this->dbforge->drop_table('notes');
        $this->dbforge->drop_table('sms_notification_alert');
        $this->dbforge->drop_table('sms_templates');
        $this->dbforge->drop_table('chron_email_queue');
        $this->dbforge->drop_table('email_templates');
        $this->dbforge->drop_table('bookings');
        $this->dbforge->drop_table('users');
        $this->dbforge->drop_table('settings');
        $this->dbforge->drop_table('admin');
    }
}