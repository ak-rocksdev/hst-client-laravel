-- Langkah Setup Website

-- Perubahan database:
-- Table user

-- Tambah column: 
-- password_version (0: lama, 1: baru)
ALTER TABLE `user` ADD password_version tinyint(1) DEFAULT 0 NOT NULL AFTER category;
-- last_login_at
ALTER TABLE `user` ADD last_login_at timestamp NULL AFTER password_version;

-- Table Event_list
-- Tambah column
-- max_join_competition
ALTER TABLE `event_list` ADD max_join_competition tinyint(1) DEFAULT 1 NOT NULL AFTER registration_type;