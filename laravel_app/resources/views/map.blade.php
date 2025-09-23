<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>自販機マップ</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        html,body { height:100%; margin:0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
        #map { height: calc(100vh - 48px); } 
        header { height:48px; padding:10px 16px; background:#fafafa; border-bottom:1px solid #eee; }
        .modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9998; }
        .modal-window { display:none; position:fixed; z-index:9999; left:50%; top:12%; transform:translateX(-50%); background:#fff; border-radius:8px; width:min(720px,94%); max-height:76vh; overflow:auto; box-shadow:0 8px 30px rgba(0,0,0,0.25); padding:18px; }
        .modal-window h2 { margin:0 0 12px; font-size:18px; }
        .modal-close { display:inline-block; margin-top:12px; padding:8px 12px; background:#2b6cb0; color:#fff; border-radius:6px; cursor:pointer; text-decoration:none; }
        ul.prod-list { padding-left:18px; margin:0; }
        ul.prod-list li { margin:6px 0; }
    </style>
</head>
<body>
    <header>
        <strong>自販機マップ</strong>
    </header>

    <div id="map"></div>

    <!-- モーダル要素 -->
    <div id="modalBackdrop" class="modal-backdrop" onclick="closeModal()"></div>
    <div id="modalWindow" class="modal-window" role="dialog" aria-hidden="true">
        <h2 id="modalTitle">読み込み中…</h2>
        <div id="modalBody">
            <ul id="modalItems" class="prod-list"></ul>
        </div>
        <a class="modal-close" onclick="closeModal()">閉じる</a>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // 名古屋中心
        const CENTER_LAT = 35.170915, CENTER_LNG = 136.881537;
        const map = L.map('map').setView([CENTER_LAT, CENTER_LNG], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // モーダル helpers
        function openModal() {
            document.getElementById('modalBackdrop').style.display = 'block';
            document.getElementById('modalWindow').style.display = 'block';
            document.getElementById('modalWindow').setAttribute('aria-hidden','false');
        }
        function closeModal() {
            document.getElementById('modalBackdrop').style.display = 'none';
            document.getElementById('modalWindow').style.display = 'none';
            document.getElementById('modalWindow').setAttribute('aria-hidden','true');
        }

        // 自販機データ取得 & マーカー描画
        fetch('/api/vending-machines')
            .then(r => {
                if (!r.ok) throw new Error('API error ' + r.status);
                return r.json();
            })
            .then(data => {
                data.forEach(vm => {
                    const lat = parseFloat(vm.latitude ?? vm.lat ?? vm.latitude);
                    const lng = parseFloat(vm.longitude ?? vm.lng ?? vm.longitude);
                    if (!isFinite(lat) || !isFinite(lng)) return;

                    const marker = L.marker([lat, lng]).addTo(map);

                    // popup 中のボタンは popupopen でイベントを付与する方式にする
                    const popupHtml = `<b>${escapeHtml(vm.name)}</b><br><button class="show-btn" data-id="${vm.id}">詳細を見る</button>`;
                    marker.bindPopup(popupHtml);
                });
            })
            .catch(err => {
                console.error(err);
                alert('自販機データの取得に失敗しました。コンソールを確認してください。');
            });

        // popupopen で show-btn に handler を付与
        map.on('popupopen', function(e) {
            let popupEl = null;
            try {
                popupEl = (typeof e.popup.getElement === 'function') ? e.popup.getElement() : e.popup._contentNode;
            } catch (err) {
                popupEl = e.popup._contentNode || null;
            }
            if (!popupEl) return;

            const btn = popupEl.querySelector('.show-btn');
            if (!btn) return;

            // 可能なら既存 handler を削除して二重登録を防ぐ
            if (btn._vmHandler) btn.removeEventListener('click', btn._vmHandler);

            btn._vmHandler = function () {
                const id = this.getAttribute('data-id');
                fetch(`/api/vending-machines/${id}`)
                    .then(r => {
                        if (!r.ok) throw new Error('API error ' + r.status);
                        return r.json();
                    })
                    .then(machine => {
                        document.getElementById('modalTitle').textContent = machine.name ?? '自販機';
                        const list = document.getElementById('modalItems');
                        list.innerHTML = '';
                        const invs = machine.inventories ?? [];
                        if (invs.length === 0) {
                            const li = document.createElement('li');
                            li.textContent = '商品情報がありません';
                            list.appendChild(li);
                        } else {
                            invs.forEach(inv => {
                                const prod = inv.product ?? {};
                                const li = document.createElement('li');
                                li.textContent = `${prod.name ?? '不明'} — ¥${prod.price ?? '-'} (在庫: ${inv.stock ?? '-'})`;
                                list.appendChild(li);
                            });
                        }
                        openModal();
                    })
                    .catch(err => {
                        console.error(err);
                        document.getElementById('modalTitle').textContent = '読み込みエラー';
                        document.getElementById('modalItems').innerHTML = '<li>データ取得に失敗しました。</li>';
                        openModal();
                    });
            };

            btn.addEventListener('click', btn._vmHandler);
        });

        // ヘルパ
        function escapeHtml(s) {
            if (!s) return '';
            return String(s).replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'", '&#39;');
        }
    </script>
</body>
</html>
