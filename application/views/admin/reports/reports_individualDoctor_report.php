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
                    <?php echo lang('doctors'); ?> Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="text-center">
                                    <h4><?php echo lang('doctors'); ?> Profile Details</h4>
                                    <figure>
                                        <img class="img-circle" width="200" height="200" src="<?php echo get_user_porfileimage($user->id); ?>">
                                    </figure>
                                    <div class="text-muted small text-uppercase font-weight-bold">Patient Full Name</div>
                                    <div class="text-value pb-3"><?php echo $user->firstname . ' ' . $user->lastname; ?></div>
                                  
                                    <div class="d-none d-lg-block">
                                    <div class="text-muted small text-uppercase font-weight-bold">Email</div>
                                    <div class=" pb-2"><?php echo $user->email; ?></div>
                                     <div class="text-muted small text-uppercase font-weight-bold">Personal ID</div>
                                    <div class=" pb-2"><?php echo $user->personal_id; ?></div>
                                    <!--<div class="text-muted small text-uppercase font-weight-bold">Contact no</div>
                                    <div class=" pb-2"><?php echo $user->phone; ?></div> -->
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 col-sm-6 d-none d-md-block d-lg-block">
                                <div class="text-center">
                                    <h4 class="pb-2">Meeting Details</h4>
                                    <div class="text-muted small text-uppercase font-weight-bold">Total Meetings</div>
                                    <div class="text-value pb-3"><?php echo $grp_status['all_meetings']; ?></div>
                                    <div class="text-muted small text-uppercase font-weight-bold">Completed</div>
                                    <div class="text-value pb-3"><?php echo $grp_status['completed']; ?></div>
                                    <div class="text-muted small text-uppercase font-weight-bold">Upcoming</div>
                                    <div class="text-value pb-3"><?php echo $grp_status['upcoming']; ?></div>
                                    <div class="text-muted small text-uppercase font-weight-bold">Missed</div>
                                    <div class="text-value pb-3"><?php echo $grp_status['missed']; ?></div>
                                    <div class="text-muted small text-uppercase font-weight-bold">Cancelled </div>
                                    <div class="text-value pb-3"><?php echo $grp_status['cancelled']; ?></div>
                                </div>

                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="chart-wrapper">
                                    <canvas id="canvas-5"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>Patient List</label>
                                            <select class="form-control form-control-sm select2" name="pt" id="filterDoctor">
                                                <?php foreach ($doctors as $dkey => $dval) {
                                                    $selected = (isset($_GET['pt']) && $_GET['pt'] == $dkey)?'selected':"";
                                                    echo '<option value="' . $dkey . '" '.$selected.'>' . $dval . '</option>';
                                                } ?>
                                            </select></div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 mb-4">
                                        <button class="btn btn-primary mt-4" type="submit">Search</button>
                                        <a href="<?php echo current_url(); ?>#listallmeetings" class="btn btn-warning mt-4"> Reset </a>
                                        <button class="btn btn-secondary mt-4" type="submit" name="export" value="excel" id="excelBtn">Excel</button>
                                        
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <table class="table table-hover table-bordered" id="posts">
                                    <thead>
                                        <!-- <th>Action</th> -->
                                        <th>Meeting Id</th>
                                        <th>Booking Date</th>
                                        <th>Meeting Time</th>
                                        <th>Patients Name</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($all_pat_meetings as $meet) { ?>
                                            <tr>
                                                <!-- <td><input type="checkbox"></td> -->
                                                <td><?php echo $meet->meeting_id; ?></td>
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


<script src="<?php echo template_assets(); ?>vendors/chartjs/js/Chart.min.js"></script>
<script src="<?php echo template_assets(); ?>vendors/_coreui/coreui-plugin-chartjs-custom-tooltips/js/custom-tooltips.min.js"></script>
<script>
    $(function() {
        var pieChart = new Chart($('#canvas-5'), {
            type: 'pie',
            data: {
                labels: ['Completed', 'Cancelled', 'Upcoming', 'Missed'],
                datasets: [{
                    data: <?php echo $piegraphData; ?>,
                    backgroundColor: ['#188a71', '#FF6384', '#36A2EB', '#FFCE56'],
                    hoverBackgroundColor: ['#188a71', '#FF6384', '#36A2EB', '#FFCE56']
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Status-wise Individual Report'
                },
            }
        });
    });

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