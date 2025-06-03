<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UPPK Petra</title>
  @vite('resources/css/app.css')
</head>

<body class="bg-white text-gray-800 flex flex-col min-h-screen">
<style>
  body {
    margin: 0;
    background-image: url('petra.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* ✅ KUNCI: agar gambar tetap diam */
  }
</style>

  @include('base.header')

  <main class="flex-1">
    @yield('content')
  </main>

  @include('base.footer')

</body>
</html>
