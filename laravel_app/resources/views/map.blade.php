<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>自販機マップ</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
        }
        /* モーダル */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: #fff;
            margin: 10% auto;
            padding: 20px;
            width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .close-btn {
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f5f5f5;
        }
        .out-of-stock {
            color: #aaa;
        }
    </style>
</head>
<body>
    <h1>自販機マップ</h1>
    <div id="map"></div>

    <!-- モーダル -->
    <div id="machineModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="machineName">自販機詳細</h2>
                <span class="close-btn" id="closeModal">&times;</span>
            </div>
            <div class="product-list">
                <table>
                    <thead>
                        <tr>
                            <th>商品名</th>
                            <th>価格</th>
                            <th>在庫</th>
                        </tr>
                    </thead>
                    <tbody id="productList">
                        <!-- 商品が入る -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([35.170768, 136.881951], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var modal = document.getElementById('machineModal');
        var closeModal = document.getElementById('closeModal');
        var machineName = document.getElementById('machineName');
        var productList = document.getElementById('productList');

        closeModal.onclick = function() {
            modal.style.display = "none";
        };
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        fetch('/api/vending-machines')
            .then(res => res.json())
            .then(data => {
                data.forEach(machine => {
                    var marker = L.marker([machine.latitude, machine.longitude]).addTo(map);
                    marker.bindPopup(machine.name);

                    marker.on('click', () => {
                        fetch(`/api/vending-machines/${machine.id}`)
                            .then(res => res.json())
                            .then(machineData => {
                                machineName.textContent = machineData.name;
                                productList.innerHTML = '';

                                if (machineData.inventories && machineData.inventories.length > 0) {
                                    machineData.inventories.forEach(inv => {
                                        var tr = document.createElement('tr');
                                        if (inv.stock === 0) {
                                            tr.classList.add('out-of-stock');
                                        }
                                        tr.innerHTML = `
                                            <td>${inv.product.name}</td>
                                            <td>${inv.product.price}円</td>
                                            <td>${inv.stock}</td>
                                        `;
                                        productList.appendChild(tr);
                                    });
                                } else {
                                    productList.innerHTML = `
                                        <tr><td colspan="3">商品情報がありません</td></tr>
                                    `;
                                }

                                modal.style.display = "block";
                            })
                            .catch(err => {
                                console.error(err);
                                alert('商品データの取得に失敗しました');
                            });
                    });
                });
            })
            .catch(err => {
                console.error(err);
                alert('自販機データの取得に失敗しました');
            });
    </script>
</body>
</html>
