@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Heading --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h4 mb-0">{{ __('user.profile') . ' - ' . $user->name }}</h1>
                </div>

                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <th class="w-25 text-muted">{{ __('user.name') }}</th>
                                    <td class="w-75">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('user.email') }}</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="align-middle text-muted">{{ __('user.telegram_chat_id') }}</th>
                                    <td class="align-middle">
                                        {{ $user->telegram_chat_id }}
                                        @if (config('services.telegram_notifier.token') && $user->telegram_chat_id)
                                            @livewire('telegram-test-button')
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        {{ link_to_route('profile.edit', __('user.profile_edit'), [], ['class' => 'btn btn-warning']) }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
