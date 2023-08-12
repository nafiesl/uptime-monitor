@extends('layouts.app')

@section('title', __('customer_site.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('customer_site.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('customer_site.name') }}</td><td>{{ $customerSite->name }}</td></tr>
                        <tr><td>{{ __('customer_site.url') }}</td><td>{{ $customerSite->url }}</td></tr>
                        <tr><td>{{ __('app.status') }}</td><td>{{ $customerSite->is_active }}</td></tr>
                        <tr><td>{{ __('app.created_at') }}</td><td>{{ $customerSite->created_at }}</td></tr>
                        <tr><td>{{ __('app.updated_at') }}</td><td>{{ $customerSite->updated_at }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $customerSite)
                    {{ link_to_route('customer_sites.edit', __('customer_site.edit'), [$customerSite], ['class' => 'btn btn-warning', 'id' => 'edit-customer_site-'.$customerSite->id]) }}
                @endcan
                {{ link_to_route('customer_sites.index', __('customer_site.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="chart">
            <div class="toolbar">
                <button id="one_month">1M</button>
                <button id="six_months">6M</button>
                <button id="one_year"class="active">1Y</button>
                <button id="ytd">YTD</button>
                <button id="all">ALL</button>
            </div>

            <div id="chart-timeline"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')

<style>
    .toolbar {
        margin-left: 45px;
    }

    button {
        background: #fff;
        color: #222;
        border: 1px solid #e7e7e7;
        border-bottom: 2px solid #ddd;
        border-radius: 2px;
        padding: 4px 17px;
    }

    button.active {
        color: #fff;
        background: #008FFB;
        border: 1px solid blue;
        border-bottom: 2px solid blue;
    }

    button:focus {
        outline: 0;
    }

</style>
@endpush()
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        series: [{
            data: {!! json_encode($chartData) !!}
        }],
        chart: {
            id: 'line-datetime',
            type: 'line',
            height: 400,
            zoom: {
                autoScaleYaxis: true
            }
        },
        annotations: {
            yaxis: [{
                y: 5000,
                borderColor: 'orange',
                label: {
                    show: true,
                    text: 'Trashold',
                    style: {
                        color: "#fff",
                        background: 'orange'
                    }
                }
            }]
        },
        dataLabels: {
            enabled: false
        },
        markers: {
            size: 0,
            style: 'hollow',
        },
        xaxis: {
            type: 'datetime',
            min: new Date("{{ Carbon::yesterday() }}").getTime(),
            max: new Date("{{ Carbon::tomorrow() }}").getTime(),
            labels: {
                datetimeUTC: false,
            },
            title: {
                text: 'Datetime',
            },
        },
        yaxis: {
            tickAmount: 10,
            title: {
                text: 'Miliseconds',
            },
        },
        stroke: {
          width: [2]
        },
        tooltip: {
            x: {
                format: 'dd MMM yyyy'
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart-timeline"), options);
    chart.render();


    var resetCssClasses = function(activeEl) {
        var els = document.querySelectorAll('button')
        Array.prototype.forEach.call(els, function(el) {
            el.classList.remove('active')
        })

        activeEl.target.classList.add('active')
    }
</script>
@endpush
