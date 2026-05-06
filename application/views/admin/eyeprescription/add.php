<?php
$edit = isset($edit_mode) && $edit_mode;
$p = $edit ? $prescription : array();
$dist_ref = array(); $near_ref = array();
if ($edit && !empty($refractions)) {
    foreach ($refractions as $r) {
        if ($r['type'] == 'distance') $dist_ref = $r;
        if ($r['type'] == 'near') $near_ref = $r;
    }
}
$existing_meds = ($edit && !empty($medicines)) ? $medicines : array();
?>
<style>
.eye-form .nav-tabs > li > a { font-size: 13px; padding: 8px 15px; }
.eye-form .re-le-header { background: #f0f7ff; padding: 6px 10px; font-weight: bold; border-radius: 4px; margin-bottom: 8px; }
.eye-form .re-le-header.le { background: #fff5f0; }
.eye-form .section-title { background: linear-gradient(135deg, #3c8dbc, #2c6fa0); color: #fff; padding: 8px 15px; border-radius: 4px; margin: 15px 0 10px; font-size: 14px; }
.eye-form .form-group { margin-bottom: 10px; }
.eye-form label { font-size: 12px; font-weight: 600; }
.refraction-table th { background: #f5f5f5; font-size: 12px; text-align: center; }
.refraction-table td { text-align: center; }
.refraction-table input { text-align: center; }
.med-row { background: #fafafa; padding: 8px; border: 1px solid #eee; border-radius: 4px; margin-bottom: 5px; }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->session->flashdata('msg'); ?>
                <div class="box box-primary eye-form">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-eye"></i> <?php echo $edit ? 'Edit' : 'Add'; ?> Eye Prescription</h3>
                        <span class="pull-right">
                            <strong><?php echo isset($patient['patient_name']) ? $patient['patient_name'] : ''; ?></strong>
                            <?php if(isset($patient['patient_id'])) { ?> (ID: <?php echo $patient['patient_id']; ?>) <?php } ?>
                        </span>
                    </div>
                    <form method="post" action="<?php echo base_url('admin/eyeprescription/save'); ?>" id="eyeRxForm">
                        <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                        <input type="hidden" name="opd_id" value="<?php echo isset($opd_id) ? $opd_id : ''; ?>">
                        <input type="hidden" name="ipd_id" value="<?php echo isset($ipd_id) ? $ipd_id : ''; ?>">
                        <input type="hidden" name="visit_id" value="<?php echo isset($visit_id) ? $visit_id : ''; ?>">
                        <?php if ($edit) { ?><input type="hidden" name="edit_id" value="<?php echo $p['id']; ?>"><?php } ?>

                        <div class="box-body">
                            <ul class="nav nav-tabs" id="eyeRxTabs">
                                <li class="active"><a data-toggle="tab" href="#tab_general"><i class="fa fa-user-md"></i> General</a></li>
                                <li><a data-toggle="tab" href="#tab_vision"><i class="fa fa-eye"></i> Vision & Tear</a></li>
                                <li><a data-toggle="tab" href="#tab_exam"><i class="fa fa-stethoscope"></i> Examination</a></li>
                                <li><a data-toggle="tab" href="#tab_refraction"><i class="fa fa-binoculars"></i> Refraction</a></li>
                                <li><a data-toggle="tab" href="#tab_diagnosis"><i class="fa fa-file-text-o"></i> Diagnosis</a></li>
                                <li><a data-toggle="tab" href="#tab_rx"><i class="fa fa-medkit"></i> Rx (Medicine)</a></li>
                            </ul>

                            <div class="tab-content pt6" style="padding-top:15px;">
                                <!-- TAB 1: General -->
                                <div id="tab_general" class="tab-pane active">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Doctor <small class="req" style="color:red;"> *</small></label>
                                                <select name="doctor_id" class="form-control select2" required>
                                                    <option value="">Select</option>
                                                    <?php foreach ($doctors as $doc) { ?>
                                                        <option value="<?php echo $doc['id']; ?>" <?php echo ($edit && $p['doctor_id'] == $doc['id']) ? 'selected' : ''; ?>><?php echo $doc['name'] . ' ' . $doc['surname'] . ' (' . $doc['employee_id'] . ')'; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>C/C (Chief Complaint)</label>
                                                <textarea name="chief_complaint" class="form-control" rows="2"><?php echo $edit ? $p['chief_complaint'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="section-title"><i class="fa fa-heartbeat"></i> General Health</div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>DM</label>
                                            <select name="dm" class="form-control">
                                                <option value="NA" <?php echo ($edit && $p['dm']=='NA')?'selected':''; ?>>N/A</option>
                                                <option value="Yes" <?php echo ($edit && $p['dm']=='Yes')?'selected':''; ?>>Yes</option>
                                                <option value="No" <?php echo ($edit && $p['dm']=='No')?'selected':''; ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>HTN</label>
                                            <select name="htn" class="form-control">
                                                <option value="NA" <?php echo ($edit && $p['htn']=='NA')?'selected':''; ?>>N/A</option>
                                                <option value="Yes" <?php echo ($edit && $p['htn']=='Yes')?'selected':''; ?>>Yes</option>
                                                <option value="No" <?php echo ($edit && $p['htn']=='No')?'selected':''; ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2"><label>RBS</label><input type="text" name="rbs" class="form-control" value="<?php echo $edit ? $p['rbs'] : ''; ?>"></div>
                                        <div class="col-md-3"><label>BP</label><input type="text" name="bp" class="form-control" placeholder="120/80" value="<?php echo $edit ? $p['bp'] : ''; ?>"></div>
                                        <div class="col-md-3"><label>Pulse</label><input type="text" name="pulse" class="form-control" value="<?php echo $edit ? $p['pulse'] : ''; ?>"></div>
                                    </div>
                                    <div class="section-title"><i class="fa fa-history"></i> History</div>
                                    <div class="row">
                                        <div class="col-md-6"><label>Medical History</label><textarea name="medical_history" class="form-control" rows="2"><?php echo $edit ? $p['medical_history'] : ''; ?></textarea></div>
                                        <div class="col-md-6"><label>Surgical History</label><textarea name="surgical_history" class="form-control" rows="2"><?php echo $edit ? $p['surgical_history'] : ''; ?></textarea></div>
                                    </div>
                                </div>

                                <!-- TAB 2: Vision & Tear -->
                                <div id="tab_vision" class="tab-pane">
                                    <div class="section-title"><i class="fa fa-eye"></i> Distance Vision</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="re-le-header">RE (Right Eye)</div>
                                            <div class="row">
                                                <div class="col-md-6"><label>Unaided</label><input type="text" name="va_dist_unaided_re" class="form-control" placeholder="6/6" value="<?php echo $edit ? $p['va_dist_unaided_re'] : ''; ?>"></div>
                                                <div class="col-md-6"><label>Aided</label><input type="text" name="va_dist_aided_re" class="form-control" placeholder="6/6" value="<?php echo $edit ? $p['va_dist_aided_re'] : ''; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="re-le-header le">LE (Left Eye)</div>
                                            <div class="row">
                                                <div class="col-md-6"><label>Unaided</label><input type="text" name="va_dist_unaided_le" class="form-control" placeholder="6/6" value="<?php echo $edit ? $p['va_dist_unaided_le'] : ''; ?>"></div>
                                                <div class="col-md-6"><label>Aided</label><input type="text" name="va_dist_aided_le" class="form-control" placeholder="6/6" value="<?php echo $edit ? $p['va_dist_aided_le'] : ''; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="section-title"><i class="fa fa-tint"></i> Tear / Drainage Tests</div>
                                    <div class="row">
                                        <div class="col-md-3"><label>SPT RE</label><input type="text" name="spt_re" class="form-control" value="<?php echo $edit ? $p['spt_re'] : ''; ?>"></div>
                                        <div class="col-md-3"><label>SPT LE</label><input type="text" name="spt_le" class="form-control" value="<?php echo $edit ? $p['spt_le'] : ''; ?>"></div>
                                        <div class="col-md-3"><label>Schirmer RE</label><input type="text" name="schirmer_re" class="form-control" value="<?php echo $edit ? $p['schirmer_re'] : ''; ?>"></div>
                                        <div class="col-md-3"><label>Schirmer LE</label><input type="text" name="schirmer_le" class="form-control" value="<?php echo $edit ? $p['schirmer_le'] : ''; ?>"></div>
                                    </div>
                                </div>

                                <!-- TAB 3: Examination -->
                                <div id="tab_exam" class="tab-pane">
                                    <div class="section-title"><i class="fa fa-search"></i> Slit Lamp Examination</div>
                                    <?php
                                    $exam_fields = array('lid','cornea','pupil','lens','cd','angle_van','fundus');
                                    $exam_labels = array('Lid','Cornea','Pupil','Lens','C/D Ratio','Angle / VAN','Fundus');
                                    foreach ($exam_fields as $idx => $field) { ?>
                                    <div class="row" style="margin-bottom:8px;">
                                        <div class="col-md-2"><label style="padding-top:7px;"><?php echo $exam_labels[$idx]; ?></label></div>
                                        <div class="col-md-5">
                                            <div class="input-group"><span class="input-group-addon" style="background:#f0f7ff;font-weight:bold;font-size:11px;">RE</span>
                                            <input type="text" name="<?php echo $field; ?>_re" class="form-control" value="<?php echo $edit ? $p[$field.'_re'] : ''; ?>"></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="input-group"><span class="input-group-addon" style="background:#fff5f0;font-weight:bold;font-size:11px;">LE</span>
                                            <input type="text" name="<?php echo $field; ?>_le" class="form-control" value="<?php echo $edit ? $p[$field.'_le'] : ''; ?>"></div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="section-title"><i class="fa fa-tachometer-alt"></i> IOP (Intraocular Pressure)</div>
                                    <div class="row">
                                        <div class="col-md-3"><label>IOP RE</label><input type="text" name="iop_re" class="form-control" placeholder="mmHg" value="<?php echo $edit ? $p['iop_re'] : ''; ?>"></div>
                                        <div class="col-md-3"><label>IOP LE</label><input type="text" name="iop_le" class="form-control" placeholder="mmHg" value="<?php echo $edit ? $p['iop_le'] : ''; ?>"></div>
                                        <div class="col-md-3"><label>Method</label>
                                            <select name="iop_method" class="form-control">
                                                <option value="">Select</option>
                                                <option value="GAT" <?php echo ($edit && $p['iop_method']=='GAT')?'selected':''; ?>>GAT</option>
                                                <option value="NCT" <?php echo ($edit && $p['iop_method']=='NCT')?'selected':''; ?>>NCT</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAB 4: Refraction -->
                                <div id="tab_refraction" class="tab-pane">
                                    <div class="section-title"><i class="fa fa-binoculars"></i> Distance Glass Prescription</div>
                                    <table class="table table-bordered refraction-table">
                                        <thead><tr><th></th><th>SPH</th><th>CYL</th><th>AXIS</th><th>VA</th></tr></thead>
                                        <tbody>
                                            <tr><td class="re-le-header" style="width:80px;">RE</td>
                                                <td><input type="text" name="dist_sph_re" class="form-control" value="<?php echo isset($dist_ref['sph_re']) ? $dist_ref['sph_re'] : ''; ?>"></td>
                                                <td><input type="text" name="dist_cyl_re" class="form-control" value="<?php echo isset($dist_ref['cyl_re']) ? $dist_ref['cyl_re'] : ''; ?>"></td>
                                                <td><input type="text" name="dist_axis_re" class="form-control" value="<?php echo isset($dist_ref['axis_re']) ? $dist_ref['axis_re'] : ''; ?>"></td>
                                                <td><input type="text" name="dist_va_re" class="form-control" value="<?php echo isset($dist_ref['va_re']) ? $dist_ref['va_re'] : ''; ?>"></td>
                                            </tr>
                                            <tr><td class="re-le-header le">LE</td>
                                                <td><input type="text" name="dist_sph_le" class="form-control" value="<?php echo isset($dist_ref['sph_le']) ? $dist_ref['sph_le'] : ''; ?>"></td>
                                                <td><input type="text" name="dist_cyl_le" class="form-control" value="<?php echo isset($dist_ref['cyl_le']) ? $dist_ref['cyl_le'] : ''; ?>"></td>
                                                <td><input type="text" name="dist_axis_le" class="form-control" value="<?php echo isset($dist_ref['axis_le']) ? $dist_ref['axis_le'] : ''; ?>"></td>
                                                <td><input type="text" name="dist_va_le" class="form-control" value="<?php echo isset($dist_ref['va_le']) ? $dist_ref['va_le'] : ''; ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="section-title"><i class="fa fa-binoculars"></i> Near Glass Prescription</div>
                                    <table class="table table-bordered refraction-table">
                                        <thead><tr><th></th><th>SPH</th><th>CYL</th><th>AXIS</th><th>VA</th></tr></thead>
                                        <tbody>
                                            <tr><td class="re-le-header" style="width:80px;">RE</td>
                                                <td><input type="text" name="near_sph_re" class="form-control" value="<?php echo isset($near_ref['sph_re']) ? $near_ref['sph_re'] : ''; ?>"></td>
                                                <td><input type="text" name="near_cyl_re" class="form-control" value="<?php echo isset($near_ref['cyl_re']) ? $near_ref['cyl_re'] : ''; ?>"></td>
                                                <td><input type="text" name="near_axis_re" class="form-control" value="<?php echo isset($near_ref['axis_re']) ? $near_ref['axis_re'] : ''; ?>"></td>
                                                <td><input type="text" name="near_va_re" class="form-control" value="<?php echo isset($near_ref['va_re']) ? $near_ref['va_re'] : ''; ?>"></td>
                                            </tr>
                                            <tr><td class="re-le-header le">LE</td>
                                                <td><input type="text" name="near_sph_le" class="form-control" value="<?php echo isset($near_ref['sph_le']) ? $near_ref['sph_le'] : ''; ?>"></td>
                                                <td><input type="text" name="near_cyl_le" class="form-control" value="<?php echo isset($near_ref['cyl_le']) ? $near_ref['cyl_le'] : ''; ?>"></td>
                                                <td><input type="text" name="near_axis_le" class="form-control" value="<?php echo isset($near_ref['axis_le']) ? $near_ref['axis_le'] : ''; ?>"></td>
                                                <td><input type="text" name="near_va_le" class="form-control" value="<?php echo isset($near_ref['va_le']) ? $near_ref['va_le'] : ''; ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- TAB 5: Diagnosis -->
                                <div id="tab_diagnosis" class="tab-pane">
                                    <div class="row">
                                        <div class="col-md-6"><label>Diagnosis</label><textarea name="diagnosis" class="form-control" rows="3"><?php echo $edit ? $p['diagnosis'] : ''; ?></textarea></div>
                                        <div class="col-md-6"><label>Plan</label><textarea name="plan" class="form-control" rows="3"><?php echo $edit ? $p['plan'] : ''; ?></textarea></div>
                                    </div>
                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-md-6"><label>Investigation</label><textarea name="investigation" class="form-control" rows="2"><?php echo $edit ? $p['investigation'] : ''; ?></textarea></div>
                                        <div class="col-md-6"><label>Counseling</label><textarea name="counseling" class="form-control" rows="2"><?php echo $edit ? $p['counseling'] : ''; ?></textarea></div>
                                    </div>
                                    <div class="row" style="margin-top:10px;">
                                        <div class="col-md-4"><label>Follow-up Date</label><input type="date" name="followup_date" class="form-control" value="<?php echo $edit ? $p['followup_date'] : ''; ?>"></div>
                                        <div class="col-md-4"><label>Advice</label><textarea name="advice" class="form-control" rows="2"><?php echo $edit ? $p['advice'] : ''; ?></textarea></div>
                                        <div class="col-md-4"><label>Print Note</label><textarea name="print_note" class="form-control" rows="2"><?php echo $edit ? $p['print_note'] : ''; ?></textarea></div>
                                    </div>
                                </div>

                                <!-- TAB 6: Rx (Medicine) -->
                                <div id="tab_rx" class="tab-pane">
                                    <div class="section-title"><i class="fa fa-medkit"></i> Medicine Prescription</div>
                                    <div id="medicine_container">
                                        <?php if (!empty($existing_meds)) { foreach ($existing_meds as $mi => $med) { ?>
                                        <div class="med-row row">
                                            <div class="col-md-3"><select name="medicine_id[]" class="form-control select2" style="width:100%;"><option value="">Select</option><?php foreach ($medicine_list as $ml) { ?><option value="<?php echo $ml['id']; ?>" <?php echo ($med['pharmacy_id']==$ml['id'])?'selected':''; ?>><?php echo $ml['medicine_name']; ?></option><?php } ?></select></div>
                                            <div class="col-md-2"><select name="medicine_dosage_id[]" class="form-control"><option value="">Dosage</option><?php foreach ($dosage_list as $d) { ?><option value="<?php echo $d['id']; ?>" <?php echo ($med['dosage_id']==$d['id'])?'selected':''; ?>><?php echo $d['dosage']; ?></option><?php } ?></select></div>
                                            <div class="col-md-2"><select name="medicine_interval_id[]" class="form-control"><option value="">Interval</option><?php foreach ($dose_interval_list as $d) { ?><option value="<?php echo $d['id']; ?>" <?php echo ($med['dose_interval_id']==$d['id'])?'selected':''; ?>><?php echo $d['name']; ?></option><?php } ?></select></div>
                                            <div class="col-md-2"><select name="medicine_duration_id[]" class="form-control"><option value="">Duration</option><?php foreach ($dose_duration_list as $d) { ?><option value="<?php echo $d['id']; ?>" <?php echo ($med['dose_duration_id']==$d['id'])?'selected':''; ?>><?php echo $d['name']; ?></option><?php } ?></select></div>
                                            <div class="col-md-2"><input type="text" name="medicine_instruction[]" class="form-control" placeholder="Instruction" value="<?php echo $med['instruction']; ?>"></div>
                                            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-med"><i class="fa fa-times"></i></button></div>
                                        </div>
                                        <?php } } ?>
                                    </div>
                                    <button type="button" class="btn btn-info btn-sm" id="addMedicineRow"><i class="fa fa-plus"></i> Add Medicine</button>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Eye Prescription</button>
                            <a href="<?php echo base_url('admin/eyeprescription/index/' . $patient_id); ?>" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Medicine Row Template (hidden) -->
<div id="med_row_template" style="display:none;">
    <div class="med-row row">
        <div class="col-md-3"><select name="medicine_id[]" class="form-control select2_dynamic" style="width:100%;"><option value="">Select Medicine</option><?php foreach ($medicine_list as $ml) { ?><option value="<?php echo $ml['id']; ?>"><?php echo $ml['medicine_name']; ?></option><?php } ?></select></div>
        <div class="col-md-2"><select name="medicine_dosage_id[]" class="form-control"><option value="">Dosage</option><?php foreach ($dosage_list as $d) { ?><option value="<?php echo $d['id']; ?>"><?php echo $d['dosage']; ?></option><?php } ?></select></div>
        <div class="col-md-2"><select name="medicine_interval_id[]" class="form-control"><option value="">Interval</option><?php foreach ($dose_interval_list as $d) { ?><option value="<?php echo $d['id']; ?>"><?php echo $d['name']; ?></option><?php } ?></select></div>
        <div class="col-md-2"><select name="medicine_duration_id[]" class="form-control"><option value="">Duration</option><?php foreach ($dose_duration_list as $d) { ?><option value="<?php echo $d['id']; ?>"><?php echo $d['name']; ?></option><?php } ?></select></div>
        <div class="col-md-2"><input type="text" name="medicine_instruction[]" class="form-control" placeholder="Instruction"></div>
        <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-med"><i class="fa fa-times"></i></button></div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2();
    $('#addMedicineRow').click(function() {
        var row = $('#med_row_template').html();
        var $row = $(row);
        $('#medicine_container').append($row);
        $row.find('.select2_dynamic').select2();
    });
    $(document).on('click', '.remove-med', function() {
        $(this).closest('.med-row').remove();
    });
});
</script>
