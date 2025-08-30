@extends('layouts.app')

@section('title', __('site.edit'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">

                @if (request('action') == 'delete' && $site)
                    @can('delete', $site)
                        <div class="card shadow-sm">
                            <div class="card-header">{{ __('site.delete') }}</div>

                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-primary">{{ __('site.name') }}</label>
                                    <p class="mb-0">{{ $site->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-primary">{{ __('site.url') }}</label>
                                    <p class="mb-0">{{ $site->url }}</p>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-primary">{{ __('app.status') }}</label>
                                    <p class="mb-0">{{ $site->is_active ? 'Active' : 'Inactive' }}</p>
                                </div>

                                @if ($errors->has('site_id'))
                                    <div class="small text-danger mt-2">{{ $errors->first('site_id') }}</div>
                                @endif
                            </div>

                            <div class="card-body text-danger border-top">
                                {{ __('site.delete_confirm') }}
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                                {!! FormField::delete(
                                    ['route' => ['sites.destroy', $site]],
                                    __('app.delete_confirm_button'),
                                    ['class' => 'btn btn-danger'],
                                    ['site_id' => $site->id],
                                ) !!}
                                {{ link_to_route('sites.edit', __('app.cancel'), [$site], ['class' => 'btn btn-link']) }}
                            </div>
                        </div>
                    @endcan
                @else
                    <div class="card shadow-sm">
                        <div class="card-header">{{ __('site.edit') }}</div>

                        {{ Form::model($site, ['route' => ['sites.update', $site], 'method' => 'patch']) }}
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    {!! FormField::text('name', ['required' => true, 'label' => __('site.name')]) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! FormField::select('vendor_id', $availableVendors, [
                                        'label' => __('vendor.vendor'),
                                        'class' => 'form-select', // BS5 select
                                    ]) !!}
                                </div>

                                <div class="col-12">
                                    {!! FormField::text('url', ['label' => __('site.url')]) !!}
                                </div>

                                <div class="col-md-5">
                                    {!! FormField::text('check_interval', [
                                        'label' => __('site.check_interval'),
                                        'addon' => ['before' => __('time.every'), 'after' => trans_choice('time.minutes', $site->check_interval)],
                                        'type' => 'number',
                                        'min' => 1,
                                        'max' => 60,
                                    ]) !!}
                                </div>
                                <div class="col-md-7">
                                    {!! FormField::radios(
                                        'priority_code',
                                        ['high' => 'High', 'normal' => 'Normal', 'low' => 'Low'],
                                        ['label' => __('site.priority_code')],
                                    ) !!}
                                </div>

                                <div class="col-md-6">
                                    {!! FormField::text('warning_threshold', [
                                        'label' => __('site.warning_threshold'),
                                        'addon' => ['after' => __('time.miliseconds')],
                                        'type' => 'number',
                                        'min' => 1000,
                                        'max' => 30000,
                                        'step' => 1000,
                                    ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! FormField::text('down_threshold', [
                                        'label' => __('site.down_threshold'),
                                        'addon' => ['after' => __('time.miliseconds')],
                                        'type' => 'number',
                                        'min' => 2000,
                                        'max' => 60000,
                                        'step' => 1000,
                                    ]) !!}
                                </div>

                                <div class="col-md-6">
                                    {!! FormField::text('notify_user_interval', [
                                        'label' => __('site.notify_user_interval'),
                                        'addon' => ['before' => __('time.every'), 'after' => trans_choice('time.minutes', $site->notify_user_interval)],
                                        'type' => 'number',
                                        'min' => 0,
                                        'max' => 60,
                                        'info' => ['text' => __('site.notify_user_interval_form_info'), 'class' => 'primary'],
                                    ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! FormField::radios(
                                        'is_active',
                                        [1 => __('app.active'), 0 => __('app.inactive')],
                                        ['label' => __('app.status')],
                                    ) !!}
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex align-items-center gap-2">
                            {{ Form::submit(__('site.update'), ['class' => 'btn btn-warning']) }}
                            {{ link_to_route('sites.show', __('app.cancel'), [$site], ['class' => 'btn btn-link']) }}
                            @can('delete', $site)
                                {{ link_to_route('sites.edit', __('app.delete'), [$site, 'action' => 'delete'], ['class' => 'btn btn-danger ms-auto', 'id' => 'del-site-' . $site->id]) }}
                            @endcan
                        </div>
                        {{ Form::close() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
