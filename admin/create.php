<?php require '../include/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_stk'];
    $alamat = $_POST['alamat'];
    $keterangan = $_POST['keterangan'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $foto = '';
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/$foto");
    }

    $stmt = $conn->prepare("INSERT INTO stks (nama_stk, alamat, foto, keterangan, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdd", $nama, $alamat, $foto, $keterangan, $latitude, $longitude);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah STK</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
  <h1 class="text-2xl font-bold mb-4">Tambah STK</h1>
  <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow w-full max-w-lg space-y-4">
    <input name="nama_stk" required class="border p-2 w-full" placeholder="Nama STK">
    <textarea name="alamat" required class="border p-2 w-full" placeholder="Alamat"></textarea>
    <textarea name="keterangan" class="border p-2 w-full" placeholder="Keterangan"></textarea>

    <div class="flex space-x-4">
      <input type="number" step="any" name="latitude" required class="border p-2 w-full" placeholder="Latitude">
      <input type="number" step="any" name="longitude" required class="border p-2 w-full" placeholder="Longitude">
    </div>

    <input type="file" name="foto" class="block">
    
    <div class="flex space-x-2 mt-4">
      <button class="bg-green-500 text-white px-4 py-2 rounded" type="submit">Simpan</button>
      <a class="bg-gray-400 text-white px-4 py-2 rounded" href="dashboard.php">Kembali</a>
    </div>
  </form>
</body>
</html>
