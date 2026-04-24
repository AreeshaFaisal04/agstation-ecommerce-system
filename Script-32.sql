CREATE DATABASE practice_final;
USE practice_final;

-- Table: departments
CREATE TABLE departments (
    department_id INT PRIMARY KEY,
    name VARCHAR(50),
    hod VARCHAR(50) -- Head of Department
);

-- Table: professors
CREATE TABLE professors (
    professor_id INT PRIMARY KEY,
    name VARCHAR(50),
    department_id INT,
    email VARCHAR(50),
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Table: students
CREATE TABLE students (
    student_id INT PRIMARY KEY,
    name VARCHAR(50),
    age INT,
    gender CHAR(1),
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Table: courses
CREATE TABLE courses (
    course_id INT PRIMARY KEY,
    course_name VARCHAR(100),
    professor_id INT,
    credits INT,
    FOREIGN KEY (professor_id) REFERENCES professors(professor_id)
);

-- Table: enrollments
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY,
    student_id INT,
    course_id INT,
    marks INT,
    semester VARCHAR(10),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- Insert departments
INSERT INTO departments VALUES
(1, 'Computer Science', 'Dr. Imran'),
(2, 'Electrical Engineering', 'Dr. Nida'),
(3, 'Mechanical Engineering', 'Dr. Ali');

-- Insert professors
INSERT INTO professors VALUES
(1, 'Dr. Khan', 1, 'khan@univ.edu'),
(2, 'Dr. Ahmed', 1, 'ahmed@univ.edu'),
(3, 'Dr. Ayesha', 2, 'ayesha@univ.edu'),
(4, 'Dr. Faheem', 3, 'faheem@univ.edu');

-- Insert students
INSERT INTO students VALUES
(1, 'Ali', 20, 'M', 1),
(2, 'Sara', 21, 'F', 1),
(3, 'Bilal', 22, 'M', 2),
(4, 'Fatima', 20, 'F', 3),
(5, 'Zain', 23, 'M', 1),
(6, 'Ayesha', 21, 'F', 2),
(7, 'Ahmed', 22, 'M', 3);

-- Insert courses
INSERT INTO courses VALUES
(101, 'Data Structures', 1, 3),
(102, 'Databases', 2, 4),
(103, 'Operating Systems', 3, 3),
(104, 'Thermodynamics', 4, 3),
(105, 'Algorithms', 1, 4);

-- Insert enrollments
INSERT INTO enrollments VALUES
(1, 1, 101, 85, 'Fall2024'),
(2, 1, 102, 70, 'Fall2024'),
(3, 2, 101, 95, 'Fall2024'),
(4, 2, 102, 88, 'Fall2024'),
(5, 3, 101, 75, 'Fall2024'),
(6, 3, 103, 80, 'Fall2024'),
(7, 4, 102, 92, 'Fall2024'),
(8, 5, 101, 90, 'Fall2024'),
(9, 5, 102, 85, 'Fall2024'),
(10, 5, 103, 65, 'Fall2024'),
(11, 6, 103, 78, 'Fall2024'),
(12, 6, 104, 88, 'Fall2024'),
(13, 7, 104, 82, 'Fall2024'),
(14, 7, 105, 75, 'Fall2024');


SELECT * FROM enrollments;

 SELECT * FROM students WHERE gender='M';
 
 SELECT * FROM courses;
 
 SELECT * FROM courses WHERE credits > 3;
 
  SELECT * FROM enrollments where marks>80;
  
  select * from courses;
  
   SELECT  course_id , AVG(marks) AS Average FROM enrollments group by course_id; 
   
     select course_id , count(student_id) as my_count from enrollments group by course_id ;
     
     select course_id  from enrollments group by course_id having avg(marks)>80 ;
     
     select student_id  from enrollments group by student_id having count(course_id)>2 ;
     
SELECT name
FROM students
WHERE student_id IN (
    SELECT student_id
    FROM enrollments
    GROUP BY student_id
    HAVING COUNT(course_id) > 2
);

SELECT student_id, course_id, marks
FROM enrollments e1
WHERE marks > (
    SELECT AVG(marks)
    FROM enrollments e2
    WHERE e2.course_id = e1.course_id
);


SELECT student_id
FROM enrollments
GROUP BY student_id
HAVING COUNT(course_id) = (
    SELECT COUNT(*)
    FROM courses
    WHERE credits > 3
);





     
 
