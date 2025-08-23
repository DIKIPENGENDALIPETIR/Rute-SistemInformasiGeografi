<?php require '../include/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard STK</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-700">📋 Dashboard STK</h1>
    <a href="create.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">+ Tambah STK</a>
  </header>

  <main class="p-6 max-w-7xl mx-auto">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
      <?php
      $result = $conn->query("SELECT * FROM stks ORDER BY id DESC");
      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
        <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
          <?php if ($row['foto']): ?>
            <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Foto STK" class="w-full h-48 object-cover">
          <?php else: ?>
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 italic">Tidak ada foto</div>
          <?php endif; ?>
          <div class="p-4">
            <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($row['nama_stk']) ?></h2>
            <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars(mb_strimwidth($row['alamat'], 0, 60, "...")) ?></p>
            <div class="flex justify-between items-center mt-4">
              <a href="detail.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline text-sm">Detail</a>
              <div class="flex space-x-2">
                <a href="edit.php?id=<?= $row['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Hapus</a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; else: ?>
        <p class="col-span-3 text-center text-gray-500">Belum ada data STK.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
