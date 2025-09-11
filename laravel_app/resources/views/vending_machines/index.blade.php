<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>自販機一覧</title>
    <style>
        body{ font-family: sans-serif; margin: 20px; }
        ul{ padding-left: 1rem; }
    </style>
</head>
<body>
    <h1>自販機一覧</h1>

    @foreach($machines as $machine)
        <h2><a href="{{ url('/vending-machines/'.$machine->id) }}">{{ $machine->name }}</a></h2>
        <ul>
            @forelse($machine->inventories as $inventory)
                <li>
                    {{ $inventory->product->name ?? '不明な商品' }} (¥{{ $inventory->product->price ?? '-' }})
                    - 在庫: {{ $inventory->stock > 0 ? $inventory->stock : '売切れ' }}
                </li>
            @empty
                <li>商品データなし</li>
            @endforelse
        </ul>
    @endforeach

</body>
</html>
