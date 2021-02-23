<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Logs</li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="row">
            <div class="animated fadeIn col-md-12">
                <div class="animated fadeIn">
                    <div class="card">
                        <div class="card-header">
                            Bank-Auth Logs
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
                                                <label>Personal Id</label>
                                            <select class="form-control form-control-sm select2" id="filterPid">
                                                <option value="">-- Select --</option>
                                                <?php foreach($pid_arra as $k => $fkey)
                                                {
                                                    echo '<option value="'.$k.'">'.$fkey.'</option>';
                                                } ?>
                                              
                                            </select></div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Status</label>
                                            <select class="form-control form-control-sm select2" id="filterstatus">
                                                <option value="">-- Select --</option>
                                                <?php foreach($status_arra as $fkey)
                                                    {
                                                        echo '<option  value="'.$fkey.'">'.$fkey.'</option>';
                                                    } ?>
                                            </select></div>
                                        </div>
                                        <div class="col-md-2 col-sm-12" >
                                            <button class="btn btn-warning mt-4" type="button" id="ResetBtn">Reset</button>
                                        </div>
                                    </div>      
                                     <div id='control_sh' style="font-weight: bold;">
                                          <input type="checkbox" class="hide_show"><span> Personal Id</span>
                                          <input type="checkbox" class="hide_show"><span> Meeting Id</span>
                                          <input type="checkbox" class="hide_show"><span> Status</span>
                                          <input type="checkbox" class="hide_show"><span> Auto-Start Token</span>
                                          <input type="checkbox" class="hide_show"><span> Request</span>
                                          <input type="checkbox" class="hide_show"><span> Response</span>
                                          <input type="checkbox" class="hide_show"><span> Error Log</span>
                                        </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="dtLogs"> 
                                            <thead>
                                                    <th>Personal Id</th>
                                                    <th>Meeting Id</th>
                                                    <th>Status</th>
                                                    <th>Auto-Start Token</th>
                                                    <th>Request</th>
                                                    <th>Response</th>
                                                    <th width=20%>Error Log</th>
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

<script>

    $(document).ready(function () {
        var selected = [];
        start_date = $('#startdate').val();end_date = $('#enddate').val();
        
        
        LogDt = $('#dtLogs').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 4, 'DESC' ]],
            "ajax":{
                "url": "<?php echo admin_url('logs/bankauth_log') ?>",
                "dataType": "json",
                "type": "POST",
                "data": function ( d ) {
                d.startdate = $('#startdate').val();
                d.enddate = $('#enddate').val();
                d.personal_id = $('#filterPid').val();
                d.status = $('#filterstatus').val();
                }
            },
            
           
        "columns": [
            { "data": "personal_id" },
            { "data": "meeting_id" },
            { "data": "status"},
            { "data": "autostarttoken" },
            { "data": "request" },
            { "data": "response"},
            { "data": "error_log" },
            ]
        })

    });
    $('.hide_show').on('change',function(){
        var hide = $(this).is(':checked');
        
        var ti = $(this).index(".hide_show");
        
        $('#dtLogs tr').each(function(){
            if(hide){
                $('td:eq(' + ti + ')',this).hide(100);
                $('th:eq(' + ti + ')',this).hide(100);
            }
            else{
                $('td:eq(' + ti + ')',this).show(100);
                $('th:eq(' + ti + ')',this).show(100);
            }
        });
    });

    $(document).on('change','#filterPid,#filterstatus',function(){
        LogDt.ajax.reload( null, false );
    })
    $('#ResetBtn').on('click',function(){
            $('#startdate,#enddate,#filterPid,#filterstatus,input[type=search]').val('');
            $(".select2").select2();
            LogDt.search('').draw();
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
            LogDt.ajax.reload( null, false );
        }
    }

    $(document).on('click','.view_error',function(){
        $('#log1'+$(this).attr('rel')).addClass('d-none');
        $('#e_log'+$(this).attr('rel')).removeClass('d-none');
    });

    $(document).on('click','.hide_error',function(){
     $('#log1'+$(this).attr('rel')).removeClass('d-none');
        $('#e_log'+$(this).attr('rel')).addClass('d-none');
    });
    

</script>
    
    <div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="koglogs" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="koglogs">Logs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="logsbody">
                    
                </div>
            </div>
        </div>
    </div>