<!-- resources/views/items/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Items List</title>
</head>
<body>
    <h1>Items</h1>

    <ul>
        @foreach ($items as $item)
            <li>{{ $item->name }} - ${{ $item->price }}</li>
        @endforeach
    </ul>
</body>
</html>