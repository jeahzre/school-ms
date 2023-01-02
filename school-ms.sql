-- admin std_db = suarez081119@gmail.com
CREATE DATABASE IF NOT EXISTS `school`;
USE `school`;

-- Login
CREATE TABLE IF NOT EXISTS `usr` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
`email` VARCHAR(50) NOT NULL, 
`passwd` VARCHAR(50) NOT NULL, 
UNIQUE(`email`)
);

CREATE TABLE IF NOT EXISTS `teacher` (
`user_id` INT UNSIGNED NOT NULL, 
`name` VARCHAR(30) NOT NULL,
`initial_name` VARCHAR(30),
`gender` ENUM('male', 'female') NOT NULL,
`phone_number` INT, 
`registration_date` DATE NOT NULL DEFAULT (CURDATE()),
UNIQUE(`user_id`),
FOREIGN KEY(`user_id`) REFERENCES `usr`(`id`) ON DELETE CASCADE
);

-- SELECT `id`, `email`, `passwd`, `name`, `initial_name`, `gender`, `phone_number`, `registration_date` FROM `usr` 
--   INNER JOIN `teacher`
--   ON `usr`.`id` = `teacher`.`user_id`
--   WHERE `usr`.`id` = 18;
--   SELECT `id`, `email`, `passwd`, `name`, `initial_name`, `gender`, `phone_number`, `registration_date` FROM `usr`   
--   INNER JOIN `teacher`  ON `usr`.`id` = `teacher`.`user_id`  
--   WHERE `usr`.`id` = 18;
-- show open tables where in_use>0;
-- show processlist;
-- kill 7124;
-- SELECT COUNT(`user_id`) AS student_count FROM student;

-- ALTER TABLE `usr` AUTO_INCREMENT=1;
-- DELETE FROM teacher_attendance WHERE user_id = 15;
-- ALTER TABLE `student_attendance` 
-- DROP CONSTRAINT `student_attendance_ibfk_1`;
-- ALTER TABLE `student_attendance` 
-- ADD FOREIGN KEY(`user_id`) REFERENCES `student`(`user_id`);
-- SELECT `user_id` FROM `user_detail` ORDER BY `user_id` DESC LIMIT 1;
-- ALTER TABLE `subject`
-- RENAME COLUMN `subject_id` TO `id`;
-- ALTER TABLE `subject_routing`
-- MODIFY COLUMN `teacher_id` INT UNSIGNED NOT NULL;
-- ALTER TABLE student_grade
-- ADD UNIQUE(`user_id`, `grade`);
-- DELETE FROM teacher_salary WHERE salary = 830.00 AND user_id = 18;

INSERT INTO `usr` (`email`, `passwd`)
VALUES ('a@a.aa', '12345'), ('b@b.bb', '12345'), ('c@a.aa', '12345'), ('c@b.bb', '12345');

CREATE TABLE IF NOT EXISTS `user_type` (
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
`user_type` VARCHAR(50) NOT NULL
); 

INSERT INTO `user_type` (`user_type`)
VALUES ('teacher'), ('student'), ('admin');

CREATE TABLE IF NOT EXISTS `user_user_type` (
`user_id` INT UNSIGNED NOT NULL, 
`user_type_id` INT UNSIGNED NOT NULL, 
UNIQUE(`user_id`, `user_type_id`),
FOREIGN KEY(user_id) REFERENCES `usr`(id) ON DELETE CASCADE,
FOREIGN KEY(user_type_id) REFERENCES `user_type`(id)
);

INSERT INTO `user_user_type` (`user_id`, `user_type_id`)
VALUES (1, 2);

INSERT INTO `teacher`
VALUES (1, 'Cibiby', 'CBB', 'male', 11111, DEFAULT), 
(2, 'Dadaaa', 'DAA', 'male', 11111, DEFAULT);

CREATE TABLE IF NOT EXISTS `student` (
`user_id` INT UNSIGNED NOT NULL, 
`name` VARCHAR(30) NOT NULL,
`initial_name` VARCHAR(30),
`gender` ENUM('male', 'female'),
`phone_number` INT, 
`guardian_name` VARCHAR(30) NOT NULL, 
`guardian_phone` INT NOT NULL,
`guardian_email` VARCHAR(30) NOT NULL,
`registration_date` DATE NOT NULL DEFAULT (CURDATE()),
UNIQUE(`user_id`),
FOREIGN KEY(`user_id`) REFERENCES `usr`(`id`) ON DELETE CASCADE
);

INSERT INTO `student`
VALUES (3, 'Ca Aaa', 'CA', 'male', 11111, 'Hh', 22222, 'h@h.hh'), 
(4, 'Bee Beee', 'BB', 'male', 11111, 'Et', 22222, 'e@e.ee');

