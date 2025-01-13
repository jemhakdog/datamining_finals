USE students;
INSERT INTO class (student_id, subject_id, name, course) 
SELECT 
    s.id as student_id,
    sub.subject_id,
    s.name,
    s.course
FROM students s
CROSS JOIN subjects sub
WHERE s.course = sub.course
AND NOT EXISTS (
    SELECT 1 
    FROM class c 
    WHERE c.student_id = s.id 
    AND c.subject_id = sub.subject_id
);