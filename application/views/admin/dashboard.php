
      <main class="main">
        <!-- Breadcrumb-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">Home</li>
          <li class="breadcrumb-item">
            <a href="#">Admin</a>
          </li>
          <li class="breadcrumb-item active">Dashboard</li>
          
          <li class="breadcrumb-menu d-md-down-none">
            <div class="btn-group" role="group" aria-label="Button group">
              <a class="btn" href="#">
                <i class="icon-speech"></i>
              </a>
              <a class="btn" href="./">
                <i class="icon-graph"></i>  Dashboard</a>
              <a class="btn" href="#">
                <i class="icon-settings"></i>  Settings</a>
            </div>
          </li>
        </ol>

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-primary">
                  <div class="card-body pb-0">
                    
                    <div class="text-value"><?php echo $total_meetings; ?></div>
                    <div>Total Meetings</div>
                  </div>
                  <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                    <canvas class="chart" id="card-chart1" height="70"></canvas>
                  </div>
                </div>
              </div>
              <!-- /.col-->
              <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-info">
                  <div class="card-body pb-0">
                    <button class="btn btn-transparent p-0 float-right" type="button">
                      <i class="icon-location-pin"></i>
                    </button>
                    <div class="text-value"><?php echo $total_todays_meeting; ?></div>
                    <div>Total Todays Meetings</div>
                  </div>
                  <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                    <canvas class="chart" id="card-chart2" height="70"></canvas>
                  </div>
                </div>
              </div>
              <!-- /.col-->
              <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-warning">
                  <div class="card-body pb-0">
                    
                    <div class="text-value"><?php echo $total_upcoming_meeting; ?></div>
                    <div>Total Upcoming Meetings</div>
                  </div>
                  <div class="chart-wrapper mt-3" style="height:70px;">
                    <canvas class="chart" id="card-chart3" height="70"></canvas>
                  </div>
                </div>
              </div>
              <!-- /.col-->
              <div class="col-sm-6 col-lg-3">
                <div class="card text-white bg-danger">
                  <div class="card-body pb-0">
                    
                    <div class="text-value"><?php echo $total_cancelled_meeting; ?></div>
                    <div>Total Cancelled Meetings</div>
                  </div>
                  <div class="chart-wrapper mt-3 mx-3" style="height:70px;">
                    <canvas class="chart" id="card-chart4" height="70"></canvas>
                  </div>
                </div>
              </div>
              <!-- /.col-->
            </div>
            
           
            <!-- /.row-->
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">Latest Meetings</div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-6">
                            <div class="callout callout-info">
                              <small class="text-muted">This Weeks Meetings</small>
                              <br>
                              <strong class="h4"><?php echo $total_thisweek_meeting; ?></strong>
                              <div class="chart-wrapper">
                                <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
                              </div>
                            </div>
                          </div>
                          <!-- /.col-->
                          <div class="col-sm-6">
                            <div class="callout callout-danger">
                              <small class="text-muted">Missed Meetings</small>
                              <br>
                              <strong class="h4"><?php echo $total_missed_meeting; ?></strong>
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
                              <small class="text-muted">New Feedbacks</small>
                              <br>
                              <strong class="h4">3</strong>
                              <div class="chart-wrapper">
                                <canvas id="sparkline-chart-3" width="100" height="30"></canvas>
                              </div>
                            </div>
                          </div>
                          <!-- /.col-->
                          <div class="col-sm-6">
                            <div class="callout callout-success">
                              <small class="text-muted">Total Completed Meetings</small>
                              <br>
                              <strong class="h4"><?php echo $total_completed_meeting; ?></strong>
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
                    <table class="table table-responsive-sm table-hover table-outline mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th class="text-center">
                            <i class="icon-people"></i>
                          </th>
                          <th>Doctor</th>
                          <th class="text-center">
                            <i class="icon-people"></i>
                          </th>
                          <th>Patients</th>
                          <th>Meeting Date</th>
                          <th>Status</th>
                          <!-- <th>Activity</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(empty($thisweek_meeting)){echo '<tr><td class="text-center" colspan="8">Currently no Meetings</td></tr>';}   ?>
                      <?php foreach($thisweek_meeting as $thisweek){ ?>
                        <tr>
                          <td class="text-center">
                            <div class="avatar">
                              <img class="img-avatar" style="width:35px;height:35px" src="<?php echo get_user_porfileimage($thisweek->doctor_id); ?>" alt="">
                              <span class="avatar-status badge-success"></span>
                            </div>
                          </td>
                          <td>
                            <div><?php echo $doctors[$thisweek->doctor_id]; ?></div>
                            <div class="small text-muted">
                              <!-- <span>New</span> | Registered: Jan 1, 2015</div> -->
                          </td>
                          <td class="text-center">
                            <?php if(isset($thisweek->patient_id)){ ?>
                            <div class="avatar">
                              <img class="img-avatar" src="<?php echo get_user_porfileimage($thisweek->patient_id); ?>" style="width:35px;height:35px" alt="">
                              <span class="avatar-status badge-success"></span>
                            </div>
                            <?php } ?>
                          </td>
                          <td>
                          <?php if(isset($thisweek->patient_id)){ ?>
                            <div><?php echo $patients[$thisweek->patient_id]; ?></div>
                            <div class="small text-muted">
                              <!-- <span>New</span> | Registered: Jan 1, 2015</div> -->
                              <?php } ?>
                          </td>
                          <td>
                            <?php echo date('d M, Y',strtotime($thisweek->booking_date)); ?>
                          </td>
                          <?php $color = 'primary';
                          if($thisweek->status == 'rescheduled'){$color = 'warning';}
                          else if($thisweek->status == 'missed'||$thisweek->status == 'cancelled'){$color="danger";}
                          else if($thisweek->status == 'completed'){$color = 'success';}
                          ?>
                          <td>
                            <span class="badge badge-<?php echo $color; ?>"><?php echo ucfirst($thisweek->status); ?></span>
                          </td>
                         <!--  <td>
                            <button class="btn btn-rounded btn-primary">Update</button>
                          </td> -->
                        </tr>
                      <?php } ?>
                                               
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- /.col-->
            </div>
            <!-- /.row-->
          </div>
        </div>
      </main>
      