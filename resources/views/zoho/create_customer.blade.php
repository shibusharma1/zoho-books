<!DOCTYPE html>
<html>

<head>
    <title>Create Zoho Customer</title>
</head>

<body>

    <h2>Create Customer (Zoho Books)</h2>

    <form method="POST" action="/zoho/customer/store">
        @csrf

        <div>
            <label>Contact Name</label><br>
            <input type="text" name="contact_name" required>
        </div>

        <br>

        <div>
            <label>Company Name</label><br>
            <input type="text" name="company_name">
        </div>

        <br>

        <div>
            <label>Email</label><br>
            <input type="email" name="email">
        </div>

        <br>

        <div>
            <label>Phone</label><br>
            <input type="text" name="phone">
        </div>

        <br>

        <button type="submit">Create Customer</button>

    </form>

</body>

</html>
