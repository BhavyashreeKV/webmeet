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
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            <label>Start Date</label>
                                            <div class="form-group"><input type="text" id="startdate" class="form-control form-control-sm datepicker" value=""></div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <label>End date</label>
                                            <div class="form-group"><input type="text" id="enddate" class="form-control form-control-sm datepicker" value=""></div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label>Status</label>
                                            <select class="form-control form-control-sm select2" id="filterStatus">
                                                <option value="">-- Select --</option>
                                                <?php foreach(config_item('booking_status') as $k=>$v): ?>
                                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                                <?php endforeach; ?>
                                            </select></div>
                                        </div>
                                        <!-- <div class="col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label>Patient List</label>
                                            <select class="form-control form-control-sm select2" id="filterPatient">
                                                <?php foreach($patients as $pkey=>$pval)
                                                {
                                                    echo '<option value="'.$pkey.'">'.$pval.'</option>';
                                                } ?>
                                            </select></div>
                                        </div> -->
                                        <div class="col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo lang('doctors'); ?> List</label>
                                            <select class="form-control form-control-sm select2" id="filterDoctor">
                                                <?php foreach($doctors as $dkey=>$dval)
                                                    {
                                                        echo '<option value="'.$dkey.'">'.$dval.'</option>';
                                                    } ?>
                                            </select></div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 mb-4">
                                            <button class="btn btn-warning mt-4" type="button" id="ResetBtn" onclick="$('#exportVal').val('')">Reset</button>
                                            <!-- <button class="btn btn-secondary mt-4" type="button" id="excelBtn" onclick="$('#exportVal').val('Excel')">Excel</button> -->
                                            <!-- <button class="btn btn-secondary mt-4" type="button" id="pdfBtn" onclick="$('#exportVal').val('PDF')">PDF</button> -->
                                            <input type="hidden" id="exportVal" value="">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="posts">
                                            <thead>
                                                <th width="2%" data-orderable="false">Action</th>
                                                <th>Meeting Date</th>
                                                <th>Meeting Time</th>
                                                <th>Status</th>
                                                <th>Behandlare</th>
                                                <th>Participants Count</th>
                                                <!-- <th>Id</th> -->
                                                <th>Meeting Id</th>
                                            </thead>
                                        </table>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="participant_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Participants Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <th>Personal Id</th>
                    <th>Phone No</th>
                    <th>Email</th>
                    <th>Participant Name</th>
                    <th>BankId Login</th>
                    <th>Session Attend ?</th>
                    <th>Chat Disabled</th>
                </thead>
                <tbody id="participant_data">
                    <tr>
                        <td>1987654320</td>
                        <td>9875621445</td>
                        <td>mail@mail.com</td>
                        <td>Username</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
   

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
        allmeetingDt = $('#posts').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 1, 'DESC' ]],
            "ajax":{
		     "url": "<?php echo admin_url('reports/group_meeting_data') ?>",
		     "dataType": "json",
		     "type": "POST",
		     "data": function ( d ) {
                d.startdate = $('#startdate').val();
                d.enddate = $('#enddate').val();
                d.status = $('#filterStatus').val();
                d.patient = $('#filterPatient').val();
                d.doctor = $('#filterDoctor').val();
                d.export = $('#exportVal').val();
                },
                
                
        "dataSrc": function ( json ) {
                //Make your callback here.
                if(typeof(json.filepath) != "undefined")
                    {
                        var a = document.createElement('a');
                        var binaryData = [];
                        binaryData.push(json.filepath);
                        window.URL.createObjectURL(new Blob(binaryData, {type: "application/zip"}))
                        a.href = json.filepath;
                        a.download = json.filename;
                        document.body.append(a);
                        a.click();
                        a.remove();
                        // return false;
                        // window.URL.revokeObjectURL(url);
                        $('#exportVal').val('')
                        allmeetingDt.ajax.reload( null, false );
                    } 
                    else
                    {
                        return json.data;
                    }
            } 
		    },
	    "columns": [
		          { "data": "action" },
		          { "data": "booking_date" },
		          { "data": "meeting_time" },
		          { "data": "status" },
		          { "data": "doctor_fullname" },
		          { "data": "participant_count" },
		        //   { "data": "id" },
		          { "data": "meeting_id" },
               ],
               "drawCallback": function( settings ) {
                    /* alert( 'DataTables has redrawn the table' ); */
                    console.log(settings.json);
                   
                    $('#exportVal').val('')
                }	 

        });
    
    });
    $(document).on('change','#filterPatient,#filterDoctor,#filterStatus',function(){
        allmeetingDt.ajax.reload( null, false );
    })
    $('#ResetBtn').on('click',function(){
            $('#startdate,#enddate,#filterPatient,#filterDoctor,#filterStatus,input[type=search]').val('');
            $(".select2").select2();
            allmeetingDt.search('').draw();
        })
        $('#excelBtn,#pdfBtn').on('click',function(){
            allmeetingDt.ajax.reload( null, false );
        })
    function selectedDate(e)
    {
        id = e.delegateTarget.attributes[1].value
        if(id == 'startdate')
        {
            $('#enddate').data("DateTimePicker").minDate(e.date);
        }
        if(id == 'enddate')
        {
            $('#startdate').data("DateTimePicker").maxDate(e.date);
        }
        if($('#startdate').val() != '' && $('#enddate').val() != '')
        {
            allmeetingDt.ajax.reload( null, false );
        }
    }

    $(document).on('click','.participant',function(){
        URL = '<?php echo admin_url('reports/prt_detials/'); ?>'+$(this).data('id');
        $.post(URL,function(resp){
            $('#participant_data').html(resp);
            $('#participant_details').modal('show');
        });

    });

</script>