<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>近くの自販機</title>
    <style>body{font-family:sans-serif;margin:20px} table{border-collapse:collapse;width:100%} th,td{border:1px solid #ddd;padding:8px}</style>
</head>
<body>
    <h1>近くの自販機</h1>
    <p>検索座標: 緯度 {{ $lat ?? '—' }}, 経度 {{ $lng ?? '—' }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>名前</th><th>距離 (km)</th><th>商品</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($machines as $machine)
                <tr>
                    <td>{{ $machine->id }}</td>
                    <td><a href="{{ url('/vending-machines/'.$machine->id) }}">{{ $machine->name }}</a></td>
                    <td>{{ isset($machine->distance) ? number_format($machine->distance, 2) : '-' }}</td>
                    <td>
                        @if($machine->inventories->isNotEmpty())
                            <ul>
                                @foreach($machine->inventories as $inv)
                                    <li>{{ $inv->product->name ?? '不明' }} (在庫: {{ $inv->stock }})</li>
                                @endforeach
                            </ul>
                        @else
                            なし
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">該当する自販機が見つかりません</td></tr>
            @endforelse
        </tbody>
    </table>

    <p><a href="{{ url('/vending-machines') }}">一覧へ戻る</a></p>
</body>
</html>
