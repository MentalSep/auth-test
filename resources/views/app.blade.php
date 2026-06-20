<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $section === 'home' ? 'Acme Workspace' : ucfirst($section).' · Acme Workspace' }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font: 16px/1.5 system-ui, sans-serif; color: #182033; background: #f6f7fb; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 2rem; padding: 1rem max(1rem, calc((100% - 1100px) / 2)); background: white; border-bottom: 1px solid #e4e7ef; }
        nav { display: flex; align-items: center; gap: 1rem; }
        a { color: #3159d5; text-decoration: none; }
        nav a { color: #4b5568; }
        main { max-width: 1100px; margin: auto; padding: 3rem 1rem; }
        h1 { font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.1; margin: 0 0 1rem; }
        h2 { margin-top: 0; }
        .brand { color: #182033; font-weight: 750; }
        .button { display: inline-block; padding: .75rem 1rem; border: 0; border-radius: .5rem; color: white; background: #3159d5; cursor: pointer; font: inherit; }
        .hero { max-width: 680px; padding: 5rem 0; }
        .muted { color: #657086; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-top: 2rem; }
        .card { padding: 1.25rem; background: white; border: 1px solid #e4e7ef; border-radius: .75rem; box-shadow: 0 5px 20px #1820330a; }
        .stat { font-size: 2rem; font-weight: 750; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: .85rem; text-align: left; border-bottom: 1px solid #e4e7ef; }
        details { position: relative; }
        summary { cursor: pointer; list-style: none; color: #3159d5; }
        .menu { position: absolute; right: 0; z-index: 1; width: 220px; padding: .75rem; background: white; border: 1px solid #e4e7ef; border-radius: .5rem; box-shadow: 0 10px 30px #18203320; }
        .menu a, .menu button { display: block; width: 100%; padding: .5rem; text-align: left; color: #3159d5; background: none; border: 0; font: inherit; cursor: pointer; }
        pre, ul.tags { padding: 1rem; background: #eef1f7; border-radius: .5rem; overflow: auto; }
        ul.tags { display: flex; flex-wrap: wrap; gap: .5rem; list-style: none; }
        ul.tags li { padding: .25rem .6rem; background: white; border-radius: 1rem; }
    </style>
</head>
<body>
    <header>
        <a class="brand" href="/">Acme Workspace</a>

        @if ($section === 'home')
            <a class="button" href="/dashboard">Sign in</a>
        @else
            <nav>
                <a href="/dashboard">Dashboard</a>
                <a href="/projects">Projects</a>
                <a href="/reports">Reports</a>
                <details>
                    <summary>{{ $user['name'] ?? $user['email'] ?? 'Profile' }}</summary>
                    <div class="menu">
                        <a href="/profile">Profile & access</a>
                        <a href="{{ rtrim(config('sso.auth_url'), '/') }}/dashboard">Central auth account</a>
                        <form method="POST" action="{{ route('sso.client.logout') }}">
                            @csrf
                            <button type="submit">Sign out</button>
                        </form>
                    </div>
                </details>
            </nav>
        @endif
    </header>

    <main>
        @if ($section === 'home')
            <section class="hero">
                <p class="muted">Internal project workspace</p>
                <h1>Plan projects, review reports, and keep work moving.</h1>
                <p class="muted">The landing page is public. Workspace content requires authentication through the company SSO service.</p>
                <a class="button" href="/dashboard">Open workspace</a>
            </section>
            <section class="grid">
                <article class="card"><h2>Projects</h2><p>Track current initiatives and owners.</p></article>
                <article class="card"><h2>Reports</h2><p>Review weekly delivery and security summaries.</p></article>
                <article class="card"><h2>Central access</h2><p>Roles and permissions come from company SSO.</p></article>
            </section>
        @elseif ($section === 'dashboard')
            <h1>Dashboard</h1>
            <p class="muted">Welcome back, {{ $user['name'] ?? 'team member' }}.</p>
            <section class="grid">
                <article class="card"><div class="stat">4</div><div>Active projects</div></article>
                <article class="card"><div class="stat">12</div><div>Open tasks</div></article>
                <article class="card"><div class="stat">3</div><div>Reports ready</div></article>
            </section>
        @elseif ($section === 'projects')
            <h1>Projects</h1>
            <p class="muted">Protected sample project data.</p>
            <section class="grid">
                <article class="card"><h2>Client Portal</h2><p>Owner: Platform team</p><strong>In progress</strong></article>
                <article class="card"><h2>Security Review</h2><p>Owner: Security team</p><strong>Review</strong></article>
                <article class="card"><h2>Data Migration</h2><p>Owner: Operations</p><strong>Planning</strong></article>
            </section>
        @elseif ($section === 'reports')
            <h1>Reports</h1>
            <p class="muted">Protected sample reports.</p>
            <table>
                <thead><tr><th>Report</th><th>Owner</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td>Weekly delivery</td><td>Platform</td><td>Ready</td></tr>
                    <tr><td>Access review</td><td>Security</td><td>Ready</td></tr>
                    <tr><td>Migration readiness</td><td>Operations</td><td>Draft</td></tr>
                </tbody>
            </table>
        @else
            <h1>Profile & access</h1>
            <section class="grid">
                <article class="card">
                    <h2>Authenticated user</h2>
                    <pre>{{ json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </article>
                <article class="card">
                    <h2>Roles</h2>
                    <ul class="tags">
                        @forelse ($roles as $role)
                            <li>{{ is_array($role) ? ($role['name'] ?? json_encode($role)) : $role }}</li>
                        @empty
                            <li>No roles</li>
                        @endforelse
                    </ul>
                    <h2>Permissions</h2>
                    <ul class="tags">
                        @forelse ($permissions as $permission)
                            <li>{{ is_array($permission) ? ($permission['name'] ?? json_encode($permission)) : $permission }}</li>
                        @empty
                            <li>No permissions</li>
                        @endforelse
                    </ul>
                </article>
            </section>
        @endif
    </main>
</body>
</html>
