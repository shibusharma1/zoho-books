<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit | Taxes</title>
</head>

<body>
    <h2>Edit Tax</h2>

    <form method="POST" action="/zoho/taxes/{{ $tax['tax_id'] }}">
        @csrf
        @method('PUT')

        <label>Tax Name</label>
        <input type="text" name="tax_name" value="{{ $tax['tax_name'] }}">

        <br><br>

        <label>Tax Percentage</label>
        <input type="number" name="tax_percentage" step="0.01" value="{{ $tax['tax_percentage'] }}">

        <br><br>

        <button type="submit">Update Tax</button>
    </form>
    
</body>

</html>
