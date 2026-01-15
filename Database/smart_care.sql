-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 12:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_care`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `disease_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`, `disease_description`) VALUES
(2, 4, 6, '2026-04-12', '11:00:00', 'Confirmed', NULL),
(3, 4, 5, '2026-12-06', '12:00:00', 'Cancelled', NULL),
(4, 3, 5, '2026-01-14', '05:00:00', 'Cancelled', NULL),
(5, 3, 7, '2026-01-15', '06:00:00', 'Confirmed', NULL),
(6, 6, 7, '2026-01-15', '07:00:00', 'Confirmed', NULL),
(7, 6, 11, '2026-01-14', '07:00:00', 'Confirmed', NULL),
(8, 6, 5, '2026-01-15', '06:00:00', 'Confirmed', NULL),
(9, 6, 11, '2026-01-15', '06:00:00', 'Confirmed', NULL),
(10, 6, 5, '2026-01-15', '06:00:00', '', NULL),
(11, 6, 8, '2026-01-17', '07:00:00', 'Confirmed', NULL),
(12, 7, 8, '2026-01-15', '12:00:00', 'Confirmed', NULL),
(13, 7, 10, '2026-01-15', '05:00:00', 'Confirmed', NULL),
(14, 7, 5, '2026-01-15', '07:00:00', '', NULL),
(15, 7, 5, '2026-01-15', '05:00:00', '', NULL),
(16, 7, 5, '2026-01-15', '06:00:00', '', NULL),
(17, 7, 11, '2026-01-22', '12:00:00', 'Confirmed', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`) VALUES
(8, 'Faisalabad'),
(5, 'Hyderabad'),
(3, 'Islamabad'),
(1, 'Karachi'),
(2, 'Lahore'),
(7, 'Peshawar '),
(6, 'Quetta'),
(9, 'Rawalpindi ');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contact_id`, `user_id`, `role_id`, `full_name`, `email`, `message`, `created_at`) VALUES
(1, NULL, NULL, 'fatima', 'fatima@gmail.com', 'There are not so many doctors kindly increase your staff.', '2026-01-10 23:54:24'),
(2, 26, 3, 'Amna Batool', 'amna@gmail.com', 'You guys are working hard to make your website look clean and user friendly. All the best', '2026-01-12 00:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `doctor_phone` varchar(15) DEFAULT NULL,
  `doctor_email` varchar(100) DEFAULT NULL,
  `availability_status` text DEFAULT NULL,
  `consultation_fee` int(11) DEFAULT 0,
  `doc_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `city_id`, `specialization`, `qualification`, `experience_years`, `doctor_phone`, `doctor_email`, `availability_status`, `consultation_fee`, `doc_name`) VALUES
