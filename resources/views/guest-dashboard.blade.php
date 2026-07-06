<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workroute - Issue Tracking System</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .navbar {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
            font-size: 22px;
        }
        .navbar-links a {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
            margin-left: 15px;
        }
        .navbar-links a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .hero {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 30px;
            text-align: center;
            margin-bottom: 25px;
        }
        .hero h2 {
            margin-top: 0;
        }
        .hero p {
            color: #666;
            max-width: 600px;
            margin: 10px auto 20px;
        }
        .hero a {
            display: inline-block;
            background: #007bff;
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: bold;
            margin: 0 5px;
        }
        .hero a.secondary {
            background: #6c757d;
        }
        .stats-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 15px 25px;
            flex: 1;
            min-width: 120px;
        }
        .stat-card h4 {
            margin: 0 0 5px 0;
            color: #666;
            font-size: 13px;
        }
        .stat-card span {
            font-size: 28px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #e0e0e0;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-unassigned { background-color: #e0e0e0; color: #333; }
        .status-assigned { background-color: #ffe8d6; color: #d9534f; }
        .status-inprogress { background-color: #fff3cd; color: #856404; }
        .status-complete { background-color: #d4edda; color: #155724; }
        .notice {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Workroute</h1>
        <div class="navbar-links">
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>

    <div class="container">

        <div class="hero">
            <h2>Issue Tracking System</h2>
            <p>Workroute membantu tim Anda mengelola issues, melacak progress pekerjaan, dan berkomunikasi secara efisien.</p>
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}" class="secondary">Register</a>
        </div>

        <div class="notice">
            Anda sedang melihat sebagai Guest. Silakan <a href="{{ route('login') }}">login</a> untuk membuat issues, mengelola tugas, dan menggunakan chat.
        </div>

        <!-- Stats Overview (read-only) -->
        <div class="stats-grid">
            <div class="stat-card">
                <h4>Total Issues</h4>
                <span>{{ $stats['total'] }}</span>
            </div>
            <div class="stat-card">
                <h4>Unassigned</h4>
                <span style="color: #555;">{{ $stats['unassigned'] }}</span>
            </div>
            <div class="stat-card">
                <h4>Assigned</h4>
                <span style="color: #d9534f;">{{ $stats['assigned'] }}</span>
            </div>
            <div class="stat-card">
                <h4>In Progress</h4>
                <span style="color: #856404;">{{ $stats['in_progress'] }}</span>
            </div>
            <div class="stat-card">
                <h4>Complete</h4>
                <span style="color: #155724;">{{ $stats['complete'] }}</span>
            </div>
        </div>

        <!-- Recent Issues (read-only, no actions) -->
        <div style="background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px;">
            <h3 style="margin-top: 0;">Recent Issues</h3>
            @if($recentIssues->isEmpty())
                <p style="color: #777;">No issues found.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentIssues as $issue)
                            <tr>
                                <td style="font-weight: bold;">{{ $issue->id }}</td>
                                <td>{{ $issue->subject }}</td>
                                <td>{{ $issue->type }}</td>
                                <td>
                                    <span class="badge status-{{ strtolower(str_replace(' ', '', $issue->status)) }}">
                                        {{ $issue->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($issue->priority == 'Low')
                                        <span style="color: green; font-weight: bold;">↓ Low</span>
                                    @elseif($issue->priority == 'Medium')
                                        <span style="color: orange; font-weight: bold;">→ Medium</span>
                                    @else
                                        <span style="color: red; font-weight: bold;">↑ High</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

</body>
</html>
