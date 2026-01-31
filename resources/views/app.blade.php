<!DOCTYPE html>
<html class="dark">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @routes
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('node_modules/flowbite/dist/flowbite.min.js')
    @inertiaHead
  </head>
  <body class="bg-dark-backdrop">
    @yield('content')
    @inertia
  </body>
</html>
