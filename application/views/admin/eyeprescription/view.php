<?php
$p = $prescription;
$dist_ref = array(); $near_ref = array();
if (!empty($refractions)) {
    foreach ($refractions as $r) {
        if ($r['type'] == 'distance') $dist_ref = $r;
        if ($r['type'] == 'near') $near_ref = $r;
    }
}
?>
<style>
.eye-view .info-box { border: 1px solid #e0e0e0; border-radius: 5px; padding: 12px; margin-bottom: 10px; }
.eye-view .info-box h5 { background: linear-gradient(135deg,#3c8dbc,#2c6fa0); color:#fff; padding:8px 12px; border-radius:4px; margin:-12px -12px 10px; font-size:13px; }
.eye-view .re-tag { background:#f0f7ff; padding:2px 8px; border-radius:3px; font-weight:bold; font-size:11px; }
.eye-view .le-tag { background:#fff5f0; padding:2px 8px; border-radius:3px; font-weight:bold; font-size:11px; }
.eye-view .data-label { font-weight:600; color:#555; font-size:12px; }
.eye-view .data-value { font-size:13px; }
.refraction-view th { background:#f5f5f5; text-align:center; font-size:12px; }
.refraction-view td { text-align:center; }
</style>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <?php echo $this->session->flashdata('msg'); ?>
        <div class="box box-primary eye-view">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eye"></i> Eye Prescription #<?php echo $p['id']; ?></h3>
            <div class="pull-right">
              <a href="<?php echo base_url('admin/eyeprescription/edit/'.$p['id']); ?>" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Edit</a>
              <a href="<?php echo base_url('admin/eyeprescription/printPrescription/'.$p['id']); ?>" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-print"></i> Print</a>
              <a href="<?php echo base_url('admin/eyeprescription/index/'.$p['patient_id']); ?>" class="btn btn-sm btn-default"><i class="fa fa-list"></i> All Prescriptions</a>
            </div>
          </div>
          <div class="box-body">
            <!-- Patient & Doctor Info -->
            <div class="row">
              <div class="col-md-6">
                <div class="info-box"><h5><i class="fa fa-user"></i> Patient</h5>
                  <table class="table table-condensed mb0">
                    <tr><td class="data-label">Name</td><td class="data-value"><?php echo $p['patient_name']; ?></td></tr>
                    <tr><td class="data-label">Gender</td><td><?php echo $p['gender']; ?></td></tr>
                    <tr><td class="data-label">Guardian</td><td><?php echo $p['guardian_name']; ?></td></tr>
                    <tr><td class="data-label">Phone</td><td><?php echo $p['mobileno']; ?></td></tr>
                  </table>
                </div>
              </div>
              <div class="col-md-6">
                <div class="info-box"><h5><i class="fa fa-user-md"></i> Doctor & Date</h5>
                  <table class="table table-condensed mb0">
                    <tr><td class="data-label">Doctor</td><td><?php echo $p['doctor_name'].' '.$p['doctor_surname']; ?></td></tr>
                    <tr><td class="data-label">Qualification</td><td><?php echo $p['doctor_qualification']; ?></td></tr>
                    <tr><td class="data-label">Date</td><td><?php echo date('d-M-Y H:i', strtotime($p['date'])); ?></td></tr>
                    <tr><td class="data-label">C/C</td><td><?php echo $p['chief_complaint']; ?></td></tr>
                  </table>
                </div>
              </div>
            </div>

            <!-- General Health -->
            <div class="info-box"><h5><i class="fa fa-heartbeat"></i> General Health</h5>
              <div class="row">
                <div class="col-md-2"><span class="data-label">DM:</span> <?php echo $p['dm']; ?></div>
                <div class="col-md-2"><span class="data-label">HTN:</span> <?php echo $p['htn']; ?></div>
                <div class="col-md-2"><span class="data-label">RBS:</span> <?php echo $p['rbs']; ?></div>
                <div class="col-md-3"><span class="data-label">BP:</span> <?php echo $p['bp']; ?></div>
                <div class="col-md-3"><span class="data-label">Pulse:</span> <?php echo $p['pulse']; ?></div>
              </div>
            </div>

            <!-- Vision -->
            <div class="info-box"><h5><i class="fa fa-eye"></i> Vision Test</h5>
              <table class="table table-bordered table-condensed">
                <thead><tr><th></th><th>Unaided</th><th>Aided</th></tr></thead>
                <tbody>
                  <tr><td><span class="re-tag">RE</span></td><td><?php echo $p['va_dist_unaided_re']; ?></td><td><?php echo $p['va_dist_aided_re']; ?></td></tr>
                  <tr><td><span class="le-tag">LE</span></td><td><?php echo $p['va_dist_unaided_le']; ?></td><td><?php echo $p['va_dist_aided_le']; ?></td></tr>
                </tbody>
              </table>
            </div>

            <!-- Tear Tests -->
            <div class="info-box"><h5><i class="fa fa-tint"></i> Tear / Drainage</h5>
              <div class="row">
                <div class="col-md-3"><span class="data-label">SPT RE:</span> <?php echo $p['spt_re']; ?></div>
                <div class="col-md-3"><span class="data-label">SPT LE:</span> <?php echo $p['spt_le']; ?></div>
                <div class="col-md-3"><span class="data-label">Schirmer RE:</span> <?php echo $p['schirmer_re']; ?></div>
                <div class="col-md-3"><span class="data-label">Schirmer LE:</span> <?php echo $p['schirmer_le']; ?></div>
              </div>
            </div>

            <!-- Examination -->
            <div class="info-box"><h5><i class="fa fa-search"></i> Eye Examination</h5>
              <table class="table table-bordered table-condensed">
                <thead><tr><th>Finding</th><th><span class="re-tag">RE</span></th><th><span class="le-tag">LE</span></th></tr></thead>
                <tbody>
                  <?php $fields = array('lid'=>'Lid','cornea'=>'Cornea','pupil'=>'Pupil','lens'=>'Lens','cd'=>'C/D','angle_van'=>'Angle/VAN','fundus'=>'Fundus');
                  foreach($fields as $k=>$v){ ?>
                  <tr><td class="data-label"><?php echo $v; ?></td><td><?php echo $p[$k.'_re']; ?></td><td><?php echo $p[$k.'_le']; ?></td></tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <!-- IOP -->
            <div class="info-box"><h5><i class="fa fa-tachometer"></i> IOP</h5>
              <div class="row">
                <div class="col-md-3"><span class="data-label">RE:</span> <?php echo $p['iop_re']; ?> mmHg</div>
                <div class="col-md-3"><span class="data-label">LE:</span> <?php echo $p['iop_le']; ?> mmHg</div>
                <div class="col-md-3"><span class="data-label">Method:</span> <?php echo $p['iop_method']; ?></div>
              </div>
            </div>

            <!-- Refraction -->
            <?php if(!empty($dist_ref) || !empty($near_ref)){ ?>
            <div class="info-box"><h5><i class="fa fa-binoculars"></i> Refraction / Glass Prescription</h5>
              <?php if(!empty($dist_ref)){ ?>
              <p class="data-label">Distance:</p>
              <table class="table table-bordered refraction-view">
                <thead><tr><th></th><th>SPH</th><th>CYL</th><th>AXIS</th><th>VA</th></tr></thead>
                <tbody>
                  <tr><td><span class="re-tag">RE</span></td><td><?php echo $dist_ref['sph_re']; ?></td><td><?php echo $dist_ref['cyl_re']; ?></td><td><?php echo $dist_ref['axis_re']; ?></td><td><?php echo $dist_ref['va_re']; ?></td></tr>
                  <tr><td><span class="le-tag">LE</span></td><td><?php echo $dist_ref['sph_le']; ?></td><td><?php echo $dist_ref['cyl_le']; ?></td><td><?php echo $dist_ref['axis_le']; ?></td><td><?php echo $dist_ref['va_le']; ?></td></tr>
                </tbody>
              </table>
              <?php } if(!empty($near_ref)){ ?>
              <p class="data-label">Near:</p>
              <table class="table table-bordered refraction-view">
                <thead><tr><th></th><th>SPH</th><th>CYL</th><th>AXIS</th><th>VA</th></tr></thead>
                <tbody>
                  <tr><td><span class="re-tag">RE</span></td><td><?php echo $near_ref['sph_re']; ?></td><td><?php echo $near_ref['cyl_re']; ?></td><td><?php echo $near_ref['axis_re']; ?></td><td><?php echo $near_ref['va_re']; ?></td></tr>
                  <tr><td><span class="le-tag">LE</span></td><td><?php echo $near_ref['sph_le']; ?></td><td><?php echo $near_ref['cyl_le']; ?></td><td><?php echo $near_ref['axis_le']; ?></td><td><?php echo $near_ref['va_le']; ?></td></tr>
                </tbody>
              </table>
              <?php } ?>
            </div>
            <?php } ?>

            <!-- Diagnosis -->
            <div class="info-box"><h5><i class="fa fa-file-text-o"></i> Diagnosis & Plan</h5>
              <div class="row">
                <div class="col-md-6"><span class="data-label">Diagnosis:</span><br><?php echo nl2br($p['diagnosis']); ?></div>
                <div class="col-md-6"><span class="data-label">Plan:</span><br><?php echo nl2br($p['plan']); ?></div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-4"><span class="data-label">Investigation:</span><br><?php echo nl2br($p['investigation']); ?></div>
                <div class="col-md-4"><span class="data-label">Counseling:</span><br><?php echo nl2br($p['counseling']); ?></div>
                <div class="col-md-4"><span class="data-label">Follow-up:</span><br><?php echo $p['followup_date'] ? date('d-M-Y', strtotime($p['followup_date'])) : '-'; ?></div>
              </div>
            </div>

            <!-- Medicines -->
            <?php if(!empty($medicines)){ ?>
            <div class="info-box"><h5><i class="fa fa-medkit"></i> Rx (Medicine)</h5>
              <table class="table table-bordered table-striped">
                <thead><tr><th>#</th><th>Medicine</th><th>Category</th><th>Dosage</th><th>Interval</th><th>Duration</th><th>Instruction</th></tr></thead>
                <tbody>
                  <?php foreach($medicines as $i=>$m){ ?>
                  <tr><td><?php echo $i+1; ?></td><td><?php echo $m['medicine_name']; ?></td><td><?php echo $m['medicine_category']; ?></td><td><?php echo $m['medicine_dosage']; ?></td><td><?php echo $m['dose_interval']; ?></td><td><?php echo $m['dose_duration']; ?></td><td><?php echo $m['instruction']; ?></td></tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
