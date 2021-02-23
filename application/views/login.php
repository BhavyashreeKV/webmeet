<!DOCTYPE html>

<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="<?php echo config_item('company_name'); ?>">
    <meta name="author" content="Vasudevan">
    <meta name="keyword" content="Bootstrap,Admin,Template,Kognitiva,jQuery,CSS,HTML">
    <title>Login - <?php echo config_item('company_name'); ?></title>
    <!-- Icons-->
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/_coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="<?php echo base_url('assets_v1'); ?>/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1'); ?>/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    
  </head>
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card-group">
            <div class="card p-4">
                <?php if($this->session->flashdata('error')){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error !</strong> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                <?php } ?>
                <form action="<?php echo current_url(); ?>" method="post">
              <div class="card-body">
                <h1>Login</h1>
                <p class="text-muted">Sign In to your account</p>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="icon-user"></i>
                    </span>
                  </div>
                  <input class="form-control" id="username" name="username" type="text" placeholder="Username">
                </div>
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="icon-lock"></i>
                    </span>
                  </div>
                  <input class="form-control" id="password" name="password" type="password" placeholder="Password">
                </div>
                <div class="row">
                  <div class="col-6">
                        <input type="hidden" name="submitted" value="1">
                        <input type="hidden" name="redirect" value="<?php echo $redirect;?>">
                    <button class="btn btn-primary px-4" type="submit">Login</button>
                  </div>
                  <div class="col-6 text-right">
                    <a class="btn btn-link px-0" href="<?php echo site_url(config_item('admin_folder').'/login/recover_password'); ?>">Forgot password?</a>
                  </div>
                </div>
              </div>
                </form>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
              <div class="card-body text-center">
                <div>
                  <h2 class="pt-5"><?php echo config_item('company_name'); ?></h2>
                  <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p> -->
                  <!-- <button class="btn btn-primary active mt-3" type="button">Register Now!</button> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?php echo base_url('assets_v1/vendors'); ?>/jquery/js/jquery.min.js"></script>
    <script src="<?php echo base_url('assets_v1/vendors'); ?>/popper.js/js/popper.min.js"></script>
    <script src="<?php echo base_url('assets_v1/vendors'); ?>/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets_v1/vendors'); ?>/pace-progress/js/pace.min.js"></script>
    <script src="<?php echo base_url('assets_v1/vendors'); ?>/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
    <script src="<?php echo base_url('assets_v1/vendors'); ?>/_coreui/coreui/js/coreui.min.js"></script>
  </body>
</html>
