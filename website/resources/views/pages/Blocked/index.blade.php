@extends('layout.app')
@section('title', '')
@section('pagetitle', '')
@section('content')
    <div class="row">
        <div class="container-fluid">
            @if (old('error'))
                <div class="alert alert-danger">{{ old('error') }}</div>
            @endif
            @if (old('success'))
                <div class="alert alert-success">{{ old('success') }}</div>
            @endif
            <div class="col-md-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <div class="card-header">
                            <h4 class="card-title">Market Watch</h4>
                        </div>
                        {{-- <form action="{{route('blockscript.store')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Do it</button>
                    </form> --}}
                        <form action="" method="get">
                            <div class="row mt-2">
                                <div class="col-md-6 mb-2">
                                    <label>Market</label>
                                    <select class="form-control" name="segment_id" id="segment-dropdown">
                                        <option value="">All</option>
                                        @foreach ($segment as $seg)
                                            <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Script</label>
                                    <select class="form-control" name="script_id" id="script-dropdown">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Status</label>
                                    <select name="is_banned" id="is_banned" class="form-control">
                                        <option value="0">Activated</option>
                                        <option value="1">Banned</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <label></label>
                                    <div class="d-flex flex-column flex-md-row w-100 gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary w-100 mt-4">Search</button>
                                    </div>
                                    <label></label>
                                </div>
                                <div class="col-md-4 d-grid gap-2">

                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-10" style="padding: 20px">
                                <h5>Active Clients: </h5>
                            </div>
                            <div class="col-md-2">
                                @include('partials.page_select')
                            </div>
                        </div>
                        <div class="table-responsive text-center">
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Script</th>
                                        <th>Bid</th>
                                        <th>Ask</th>
                                        <th>Ltp</th>
                                        <th>Change</th>
                                        <th>High</th>
                                        <th>Low</th>
                                        <th>Status</th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="stock-data">
                                    @php
                                        use Carbon\Carbon;
                                    @endphp
                                    @foreach ($expiry as $u)
                                            @php
                                                $response = json_decode($u->log);
                                                if (
                                                    $response &&
                                                    property_exists($response, 'PrevClose') &&
                                                    property_exists($response, 'LTP')
                                                ) {
                                                    if ($response->PrevClose != 0) {
                                                        $netChangePercent = round(
                                                            (($response->LTP - $response->PrevClose) /
                                                                $response->PrevClose) *
                                                                100,
                                                            2,
                                                        );
                                                    } else {
                                                        $netChangePercent = 0;
                                                    }
                                                    $netChange = round($response->LTP - $response->PrevClose, 2);
                                                } else {
                                                    $netChangePercent = 0;
                                                    $netChange = 0;
                                                }
                                            @endphp
                                            <tr id="{{ $u->token }}" data-script-id="{{ $u->script->id }}"
                                                data-is-banned="{{ $u->script->is_banned }}">
                                                <td style="text-align: left;">{{ $u->tradingsymbol}}
                                                </td>
                                                <td class="rate-cell" style="color: red">{{ $response->BBP }}</td>
                                                <td class="rate-cell" style="color: red">{{ $response->BSP }}</td>
                                                <td style="color: red">{{ $response->LTP }}</td>
                                                <td style="color: red">
                                                    {{ $netChange }}
                                                </td>
                                                <td>{{ $response->High }}</td>
                                                <td>{{ $response->Low }}</td>

                                                <td>
                                                    @if ($u->script->is_banned == '0')
                                                        Activated
                                                    @else
                                                        Banned
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ url('toggleBan', $u->script->id) }}" method="post">
                                                        @csrf
                                                        @if ($u->script->is_banned == '0')
                                                            <button data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-custom-class="custom-tooltip" data-bs-title="Bann"
                                                                class="btn btn-danger btn-sm">Add To Ban</button>
                                                        @else
                                                            <button data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-custom-class="custom-tooltip"
                                                                data-bs-title="Activate"
                                                                class="btn btn-success btn-sm">Remove Ban</button>
                                                        @endif
                                                    </form>
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $expiry->appends(\Request::all())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let tokens = [];
        let exchangerGroups = {}; 
        let exchanger;
        let token = JSON.parse(@json($token));
        let list = [];
    
        @foreach ($expiry as $item)
            exchanger = "{{ $item->script->segment->exchange }}";
            tokens = "{{ $item->token }}";

            if (!exchangerGroups[exchanger]) {
                exchangerGroups[exchanger] = [];
            }

            exchangerGroups[exchanger].push(tokens);
            console.log(exchangerGroups);
        @endforeach

        for (let exchanger in exchangerGroups) {
            list.push({
                exchanger: exchanger,
                script: exchangerGroups[exchanger]
            });
        }
        const socket = io("wss://socket.whitegoldtrades.com", {
            query: {
                data: JSON.stringify(token),
                list: JSON.stringify(list)
            }
        });

        // socket.on("connect", (io) => {
        //     console.log(io)
        // });

        socket.on('oldData', (res) => {
            console.log(res, "old")
        });

        socket.on('response', (res) => {
            let data = JSON.parse(res);
            print(data);
        });

        let lastUpdatedData = {
            Symbol: '',
            ExpiryString: '',
            BBP: '',
            BSP: '',
            LTP: '',
            High: '',
            Low: '',
            Open: '',
            netChange: 0,
            PrevClose: ''
        };

        let lastValues = {};
        let buyColor = "#BF2114";
        let sellColor = "#BF2114";
        let ltpColor = "#BF2114";
        function print(res) {
            console.log("response = ", res);
            for (const item of res.fetched) {
                if (!lastValues[item.symbolToken]) {
                    lastValues[item.symbolToken] = {
                        buy: item.depth.buy[0].price,
                        sell: item.depth.sell[0].price,
                        ltp: item.ltp,
                    };
                }
                let scriptId = $("#" + item.symbolToken).data('script-id');
                let isBanned = $("#" + item.symbolToken).data('is-banned');
                console.log("is banned = ", isBanned, scriptId);

                const currentBuyPrice = item.depth.buy[0].price;
                const currentSellPrice = item.depth.sell[0].price;
                const currentLTP = item.ltp;

                if (currentBuyPrice < lastValues[item.symbolToken].buy) {
                    buyColor = '#BF2114'; 
                } else if (currentBuyPrice > lastValues[item.symbolToken].buy) {
                    buyColor = '#0C51C4'; 
                }

                if (currentSellPrice < lastValues[item.symbolToken].sell) {
                    sellColor = '#BF2114'; 
                } else if (currentSellPrice > lastValues[item.symbolToken].sell) {
                    sellColor = '#0C51C4'; 
                }

                if (currentLTP < lastValues[item.symbolToken].ltp) {
                    ltpColor = '#BF2114';
                } else if (currentLTP > lastValues[item.symbolToken].ltp) {
                    ltpColor = '#0C51C4'; 
                }

                $("#" + item.symbolToken + " td").eq(1).html(currentBuyPrice);
                $("#" + item.symbolToken + " td").eq(2).html(currentSellPrice);
                $("#" + item.symbolToken + " td").eq(3).html(currentLTP);

                $("#" + item.symbolToken + " td").eq(4).html(item.netChange);

                $("#" + item.symbolToken + " td").eq(5).html(item.high);
                $("#" + item.symbolToken + " td").eq(6).html(item.low);

                // let netChange = item.ltp - item.close;
                // let changePercent = (netChange / item.close) * 100;
                

                // let html = `<td style="text-align: left;">${item.tradingSymbol}</td>
                //     <td class="rate-cell bbp-rate-cell" data-value='${JSON.stringify(item)}' style="color:${buyColor}" data-rate="${item.depth.buy[0].price}">${item.depth.buy[0].price}</td>
                //     <td class="rate-cell bsp-rate-cell" data-value='${JSON.stringify(item)}' style="color:${sellColor}" data-rate="${item.depth.sell[0].price}">${item.depth.sell[0].price}</td>
                //     <td style="color:${ltpColor}">${item.ltp}</td>
                //     <td>${item.netChange < 0 ? `<i style="color:red;" class="bi bi-caret-down-fill"></i>` : `<i style="color:#00ff2d;" class="bi bi-caret-up-fill"></i>`} ${netChange.toFixed(2)}</td>
                //     <td>${item.high}</td>
                //     <td>${item.low}</td>
                //     <td>${isBanned == "0" ? 'Activated' : 'Banned'}</td>
                //     <td>
                //         <form action="{{ url('toggleBan') }}/${scriptId}" method="post">
                //             @csrf
                //             ${isBanned == "0" ?
                //                 '<button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Bann" class="btn btn-danger btn-sm">Add To Ban</button>' :
                //                 '<button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Activate" class="btn btn-success btn-sm">Remove Ban</button>'}
                //         </form>
                //     </td>`;

                // $("#" + item.symbolToken).replaceWith("<tr id='" + item.symbolToken + "'>" + html + "</tr>");

                lastValues[item.symbolToken] = {
                    buy: currentBuyPrice,
                    sell: currentSellPrice,
                    ltp: currentLTP
                };
            }
        }
        // function print(res) {
        //     console.log("response = ", res);
        //     let html = '';
        //     let netChange = res.LTP - res.PrevClose;
        //     res.netChange = netChange;
        //     console.log(res);

        //     let scriptId = $("#" + res.UniqueName).data('script-id');
        //     let isBanned = $("#" + res.UniqueName).data('is-banned');

        //     html = `<td style="text-align: left;">${res.Symbol} ${res.ExpiryString}</td>
        //     <td style="color:${res.BBP < lastUpdatedData.BBP ? 'red' : 'green'}">${res.BBP}</td>
        //     <td style="color:${res.BSP < lastUpdatedData.BSP ? 'red' : 'green'}">${res.BSP}</td>
        //     <td style="color:${res.LTP < lastUpdatedData.LTP ? 'red' : 'green'}">${res.LTP}</td>
        //     <td style="color:${res.netChange < lastUpdatedData.netChange ? 'red' : 'green'}">${netChange.toFixed(2)}</td>
        //     <td>${res.High}</td>
        //     <td>${res.Low}</td>
        //     <td>${isBanned == 0 ? 'Activated' : 'Banned'}</td>
        //     <td>
        //         <form action="{{ url('toggleBan') }}/${scriptId}" method="post">
        //             @csrf
        //             ${isBanned == 0 ?
        //                 '<button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Bann" class="btn btn-danger btn-sm">Add To Ban</button>' :
        //                 '<button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Activate" class="btn btn-success btn-sm">Remove Ban</button>'}
        //         </form>
        //     </td>`;
        //     $("#" + res.UniqueName).replaceWith("<tr id='" + res.UniqueName + "'>" + html + "</tr>");
        //     lastUpdatedData = res;
        //     html = '';
        // }

        $(document).ready(function() {
            $('#segment-dropdown').on('change', function() {
                changescript(this.value);
            });
        });

        function changescript(value) {
            console.log("script =", value);
            var idSegment = value;
            $("#script-dropdown").html('');
            $.ajax({
                url: "{{ url('/fetch_script') }}",
                type: "POST",
                data: {
                    segment_id: idSegment,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    $('#script-dropdown').html('<option value="">Select Script</option>');
                    $.each(res.script, function(key, value) {
                        $("#script-dropdown").append('<option value="' + value.id + '" ' + ($(
                                "#script-dropdown").attr('data-value') == value.id ?
                            "selected" : "") + '>' + value.name + '</option>');
                    });
                    console.log("fetch script = ", res);
                }
            });
        }
    </script>
@endsection
