@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Heading --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h4 mb-0">{{ __('user.profile_edit') }}</h1>
                </div>

                <div class="card shadow-sm">
                    {{ Form::model($user, ['route' => 'profile.update', 'method' => 'patch']) }}
                    <div class="card-body">
                        {!! FormField::text('name', ['required' => true, 'label' => __('user.name')]) !!}

                        <div class="row g-3">
                            <div class="col-md-6">
                                {!! FormField::email('email', ['required' => true, 'label' => __('user.email')]) !!}
                            </div>
                            <div class="col-md-6">
                                {!! FormField::text('telegram_chat_id', ['label' => __('user.telegram_chat_id')]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        {{ Form::submit(__('user.profile_update'), ['class' => 'btn btn-warning']) }}
                        {{ link_to_route('profile.show', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
                    </div>
                    {{ Form::close() }}
                </div>

            </div>
        </div>
    </div>
@endsection
