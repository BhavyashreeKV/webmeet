<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Settings</li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
        <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="icon-settings"></i> General <span class="fw-300"><i>inputs</i></span>
                        </div>
                        <div class="card-body">
                            <?php echo form_open_multipart(admin_url('settings?t=' . $cod), 'class="form-horizontal"'); ?>
                                <div class="">
                                    <?php foreach ($w_config as $con) { ?>
                                        <?php if ($con->type == 'text') { ?>
                                            <div class="form-group">
                                                <label class="col-form-label" for="email_hr"><strong><?php echo $con->name; ?> </strong>
                                                    <span>(<?php echo $con->settings_key; ?>)</span>
                                                </label>
                                                <div class="">
                                                    <input type="text" name="<?php echo $con->settings_key; ?>" class="form-control" value="<?php echo set_value($con->settings_key, $con->setting); ?>">
                                                </div>
                                            </div>
                                        <?php }
                                            if ($con->type == 'password') { ?>
                                            <div class="form-group">
                                                <label class="col-form-label" for="s"><strong><?php echo $con->name; ?> </strong>
                                                    <span>(<?php echo $con->settings_key; ?>)</span>
                                                </label>
                                                <div class="">
                                                    <input type="password" name="<?php echo $con->settings_key; ?>" class="form-control" value="<?php echo set_value($con->settings_key, $con->setting); ?>">
                                                </div>
                                            </div>
                                        <?php }
                                            if ($con->type == 'select') { ?>
                                            <div class="form-group">
                                                <label class="col-form-label" for="pwd_hr"><strong><?php echo $con->name; ?></strong>
                                                    <span>(<?php echo $con->settings_key; ?>)</span></label>
                                                <div class="">
                                                    <?php
                                                            $options = array();
                                                            if ($con->options != '') {
                                                                if (is_array($con->options)) {
                                                                    $opts = $con->options;
                                                                    //                                                        print_a($con->options);
                                                                } else {
                                                                    $opts = explode(",", $con->options);
                                                                }
                                                                //                                                array_fill_keys($opts, $opts);
                                                                foreach ($opts as $opt) {
                                                                    $options[$opt] = $opt;
                                                                }
                                                            }
                                                            echo form_dropdown($con->settings_key, $options, set_value($con->settings_key, $con->setting), 'class="form-control"');
                                                            ?>
                                                </div>
                                            </div>
                                        <?php }
                                            if ($con->type == 'textarea') { ?>
                                            <div class="form-group">
                                                <label class="col-form-label"><strong><?php echo $con->name; ?> </strong>
                                                    <span>(<?php echo $con->settings_key; ?>)</span></label>
                                                <div class="">
                                                    <textarea name="<?php echo $con->settings_key; ?>" cols="40" rows="3" class="form-control"><?php echo set_value($con->settings_key, html_entity_decode($con->setting)); ?></textarea>
                                                </div>
                                            </div>
                                        <?php }
                                            if ($con->type == 'file') { ?>
                                            <div class="form-group">
                                                <label class="col-form-label"><strong><?php echo $con->name; ?> </strong>
                                                    <span>(<?php echo $con->settings_key; ?>)</span></label>
                                                <div class="form-inline">
                                                    <div class="col-4">
                                                        <?php echo form_upload(array('name' => $con->settings_key, 'class' => 'upload')); ?>
                                                    </div>
                                                    <?php if ($con->setting != '') { ?>
                                                        <div class="col-8">
                                                            <img src="<?php echo upload_url('site_images/', $con->setting); ?>" alt="img">
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php }
                                            if ($con->type == 'checkbox') { ?>
                                            <div class="form-group">
                                                <label class="col-form-label"><strong><?php echo $con->name; ?> </strong>
                                                    <span>(<?php echo $con->settings_key; ?>)</span></label>
                                                <div class="col-md-10">
                                                    <div class="checkbox">
                                                        <label>
                                                            <?php echo form_checkbox($con->settings_key, '1', set_value($con->settings_key, $con->setting), 'id="' . $con->settings_key . '" class="checkbox style-0"'); ?>
                                                            <span>Check to switch ON/OFF</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                            if ($con->type == 'text_editor') { ?>
                                            <div class="form-group row">
                                                <label class="col-form-label"><strong><?php echo $con->name; ?> </strong>
                                                    <span>(<?php echo $con->settings_key; ?>)</span></label>
                                                <div class="">
                                                    <textarea name="<?php echo $con->settings_key; ?>" cols="40" rows="3" class="form-control"><?php echo set_value($con->settings_key, html_entity_decode($con->setting)); ?></textarea>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <div class="form-actions float-right">
                                <button class="btn btn-success waves-effect waves-themed ml-auto waves-effect waves-themed" type="submit">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php if (ENVIRONMENT == 'development') { ?>
                    <div class="col">
                        <div id="panel-1" class="card">
                            <div class="card-header">
                                Add / Edit Settings <span class="fw-300"><i>(Only For Developers)</i></span>
                            </div>
                            <div class="card-body">
                                <div class="form-group">

                                    <label class="control-label">Select</label>
                                    <div class="">
                                        <select class="form-control select2" id="edit_options">
                                            <option value=''>Select</option>
                                            <?php foreach ($w_config as $conf) { ?>
                                                <option value='<?php echo $conf->id; ?>'><?php echo $conf->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="col-md-12">
                                        <button type="button" onclick="window.location='<?php echo admin_url('settings/form'); ?>/'+$('#edit_options').val();" class="btn btn-primary"><i class="icon-pencil"></i> <?php echo lang('edit'); ?></button>
                                        <a href="<?php echo admin_url('settings/form'); ?>" class="btn btn-success"><i class="icon-plus"></i> <?php echo lang('add_new'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>