@extends('layouts.app')

@section('title', __('customer_site.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $customerSite)
        @can('delete', $customerSite)
            <div class="card">
                <div class="card-header">{{ __('customer_site.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('customer_site.name') }}</label>
                    <p>{{ $customerSite->name }}</p>
                    <label class="control-label text-primary">{{ __('customer_site.url') }}</label>
                    <p>{{ $customerSite->url }}</p>
                    <label class="control-label text-primary">{{ __('app.status') }}</label>
                    <p>{{ $customerSite->is_active }}</p>
                    {!! $errors->first('customer_site_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('customer_site.delete_confirm') }}</div>
                <div class="card-footer">
                    {!! FormField::delete(
                        ['route' => ['customer_sites.destroy', $customerSite]],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['customer_site_id' => $customerSite->id]
                    ) !!}
                    {{ link_to_route('customer_sites.edit', __('app.cancel'), [$customerSite], ['class' => 'btn btn-link']) }}
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">{{ __('customer_site.edit') }}</div>
            {{ Form::model($customerSite, ['route' => ['customer_sites.update', $customerSite], 'method' => 'patch']) }}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('customer_site.name')]) !!}
                {!! FormField::text('url', ['label' => __('customer_site.url')]) !!}
                <div class="row">
                    <div class="col-4">
                        {!! FormField::text('check_periode', [
                            'label' => __('customer_site.check_periode'),
                            'addon' => ['before' => __('time.every'), 'after' => __('time.minutes')]
                        ]) !!}
                    </div>
                    <div class="col-8">{!! FormField::radios('priority_code', ['high' => 'High', 'normal' => 'Normal', 'low' => 'Low'], ['label' => __('customer_site.priority_code')]) !!}</div>
                </div>
                <div class="row">
                    <div class="col-6">{!! FormField::text('warning_threshold', ['label' => __('customer_site.warning_threshold'), 'addon' => ['after' => __('time.miliseconds')]]) !!}</div>
                    <div class="col-6">{!! FormField::text('down_threshold', ['label' => __('customer_site.down_threshold'), 'addon' => ['after' => __('time.miliseconds')]]) !!}</div>
                </div>
                <div class="row">
                    <div class="col-6">
                        {!! FormField::radios('notify_user', [
                            1 => __('app.active'),
                            0 => __('app.inactive'),
                        ], ['label' => __('customer_site.notify_user')]) !!}
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
                {{ Form::submit(__('customer_site.update'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('customer_sites.show', __('app.cancel'), [$customerSite], ['class' => 'btn btn-link']) }}
                @can('delete', $customerSite)
                    {{ link_to_route('customer_sites.edit', __('app.delete'), [$customerSite, 'action' => 'delete'], ['class' => 'btn btn-danger float-right', 'id' => 'del-customer_site-'.$customerSite->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
@endsection
