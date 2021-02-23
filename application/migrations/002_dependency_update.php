<?php 
class Migration_Dependency_update extends CI_Migration
{
    /* Create the trigger for deletion of the booking 
        1, delete the chron emails 
        2, deletet the sms notifications */
    function mk_trigger_booking()
    {
        $this->db->query("CREATE TRIGGER `del_booking_dependency` BEFORE DELETE ON `bookings`
        FOR EACH ROW BEGIN
       DELETE FROM chron_email_queue WHERE OLD.meeting_id = chron_email_queue.meeting_id;
       DELETE FROM sms_notification_alert WHERE OLD.meeting_id = sms_notification_alert.meeting_id;
       END");


    }

    function drp_trigger_booking()
    {
        $this->db->query('DROP TRIGGER IF EXISTS `del_booking_dependency`');
    }

    function up()
    {
        $this->mk_trigger_booking();
    }
    function down()
    {
        $this->drp_trigger_booking();
    }
}