<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SSO Test Client</title>
    <style>
        body { max-width: 760px; margin: 3rem auto; padding: 0 1rem; font: 16px/1.5 system-ui, sans-serif; color: #172033; }
        nav { display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
        a { color: #2557d6; }
        pre, ul { padding: 1rem; background: #f4f6fa; border-radius: .5rem; overflow: auto; }
        button { padding: .6rem 1rem; cursor: pointer; }
    </style>
</head>
<body>
    <nav>
        <a href="/">Home</a>
        <a href="/dashboard">Dashboard</a>
        <a href="/user">User</a>
        <a href="/roles">Roles</a>
        <a href="/permissions">Permissions</a>
    </nav>

    @if ($section === 'home')
        <h1>SSO Test Client</h1>
        <p>This page is public. Open the dashboard to authenticate through the central SSO service.</p>
        <p><a href="/dashboard">Open protected dashboard</a></p>
    @else
        <h1>{{ ucfirst($section) }}</h1>

        @if (in_array($section, ['dashboard', 'user'], true))
            <h2>Authenticated user</h2>
            <pre>{{ json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        @endif

        @if (in_array($section, ['dashboard', 'roles'], true))
            <h2>Roles</h2>
            <ul>
                @forelse ($roles as $role)
                    <li>{{ is_array($role) ? ($role['name'] ?? json_encode($role)) : $role }}</li>
                @empty
                    <li>No roles.</li>
                @endforelse
            </ul>
        @endif

        @if (in_array($section, ['dashboard', 'permissions'], true))
            <h2>Permissions</h2>
            <ul>
                @forelse ($permissions as $permission)
                    <li>{{ is_array($permission) ? ($permission['name'] ?? json_encode($permission)) : $permission }}</li>
                @empty
                    <li>No permissions.</li>
                @endforelse
            </ul>
        @endif

        <form method="POST" action="{{ route('sso.client.logout') }}">
            @csrf
            <button type="submit">Central logout</button>
        </form>
    @endif
</body>
</html>
