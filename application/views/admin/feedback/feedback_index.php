<style type="text/css">
.dataTables_filter { position: relative; left: -40px; }
</style>
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
                            Feedbacks
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered" id="dtNotes"> 
                                            <thead>
                                                    <th data-orderable="false"><?php echo lang('action'); ?></th>
                                                    <th><?php echo lang('meeting_id'); ?></th>
                                                    <th><?php echo lang('meeting_date'); ?></th>
                                                    <th><?php echo lang('name'); ?></th>
                                                    <th data-orderable="false"><select name="category" id="category" class="form-control">
                                                        <option value="Doctor"><?php echo lang('doctors'); ?></option>
                                                        <option value="Patient">Patient</option>
                                                    </select>
                                                    </th>
                                                    <th><?php echo lang('rating'); ?></th>
                                                    <th><?php echo lang('review'); ?></th>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                            <tfoot>
                                                    <th data-orderable="false"><?php echo lang('action'); ?></th>
                                                    <th><?php echo lang('meeting_id'); ?></th>
                                                    <th><?php echo lang('meeting_date'); ?></th>
                                                    <th><?php echo lang('name'); ?></th>
                                                    <th><?php echo lang('type'); ?></th>
                                                    <th><?php echo lang('rating'); ?></th>
                                                    <th><?php echo lang('review'); ?></th>
                                            </tfoot>
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
       
    
    $(document).on('click','.delNotes',function(){
        var postURL = $(this).attr('rel');
        if(confirm('Are you sure you want to delete this note?'))
        {
            $.post(postURL,function(resp){
                if(resp == 1)
                {
                    category = $('#category').val();
                    $('#dtNotes').DataTable().destroy();
                    load_data(category);
                    notify('Your Notes deleted successfully');
                }
            });
        }
        else
        {
            
            return false;
        }
    });
    load_data();
    function load_data(is_category)
    {
    var dataTable = $('#dtNotes').DataTable({
    "processing":true,
    "serverSide":true,
    "order": [[ 4, 'DESC' ]],
    "ajax":{
        url:"<?php echo admin_url('feedback/all_feedbacks') ?>",
        type:"POST",
        data:{is_category:is_category}
    },
    "columns": [
            { "data": "action" },
            { "data": "meeting_id" },
            { "data": "booking_date" },
            { "data": "fullname" },
            { "data": "type" },
            { "data": "rating" },
            { "data": "review" },
            ],
    "columnDefs":[
        {
        "targets":[2],
        "orderable":false,
        },
    ],
    });
    }

 $(document).on('change', '#category', function(){
  var category = $(this).val();
  $('#dtNotes').DataTable().destroy();
  if(category != '')
  {
    load_data(category);
  }
  else
  {
    load_data();
  }
 });
});
</script>


    