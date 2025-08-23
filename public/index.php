<?php include '../include/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard STK</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <header class="bg-white shadow p-4 flex justify-between items-center">
  </header>

  <main class="p-6 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php
      $result = $conn->query("SELECT * FROM stks ORDER BY id DESC");
      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
  <?php if (!empty($row['foto'])): ?>
    <img src="../admin/uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto STK" class="w-full h-64 object-cover">
  <?php else: ?>
    <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500 italic">
      Tidak ada Foto
    </div>
  <?php endif; ?>

  <div class="p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-2">
      <?= htmlspecialchars($row['nama_stk']) ?>
    </h2>

    <div class="mb-2">
      <h3 class="text-sm text-gray-500 font-semibold">Alamat:</h3>
      <p class="text-gray-700"><?= nl2br(htmlspecialchars($row['alamat'])) ?></p>
    </div>

    <div class="mt-4 flex justify-end">
        <a href="view.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline text-sm">Detail</a>
    </div>
  </div>
    </div>

      <?php endwhile; else: ?>
        <p class="col-span-4 text-center text-gray-500">Belum ada data STK.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
