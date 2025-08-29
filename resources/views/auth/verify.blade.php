@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <p class="mb-2">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                        </p>
                        <p class="mb-0">
                            {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 align-baseline">
                                {{ __('click here to request another') }}
                            </button>
                        </form>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
