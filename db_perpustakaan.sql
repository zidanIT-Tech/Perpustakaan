-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 07:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `pengarang` varchar(255) NOT NULL,
  `tahun_terbit` int(4) NOT NULL,
  `deskripsi` text NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `stok` int(11) NOT NULL,
  `id_rak` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `id_kategori`, `judul_buku`, `gambar`, `pengarang`, `tahun_terbit`, `deskripsi`, `user`, `stok`, `id_rak`) VALUES
(89, 15, 'Otonari No Tenshi', 'https://i.ytimg.com/vi/tYQG516tyGs/maxresdefault.jpg', 'Saekisan', 2019, 'Dalam anime \"Otonari no Tenshi-sama ni Itsunomanika Dame Ningen ni Sareteita Ken\", kehidupan Amane Fujimiya, seorang pemuda yang pemalu dan acuh tak acuh, mengalami perubahan yang tidak terduga dengan kedatangan Mahiru Shiina, tetangganya yang menawan dan sempurna, yang dikenal sebagai \"Anjo\". Apa yang dimulai sebagai tindakan sederhana dari kepedulian, ketika Mahiru membantunya saat sakit biasa, dengan cepat berubah menjadi serangkaian interaksi yang mengungkapkan kelemahan dan keinginan Amane. Saat Mahiru memasuki hidupnya, dia tidak hanya menjadi teman yang konstan, tetapi juga dorongan bagi Amane untuk menghadapi ketidakamanannya dan menemukan nilai dari saling merawat. Dengan sentuhan romansa dan momen pertumbuhan pribadi, cerita ini mengeksplorasi kompleksitas hubungan mereka, membawa keduanya untuk memikirkan kembali pandangan mereka tentang kehidupan dan hubungan.', 'admin', 7, 1),
(91, 15, 'One Punch Man', 'https://img1.goodfon.com/wallpaper/nbig/4/45/one-punch-man-vanpachmen-art-anime-personazhi-saitama-genos.jpg', 'ONE', 2009, 'One Punch Man adalah sebuah serial manga dan anime yang mengisahkan tentang Saitama, seorang pahlawan super dengan kekuatan yang tak tertandingi. Cerita ini berfokus pada perjalanan Saitama dalam mencari lawan yang sebanding dengan kekuatannya, meskipun ia bisa mengalahkan musuh dengan satu pukulan saja.\r\n\r\nPada awal cerita, Saitama adalah seorang pria biasa yang frustasi dengan kehidupannya yang membosankan. Ia kemudian memutuskan untuk menjadi pahlawan dan berlatih dengan keras selama tiga tahun, hasilnya ia mendapatkan kekuatan yang sangat luar biasa. Namun, kekuatannya yang tak terkalahkan membuatnya tidak merasakan tantangan dalam melawan musuh.\r\n\r\nDalam perjalanan petualangannya, Saitama bertemu dengan berbagai pahlawan super lainnya seperti Genos, seorang cyborg yang menjadi muridnya, dan banyak musuh yang mengancam keamanan kota. Meskipun demikian, Saitama selalu bisa mengalahkan musuh-musuhnya dengan satu pukulan, membuatnya merasa semakin frustrasi dengan kehidupannya yang monoton.\r\n\r\nSelain itu, cerita juga mengungkapkan fakta bahwa ada sebuah organisasi jahat yang ingin menghancurkan umat manusia. Saitama dan para pahlawan super lainnya pun berjuang untuk melawan organisasi ini dan menjaga kota dari ancaman yang datang.\r\n\r\nDalam perjalanan ceritanya, One Punch Man mengeksplorasi tema-tema seperti ketangguhan, persahabatan, dan tujuan hidup. Meskipun memiliki kekuatan yang tak terkalahkan, Saitama harus menghadapi ketidakpuasan pribadi dan mencari kepuasan dalam melakukan tugasnya sebagai pahlawan.', 'admin', 3, 2),
(94, 21, 'Dongeng Sang Kancil', 'https://th.bing.com/th/id/OIP.OK-MfHgBrhuSa-_PNYePwQHaD3?w=284&h=180&c=7&r=0&o=5&dpr=2&pid=1.7', 'Kyai R.A', 2024, 'Dongeng Sang Kancil merupakan sebuah filem animasi terbaru yang dihasilkan berdasarkan cerita rakyat Sang Kancil dan filem ini dijangka menjadi penerbitan ikonik Les’ Copaque Production yang seterusnya.\r\n\r\nFilem ini mengisahkan seekor anak kancil yang menyaksikan ibunya dibunuh oleh satu bayang hitam misteri.\r\n\r\nSetelah dewasa, Kancil bertekad mencari bayang hitam tersebut untuk membalas dendam atas kematian ibunya dan menyelamatkan Hutan Rimba daripada ancaman yang sama.\r\n\r\nDalam pengembaraannya, Kancil bertemu dengan tiga pemangsa utama yang menguasai langit, daratan, dan lautan, iaitu Helang Perkasa, Gajah Belukar, dan Raja Buaya.\r\n\r\nDengan kecerdikannya, Kancil berjaya mendapatkan sokongan daripada mereka serta penghuni hutan lain untuk menumpaskan bayang hitam tersebut.\r\n\r\n“Dongeng Sang Kancil” diadaptasi daripada cerita rakyat tradisional Melayu yang menonjolkan kecerdikan dan kelicikan Sang Kancil dalam menghadapi pelbagai cabaran.\r\n\r\nDengan kecerdasannya, Sang Kancil sering melarikan diri dari bahaya dan kadangkala membantu haiwan lain yang memerlukan bantuan.', 'admin', 10, 1),
(95, 21, 'Laskar Pelangi', 'https://miro.medium.com/v2/resize:fit:700/1*kla6IMIK6gBjmyz2l6PIiA.jpeg', 'Andrea Hirata', 2005, 'Laskar Pelangi merupakan sebuah karya inspiratif yang diangkat dari kisah nyata masa kecil sang penulis, Andrea Hirata, di daerah terpencil Belitung Timur. Novel ini mengisahkan perjuangan sepuluh anak luar biasa yang berasal dari keluarga miskin, tetapi memiliki semangat belajar yang tak tergoyahkan. Mereka bersekolah di SD Muhammadiyah Gantong, sebuah sekolah sederhana yang hampir ditutup karena kekurangan murid dan fasilitas. Namun, kehadiran mereka menghidupkan kembali semangat pendidikan di sana.\r\n\r\nDalam perjalanan mereka menuntut ilmu, kelompok anak-anak ini diberi nama “Laskar Pelangi” oleh guru mereka yang penuh cinta dan dedikasi, Bu Muslimah. Masing-masing anggota Laskar Pelangi memiliki karakter unik dan kekuatan tersendiri, seperti Lintang si jenius matematika yang harus menempuh perjalanan puluhan kilometer demi sekolah, atau Mahar si seniman eksentrik yang penuh imajinasi. Mereka menghadapi berbagai rintangan—kemiskinan, tekanan sosial, keterbatasan fasilitas, dan bahkan ancaman dari sekolah-sekolah elite yang lebih kaya.\r\n\r\nNamun, di balik semua itu, ada semangat, keceriaan, dan mimpi yang terus tumbuh. Laskar Pelangi bukan hanya tentang perjuangan meraih pendidikan, tapi juga kisah tentang persahabatan sejati, pengorbanan orang tua, dan keyakinan bahwa mimpi bisa membawa siapa pun terbang tinggi, sejauh pelangi di langit. Novel ini tidak hanya menggugah emosi, tetapi juga menjadi pengingat bahwa pendidikan adalah senjata paling ampuh untuk mengubah masa depan, meski dimulai dari tempat yang paling sederhana.', 'admin', 10, 1),
(96, 23, 'Pemrograman Web dengan PHP dan MySQL', 'https://th.bing.com/th/id/OIP.14lLF11klrawU1aGVILgDQHaHa?w=190&h=190&c=7&r=0&o=5&dpr=2&pid=1.7', 'Lukmanul Hakim', 2021, 'Panduan lengkap belajar membuat website dinamis menggunakan bahasa pemrograman PHP dan database MySQL.', 'admin', 10, 2);

