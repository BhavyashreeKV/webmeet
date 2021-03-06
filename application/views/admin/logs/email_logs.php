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
                            Email Logs
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
                                                <label><?php echo lang('from'); ?></label>
                                            <select class="form-control form-control-sm select2" id="filterFrom">
                                                <option value="">-- Select --</option>
                                                <?php foreach($from_arra as $k => $fkey)
                                                {
                                                    echo '<option value="'.$k.'">'.$fkey.'</option>';
                                                } ?>
                                              
                                            </select></div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo lang('to'); ?></label>
                                            <select class="form-control form-control-sm select2" id="filterTo">
                                                <option value="">-- Select --</option>
                                                <?php foreach($to_arra as $fkey)
                                                    {
                                                        echo '<option  value="'.$fkey.'">'.$fkey.'</option>';
                                                    } ?>
                                            </select></div>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            <button class="btn btn-warning mt-4" type="button" id="ResetBtn">Reset</button>
                                        </div>
                                    </div>      
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="dtLogs"> 
                                            <thead>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th >Status</th>
                                                    <th>Subject</th>
                                                    <th>Content</th>
                                                    <th>Error Log</th>
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
                "url": "<?php echo admin_url('logs/email_log') ?>",
                "dataType": "json",
                "type": "POST",
                "data": function ( d ) {
                d.startdate = $('#startdate').val();
                d.enddate = $('#enddate').val();
                d.from = $('#filterFrom').val();
                d.to = $('#filterTo').val();
                }
            },
            
           
        "columns": [
            { "data": "from" },
            { "data": "to" },
            { "data": "send"},
            { "data": "subject" },
            { "data": "content" },
            { "data": "error_log" },
            ]
        })
    });
    $(document).on('change','#filterFrom,#filterTo',function(){
        LogDt.ajax.reload( null, false );
    })
    $('#ResetBtn').on('click',function(){
            $('#startdate,#enddate,#filterFrom,#filterTo,input[type=search]').val('');
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

    $(document).on('click','.view_content',function(){
        $('#log'+$(this).attr('rel')).addClass('d-none');
        $('#d_log'+$(this).attr('rel')).removeClass('d-none');
    });

    $(document).on('click','.view_error',function(){
        $('#log1'+$(this).attr('rel')).addClass('d-none');
        $('#e_log'+$(this).attr('rel')).removeClass('d-none');
    });

    $(document).on('click','.hide_content',function(){
        $('#log'+$(this).attr('rel')).removeClass('d-none');
        $('#d_log'+$(this).attr('rel')).addClass('d-none');
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