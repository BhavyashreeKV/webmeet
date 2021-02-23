<main class="main">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><?php echo lang('doctors'); ?></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
        
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
    <form action="<?php echo current_url(); ?>" method="post">
    <div class="row">
        
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                       <?php echo $page_title; ?>
                </div>
                    
                        <div class="card-body">
                            
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="firstname"><?php echo lang('firstname'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="firstname" value="<?php echo set_value('firstname',$firstname); ?>" id="firstname" required class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="lastname"><?php echo lang('lastname'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" required name="lastname" id="lastname" value="<?php echo set_value('lastname',$lastname); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="simpleinput1"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" required name="email" id="simpleinput1" class="form-control" value="<?php echo set_value('email',$email); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="personal_id"><?php echo lang('personal_id'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" required name="personal_id" id="personal_id" class="form-control" value="<?php echo set_value('personal_id',$personal_id); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="hsaid"><?php echo lang('hsaid'); ?> </label>
                                            <input type="text"  name="hsaid" id="hsaid" class="form-control" value="<?php echo set_value('hsaid',$hsaid); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="phone"><?php echo lang('phone'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" id="phone" value="<?php echo set_value('phone',$phone); ?>" class="form-control" required onkeypress="return isNumberKey(event)" maxlength="12">
                                            <small class="text-muted">Add number with country code Ex. 46 763 233 213</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for=""><?php echo lang('status'); ?> <span class="text-danger">*</span></label>
                                            <?php echo form_dropdown('status',config_item('status'),$status,'class="form-control" required'); ?>
                                        </div>
                                    </div>
                                    <div class="col-4 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="password"><?php echo lang('password'); ?> <?php if(!$id){echo '<span class="text-danger">*</span>';} ?></label>
                                            <input type="password" name="password" id="password" class="form-control" <?php if(!$id){echo 'required';} ?>>
                                        </div>
                                    </div>
                                    <div class="col-4 mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="confirm_password"><?php echo lang('confirm_password'); ?> <?php if(!$id){echo '<span class="text-danger">*</span>';} ?></label>
                                            <input type="password" name="confirm" id="confirm_password" class="form-control" <?php if(!$id){echo 'required';} ?>>
                                        </div>
                                    </div>
                                    <div class="col-4 mb-3">
                                        <div class="form-group">
                                            <label><?php echo lang('privilege'); ?></label>
                                            <?php echo form_dropdown('privilege',config_item('doc_privileges'),$privilege,'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    
                                </div>

                                        <div class="form-action float-right">                            
                                            <a href="<?php echo admin_url('doctors'); ?>" class="btn btn-secondary waves-effect waves-themed ">Back</a>
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
<script>
function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
</script>