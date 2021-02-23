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
        
        <div class="col-lg-12 col-xl-12">
            <!--Table bordered-->
            
            <!--Table hover-->
            <div class="card">
                <div class="card-header">
                    <span><?php echo $page_title; ?></span>
                    <span class="float-right">Total Row <span class="fw-300"><i><?php echo $total_rows; ?></i></span></span>
                    
                </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="<?php echo admin_url('users/form'); ?>" class="btn btn-primary waves-effect waves-themed"><i class="icon-plus"></i> <?php echo lang('add_new'); ?></a>
                        </div>
                        <div class="frame-wrap table-responsive-lg">
                            <table class="table table-bordered  table-hover m-0">
                                <thead class="">
                                    <tr>
                                        <!-- <th>/#</th> -->
                                        <th>Action</th>
                                        <th>Username</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Privileges</th>
                                        <th>Status</th>
                                        <th>Last logged in</th>
                                        <th>Last Logged IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(empty($users)){echo '<tr colspan="6"><td>'.lang('no_records'). '</td></tr>';} 
                                    foreach($users as $user): ?>
                                    <tr>
                                        <!-- <th scope="row">1</!-->
                                        <td>
                                        <a href="<?php echo admin_url('users/form/'.$user->id); ?>" class="btn btn-outline-primary btn-icon  waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('edit'); ?>">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <?php if($user->id != $this->User['id']){ ?>
                                        <a href="<?php echo admin_url('users/delete/'.$user->id); ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="btn btn-outline-danger btn-icon  waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('delete'); ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <?php } ?>
                                        
                                        </td>
                                        <td><?php echo $user->username; ?></td>
                                        <td><?php echo $user->fullname;  ?></td>
                                        <td><?php echo $user->email ;?></td>
                                        <td><?php echo $user->privilege != 'null'? implode(', ',json_decode($user->privilege)):'' ;?></td>
                                        <td><?php echo $user->status == 1?'Active':'In-Active'; ?></td>
                                        <td><?php echo $user->last_login_date ;?></td>
                                        <td><?php echo $user->last_logged_in_ip ;?></td>
                                    </tr>
                                <?php endforeach; ?>                                    
                                </tbody>
                            </table>
                            
                        </div>
                        <?php echo pagination_get($total_rows,$perpage); ?>
                        
                    </div>
            </div>
            
                </div>
            </div>
        </div>
    </div>
</main>