<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Eyeprescription_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add or Update eye prescription
     */
    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('eye_prescriptions', $data);
            $record_id = $data['id'];
        } else {
            $this->db->insert('eye_prescriptions', $data);
            $record_id = $this->db->insert_id();
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        return $record_id;
    }

    /**
     * Get single eye prescription by ID
     */
    public function get($id)
    {
        $this->db->select('eye_prescriptions.*, patients.patient_name, patients.id as pid, patients.mobileno, patients.gender, patients.dob, patients.age, patients.month, patients.guardian_name, patients.image as patient_image, staff.name as doctor_name, staff.surname as doctor_surname, staff.employee_id as doctor_employee_id, staff.qualification as doctor_qualification, staff.specialization as doctor_specialization, staff.work_exp as doctor_work_exp, generator.name as generated_by_name, generator.surname as generated_by_surname, generator.employee_id as generated_by_employee_id');
        $this->db->from('eye_prescriptions');
        $this->db->join('patients', 'patients.id = eye_prescriptions.patient_id', 'left');
        $this->db->join('staff', 'staff.id = eye_prescriptions.doctor_id', 'left');
        $this->db->join('staff as generator', 'generator.id = eye_prescriptions.generated_by', 'left');
        $this->db->where('eye_prescriptions.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get all eye prescriptions for a patient
     */
    public function getByPatient($patient_id)
    {
        $this->db->select('eye_prescriptions.*, staff.name as doctor_name, staff.surname as doctor_surname, staff.employee_id as doctor_employee_id');
        $this->db->from('eye_prescriptions');
        $this->db->join('staff', 'staff.id = eye_prescriptions.doctor_id', 'left');
        $this->db->where('eye_prescriptions.patient_id', $patient_id);
        $this->db->order_by('eye_prescriptions.date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get eye prescriptions by OPD ID
     */
    public function getByOpd($opd_id)
    {
        $this->db->select('eye_prescriptions.*, staff.name as doctor_name, staff.surname as doctor_surname, staff.employee_id as doctor_employee_id');
        $this->db->from('eye_prescriptions');
        $this->db->join('staff', 'staff.id = eye_prescriptions.doctor_id', 'left');
        $this->db->where('eye_prescriptions.opd_id', $opd_id);
        $this->db->order_by('eye_prescriptions.date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get eye prescriptions by visit_id
     */
    public function getByVisit($visit_id)
    {
        $this->db->select('eye_prescriptions.*, staff.name as doctor_name, staff.surname as doctor_surname, staff.employee_id as doctor_employee_id');
        $this->db->from('eye_prescriptions');
        $this->db->join('staff', 'staff.id = eye_prescriptions.doctor_id', 'left');
        $this->db->where('eye_prescriptions.visit_id', $visit_id);
        $this->db->order_by('eye_prescriptions.date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get eye prescriptions by IPD ID
     */
    public function getByIpd($ipd_id)
    {
        $this->db->select('eye_prescriptions.*, staff.name as doctor_name, staff.surname as doctor_surname, staff.employee_id as doctor_employee_id');
        $this->db->from('eye_prescriptions');
        $this->db->join('staff', 'staff.id = eye_prescriptions.doctor_id', 'left');
        $this->db->where('eye_prescriptions.ipd_id', $ipd_id);
        $this->db->order_by('eye_prescriptions.date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get refraction data for an eye prescription
     */
    public function getRefractions($eye_prescription_id)
    {
        $this->db->select('*');
        $this->db->from('eye_prescription_refractions');
        $this->db->where('eye_prescription_id', $eye_prescription_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Add refraction data
     */
    public function addRefraction($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('eye_prescription_refractions', $data);
            return $data['id'];
        } else {
            $this->db->insert('eye_prescription_refractions', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Delete refractions by prescription id
     */
    public function deleteRefractionsByPrescription($eye_prescription_id)
    {
        $this->db->where('eye_prescription_id', $eye_prescription_id);
        $this->db->delete('eye_prescription_refractions');
    }

    /**
     * Get medicines for an eye prescription
     */
    public function getMedicines($eye_prescription_id)
    {
        $this->db->select('eye_prescription_medicines.*, pharmacy.medicine_name, medicine_category.medicine_category, medicine_dosage.dosage as medicine_dosage, dose_interval.name as dose_interval, dose_duration.name as dose_duration');
        $this->db->from('eye_prescription_medicines');
        $this->db->join('pharmacy', 'pharmacy.id = eye_prescription_medicines.pharmacy_id', 'left');
        $this->db->join('medicine_category', 'medicine_category.id = pharmacy.medicine_category_id', 'left');
        $this->db->join('medicine_dosage', 'medicine_dosage.id = eye_prescription_medicines.dosage_id', 'left');
        $this->db->join('dose_interval', 'dose_interval.id = eye_prescription_medicines.dose_interval_id', 'left');
        $this->db->join('dose_duration', 'dose_duration.id = eye_prescription_medicines.dose_duration_id', 'left');
        $this->db->where('eye_prescription_medicines.eye_prescription_id', $eye_prescription_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Add medicine to eye prescription
     */
    public function addMedicine($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('eye_prescription_medicines', $data);
            return $data['id'];
        } else {
            $this->db->insert('eye_prescription_medicines', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Delete medicines by prescription id
     */
    public function deleteMedicinesByPrescription($eye_prescription_id)
    {
        $this->db->where('eye_prescription_id', $eye_prescription_id);
        $this->db->delete('eye_prescription_medicines');
    }

    /**
     * Delete entire eye prescription and related data
     */
    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        // Cascade deletes via FK, but explicit for safety
        $this->db->where('eye_prescription_id', $id);
        $this->db->delete('eye_prescription_refractions');

        $this->db->where('eye_prescription_id', $id);
        $this->db->delete('eye_prescription_medicines');

        $this->db->where('id', $id);
        $this->db->delete('eye_prescriptions');

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        return true;
    }

    /**
     * Get medicine list for dropdown (from pharmacy)
     */
    public function getMedicineList()
    {
        $this->db->select('pharmacy.id, pharmacy.medicine_name, medicine_category.medicine_category');
        $this->db->from('pharmacy');
        $this->db->join('medicine_category', 'medicine_category.id = pharmacy.medicine_category_id', 'left');
        $this->db->order_by('pharmacy.medicine_name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get dosage list
     */
    public function getDosageList()
    {
        $query = $this->db->get('medicine_dosage');
        return $query->result_array();
    }

    /**
     * Get dose interval list
     */
    public function getDoseIntervalList()
    {
        $query = $this->db->get('dose_interval');
        return $query->result_array();
    }

    /**
     * Get dose duration list
     */
    public function getDoseDurationList()
    {
        $query = $this->db->get('dose_duration');
        return $query->result_array();
    }

    /**
     * Count total eye prescriptions for a patient
     */
    public function countByPatient($patient_id)
    {
        $this->db->where('patient_id', $patient_id);
        return $this->db->count_all_results('eye_prescriptions');
    }
}
