<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoho</title>
</head>
<body>
    <div id="app"></div>

    <script>
        const zoho = {{ Js::from([
            'domain' => config('services.zoho.domain'),
            'client_id' => config('services.zoho.client_id'),
            'redirect_uri' => config('services.zoho.redirect_uri'),
            'scope' => config('services.zoho.scope'),
        ]) }}
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</body>
</html>