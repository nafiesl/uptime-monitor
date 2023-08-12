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
                {!! FormField::radios('is_active', [
                    0 => __('app.inactive'),
                    1 => __('app.active')
                ], ['label' => __('app.status')]) !!}
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
