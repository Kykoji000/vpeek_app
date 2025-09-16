<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自販機マップ</title>

    <!-- LeafletのCSS -->
    <link 
      rel="stylesheet" 
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
    />

    <style>
        #map {
            width: 100%;
            height: 100vh;
        }
    </style>
</head>
<body>
    <h1>自販機マップ</h1>
    <div id="map"></div>

    <!-- LeafletのJS -->
    <script 
      src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>

    <script>
        // 中心座標（東京駅付近）
        const centerLat = 35.170768;
        const centerLng = 136.881951;

        // マップを生成
        const map = L.map('map').setView([centerLat, centerLng], 13);

        // OpenStreetMapタイルを読み込み
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // サンプルのマーカー（東京駅）
        L.marker([centerLat, centerLng])
            .addTo(map)
            .bindPopup('名古屋駅のサンプル自販機')
            .openPopup();
    </script>
</body>
</html>
