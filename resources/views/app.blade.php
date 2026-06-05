<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <!-- Primary meta -->
    <title>ForYou — Colecții de modă pentru femei</title>
    <meta name="description" content="Colecții exclusive de jachete, paltoane și haine pentru femei. Calitate superioară, stiluri actuale, livrare rapidă în toată Moldova.">
    <meta name="keywords" content="haine femei, jachete femei, paltoane femei, modă Moldova, îmbrăcăminte feminină, FORYOU">
    <meta name="author" content="FORYOU">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#1c1917">
    <meta name="color-scheme" content="light">

    <!-- Canonical -->
    <link rel="canonical" href="{{ config('app.url') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="FORYOU">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="FORYOU — Colecții de modă pentru femei">
    <meta property="og:description" content="Colecții exclusive de jachete, paltoane și haine pentru femei. Calitate superioară, livrare rapidă în toată Moldova.">
    <meta property="og:image" content="{{ asset('og-image.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="ro_MD">
    <meta property="og:locale:alternate" content="ru_MD">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="FORYOU — Colecții de modă pentru femei">
    <meta name="twitter:description" content="Colecții exclusive de jachete, paltoane și haine pentru femei.">
    <meta name="twitter:image" content="{{ asset('og-image.jpg') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <div id="app"></div>
</body>
</html>
