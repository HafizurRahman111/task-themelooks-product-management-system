<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Application')</title>
    <!-- Add other meta tags or assets like CSS, JS here -->
</head>

<body>
    <!-- Navbar or Header Section -->
    <nav>
        <!-- Your navigation here -->
    </nav>

    <!-- Main Content Section -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer Section -->
    <footer>
        <!-- Your footer here -->
    </footer>

    <!-- Add JavaScript or other scripts here -->
</body>

</html>