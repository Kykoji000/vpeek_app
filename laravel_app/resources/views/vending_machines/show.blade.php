<!DOCTYPE html>
<html>
<head>
    <title>{{ $machine->name }}</title>
</head>
<body>
    <h1>{{ $machine->name }}</h1>
    <ul>
        @foreach($machine->inventories as $inventory)
            <li>
                {{ $inventory->product->name }} (¥{{ $inventory->product->price }})
                - 在庫: {{ $inventory->stock > 0 ? $inventory->stock : '売切れ' }}
            </li>
        @endforeach
    </ul>
</body>
</html>
