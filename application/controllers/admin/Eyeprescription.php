<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Eyeprescription extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('eyeprescription_model');
        $this->load->model('patient_model');
        $this->load->model('staff_model');
        $this->load->model('printing_model');
        $this->load->model('setting_model');
        $this->load->library('Enc_lib');
    }

    /**
     * List all eye prescriptions for a patient
     */
    public function index($patient_id = null)
    {
        if (!$patient_id) {
            show_404();
        }
        $data['patient'] = $this->patient_model->patientProfileDetails($patient_id);
        $data['prescriptions'] = $this->eyeprescription_model->getByPatient($patient_id);
        $data['patient_id'] = $patient_id;
        $this->load->view('layout/header');
        $this->load->view('admin/eyeprescription/list', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Add new eye prescription form
     */
    public function add($patient_id = null, $opd_id = null, $visit_id = null)
    {
        if (!$patient_id) {
            show_404();
        }
        $data['patient'] = $this->patient_model->patientProfileDetails($patient_id);
        $data['patient_id'] = $patient_id;
        $data['opd_id'] = $opd_id;
        $data['visit_id'] = $visit_id;
        $data['doctors'] = $this->staff_model->getStaffbyrole(3); // role 3 = Doctor
        $data['medicine_list'] = $this->eyeprescription_model->getMedicineList();
        $data['dosage_list'] = $this->eyeprescription_model->getDosageList();
        $data['dose_interval_list'] = $this->eyeprescription_model->getDoseIntervalList();
        $data['dose_duration_list'] = $this->eyeprescription_model->getDoseDurationList();
        $data['title'] = 'Add Eye Prescription';

        $this->load->view('layout/header');
        $this->load->view('admin/eyeprescription/add', $data);
        $this->load->view('layout/footer');
    }
    /**
     * Add new eye prescription form for IPD
     */
    public function add_ipd($patient_id = null, $ipd_id = null)
    {
        if (!$patient_id) {
            show_404();
        }
        $data['patient'] = $this->patient_model->patientProfileDetails($patient_id);
        $data['patient_id'] = $patient_id;
        $data['ipd_id'] = $ipd_id;
        $data['opd_id'] = null;
        $data['visit_id'] = null;
        $data['doctors'] = $this->staff_model->getStaffbyrole(3); // role 3 = Doctor
        $data['medicine_list'] = $this->eyeprescription_model->getMedicineList();
        $data['dosage_list'] = $this->eyeprescription_model->getDosageList();
        $data['dose_interval_list'] = $this->eyeprescription_model->getDoseIntervalList();
        $data['dose_duration_list'] = $this->eyeprescription_model->getDoseDurationList();
        $data['title'] = 'Add Eye Prescription (IPD)';

        $this->load->view('layout/header');
        $this->load->view('admin/eyeprescription/add', $data);
        $this->load->view('layout/footer');
    }
    /**
     * Save eye prescription (POST handler)
     */
    public function save()
    {
        $patient_id = $this->input->post('patient_id');
        $edit_id = $this->input->post('edit_id');

        // Build main prescription data
        $prescription_data = array(
            'patient_id'        => $patient_id,
            'opd_id'            => $this->input->post('opd_id') ?: null,
            'ipd_id'            => $this->input->post('ipd_id') ?: null,
            'visit_id'          => $this->input->post('visit_id') ?: null,
            'doctor_id'         => $this->input->post('doctor_id'),
            'generated_by'      => $this->customlib->getLoggedInUserID(),
            'date'              => date('Y-m-d H:i:s'),
            'chief_complaint'   => $this->input->post('chief_complaint'),
            'dm'                => $this->input->post('dm') ?: 'NA',
            'htn'               => $this->input->post('htn') ?: 'NA',
            'rbs'               => $this->input->post('rbs'),
            'bp'                => $this->input->post('bp'),
            'pulse'             => $this->input->post('pulse'),
            'spt_re'            => $this->input->post('spt_re'),
            'spt_le'            => $this->input->post('spt_le'),
            'schirmer_re'       => $this->input->post('schirmer_re'),
            'schirmer_le'       => $this->input->post('schirmer_le'),
            'medical_history'   => $this->input->post('medical_history'),
            'surgical_history'  => $this->input->post('surgical_history'),
            'va_dist_unaided_re' => $this->input->post('va_dist_unaided_re'),
            'va_dist_unaided_le' => $this->input->post('va_dist_unaided_le'),
            'va_dist_aided_re'   => $this->input->post('va_dist_aided_re'),
            'va_dist_aided_le'   => $this->input->post('va_dist_aided_le'),
            'lid_re'            => $this->input->post('lid_re'),
            'lid_le'            => $this->input->post('lid_le'),
            'cornea_re'         => $this->input->post('cornea_re'),
            'cornea_le'         => $this->input->post('cornea_le'),
            'pupil_re'          => $this->input->post('pupil_re'),
            'pupil_le'          => $this->input->post('pupil_le'),
            'lens_re'           => $this->input->post('lens_re'),
            'lens_le'           => $this->input->post('lens_le'),
            'cd_re'             => $this->input->post('cd_re'),
            'cd_le'             => $this->input->post('cd_le'),
            'angle_van_re'      => $this->input->post('angle_van_re'),
            'angle_van_le'      => $this->input->post('angle_van_le'),
            'fundus_re'         => $this->input->post('fundus_re'),
            'fundus_le'         => $this->input->post('fundus_le'),
            'iop_re'            => $this->input->post('iop_re'),
            'iop_le'            => $this->input->post('iop_le'),
            'iop_method'        => $this->input->post('iop_method'),
            'diagnosis'         => $this->input->post('diagnosis'),
            'plan'              => $this->input->post('plan'),
            'investigation'     => $this->input->post('investigation'),
            'counseling'        => $this->input->post('counseling'),
            'followup_date'     => $this->input->post('followup_date') ? $this->input->post('followup_date') : null,
            'advice'            => $this->input->post('advice'),
            'print_note'        => $this->input->post('print_note'),
        );

        if ($edit_id) {
            $prescription_data['id'] = $edit_id;
        }

        $prescription_id = $this->eyeprescription_model->add($prescription_data);

        if ($prescription_id) {
            // Save Refractions
            $this->eyeprescription_model->deleteRefractionsByPrescription($prescription_id);

            // Distance refraction
            $distance_data = array(
                'eye_prescription_id' => $prescription_id,
                'type'      => 'distance',
                'sph_re'    => $this->input->post('dist_sph_re'),
                'cyl_re'    => $this->input->post('dist_cyl_re'),
                'axis_re'   => $this->input->post('dist_axis_re'),
                'va_re'     => $this->input->post('dist_va_re'),
                'sph_le'    => $this->input->post('dist_sph_le'),
                'cyl_le'    => $this->input->post('dist_cyl_le'),
                'axis_le'   => $this->input->post('dist_axis_le'),
                'va_le'     => $this->input->post('dist_va_le'),
            );
            $this->eyeprescription_model->addRefraction($distance_data);

            // Near refraction
            $near_data = array(
                'eye_prescription_id' => $prescription_id,
                'type'      => 'near',
                'sph_re'    => $this->input->post('near_sph_re'),
                'cyl_re'    => $this->input->post('near_cyl_re'),
                'axis_re'   => $this->input->post('near_axis_re'),
                'va_re'     => $this->input->post('near_va_re'),
                'sph_le'    => $this->input->post('near_sph_le'),
                'cyl_le'    => $this->input->post('near_cyl_le'),
                'axis_le'   => $this->input->post('near_axis_le'),
                'va_le'     => $this->input->post('near_va_le'),
            );
            $this->eyeprescription_model->addRefraction($near_data);

            // Save Medicines
            $this->eyeprescription_model->deleteMedicinesByPrescription($prescription_id);

            $medicine_ids = $this->input->post('medicine_id');
            $dosage_ids = $this->input->post('medicine_dosage_id');
            $interval_ids = $this->input->post('medicine_interval_id');
            $duration_ids = $this->input->post('medicine_duration_id');
            $instructions = $this->input->post('medicine_instruction');

            if (!empty($medicine_ids)) {
                foreach ($medicine_ids as $key => $med_id) {
                    if (!empty($med_id)) {
                        $med_data = array(
                            'eye_prescription_id' => $prescription_id,
                            'pharmacy_id'         => $med_id,
                            'dosage_id'           => isset($dosage_ids[$key]) ? $dosage_ids[$key] : null,
                            'dose_interval_id'    => isset($interval_ids[$key]) ? $interval_ids[$key] : null,
                            'dose_duration_id'    => isset($duration_ids[$key]) ? $duration_ids[$key] : null,
                            'instruction'         => isset($instructions[$key]) ? $instructions[$key] : '',
                        );
                        $this->eyeprescription_model->addMedicine($med_data);
                    }
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Eye Prescription saved successfully!</div>');

            // Redirect based on context
            if ($this->input->post('opd_id')) {
                redirect('admin/eyeprescription/view/' . $prescription_id);
            } else {
                redirect('admin/eyeprescription/view/' . $prescription_id);
            }
        } else {
            $error = $this->db->error();
            log_message('error', 'Eye Prescription Save Error: ' . json_encode($error));
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Error saving Eye Prescription! ' . $error['message'] . '</div>');
            redirect('admin/eyeprescription/add/' . $patient_id);
        }
    }

    /**
     * View eye prescription details
     */
    public function view($id)
    {
        $data['prescription'] = $this->eyeprescription_model->get($id);
        if (empty($data['prescription'])) {
            show_404();
        }
        $data['refractions'] = $this->eyeprescription_model->getRefractions($id);
        $data['medicines'] = $this->eyeprescription_model->getMedicines($id);
        $data['title'] = 'Eye Prescription Details';

        $this->load->view('layout/header');
        $this->load->view('admin/eyeprescription/view', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Edit eye prescription
     */
    public function edit($id)
    {
        $data['prescription'] = $this->eyeprescription_model->get($id);
        if (empty($data['prescription'])) {
            show_404();
        }
        $data['refractions'] = $this->eyeprescription_model->getRefractions($id);
        $data['medicines'] = $this->eyeprescription_model->getMedicines($id);
        $data['patient_id'] = $data['prescription']['patient_id'];
        $data['patient'] = $this->patient_model->patientProfileDetails($data['patient_id']);
        $data['opd_id'] = $data['prescription']['opd_id'];
        $data['ipd_id'] = $data['prescription']['ipd_id'];
        $data['visit_id'] = $data['prescription']['visit_id'];
        $data['doctors'] = $this->staff_model->getStaffbyrole(3);
        $data['medicine_list'] = $this->eyeprescription_model->getMedicineList();
        $data['dosage_list'] = $this->eyeprescription_model->getDosageList();
        $data['dose_interval_list'] = $this->eyeprescription_model->getDoseIntervalList();
        $data['dose_duration_list'] = $this->eyeprescription_model->getDoseDurationList();
        $data['title'] = 'Edit Eye Prescription';
        $data['edit_mode'] = true;

        $this->load->view('layout/header');
        $this->load->view('admin/eyeprescription/add', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Delete eye prescription
     */
    public function delete($id)
    {
        $prescription = $this->eyeprescription_model->get($id);
        if (empty($prescription)) {
            show_404();
        }
        $patient_id = $prescription['patient_id'];
        $this->eyeprescription_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Eye Prescription deleted successfully!</div>');
        redirect('admin/eyeprescription/index/' . $patient_id);
    }

    /**
     * Print eye prescription
     */
    public function printPrescription($id)
    {
        $data['prescription'] = $this->eyeprescription_model->get($id);
        if (empty($data['prescription'])) {
            show_404();
        }
        $data['refractions'] = $this->eyeprescription_model->getRefractions($id);
        $data['medicines'] = $this->eyeprescription_model->getMedicines($id);
        if (!empty($data['prescription']['ipd_id'])) {
            $data['print_details'] = $this->printing_model->getheaderfooter('ipdpres');
        } else {
            $data['print_details'] = $this->printing_model->getheaderfooter('opdpre');
        }
        $data['setting'] = $this->setting_model->get();
        $data['title'] = 'Print Eye Prescription';

        $this->load->view('admin/eyeprescription/print', $data);
    }

    /**
     * AJAX: Get eye prescriptions for a visit (used in visitDetails tab)
     */
    public function getByVisitAjax()
    {
        $visit_id = $this->input->post('visit_id');
        $patient_id = $this->input->post('patient_id');

        $prescriptions = array();
        if ($visit_id) {
            $prescriptions = $this->eyeprescription_model->getByVisit($visit_id);
        } elseif ($patient_id) {
            $prescriptions = $this->eyeprescription_model->getByPatient($patient_id);
        }

        $data['prescriptions'] = $prescriptions;
        $data['patient_id'] = $patient_id;
        $data['visit_id'] = $visit_id;

        $page = $this->load->view('admin/eyeprescription/_visit_tab_content', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }
    /**
     * AJAX: Get eye prescriptions for an IPD admission (used in ipdprofile tab)
     */
    public function getByIpdAjax()
    {
        $ipd_id = $this->input->post('ipd_id');
        $patient_id = $this->input->post('patient_id');

        $prescriptions = array();
        if ($ipd_id) {
            $prescriptions = $this->eyeprescription_model->getByIpd($ipd_id);
        } elseif ($patient_id) {
            $prescriptions = $this->eyeprescription_model->getByPatient($patient_id);
        }

        $data['prescriptions'] = $prescriptions;
        $data['patient_id'] = $patient_id;
        $data['ipd_id'] = $ipd_id;

        $page = $this->load->view('admin/eyeprescription/_visit_tab_content', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }
}
