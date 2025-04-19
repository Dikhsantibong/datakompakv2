-- Create meeting_shifts table as the main table
CREATE TABLE meeting_shifts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    current_shift ENUM('A', 'B', 'C', 'D') NOT NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Create meeting_shift_machine_statuses table
CREATE TABLE meeting_shift_machine_statuses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    meeting_shift_id BIGINT UNSIGNED NOT NULL,
    machine_id BIGINT UNSIGNED NOT NULL,
    status SET('operasi', 'standby', 'har_rutin', 'har_nonrutin', 'gangguan') NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (meeting_shift_id) REFERENCES meeting_shifts(id) ON DELETE CASCADE,
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

-- Create meeting_shift_auxiliary_equipment table
CREATE TABLE meeting_shift_auxiliary_equipment (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    meeting_shift_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    status SET('normal', 'abnormal', 'gangguan', 'flm') NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (meeting_shift_id) REFERENCES meeting_shifts(id) ON DELETE CASCADE
);

-- Create meeting_shift_resources table
CREATE TABLE meeting_shift_resources (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    meeting_shift_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    status ENUM('0-20', '21-40', '41-61', '61-80', 'up-80') NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (meeting_shift_id) REFERENCES meeting_shifts(id) ON DELETE CASCADE
);

-- Create meeting_shift_k3l table
CREATE TABLE meeting_shift_k3l (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    meeting_shift_id BIGINT UNSIGNED NOT NULL,
    type ENUM('unsafe_action', 'unsafe_condition') NOT NULL,
    uraian TEXT NOT NULL,
    saran TEXT NOT NULL,
    eviden_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (meeting_shift_id) REFERENCES meeting_shifts(id) ON DELETE CASCADE
);

-- Create meeting_shift_notes table
CREATE TABLE meeting_shift_notes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    meeting_shift_id BIGINT UNSIGNED NOT NULL,
    type ENUM('sistem', 'umum') NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (meeting_shift_id) REFERENCES meeting_shifts(id) ON DELETE CASCADE
);

-- Create meeting_shift_resume table
CREATE TABLE meeting_shift_resume (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    meeting_shift_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (meeting_shift_id) REFERENCES meeting_shifts(id) ON DELETE CASCADE
);

-- Create meeting_shift_attendance table
CREATE TABLE meeting_shift_attendance (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    meeting_shift_id BIGINT UNSIGNED NOT NULL,
    nama VARCHAR(255) NOT NULL,
    shift ENUM('A', 'B', 'C', 'D') NOT NULL,
    status ENUM('hadir', 'izin', 'sakit', 'cuti', 'alpha') NOT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (meeting_shift_id) REFERENCES meeting_shifts(id) ON DELETE CASCADE
); 