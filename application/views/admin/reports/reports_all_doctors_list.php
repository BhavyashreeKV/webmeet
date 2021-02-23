<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><?php echo lang('reports'); ?></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="row">
            
                <div class="col-12 animated fadeIn">
                    <div class="card">
                        <div class="card-header">
                            <?php echo $page_title; ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                       
                                    <table class="table table-hover table-bordered" id="posts">
                                        <thead>
                                            <!-- <th>Action</th> -->
                                            <th><?php echo lang('doctors'); ?> Name</th>
                                            <th>Total Meetings Count</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            <?php 
                                             foreach($patients as $patient){ ?>
                                                <tr>
                                                    <!-- <td><input type="checkbox"></td> -->
                                                    <td><?php echo $patient->fullname; ?></td>
                                                    <td><?php echo $patient->total_meetings; ?></td>
                                                    <td>
                                                        <a href="<?php echo admin_url('reports/individual_doc_reports/'.$patient->user_id); ?>" class="btn btn-primary waves waves-effect" data-toggle="tooltip" data-original-title="View individual meetings reports">View Reports</a>
                                                        <a href="<?php echo admin_url('reports/own_meetings/'.$patient->user_id); ?>" class="btn btn-primary waves waves-effect" data-toggle="tooltip" data-original-title="View individual meetings reports">View Own Meetings</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                               
                                        </tbody>        
                                    </table>
                                </div>
                                <div class="col-12">
                                    <?php echo pagination_get($total_rows,$perpage); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</main>

