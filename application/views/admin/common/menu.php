<?php
$uri_segment1 = $this->uri->segment(2);
$uri_segment2 = $this->uri->segment(3);
?>
<div class="sidebar">
        <nav class="sidebar-nav">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment1 == 'dashboard'|| $uri_segment1 == '')?'active':'';?>" href="<?php echo admin_url('dashboard'); ?>">
                <i class="nav-icon icon-speedometer"></i> Dashboard
              </a>
            </li>
            <?php if ($this->auth->check_privilege(array('booker'), false, false)) { ?>
            <li class="nav-title">Catalog</li>
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-people"></i> Patients</a>
                <ul class="nav-dropdown-items">
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo admin_url('patients/form'); ?>">
                      <i class="nav-icon icon-user"></i>Add Patient <span class="badge badge-success">NEW</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo admin_url('patients'); ?>">
                      <i class="nav-icon icon-list"></i>Manage Patients </a>
                  </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="javascript:;">
                <i class="nav-icon fa fa-stethoscope"></i> <?php echo lang('doctors'); ?></a>
                <ul class="nav-dropdown-items">
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo admin_url('doctors/form'); ?>">
                      <i class="nav-icon fa fa-user-md"></i><?php echo lang('add_doctor'); ?> <span class="badge badge-success">NEW</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo admin_url('doctors'); ?>">
                      <i class="nav-icon icon-list"></i><?php echo lang('manage_doctor'); ?></a>
                  </li>
                </ul>                 
            </li>
            <li class="divider"></li>
            <li class="nav-title">Bookings</li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment1 == 'bookings')?'active':'';?>" href="<?php echo admin_url('bookings'); ?>">
                <i class="nav-icon icon-cursor"></i> Manage Bookings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment1 == 'notes')?'active':'';?>" href="<?php echo admin_url('notes'); ?>">
                <i class="nav-icon icon-notebook"></i> Manage Notes
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment1 == 'feedback')?'active':'';?>" href="<?php echo admin_url('feedback'); ?>">
                <i class="nav-icon fa fa-commenting-o"></i> Manage Feedbacks
              </a>
            </li>
            <?php } ?>
            
              <li class="divider"></li>
            <li class="nav-title">Reports</li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'all_meetings')?'active':'';?>" href="<?php echo admin_url('reports/all_meetings'); ?>">
                <i class="nav-icon icon-cursor"></i> All Meetings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'group_meetings')?'active':'';?>" href="<?php echo admin_url('reports/group_meetings'); ?>">
                <i class="nav-icon icon-cursor"></i> Group Meetings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'immediate_meetings')?'active':'';?>" href="<?php echo admin_url('reports/immediate_meetings'); ?>">
                <i class="nav-icon icon-cursor"></i> Quick Meetings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'upcoming_meetings')?'active':'';?>" href="<?php echo admin_url('reports/upcoming_meetings'); ?>">
                <i class="nav-icon icon-cursor"></i> Upcoming Meetings
              </a>
            </li>
            <?php if ($this->auth->check_privilege(array('admin'), false, false)) { ?>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'bookers_report')?'active':'';?>" href="<?php echo admin_url('reports/bookers_report'); ?>">
                <i class="nav-icon icon-people"></i> Bookers Reports
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'patient_meetings')?'active':'';?>" href="<?php echo admin_url('reports/patient_meetings'); ?>">
                <i class="nav-icon icon-people"></i> Patients Reports
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'doctor_meetings')?'active':'';?>" href="<?php echo admin_url('reports/doctor_meetings'); ?>">
                <i class="nav-icon fa fa-stethoscope"></i> <?php echo lang('doctors'); ?> Reports
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($uri_segment2 == 'historical_report')?'active':'';?>" href="<?php echo admin_url('reports/historical_report'); ?>">
                <i class="nav-icon icon-hourglass"></i> Historical Reports
              </a>
            </li>
            
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle">
                <i class="nav-icon icon-graph"></i> Graphical Reports
              </a>
                  <ul class="nav-dropdown-items">
                    <li class="nav-item">
                      <a class="nav-link <?php echo ($uri_segment2 == 'monthly_meetings_status')?'active':'';?>" href="<?php echo admin_url('reports/monthly_meetings_status'); ?>">
                        <i class="nav-icon icon-people"></i> Status-wise Reports
                      </a>
                    </li>
                  </ul>
            </li>
            <li class="divider"></li>
            <li class="nav-title">Admin User Management</li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo admin_url('users'); ?>">
                <i class="nav-icon icon-people"></i> <?php echo lang('manage_users'); ?></a>
            </li> 
            <li class="nav-item">
              <a class="nav-link" href="<?php echo admin_url('users/form'); ?>">
                <i class="nav-icon cui-user-follow"></i> <?php echo lang('add_user'); ?></a>
            </li> 
            <li class="divider"></li>
            <li class="nav-title">Administrative & Settings</li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo admin_url('settings?t=website'); ?>">
                <i class="nav-icon icon-settings"></i> <?php echo lang('website_settings'); ?></a>
            </li>
            <li class="nav-item nav-dropdown <?php if ($uri_segment2 == 'email_templates' || isset($_GET['t']) && $_GET['t'] =='email') {echo 'active open';} ?>">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-cursor"></i> <?php echo lang('notification_settings'); ?></a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link <?php if(isset($_GET['t']) && $_GET['t'] =='email'){echo 'active'; } ?>" href="<?php echo admin_url('settings?t=email'); ?>">
                    <i class="nav-icon icon-cursor"></i> <?php echo lang('email_configuration'); ?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?php if ($uri_segment2 == "email_templates") {echo 'active';} ?>" href="<?php echo admin_url('settings/email_templates'); ?>">
                    <i class="nav-icon icon-cursor"></i> <?php echo lang('email_templates'); ?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?php if ($uri_segment2 == "sms_templates") {echo 'active';} ?>" href="<?php echo admin_url('settings/sms_templates'); ?>">
                    <i class="nav-icon icon-cursor"></i> <?php echo lang('sms_template'); ?></a>
                </li>
              </ul>
            </li>
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-cursor"></i>Logs</a>
                <ul class="nav-dropdown-items">
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo admin_url('logs/email_logs'); ?>">
                      <i class="nav-icon icon-cursor"></i>Email Logs</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo admin_url('logs/sms_logs'); ?>">
                      <i class="nav-icon icon-cursor"></i>SMS Logs</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo admin_url('logs/bankauth_logs'); ?>">
                      <i class="nav-icon icon-cursor"></i>Bank-Auth Logs</a>
                  </li>
                </ul>
            </li>
            <!-- <li class="nav-item nav-dropdown <?php //if ($uri_segment2 == 'openvidu') {echo 'active open';} ?>">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon icon-cursor"></i> <?php // echo lang('openvidu_settings'); ?></a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link <?php // if($uri_segment2 =='openvidu'){echo 'active'; } ?>" href="<?php // echo admin_url('openvidu'); ?>">
                    <i class="nav-icon icon-cursor"></i> <?php // echo lang('openvidu_sessions'); ?></a>
                </li>
               
              </ul>
            </li> -->
            <?php } ?>
          </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
      </div>