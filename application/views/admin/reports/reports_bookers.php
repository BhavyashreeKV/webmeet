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
                            All Meetings
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                       
                                    <table class="table table-hover table-bordered" id="posts">
                                        <thead>
                                            <!-- <th>Action</th> -->
                                            <th>Bookers Name</th>
                                            <th>Meetings Created for Last 7 Days</th>
                                            <th>Meetings Created for Last 30 Days</th>
                                            <th>Total Meetings</th>
                                        </thead>
                                        <tbody>
                                            <?php $sum_last7days = $sum_last30days = $sum_alltotal = 0;
                                             foreach($bookers_list as $list){ ?>
                                                <tr>
                                                    <!-- <td><input type="checkbox"></td> -->
                                                    <td><?php echo $list->fullname; ?></td>
                                                    <td><?php echo $list->last7days; $sum_last7days = $sum_last7days + $list->last7days; ?></td>
                                                    <td><?php echo $list->last30days; $sum_last30days = $sum_last30days + $list->last30days; ?></td>
                                                    <td><?php echo $list->total; $sum_alltotal = $sum_alltotal + $list->total; ?></td>
                                                </tr>
                                            <?php } ?>
                                                <tr>
                                                    <!-- <th></th> -->
                                                    <th>All Total</th>
                                                    <th><?php echo $sum_last7days; ?></th>
                                                    <th><?php echo $sum_last30days; ?></th>
                                                    <th><?php echo $sum_alltotal; ?></th>
                                                </tr>
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

