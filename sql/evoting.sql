-- Fresh database
DROP DATABASE IF EXISTS evoting;
CREATE DATABASE evoting;
USE evoting;

-- Students
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reg_number VARCHAR(20) UNIQUE NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name  VARCHAR(50) NOT NULL,
  faculty    VARCHAR(100) NOT NULL,
  department VARCHAR(100) NOT NULL,
  level INT NOT NULL,
  has_voted TINYINT(1) DEFAULT 0
);

-- Candidates
CREATE TABLE candidates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  position VARCHAR(100) NOT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Votes (one student → many candidates, one per position on submit; double voting blocked)
CREATE TABLE votes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  candidate_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_votes_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  CONSTRAINT fk_votes_candidate FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE
);

-- Admins (bcrypt hash)
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Positions reference
CREATE TABLE positions_ref (
  id INT AUTO_INCREMENT PRIMARY KEY,
  position VARCHAR(100) UNIQUE NOT NULL
);

INSERT INTO positions_ref (position) VALUES
('President'),
('Vice President'),
('Director of Socials'),
('Assistant Secretary General'),
('Financial Secretary'),
('Director of Games'),
('Secretary General'),
('Director of Welfare');

-- Default admin: admin / admin123
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$5xW7lqQe1zDkK0mUe8s8fO0c7cYv0z8G8Zb5m6k2x8n2m1d7jzQ0u');

-- Seed 50 students (includes your 7 originals)
INSERT INTO students (reg_number, first_name, last_name, faculty, department, level) VALUES
('2021/247354','Chinedu','Okeke','Faculty of Agriculture','Crop Science',200),
('2021/754976','Musa','Abdullahi','Faculty of Biological Sciences','Botany',300),
('2021/764987','Tunde','Ajayi','Faculty of Arts','English',100),
('2021/764877','Ngozi','Eze','Faculty of Physical Sciences','Computer Science',400),
('2021/324788','Fatima','Usman','Faculty of Biological Sciences','Zoology',200),
('2021/247976','Segun','Adeyemi','Faculty of Arts','History',300),
('2021/247876','Chioma','Nwachukwu','Faculty of Agriculture','Animal Science',100),
('2021/100001','Ibrahim','Lawal','Faculty of Physical Sciences','Mathematics',200),
('2021/100002','Blessing','Obi','Faculty of Physical Sciences','Statistics',400),
('2021/100003','Adaobi','Eze','Faculty of Arts','Linguistics',200),
('2021/100004','Mary','Yusuf','Faculty of Biological Sciences','Botany',100),
('2021/100005','Emeka','Nwafor','Faculty of Physical Sciences','Physics',300),
('2021/100006','Joseph','Okafor','Faculty of Agriculture','Animal Science',400),
('2021/100007','John','Bello','Faculty of Arts','History',200),
('2021/100008','Halima','Mohammed','Faculty of Agriculture','Crop Science',300),
('2021/100009','Victor','Oladipo','Faculty of Physical Sciences','Computer Science',400),
('2021/100010','Grace','Okorie','Faculty of Arts','English',200),
('2021/100011','Samuel','Obi','Faculty of Biological Sciences','Zoology',100),
('2021/100012','Ruqayyah','Aliyu','Faculty of Agriculture','Animal Science',300),
('2021/100013','Kelechi','Onyeka','Faculty of Physical Sciences','Mathematics',400),
('2021/100014','Bola','Ogunleye','Faculty of Arts','Linguistics',200),
('2021/100015','Hassan','Mohammed','Faculty of Agriculture','Crop Science',100),
('2021/100016','Esther','Adebayo','Faculty of Physical Sciences','Statistics',300),
('2021/100017','Uche','Okafor','Faculty of Arts','English',400),
('2021/100018','Patience','Opara','Faculty of Agriculture','Animal Science',200),
('2021/100019','Ismail','Yusuf','Faculty of Biological Sciences','Botany',300),
('2021/100020','Chisom','Eneh','Faculty of Physical Sciences','Computer Science',100),
('2021/100021','Amina','Bello','Faculty of Arts','History',200),
('2021/100022','Ifeanyi','Okonkwo','Faculty of Physical Sciences','Physics',400),
('2021/100023','Zainab','Ibrahim','Faculty of Arts','Linguistics',300),
('2021/100024','Kunle','Adeoye','Faculty of Physical Sciences','Statistics',100),
('2021/100025','Chukwudi','Eze','Faculty of Biological Sciences','Zoology',400),
('2021/100026','Folake','Akinyemi','Faculty of Arts','English',200),
('2021/100027','Aliyu','Danjuma','Faculty of Agriculture','Crop Science',300),
('2021/100028','Mercy','Okafor','Faculty of Physical Sciences','Mathematics',100),
('2021/100029','Bashir','Suleiman','Faculty of Biological Sciences','Botany',200),
('2021/100030','Kemi','Balogun','Faculty of Arts','Linguistics',300),
('2021/100031','Ijeoma','Umeh','Faculty of Physical Sciences','Physics',400),
('2021/100032','Yusuf','Mohammed','Faculty of Agriculture','Animal Science',100),
('2021/100033','Chinyere','Okeke','Faculty of Biological Sciences','Zoology',200),
('2021/100034','Abdul','Rahman','Faculty of Physical Sciences','Computer Science',300),
('2021/100035','Oluwaseun','Adekunle','Faculty of Arts','English',400),
('2021/100036','Hadiza','Sani','Faculty of Agriculture','Crop Science',200),
('2021/100037','Nnamdi','Obi','Faculty of Physical Sciences','Statistics',300),
('2021/100038','Sarah','Okonkwo','Faculty of Arts','History',100),
('2021/100039','Gbenga','Alabi','Faculty of Physical Sciences','Mathematics',200),
('2021/100040','Amaka','Eze','Faculty of Biological Sciences','Botany',400),
('2021/100041','Sule','Abubakar','Faculty of Arts','Linguistics',300),
('2021/100042','Chidera','Nwosu','Faculty of Agriculture','Animal Science',100),
('2021/100043','Mohammed','Umar','Faculty of Arts','History',200),
('2021/100044','Ifunanya','Okorie','Faculty of Agriculture','Crop Science',300);

-- Candidates (photos can be uploaded later in admin)
INSERT INTO candidates (name, position) VALUES
('Chinedu Okeke','President'),
('Musa Abdullahi','President'),
('Adaobi Eze','Vice President'),
('John Bello','Director of Socials'),
('Emeka Nwafor','Director of Socials'),
('Mary Yusuf','Assistant Secretary General'),
('Tunde Ajayi','Financial Secretary'),
('Blessing Obi','Director of Games'),
('Ibrahim Lawal','Director of Games'),
('Joseph Okafor','Secretary General'),
('Segun Adeyemi','Secretary General'),
('Chioma Nwachukwu','Director of Welfare'),
('Fatima Usman','Director of Welfare');
