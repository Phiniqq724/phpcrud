<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "profil_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nama = $_POST['nama'];
        $nomor_absen = $_POST['nomor_absen'];
        $kelas = $_POST['kelas'];
        
        $foto = $_FILES['foto']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);

        $sql = "INSERT INTO profil (nama, nomor_absen, kelas, foto) VALUES ('$nama', '$nomor_absen', '$kelas', '$foto')";
        $conn->query($sql);
    }
    
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $nomor_absen = $_POST['nomor_absen'];
        $kelas = $_POST['kelas'];
        
        if (!empty($_FILES['foto']['name'])) {
            $foto = $_FILES['foto']['name'];
            $target_file = "uploads/" . basename($_FILES['foto']['name']);
            move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
            $sql = "UPDATE profil SET nama='$nama', nomor_absen='$nomor_absen', kelas='$kelas', foto='$foto' WHERE id=$id";
        } else {
            $sql = "UPDATE profil SET nama='$nama', nomor_absen='$nomor_absen', kelas='$kelas' WHERE id=$id";
        }
        $conn->query($sql);
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM profil WHERE id=$id";
        $conn->query($sql);
    }
}
$result = $conn->query("SELECT * FROM profil");
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Profil</title>
</head>
<body>
    <h2>Tambah Profil</h2>
    <form method="post" enctype="multipart/form-data">
        Nama: <input type="text" name="nama" required><br>
        Nomor Absen: <input type="text" name="nomor_absen" required><br>
        Kelas: <input type="text" name="kelas" required><br>
        Foto: <input type="file" name="foto" required><br>
        <input type="submit" name="add" value="Tambah">
    </form>

    <h2>Daftar Profil</h2>
    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Nomor Absen</th>
            <th>Kelas</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['nomor_absen'] ?></td>
                <td><?= $row['kelas'] ?></td>
                <td><img src="uploads/<?= $row['foto'] ?>" width="50"></td>
                <td>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        Nama: <input type="text" name="nama" value="<?= $row['nama'] ?>" required><br>
                        Nomor Absen: <input type="text" name="nomor_absen" value="<?= $row['nomor_absen'] ?>" required><br>
                        Kelas: <input type="text" name="kelas" value="<?= $row['kelas'] ?>" required><br>
                        Foto: <input type="file" name="foto"><br>
                        <input type="submit" name="edit" value="Edit">
                        <input type="submit" name="delete" value="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
