<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Meetings</li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="row">
            <div class="animated fadeIn col-md-12">
                <div class="animated fadeIn">
                    <div class="card">
                        <div class="card-header">
                            All Notes
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
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Patient List</label>
                                            <select class="form-control form-control-sm select2" id="filterPatient">
                                                <?php foreach($patients as $pkey=>$pval)
                                                {
                                                    echo '<option value="'.$pkey.'">'.$pval.'</option>';
                                                } ?>
                                            </select></div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo lang('doctors'); ?> List</label>
                                            <select class="form-control form-control-sm select2" id="filterDoctor">
                                                <?php foreach($doctors as $dkey=>$dval)
                                                    {
                                                        echo '<option value="'.$dkey.'">'.$dval.'</option>';
                                                    } ?>
                                            </select></div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <button class="btn btn-warning mt-4" type="button" id="ResetBtn">Reset</button>
                                        </div>
                                    </div>      
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="dtNotes"> 
                                            <thead>
                                                    <th data-orderable="false"><?php echo lang('action'); ?></th>
                                                    <th><?php echo lang('doctor_name'); ?></th>
                                                    <th><?php echo lang('patient_name'); ?></th>
                                                    <th><?php echo lang('meeting_id'); ?></th>
                                                    <th><?php echo lang('meeting_date'); ?></th>
                                                    <th width="40%"><?php echo lang('notes'); ?></th>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
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
            
        </div>
    </div>
</main>
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
        var selected = [];
        start_date = $('#startdate').val();end_date = $('#enddate').val();
        
        
        NoteDt = $('#dtNotes').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 4, 'DESC' ]],
            "ajax":{
                "url": "<?php echo admin_url('notes/behandlareNotes') ?>",
                "dataType": "json",
                "type": "POST",
                "data": function ( d ) {
                d.startdate = $('#startdate').val();
                d.enddate = $('#enddate').val();
                d.patient = $('#filterPatient').val();
                d.doctor = $('#filterDoctor').val();
                // d.custom = $('#myInput').val();
                // etc
                }
            },
            
           
	    "columns": [
            { "data": "action" },
            { "data": "doctor_fullname" },
            { "data": "patient_fullname" },
            { "data": "meeting_id" },
            { "data": "booking_date" },
            { "data": "notes" },
            ]
        })
    });
    $(document).on('click','.notesBtn,.eNotes',function()
    {
        var meeting_id = $(this).data('meeting_id');
        $.post('<?php echo admin_url(); ?>notes/get_notes/'+meeting_id, function(response) {
            $('#notesbody').html(response)
        $('#notesModal').modal("show");
        });
    });
    $(document).on('change','#filterPatient,#filterDoctor',function(){
        NoteDt.ajax.reload( null, false );
    })
    $(document).on('click','.delNotes',function(){
        var postURL = $(this).attr('rel');
        if(confirm('Are you sure you want to delete this note?'))
        {
            $.post(postURL,function(resp){
                notify('Your Notes deleted successfully');
                NoteDt.ajax.reload( null, false );
            });
        }
        else
        {
            
            return false;
        }
       
    });
    $('#ResetBtn').on('click',function(){
            $('#startdate,#enddate,#filterPatient,#filterDoctor,input[type=search]').val('');
            $(".select2").select2();
            NoteDt.search('').draw();
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
            NoteDt.ajax.reload( null, false );
        }
    }
    $(document).on('click','.view_notes',function(){
        // alert($(this).attr('rel'))
        $('#note'+$(this).attr('rel')).addClass('d-none');
        $('#d_note'+$(this).attr('rel')).removeClass('d-none');
        $.post('<?php echo admin_url('notes/update_viewedby'); ?>',{note_id:$(this).attr('rel')},function(resp){
            
        });
    });
    $(document).on('click','.hide_notes',function(){
        // alert($(this).attr('rel'))
        $('#note'+$(this).attr('rel')).removeClass('d-none');
        $('#d_note'+$(this).attr('rel')).addClass('d-none');
    });
    

</script>


    
    <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="kognotes" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="kognotes">Manage Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="notesbody">
                    
                </div>
            </div>
        </div>
    </div>