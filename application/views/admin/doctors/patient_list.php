<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><?php echo lang('doctors'); ?></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">

                <div class="col-lg-12 col-xl-12">
                    <!--Table bordered-->

                    <!--Table hover-->
                    <div class="card">
                        <div class="card-header">
                            <span><?php echo $page_title; ?></span>
                            <span class="float-right">Total Row <span class="fw-300"><i><?php echo $total_rows; ?></i></span></span>

                        </div>
                        <div class="card-body">
                            <div class="float-right mb-3">
                                <form class="form-inline" action="<?php echo current_url_with_get(); ?>">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <input class="form-control" id="input1-group2" type="text" name="term" placeholder="Search..." autocomplete="off" value="<?php echo isset($_GET['term'])?$_GET['term']:''; ?>">
                                                <span class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-search"></i> Search</button>
                                                    <a href="<?php echo current_url();?>" class="btn btn-info text-white" >Reset</a>
                                                    <a href="<?php echo admin_url('doctors');?>" class="btn btn-info text-white" >Back</a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="">
                                <a href="<?php echo admin_url('doctors/form'); ?>" class="btn btn-primary waves-effect waves-themed"><i class="icon-plus"></i> <?php echo lang('add_new'); ?></a>
                            </div> -->
                            <div class="frame-wrap table-responsive">
                                <table class="table table-bordered  table-hover m-0">
                                    <thead class="">
                                        <tr>
                                            <!-- <th>/#</th> -->
                                            <th>Action</th>
                                            <th><?php echo sort_field('firstname', lang('fullname')); ?></th>
                                            <th><?php echo sort_field('personal_id', lang('personal_id')); ?></th>
                                            <th><?php echo sort_field('email', lang('email')); ?></th>
                                            <!-- <th><?php echo sort_field('stauts', lang('status')); ?></th> -->
                                            <th><?php echo sort_field('phone', lang('phone')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($lists)) {
                                            echo '<tr ><td colspan="8" align="center">' . lang('no_records') . '</td></tr>';
                                        }
                                        foreach ($lists as $list) : ?>
                                            <tr>
                                                <!-- <th scope="row">1</!-->
                                                <td>
                                                    <a href="javascript:;" onclick="$('#pat_id').val(<?php echo $list->id; ?>)" class="btn btn-outline-primary btn-icon  waves-effect waves-themed mb-1" data-toggle="modal" data-target="#maptreatment">
                                                        <i class="fa fa-refresh"  data-toggle="tooltip" title="<?php echo 'Manage Specialist'; ?>"></i>
                                                    </a>

                                                    
                                                </td>
                                                <td><?php echo $list->firstname . ' ' . $list->lastname;  ?></td>
                                                <td><?php echo $list->personal_id; ?></td>
                                                <td><?php echo $list->email; ?></td>
                                                
                                                <!-- <td><?php echo $list->status == 1 ? 'Active' : 'In-Active'; ?></td> -->
                                                <td><?php echo $list->phone; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>

                            <?php echo pagination_get($total_rows, $perpage); ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal" id="maptreatment">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Manage Treatment Specialist</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

    <form action="<?php echo site_url(config_item('admin_folder').'/doctors/cng_treatment_spl/'.$doctor_id); ?>">
      <!-- Modal body -->
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="pat_id" id="pat_id" value="">
                <select name="treatement" id="treatment" class=" select2" style="width: 100%">
                    <option value="">Select</option>
                    <?php foreach($ts as $list){ ?>
                        <option value="<?php echo $list->id; ?>"><?php echo $list->firstname.' '.$list->lastname; ?></option>
                    <?php } ?>
                </select>
            </div>
          </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
    </form>
    </div>
  </div>
</div>

<script>

</script>