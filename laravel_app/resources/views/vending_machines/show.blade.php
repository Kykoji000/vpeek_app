<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $machine->name }}</title>
</head>
<body>
    <h1>{{ $machine->name }}</h1>

    <ul>
        @forelse($machine->inventories as $inventory)
            <li>{{ $inventory->product->name ?? '不明' }} (¥{{ $inventory->product->price ?? '-' }}) - 在庫: {{ $inventory->stock > 0 ? $inventory->stock : '売切れ' }}</li>
        @empty
            <li>在庫情報なし</li>
        @endforelse
    </ul>

    <a href="{{ url('/vending-machines') }}">一覧へ戻る</a>
</body>
</html>
