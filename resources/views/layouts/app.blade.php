<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Workroute - Issue Tracker' }}</title>
    <style>
        /* Base polosan layout */
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background-color: #f9f9f9;
            color: #333;
        }
        .sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar h2 {
            margin-top: 0;
            font-size: 20px;
            color: #000;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0 0 auto 0;
        }
        .sidebar-menu li {
            margin-bottom: 12px;
        }
        .sidebar-menu a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            display: block;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #f0f0f0;
            color: #000;
        }
        .sidebar hr {
            border: 0;
            border-top: 1px solid #e0e0e0;
            margin: 15px 0;
        }
        .profile-section {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        .profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            background-color: #ccc;
        }
        .profile-details {
            position: relative;
        }
        .profile-details summary {
            cursor: pointer;
            font-weight: bold;
            list-style: none;
            user-select: none;
        }
        .profile-details summary::-webkit-details-marker {
            display: none;
        }
        .profile-dropdown {
            background: white;
            border: 1px solid #ccc;
            padding: 10px;
            position: absolute;
            bottom: 100%;
            left: 0;
            margin-bottom: 5px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 100;
            min-width: 120px;
        }
        .profile-dropdown a, .profile-dropdown button {
            display: block;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 5px 10px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            cursor: pointer;
            box-sizing: border-box;
        }
        .profile-dropdown a:hover, .profile-dropdown button:hover {
            background-color: #f5f5f5;
        }
        .main-content {
            flex-grow: 1;
            padding: 30px;
            box-sizing: border-box;
            overflow-y: auto;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        /* Tables and forms standard base formatting */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        /* Status styling placeholders */
        .status-unassigned { background-color: #e0e0e0; color: #333; } /* Abu-abu */
        .status-assigned { background-color: #ffe8d6; color: #d9534f; } /* Oranye */
        .status-inprogress { background-color: #fff3cd; color: #856404; } /* Kuning */
        .status-complete { background-color: #d4edda; color: #155724; } /* Hijau */
        
        /* Priority arrow indicators styling */
        .priority-low { color: green; font-weight: bold; } /* Bawah Hijau */
        .priority-medium { color: orange; font-weight: bold; } /* Kanan Kuning */
        .priority-high { color: red; font-weight: bold; } /* Atas Merah */
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>Workroute</h2>
        <ul class="sidebar-menu">
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') && !request()->routeIs('tasks.history') ? 'active' : '' }}">Task</a></li>
            <li><a href="{{ route('tasks.history') }}" class="{{ request()->routeIs('tasks.history') ? 'active' : '' }}">History</a></li>
            
            <hr>
            
            <li><a href="{{ route('chat.individual') }}" class="{{ request()->routeIs('chat.individual') ? 'active' : '' }}">Individual Chat</a></li>
            @if(auth()->user()->role !== 'client')
                <li><a href="{{ route('chat.group') }}" class="{{ request()->routeIs('chat.group') ? 'active' : '' }}">Group Chat</a></li>
            @endif
        </ul>
        
        <hr>

        <!-- User Profile Section -->
        <div class="profile-section">
            <a href="{{ route('profile.edit') }}">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="profile-avatar" alt="Avatar" title="Go to Profile">
                @else
                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(auth()->user()->email))) }}?d=mp" class="profile-avatar" alt="Avatar" title="Go to Profile">
                @endif
            </a>
            <div class="profile-details">
                <details>
                    <summary>{{ auth()->user()->name }} <small>({{ ucfirst(auth()->user()->role) }})</small></summary>
                    <div class="profile-dropdown">
                        <a href="{{ route('profile.edit') }}">Lihat Profil</a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

</body>
</html>
