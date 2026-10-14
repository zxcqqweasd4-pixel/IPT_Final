<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Driving School Campus Management System' }} | Driving School CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .navbar-brand { font-weight: 700; }
        .page-heading { margin: 1.5rem 0; }
        .card { border: 0; box-shadow: 0 .125rem .5rem rgba(30, 50, 80, .07); }
        .table > :not(caption) > * > * { vertical-align: middle; }
        .required-mark { color: #dc3545; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Driving School CMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                @foreach (['students' => 'Students', 'instructors' => 'Instructors', 'vehicles' => 'Vehicles', 'courses' => 'Courses', 'lessons' => 'Lessons'] as $route => $label)
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs($route.'.*') ? 'active' : '' }}" href="{{ route($route.'.index') }}">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

<main class="container py-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-delete-url]');
        if (!button) return;
        document.getElementById('deleteRecordForm').action = button.dataset.deleteUrl;
        document.getElementById('deleteRecordName').textContent = button.dataset.recordName;
    });
</script>
@stack('scripts')
</body>
</html>
