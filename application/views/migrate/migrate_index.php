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
                    <div class="alert alert-<?php echo $status; ?>  fade show" role="alert">
                        <strong><?php echo $header; ?></strong> 
                        <p><?php echo $information; ?></p>
                        
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