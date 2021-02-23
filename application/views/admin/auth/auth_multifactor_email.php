<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Vasudevan">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Multifactor Authentication</title>
    <!-- Icons-->
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="<?php echo base_url('assets_v1/'); ?>css/style.css" rel="stylesheet">
    <link href="<?php echo base_url('assets_v1/'); ?>vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    
  </head>
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card mx-4">
            <div class="card-body p-4">
              <h1>Email Authentication</h1>
              <p class="text-muted">Enter your registered Email id to verify your Login.</p>
              <?php if($this->session->flashdata('error')){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error !</strong> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                <?php } ?>
              <form action="<?php echo current_url(); ?>" method="post">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="icon-user"></i>
                    </span>
                    </div>
                    <input class="form-control" type="text" name="email" placeholder="Email">
                </div>
                <input type="hidden" name="submitted" value="1">
                <button type="submit" class="btn btn-block btn-success" type="button">Submit</button>
              </form>
            </div>
            
          </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="<?php echo base_url('assets_v1/'); ?>vendors/jquery/js/jquery.min.js"></script>
    <script src="<?php echo base_url('assets_v1/'); ?>vendors/popper.js/js/popper.min.js"></script>
    <script src="<?php echo base_url('assets_v1/'); ?>vendors/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets_v1/'); ?>vendors/pace-progress/js/pace.min.js"></script>
    <script src="<?php echo base_url('assets_v1/'); ?>vendors/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
    <script src="<?php echo base_url('assets_v1/'); ?>vendors/@coreui/coreui/js/coreui.min.js"></script>
  </body>
</html>
