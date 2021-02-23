<main class="main">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">User Management</li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
        
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
    <form action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                       <?php echo $page_title; ?>
                </div>
                    
                        <div class="card-body">
                            
                                <div class="row border-bottom">
                                    <div class="col-12">
                                        <legend>Update Personal Information</legend>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="simpleinput"><?php echo lang('fullname'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="fullname" value="<?php echo set_value('fullname',$fullname); ?>" id="simpleinput" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="username"><?php echo lang('username'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" required name="username" id="username" value="<?php echo set_value('username',$username); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="simpleinput1"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" required name="email" id="simpleinput1" class="form-control" value="<?php echo set_value('email',$email); ?>">
                                        </div><?php echo form_error('name'); ?>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="phone"><?php echo lang('phone'); ?></label>
                                            <input type="text" name="phone" id="phone" value="<?php echo set_value('phone',$phone); ?>" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <legend>Update Password</legend>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="password"><?php echo lang('password'); ?></label>
                                            <input type="password" name="password" id="password" class="form-control" <?php if(!$id){echo 'required';} ?>>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="confirm_password"><?php echo lang('confirm_password'); ?></label>
                                            <input type="password" name="confirm" id="confirm_password" class="form-control" <?php if(!$id){echo 'required';} ?>>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <legend>Update Profile Picture</legend>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="profile_img"><?php echo lang('profile_img'); ?></label>
                                            <input type="file" name="profile_img" id="profile_img" class="form-control-file">
                                        </div>
                                    </div>
                                    <?php if($profile_img){ ?>
                                    <div class="col-12 p-2 border text-center">
                                        <img src="<?php echo upload_url('profile/thumb/',$profile_img); ?>">
                                    </div>
                                    <?php } ?>
                                    
                                    
                                </div>
                               
                                
                                <div class="mt-3 form-action float-right">                            
                                    <a href="<?php echo admin_url('users'); ?>" class="btn btn-secondary waves-effect waves-themed ">Back</a>
                                    <button class="btn btn-success ml-auto waves-effect waves-themed" type="submit">Submit</button>
                                </div>
                        </div>
                    </form>
                
            </div>
        </div>
        
    </div>
        </form>
        </div>
    </div>
</main>