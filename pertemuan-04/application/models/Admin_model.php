<?php
// DIPERBARUI di P4
class Admin_model
{
    private mysqli $db;
public function __construct()
{
global $db;
$this->db = $db;
}
// DIPERTAHANKAN DARI P3 - Auth.php membutuhkan password untuk password_verify().
public function findByUsername(string $username): ?array
{
$sql = "
SELECT username, password, status_akun
FROM t_admin
WHERE username = ?
LIMIT 1
";
$stmt = $this->db->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
return $row ?: null;
}
// P4: BARU - membaca seluruh data admin untuk halaman daftar tanpa mengambil password hash.
public function getAll(): array
{
$sql = "
SELECT username, status_akun
FROM t_admin
ORDER BY username ASC
";
$stmt = $this->db->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
return $rows;
}
// P4: BARU - menambah admin baru.
public function create(
string $username,
string $passwordHash,
string $statusAkun
): bool {
$sql = "
INSERT INTO t_admin (username, password, status_akun)
VALUES (?, ?, ?)
";
$stmt = $this->db->prepare($sql);
$stmt->bind_param('sss', $username, $passwordHash, $statusAkun);
$success = $stmt->execute();
$stmt->close();
return $success;
}
// P4: BARU - mengubah username, status, dan, bila diisi, password admin.
public function update(
    string $usernameLama,
    string $usernameBaru,
    ?string $passwordHash,
    string $statusAkun
): bool {
if ($passwordHash !== null) {
$sql = "
UPDATE t_admin
SET username = ?, password = ?, status_akun = ?
WHERE username = ?
";
$stmt = $this->db->prepare($sql);
$stmt->bind_param('ssss', $usernameBaru, $passwordHash, $statusAkun, $usernameLama);
} else {
$sql = "
UPDATE t_admin
SET username = ?, status_akun = ?
WHERE username = ?
";
$stmt = $this->db->prepare($sql);
$stmt->bind_param('sss', $usernameBaru, $statusAkun, $usernameLama);
}
$success = $stmt->execute();
$stmt->close();
return $success;
}
// P4: BARU - menghapus admin berdasarkan primary key username.
public function delete(string $username): bool
{
$sql = "
DELETE FROM t_admin
WHERE username = ?
";
$stmt = $this->db->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$affectedRows = $stmt->affected_rows;
$stmt->close();
return $affectedRows > 0;
}
}