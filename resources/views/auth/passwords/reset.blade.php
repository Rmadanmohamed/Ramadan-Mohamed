@extends('layouts.app')
@section('content')
<div class="container">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label for="email">{{ __('Email Address') }}</label>
        <input id="email" class="form-control" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">
        <label for="password">{{ __('Password') }}</label>
        <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
        <label for="password-confirm">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
        @foreach ($errors->all() as $error)
            <div class="text-danger">{{ $error }}</div>
        @endforeach
        <button class="btn btn-primary mt-3" type="submit">{{ __('Reset Password') }}</button>
    </form>
</div>
@endsection
