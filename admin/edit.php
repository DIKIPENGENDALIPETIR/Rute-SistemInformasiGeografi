<?php
require '../include/config.php';

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM stks WHERE id=$id")->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_stk'];
    $alamat = $_POST['alamat'];
    $keterangan = $_POST['keterangan'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Tetap gunakan foto lama jika tidak diganti
    $foto = $data['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/$foto");
    }

    $stmt = $conn->prepare("UPDATE stks SET nama_stk=?, alamat=?, keterangan=?, latitude=?, longitude=?, foto=? WHERE id=?");
    $stmt->bind_param("sssddsi", $nama, $alamat, $keterangan, $latitude, $longitude, $foto, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit STK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">Edit STK</h1>
    <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow w-full max-w-lg space-y-4">
        <input name="nama_stk" value="<?= htmlspecialchars($data['nama_stk']) ?>" required class="border p-2 w-full" placeholder="Nama STK">

        <textarea name="alamat" required class="border p-2 w-full" placeholder="Alamat"><?= htmlspecialchars($data['alamat']) ?></textarea>

        <textarea name="keterangan" class="border p-2 w-full" placeholder="Keterangan"><?= htmlspecialchars($data['keterangan']) ?></textarea>

        <div class="flex space-x-4">
            <input type="number" step="any" name="latitude" required class="border p-2 w-full" placeholder="Latitude" value="<?= htmlspecialchars($data['latitude']) ?>">
            <input type="number" step="any" name="longitude" required class="border p-2 w-full" placeholder="Longitude" value="<?= htmlspecialchars($data['longitude']) ?>">
        </div>

        <?php if (!empty($data['foto'])): ?>
            <div>
                <p class="mb-2">Foto Saat Ini:</p>
                <img src="uploads/<?= htmlspecialchars($data['foto']) ?>" class="w-32 h-32 object-cover rounded border">
            </div>
        <?php endif; ?>

        <input type="file" name="foto" class="block">

        <div class="flex space-x-2 mt-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Update</button>
            <a class="bg-gray-400 text-white px-4 py-2 rounded" href="dashboard.php">Kembali</a>
        </div>
    </form>
</body>
</html>
