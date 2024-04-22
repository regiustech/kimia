<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&family=Sunflower:wght@300;500;700&display=swap" rel="stylesheet">
        <link rel="shortcut icon" href="/assets/images/favicon.png"/>
        <link rel="stylesheet" href="/assets/css/fontello.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/assets/css/admin.css">
        @routes
        @vite(['resources/js/app.js',"resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <div id="rt-custom-loader">
            <div class="inner">
                <svg viewBox="0 0 38 38" width="40" height="40" stroke="#cfcfcf"><g fill="none" fillRule="evenodd"><g transform="translate(1 1)" stroke-width="3"><circle stroke-opacity=".25" cx="18" cy="18" r="18"/><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.8s" repeatCount="indefinite"/></path></g></g></svg>
            </div>
        </div>
        @inertia
    </body>
    <script src="https://js.stripe.com/v3"></script>
</html>