--
-- Triggers `buku`
--
DELIMITER $$
CREATE TRIGGER `log_delete_buku` AFTER DELETE ON `buku` FOR EACH ROW BEGIN
  INSERT INTO log_aktivitas (tipe_data, id_data, aksi, nama_data, user_aksi)
  VALUES ('buku', OLD.id_buku, 'DELETE', OLD.judul_buku, OLD.user);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_buku` AFTER INSERT ON `buku` FOR EACH ROW BEGIN
  INSERT INTO log_aktivitas (tipe_data, id_data, aksi, nama_data, user_aksi)
  VALUES ('buku', NEW.id_buku, 'INSERT', NEW.judul_buku, NEW.user);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_buku` AFTER UPDATE ON `buku` FOR EACH ROW BEGIN
  INSERT INTO log_aktivitas (tipe_data, id_data, aksi, nama_data, user_aksi)
  VALUES ('buku', NEW.id_buku, 'UPDATE', NEW.judul_buku, NEW.user);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama`, `dibuat_oleh`) VALUES
(14, 'Pendidikan', 'admin'),
(15, 'Fiksi Visual / Komik & Ilustrasi', 'admin'),
(21, 'Fiksi', 'admin'),
(22, 'Referensi Umum', 'admin'),
(23, 'Teknologi dan Komputer', 'admin'),
(25, 'Nonfiksi', 'admin'),
(26, 'Seni & Desain', 'admin'),
(27, 'Puisi', 'admin'),
(39, 'Bisnis dan Ekonomi', 'admin');

--
-- Triggers `kategori`
--
DELIMITER $$
CREATE TRIGGER `log_delete_kategori` AFTER DELETE ON `kategori` FOR EACH ROW BEGIN
  INSERT INTO log_aktivitas (tipe_data, id_data, aksi, nama_data, user_aksi)
  VALUES ('kategori', OLD.id_kategori, 'DELETE', OLD.nama, OLD.dibuat_oleh);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_kategori` AFTER INSERT ON `kategori` FOR EACH ROW BEGIN
  INSERT INTO log_aktivitas (tipe_data, id_data, aksi, nama_data, user_aksi)
  VALUES ('kategori', NEW.id_kategori, 'INSERT', NEW.nama, NEW.dibuat_oleh);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_kategori` AFTER UPDATE ON `kategori` FOR EACH ROW BEGIN
  INSERT INTO log_aktivitas (tipe_data, id_data, aksi, nama_data, user_aksi)
  VALUES ('kategori', NEW.id_kategori, 'UPDATE', NEW.nama, NEW.dibuat_oleh);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `name`, `username`, `password`, `level`, `status`) VALUES
(35, 'Muhammad Zidan Pratama', 'zidan', 'zidan', 'siswa', 1),
(36, 'admin', 'admin', 'admin1', 'admin', 1),
(37, 'Ibu Riski', 'ibu riski', 'riski', 'petugas', 1),
(41, 'Muhammad Fauzan', 'fauzan', 'fauzan', 'siswa', 1),
(42, 'Pak Vicky', 'Vicky', 'vicky', 'petugas', 1),
(47, 'Ibu April', 'April', 'april', 'petugas', 1),
(48, 'Wahyu Indra Alhadi', 'Wahyu', 'wahyu', 'siswa', 1),
(49, 'Muhammad Rifqi Ramadhan', 'Rifqi', 'rifqi', 'siswa', 1),
(50, 'Glenn Juan Aldaro', 'Glenn', 'glenn', 'siswa', 1),
(51, 'Ghatan Abie Rieko Kumoro', 'Pablo', 'pablo', 'siswa', 1),
(52, 'Muhammad Lutfhi Fabian', 'Budi', 'budi', 'siswa', 1),
(53, 'Hilal Sultanul Adzam', 'Hilal', 'hilal', 'siswa', 1),
(54, 'Aji Ramada Dharma', 'Aji', 'aji', 'siswa', 1),
(55, 'Muhamad Ikhsan Putra Subandi', 'Ikhsan', 'ikhsan', 'siswa', 1),
(56, 'Muhammad Navies Ramadhan', 'Navies', 'navies', 'siswa', 1),
(58, 'Ibu Misbah', 'Misbah', 'misbah', 'petugas', 1),
(59, 'Aji Ramada Dharma', 'Aji', 'aji', 'siswa', 1),
(60, 'Muhammad Aldyansyah', 'Aldy', 'aldy', 'siswa', 1),
(61, 'Rafik Anugrah Yana', 'Rafik', 'rafik', 'siswa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `tipe_data` enum('buku','kategori','user') NOT NULL,
  `id_data` int(11) NOT NULL,
  `aksi` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `nama_data` varchar(255) DEFAULT NULL,
  `user_aksi` varchar(100) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `tipe_data`, `id_data`, `aksi`, `nama_data`, `user_aksi`, `waktu`) VALUES
(4, 'buku', 85, 'INSERT', 'Perjalanan Hidup Kalel', 'ibu riski', '2025-05-19 07:23:28'),
(5, 'kategori', 14, 'UPDATE', 'Komik', 'admin', '2025-05-19 07:30:50'),
(9, 'buku', 86, 'INSERT', 'aa', 'Admin', '2025-05-20 01:45:31'),
(10, 'buku', 86, 'UPDATE', 'aaa', 'Admin', '2025-05-20 01:47:40'),
(11, 'buku', 86, 'DELETE', 'aaa', 'Admin', '2025-05-20 01:47:48'),
(12, 'buku', 87, 'INSERT', 'zidan', 'Admin', '2025-05-20 03:31:38'),
(13, 'buku', 87, 'UPDATE', 'zidana', 'Admin', '2025-05-20 03:31:48'),
(14, 'buku', 87, 'DELETE', 'zidana', 'Admin', '2025-05-20 03:31:52'),
(15, 'buku', 83, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-20 22:46:38'),
(16, 'buku', 83, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-20 23:03:30'),
(17, 'buku', 83, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-20 23:12:18'),
(18, 'buku', 83, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-20 23:15:25'),
(19, 'buku', 83, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-20 23:16:40'),
(20, 'buku', 83, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-20 23:18:35'),
(21, 'buku', 84, 'UPDATE', 'One Punch Man', 'admin', '2025-05-20 23:21:01'),
(22, 'buku', 85, 'DELETE', 'Perjalanan Hidup Kalel', 'ibu riski', '2025-05-20 23:21:10'),
(23, 'buku', 88, 'INSERT', 'Dongeng Sang Kancil', 'admin', '2025-05-21 02:46:55'),
(24, 'buku', 88, 'UPDATE', 'Dongeng Sang Kancil', 'admin', '2025-05-21 02:49:55'),
(25, 'buku', 83, 'DELETE', 'Otonari No Tenshi', 'admin', '2025-05-21 12:48:16'),
(26, 'buku', 84, 'DELETE', 'One Punch Man', 'admin', '2025-05-21 12:48:58'),
(27, 'buku', 88, 'DELETE', 'Dongeng Sang Kancil', 'admin', '2025-05-21 12:49:32'),
(28, 'buku', 89, 'INSERT', 'Otonari No Tenshi', 'admin', '2025-05-21 12:58:39'),
(29, 'buku', 91, 'INSERT', 'One Punch Man', 'admin', '2025-05-21 13:20:39'),
(30, 'buku', 92, 'INSERT', 'as', 'admin', '2025-05-21 13:23:19'),
(31, 'buku', 92, 'DELETE', 'as', 'admin', '2025-05-21 13:23:25'),
(32, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 13:24:08'),
(33, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 13:24:17'),
(34, 'buku', 93, 'INSERT', 'as', 'Vicky', '2025-05-21 13:26:22'),
(35, 'buku', 93, 'UPDATE', 'as', 'Vicky', '2025-05-21 13:26:27'),
(36, 'buku', 93, 'DELETE', 'as', 'Vicky', '2025-05-21 13:26:31'),
(37, 'buku', 89, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-21 14:36:38'),
(38, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:13:18'),
(39, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:13:18'),
(40, 'buku', 89, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-21 15:15:25'),
(41, 'buku', 89, 'UPDATE', 'Otonari No Tenshi', 'admin', '2025-05-21 15:15:25'),
(42, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:16:07'),
(43, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:16:07'),
(44, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:17:25'),
(45, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:17:25'),
(46, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:19:24'),
(47, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:19:24'),
(48, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:20:05'),
(49, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:21:13'),
(50, 'buku', 91, 'UPDATE', 'One Punch Man', 'admin', '2025-05-21 15:25:55'),
(51, 'buku', 94, 'INSERT', 'Dongeng Sang Kancil', 'admin', '2025-05-21 15:50:23'),
(52, 'buku', 94, 'UPDATE', 'Dongeng Sang Kancil', 'admin', '2025-05-21 15:54:49'),
(53, 'kategori', 14, 'UPDATE', 'Pendidikan', 'admin', '2025-05-22 00:25:51'),
(54, 'kategori', 15, 'UPDATE', 'Fiksi Visual / Komik & Ilustrasi', 'admin', '2025-05-22 00:26:01'),
(55, 'kategori', 18, 'DELETE', 'Novel', 'admin', '2025-05-22 00:26:10'),
(56, 'kategori', 20, 'DELETE', 'Cerpen', 'admin', '2025-05-22 00:26:16'),
(57, 'kategori', 21, 'UPDATE', 'Fiksi', 'admin', '2025-05-22 00:26:35'),
(58, 'kategori', 22, 'UPDATE', 'Referensi Umum', 'admin', '2025-05-22 00:26:55'),
(59, 'kategori', 23, 'UPDATE', 'Teknologi dan Komputer', 'admin', '2025-05-22 00:27:23'),
(60, 'kategori', 25, 'UPDATE', 'Nonfiksi', 'admin', '2025-05-22 00:27:37'),
(61, 'kategori', 26, 'UPDATE', 'Seni & Desain', 'admin', '2025-05-22 00:28:46'),
(62, 'kategori', 39, 'INSERT', 'Bisnis dan Ekonomi', 'admin', '2025-05-22 00:29:00'),
(63, 'buku', 95, 'INSERT', 'Laskar Pelangi', 'admin', '2025-05-22 00:36:02'),
(64, 'buku', 95, 'UPDATE', 'Laskar Pelangi', 'admin', '2025-05-22 00:36:59'),
(65, 'buku', 94, 'UPDATE', 'Dongeng Sang Kancil', 'admin', '2025-05-22 00:37:12'),
(66, 'buku', 94, 'UPDATE', 'Dongeng Sang Kancil', 'admin', '2025-05-22 00:37:23'),
(67, 'buku', 95, 'UPDATE', 'Laskar Pelangi', 'admin', '2025-05-22 00:37:49'),
(68, 'buku', 95, 'UPDATE', 'Laskar Pelangi', 'admin', '2025-05-22 00:38:22'),
(69, 'buku', 95, 'UPDATE', 'Laskar Pelangi', 'admin', '2025-05-22 00:38:37'),
(70, 'buku', 95, 'UPDATE', 'Laskar Pelangi', 'admin', '2025-05-22 00:40:39'),
(71, 'buku', 95, 'UPDATE', 'Laskar Pelangi', 'admin', '2025-05-22 00:41:30'),
(72, 'buku', 96, 'INSERT', 'Pemrograman Web dengan PHP dan MySQL', 'admin', '2025-05-22 00:42:58'),
(73, 'buku', 96, 'UPDATE', 'Pemrograman Web dengan PHP dan MySQL', 'admin', '2025-05-22 01:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `rak`
--

CREATE TABLE `rak` (
  `id_rak` int(11) NOT NULL,
  `no_rak` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rak`
--

INSERT INTO `rak` (`id_rak`, `no_rak`) VALUES
(1, 'A-1'),
(2, 'A-2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_rak` (`id_rak`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `rak`
--
ALTER TABLE `rak`
  ADD PRIMARY KEY (`id_rak`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `rak`
--
ALTER TABLE `rak`
  MODIFY `id_rak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `buku_ibfk_2` FOREIGN KEY (`id_rak`) REFERENCES `rak` (`id_rak`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
