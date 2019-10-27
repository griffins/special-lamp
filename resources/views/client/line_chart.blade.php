<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">#{{$account->account}} - {{ $account->name }} ({{ $account->currency }}
                - {{currency( normalize( $account->balance),true,0)}}
                )</h3>
        </div>
        <div class="card-body">
            <div id="chart-{{$account->account}}"></div>
        </div>
        <script>
            (function () {
                var data = JSON.parse(atob("{{ base64_encode(json_encode( $account->chartOf(request('type','daily'))))}}"));
                c3.generate({
                    bindto: '#chart-' + data.id, // id of chart wrapper
                    data: {
                        columns: [
                            data.series,
                        ],
                        type: 'line', // default type of chart
                        colors: {
                            'data': randomProperty(colors,{{ $account->id }}),
                        },
                        names: {
                            // name of each series
                            'data': 'Profit',
                        }
                    },
                    axis: {
                        x: {
                            type: 'category',
                            categories: data.labels
                        },
                    },
                    legend: {
                        show: true, //hide legend
                    },
                    padding: {
                        bottom: 0,
                        top: 0
                    },
                });
            })()
        </script>
    </div>
</div>