(5, 12, 1, 'General Physician ', NULL, NULL, NULL, NULL, NULL, 0, NULL),
(6, 16, 5, 'Psychologist ', NULL, NULL, NULL, NULL, NULL, 0, NULL),
(7, 19, 1, ' Physiotherapist ', 'MBBS , FPS', 4, '038765432', 'hadeed123@gmail.com', 'Available', 1800, 'Hadeed Raza '),
(8, 22, 1, 'General Physician ', 'MBBS', 2, '34567821', 'umer@gmail.com', 'Available', 750, 'Umer'),
(9, 23, 6, 'Psychologist ', NULL, 4, NULL, NULL, NULL, 3500, NULL),
(10, 24, 1, ' Physiotherapist ', NULL, 3, NULL, NULL, NULL, 3400, NULL),
(11, 25, 1, 'General Physician ', NULL, 3, NULL, NULL, NULL, 1200, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `medical_info`
--

CREATE TABLE `medical_info` (
  `info_id` int(11) NOT NULL,
  `disease_name` varchar(150) DEFAULT NULL,
  `prevention` text DEFAULT NULL,
  `cure` text DEFAULT NULL,
  `info_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_info`
--

INSERT INTO `medical_info` (`info_id`, `disease_name`, `prevention`, `cure`, `info_image`) VALUES
(1, 'Flu', 'Flu Prevention Steps:\r\nHand Hygiene: Wash your hands with soap and warm water for at least 20 seconds, especially after returning home from outside.\r\n\r\nMask Usage: Use a mask in congested or crowded places to protect yourself from airborne germs.\r\n\r\nHydration: Drink plenty of water and healthy fluids (such as soup or juice) throughout the day to maintain your immunity.\r\n\r\nAvoid Contact: Maintain a proper distance from anyone who already has the flu and keep their personal items (towels, utensils) separate.\r\n\r\nHealthy Diet: Include fruits rich in Vitamin C (Oranges, Lemon) and vegetables in your daily diet.', 'Flu Treatment & Cure:\r\nProper Rest: The body needs complete bed rest to recover effectively.\r\n\r\nHydration: Drink plenty of water, fresh juices, and soup to prevent dehydration in the body.\r\n\r\nFever Management: Use Paracetamol or Panadol for fever and body aches (but always follow a doctor\'s instructions).\r\n\r\nSteam Inhalation: Take steam from hot water to clear a blocked nose and chest congestion.\r\n\r\nSoothing Drinks: Use honey with ginger tea or warm saline water to soothe a sore throat.', '1768155855_Influenza.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `medical_news`
--

CREATE TABLE `medical_news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `news_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_news`
--

INSERT INTO `medical_news` (`news_id`, `title`, `content`, `news_image`) VALUES
(2, 'Seasonal Changes and Influenza: How to Stay Protected This Year?', 'As winter approaches, a rapid increase in cases of cold, flu, and Influenza is observed. Many people dismiss it as a common cold, but if proper precautions are not taken, it can lead to lung infections and other complications.\r\n\r\nWhat are the Symptoms of Influenza?\r\nFlu symptoms are slightly different and more severe than a common cold. These include a sudden high fever, intense body aches, cough, and feeling of exhaustion. Sometimes, a sore throat and a runny nose are also reported.\r\n\r\nBest Ways to Prevent Influenza:\r\nVaccination: Getting a flu vaccine every year is the most effective way to stay protected from this disease.\r\n\r\nHand Hygiene: Washing your hands with soap several times a day eliminates germs.\r\n\r\nUsing a Mask: Wearing a mask in crowded places can protect you from viruses present in the air.\r\n\r\nImmunity: Consume fruits rich in Vitamin C, such as oranges and kinnow, so your body can fight off illnesses.\r\n\r\nWhen to See a Doctor?\r\nIf a fever lasts for more than 3 days or if you experience difficulty breathing, contact your nearby doctor or a SmartCARE clinic immediately. Remember, prevention is better than cure.', '1767979433_Influenza.jpg'),
(3, 'Revolutionizing Healthcare: The Role of Artificial Intelligence in Modern Diagnostics', 'Artificial Intelligence (AI) is rapidly transforming the landscape of modern medicine, bringing unprecedented accuracy and speed to patient diagnostics. From analyzing complex medical imaging like X-rays and MRIs to predicting potential health risks before they become critical, AI-driven tools are empowering doctors to make more informed decisions. These technological advancements are not just limited to large hospitals; they are becoming integrated into digital health portals like SmartCARE, allowing for seamless management of patient records and automated appointment scheduling.\r\n\r\nThe integration of AI helps in identifying patterns in vast amounts of medical data that might be invisible to the human eye, such as early-stage cardiovascular issues or minor oncological changes. Furthermore, tele-consultation and remote patient monitoring are now more efficient than ever, ensuring that patients in rural areas receive the same quality of care as those in urban centers. As we move further into 2026, the focus is shifting towards \"Preventative Care,\" where digital health systems use real-time data to suggest lifestyle changes and early interventions. This shift not only improves the overall patient recovery rate but also significantly reduces the financial burden on healthcare systems. For healthcare professionals and patients alike, embracing these smart technologies is no longer an option but a necessity for a healthier, more efficient future.', '1768176728_cigarette-8771248_1280.png'),
(4, 'Why Annual Health Check-ups are Your Best Defense Against Silent Diseases', 'In the hustle and bustle of modern life, our health often takes a backseat until a major problem arises. However, the cornerstone of modern medicine is shifting from treatment to prevention. Annual health check-ups are not just \"optional\" appointments; they are essential tools for maintaining long-term wellness.\r\n\r\nMany life-threatening conditions, such as hypertension, Type 2 diabetes, and certain early-stage cancers, often present no symptoms in their initial phases. By the time a patient feels \"sick,\" the condition may have already progressed. Through routine screenings, blood tests, and physical examinations, doctors can identify risk factors and intervene early.\r\n\r\nKey Benefits of Regular Screenings:\r\n\r\nEarly Detection: Catching a disease early significantly increases the chances of successful treatment.\r\n\r\nCost-Savings: Preventive care is much more affordable than emergency surgeries or long-term chronic disease management.\r\n\r\nPersonalized Health Roadmap: A check-up allows your doctor to provide tailored advice on diet, exercise, and mental health based on your specific body metrics.\r\n\r\nAt our hospital, we believe that health is a proactive journey. Investing a few hours once a year in a comprehensive screening can add years of healthy living to your life.', '1768390563_medicine-350935_1280.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dob` date DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `patient_phone` varchar(15) DEFAULT NULL,
  `patient_gmail` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `dob`, `blood_group`, `gender`, `patient_phone`, `patient_gmail`) VALUES
(3, 15, '1997-12-31', 'AB+', 'Female', NULL, NULL),
(4, 20, '2000-02-02', 'O+', 'Male', NULL, NULL),
(5, 26, '2005-06-14', 'AB+', 'Female', NULL, NULL),
(6, 27, '2000-12-19', 'AB+', 'Female', NULL, NULL),
(7, 28, '2005-06-14', 'B+', 'Female', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Administrator'),
(2, 'Doctor'),
(3, 'Patient');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role_id`, `email`, `full_name`, `address`, `phone_number`, `reset_token`, `token_expiry`) VALUES
(1, 'Admin', '$2y$10$8nRixpjFOAw/k.E4KrBe3ekzM82NP4reRVM8tNiYdIwKKtkeEO7Du', 1, 'admin@gmail.com', 'Administrator', 'Aptech', '03121234567', NULL, NULL),
(12, 'hussain123@gmail.com', '$2y$10$av1BpSnKvC5jfT6PEHYrM.pDwOnfNblJ2qhJdIr1pb/SVkImYI3mi', 2, 'hussain123@gmail.com', 'Hussain Ali', 'Model Colony', '030247865', NULL, NULL),
(13, 'amna', 'amna123', 1, 'amna123@gmail.com', 'Amna Batool', 'Gulbahar, Karachi', '0338877689', NULL, NULL),
(15, 'fatima@gmail.com', '$2y$10$s4NqCwoAdb65NOrv9rYmgOZHjqifc/6/fuVfP3Nke2Vy7QaMBvaIS', 3, 'fatima@gmail.com', 'Fatima', 'Malir Karachi', '039876541', NULL, NULL),
(16, 'ahmed@gmail.com', '$2y$10$Dc.HfzynAZXWaFkHWtjX4en/4oymDpsnvA2UW3uTvnusm3VQZ4Qkq', 2, 'ahmed@gmail.com', 'Ahmed', 'Lateefabad', '0305462782', NULL, NULL),
(18, 'nida@gmail.com', '$2y$10$DL7BXWw09KdS3rMEISkiDu1/uXtMUICO5CrdQDNUmA6Degd2o09hu', 2, 'nida@gmail.com', 'Dr. Nida', 'north nazimabad', '0312876549', NULL, NULL),
(19, 'Hadeed', '$2y$10$e9a2IXhB6.Vi5O0XTWM3o.7ZG2MdPt8dBGWVJJEDAIUmY.c7g1RyK', 2, 'hadeed123@gmail.com', 'Hadeed Raza', 'north nazimabad', '038765432', NULL, NULL),
(20, 'aliyaar@gmail.com', '$2y$10$NaBid4wct1hrPrUbjbbNHOMT2SBVFEt6YSap.xlowh9PSGGJu0kkO', 3, 'aliyaar@gmail.com', 'Aliyaar', 'North Nazimabad', '03221234590', NULL, NULL),
(21, 'mairaj', '$2y$10$Q3r.H5BjOOJLr.DBfSbHmeA0jhDmsFYn9POgn3XvAhOh5/2aHKs0e', 2, 'mairajkhan@gmail.com', 'Mairaj Khan', 'North Nazimabad', '03221234580', NULL, NULL),
(22, 'Umer ', '$2y$10$0R6Ln9TX06J2/EQmwJPmU.jHm7MMBzhO9qLcgQJMuh8YC5rMVMKhe', 2, 'umer@gmail.com', 'Muhammad Umer', 'north nazimabad', '034876522', NULL, NULL),
(23, 'raza', '$2y$10$OD98LPQDSeq8cBXBV1tlqO8uX53Htw0p9I5gxZB1fX1NLDo76uLfW', 2, 'raza@gmail.com', 'Muhammad Raza', 'turbat', '0334455667', NULL, NULL),
(24, 'Yahya', '$2y$10$E/m6NovnOKYIVZAAHhpjiedlm5UsVp77ta8MyILJkTe2Plp4PMrri', 2, 'yahya@gmail.com', 'Yahya ', 'Saddar', '039876542', NULL, NULL),
(25, 'aliyan', '$2y$10$R1F2lnsqdnINO9xEpctcBe9h7qmsJhxyWh7exPgS7RspJBrjGUwqa', 2, 'aliyan@gmail.com', 'Aliyan', 'north nazimabad', '032145678', NULL, NULL),
(26, 'amna@gmail.com', '$2y$10$wjwOYHlo0skgmPOxingSnOBJw0wgleIcXlIaD8NeAQvReVFNlBiOW', 3, 'amna@gmail.com', 'Amna Batool', 'Gulbahar', '0332244167', NULL, NULL),
(27, 'batoolamna002@gmail.com', '$2y$10$gX/UQ8EhZavneqaIcmGwUuunrY8XFsqp4wNkKuPzJk3Wv4TF/KF4W', 3, 'batoolamna002@gmail.com', 'zahra', 'north nazimabad', '03123459876', NULL, NULL),
(28, 'amnaxtrade@gmail.com', '$2y$10$pu9Rxlh7.wp7x5MbONX0beJmGcGQZV2DXBwK6EPDhmnFNDahlsb8a', 3, 'amnaxtrade@gmail.com', 'ayesha', 'north nazimabad', '03987654321', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`),
  ADD UNIQUE KEY `city_name` (`city_name`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `medical_info`
--
ALTER TABLE `medical_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `medical_news`
--
ALTER TABLE `medical_news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `medical_info`
--
ALTER TABLE `medical_info`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `medical_news`
--
ALTER TABLE `medical_news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
