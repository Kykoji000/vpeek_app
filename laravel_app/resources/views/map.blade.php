<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>自販機マップ</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 100vh; }
    </style>
</head>
<body>
    <h1>自販機マップ</h1>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([35.170915, 136.881537], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        fetch('/api/vending-machines/nearby?lat=35.170915&lng=136.881537')
            .then(response => response.json())
            .then(data => {
                data.forEach(vm => {
                    let popupContent = `<b>${vm.name}</b><br>`;

                    if (vm.inventories && vm.inventories.length > 0) {
                        popupContent += "<ul>";
                        vm.inventories.forEach(inv => {
                            popupContent += `<li>${inv.product.name} - ${inv.product.price}円 (${inv.stock}個)</li>`;
                        });
                        popupContent += "</ul>";
                    } else {
                        popupContent += "商品情報なし";
                    }

                    L.marker([vm.latitude, vm.longitude])
                        .addTo(map)
                        .bindPopup(popupContent);
                });
            })
            .catch(err => console.error(err));
    </script>

</body>
</html>
