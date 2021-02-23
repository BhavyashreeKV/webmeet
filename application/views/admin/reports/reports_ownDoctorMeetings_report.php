<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><?php echo lang('reports'); ?></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="row">

            <div class="col-12 animated fadeIn" id="listallmeetings">
                <div class="card">
                    <div class="card-header">
                        <?php echo $page_title; ?>
                        <div class="card-header-actions">
                            <div class="card-header-action">
                                <?php echo $default_text; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="callout callout-info">
                              <small class="text-muted">Total Meetings</small>
                              <br>
                              <strong class="h4"> <?php echo $card_datas['total_meetings']; ?> </strong>
                              <div class="chart-wrapper">
                                <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                              </div>
                            </div>
                          </div>
                          <!-- /.col-->
                          <div class="col-sm-6">
                            <div class="callout callout-danger">
                              <small class="text-muted">Cancelled Meetings</small>
                              <br>
                              <strong class="h4"><?php echo $card_datas['cancelled']; ?></strong>
                              <div class="chart-wrapper">
                                <canvas id="sparkline-chart-2" width="100" height="30"></canvas>
                              </div>
                            </div>
                          </div>
                          <!-- /.col-->
                        </div>
                        <!-- /.row-->
                      </div>
                      <!-- /.col-->
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="callout callout-warning">
                              <small class="text-muted">Missed Meetings</small>
                              <br>
                              <strong class="h4"><?php echo $card_datas['missed']; ?></strong>
                              <div class="chart-wrapper">
                                <canvas id="sparkline-chart-3" width="100" height="30"></canvas>
                              </div>
                            </div>
                          </div>
                          <!-- /.col-->
                          <div class="col-sm-6">
                            <div class="callout callout-success">
                              <small class="text-muted">Completed Meetings</small>
                              <br>
                              <strong class="h4"><?php echo $card_datas['completed']; ?></strong>
                              <div class="chart-wrapper">
                                <canvas id="sparkline-chart-4" width="100" height="30"></canvas>
                              </div>
                            </div>
                          </div>
                          <!-- /.col-->
                        </div>
                        <!-- /.row-->
                      </div>
                      <!-- /.col-->
                    </div>
                    <!-- /.row-->
                    <br>
                        <div class="row">
                            <div class="col-12">
                                <form action="<?php echo current_url_with_get(); ?>#listallmeetings" class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <label>Start Date</label>
                                        <div class="form-group"><input type="text" id="startdate" name="start_date" class="form-control form-control-sm datepicker" value="<?php echo isset($_GET['start_date'])?$_GET['start_date']:''; ?>"></div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <label>End date</label>
                                        <div class="form-group"><input type="text" id="enddate" name="end_date" class="form-control form-control-sm datepicker" value="<?php echo isset($_GET['end_date'])?$_GET['end_date']:''; ?>"></div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <?php $re_status = config_item('srch_status');
                                            echo form_dropdown('st', $re_status, isset($_GET['st']) ? $_GET['st'] : '', 'class="form-control form-control-sm"'); ?>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-12 mb-4">
                                        <button class="btn btn-primary mt-4" type="submit">Search</button>
                                        <a href="<?php echo current_url(); ?>#listallmeetings" class="btn btn-warning mt-4"> Reset </a>
                                        <button class="btn btn-secondary mt-4" type="submit" name="export" value="excel" id="excelBtn">Excel</button>
                                        <a href="<?php echo admin_url('reports/doctor_meetings'); ?>#listallmeetings" class="btn bg-purple text-white mt-4"> Back </a>
                                        
                                    </div>
                                </form>
                            </div>
                            <div class="col-12  table-responsive">
                                <table class="table table-hover table-bordered" id="posts">
                                    <thead>
                                        <!-- <th>Action</th> -->
                                        <th>Meeting Id</th>
                                        <th>Created Date</th>
                                        <th>Booking Date</th>
                                        <th>Meeting Time</th>
                                        <th><?php echo lang('doctors'); ?> Name</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($doc_meetings)){echo '<tr><td class="text-center" colspan="6">No Records found</td></tr>';}
                                        foreach ($doc_meetings as $meet) { ?>
                                            <tr>
                                                <!-- <td><input type="checkbox"></td> -->
                                                <td><?php echo $meet->meeting_id; ?></td>
                                                <td><?php echo $meet->added_date; ?></td>
                                                <td><?php echo $meet->booking_date; ?></td>
                                                <td><?php echo date('H:i A', $meet->start_datetime) . ' to ' . date('H:i A', $meet->end_datetime); ?></td>
                                                <td><?php echo $meet->pat_fullname; ?></td>
                                                <td><?php echo $meet->status; ?></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <?php echo pagination_get($total_rows, $perpage); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>
</main>


<script>
    
    function selectedDate(e) {
        id = e.delegateTarget.attributes[1].value
        if (id == 'startdate') {
            $('#enddate').data("DateTimePicker").minDate(e.date);
        }
        if (id == 'enddate') {
            $('#startdate').data("DateTimePicker").maxDate(e.date);
        }

    }
</script>