CREATE TABLE IF NOT EXISTS `grade`(
`grade` INT UNSIGNED NOT NULL PRIMARY KEY, 
`admission_fee` DECIMAL(10,2) NOT NULL, 
`hall_charge` INT
);

-- Add/edit grade 
INSERT INTO `grade`
VALUES (1, 100, 4) AS new
ON DUPLICATE KEY UPDATE
`grade` = new.`grade`, 
`admission_fee` = new.`admission_fee`, 
`hall_charge` = new.`hall_charge`
;

CREATE TABLE IF NOT EXISTS `student_grade` (
`user_id` INT UNSIGNED NOT NULL,
`grade` INT UNSIGNED NOT NULL, 
UNIQUE(`user_id`, `grade`), 
FOREIGN KEY(`user_id`) REFERENCES `usr`(`id`) ON DELETE CASCADE, 
FOREIGN KEY(`grade`) REFERENCES `grade`(`grade`) ON DELETE CASCADE
);

INSERT INTO `student_grade`
VALUES (1, 1);

CREATE TABLE IF NOT EXISTS `class` (
`class_name` VARCHAR(30) NOT NULL PRIMARY KEY, 
`student_count` INT UNSIGNED NOT NULL
);

-- Add/edit class
INSERT INTO `class`
VALUES ('1a', 20);

-- UPDATE `class`
-- SET `class_name` = '1b',
-- `student_count` = '20'
-- WHERE `class_name` = '1b';

CREATE TABLE IF NOT EXISTS `class_grade` (
`class_name` VARCHAR(30) NOT NULL, 
`grade` INT UNSIGNED NOT NULL, 
UNIQUE(`class_name`, `grade`), 
FOREIGN KEY(`class_name`) REFERENCES `class`(`class_name`) ON DELETE CASCADE, 
FOREIGN KEY(`grade`) REFERENCES `grade`(`grade`) ON DELETE CASCADE
);

INSERT INTO `class_grade`
VALUES ('1a', 1);

CREATE TABLE IF NOT EXISTS `subject` (
`id` INT AUTO_INCREMENT PRIMARY KEY,
`subject_name` VARCHAR(30) NOT NULL
);

INSERT INTO `subject`
VALUES (1, 'science') AS new
ON DUPLICATE KEY UPDATE
`name` = new.`name`;

-- Replaced by subject_routing table
-- CREATE TABLE IF NOT EXISTS `grade_subject`(
-- `grade` INT UNSIGNED NOT NULL, 
-- `subject_id` INT NOT NULL,
-- PRIMARY KEY(`grade`, `subject_id`),
-- FOREIGN KEY(`grade`) REFERENCES `grade`(`grade`),
-- FOREIGN KEY(`subject_id`) REFERENCES `subject`(`subject_id`)
-- );

-- INSERT INTO `grade_subject`
-- VALUES (1, 1);

CREATE TABLE IF NOT EXISTS `subject_routing`(
`grade` INT UNSIGNED NOT NULL, 
`subject_id` INT NOT NULL,
`teacher_id` INT UNSIGNED NOT NULL,
`fee` DECIMAL(10,2) NOT NULL,
PRIMARY KEY(`grade`, `subject_id`),
FOREIGN KEY(`grade`) REFERENCES `grade`(`grade`) ON DELETE CASCADE,
FOREIGN KEY(`subject_id`) REFERENCES `subject`(`id`) ON DELETE CASCADE,
FOREIGN KEY(`teacher_id`) REFERENCES `teacher`(`user_id`) ON DELETE CASCADE
);

INSERT INTO `subject_routing`
VALUES (1, 1, 1, 50.67);

-- Till dashboard, to modify referencing table
CREATE TABLE IF NOT EXISTS `teacher_salary` (
`user_id` INT UNSIGNED NOT NULL, 
`salary` DECIMAL(10,2) NOT NULL,
UNIQUE(`user_id`), 
FOREIGN KEY(`user_id`) REFERENCES `teacher`(`user_id`) ON DELETE CASCADE
);

INSERT INTO `teacher_salary`
VALUES (1, 830.67) as new
ON DUPLICATE KEY UPDATE
`salary` = new.`salary`;

CREATE TABLE IF NOT EXISTS `teacher_payment` (
`user_id` INT UNSIGNED NOT NULL, 
`paid` DECIMAL(10, 2) NOT NULL,
`paid_date` DATETIME NOT NULL DEFAULT (NOW()),
UNIQUE(`user_id`), 
FOREIGN KEY(`user_id`) REFERENCES `teacher`(`user_id`)
);

INSERT INTO `teacher_payment`
VALUES (1, 830.67, DEFAULT) as new
ON DUPLICATE KEY UPDATE
`paid` = new.`paid`;

