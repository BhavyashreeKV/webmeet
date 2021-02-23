<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Openvidu Current Sessions</li>
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
                    <span class="float-right">Total Row <span class="fw-300"><i><?php echo $all_sessions['result']->numberOfElements; ?></i></span></span>
                    
                </div>
                    <div class="card-body">
                        
                        <div class="frame-wrap table-responsive-lg">
                            <table class="table table-bordered  table-hover m-0">
                                <thead class="">
                                    <tr>
                                        <!-- <th>/#</th> -->
                                        <th>Session Id</th>
                                        <th>Connection Details</th>
                                        <th>Created Date</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(empty($all_sessions['result']->content)){echo '<tr colspan="6"><td>'.lang('no_records'). '</td></tr>';} 
                                    foreach($all_sessions['result']->content as $sess): ?>
                                    <tr>
                                        <td><?php echo $sess->sessionId; ?></td>
                                        <td>
                                            <?php if($sess->connections->numberOfElements > 0){
                                                $connections = $sess->connections;
                                                foreach($connections->content as $con)
                                                { ?>
                                                <div class="border-bottom">
                                                    <div>Connection Start time : <?php echo date('d-m-Y H:i:s',($con->createdAt/1000));  ?></div>
                                                    <div>Platform : <?php echo $con->platform;  ?></div>
                                                    <div>Role : <?php echo $con->role;  ?></div>
                                                    <div>Server Data : <?php echo $con->serverData;  ?></div>
                                                    <div>Client Data : <?php echo $con->clientData;  ?></div>
                                                </div>
                                          <?php }
                                            } ?>
                                        </td>
                                        <td><?php echo date('d-m-Y H:i:s',($sess->createdAt/1000));  ?></td>
                                        <!-- <td><a href="<?php //echo admin_url('openvidu/remove_session/'.$sess->sessionId); ?>" class="btn btn-rounded btn-outline-danger">Remove</a></td> -->
                                    </tr>
                                <?php endforeach; ?>                                    
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