<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>@yield('title','Tasks for Today')</title>
 <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
 <header><nav aria-label="Main navigation"><a class="brand" href="{{ route('home') }}">Tasks for Today</a>
 @foreach(['home'=>'Welcome','tasks.index'=>'Task List','profile'=>'Profile','about'=>'About'] as $route=>$label)
  <a href="{{ route($route) }}" @if(request()->routeIs($route)) aria-current="page" @endif>{{ $label }}</a>
 @endforeach
 @auth
  <span class="account">{{ auth()->user()->full_name }}</span>
  <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-button">Log out</button></form>
 @else
  <a href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif>Log in</a>
 @endauth
 </nav></header>
 <main>
 @if(session('success'))<p class="notice success" role="status">{{ session('success') }}</p>@endif
 @if(session('warning'))<p class="notice warning" role="status">{{ session('warning') }}</p>@endif
 @if(session('status'))<p class="notice success" role="status">{{ session('status') }}</p>@endif
 @yield('content')
 </main><footer>IT0049 · Technical Summative Assessment 4 · ANGELO P. BUEN</footer>
</body></html>
