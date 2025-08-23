<?php
include '../include/config.php';

// Ambil semua STK dengan koordinat valid
$stks = [];
$res = $conn->query("SELECT id, nama_stk, latitude, longitude FROM stks WHERE latitude<>0 AND longitude<>0");
while ($r = $res->fetch_assoc()) {
    $stks[] = $r;
}

// Jika ada parameter ?id, ambil tujuan
$tujuan = null;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $result = $conn->query("SELECT id, nama_stk, latitude, longitude FROM stks WHERE id = $id AND latitude<>0 AND longitude<>0");
    if ($result && $result->num_rows === 1) {
        $tujuan = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Rute ke STK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    #map { height: 70vh; width: 100%; }
    #panel { height: 70vh; overflow-y: auto; }
  </style>
  <script>
    const defaultDestination = <?= $tujuan ? json_encode([
        'lat' => (float) $tujuan['latitude'],
        'lng' => (float) $tujuan['longitude'],
        'name' => $tujuan['nama_stk']
    ]) : 'null' ?>;
  </script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Header -->
  <header class="bg-white shadow-md p-5 flex justify-between items-center sticky top-0 z-10">
    <h1 class="text-2xl font-bold flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V6a2 2 0 012-2h14a2 2 0 012 2v9.382a2 2 0 01-1.553 1.894L15 20l-3-1.5L9 20z" />
      </svg>
      Rute ke STK
    </h1>
    <a href="index.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">← Kembali</a>
  </header>

  <!-- Main Content -->
  <main class="p-6 max-w-7xl mx-auto space-y-6">

    <!-- Pilihan Tujuan -->
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
      <h2 class="text-xl font-semibold">📍 Pilih Tujuan STK</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($stks as $s): ?>
        <div 
          class="p-4 border rounded hover:bg-blue-50 cursor-pointer"
          data-lat="<?= $s['latitude'] ?>"
          data-lng="<?= $s['longitude'] ?>"
          onclick="selectDestination(this)"
        >
          <h3 class="text-lg font-bold text-blue-700"><?= htmlspecialchars($s['nama_stk']) ?></h3>
          <p class="text-sm text-gray-500">Koordinat: <?= $s['latitude'] ?>, <?= $s['longitude'] ?></p>
          <a href="?id=<?= $s['id'] ?>" class="text-sm text-blue-500 hover:underline">Tampilkan Rute Otomatis</a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Peta dan Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Peta -->
      <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="p-4 border-b">
          <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">🗺️ Peta Lokasi</h2>
        </div>
        <div id="map" class="w-full h-[60vh] rounded-b-2xl"></div>
      </div>

      <!-- Rincian Rute -->
      <div class="bg-white rounded-2xl shadow-md flex flex-col">
        <div class="p-4 border-b">
          <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">📍 Rincian Rute</h2>
        </div>
        <div id="panel" class="p-4 text-sm text-gray-700 space-y-2 overflow-y-auto h-[200px]">
          <?php if ($tujuan): ?>
            <p class="text-blue-600 font-semibold">Tujuan: <?= htmlspecialchars($tujuan['nama_stk']) ?></p>
          <?php else: ?>
            <p class="text-gray-400 italic">Silakan pilih tujuan STK untuk menampilkan rute.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <!-- Script Google Maps -->
  <script>
    let map, directionsService, directionsRenderer;
    let renderers = [];

    function clearRenderers() {
      renderers.forEach(r => r.setMap(null));
      renderers = [];
    }

    function initMap() {
      directionsService = new google.maps.DirectionsService();
      directionsRenderer = new google.maps.DirectionsRenderer({ panel: document.getElementById('panel') });
      directionsRenderer.setMap(null); // hanya untuk panel

      map = new google.maps.Map(document.getElementById('map'), { zoom: 13 });

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
          const userLoc = { lat: pos.coords.latitude, lng: pos.coords.longitude };
          map.setCenter(userLoc);
          new google.maps.Marker({
            position: userLoc,
            map,
            title: "Lokasi Anda",
            icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
          });

          if (defaultDestination) {
            calculateRoute(userLoc, { lat: defaultDestination.lat, lng: defaultDestination.lng });
          }
        }, err => alert("Gagal mendeteksi lokasi: " + err.message));
      } else {
        alert("Browser tidak mendukung geolokasi.");
      }
    }

    function selectDestination(element) {
      const lat = parseFloat(element.dataset.lat);
      const lng = parseFloat(element.dataset.lng);

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
          const userLoc = { lat: pos.coords.latitude, lng: pos.coords.longitude };
          calculateRoute(userLoc, { lat, lng });
        }, err => alert("Tidak bisa memperoleh lokasi Anda."));
      }
    }

    function calculateRoute(start, end) {
      directionsService.route({
        origin: start,
        destination: end,
        travelMode: 'DRIVING',
        provideRouteAlternatives: true
      }, (response, status) => {
        if (status === 'OK') {
          clearRenderers();

          response.routes.forEach((route, index) => {
            const newRenderer = new google.maps.DirectionsRenderer({
              map: map,
              directions: response,
              routeIndex: index,
              suppressMarkers: false,
              polylineOptions: {
                strokeColor: index === 0 ? '#000000' : '#008000',
                strokeOpacity: 0.9,
                strokeWeight: 5
              }
            });
            renderers.push(newRenderer);
          });

          directionsRenderer.setDirections(response);
          directionsRenderer.setRouteIndex(0);
        } else {
          alert("Gagal menampilkan rute: " + status);
        }
      });
    }
  </script>

  <!-- Google Maps API -->
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCm_t75G1HRTGvFPaP95i3tUOpdRNWFYbE&callback=initMap">
  </script>
</body>
</html>
