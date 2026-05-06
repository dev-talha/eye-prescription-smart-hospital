<?php $pat = isset($patient) ? $patient : array(); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eye"></i> Eye Prescriptions — <?php echo isset($pat['patient_name']) ? $pat['patient_name'] : ''; ?></h3>
            <a href="<?php echo base_url('admin/eyeprescription/add/'.$patient_id); ?>" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> New Eye Prescription</a>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th>#</th><th>Date</th><th>Doctor</th><th>Diagnosis</th><th>Follow-up</th><th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($prescriptions)){ foreach($prescriptions as $i=>$rx){ ?>
                  <tr>
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo date('d-M-Y', strtotime($rx['date'])); ?></td>
                    <td><?php echo $rx['doctor_name'].' '.$rx['doctor_surname']; ?></td>
                    <td><?php echo mb_strimwidth($rx['diagnosis'], 0, 60, '...'); ?></td>
                    <td><?php echo $rx['followup_date'] ? date('d-M-Y', strtotime($rx['followup_date'])) : '-'; ?></td>
                    <td class="text-center">
                      <a href="<?php echo base_url('admin/eyeprescription/view/'.$rx['id']); ?>" class="btn btn-xs btn-info" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
                      <a href="<?php echo base_url('admin/eyeprescription/edit/'.$rx['id']); ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                      <a href="<?php echo base_url('admin/eyeprescription/printPrescription/'.$rx['id']); ?>" target="_blank" class="btn btn-xs btn-default" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></a>
                      <a href="<?php echo base_url('admin/eyeprescription/delete/'.$rx['id']); ?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                  <?php } } else { ?>
                  <tr><td colspan="6" class="text-center text-muted">No eye prescriptions found.</td></tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
