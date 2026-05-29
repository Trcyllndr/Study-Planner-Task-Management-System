-- =========================================
-- STUDY PLANNER SYSTEM - FINAL OLTP DATABASE
-- =========================================

CREATE DATABASE IF NOT EXISTS study_planner_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE study_planner_db;

-- =========================================
-- USERS TABLE
-- =========================================

CREATE TABLE users (

    id INT AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('student', 'admin')
        NOT NULL DEFAULT 'student',

    status ENUM('active', 'inactive')
        NOT NULL DEFAULT 'active',

    email_verified BOOLEAN
        NOT NULL DEFAULT FALSE,

    verification_token VARCHAR(64) NULL,

    reset_token VARCHAR(64) NULL,

    reset_token_expiry DATETIME NULL,

    last_login DATETIME NULL,

    deleted_at TIMESTAMP NULL,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- =========================================
-- CATEGORIES TABLE
-- =========================================

CREATE TABLE categories (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NULL,

    name VARCHAR(50) NOT NULL,

    category_type ENUM(
        'Project',
        'Exam',
        'Assignment',
        'Quiz',
        'Study',
        'Personal',
        'Other'
    ) NOT NULL DEFAULT 'Other',

    color VARCHAR(7)
        NOT NULL DEFAULT '#3498db',

    is_default BOOLEAN
        NOT NULL DEFAULT FALSE,

    deleted_at TIMESTAMP NULL,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_categories_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT unique_user_category
        UNIQUE(user_id, name)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- =========================================
-- TASKS TABLE
-- =========================================

CREATE TABLE tasks (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    category_id INT NULL,

    title VARCHAR(150) NOT NULL,

    description TEXT NULL,

    deadline DATETIME NOT NULL,

    priority ENUM(
        'Low',
        'Medium',
        'High'
    ) NOT NULL DEFAULT 'Medium',

    status ENUM(
        'pending',
        'completed',
        'overdue'
    ) NOT NULL DEFAULT 'pending',

    estimated_minutes INT NULL,

    completed_at DATETIME NULL,

    deleted_at TIMESTAMP NULL,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_tasks_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_tasks_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE SET NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- =========================================
-- TASK LOGS TABLE
-- Tracks task changes/history
-- =========================================

CREATE TABLE task_logs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    task_id INT NOT NULL,

    user_id INT NOT NULL,

    action_type ENUM(
        'created',
        'updated',
        'completed',
        'deleted'
    ) NOT NULL,

    old_status ENUM(
        'pending',
        'completed',
        'overdue'
    ) NULL,

    new_status ENUM(
        'pending',
        'completed',
        'overdue'
    ) NULL,

    action_description TEXT NULL,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tasklogs_task
        FOREIGN KEY (task_id)
        REFERENCES tasks(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_tasklogs_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- =========================================
-- NOTIFICATIONS TABLE
-- =========================================

CREATE TABLE notifications (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    task_id INT NULL,

    type ENUM(
        'reminder',
        'overdue',
        'system',
        'completion'
    ) NOT NULL DEFAULT 'system',

    message VARCHAR(255) NOT NULL,

    is_read BOOLEAN
        NOT NULL DEFAULT FALSE,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notifications_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_notifications_task
        FOREIGN KEY (task_id)
        REFERENCES tasks(id)
        ON DELETE SET NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- =========================================
-- ACTIVITY LOGS TABLE
-- Tracks user/system activities
-- =========================================

CREATE TABLE activity_logs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    action VARCHAR(100) NOT NULL,

    description TEXT NULL,

    ip_address VARCHAR(45) NULL,

    created_at TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_activitylogs_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- =========================================
-- INDEXES
-- =========================================

CREATE INDEX idx_tasks_deadline_status
ON tasks(deadline, status);

CREATE INDEX idx_tasks_priority
ON tasks(priority);

CREATE INDEX idx_tasks_user
ON tasks(user_id);

CREATE INDEX idx_tasklogs_created_at
ON task_logs(created_at);

CREATE INDEX idx_categories_type
ON categories(category_type);

CREATE INDEX idx_notifications_user
ON notifications(user_id);

CREATE INDEX idx_activitylogs_user
ON activity_logs(user_id);



-- =========================================
-- DEFAULT SYSTEM CATEGORIES
-- =========================================

INSERT INTO categories (
    user_id,
    name,
    category_type,
    color,
    is_default
)
VALUES
(NULL, 'Projects', 'Project', '#9b59b6', TRUE),
(NULL, 'Exams', 'Exam', '#e74c3c', TRUE),
(NULL, 'Assignments', 'Assignment', '#3498db', TRUE),
(NULL, 'Quizzes', 'Quiz', '#f39c12', TRUE),
(NULL, 'Study Sessions', 'Study', '#2ecc71', TRUE),
(NULL, 'Personal Tasks', 'Personal', '#1abc9c', TRUE);



-- =========================================
-- OPTIONAL DEFAULT ADMIN ACCOUNT
-- =========================================

/*

INSERT INTO users (
    full_name,
    email,
    password,
    role,
    status,
    email_verified
)
VALUES (
    'Administrator',
    'admin@studyplanner.com',
    '$2y$10$examplehashedpasswordhere',
    'admin',
    'active',
    TRUE
);

*/