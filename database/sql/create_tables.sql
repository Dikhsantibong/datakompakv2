-- Create Blackstart table
CREATE TABLE blackstarts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    unit_id INT NOT NULL,
    pembangkit_status ENUM('tersedia', 'tidak_tersedia') NOT NULL,
    black_start_status ENUM('tersedia', 'tidak_tersedia') NOT NULL,
    sop_status ENUM('tersedia', 'tidak_tersedia') NOT NULL,
    load_set_status ENUM('tersedia', 'tidak_tersedia') NOT NULL,
    line_energize_status ENUM('tersedia', 'tidak_tersedia') NOT NULL,
    status_jaringan ENUM('normal', 'tidak_normal') NOT NULL,
    pic VARCHAR(100) NOT NULL,
    status ENUM('open', 'close') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (unit_id) REFERENCES power_plants(id) ON DELETE CASCADE
);

-- Create Peralatan Blackstart table
CREATE TABLE peralatan_blackstarts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    blackstart_id BIGINT UNSIGNED NOT NULL,
    unit_id INT NOT NULL,
    kompresor_diesel_jumlah INT,
    kompresor_diesel_satuan VARCHAR(10) DEFAULT 'bh',
    kompresor_diesel_kondisi ENUM('normal', 'tidak_normal'),
    tabung_udara_jumlah INT,
    tabung_udara_satuan VARCHAR(10) DEFAULT 'bh',
    tabung_udara_kondisi ENUM('normal', 'tidak_normal'),
    ups_kondisi ENUM('normal', 'tidak_normal'),
    lampu_emergency_jumlah INT,
    lampu_emergency_kondisi ENUM('normal', 'tidak_normal'),
    battery_catudaya_jumlah INT,
    battery_catudaya_satuan VARCHAR(10) DEFAULT 'bh',
    battery_catudaya_kondisi ENUM('normal', 'tidak_normal'),
    battery_blackstart_jumlah INT,
    battery_blackstart_satuan VARCHAR(10) DEFAULT 'bh',
    battery_blackstart_kondisi ENUM('normal', 'tidak_normal'),
    radio_komunikasi_kondisi ENUM('normal', 'tidak_normal'),
    radio_kompresor_kondisi ENUM('normal', 'tidak_normal'),
    panel_kondisi ENUM('normal', 'tidak_normal'),
    simulasi_blackstart ENUM('pernah', 'belum_pernah'),
    start_kondisi_blackout ENUM('pernah', 'belum_pernah'),
    waktu_mulai TIME,
    waktu_selesai TIME,
    waktu_deadline TIME,
    pic VARCHAR(100),
    status ENUM('normal', 'tidak_normal'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blackstart_id) REFERENCES blackstarts(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_id) REFERENCES power_plants(id) ON DELETE CASCADE
);

-- Create meetings table
CREATE TABLE IF NOT EXISTS meetings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pekerjaan TEXT NOT NULL,
    pic VARCHAR(255) NOT NULL,
    deadline_start DATE NOT NULL,
    deadline_finish DATE NOT NULL,
    kondisi TEXT NOT NULL,
    status ENUM('open', 'on progress', 'closed') NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create link_koordinasis table
CREATE TABLE IF NOT EXISTS link_koordinasis (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uraian TEXT NOT NULL,
    link VARCHAR(255) NOT NULL,
    monitoring ENUM('harian', 'mingguan', 'bulanan') NOT NULL,
    koordinasi ENUM('eng', 'bs', 'ops') NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
