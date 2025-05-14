use mypetakom;

CREATE TABLE LOGIN (
    login_id INT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL
);

CREATE TABLE STUDENT (
    login_id INT PRIMARY KEY,
    name VARCHAR(100),
    student_id VARCHAR(20),
    email VARCHAR(100),
    profile_picture VARCHAR(255),
    program VARCHAR(100),
    qr_code VARCHAR(255),
    account_status VARCHAR(50),
    last_login DATETIME,
    FOREIGN KEY (login_id) REFERENCES LOGIN(login_id)
);

CREATE TABLE EVENTADVISOR (
    login_id INT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    department VARCHAR(100),
    profile_picture VARCHAR(255),
    account_status VARCHAR(50),
    last_login DATETIME,
    FOREIGN KEY (login_id) REFERENCES LOGIN(login_id)
);

CREATE TABLE ADMINISTRATOR (
    login_id INT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    office VARCHAR(100),
    profile_picture VARCHAR(255),
    account_status VARCHAR(50),
    last_login DATETIME,
    FOREIGN KEY (login_id) REFERENCES LOGIN(login_id)
);

CREATE TABLE MEMBERSHIP (
    membership_id INT PRIMARY KEY,
    student_id INT,
    student_card VARCHAR(255),
    status VARCHAR(50),
    registered_date DATE,
    approved_by_admin_id INT,
    FOREIGN KEY (student_id) REFERENCES STUDENT(login_id),
    FOREIGN KEY (approved_by_admin_id) REFERENCES ADMINISTRATOR(login_id)
);

CREATE TABLE EVENT (
    event_id INT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    location VARCHAR(255),
    event_date DATE,
    status VARCHAR(50),
    approval_letter VARCHAR(255),
    event_advisor_id INT,
    qr_code VARCHAR(255),
    FOREIGN KEY (event_advisor_id) REFERENCES EVENTADVISOR(login_id)
);

CREATE TABLE COMMITTEE (
    committee_id INT PRIMARY KEY,
    event_id INT,
    student_id INT,
    position VARCHAR(100),
    FOREIGN KEY (event_id) REFERENCES EVENT(event_id),
    FOREIGN KEY (student_id) REFERENCES STUDENT(login_id)
);

CREATE TABLE ATTENDANCESLOT (
    slot_id INT PRIMARY KEY,
    event_id INT,
    slot_time DATETIME,
    qr_code VARCHAR(255),
    FOREIGN KEY (event_id) REFERENCES EVENT(event_id)
);

CREATE TABLE ATTENDANCE (
    attendance_id INT PRIMARY KEY,
    slot_id INT,
    student_id INT,
    attendance_time DATETIME,
    location_checked VARCHAR(255),
    status VARCHAR(50),
    student_verification VARCHAR(100),
    actual_latitude FLOAT,
    actual_longitude FLOAT,
    FOREIGN KEY (slot_id) REFERENCES ATTENDANCESLOT(slot_id),
    FOREIGN KEY (student_id) REFERENCES STUDENT(login_id)
);

CREATE TABLE MERITSCORE (
    meritscore_id INT PRIMARY KEY,
    merit_description VARCHAR(255),
    merit_score INT
);

CREATE TABLE MERIT (
    merit_id INT PRIMARY KEY,
    student_id INT,
    event_id INT,
    role VARCHAR(100),
    meritscore_id INT,
    semester VARCHAR(50),
    approved_by_admin_id INT,
    FOREIGN KEY (student_id) REFERENCES STUDENT(login_id),
    FOREIGN KEY (event_id) REFERENCES EVENT(event_id),
    FOREIGN KEY (meritscore_id) REFERENCES MERITSCORE(meritscore_id),
    FOREIGN KEY (approved_by_admin_id) REFERENCES ADMINISTRATOR(login_id)
);

CREATE TABLE MERITCLAIM (
    claim_id INT PRIMARY KEY,
    student_id INT,
    event_id INT,
    justification_letter VARCHAR(255),
    status VARCHAR(50),
    date_claimed DATE,
    participation_letter VARCHAR(255),
    approved_by_admin_id INT,
    FOREIGN KEY (student_id) REFERENCES STUDENT(login_id),
    FOREIGN KEY (event_id) REFERENCES EVENT(event_id),
    FOREIGN KEY (approved_by_admin_id) REFERENCES ADMINISTRATOR(login_id)
);

INSERT INTO EVENT (event_id, title, description, location, event_date, status, approval_letter, event_advisor_id, qr_code)
VALUES
(1, 'Tech Conference 2025', 'A conference to discuss the future of technology.', 'Tech Center, City Hall', '2025-06-10', 'Approved', 'tech_conf_approval.pdf', 1, 'QR123456789'),
(2, 'Sports Fest 2025', 'A fun sports event for the students of the university.', 'University Stadium', '2025-07-15', 'Pending', 'sports_fest_approval.pdf', 2, 'QR987654321'),
(3, 'Cultural Night', 'An evening celebrating the diversity of cultures at the campus.', 'Cultural Hall', '2025-08-01', 'Approved', 'cultural_night_approval.pdf', 3, 'QR112233445'),
(4, 'Health and Wellness Expo', 'An exhibition to promote a healthy lifestyle and wellness practices.', 'Expo Hall, City Convention Center', '2025-09-05', 'Approved', 'health_wellness_approval.pdf', 4, 'QR667788990'),
(5, 'Art and Design Showcase', 'A showcase of student artwork and design projects.', 'Campus Art Gallery', '2025-10-20', 'Pending', 'art_showcase_approval.pdf', 5, 'QR556677889');
