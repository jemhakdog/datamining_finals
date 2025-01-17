-- Table structure for table `subject_teachers`
DROP TABLE IF EXISTS `subject_teachers`;
CREATE TABLE IF NOT EXISTS `subject_teachers` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `subject_id` int(6) NOT NULL,
  `teacher_id` int(6) NOT NULL,
  `status` enum('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Add some initial assignments based on existing departments (as approved)
INSERT INTO subject_teachers (subject_id, teacher_id, status)
SELECT s.subject_id, t.id, 'approved'
FROM subjects s
INNER JOIN teachers t ON s.course = t.department;
