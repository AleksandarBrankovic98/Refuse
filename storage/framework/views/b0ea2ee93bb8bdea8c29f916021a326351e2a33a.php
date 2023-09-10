<?php $__env->startSection('styles'); ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 font-size-18">Phone Numbers</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item">Lead Generation </li>
                                <li class="breadcrumb-item active">Phone Numbers</li>
                            </ol>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header bg-soft-dark ">
                            Active Twilio Phone Numbers
                            <button class="btn btn-outline-primary btn-sm float-right" title="New" data-toggle="modal"
                                    data-target="#newModal"><i class="fas fa-plus-circle"></i></button>

                        </div>
                        <div class="card-body">
                            <?php if($all_phone_nums->isEmpty()): ?>
                                <p>No Active Twilio Phone Numbers.</p>
                            <?php else: ?>
                                <table class="table table-striped table-bordered" id="datatable">
                                <thead>
                                <tr>
                                    <th scope="col">SNO</th>
                                  
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Status</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1;      
                                ?>
                                <?php $__currentLoopData = $all_phone_nums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_num): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($count); ?></td>
                                       
                                        <td><?php echo e($p_num->number); ?></td>
                                       
                                        <td>
                                          
                                            <input data-id="<?php echo e($p_num->id); ?>" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" <?php echo e($p_num->is_active ? 'checked' : ''); ?>>
                    
                                            
                                        </td>
                                    </tr>
                                    <?php
                                $count++;      
                                ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

        </div> <!-- container-fluid -->
    </div>
    <script src="<?php echo e(asset('back/assets/libs/jquery/jquery.min.js')); ?>"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
         $('.toggle-class').change(function() { 
            var status = $(this).prop('checked') == true ? 1 : 0;  
           var phn_id = $(this).data('id');  
        let data = {
                phn_id: phn_id,
                sts: status,
               
            }
            
                axios.post('phone/changeStatus', data)
                    .then(response => {
                            if (response.data.status == 200) {
                               //alert("updated");
                            }
                                })
                            
                        }
                    )
                    .catch(error => console.log(error));
            
  
   
</script>
    <!-- End Page-content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.inc.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home4/bbagnall/public_html/bulk/test/bulk_sms/resources/views/back/pages/phone/index.blade.php ENDPATH**/ ?>