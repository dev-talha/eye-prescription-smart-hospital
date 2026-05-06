<?php
$p = $prescription;
$dist_ref = array(); $near_ref = array();
if (!empty($refractions)) {
    foreach ($refractions as $r) {
        if ($r['type'] == 'distance') $dist_ref = $r;
        if ($r['type'] == 'near') $near_ref = $r;
    }
}
$setting = isset($setting[0]) ? $setting[0] : array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eye Prescription - <?php echo $p['patient_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* A4 Page Formatting with 0.5 inch margins */
        @page {
            size: A4;
            margin: 0.2in;
            margin-left: 0.3in;
        }
        body {
            background-color: #fff;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .patient-info-row {
            border: 1px solid #000;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000;
            margin-bottom: 10px;
            margin-top: 15px;
            font-size: 13px;
            padding-bottom: 3px;
            color: #000;
        }
        .table-bw {
            border: 1px solid #000;
            width: 100%;
        }
        .table-bw th, .table-bw td {
            border: 1px solid #000 !important;
            padding: 4px 6px;
            color: #000;
        }
        .table-bw th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 11px;
        }
        .footer-section {
            margin-top: 40px;
        }
        .sig-line {
            border-top: 2px solid #000;
            width: 180px;
            margin: 0 auto;
            margin-top: 5px;
        }
        .bengali-note {
            font-family:sans-serif; /* Switching to sans-serif for better Bengali rendering */
            font-size: 14px;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
        .no-print {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }
        .compact-table th,
        .compact-table td {
            padding: 1px 5px;
            height: 20px;
            line-height: 1.1;
            vertical-align: middle;
        }
        .medi-table td, .medi-table th {
            border: none!important;
            border-bottom: 1px solid #000!important;
            vertical-align: middle;
        }
        .print-header-img {
            width: 100%;
            max-height: 120px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-dark">Print Prescription (A4)</button>
</div>

<div class="container-fluid">
    <!-- Row 1: Hospital Header -->
    <div class="header-section text-center">
        <?php if (!empty($print_details['print_header'])) { ?>
            <img src="<?php echo base_url('uploads/printing/'.$print_details['print_header']); ?>" class="print-header-img">
        <?php } ?>
    </div>

    <!-- Row 2: Patient Info -->
    <div class="patient-info-row row g-0">
        <div class="col-3 border-end p-2">
            <p class="m-0"><strong>Date:</strong> <?php echo date('d/m/Y', strtotime($p['date'])); ?></p>
            <p class="m-0"><strong>Patient ID:</strong> <?php echo $p['pid']; ?></p>
            <p class="m-0"><strong>Prescription:</strong> <?php echo $this->customlib->getSessionPrefixByType('opd_prescription').$p['id']; ?></p>
            <p class="m-0"><strong><?php echo $p['ipd_id'] ? 'IPD ID:' : 'OPD ID:'; ?></strong> <?php echo $p['ipd_id'] ? $this->customlib->getSessionPrefixByType('ipd_no') . $p['ipd_id'] : $this->customlib->getSessionPrefixByType('opd_no') . $p['opd_id']; ?></p>
        </div>
        <div class="col-4 border-end p-2">
            <p class="m-0"><strong>Name:</strong> <?php echo $p['patient_name']; ?></p>
            <p class="m-0"><strong>Age:</strong> <?php echo $this->customlib->get_patient_current_age($p['patient_id']); ?></p>
            <p class="m-0"><strong>Gender:</strong> <?php echo (isset($p['gender'])) ? $p['gender'] : ""; ?></p>
            <p class="m-0"><strong>Follow-up Date:</strong> <?php echo $p['followup_date'] ? date('d/m/Y', strtotime($p['followup_date'])) : '-'; ?></p>
        </div>
        <div class="col-5 p-2">
            <p class="m-0"><strong>Cons. Dr.:</strong> <?php echo $p['doctor_name'] . ' ' . $p['doctor_surname'] . ' (' . $p['doctor_employee_id'] . ')'; ?></p>
            <p class="m-0"><strong>Qualification:</strong> <?php echo $p['doctor_qualification']; ?></p>
        </div>
    </div>

    <!-- Row 3: Split Layout -->
    <div class="row g-3">
        <!-- Left Column (30%) -->
        <div class="col-4 border-end">
            <div class="pe-0">
                <?php if($p['dm'] != 'NA' || $p['htn'] != 'NA' || $p['rbs'] || $p['bp'] || $p['pulse']){ ?>
                <div class="section-title">General Health</div>
                <p class="mb-1">
                    <?php if($p['dm'] != 'NA') echo '<strong>DM:</strong> '.$p['dm'].' | '; ?>
                    <?php if($p['htn'] != 'NA') echo '<strong>HTN:</strong> '.$p['htn'].' | '; ?>
                    <?php if($p['rbs']) echo '<strong>RBS:</strong> '.$p['rbs'].' '; ?>
                </p>
                <p class="mb-1">
                    <?php if($p['bp']) echo '<strong>BP:</strong> '.$p['bp'].' | '; ?>
                    <?php if($p['pulse']) echo '<strong>Pulse:</strong> '.$p['pulse'].' '; ?>
                </p>
                <?php } ?>

                <?php if($p['va_dist_unaided_re'] || $p['va_dist_unaided_le']){ ?>
                <div class="section-title">Vision, Tear & IOP</div>
                <table class="table table-sm table-bw text-center mb-2 compact-table">
                    <thead>
                        <tr><th style="width: 30px;">Eye</th><th>Unaided</th><th>Aided</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>RE</td><td><?php echo $p['va_dist_unaided_re']; ?></td><td><?php echo $p['va_dist_aided_re']; ?></td></tr>
                        <tr><td>LE</td><td><?php echo $p['va_dist_unaided_le']; ?></td><td><?php echo $p['va_dist_aided_le']; ?></td></tr>
                    </tbody>
                </table>
                <?php } ?>

                <?php if($p['spt_re'] || $p['spt_le'] || $p['schirmer_re'] || $p['schirmer_le']){ ?>
                <table class="table table-sm table-bw text-center mb-2 compact-table">
                    <thead>
                        <tr><th style="width: 30px;">Eye</th><th>SPT</th><th>Schirmer</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>RE</td><td><?php echo $p['spt_re']; ?></td><td><?php echo $p['schirmer_re']; ?></td></tr>
                        <tr><td>LE</td><td><?php echo $p['spt_le']; ?></td><td><?php echo $p['schirmer_le']; ?></td></tr>
                    </tbody>
                </table>
                <?php } ?>

                <?php if($p['iop_re'] || $p['iop_le']){ ?>
                <table class="table table-sm table-bw text-center mb-2 compact-table">
                    <thead>
                        <tr><th style="width: 30px;">Eye</th><th>IOP (mmHg)</th><th>Method</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>RE</td><td><?php echo $p['iop_re']; ?></td><td rowspan="2" style="vertical-align: middle;"><?php echo $p['iop_method']; ?></td></tr>
                        <tr><td>LE</td><td><?php echo $p['iop_le']; ?></td></tr>
                    </tbody>
                </table>
                <?php } ?>

                <?php 
                $has_exam = false;
                $fields = array('lid'=>'Lid','cornea'=>'Cornea','pupil'=>'Pupil','lens'=>'Lens','cd'=>'C/D Ratio','angle_van'=>'Angle/VAN','fundus'=>'Fundus');
                foreach($fields as $k=>$v){ if($p[$k.'_re'] || $p[$k.'_le']) $has_exam = true; }
                if($has_exam){ ?>
                <div class="section-title">Slit Lamp</div>
                <table class="table table-sm table-bw mb-3 compact-table">
                    <thead>
                        <tr><th>Finding</th><th>RE</th><th>LE</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($fields as $k=>$v){ ?>
                        <tr><td><?php echo $v; ?></td><td><?php echo $p[$k.'_re']; ?></td><td><?php echo $p[$k.'_le']; ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } ?>

                <div class="section-title">Notes</div>
                <?php if($p['diagnosis']){ ?><p class="small mb-1"><strong>Diagnosis:</strong> <?php echo nl2br($p['diagnosis']); ?></p><?php } ?>
                <?php if($p['plan']){ ?><p class="small mb-1"><strong>Plan:</strong> <?php echo nl2br($p['plan']); ?></p><?php } ?>
                <?php if($p['investigation']){ ?><p class="small mb-1"><strong>Investigation:</strong> <?php echo nl2br($p['investigation']); ?></p><?php } ?>
                <?php if($p['counseling']){ ?><p class="small mb-1"><strong>Counseling:</strong> <?php echo nl2br($p['counseling']); ?></p><?php } ?>
                <?php if($p['advice']){ ?><p class="small mb-1"><strong>Advice:</strong> <?php echo nl2br($p['advice']); ?></p><?php } ?>
            </div>
        </div>

        <!-- Right Column (70%) -->
        <div class="col-8">
            <?php if(!empty($dist_ref) || !empty($near_ref)){ ?>
            <div class="section-title">Glass Prescription</div>
            <table class="table table-sm table-bw text-center mb-4">
                <thead>
                    <tr>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 10%;">Eye</th>
                        <th style="width: 15%;">SPH</th>
                        <th style="width: 15%;">CYL</th>
                        <th style="width: 15%;">AXIS</th>
                        <th style="width: 15%;">VA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($dist_ref)){ ?>
                    <tr>
                        <td rowspan="2" class="align-middle fw-bold">Dist.</td>
                        <td>RE</td><td><?php echo $dist_ref['sph_re']; ?></td><td><?php echo $dist_ref['cyl_re']; ?></td><td><?php echo $dist_ref['axis_re']; ?></td><td><?php echo $dist_ref['va_re']; ?></td>
                    </tr>
                    <tr>
                        <td>LE</td><td><?php echo $dist_ref['sph_le']; ?></td><td><?php echo $dist_ref['cyl_le']; ?></td><td><?php echo $dist_ref['axis_le']; ?></td><td><?php echo $dist_ref['va_le']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if(!empty($near_ref)){ ?>
                    <tr style="border-top: 2px dotted #000;">
                        <td rowspan="2" class="align-middle fw-bold">Near</td>
                        <td>RE</td><td><?php echo $near_ref['sph_re']; ?></td><td><?php echo $near_ref['cyl_re']; ?></td><td><?php echo $near_ref['axis_re']; ?></td><td><?php echo $near_ref['va_re']; ?></td>
                    </tr>
                    <tr>
                        <td>LE</td><td><?php echo $near_ref['sph_le']; ?></td><td><?php echo $near_ref['cyl_le']; ?></td><td><?php echo $near_ref['axis_le']; ?></td><td><?php echo $near_ref['va_le']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>

            <?php if(!empty($medicines)){ ?>
            <div class="section-title">Medicines (℞)</div>
            <table class="table table-sm table-bw medi-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine</th>
                        <th>Dose</th>
                        <th>Interval</th>
                        <th>Days</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($medicines as $i => $m){ ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td>
                            <?php echo $m['medicine_name']; ?>
                            <?php if($m['instruction']){ ?><br> <small class="Instraction"><?php echo $m['instruction']; ?></small><?php } ?>
                        </td>
                        <td><?php echo $m['medicine_dosage']; ?></td>
                        <td><?php echo $m['dose_interval']; ?></td>
                        <td><?php echo $m['dose_duration']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>

    <!-- Row 4: Footer -->
    <div class="footer-section">
        <div class="row text-center">
            <div class="col-4">
                <div class="sig-line"></div>
                <small class="fw-bold">Patient Signature</small>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <div class="sig-line"></div>
                <small class="fw-bold">Authorised Signatory</small>
            </div>
        </div>
        <div class="bengali-note">
            পরবর্তী সাক্ষাৎ এর সময় প্রেসক্রিপশন সঙ্গে নিয়ে আসবেন।
        </div>
        
        <div class="text-center" style="margin-top:20px;">
            <?php if (!empty($print_details['print_footer'])) { ?>
                <img src="<?php echo base_url('uploads/printing/'.$print_details['print_footer']); ?>" class="img-responsive" style="max-height:100px; width:100%; object-fit:contain;">
            <?php } ?>
        </div>
    </div>
</div>

<script>
    // Remove the default system window.print to rely on our own if needed,
    // though the user might want it to automatically print just like the system does
    window.onload = function() {
        // window.print(); // Uncomment if auto-print is desired
    };
</script>
</body>
</html>
