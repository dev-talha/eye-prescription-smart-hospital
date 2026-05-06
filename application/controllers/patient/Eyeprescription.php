<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Eyeprescription extends Patient_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Eyeprescription_model');
        $this->load->model('printing_model');
    }

    public function getPrescription()
    {
        $id = $this->input->post('id');
        $data['prescription'] = $this->Eyeprescription_model->get($id);
        
        $patient_id = $this->customlib->getPatientSessionUserID();
        if ($data['prescription']['patient_id'] != $patient_id) {
            echo json_encode(array('status' => 0, 'msg' => 'Unauthorized'));
            return;
        }

        $data['refractions'] = $this->Eyeprescription_model->getRefractions($id);
        $data['medicines'] = $this->Eyeprescription_model->getMedicines($id);

        $page = $this->load->view('patient/eyeprescription/_viewPrescription', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function printPrescription($id)
    {
        $data['prescription'] = $this->Eyeprescription_model->get($id);
        if (!$data['prescription']) {
            show_404();
        }

        // Security check: ensure this prescription belongs to the logged-in patient
        $patient_id = $this->customlib->getPatientSessionUserID();
        if ($data['prescription']['patient_id'] != $patient_id) {
            redirect('site/userlogin');
        }

        $data['refractions'] = $this->Eyeprescription_model->getRefractions($id);
        $data['medicines'] = $this->Eyeprescription_model->getMedicines($id);

        // Get header/footer based on context (IPD or OPD)
        if ($data['prescription']['ipd_id']) {
            $data['print_details'] = $this->printing_model->getheaderfooter('ipdpres');
        } else {
            $data['print_details'] = $this->printing_model->getheaderfooter('opdpre');
        }

        $data['setting'] = $this->setting_model->get();

        $this->load->view('admin/eyeprescription/print', $data);
    }
}
