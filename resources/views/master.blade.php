
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Crud de productos</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
                {{--  <router-link to="/producto" class="nav-link">
                    <p>
                        Productos
                    </p>
                </router-link>  --}}
         </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content">
      <div class="container-fluid">

        <router-view></router-view>

      </div>
    </div>
  </div>
</div>


<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
