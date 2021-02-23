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
    <title>Recover Password - <?php echo config_item('company_name'); ?></title>
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
        <div class="col-md-6">
          <div class="card mx-4">
            <div class="card-body p-4">
            
            <form action="<?php echo current_url(); ?>" method="post">
            <?php if($this->session->flashdata('message')){ ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success !</strong> <?php echo $this->session->flashdata('message'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if($this->session->flashdata('error')){ ?> 
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> <?php echo $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
              <h1>Forgot Password?</h1>
              <p class="text-muted">Not a problem, happens to the best of us. Just use the form below to reset it!</p>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="icon-user"></i>
                  </span>
                </div>
                <input class="form-control" type="text" name="username" placeholder="Enter Registered username*" required>
              </div>
              
              
              <button class="btn btn-block btn-success" type="submit">Recover</button>
            </form>
            <a class="btn btn-link px-0" href="<?php echo site_url(config_item('admin_folder').'/login'); ?>">Back To Login</a>
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

