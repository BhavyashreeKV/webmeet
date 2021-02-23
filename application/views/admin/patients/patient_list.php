<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Patients</li>
        <li class="breadcrumb-item active"><?php

use phpDocumentor\Reflection\Types\Null_;

echo $page_title; ?></li>
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
                        <div class="float-right">
                                <form class="form-inline" action="<?php echo current_url_with_get(); ?>">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <input class="form-control" id="input1-group2" type="text" name="term" placeholder="Search..." autocomplete="off" value="<?php echo isset($_GET['term'])?$_GET['term']:''; ?>">
                                                <span class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-search"></i> Search</button>
                                                    <a href="<?php echo current_url();?>" class="btn btn-info text-white" >Reset</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="mb-3">
                                <a href="<?php echo admin_url('patients/form'); ?>" class="btn btn-primary waves-effect waves-themed"><i class="icon-plus"></i> <?php echo lang('add_new'); ?></a>
                            </div>
                            <div class="frame-wrap table-responsive-lg">
                                <table class="table table-bordered  table-hover m-0">
                                    <thead class="">
                                        <tr>
                                            <!-- <th>/#</th> -->
                                            <th>Action</th>
                                            <th><?php echo sort_field('firstname', lang('fullname')); ?></th>
                                            <th><?php echo sort_field('personal_id', lang('personal_id')); ?></th>
                                            <th><?php echo sort_field('email', lang('email')); ?></th>
                                            <th><?php echo sort_field('stauts', lang('status')); ?></th>
                                            <th><?php echo sort_field('added_by', lang('doctors')); ?></th>
                                            <th><?php echo sort_field('phone', lang('phone')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($lists)) {
                                            echo '<tr ><td colspan="6" align="center">' . lang('no_records') . '</td></tr>';
                                        }
                                        foreach ($lists as $list) : ?>
                                            <tr>
                                                <!-- <th scope="row">1</!-->
                                                <td>
                                                    <a href="<?php echo admin_url('patients/form/' . $list->id); ?>" class="btn btn-outline-primary btn-icon  waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>

                                                    <a href="<?php echo admin_url('patients/delete/' . $list->id); ?>" onclick="return confirm('<?php echo lang('confirm_delete'); ?>');" class="btn btn-outline-danger btn-icon  waves-effect waves-themed" data-toggle="tooltip" title="<?php echo lang('delete'); ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                                <td><?php echo $list->firstname . ' ' . $list->lastname;  ?></td>
                                                <td><?php echo $list->personal_id; ?></td>
                                                <td><?php echo $list->email; ?></td>
                                                <td><?php echo $list->status == 1 ? 'Active' : 'In-Active'; ?></td>
                                                <td><?php 
                                                if($list->added_by != Null){
                                                    $doctor = $this->Common_model->get_tbl_row('users',$list->added_by);
                                                    echo $doctor->firstname. ' '. $doctor->lastname;
                                                }
                                                ?></td>
                                                <td><?php echo $list->phone; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>

                            <?php echo pagination_get($total_rows, $perpage); ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>