CREATE TABLE IF NOT EXISTS `student_payment` (
`user_id` INT UNSIGNED NOT NULL, 
`paid` DECIMAL(10, 2) NOT NULL,
`paid_date` DATETIME NOT NULL DEFAULT (NOW()),
FOREIGN KEY(`user_id`) REFERENCES `student`(`user_id`)
);

INSERT INTO `student_payment`
VALUES (3, 830, DEFAULT), (4, 750, DEFAULT);

CREATE TABLE IF NOT EXISTS `teacher_attendance` (
`user_id` INT UNSIGNED NOT NULL, 
`date` DATE NOT NULL DEFAULT (CURDATE()),
UNIQUE(`user_id`), 
FOREIGN KEY(`user_id`) REFERENCES `teacher`(`user_id`)
);

INSERT INTO `teacher_attendance`
VALUES (1, CURDATE());

CREATE TABLE IF NOT EXISTS `student_attendance` (
`user_id` INT UNSIGNED NOT NULL, 
`date` DATE NOT NULL DEFAULT (CURDATE()),
UNIQUE(`user_id`), 
FOREIGN KEY(`user_id`) REFERENCES `student`(`user_id`)
);

INSERT INTO `student_attendance`
VALUES (3, CURDATE());

CREATE TABLE IF NOT EXISTS `exam` (
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
`name` VARCHAR(30) NOT NULL,
`subject_id` INT NOT NULL,
`datetime` DATETIME NOT NULL, 
UNIQUE(`subject_id`, `datetime`), 
FOREIGN KEY(`subject_id`) REFERENCES `subject`(`id`)
);

INSERT INTO `exam` (`name`, `subject_id`, `datetime`)
VALUES ('Anatomy', 1, '2022-02-10 14:00:00');

CREATE TABLE IF NOT EXISTS `plan` (
`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
`activity_name` VARCHAR(30) NOT NULL,
`datetime` DATETIME NOT NULL,
UNIQUE(`activity_name`, `datetime`)
);

INSERT INTO `plan` (`activity_name`, `datetime`)
VALUES ('Party 1', '2022-02-12 09:00:00');

CREATE TABLE IF NOT EXISTS `usr_profile_picture` (
`user_id` INT UNSIGNED NOT NULL,
`profile_picture` VARCHAR(100) NOT NULL,
FOREIGN KEY(user_id) REFERENCES `usr`(id) ON DELETE CASCADE
);

-- Dashboard 
-- Select admin, teacher, student for counting the total
-- SELECT * FROM `user_user_type` 
-- WHERE user_type_id = (
-- SELECT id FROM user_type WHERE user_type = 'admin'
-- );

-- SELECT * FROM `user_user_type` 
-- WHERE user_type_id = (
-- SELECT id FROM user_type WHERE user_type = 'teacher'
-- );

-- SELECT * FROM `user_user_type` 
-- WHERE user_type_id = (
-- SELECT id FROM user_type WHERE user_type = 'student'
-- );

-- My profile 
-- SELECT `id`, `email`, `passwd`, `name`, `initial_name`, `gender`, `phone_number`, `registration_date` FROM `usr` u
-- INNER JOIN `teacher` t
-- ON `u`.`id` = `t`.`user_id`;

-- Edit my profile
-- UPDATE `usr`
-- SET `email` = 'd@a.aa', 
-- `passwd` = '12345a'
-- WHERE `id` = 22;

-- UPDATE `teacher`
-- SET `name` = 'Kibiyay', 
-- `initial_name` = 'KA', 
-- `gender` = 'male', 
-- `phone_number` = 11111 
-- WHERE `user_id` = 1;

-- UPDATE `student`
-- SET `name` = 'Ca Aaa', 
-- `initial_name` = 'CBB', 
-- `gender` = 'male', 
-- `phone_number` = 11111, 
-- `guardian_name` = 'Hh',
-- `guardian_phone` = 22222, 
-- `guardian_email` = 'h@h.hh'
-- WHERE `user_id` = (SELECT id FROM usr WHERE `email` = 'c@a.aa');

-- Class
-- SELECT * FROM `class`;

-- Grade
-- SELECT * FROM `grade`;

-- Subject
-- SELECT * FROM `subject`;

-- Teacher 
-- Add teacher
-- INSERT INTO `usr` (`email`, `passwd`)
-- VALUES ('a@a.aa', '12345');
-- INSERT INTO `user_detail`
-- VALUES ((SELECT id FROM usr WHERE `email` = 'a@a.aa'), 'AA', 123);

-- All teacher 
-- Detail 
-- SELECT id FROM user_type WHERE user_type = 'teacher';
-- SELECT `user_id` FROM `user_user_type` 
-- WHERE `user_type_id` = 
-- (SELECT `id` FROM `user_type` WHERE `user_type` = 'student');
-- For each user_id-s returned by above statement
-- SELECT * FROM `user_detail` WHERE `user_id` = 16;
