<!DOCTYPE html>
<html>
<head>
    <title>自販機一覧</title>
</head>
<body>
    <h1>自販機一覧</h1>

    @foreach($machines as $machine)
        <h2>{{ $machine->name }}</h2>
        <ul>
            @foreach($machine->inventories as $inventory)
                <li>
                    {{ $inventory->product->name }} (¥{{ $inventory->product->price }})
                    - 在庫: {{ $inventory->stock > 0 ? $inventory->stock : '売切れ' }}
                </li>
            @endforeach
        </ul>
    @endforeach

</body>
</html>
