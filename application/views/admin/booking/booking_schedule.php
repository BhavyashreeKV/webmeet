<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Doctors</li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="row">
            <div class="animated fadeIn col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo current_url(); ?>" method="post">
                            <div class="row">
                                <?php if($b_id){ ?>
                                <div class="col-sm-12">
                                    <p class="lead">Meeting Id: <strong><?php echo $meeting_id; ?></strong></p>
                                </div>
                                <?php } ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="startdate">Date</label> 
                                        <input class="form-control dfdatepicker" name="booking_date" id="booking_date" type="text" placeholder="Enter Date" value="<?php echo set_value('booking_date',$booking_date); ?>" autocomplete="off" onblur="get_doc_meet()">
                                        <?php if($id){ echo '<br><span class="badge badge-secondary">Old Date - '.$booking_date.'</span>'; } ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="startdate">Start Time</label>
                                        <input class="form-control dftimepicker" name="startdate" id="starttime" type="text" placeholder="Enter start time" autocomplete="off" value="<?php echo set_value('startdate',$start_datetime); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="enddate">End Time</label>
                                        <input class="form-control dftimepicker" name="enddate" type="text" id="endtime" placeholder="Enter end time" autocomplete="off" value="<?php echo set_value('enddate',$end_datetime); ?>">
                                    </div>
                                </div>
                                <?php if($id){ ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name">Status</label>
                                        <?php echo form_dropdown('status', config_item('re_status'), $status, 'class="form-control"'); ?>
                                        <?php if($id){ echo '<br><span class="badge badge-info">Old Status - '.ucfirst($status).'</span>'; } ?>
                                    </div>
                                </div>
                                <?php }else
                                {
                                    echo form_hidden('status','new');
                                } ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name"><?php echo lang('doctors'); ?></label>
                                        <?php echo form_dropdown('doctor_id',$all_doctors,$doctor_id,'id="doctor_id" class="form-control select2"'); ?>
                                        <!-- <select class="form-control" name="doctor_id" id="doctor_id">
                                            <option value="">Select Doctor</option>
                                            <?php foreach ($all_doctors as $key => $val) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                            <?php } ?>
                                        </select> -->
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="name">Patient</label>
                                        <?php echo form_dropdown('patient_id',$all_patients,$patient_id,'id="patient_id" class="form-control select2"'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group pt-4">
                                        <input type="checkbox" id="noti" name="notify" value="1" checked>
                                        <label for="noti">Send Notification <span class="fa fa-info-circle text-info" data-toggle="tooltip" data-original-title="Send Email and SMS notification to behandlare and patient"></span></label>
                                    </div>
                                </div>

                            </div>
                            <div class="form-action float-right">
                                <a href="<?php echo admin_url('bookings'); ?>" class="btn btn-secondary waves-effect waves-themed " data-toggle="tooltip" data-original-title="click to reset">Reset</a>
                                <button class="btn btn-success ml-auto waves-effect waves-themed" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="animated fadeIn">
                    <div class="card">
                        <div class="card-header">
                            Last Scheduled Meetings
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-bordered" id="posts">
                                        <thead>
                                            <th data-orderable="false">Action</th>
                                            <th>Meeting Date</th>
                                            <th>Meeting Time</th>
                                            <th>Status</th>
                                            <th>Behandlare</th>
                                            <th>Patient</th>
                                            <th>Id</th>
                                            <th>Meeting Id</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  <div class="animated fadeIn">
                    <div class="card">
                        <div class="card-header">
                            <i class="icon-calendar"></i> Schedule an Meeting
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="animated fadeIn col-md-4">
                <div class="card">
                    <div class="card-header">
                        List Of Doctors Meetings
                    </div>
                    <div class="card-body">
                        <h3 id="docname"></h3>

                        <ul class="list-group">


                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $(function() {
        <?php if($b_id){ ?>
            get_doc_meet();
        <?php } ?>
        $('#doctor_id').on('change', function() {
            get_doc_meet();
        });
        $('#booking_date').on('change', function() {
            get_doc_meet();
        });

    });

    $('#noti').on('click',function(){
        if($(this).prop("checked") == false){
            return confirm('Are you sure? Unchecking it will not send notification!')
        }
    });

    function get_doc_meet() {
        var booking_date = $('#booking_date').val();
        var doc = $('#doctor_id').val();
        if (doc != '' && booking_date != '') {
            // alert(stdate+':s'+doc);
            $.post('<?php echo admin_url('bookings/get_doctors_meeting'); ?>', {
                bdate: booking_date,
                doc: doc
            }, function(data) {
                JSONdata = JSON.parse(data)
                $('#docname').html(JSONdata.doctor);
                $('.list-group').html(JSONdata.list);
            });
        }

    }

    function areyousure()
    {
        if(confirm('<?php echo lang('booking_confirm_delete'); ?>'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('#posts').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 1, 'DESC' ]],
            "ajax":{
		     "url": "<?php echo admin_url('bookings/posts') ?>",
		     "dataType": "json",
		     "type": "POST",
		     "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
		                   },
	    "columns": [
		          { "data": "action" },
		          { "data": "booking_date" },
		          { "data": "meeting_time" },
		          { "data": "status" },
		          { "data": "doctor_fullname" },
		          { "data": "patient_fullname" },
		          { "data": "id" },
		          { "data": "meeting_id" },
		       ],	 
               "drawCallback": function( settings ) {
                     $('[data-toggle="tooltip"]').tooltip();
                }	 

        });
        
    });
</script>