@extends('layouts.app')

@section('title', __('site.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $site)
        @can('delete', $site)
            <div class="card">
                <div class="card-header">{{ __('site.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('site.name') }}</label>
                    <p>{{ $site->name }}</p>
                    <label class="control-label text-primary">{{ __('site.url') }}</label>
                    <p>{{ $site->url }}</p>
                    <label class="control-label text-primary">{{ __('app.status') }}</label>
                    <p>{{ $site->is_active }}</p>
                    {!! $errors->first('site_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('site.delete_confirm') }}</div>
                <div class="card-footer">
                    {!! FormField::delete(
                        ['route' => ['sites.destroy', $site]],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['site_id' => $site->id]
                    ) !!}
                    {{ link_to_route('sites.edit', __('app.cancel'), [$site], ['class' => 'btn btn-link']) }}
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">{{ __('site.edit') }}</div>
            {{ Form::model($site, ['route' => ['sites.update', $site], 'method' => 'patch']) }}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        {!! FormField::text('name', ['required' => true, 'label' => __('site.name')]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! FormField::select('vendor_id', $availableVendors, ['label' => __('vendor.vendor')]) !!}
                    </div>
                </div>
                {!! FormField::text('url', ['label' => __('site.url')]) !!}
                <div class="row">
                    <div class="col-md-5">
                        {!! FormField::text('check_interval', [
                            'label' => __('site.check_interval'),
                            'addon' => ['before' => __('time.every'), 'after' => trans_choice('time.minutes', $site->check_interval)],
                            'type' => 'number',
                            'min' => 1,
                            'max' => 60,
                        ]) !!}
                    </div>
                    <div class="col-md-7">{!! FormField::radios('priority_code', ['high' => 'High', 'normal' => 'Normal', 'low' => 'Low'], ['label' => __('site.priority_code')]) !!}</div>
                </div>
                <div class="row">
                    <div class="col-6">
                        {!! FormField::text('warning_threshold', [
                            'label' => __('site.warning_threshold'),
                            'addon' => ['after' => __('time.miliseconds')],
                            'type' => 'number',
                            'min' => 1000,
                            'max' => 30000,
                            'step' => 1000,
                        ]) !!}
                    </div>
                    <div class="col-6">
                        {!! FormField::text('down_threshold', [
                            'label' => __('site.down_threshold'),
                            'addon' => ['after' => __('time.miliseconds')],
                            'type' => 'number',
                            'min' => 2000,
                            'max' => 60000,
                            'step' => 1000,
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        {!! FormField::text('notify_user_interval', [
                            'label' => __('site.notify_user_interval'),
                            'addon' => ['before' => __('time.every'), 'after' => trans_choice('time.minutes', $site->notify_user_interval)],
                            'type' => 'number',
                            'min' => 0,
                            'max' => 60,
                            'info' => ['text' => __('site.notify_user_interval_form_info'), 'class' => 'primary'],
                        ]) !!}
                    </div>
                    <div class="col-6">
                        {!! FormField::radios('is_active', [
                            1 => __('app.active'),
                            0 => __('app.inactive'),
                        ], ['label' => __('app.status')]) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {{ Form::submit(__('site.update'), ['class' => 'btn btn-warning']) }}
                {{ link_to_route('sites.show', __('app.cancel'), [$site], ['class' => 'btn btn-link']) }}
                @can('delete', $site)
                    {{ link_to_route('sites.edit', __('app.delete'), [$site, 'action' => 'delete'], ['class' => 'btn btn-danger float-end', 'id' => 'del-site-'.$site->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
@endsection
