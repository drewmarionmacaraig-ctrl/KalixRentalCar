-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: kalix_rental_car
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addons`
--

DROP TABLE IF EXISTS `addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pricing_type` enum('per_day','flat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'per_day',
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addons`
--

LOCK TABLES `addons` WRITE;
/*!40000 ALTER TABLE `addons` DISABLE KEYS */;
INSERT INTO `addons` VALUES (1,'Professional Driver','Experienced Palawan local driver (8 hrs/day, excludes driver meals/accommodation for out-of-town).','per_day',1000.00,1,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(2,'Child Safety Seat','ISOFIX/belt-compatible toddler or infant car seat.','per_day',250.00,1,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(3,'Airport / Hotel Delivery & Pickup','Convenient vehicle drop-off and pickup within Puerto Princesa City proper.','flat',500.00,1,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(4,'El Nido / San Vicente Out-of-Town Permit','Extended northern Palawan travel clearance & roadside support.','flat',1500.00,1,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(5,'Roof Rack / Cargo Carrier','Extra luggage capacity for surfboards or group travel.','per_day',300.00,1,'2026-11-25 17:25:47','2026-11-25 17:25:47');
/*!40000 ALTER TABLE `addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_addons`
--

DROP TABLE IF EXISTS `booking_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_addons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `addon_id` bigint unsigned DEFAULT NULL,
  `name_snapshot` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pricing_type_snapshot` enum('per_day','flat') COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price_snapshot` decimal(10,2) NOT NULL,
  `days` smallint unsigned NOT NULL DEFAULT '1',
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_addons_booking_id_foreign` (`booking_id`),
  KEY `booking_addons_addon_id_foreign` (`addon_id`),
  CONSTRAINT `booking_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_addons_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_addons`
--

LOCK TABLES `booking_addons` WRITE;
/*!40000 ALTER TABLE `booking_addons` DISABLE KEYS */;
INSERT INTO `booking_addons` VALUES (1,2,2,'Child Safety Seat','per_day',250.00,3,750.00,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(2,4,2,'Child Safety Seat','per_day',250.00,2,500.00,'2026-11-25 17:44:01','2026-11-25 17:44:01');
/*!40000 ALTER TABLE `booking_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_extensions`
--

DROP TABLE IF EXISTS `booking_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_extensions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `previous_return_datetime` datetime NOT NULL,
  `new_return_datetime` datetime NOT NULL,
  `extra_days` smallint unsigned NOT NULL,
  `rate_per_day` decimal(10,2) NOT NULL,
  `total_added_amount` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_extensions_booking_id_foreign` (`booking_id`),
  KEY `booking_extensions_approved_by_foreign` (`approved_by`),
  CONSTRAINT `booking_extensions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `booking_extensions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_extensions`
--

LOCK TABLES `booking_extensions` WRITE;
/*!40000 ALTER TABLE `booking_extensions` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_extensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_payments`
--

DROP TABLE IF EXISTS `booking_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `payment_type` enum('down_payment','balance','extension','full') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'down_payment',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_payments_booking_id_foreign` (`booking_id`),
  KEY `booking_payments_verified_by_foreign` (`verified_by`),
  CONSTRAINT `booking_payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_payments`
--

LOCK TABLES `booking_payments` WRITE;
/*!40000 ALTER TABLE `booking_payments` DISABLE KEYS */;
INSERT INTO `booking_payments` VALUES (1,1,'down_payment','gcash',7000.00,'GC-908172635',NULL,'verified',1,'2026-11-23 17:25:47',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(2,2,'down_payment','palawanpay',5625.00,'PP-445566778',NULL,'pending',NULL,NULL,NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(3,3,'full','bank_transfer',6500.00,'BDO-77123890',NULL,'verified',1,'2026-11-13 17:25:47',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47');
/*!40000 ALTER TABLE `booking_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_id` bigint unsigned NOT NULL,
  `renter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drivers_license_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drivers_license_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `government_id_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `government_id_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_driver_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_driver_license` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_driver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_driver_license` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pickup_datetime` datetime NOT NULL,
  `return_datetime` datetime NOT NULL,
  `actual_return_datetime` datetime DEFAULT NULL,
  `pickup_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_intended_use` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `daily_rate_snapshot` decimal(10,2) NOT NULL,
  `total_days` smallint unsigned NOT NULL,
  `base_amount` decimal(10,2) NOT NULL,
  `addons_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `processing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `down_payment_amount` decimal(10,2) NOT NULL,
  `remaining_balance` decimal(10,2) NOT NULL,
  `payment_option` enum('down_payment','full') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'down_payment',
  `payment_method` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gcash',
  `status` enum('pending_payment','payment_submitted','confirmed','rented','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `contract_accepted_at` timestamp NULL DEFAULT NULL,
  `contract_signature_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `release_checklist` json DEFAULT NULL,
  `return_checklist` json DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  KEY `bookings_availability_idx` (`vehicle_id`,`status`,`pickup_datetime`,`return_datetime`),
  CONSTRAINT `bookings_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,'KLX-2026-0001',4,'Marco Santos','0917-555-0142','marco.santos@example.com','N01-18-045210',NULL,'P1234567A',NULL,'Marco Santos','N01-18-045210',NULL,NULL,'2026-11-25 09:00:00','2026-11-28 17:00:00',NULL,'Puerto Princesa Airport','Foton Tagburos, Puerto Princesa','Puerto Princesa - El Nido Tour',4500.00,3,13500.00,500.00,0.00,0.00,14000.00,7000.00,7000.00,'down_payment','gcash','rented','2026-11-23 17:25:47','Marco Santos','{\"fuel\": \"Full tank\", \"tires\": \"Good condition + spare\", \"lights\": \"All working\", \"exterior\": \"Clean, minor scratch on rear bumper\", \"interior\": \"Clean, 7 seats intact\", \"accessories\": \"OR/CR copy, tools, jack\"}',NULL,NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(2,'KLX-2026-0002',1,'Clarisse Mendoza','0918-234-8891','clarisse.m@example.com','D04-20-112934',NULL,'UMID-0092-1122',NULL,'Clarisse Mendoza','D04-20-112934',NULL,NULL,'2026-11-28 08:00:00','2026-12-01 08:00:00',NULL,'Foton Tagburos, Puerto Princesa','Foton Tagburos, Puerto Princesa','Sabang Underground River & City Tour',3500.00,3,10500.00,750.00,0.00,0.00,11250.00,5625.00,5625.00,'down_payment','palawanpay','payment_submitted','2026-11-25 14:25:47','Clarisse Mendoza',NULL,NULL,NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(3,'KLX-2026-0003',2,'Jerome Villanueva','0927-441-9023','jerome.v@example.com','N02-19-887766',NULL,'DL-N02-19-887766',NULL,'Jerome Villanueva','N02-19-887766',NULL,NULL,'2026-11-16 09:00:00','2026-11-19 09:00:00','2026-11-19 09:15:00','Puerto Princesa Airport','Puerto Princesa Airport','Puerto Princesa City',2000.00,3,6000.00,500.00,0.00,0.00,6500.00,6500.00,0.00,'full','bank_transfer','completed','2026-11-13 17:25:47','Jerome Villanueva',NULL,NULL,NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(4,'KLX-2026-BLIUWY',2,'Drew Marion F Macaraig','09123456789','macaraigdrew99@gmail.com','12346578',NULL,'Passport',NULL,'Drew Marion F Macaraig','12346578',NULL,NULL,'2026-11-27 09:00:00','2026-11-29 09:00:00',NULL,'Foton Tagburos, Puerto Princesa','Foton Tagburos, Puerto Princesa','Puerto Princesa / Palawan Tour',2000.00,2,4000.00,500.00,0.00,0.00,4500.00,2250.00,2250.00,'down_payment','gcash','pending_payment','2026-11-25 17:44:19','Drew Marion F Macaraig',NULL,NULL,NULL,'2026-11-25 17:44:01','2026-11-25 17:44:19');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_id` bigint unsigned DEFAULT NULL,
  `status` enum('new','replied','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inquiries_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `inquiries_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inquiries`
--

LOCK TABLES `inquiries` WRITE;
/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
INSERT INTO `inquiries` VALUES (1,'Angela Reyes','0917-882-3310','angela.reyes@example.com','Group rate for 10-day Hiace rental','Hi! Do you offer a discounted rate if we book the Toyota Hiace for 10 days from Puerto Princesa to El Nido and back?',5,'new',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(2,'David Tan','0998-112-4500','david.tan@example.com','Early morning airport pickup','Our flight lands at 6:30 AM at PPS Airport. Can we arrange early vehicle release?',NULL,'replied','Replied via Viber — confirmed 6:30 AM release is available with ₱500 airport delivery fee.','2026-11-25 17:25:47','2026-11-25 17:25:47');
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000001_create_vehicles_table',1),(5,'2026_01_01_000002_create_vehicle_images_table',1),(6,'2026_01_01_000003_create_addons_table',1),(7,'2026_01_01_000004_create_bookings_table',1),(8,'2026_01_01_000005_create_booking_addons_table',1),(9,'2026_01_01_000006_create_booking_payments_table',1),(10,'2026_01_01_000007_create_booking_extensions_table',1),(11,'2026_01_01_000008_create_payment_settings_table',1),(12,'2026_01_01_000009_create_inquiries_table',1),(13,'2026_01_01_000010_add_availability_index_to_bookings_table',2),(14,'2026_01_01_000011_create_payment_methods_table',3),(15,'2026_01_01_000012_add_id_images_to_bookings_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instructions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES (1,'gcash','GCash','KLX Affordable Cars for Rent','0907-883-8861',NULL,NULL,1,1,'2026-11-25 17:48:11','2026-11-25 17:48:11'),(2,'palawanpay','PalawanPay','KLX Affordable Cars for Rent','0907-883-8861',NULL,NULL,1,2,'2026-11-25 17:48:11','2026-11-25 17:48:11'),(3,'bank_transfer','Bank Transfer (BDO / BPI)','KLX Affordable Cars for Rent','0012-3456-7890',NULL,NULL,1,3,'2026-11-25 17:48:11','2026-11-25 17:48:11');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_settings`
--

DROP TABLE IF EXISTS `payment_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gcash_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `gcash_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gcash_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gcash_qr_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `palawanpay_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `palawanpay_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `palawanpay_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `palawanpay_qr_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_qr_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_card_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `credit_card_fee_percentage` decimal(5,2) NOT NULL DEFAULT '6.00',
  `down_payment_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `down_payment_percentage` decimal(5,2) NOT NULL DEFAULT '50.00',
  `down_payment_fixed_amount` decimal(10,2) NOT NULL DEFAULT '5000.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_settings`
--

LOCK TABLES `payment_settings` WRITE;
/*!40000 ALTER TABLE `payment_settings` DISABLE KEYS */;
INSERT INTO `payment_settings` VALUES (1,1,'KLX Affordable Cars for Rent','0907-883-8861',NULL,1,'KLX Affordable Cars for Rent','0907-883-8861',NULL,1,'BDO / BPI','KLX Affordable Cars for Rent','0012-3456-7890',NULL,1,6.00,'percentage',50.00,5000.00,'2026-11-25 17:25:47','2026-11-25 17:25:47');
/*!40000 ALTER TABLE `payment_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('00lMfFMRP816x8VtR1TS1wGJq2U5uOZ27wI8e4zU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456','eyJfdG9rZW4iOiJQdmh2b0VaY3lNWEZkTUFmWnIyUklTc2RRcHVacUZRRDVCM096S29QIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC92ZWhpY2xlc1wvdG95b3RhLWlubm92YS0yOC1lIiwicm91dGUiOiJjYXRhbG9nLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1795656980),('2ORCnwgf8na7JDBNwgXFO4nnOFrlRUGATQx23diT',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456','eyJfdG9rZW4iOiJjOGttaFlIQjU4YkpkNzJ4dHJYWHZZRXRxczM5M1VhNEExbHJLWVhNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvbG9naW4iLCJyb3V0ZSI6ImFkbWluLmxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1795656984),('BAc6oLrYEfCjEyOFrSSA4bG5B1hpF3PsYP569i9y',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456','eyJfdG9rZW4iOiJyanRyQVVURUlQNjFNYzRIdGRDUjh4cjBRNmR6aUNpV1NFYlNDTW82IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1795656978),('CFPbaDmWvoHXAubyyCPhkHNEYWhNpxte4ESSz9Ps',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456','eyJfdG9rZW4iOiI0SG0xclNtRUF6WjVkQ2UwWklqMFVJZEFPT2xVTGFRTmtPdUhhZFJiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9pbnF1aXJ5Iiwicm91dGUiOiJpbnF1aXJ5LmNyZWF0ZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795656983),('DaypRE4W18gAiUA288A2kejfMxTfQGTUU19IukHx',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456','eyJfdG9rZW4iOiJyWFhjZlB2em1VOXFCOWZIcmVnZkxycEs2M1N4bkh2dWhsQ2FSVkY2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ib29rXC90b3lvdGEtaW5ub3ZhLTI4LWUiLCJyb3V0ZSI6ImJvb2tpbmcuY2hlY2tvdXQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1795656981),('gwwzhAIzapL5geYHIrWM8zJEZIdDtBVBWQyu5El1',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJqdlk5bmJQeE1YdGcxN1V0WlBGQkU2NXd3MWNxUVlIc3hKU3dqeHpDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1795658258),('QYrT9aM7Im7PDE7P6Jv1hb9pLll0jjlsRzvfhiuD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456','eyJfdG9rZW4iOiJ2UGxDdHZiTTd2ZW9RbWx5RmhYMWpCaWV4RkJnWTRHN3VWMUU3S2QzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC92ZWhpY2xlcyIsInJvdXRlIjoiY2F0YWxvZy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795656979),('XgGMgNdfOGhvzWXquDroiwFSi50OVkyAubEdXi40',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJXeWplOVdiSll3V3NjRmJ6N0EyeTB2czBTT3lwOVJjSjdXVkxVT1B4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiMzA1ODg1MDkyZTBhNzMyYWMzZWVjMjQ5MDIwNzFhNDRlNGExZWEwMDhlZDExZWMxN2Y1ZjFlOWZiMDhmNzk1NyJ9',1795658244);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'KLX Admin','admin@kalixrental.ph',NULL,'$2y$12$GMrzLK/B9vkWZ0aMA0xuF.omjTTASJAwuDhSOHDbeljERTEejkKl2',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicle_images`
--

DROP TABLE IF EXISTS `vehicle_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicle_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_images_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `vehicle_images_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicle_images`
--

LOCK TABLES `vehicle_images` WRITE;
/*!40000 ALTER TABLE `vehicle_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicle_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` smallint unsigned NOT NULL DEFAULT '2024',
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MPV',
  `transmission` enum('Automatic','Manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Automatic',
  `seats` tinyint unsigned NOT NULL DEFAULT '5',
  `fuel_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Gasoline',
  `daily_rate` decimal(10,2) NOT NULL,
  `status` enum('available','rented','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `primary_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_slug_unique` (`slug`),
  UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,'Toyota Innova 2.8 E','toyota-innova-28-e','Toyota','Innova',2024,'KLX-1001','MPV','Automatic',7,'Diesel',3500.00,'available',1,'Spacious 7-seater diesel MPV ideal for families and groups exploring Puerto Princesa, Sabang, and El Nido.',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(2,'Toyota Vios 1.3 XLE','toyota-vios-13-xle','Toyota','Vios',2024,'KLX-1002','Sedan','Automatic',5,'Gasoline',2000.00,'available',1,'Fuel-efficient and easy-to-drive sedan, great for city errands and couples touring Palawan.',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(3,'Mitsubishi Xpander GLS','mitsubishi-xpander-gls','Mitsubishi','Xpander',2024,'KLX-1003','MPV','Automatic',7,'Gasoline',3000.00,'available',1,'Modern 7-seater crossover MPV with high ground clearance and cold dual-zone air conditioning.',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(4,'Toyota Fortuner 2.4 G','toyota-fortuner-24-g','Toyota','Fortuner',2023,'KLX-1004','SUV','Automatic',7,'Diesel',4500.00,'rented',0,'Rugged 7-seater SUV built for long highway drives and rough coastal roads across Palawan.',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(5,'Toyota Hiace Commuter Deluxe','toyota-hiace-commuter-deluxe','Toyota','Hiace',2023,'KLX-1005','Van','Manual',15,'Diesel',5000.00,'available',0,'15-seater passenger van for barkada trips, corporate outings, and airport transfers.',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47'),(6,'Toyota Wigo 1.0 G','toyota-wigo-10-g','Toyota','Wigo',2024,'KLX-1006','Hatchback','Automatic',5,'Gasoline',1600.00,'maintenance',0,'Compact, budget-friendly hatchback perfect for solo travelers or quick Puerto Princesa city trips.',NULL,'2026-11-25 17:25:47','2026-11-25 17:25:47');
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-26 22:13:25
