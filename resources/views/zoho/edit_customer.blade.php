<!DOCTYPE html>
<html>

<head>
    <title>Edit Customer</title>
</head>

<body>

    <h2>Edit Zoho Customer</h2>

    <form method="POST" action="/zoho/customer/{{ $customer['contact_id'] }}/update">
        @csrf

        <div>
            <label>Contact Name</label><br>
            <input type="text" name="contact_name" value="{{ $customer['contact_name'] }}">
        </div>

        <br>

        <div>
            <label>Company Name</label><br>
            <input type="text" name="company_name" value="{{ $customer['company_name'] ?? '' }}">
        </div>

        <br>

        <div>
            <label>Email</label><br>
            <input type="email" name="email" value="{{ $customer['email'] ?? '' }}">
        </div>

        <br>

        <div>
            <label>Phone</label><br>
            <input type="text" name="phone" value="{{ $customer['phone'] ?? '' }}">
        </div>

        <br>

        <button type="submit">Update Customer</button>

    </form>

</body>

</html>
