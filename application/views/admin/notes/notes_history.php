<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item">Notes</li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="row">
            <div class="animated fadeIn col-md-12">
                <div class="animated fadeIn">
                    <div class="card">
                        <div class="card-header">
                            Notes History
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                         <input type="hidden" value="<?php echo $note_id; ?>" id="notesId">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="dtNotes"> 
                                            <thead>
                                                    <th data-orderable="false"><?php echo lang('action'); ?></th>
                                                    <th><?php echo lang('fullname'); ?></th>
                                                    <th><?php echo lang('added_date'); ?></th>
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
    $(document).ready(function () {
        var selected = [];
        start_date = $('#startdate').val();end_date = $('#enddate').val();
        
        
        NoteDt = $('#dtNotes').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [[ 2, 'DESC' ]],
            "ajax":{
                "url": "<?php echo admin_url('notes/notes_log') ?>",
                "dataType": "json",
                "type": "POST",
                "data": function ( d ) {
                /* d.startdate = $('#startdate').val();
                d.enddate = $('#enddate').val();
                d.patient = $('#filterPatient').val(); */
                d.note_id = $('#notesId').val();
                // d.custom = $('#myInput').val();
                // etc
                }
            },
            
           
	    "columns": [
            { "data": "action" },
            { "data": "fullname" },
            { "data": "added_date" },
            ]
        })
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