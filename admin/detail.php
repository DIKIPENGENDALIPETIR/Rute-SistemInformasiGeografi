<?php require '../include/config.php';
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM stks WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Detail STK</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
  <div class="bg-white p-6 rounded shadow w-full max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-2"><?= htmlspecialchars($data['nama_stk']) ?></h1>
    <p class="mb-2"><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($data['alamat'])) ?></p>
    <p class="mb-2"><strong>Keterangan:</strong> <?= nl2br(htmlspecialchars($data['keterangan'])) ?></p>
    <?php if ($data['foto']): ?>
      <img src="uploads/<?= $data['foto'] ?>" class="w-64 rounded mb-4">
    <?php endif; ?>
    <a href="dashboard.php" class="text-blue-500">← Kembali</a>
  </div>
</body>
</html>
