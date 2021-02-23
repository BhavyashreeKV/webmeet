<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title><?php echo isset($page_title)? $page_title.' - ':''; echo config_item('company_name'); ?></title>
    <!-- Icons-->
    <link rel="icon" type="image/ico" href="<?php echo upload_url('site_images/',config_item('fav_icon')); ?>" sizes="any" />
    <link href="<?php echo template_assets(); ?>vendors/_coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="<?php echo template_assets(); ?>vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="<?php echo template_assets(); ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo template_assets(); ?>vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link href="<?php echo template_assets(); ?>vendors/select2/css/select2.min.css" rel="stylesheet">
    <!-- Datetime picker -->
    <link href="<?php echo template_assets(); ?>vendors/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" integrity="sha256-bLNUHzSMEvxBhoysBE7EXYlIrmo7+n7F4oJra1IgOaM=" crossorigin="anonymous" />
    <!-- Main styles for this application-->
    <link href="<?php echo template_assets(); ?>css/style.css" rel="stylesheet">
    <link href="<?php echo template_assets(); ?>vendors/toastr/css/toastr.css" rel="stylesheet">
    <link href="<?php echo template_assets(); ?>vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.dataTables.min.css" />
 
    <?php if(isset($booking_script)){ ?>
      <link href="<?php echo template_assets(); ?>vendors/fullcalendar/css/fullcalendar.min.css" rel="stylesheet"/>
    <?php } ?>
    <script src="<?php echo template_assets(); ?>vendors/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js" ></script>

  </head>
  <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <header class="app-header navbar">
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img class="navbar-brand-full" src="<?php echo upload_url('site_images/',config_item('site_logo')); ?>" width="89" height="25" alt="CoreUI Logo">
        <img class="navbar-brand-minimized" src="<?php echo template_assets(); ?>img/brand/sygnet.svg" width="30" height="30" alt="CoreUI Logo">
      </a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
          <a class="nav-link" href="#">Dashboard</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link" href="#">Users</a>
        </li>
        <li class="nav-item px-3">
          <a class="nav-link" href="#">Settings</a>
        </li>
      </ul> -->
      <ul class="nav navbar-nav ml-auto mr-4">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <img class="img-avatar" src="<?php echo ($this->admin['profile_pic']!=NULL)?upload_url('profile/thumb/',$this->admin['profile_pic']): template_assets('img/avatars/6.jpg'); ?>" alt="admin@bootstrapmaster.com">
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <!-- <div class="dropdown-header text-center">
              <strong>Account</strong>
            </div>
            <a class="dropdown-item" href="#">
              <i class="fa fa-bell-o"></i> Updates
              <span class="badge badge-info">42</span>
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-envelope-o"></i> Messages
              <span class="badge badge-success">42</span>
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-tasks"></i> Tasks
              <span class="badge badge-danger">42</span>
            </a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-comments"></i> Comments
              <span class="badge badge-warning">42</span>
            </a> -->
            <div class="dropdown-header text-center">
              <strong>Settings</strong>
            </div>
            <a class="dropdown-item" href="<?php echo admin_url('dashboard/profile'); ?>">
              <i class="fa fa-user"></i> Profile</a>
            
            <a class="dropdown-item" href="<?php echo admin_url('login/logout'); ?>">
              <i class="fa fa-lock"></i> Logout</a>
          </div>
        </li>
      </ul>
      <!-- <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
        <span class="navbar-toggler-icon"></span>
      </button> -->
    </header>
    <div class="app-body">
      <?php include_once('menu.php'); ?>