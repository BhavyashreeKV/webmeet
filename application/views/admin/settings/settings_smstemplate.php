<main class="main">
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
    <li class="breadcrumb-item">Email Settings</li>
    <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
</ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
    
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div  class="card">
                <div class="card-header">
                    <?php echo lang('sms_template_form'); ?>
                </div>
                <div class="card-body">
                    <form  action="<?php echo admin_url('settings/sms_templates/' . $id); ?>" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label for=""><?php echo lang('name'); ?><span class="text-danger"> *</span></label>
                            <input type="text" name="name" value="<?php echo set_value('name', $name); ?>" required class="form-control" <?php if ($id) { ?>readonly<?php } ?>>
                        </div>
                        
                        <div class="form-group">
                            <label for=""><?php echo lang('message'); ?><span class="text-danger"> *</span></label>
                            <textarea name="message" class="form-control" required><?php echo set_value('message', $message); ?></textarea>
                            <div class="panel-tag mt-3"> <?php echo lang('currly_note'); ?></div>
                        </div>
                        
                        <div class="form-action float-right">
                            <a href="<?php echo admin_url('settings/sms_templates'); ?>" class="btn btn-secondary ml-auto waves-effect waves-themed">Reset</a>
                            <button id="js-form-btn" class="btn btn-success waves-effect waves-themed ml-2" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <?php echo lang('all_sms_list'); ?>
                </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    </tr>
                                    <th>Action</th>
                                    <th><?php echo lang('name'); ?></th>
                                    <th><?php echo lang('message'); ?></th>
                                    <tr>
                                </thead>
                                <tbody>
                                    <?php if (count($templates) < 1) {
                                        echo '<tr><td align="center" colspan="5">' . lang('no_records') . '</td></tr>';
                                    }
                                    foreach ($templates as $list) { ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo admin_url('settings/sms_templates/' . $list->id); ?>" class="btn btn-outline-primary btn-icon waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                
                                                <a href="<?php echo admin_url('settings/sms_templates_delete/' . $list->id); ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="btn btn-outline-danger btn-icon waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                            <td><?php echo  $list->name; ?></td>
                                            <td><?php echo $list->message; ?></td>

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
