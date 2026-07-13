@extends('layouts.app')

@section('title', __('messages.welcome'))

@section('content')
<div class="text-center py-20">
    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ __('messages.welcome') }}</h1>
    <p class="text-lg text-gray-500">{{ __('messages.coming_soon') }}</p>
</div>
@endsection
