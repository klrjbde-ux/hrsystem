<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        .credentials {
            background-color: #f9f9f9;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .credentials p {
            margin: 0;
            font-weight: bold;
        }

        .cta {
            text-align: center;
            margin-top: 20px;
        }

        .cta a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to BLS SOL</h1>
        <p>Hello {{$name}},</p>
        <p>Congratulations on joining our team! We're thrilled to have you aboard.</p>
        <p>Below are your login credentials:</p>
        <div class="credentials">
            <p>Your login credentials:</p>
            <p>Email: {{$email}}</p>
            <p>Password: {{$password}}</p>
        </div>
        <div class="cta">
            <a href="{{ route('login') }}">Login Now</a>
        </div>
        <p>If you have any questions or need assistance, feel free to contact us.</p>
        <p>Best regards,</p>
    </div>
</body>

</html>