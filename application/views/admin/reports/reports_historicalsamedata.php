<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><?php echo lang('reports'); ?></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="alert">
            <h4><i class="fa fa-info-circle"></i> Information</h4>
            This page shows the count of the same <?php echo lang('doctors'); ?> and patients for past 3 months in a row.
        </div>
        <div class="row">
            
                <div class="col-12 animated fadeIn">
                    <div class="card">
                        <div class="card-header">
                            Historical Same Meeting in last 3 months
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                       
                                    <table class="table table-hover table-bordered" id="posts">
                                        <thead>
                                            <!-- <th>Action</th> -->
                                            <th><?php echo lang('doctors'); ?> Name</th>
                                            <th>Patients Name</th>
                                            <th>No Of Total Meetings</th>
                                        </thead>
                                        <tbody>
                                            <?php if(empty($lists)){echo '<tr><td class="text-center" colspan="3">No Record Found</td></tr>';}
                                            foreach($lists as $meet){ ?>
                                            <tr>
                                                <td><?php echo $meet->doc_fullname; ?></td>
                                                <td><?php echo $meet->pat_fullname; ?></td>
                                                <td><h5><span class="badge badge-pill badge-success"><?php echo $meet->total_meeting; ?></span></h5></td>
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
    </div>
</main>

