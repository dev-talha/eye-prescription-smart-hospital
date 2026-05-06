<?php if(!empty($prescriptions)){ ?>
<div class="table-responsive">
  <table class="table table-bordered table-striped table-hover">
    <thead><tr><th>#</th><th>Date</th><th>Doctor</th><th>Diagnosis</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach($prescriptions as $i=>$rx){ ?>
      <tr>
        <td><?php echo $i+1; ?></td>
        <td><?php echo date('d-M-Y', strtotime($rx['date'])); ?></td>
        <td><?php echo $rx['doctor_name'].' '.$rx['doctor_surname']; ?></td>
        <td><?php echo mb_strimwidth($rx['diagnosis'],0,50,'...'); ?></td>
        <td>
          <a href="<?php echo base_url('admin/eyeprescription/view/'.$rx['id']); ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
          <a href="<?php echo base_url('admin/eyeprescription/edit/'.$rx['id']); ?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></a>
          <a href="<?php echo base_url('admin/eyeprescription/printPrescription/'.$rx['id']); ?>" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-print"></i></a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php } else { ?>
<p class="text-muted text-center">No eye prescriptions found for this visit.</p>
<?php } ?>
