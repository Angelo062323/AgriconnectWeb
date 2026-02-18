<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            @include('admin.sidepanel')
        </aside>

        <div class="admin-main">
            <header class="admin-header">
                <button type="button" class="btn-ghost sidebar-toggle" id="sidebar-toggle" aria-label="Toggle side panel">
                    ☰
                </button>
                @yield('header')
            </header>

            <main class="admin-content">
                @yield('content')
            </main>
            
            <footer class="admin-footer">
                @yield('footer')
            </footer>
        </div>
    </div>
</body>
</html>