@extends('layout.app')
@section('title', '')
@section('content')

    <style>
        .rate-cell {
            cursor: pointer;
        }
        .watch{
            padding: 0px 3px 0px 3px;
            background-color: #131617;
        }
        .add{
            line-height: 1;
            width: 100%;
            height: 38px;
            font-size: 14px;
        }
        .card-head.watch{
            padding: 0rem;
            background-color: #696969;
            font-weight: 500;
            color: #fff;
            margin-bottom: 0;
            border-bottom: 1px solid hsla(0, 0%, 100%, .12);
            font-size: 14px;
        }
        .card.watch{
            margin: 15px;
        }
    </style>

    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('watchlist.store') }}">
            @csrf
            <div class="row">
                <div class="col-3 col-lg-3 col-xl-3 watch">
                    {{-- <h6>SEGMENT</h6> --}}
                    <div class="form-group row" style="margin-bottom: 5px">
                        <div class="col-sm-12">
                            <div class="col-sm-12" style="padding: 0px">
                                <select class="form-control" name="segment_id" id="segment-dropdown">
                                    <option value="">Select segment</option>
                                    @foreach ($segment as $seg)
                                        <option value="{{ $seg->id }}">{{ $seg->name }} {{ $seg->instrument_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-lg-4 col-xl-4 watch">
                    {{-- <h6>SCRIPT</h6> --}}
                    <div class="form-group row" style="margin-bottom: 5px">
                        <div class="col-sm-12">
                            <div class="col-sm-12" style="padding: 0px">
                                <select class="form-control" name="script_id" id="script-dropdown">
                                    <option value="">Select script</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 col-lg-3 col-xl-3 watch">
                    {{-- <h6>EXPIRY</h6> --}}
                    <div class="form-group row" style="margin-bottom: 5px">

                    <div class="col-sm-12">
                        <div class="col-sm-12" style="padding: 0px">

                        <select class="form-control" name="expiry_date" id="expiry-dropdown">
                            <option value="">Select Expiry Date</option>
                        </select>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-2 col-lg-2 col-xl-2 watch">
                    <div class="form-group row" style="margin-bottom: 5px">

                    <div class="col-sm-12">
                        <div class="col-sm-12" style="padding: 0px">

                        <button type="submit" class="btn btn-success add"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3 col-lg-3 col-xl-3 watch">
                    {{-- <h6>CE/PE</h6> --}}
                    <div class="form-group row" style="margin-bottom: 5px">

                    <div class="col-sm-12">
                        <div class="cil-sm-12" style="padding: 0px">
                            <select class="form-control" name="instrument_type" id="instr-dropdown" disabled>
                                <option>Chose CE/PE</option>
                                <option value="CE">CALL</option>
                                <option value="PE">PUT</option>
                            </select>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-3 col-lg-3 col-xl-3 watch">
                    {{-- <h6>STRIKE</h6> --}}
                    <div class="form-group row" style="margin-bottom: 5px">

                    <div class="col-sm-12">
                        <div class="cil-sm-12" style="padding: 0px">
                            <select class="form-control" name="strike" id="strike-dropdown" disabled>
                                <option> Select Strike</option>
                            </select>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-4 col-lg-4 col-xl-4 watch">
                    <div class="form-group row" style="margin-bottom: 5px">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <form action="{{ route('watchlist.index') }}" method="get">
                                        <span class="input-group-text" id="basic-addon2"><i class="bi bi-search"></i></span>
                                        <input type="search" class="form-control" placeholder="Search..."
                                            value="{{ $request->search }}" name="search" aria-describedby="basic-addon2">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-2 col-lg-2 col-xl-2 watch">
                    <div class="form-group row" style="margin-bottom: 5px">

                    <div class="col-sm-12">
                        <div class="col-sm-12" style="padding: 0px">

                        <button type="submit" class="btn btn-info add"><i class="bi bi-pen"></i></button>
                        </div>
                    </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-12 col-lg-12 col-xl-12 watch">
            @foreach ($watchlist as $item)
                <div class="card watch">
                    <div class="card-head watch">
                        <div class="row col-12">
                            <div class="col-7 text-left" style="padding-left:5px;padding-right:0px;">
                                <b>{{ $item->first()->segment->name }}SYM</b>
                            </div>
                            <div class="col-5 text-right" style="padding-left:0px;padding-right:0px;">
                                {{-- <i ng-click="collapse(m.market_type_id);" class="fa fa-caret-down ng-scope"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($item as $w)
                    @if ($w->logs)
                        @php
                            $response = json_decode($w->logs->response);
                            if ($response->PrevClose != 0) {
                                $netChangePercent = round(
                                    (($response->LTP - $response->PrevClose) / $response->PrevClose) * 100,
                                    2,
                                );
                            } else {
                                $netChangePercent = 0;
                            }
                            $netChange = round($response->LTP - $response->PrevClose, 2);
                        @endphp
                        <div id="{{ $w ? $w->logs->token : '' }}" style="line-height: 1.5 !important; margin:17px; border-bottom: 1px solid #323537;">
                            <div class="card-head" style="padding:0rem;">
                                <div class="row" style=" padding-left: 12.5px;padding-right: 12.5px;">
                                    <div class="col-5 text-left" style="padding-left:0px;padding-right:0px;font-weight: 300;font-size: 12px;background-color: #061725a8;">
                                        <span>
                                            @if ($netChange < 0)
                                                <i style="color: red;" class="bi bi-caret-down-fill"></i>
                                            @else
                                                <i style="color: #00ff2d;" class="bi bi-caret-up-fill"></i>
                                            @endif
                                            {{ $netChange }} {{ $netChangePercent }}%
                                        </span>
                                    </div>
                                    <div class="col-2 text-center" style="padding-left:0px;padding-right:0px;font-weight: 300;font-size: 12px;background-color: #061725a8; line-height: 2;">
                                        <span>Q : 0</span>
                                    </div>
                                    <div class="col-5 text-right" style="padding-left:0px;padding-right:0px;    line-height: 2;font-weight: 300;font-size: 12px;background-color: #061725a8;">
                                        <b >LTP : {{ $response->LTP }}</b>
                                    </div>
                                </div>
                                <div class="row" style=" ">
                                    <div class="col-5 text-left" style="padding-right:0px;padding-right:0px;font-weight: 300;font-size: 12px;    font-weight: 500;font-size: 13px;">
                                        <span>{{ $response->Symbol }}L</span><br>
                                        <span>{{ $response->ExpiryString }}</span><br>
                                    </div>
                                    <div class="col-7 text-right" style="padding-left:0px;padding-right:17px;font-weight: 500;font-size: 14px;    font-weight: 500;font-size: 13px;">
                                        <button style="padding: 0.275rem .5rem;padding: 0px;font-size: 15px; background-color : #30ff5e; color:black;   " type="button" class="col-6 btn btn-info red rate-cell bbp-rate-cell" data-value="{{ json_encode($response) }}" >{{ $response->BBP }}<br><span style="font-size : 9px; font-weight : 400;">H : {{ $response->High }}</span></button>
                                        <button style="padding: 0.275rem .5rem;padding: 0px;font-size: 15px; background-color : #30ff5e;  color:black;  " type="button" class="col-6 btn btn-info red rate-cell bsp-rate-cell" data-value="{{ json_encode($response) }}" >{{ $response->BSP }}<br><span style="font-size : 9px; font-weight : 400;">L : {{ $response->Low }}</span></button>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('portfolio.store') }}">
                                @csrf
                                <div id="rateCard" ng-show="tradeShow" style="background-color: #000000; border: white 1px solid;" class="row ng-scope">
                                    <input type="hidden" id="exchangeCode" name="exchange_code" value="">
                                    <input type="hidden" id="product" name="product" value="">
                                    <input type="hidden" id="validity" name="validity" value="day">
                                    <input type="hidden" id="token" name="token" value="">
                                    <input type="hidden" id="disclosed_quantity" name="disclosed_quantity" value="0">
                                    <input type="hidden" id="validity_date" name="validity_date" value="">
                                    <input type="hidden" id="right" name="right" value="">
                                    <input type="hidden" id="stoploss" name="stoploss" value="">
                                    
                                    <br>
                                    <div style="font-size: 10px;line-height: 2;" class="col-12">
                                        <span style="padding: 0px;text-align: center;" class="col-3 ng-binding">O : {{ $response->Open }}</span>
                                        <span style="padding: 0px;text-align: center;" class="col-3 ng-binding">C : {{ $response->PrevClose }}</span>
                                        <span style="padding: 0px;text-align: center;" class="col-3 ng-binding">M : 10,800</span>
                                        <span style="padding: 0px;text-align: center;" class="col-3 ng-binding">P : {{ $response->PrevClose }}</span>
                                        <span class="col-12 ng-binding" style="text-align: center;font-size: 14px;">{{ $response->Symbol }} {{ $response->ExpiryString }}</span>
                                    </div>
                                    <br>
                                    <div class="col-4 text-center" style="padding-right:0px;padding-right:0px;font-weight: 300;font-size: 12px;    font-weight: 500;font-size: 13px;">									
                                        <label style="padding-top : 0px; padding-bottom : 0px;font-size: 10px;" _ngcontent-dws-c145="" class="col-form-label">Lot</label>
                                        <br>									
                                        <input style="height : 30px; font-size : 14px;"  ng-model="selectTradeLotSize" type="number" pattern="" id="lot" class="form-control ng-pristine ng-untouched ng-valid ng-valid-pattern" name="lot" value="1" step="0.0000000000">
                                        <input type="hidden" name="lot_initial" id="lot_initial">						
                                    </div>								
                                    <div class="col-4 text-center" style="padding-left:3px;padding-right:3px;font-weight: 300;font-size: 12px;    font-weight: 500;font-size: 13px;">									
                                        <label style="padding-top : 0px; padding-bottom : 0px;font-size: 10px;" _ngcontent-dws-c145="" class="col-form-label">Qty</label>
                                        <br>									
                                        <input style="height : 30px; font-size : 14px;" ng-change="qtyChange()" ng-model="selectTradeQtySize" pattern="" ng-disabled="selectMarketTrade == 1 || selectMarketTrade == 5" type="number" id="quantity" class="form-control ng-pristine ng-untouched ng-valid ng-valid-pattern" name="quantity">	
                                        <input type="hidden" name="initial" id="initial">							
                                    </div>								
                                    <div class="col-4 text-center ng-binding" style="padding-left:0px;padding-right:17px;font-weight: 300;font-size: 12px;    font-weight: 500;font-size: 13px;">									<!-- ngIf: $root.userInfo.userType != 1 -->
                                        <br>							
                                    </div><div class="col-4 text-center ng-binding" style="padding-right:0px;padding-right:0px;font-weight: 300;font-size: 12px;    font-weight: 500;font-size: 13px;">									
                                        <label style="padding-top : 0px; padding-bottom : 0px;font-size: 10px;" _ngcontent-dws-c145="" class="col-form-label">Type</label>
                                        <br>									
                                        <select style="height : 30px; font-size : 14px;" ng-model="selectTradeType" ng-disabled="selectMarketTrade == 5" _ngcontent-lmp-c162="" id="order_type" class="form-control user_type ng-pristine ng-untouched ng-valid" name="order_type">										
                                            <option value="MARKET">Market</option>										
                                            <option value="SL">Stop Loss</option>									
                                        </select>								
                                    </div>							
                                    <div ng-if="buyRadio == 1 &amp;&amp; selectTradeType == 0" class="col-4 text-center ng-binding ng-scope" style="padding-left:3px;padding-right:3px;font-weight: 300;font-size: 12px;    font-weight: 500;font-size: 13px;">									
                                        <label style="padding-top : 0px; padding-bottom : 0px;font-size : 10px;" _ngcontent-dws-c145="" class="col-form-label">Price</label>
                                        <br>									
                                        <input style="height : 30px; font-size : 14px;" ng-model="selectTradePrice" ng-value="selectTradePrice = dataTrade.BuyPrice" type="number" ng-disabled="selectTradeType == 0" id="orderPrice" class="form-control ng-pristine ng-untouched ng-valid" value="222.75" disabled="disabled" name="price">								
                                    </div>
                                    <div ng-if="selectTradeType == 0" class="col-4 text-center ng-binding ng-scope" style="padding-left:0px;padding-right:17px;padding-bottom:4px;font-weight: 300;font-size: 12px;    font-weight: 500;font-size: 13px;">								
                                        <br>
                                        <button style="    margin: 0px;/* height: 30px; */line-height: 1;" ng-if="selectTradeType == 0" ng-disabled="disableActions" ng-click="placeTrade()" _ngcontent-nhg-c161="" type="button" class="btn btn-success sell" fdprocessedid="l2sreq">									
                                            <i _ngcontent-nhg-c161="" class="fa fa-trade"></i>									
                                            <span _ngcontent-nhg-c161="" class="ng-binding">										 sell									</span>								
                                        </button>						
                                    </div>									
                                </div>
                            </form>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

@endsection

@section('scripts')
    <script>
       
        let tokens = [];
        let exchanger;
        let token = JSON.parse(@json($token));
        let list = [];
        list.push({
            exchanger: "BSE",
            script: ["99919000"]
        });

        list.push({
            exchanger: "NSE",
            script: ["26000"]
        });
        @foreach ($watchlist as $w)
        @foreach ($w as $item)
                exchanger = "{{ $item->segment->exchange }}"
                tokens.push("{{ $item->expiry->token }}");
            @endforeach
            list.push({
                exchanger: exchanger,
                script: tokens
            });
        @endforeach
        console.log("data we are sending = ", list, token);
        const socket = io("wss://socket.whitegoldtrades.com", {
            query: {
                data: JSON.stringify(token),
                list: JSON.stringify(list)
            }
        });

        $("#lot").on("change", function() {
            quantity = $("#initial").val();
            lot = $("#lot").val();
            total = quantity * lot;
            $("#quantity").val(total);
        })

        $("#quantity").on("change", function() {
            const newQuantity = $(this).val();
            const lot = $("#lot").val();
            quantity = $("#initial").val();
            console.log("log = ", lot);
            if (lot > 0) {
                const newLot = newQuantity/quantity;
                console.log("new lot = ", newLot);
                $("#lot").val(newLot);
            }
        });

        let lastClickedRowUniqueName = "";
        let tradeType ="";
        $(document).on("click", ".rate-cell", function(event) {
            clickedRowId = JSON.parse($(this).attr('data-value'));
            console.log("click,",clickedRowId)
            lastClickedRowUniqueName = clickedRowId.symbolToken;
            $.ajax({
                url: '/fetch-lot-size',
                type: 'GET',
                data: {
                    symbolToken: clickedRowId.symbolToken,

                },
                success: (response) => {
                    console.log("clicked row id =", response.symbolToken);
                    const lot_size = response.lot_size;
                    let tradeTypeBackgroundColor;
                    if (event.target.classList.contains("rate-cell") && event.target.classList.contains("bbp-rate-cell")) {
                        tradeType = 'SELL';
                        tradeTypeBackgroundColor = 'red';
                        console.log("sell fetch");
                    } else if (event.target.classList.contains("rate-cell") && event.target.classList.contains("bsp-rate-cell")){
                        tradeType = 'BUY';
                        tradeTypeBackgroundColor = 'blue';
                        console.log("buy fetch");
                    }
                    updateCardWithRowData(clickedRowId, tradeType, lot_size, response.symbolToken);

                    lastClickedRowData = {
                        row: clickedRowId,
                        backgroundColor: tradeTypeBackgroundColor
                    };

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

       

        let lastValues = {};
        let buyColor = "#30ff5e";
        let sellColor = "#BF2114";
        let bColor = 'black';
        let sColor = 'black';

        function print(res) {
            for (const item of res.fetched) {
                const symbolToken = item.symbolToken;
                
                const netChange = (item.ltp - item.close).toFixed(2);
                const changePercent = ((netChange / item.close) * 100).toFixed(2);

                let netChangeColor = netChange < 0 ? "#BF2114" : "#30ff5e";

                if (!lastValues[symbolToken]) {
                    lastValues[symbolToken] = {
                        buy: item.depth.buy[0].price,
                        sell: item.depth.sell[0].price,
                        ltp: item.ltp,
                    };
                }

                const currentBuyPrice = item.depth.buy[0].price;
                const currentSellPrice = item.depth.sell[0].price;
                const currentLTP = item.ltp;

                if (currentBuyPrice < lastValues[item.symbolToken].buy) {
                    buyColor = '#BF2114'; 
                    bColor = 'white';
                } else if (currentBuyPrice > lastValues[item.symbolToken].buy) {
                    buyColor = '#30ff5e'; 
                    bColor = 'black';
                }

                if (currentSellPrice < lastValues[item.symbolToken].sell) {
                    sellColor = '#BF2114'; 
                    sColor = 'white';
                } else if (currentSellPrice > lastValues[item.symbolToken].sell) {
                    sellColor = '#30ff5e'; 
                    sColor = 'black';
                }

                const content = `
                    <div id="${symbolToken}" style="line-height: 1.5 !important;">
                        <div class="card-head" style="padding:0rem;">
                            <div class="row" style="padding-left: 12.5px;padding-right: 12.5px;">
                                <div class="col-5 text-left" style="padding-left:0px;padding-right:0px;font-weight: 300;font-size: 12px;background-color: #061725a8;">
                                    <span>
                                        <i style="color: ${netChangeColor};" class="bi bi-caret-${netChange < 0 ? 'down' : 'up'}-fill"></i>
                                        ${netChange} ${changePercent}%
                                    </span>
                                </div>
                                <div class="col-2 text-center" style="padding-left:0px;padding-right:0px;font-weight: 300;font-size: 12px;background-color: #061725a8; line-height: 2;">
                                    <span>Q : 0</span>
                                </div>
                                <div class="col-5 text-right" style="padding-left:0px;padding-right:0px; line-height: 2;font-weight: 300;font-size: 12px;background-color: #061725a8;">
                                    <b>LTP : ${currentLTP}</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-left" style="padding-right:0px;padding-right:0px;font-weight: 300;font-size: 12px; font-weight: 500;font-size: 13px;">
                                    <span>${item.tradingSymbol}</span><br>
                                </div>
                                <div class="col-7 text-right" style="padding-left:0px;padding-right:17px;font-weight: 500;font-size: 14px; font-weight: 500;font-size: 13px;">
                                    <button style="padding: 0.275rem .5rem;padding: 0px;font-size: 15px; background-color : ${buyColor}; color:${bColor};" type="button" class="col-6 btn btn-info red rate-cell bbp-rate-cell" data-value='${JSON.stringify(item)}'>
                                        ${currentBuyPrice}<br><span style="font-size : 9px; font-weight : 400;">H : ${item.high}</span>
                                    </button>
                                    <button style="padding: 0.275rem .5rem;padding: 0px;font-size: 15px; background-color : ${sellColor};  color:${sColor};" type="button" class="col-6 btn btn-info red rate-cell bsp-rate-cell" data-value='${JSON.stringify(item)}'>
                                        ${currentSellPrice}<br><span style="font-size : 9px; font-weight : 400;">L : ${item.low}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Inject the content into the appropriate div based on symbolToken
                $("#" + symbolToken).html(content);

                // Update last values for the symbol
                lastValues[symbolToken] = {
                    buy: currentBuyPrice,
                    sell: currentSellPrice,
                    ltp: currentLTP,
                };
            }
        }


        socket.on('oldData', (res) => {})
        var rowData = [];
        socket.on('response', (res) => {
            let data = JSON.parse(res);
            // updatefixed(data);
           
            print(data);
        //     if(lastClickedRowUniqueName !="")
        //     {
        //         for (const item of data.fetched) 
        //         {
        //             if (item.symbolToken === lastClickedRowUniqueName) {

        //                 updateCardWithRowDataFromResponse(item, tradeType);
                       
        //             }
        //         }
        // }
        });

        function updateCardWithRowDataFromResponse(data, tradeType) {

                    // console.log("data ====>", data, tradeType);

                    const token = `${data.symbolToken}`;
                    const exchange_code = `${JSON.stringify(data)}`;
                    const stock_code = `${data.tradingSymbol}`;
                    const bidRate = data.depth.buy[0].price;
                    const askRate = data.depth.sell[0].price;
                    const ltp = data.ltp;
                    const changePercent = data.percentChange;
                    const netChange = data.netChange;
                    const high = data.high;
                    const low = data.low;
                    const open = data.open;
                    const close = data.close;
                    let displayRate;
                    if (tradeType === 'SELL') {
                        displayRate = data.depth.buy[0].price;
                        updateOrderPriceAndCard(tradeType, displayRate);
                        document.getElementById('SELL').checked = true;
                    } else if (tradeType === 'BUY') {
                        displayRate = data.depth.sell[0].price;
                        updateOrderPriceAndCard(tradeType, displayRate);

                        document.getElementById('BUY').checked = true;
                    }

                    const currentDate = new Date();
                    const validityDate = new Date(currentDate.getTime() + 24 * 60 * 60 * 1000);
                    const formattedValidityDate = validityDate.toISOString();

                    // document.getElementById('stock_code').value = stock_code;
                    // document.getElementById('bidRate').textContent = bidRate;
                    // document.getElementById('askRate').textContent = askRate;
                    // document.getElementById('ltp').textContent = ltp;
                    // document.getElementById('exchangeCode').value = exchange_code;
                    // document.getElementById('changePercent').textContent = changePercent;
                    // document.getElementById('validity_date').value = formattedValidityDate;
                    // document.getElementById('token').value = token;


                    // let netChangeElement = document.getElementById('netChg');
                    // if (netChange < 0) {
                    //     netChangeElement.innerHTML =
                    //         `<i style="color: red;" class="bi bi-caret-down-fill"></i> ${netChange}`;
                    // } else {
                    //     netChangeElement.innerHTML =
                    //         `<i style="color: #00ff2d;" class="bi bi-caret-up-fill"></i> ${netChange}`;
                    // }

                    // document.getElementById('high').textContent = high;
                    // document.getElementById('low').textContent = low;
                    // document.getElementById('open').textContent = open;
                    // document.getElementById('close').textContent = close;
                    // console.log("displayRate = ", displayRate);
                    // document.getElementById('orderPrice').value = displayRate;

                    // const rateCard = document.getElementById('rateCard');
                    // const rateCardBody = document.querySelector('.rate-card');            

                    // rateCard.style.display = 'block';
                    // rateCard.classList.add('responsive-card');

        }

        const BUYOption = document.querySelector('#BUY');
        const SELLOption = document.querySelector('#SELL');
        const rateCardElement = document.querySelector('.rate-card');
        const rateCard = document.getElementById('rateCard');

        function updateOrderPriceAndCard(tradeType, displayRate) {
            document.getElementById('orderPrice').value = displayRate;

            if (tradeType === 'SELL') {
                rateCardElement.classList.remove('blue-bg');
                rateCardElement.classList.add('red-bg');
                rateCardElement.style.backgroundColor = 'rgb(161, 51, 41)';
                rateCard.classList.remove('blue-bg');
                rateCard.classList.add('red-bg');
                rateCard.style.backgroundColor = 'rgb(161 51 41 / 0%)';
            } else if (tradeType === 'BUY') {
                rateCardElement.classList.remove('red-bg');
                rateCardElement.classList.add('blue-bg');
                rateCardElement.style.backgroundColor = 'rgb(31, 88, 204)';
                rateCard.classList.remove('red-bg');
                rateCard.classList.add('blue-bg');
                rateCard.style.backgroundColor = 'rgb(161 51 41 / 0%)';
            }
        }

        BUYOption.addEventListener('change', () => {
            if (BUYOption.checked) {
                tradeType = 'BUY';
                const displayRate = document.getElementById('askRate').textContent;
                updateOrderPriceAndCard(tradeType, displayRate);
            }
        });

        SELLOption.addEventListener('change', () => {
            if (SELLOption.checked) {
                tradeType = 'SELL';
                const displayRate = document.getElementById('bidRate').textContent;
                updateOrderPriceAndCard(tradeType, displayRate);
            }
        });

        document.querySelectorAll('tbody#stock-data tr').forEach(row => {
            row.addEventListener('click', function() {
                clickedRowId = this.id;
                const clickedCell = this.querySelector('.rate-cell');
                rate = clickedCell.textContent.trim();

                lastClickedRateCellId = this.id;
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            console.log("dd===>", document.getElementById('stock_code'));

            const closeCardBtn = document.getElementById('closeCardBtn');
            closeCardBtn.addEventListener('click', function() {
                const rateCard = document.getElementById('rateCard');
                rateCard.style.display = 'none';
                lastClickedRowUniqueName = null;
                console.log(lastClickedRowUniqueName, "closed")
            });


            

            $('#segment-dropdown').on('change', function() {
                var segmentId = $(this).val();
                if (segmentId == 14||segmentId == 15||segmentId == 46) {
                    $('#instr-dropdown').prop('disabled', false);
                    $('#strike-dropdown').prop('disabled', false);
                } else {
                    $('#instr-dropdown').prop('disabled', true);
                    $('#strike-dropdown').prop('disabled', true);
                }
                changescript(this.value);
            });
            $('#script-dropdown').on('change', function() {
                changeexpiry(this.value);
            });
            $('#instr-dropdown').on('change', function() {
                var scriptId = $('#script-dropdown').val();
                var expirydate = $('#expiry-dropdown').val();

                changestrike(this.value, scriptId, expirydate);
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
                    $('#script-dropdown').html('<option value=""> Select Script</option>');
                    $.each(res.script, function(key, value) {
                        $("#script-dropdown").append('<option value="' + value
                            .id + '" ' + ($("#script-dropdown").attr('data-value') == value.id ?
                                "selected" : "") + '>' + value.name + '</option>');
                    });
                    console.log("fetch script = ", res);
                }
            });
        }

        function changestrike(value, scriptId, expirydate) {
            console.log("instr =", value, scriptId);
            var instrument_type = value;
            var script_id = scriptId;
            var expiry_date = expirydate;
            $("#strike-dropdown").html('');
            $.ajax({
                url: "{{ url('/fetch_strike') }}",
                type: "GET",
                data: {
                    instrument_type: instrument_type,
                    script_id: scriptId,
                    expiry_date: expiry_date,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    $('#strike-dropdown').html('<option value=""> Select Strike</option>');
                    var selectedValue = parseFloat($("#strike-dropdown").attr('data-value'));

                    $.each(res.strike, function(key, value) {
                        var strikeValue = parseFloat(value);
                        $("#strike-dropdown").append('<option value="' + strikeValue
                            + '" ' + (selectedValue === strikeValue
                                ? "selected" : "") + '>' + strikeValue + '</option>');
                    });
                }
            });
        }

        function changeexpiry(value) {
            console.log("expiry = ", value);
            var idScript = value;
            $("#expiry-dropdown").html('');
            $.ajax({
                url: "{{ url('/fetch_expiry_date') }}",
                type: "POST",
                data: {
                    script_id: idScript,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    $('#expiry-dropdown').html('<option value=""> Select Expiry Date </option>');
                    $.each(res.date, function(key, value) {
                        $("#expiry-dropdown").append('<option value="' + value
                            .expiry_date + '" ' + ($("#expiry-dropdown").attr('data-value') == value
                                .expiry_date ?
                                "selected" : "") + '>' + value.expiry_date + '</option>');
                    });
                    console.log("fetch expiry = ", res);
                }
            });
        }



        function updateCardWithRowData(data, tradeType, lot_size, uniqueName) {
            const symbol = `${data.tradingSymbol}`;
            const exchange_code = `${JSON.stringify(data)}`;
            const stock_code = `${data.tradingSymbol}`;
            const token = `${data.symbolToken}`;
            const bidRate = data.depth.buy[0].price;
            const askRate = data.depth.sell[0].price;
            const ltp = data.ltp;
            const changePercent = data.percentChange;
            const netChange = data.netChange;
            const high = data.high;
            const low = data.low;
            const open = data.open;
            const close = data.close;
            let displayRate;

            console.log("rate tgsf holgj ;lk = ", document.getElementById('stock_code'));
            // Update the hidden input fields with the data
            document.getElementById('exchangeCode').value = exchange_code;
            document.getElementById('product').value = tradeType;
            document.getElementById('token').value = token;
            document.getElementById('stock_code').innerText = stock_code;

            // Update the form fields with the data
            document.getElementById('quantity').value = lot_size;
            document.getElementById('lot_initial').value = lot_size;
            
            // Optional: Set the price or other dynamic elements
            // Example: Setting a price based on the trade type
            if (tradeType === 'SELL') {
                displayRate = bidRate;
            } else if (tradeType === 'BUY') {
                displayRate = askRate;
            }

            // Update additional fields if necessary
            document.getElementById('user_loss_percentage_alert').value = displayRate;

            // Update the validity date
            const currentDate = new Date();
            const validityDate = new Date(currentDate.getTime() + 24 * 60 * 60 * 1000); // Add 1 day
            const formattedValidityDate = validityDate.toISOString().split('T')[0]; // Format as yyyy-mm-dd
            document.getElementById('validity_date').value = formattedValidityDate;

            // Update dynamic price and other fields in the UI
            document.getElementById('high').textContent = high;
            document.getElementById('low').textContent = low;
            document.getElementById('open').textContent = open;
            document.getElementById('close').textContent = close;

            // Show the rate card and make it responsive
            const rateCard = document.getElementById('rateCard');
            rateCard.style.display = 'block';
            rateCard.classList.add('responsive-card');
        }

    </script>

@endsection

