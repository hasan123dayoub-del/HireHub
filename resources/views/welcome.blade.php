<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HireHub API</title>
    <meta name="description" content="HireHub: a freelance marketplace REST API built with Laravel 12. Try it live with demo accounts.">
    <style>
        :root { --bg: #fafafa; --surface: #fff; --border: #e4e4e7; --text: #52525b; --text-h: #09090b; --accent: #4f46e5; --accent-bg: rgba(79, 70, 229, .08); }
        @media (prefers-color-scheme: dark) {
            :root { --bg: #0b0d12; --surface: #12151c; --border: #232733; --text: #a1a1aa; --text-h: #f4f4f5; --accent: #818cf8; --accent-bg: rgba(129, 140, 248, .1); }
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--text); font: 16px/1.6 system-ui, 'Segoe UI', Roboto, sans-serif; }
        .wrap { max-width: 880px; margin: 0 auto; padding: 48px 16px 64px; }
        h1 { color: var(--text-h); font-size: 40px; margin: 0; letter-spacing: -.02em; }
        h2 { color: var(--text-h); font-size: 20px; margin: 40px 0 12px; }
        .lead { font-size: 18px; margin: 8px 0 24px; }
        .actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .btn { display: inline-block; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid var(--border); color: var(--text-h); background: var(--surface); }
        .btn.primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 4px 16px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { text-align: left; padding: 10px 4px; border-bottom: 1px solid var(--border); vertical-align: top; }
        tr:last-child td { border-bottom: 0; }
        th { color: var(--text-h); font-size: 14px; }
        code { font-family: ui-monospace, Consolas, monospace; font-size: 14px; background: var(--accent-bg); color: var(--text-h); padding: 2px 6px; border-radius: 4px; }
        a { color: var(--accent); }
        ol, ul { padding-left: 20px; margin: 0; }
        li { margin: 6px 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; }
        .grid .card { padding: 14px 16px; }
        .grid b { color: var(--text-h); display: block; margin-bottom: 4px; }
        .note { font-size: 14px; margin-top: 12px; }
        footer { margin-top: 48px; font-size: 14px; }
        @media (max-width: 560px) { h1 { font-size: 32px; } .hide-sm { display: none; } }
    </style>
</head>
<body>
<main class="wrap">
    <h1>HireHub API</h1>
    <p class="lead">A freelance marketplace REST API built with Laravel 12: clients post projects, freelancers send proposals, and clients accept one.</p>
    <div class="actions">
        <a class="btn primary" href="{{ url('/docs/api') }}">Try the API in your browser →</a>
        <a class="btn" href="https://github.com/hasan123dayoub-del/HireHub">Source on GitHub</a>
        <a class="btn" href="https://github.com/hasan123dayoub-del/HireHub/blob/main/docs/HireHub.postman_collection.json">Postman collection</a>
    </div>

    <h2>Try it in three steps</h2>
    <div class="card" style="padding: 14px 16px">
        <ol>
            <li>Open the <a href="{{ url('/docs/api') }}">interactive docs</a> and send <code>POST /api/login</code> with a demo account below.</li>
            <li>Copy the <code>token</code> from the response.</li>
            <li>Open any protected endpoint, paste the token as the <b>Bearer token</b>, and press <b>Send API Request</b>.</li>
        </ol>
    </div>

    <h2>Demo accounts</h2>
    <div class="card">
        <table>
            <tr><th>Role</th><th>Email</th><th>Password</th><th class="hide-sm">Can</th></tr>
            <tr><td>Client</td><td><code>client@hirehub.com</code></td><td><code>password</code></td><td class="hide-sm">post, edit and delete projects</td></tr>
            <tr><td>Freelancer</td><td><code>dev@hirehub.com</code></td><td><code>password</code></td><td class="hide-sm">send proposals, manage profile and skills</td></tr>
            <tr><td>Admin</td><td><code>admin@hirehub.com</code></td><td><code>Example@Pass2026</code></td><td class="hide-sm">see platform statistics</td></tr>
        </table>
    </div>

    <h2>Public endpoints (no token needed)</h2>
    <div class="card">
        <table>
            <tr><td><a href="{{ url('/api/projects') }}"><code>GET /api/projects</code></a></td><td>Open projects with client, tags and proposal count (paginated)</td></tr>
            <tr><td><a href="{{ url('/api/projects/1') }}"><code>GET /api/projects/1</code></a></td><td>One project</td></tr>
            <tr><td><a href="{{ url('/api/freelancers') }}"><code>GET /api/freelancers</code></a></td><td>Verified freelancers with skills, best rated first</td></tr>
        </table>
    </div>

    <h2>What's inside</h2>
    <div class="grid">
        <div class="card"><b>Layered architecture</b>Controllers, Form Requests, Services and Repositories bound to interfaces.</div>
        <div class="card"><b>Auth and roles</b>Sanctum tokens, verified emails and role middleware for clients, freelancers and admins.</div>
        <div class="card"><b>Performance</b>Eager loading cut the project listing from 48 to 5 queries; listings are cached with tags.</div>
        <div class="card"><b>Events and jobs</b>Accepting a proposal rejects the others in one transaction and notifies the freelancer.</div>
        <div class="card"><b>Tested</b>Feature and unit tests run on every push with GitHub Actions.</div>
        <div class="card"><b>Documented</b>OpenAPI docs generated from the code, plus a Postman collection.</div>
    </div>

    <p class="note">Free hosting: the server sleeps when idle, so the first request can take up to a minute. The demo database is reset on every restart.</p>

    <footer>Built by <a href="https://hasan123dayoub-del.github.io/portfolio/">Hasan Dayoub</a>.</footer>
</main>
</body>
</html>
