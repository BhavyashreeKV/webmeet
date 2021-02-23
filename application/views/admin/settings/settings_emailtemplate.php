<main class="main">
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
    <li class="breadcrumb-item">Email Settings</li>
    <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
</ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
    
    <div class="row">
        <div class="col-xl-6">
            <div  class="card">
                <div class="card-header">
                    <?php echo lang('add_update'); ?>
                </div>
                <div class="card-body">
                    <form  action="<?php echo admin_url('settings/email_templates/' . $id); ?>" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label for=""><?php echo lang('name'); ?><span class="text-danger"> *</span></label>
                            <input type="text" name="name" value="<?php echo set_value('name', $name); ?>" required class="form-control" <?php if ($id) { ?>readonly<?php } ?>>
                        </div>
                        <div class="form-group">
                            <label for=""><?php echo lang('subject'); ?><span class="text-danger"> *</span></label>
                            <input type="text" name="subject" value="<?php echo set_value('subject', $subject); ?>" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for=""><?php echo lang('message'); ?><span class="text-danger"> *</span></label>
                            <textarea name="message" id="mymce1" required class="form-control" rows="10"><?php echo set_value('message', $message); ?></textarea>
                            <div class="panel-tag mt-3"> <?php echo lang('currly_note'); ?></div>
                        </div>
                        <div class="form-group">
                            <label for=""><?php echo lang('from_email'); ?><span class="text-danger"> *</span></label>
                            <input type="text" name="from_email" value="<?php echo set_value('from_email', $from_email); ?>" required class="form-control">
                        </div>
                        <!-- <div class="form-group" id="all_users">
                            <label for=""><?php echo lang('to_email'); ?></label>
                            
                            <div class="row pt-3">
                                <?php //$c=0; foreach($all_user_emails as $key=>$value){ ?>
                                <div class="col-md-6 mb-3 col-sm-6">
                                    <div class="custom-control custom-checkbox"> 
                                        <input type="checkbox" name="to_email[]" class="custom-control-input" 
                                        id="k<?php// echo $c; ?>" value="<?php// echo $key; ?>" <?php// if(in_array($key,unserialize($to_email))){echo 'checked';} ?>>
                                        <label for="k<?php// echo $c; ?>" class="custom-control-label"><?php //echo $value." ($key)"; ?></label>
                                    </div>
                                </div>
                                <?php //$c++; } ?>
                            </div>
                        </div> -->
                        <div class="form-action float-right">
                            <a href="<?php echo admin_url('settings/email_templates'); ?>" class="btn btn-secondary ml-auto waves-effect waves-themed">Reset</a>
                            <button id="js-form-btn" class="btn btn-success waves-effect waves-themed ml-2" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <?php echo lang('all_email_list'); ?>
                </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    </tr>
                                    <th>Action</th>
                                    <th><?php echo lang('name'); ?></th>
                                    <th><?php echo lang('subject'); ?></th>
                                    <th><?php echo lang('from_email'); ?></th>
                                    <tr>
                                </thead>
                                <tbody>
                                    <?php if (count($clist) < 1) {
                                        echo '<tr><td align="center" colspan="5">' . lang('no_records') . '</td></tr>';
                                    }
                                    foreach ($clist as $list) { ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo admin_url('settings/email_templates/' . $list->id); ?>" class="btn btn-outline-primary btn-icon waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="javascript:;" data-toggle="collapse" data-target="#collapse<?php echo $list->id; ?>" class="btn btn-outline-info btn-icon">
                                                    <i class="fa fa-eye" data-toggle="tooltip" title="<?php echo lang('view_more'); ?>"></i>
                                                </a>
                                                <a href="<?php echo admin_url('settings/email_templates_delete/' . $list->id); ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="btn btn-outline-danger btn-icon waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                            <td><?php echo  $list->name; ?></td>
                                            <td><?php echo $list->subject; ?></td>

                                            <td><?php echo $list->from_email; ?></td>
                                        </tr>
                                        <tr class="collapse" id="collapse<?php echo $list->id; ?>">
                                            <td colspan="5"><b>Message :</b> <?php echo $list->message; ?>
                                            <!-- <br><b>To email :</b> <?php // $to_emails = unserialize($list->to_email);
                                           // echo (!empty($to_email))?implode(', ',$to_emails):''; ?> -->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
            </div>
        </div>

    </div>
        </div>
    </div>
</main>
<!-- <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script> -->
<script>
                        // CKEDITOR.replace( 'message' );
                </script>