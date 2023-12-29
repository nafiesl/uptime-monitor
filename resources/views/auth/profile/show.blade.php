@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="page-header">
            <h1 class="page-title">{{  __('user.profile').' - '.$user->name }}</h1>
        </div>
        <div class="card">
            <table class="table table-sm card-table mb-0">
                <tbody>
                    <tr><td class="col-md-3">{{ __('user.name') }}</td><td class="col-md-9">{{ $user->name }}</td></tr>
                    <tr><td>{{ __('user.email') }}</td><td>{{ $user->email }}</td></tr>
                    <tr>
                        <td class="align-middle">{{ __('user.telegram_chat_id') }}</td>
                        <td class="align-middle">
                            {{ $user->telegram_chat_id }}
                            @if (config('services.telegram_notifier.token') && $user->telegram_chat_id)
                                @livewire('telegram-test-button')
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="card-footer">
                {{ link_to_route('profile.edit', __('user.profile_edit'), [], ['class' => 'btn btn-warning']) }}
            </div>
        </div>
    </div>
</div>
@endsection
