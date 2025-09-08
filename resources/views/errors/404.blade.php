@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h2 class="mt-6 text-6xl font-extrabold text-gray-900">
                404
            </h2>
            <p class="mt-2 text-2xl font-medium text-gray-900">Page not found</p>
            <p class="mt-1 text-gray-500">
                Sorry, we couldn't find the page you're looking for.
            </p>
        </div>
        <div class="mt-8">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Go back home
            </a>
        </div>
    </div>
</div>
@endsection
