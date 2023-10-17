-- Langkah Setup Website

-- Perubahan database:
-- Table user

-- Tambah column:

-- new_password
ALTER TABLE `user` ADD COLUMN `new_password` VARCHAR(100) DEFAULT NULL AFTER `password`;
-- password_version (0: lama, 1: baru)
ALTER TABLE `user` ADD password_version tinyint(1) DEFAULT 0 NOT NULL AFTER category;
-- last_login_at
ALTER TABLE `user` ADD last_login_at timestamp NULL AFTER password_version;
-- locale
ALTER TABLE `user` ADD locale varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL AFTER last_login_at;

ALTER TABLE `user` 
ADD COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL,
ADD COLUMN `created_by` VARCHAR(36) NULL DEFAULT NULL,
ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN `updated_by` VARCHAR(36) NULL DEFAULT NULL;

-- Table Event_list
-- Tambah column
-- max_join_competition
ALTER TABLE `event_list` ADD max_join_competition tinyint(1) DEFAULT 1 NOT NULL AFTER registration_type;
ALTER TABLE `event_list` ADD slug tinyint(1) DEFAULT 1 NOT NULL AFTER short_link; -- BELUM

-- phone tambah characters
ALTER TABLE `user` MODIFY COLUMN phone varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;


-- QUERY VANS SHOWDOWN
INSERT INTO user (full_name, nick_name, email, password, isActive, flag, level, stance, category)
VALUES
('Maxwell Seli', 'Maxwell', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Ananda Sontinarakul', 'Ananda', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('firdaus Daus', 'firdaus', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Mujib Mujib', 'Mujib', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('muhammad hafiz abdul halim', 'muhammad', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Amos Lehmuskallio', 'Amos', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Abd Nuramin Ab Halim', 'Nuramin', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Munir Muhammad', 'Munir', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Irsyad Cad', 'Irsyad', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Azizul Amin Abdullah', 'Azizul', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('HADIF ZAINAL', 'HADIF', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Danish Hurairah', 'Danish', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('shah ramadhan', 'Ramadhan', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('mohd hasni md daud', 'Mohd Hasni', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Akib Afandi', 'Akib', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Zaim Anuar', 'Zaim', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('M Yazid', 'Yazid', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Pius Elli', 'Elli', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Alfian fariadi', 'Alfian', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Kuduk Kuantan', 'Kuduk', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Faizal Mohd Shahrom', 'Faizal', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Azhad Isad Mazkamal', 'Azhad', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Nur Aiman Beins', 'Nur', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Marcus Brown Jr Bin Abdul Razzaq', 'Marcus', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Haris Hurairah', 'Haris', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Nor Mahathir Daniel', 'Nor', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Vincent Lehmuskallio', 'Vincent', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Nashmia Nor Izwan', 'Nashmia', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Zidane Yusouff Mohd Zamri', 'Zidane', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Tia Adelisa Tajul Fuad Najrul', 'Tia', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Ammar Bakhtiar Nikko', 'Ammar', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Budi Buckthiar Nikko', 'Budi', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Faris Anaqi Mohd Sharin', 'Faris', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Amir Fikri', 'Amir', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Elijah Scott Wee', 'Elijah', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Ahmad Adlan Anaqi', 'Ahmad', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Hadif Shahril', 'Hadif', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Wan Azwary', 'Wan', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Wan Mustafa', 'Wan', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Wan Izzat', 'Wan', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Amar Rawstar', 'Amar', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0),
('Momo Khan', 'Momo', '', '827ccb0eea8a706c4c34a16891f84e7b', 1, 1, 0, 0, 0);


-- Invitation
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-1902419', '', NOW(), 'Rome Collyer', 'Rome', '0000-00-00', 'Rome_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-2039889', '', NOW(), 'Ye Jia Liang', 'Ye', '0000-00-00', 'Ye_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-8228912', '', NOW(), 'Azreen Azman', 'Azreen', '0000-00-00', 'Azreen_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-7005985', '', NOW(), 'Shafiq Stu', 'Shafiq', '0000-00-00', 'Shafiq_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-3545546', '', NOW(), 'Altan-Och Batbayar', 'Altan-Och', '0000-00-00', 'Altan-Och_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-6023668', '', NOW(), 'Sumiyabazar Gunereedii', 'Sumiyabazar', '0000-00-00', 'Sumiyabazar_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-1728138', '', NOW(), 'Luk Chun Yin', 'Luk', '0000-00-00', 'Luk_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-0178404', '', NOW(), 'Yu Long', 'Yu', '0000-00-00', 'Yu_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-2349519', '', NOW(), 'Rio Morishige', 'Rio', '0000-00-00', 'Rio_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-1574595', '', NOW(), 'Daiki Hoshino', 'Daiki', '0000-00-00', 'Daiki_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-4456803', '', NOW(), 'Khyll Angelo Siarot', 'Khyll', '0000-00-00', 'Khyll_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-4646282', '', NOW(), 'Kevin Almazan', 'Kevin', '0000-00-00', 'Kevin_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-6546057', '', NOW(), 'Sharil Effendy Bin Rohaizad', 'Sharil', '0000-00-00', 'Sharil_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-1363717', '', NOW(), 'Aqil .', 'Aqil', '0000-00-00', 'Aqil_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-9741451', '', NOW(), 'Kenny Kamil', 'Kenny', '0000-00-00', 'Kenny_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-2681250', '', NOW(), 'Eugene Choi', 'Eugene', '0000-00-00', 'Eugene_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-1553636', '', NOW(), 'Dong Hyuk', 'Dong', '0000-00-00', 'Dong_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-2641489', '', NOW(), 'Kobukchip .', 'Kobukchip', '0000-00-00', 'Kobukchip_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-9220749', '', NOW(), 'Tong Cam Vu', 'Tong', '0000-00-00', 'Tong_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-3973617', '', NOW(), 'Thanh Cong Le', 'Thanh', '0000-00-00', 'Thanh_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);


INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-2615490', '', NOW(), 'Khadijah .', 'Khadijah', '0000-00-00', 'Khadijah_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-1140356', '', NOW(), 'Hina Maeda', 'Hina', '0000-00-00', 'Hina_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-3835773', '', NOW(), 'Truong Dieu Anh', 'Truong', '0000-00-00', 'Truong_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-2433127', '', NOW(), 'Lin Shi Qiao', 'Lin', '0000-00-00', 'Lin_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);
INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-7607724', '', NOW(), 'Kyan Kailana', 'Kyan', '0000-00-00', 'Kyan_1@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);


INSERT INTO contestant_list (ID_competition, ID_user, attendance, insert_user, insert_date, update_date)
VALUES
(98, 1489, 0, 0, NOW(), NULL),
(98, 1490, 0, 0, NOW(), NULL),
(98, 1491, 0, 0, NOW(), NULL),
(98, 1492, 0, 0, NOW(), NULL),
(98, 1493, 0, 0, NOW(), NULL),
(98, 1494, 0, 0, NOW(), NULL),
(98, 1495, 0, 0, NOW(), NULL),
(98, 1496, 0, 0, NOW(), NULL),
(98, 1497, 0, 0, NOW(), NULL),
(98, 1498, 0, 0, NOW(), NULL),
(98, 1499, 0, 0, NOW(), NULL),
(98, 1500, 0, 0, NOW(), NULL),
(98, 1501, 0, 0, NOW(), NULL),
(98, 1502, 0, 0, NOW(), NULL),
(98, 1503, 0, 0, NOW(), NULL),
(98, 1504, 0, 0, NOW(), NULL),
(98, 1505, 0, 0, NOW(), NULL),
(98, 1506, 0, 0, NOW(), NULL),
(98, 1507, 0, 0, NOW(), NULL),
(98, 1508, 0, 0, NOW(), NULL);

162 Rubianda Rachman
264 Muhd Hamirun
292 Mohd danniel
307 Koya Miyasaka
411 fikri fauzi
470 Mario Palandeng
504	Bayu Satya
525	Made richi
529	Ian bin nuriman amri
563	Sanggoe darma tanjung
860	Basral graito hutomo
941	Made richi
1491 Azreen Azman

INSERT INTO user_origin (user_id, country_id, country_name, created_at)
VALUES
(1489, 'AU', 'Australia', NOW()),
(1490, 'CN', 'China', NOW()),
(1491, 'MY', 'Malaysia', NOW()),
(307, 'MY', 'Malaysia', NOW()),
(411, 'MY', 'Malaysia', NOW()),
(529, 'MY', 'Malaysia', NOW()),
(1492, 'MY', 'Malaysia', NOW()),
(1493, 'MN', 'Mongolia', NOW()),
(1494, 'MN', 'Mongolia', NOW()),
(1495, 'HK', 'Hong Kong', NOW()),
(1496, 'HK', 'Hong Kong', NOW()),
(1497, 'JP', 'Japan', NOW()),
(1498, 'JP', 'Japan', NOW()),
(1499, 'PH', 'Philippines', NOW()),
(1500, 'PH', 'Philippines', NOW()),
(1501, 'SG', 'Singapore', NOW()),
(264, 'SG', 'Singapore', NOW()),
(1502, 'SG', 'Singapore', NOW()),
(292, 'SG', 'Singapore', NOW()),
(1503, 'SG', 'Singapore', NOW()),
(1504, 'KR', 'Korea, Republic of', NOW());


(162, 'ID', 'Indonesia', NOW()),
(504, 'ID', 'Indonesia', NOW()),
(470, 'ID', 'Indonesia', NOW()),

1509	Khadijah	Malaysia	MY
1510	Hina Maeda	OSAKA, JAPAN	JP
1511	Truong Dieu Anh	HANOI, VIETNAM	VN
1512	Lin Shi Qiao	SHANGHAI, CHINA	CN


INSERT INTO user_origin (user_id, country_id, country_name, created_at)
VALUES (1509, 'MY', 'Malaysia', NOW()),
(1510, 'JP', 'Japan', NOW()),
(1511, 'VN', 'Vietnam', NOW()),
(1512, 'CN', 'China', NOW());


Akib	Afandi

INSERT INTO user (activation_code, photoFile, registered_date, full_name, nick_name, dateofbirth, email, password, phone, country, city, isActive, flag, level, stance, category) VALUES ('HST-861549A', '', NOW(), 'Akib Afandi', 'Akib', '0000-00-00', 'Akibafandi@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '', '', '', 1, 1, 0, 0, 0);