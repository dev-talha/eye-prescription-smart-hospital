-- ============================================
-- Eye Prescription Module - Database Migration
-- Smart Hospital - Dr. Ibrahim Eye Care Center
-- ============================================

-- Main Eye Prescription Table
CREATE TABLE IF NOT EXISTS `eye_prescriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int DEFAULT NULL,
  `opd_id` int DEFAULT NULL,
  `visit_id` int DEFAULT NULL,
  `doctor_id` int DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  -- Chief Complaint
  `chief_complaint` text,
  -- General Health
  `dm` enum('Yes','No','NA') DEFAULT 'NA',
  `htn` enum('Yes','No','NA') DEFAULT 'NA',
  `rbs` varchar(50) DEFAULT NULL,
  `bp` varchar(50) DEFAULT NULL,
  `pulse` varchar(50) DEFAULT NULL,
  -- Tear/Drainage Tests
  `spt_re` varchar(100) DEFAULT NULL,
  `spt_le` varchar(100) DEFAULT NULL,
  `schirmer_re` varchar(100) DEFAULT NULL,
  `schirmer_le` varchar(100) DEFAULT NULL,
  -- Patient History
  `medical_history` text,
  `surgical_history` text,
  -- Vision Test
  `va_dist_unaided_re` varchar(50) DEFAULT NULL,
  `va_dist_unaided_le` varchar(50) DEFAULT NULL,
  `va_dist_aided_re` varchar(50) DEFAULT NULL,
  `va_dist_aided_le` varchar(50) DEFAULT NULL,
  -- Eye Examination
  `lid_re` varchar(255) DEFAULT NULL,
  `lid_le` varchar(255) DEFAULT NULL,
  `cornea_re` varchar(255) DEFAULT NULL,
  `cornea_le` varchar(255) DEFAULT NULL,
  `pupil_re` varchar(255) DEFAULT NULL,
  `pupil_le` varchar(255) DEFAULT NULL,
  `lens_re` varchar(255) DEFAULT NULL,
  `lens_le` varchar(255) DEFAULT NULL,
  `cd_re` varchar(50) DEFAULT NULL,
  `cd_le` varchar(50) DEFAULT NULL,
  `angle_van_re` varchar(255) DEFAULT NULL,
  `angle_van_le` varchar(255) DEFAULT NULL,
  `fundus_re` varchar(255) DEFAULT NULL,
  `fundus_le` varchar(255) DEFAULT NULL,
  -- IOP
  `iop_re` varchar(50) DEFAULT NULL,
  `iop_le` varchar(50) DEFAULT NULL,
  `iop_method` varchar(50) DEFAULT NULL,
  -- Diagnosis / Plan
  `diagnosis` text,
  `plan` text,
  `investigation` text,
  `counseling` text,
  `followup_date` date DEFAULT NULL,
  `advice` text,
  `print_note` text,
  `eye_diagram` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `opd_id` (`opd_id`),
  KEY `doctor_id` (`doctor_id`),
  KEY `visit_id` (`visit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Refraction Data (Distance + Near, RE + LE)
CREATE TABLE IF NOT EXISTS `eye_prescription_refractions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eye_prescription_id` int NOT NULL,
  `type` enum('distance','near') NOT NULL DEFAULT 'distance',
  `sph_re` varchar(20) DEFAULT NULL,
  `cyl_re` varchar(20) DEFAULT NULL,
  `axis_re` varchar(20) DEFAULT NULL,
  `va_re` varchar(20) DEFAULT NULL,
  `sph_le` varchar(20) DEFAULT NULL,
  `cyl_le` varchar(20) DEFAULT NULL,
  `axis_le` varchar(20) DEFAULT NULL,
  `va_le` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `eye_prescription_id` (`eye_prescription_id`),
  CONSTRAINT `fk_refraction_prescription` FOREIGN KEY (`eye_prescription_id`) REFERENCES `eye_prescriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Medicine Prescription (linked to pharmacy)
CREATE TABLE IF NOT EXISTS `eye_prescription_medicines` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eye_prescription_id` int NOT NULL,
  `pharmacy_id` int DEFAULT NULL,
  `dosage_id` int DEFAULT NULL,
  `dose_interval_id` int DEFAULT NULL,
  `dose_duration_id` int DEFAULT NULL,
  `instruction` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `eye_prescription_id` (`eye_prescription_id`),
  KEY `pharmacy_id` (`pharmacy_id`),
  CONSTRAINT `fk_eyemed_prescription` FOREIGN KEY (`eye_prescription_id`) REFERENCES `eye_prescriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
