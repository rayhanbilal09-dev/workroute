@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <h1>User Profile</h1>
    <p>View and manage your account details.</p>
</div>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    <!-- Profile Edit Form -->
    <div style="flex: 2; min-width: 350px; background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px;">Edit Details</h3>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 5px;">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email Address</label>
                <input type="email" value="{{ $user->email }}" style="width: 100%; padding: 8px; border: 1px solid #eee; border-radius: 4px; background-color: #f9f9f9;" disabled>
                <small style="color: #888;">Email address cannot be changed.</small>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="avatar" style="display: block; font-weight: bold; margin-bottom: 5px;">Profile Avatar</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" style="width: 100%;">
                <small style="color: #888; display: block; margin-top: 5px;">Upload a new profile picture (JPEG, PNG, max 2MB).</small>
            </div>

            <div style="margin-bottom: 15px; border-top: 1px solid #eee; padding-top: 15px;">
                <h4 style="margin-top: 0; margin-bottom: 10px;">Change Password</h4>
                <p style="color: #666; font-size: 13px; margin: 0 0 10px 0;">Leave password fields empty to keep your current password.</p>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; font-weight: bold; margin-bottom: 5px;">New Password</label>
                <input type="password" id="password" name="password" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label for="password_confirmation" style="display: block; font-weight: bold; margin-bottom: 5px;">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                Save Changes
            </button>
        </form>
    </div>

    <!-- Profile Overview Card -->
    <div style="flex: 1; min-width: 280px; background: white; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; text-align: center;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px; text-align: left;">Overview</h3>
        
        <div style="display: inline-block; margin-bottom: 15px;">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff;" alt="Avatar">
            @else
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?d=mp&s=120" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff;" alt="Avatar">
            @endif
        </div>
        
        <h2>{{ $user->name }}</h2>
        <span class="badge" style="background-color: #007bff; color: white; padding: 6px 12px; font-size: 13px;">
            {{ ucfirst($user->role) }}
        </span>
        
        <div style="margin-top: 25px; text-align: left; font-size: 14px; color: #555; border-top: 1px solid #eee; padding-top: 15px;">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Member Since:</strong> {{ $user->created_at->format('d M Y') }}</p>
        </div>
    </div>
</div>
@endsection
