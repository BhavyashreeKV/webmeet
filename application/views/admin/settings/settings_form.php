<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Settings</li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <!-- /.row-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-edit"></i><?php echo $page_title; ?></div>
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo current_url(); ?>" method="post">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Name</label>
                                            <?php
                                            $data    = array('name' => 'name', 'value' => set_value('name', $name), 'class' => 'form-control');
                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Code</label>
                                            <?php
                                            $options1    = array('website' => 'Website', 'email' => 'Email');
                                            echo form_dropdown('code', $options1,  set_value('code', $code), 'class="form-control"');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Type</label>
                                            <?php
                                            $options1    = array('text' => 'Input Box', 'textarea' => 'Textarea', 'file' => 'File Upload', 'select' => 'Select Box', 'checkbox' => 'Checkbox', 'text_editor' => 'Editor', 'password' => 'Password');
                                            echo form_dropdown('type', $options1,  set_value('type', $type), 'class="form-control select2" id="type"');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Sequence </label>
                                            <?php
                                            $data    = array('name' => 'sequence', 'value' => set_value('sequence', $sequence), 'class' => 'form-control');
                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Options</label>
                                            <?php
                                            $data    = array('rows' => 3, 'name' => 'options', 'value' => set_value('options', html_entity_decode($options)), 'class' => 'form-control', 'id' => 'opt_val');
                                            echo form_textarea($data);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions float-right">
                                    <a class="btn btn-secondary" href="<?php echo admin_url('settings'); ?>">Cancel</a>
                                    <button class="btn btn-success" type="submit">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>
            <!-- /.row-->
        </div>
    </div>
</main>