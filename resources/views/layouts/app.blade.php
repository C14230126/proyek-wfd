<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>

  <!-- Tailwind & Alpine -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>

  <!-- ✅ Google Fonts: Open Sans -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    html, body {
      overflow-x: hidden;
      font-family: 'Open Sans', sans-serif;
    }
  </style>
</head>

<body class="bg-cover bg-center min-h-screen" style="background-image: url('{{ asset('images/bg.webp') }}');">

    <!-- Navbar -->
    <x-navbar />

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
