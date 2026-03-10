<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Taxes</title>
</head>

<body>
    <h2>Create Tax</h2>

    <form method="POST" action="/zoho/taxes/store">
        @csrf

        <label>Tax Name</label>
        <input type="text" name="tax_name" required>

        <br><br>

        <label>Tax Percentage</label>
        <input type="number" name="tax_percentage" step="0.01" required>

        <br><br>

        <button type="submit">Create Tax</button>
    </form>
</body>

</html>
