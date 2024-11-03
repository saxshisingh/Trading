@extends('layout.app')
@section('title', 'Portfolio/Position')
@section('content')
<div class="container-fluid">
<form  id="filterForm"  method="GET" >
    @csrf
<div class="container-fluid">
    <div class="row">
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

        <div class="col-sm-2">
            <div style="display: flex; align-items: center;">
                <label class="check"><input type="radio" class="form-check-input" name="orderType" value="all" checked>ALL</label><br>
            </div>
            <br>
            <div style="display: flex; align-items: center; margin-top:-20px">
                <label class="check"><input type="radio" class="form-check-input" name="orderType" value="outstandin">OUTSTANDING</label>
            </div>
        </div>        
        <div class="col-sm-2">
            <div style="display: flex; align-items: center;">
                <label class="check"><input type="radio" class="form-check-input" name="selectionType" value="client" checked>CLIENT WISE</label><br>
            </div>
            <br>
            <div style="display: flex; align-items: center; margin-top:-20px">
                <label class="check"><input type="radio" class="form-check-input" name="selectionType" value="script">SCRIPT WISE</label><br>
            </div>
        </div>
        
        
        <div class="col-sm-2">
            <label class="col-form-label">MARKET</label>
            <select class="form-control" name="segment_id" id="segment-dropdown">
                <option value="">Select Market</option>
                @foreach ($segment as $seg)
                    <option value="{{ $seg->id }}">{{ $seg->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-2">
            <label class="col-form-label">SCRIPT</label>
            <select class="form-control" name="script_id" id="script-dropdown">
                <option value=""></option>
            </select>
        </div>
        
        <div class="col-sm-2">
            <label class="col-form-label">EXPIRY DATE</label>
            <input type="date" class="form-control" name="expiry_date_id">
        </div>
    </div>
</div>
<div class="container-fluid" style="margin-top: 15px">
    <div class="row" style="padding: 20px">
        <div class="col-sm-2 m-1">
            <button class="btn btn-sm btn-info" id="getPositionBtn" style="padding: 10px; color: white" type="submit">GET POSITION</button>  
        </div>        
        <div class="col-sm-2 m-1">
            <button class="btn btn-success btn-sm" style="padding: 10px; color: white" type="button" data-bs-toggle="modal" data-bs-target="#rollover">ROLL OVER ALL</button>
        </div>
        <div class="col-sm-2 m-1">
            <button class="btn btn-danger btn-sm" style="padding: 10px; color: white" type="button" data-bs-toggle="modal" data-bs-target="#close_position">CLOSE POSITION</button>
        </div>
        <div class="col-sm-2 m-1">
            <button class="btn btn-secondary btn-sm" style="padding: 10px; color: white" type="reset">CLEAR FILTER</button>
        </div>
    </div>
</div>
</form>
</div>
<div class="container-fluid" style="margin-top: 5px">
    <div class="row" style="padding: 20px">
        <div class="col-sm-2">
            <h6>TOTAL MTM</h6>
            <h6 id="total-mtm">0</h6>
        </div>       
        <div class="col-sm-2">
            <h6>LIMIT</h6>
            <h6 id="user-balance">{{auth()->user()->position_balance}}</h6>
        </div>
        <div class="col-sm-2">
            <h6>NET</h6>
            <h6 id="net-amount">{{auth()->user()->position_balance}}</h6>
        </div>
        <div class="col-sm-2">
            <h6>TOTAL QTY</h6>
            <h6 id="total-qty">0.00</h6>
        </div>
    </div>
</div>

<div class="table-responsive" >
    <table class="table">
        <thead>
            <tr>
           
                <th>MARKET</th>
                <th>SCRIPT</th>
                <th>T. BUY Q.</th>
                <th>BUY A. P.</th>
                <th>T. SELL Q.</th>
                <th>SELL A. P.</th>
                <th>NET Q.</th>
                <th>A/B P.</th>
                <th>MTM</th>
                <th>AUTO CLOSE</th>
                <th>CLOSE</th>
            </tr>
        </thead>
        <tbody  id="position-table-container">
         
        @foreach ($position as $u)
            <tr class="{{ $u->type == 'BUY' ? 'tr-blue' : 'tr-red' }}" 
                data-id="{{$u->id}}"
                data-script-name="{{ $u->script->name }}" 
                data-token="{{$u->instrument_token}}"
                data-action="{{ $u->type }}" 
                data-buy-rate="{{ $u->type=="BUY"?$u->price:"" }}" 
                data-sell-rate="{{ $u->type=="SELL"?$u->price:"" }}"
                data-qty="{{ $u->quantity }}"
                data-lot="{{ $u->lot }}"
                data-created-at="{{ $u->created_at }}"
                data-expiry-date="{{ $u->expiry->expiry_date ?? '' }}" style="font-size: 12px">

              
                <td>{{ $u->segment->instrument_type }}</td>
                <td>{{ $u->script->name }}</td>
                @if($u->type=="BUY")
                    <td>{{$u->quantity}}</td>
                    <td>{{$u->price}}</td>
                    <td>0</td>
                    <td>0</td>
                @else
                    <td>0</td>
                    <td>0</td>
                    <td>{{$u->quantity}}</td>
                    <td>{{$u->price}}</td>
                @endif
                <td class="qty">
                    @if($u->type=="BUY")
                        {{$u->quantity}}
                    @else
                        {{0-$u->quantity}}
                    @endif
                </td>
                <td class="current-value">0</td>
                <td class="mtm-value">0</td>
           
                <td>
                    {{date("d-m-Y", strtotime($u->created_at))}}
                </td>
                <td>
                        <button type="button" class="btn btn-dark btn-sm extra-small-button" data-bs-toggle="modal" data-bs-target="#close_pos"
                                data-id="{{$u->id}}"
                                data-script-name="{{ $u->script->name }}" 
                                data-action="{{ $u->type }}" 
                                data-buy-rate="{{ $u->type=="BUY"?$u->price:"" }}" 
                                data-sell-rate="{{ $u->type=="SELL"?$u->price:"" }}"
                                data-qty="{{ $u->quantity }}"
                                data-lot="{{ $u->lot }}">
                            <i class="bi bi-x" ></i>
                        </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="modal fade" id="close_pos" tabindex="-1" aria-labelledby="close_pos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Close Position</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('portfolio.check')}}" method="POST">
                @csrf
            <label for="lot">Lot</label>
            <input type="text" name="lot" class="form-control" id="modal-lot" value="" readonly>
          <label for="qty">Quantity</label>
            <input type="text" name="qty" class="form-control" id="modal-qty" value="" readonly>
          <input type="hidden" name="position_id" id="modal-position-id" value="">
          <input type="hidden" name="live_mtm" id="modal-live-mtm" value="">
          <input type="hidden" name="current_price" id="modal-current-value" value="">
          <input type="hidden" name="action" id="modal-action" value="">
          <input type="hidden" name="close_single_position" value="1">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-submit" data-bs-dismiss="modal">Submit</button>
        </div>
    </form>
      </div>
    </div>
</div>
<div class="modal fade" id="rollover" tabindex="-1" aria-labelledby="rollover" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Roll Over All Position</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{route('portfolio.check')}}" method="POST">
                @csrf
          <label for="password">Password</label><br>
          <input type="password" class="form-control custom-placeholder" name="password" placeholder="Enter Password">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-submit" data-bs-dismiss="modal">Submit</button>
        </div>
    </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="close_position" tabindex="-1" aria-labelledby="close_position" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Close All Position</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="portfolioForm" action="{{route('portfolio.check')}}" method="POST">
                @csrf
          <input type="hidden" name="close_position" value="1">
          <input type="hidden" id="portfolios" name="portfolios">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-submit" onclick="submitCloseAllForm()" data-bs-dismiss="modal">Submit</button>
        </div>
    </form>
      </div>
    </div>
  </div>


@endsection

@section('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const closePosModal = document.getElementById('close_pos');
        closePosModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const lot = button.getAttribute('data-lot');
            const qty = button.getAttribute('data-qty');
            const positionId = button.getAttribute('data-id');
            const action = button.getAttribute('data-action');
            var userId = button.getAttribute('data-id'); 
            var mtmElement = document.querySelector(`tr[data-id="${userId}"] td.mtm-value`);
            var currentElement = document.querySelector(`tr[data-id="${userId}"] td.current-value`);
            var currentValue = currentElement ? currentElement.textContent.trim() : '0'; 
            
            var mtmValue = mtmElement ? mtmElement.textContent.trim() : '0'; 
            const modalLot = closePosModal.querySelector('#modal-lot');
            const modalQty = closePosModal.querySelector('#modal-qty');
            const modalAction = closePosModal.querySelector('#modal-action');
            const modalPositionId = closePosModal.querySelector('#modal-position-id');
            document.getElementById('modal-live-mtm').value = mtmValue; 
            document.getElementById('modal-current-value').value = currentValue; 

            modalLot.value = lot;
            modalQty.value = qty;
            modalPositionId.value = positionId;
            modalAction.value = action;
        });
    });

    @if($isExtreme)
        document.addEventListener('DOMContentLoaded', function() {
            submitCloseAllForm();
        });
    @endif
    updateQty();
    let list = []; 
    let exchangerGroups = {}; 
    let exchanger;
    let tokens = [];
    let token = JSON.parse(@json($token));
    // console.log("token = ", token);
    @foreach ($position as $item)
        exchanger = "{{ $item->segment->exchange }}";
        tokens = "{{ $item->expiry->token }}";

        if (!exchangerGroups[exchanger]) {
            exchangerGroups[exchanger] = [];
        }

        exchangerGroups[exchanger].push(tokens);
    @endforeach

    for (let exchanger in exchangerGroups) {
        list.push({
            exchanger: exchanger,
            script: exchangerGroups[exchanger]
        });
    }

        // console.log("data we are sending = ", list, token);
        const socket = io("wss://socket.whitegoldtrades.com", {
            query: {
                data: JSON.stringify(token),
                list: JSON.stringify(list)
            }
        });

    socket.on('response', (res) => {
            let data = JSON.parse(res);
            // console.log("data=", data);
            updateMtm(data);
            updateQty();
        });

        function submitCloseAllForm() {
            let portfolios = [];
            let positions = document.querySelectorAll('tbody tr');
            
            positions.forEach(position => {
                let scriptName = position.getAttribute('data-script-name');
                let action = position.getAttribute('data-action');
                let buyRate = parseFloat(position.getAttribute('data-buy-rate'));
                let sellRate = parseFloat(position.getAttribute('data-sell-rate'));
                let quantity = position.getAttribute('data-qty');
                let mtm = parseFloat(position.querySelector('.mtm-value').textContent);
                let currentValue = parseFloat(position.querySelector('.current-value').textContent);
                let id = position.getAttribute('data-id');

                portfolios.push({
                    scriptName: scriptName,
                    action: action,
                    buyRate: buyRate,
                    sellRate: sellRate,
                    quantity: quantity,
                    mtm: mtm,
                    id: id,
                    currentValue: currentValue,
                });
            });
            document.getElementById('portfolios').value = JSON.stringify(portfolios);
        }

        function updateMtm(data) {
    if (typeof data !== 'object' || data === null) {
        // console.error("Expected an object, but received:", data);
        return;
    }

    for (const item of data.fetched) {
        let ticker = item.symbolToken;
        let currentPrice = 0;
        let sellPrice = item.depth.buy[0].price;
        let buyPrice = item.depth.sell[0].price;

        let positions = document.querySelectorAll(`tr[data-token="${ticker}"]`);

        positions.forEach(position => {
            let action = position.getAttribute('data-action');
            let buyRate = parseFloat(position.getAttribute('data-buy-rate'));
            let sellRate = parseFloat(position.getAttribute('data-sell-rate'));
            let qty = parseFloat(position.getAttribute('data-qty'));

            let mtm = 0;

            if (action === 'BUY') {
                currentPrice = sellPrice;
                mtm = (currentPrice - buyRate) * qty;
            } else if (action === 'SELL') {
                currentPrice = buyPrice;
                mtm = (sellRate - currentPrice) * qty;
            }
            // console.log("check = ", buyPrice, sellPrice, qty, buyRate, sellRate, action, mtm);

            position.querySelector('.mtm-value').textContent = mtm.toFixed(2);
            position.querySelector('.current-value').textContent = currentPrice;

            updateTotalMtm();
            updateQty();

            // console.log("Updated MTM for ticker", ticker, "=", mtm);
        });
    }
}


    function updateTotalMtm()
    {
        let userBalance = parseFloat(document.getElementById('user-balance').textContent.trim());

        let totalMtm = 0;
        $('td.mtm-value').each(function() {
            totalMtm += parseFloat($(this).text());
        });
        let netAmount = userBalance + totalMtm;
        document.getElementById('net-amount').textContent = netAmount.toFixed(2);  
        document.getElementById('total-mtm').textContent = totalMtm.toFixed(2);
        if (netAmount <= 0) {
            submitCloseAllForm(); 
            console.log("called submit all");
            document.getElementById('portfolioForm').submit();
            console.log("submitted");
        }
    }

    function updateQty()
    {
        let totalQty = 0;
      
        $('td.qty').each(function() {
            totalQty += parseFloat($(this).text());
        });

        document.getElementById('total-qty').textContent = totalQty;
    }
    
    $(document).ready(function() {
        $('#getPositionBtn').click(function() {
            $('#position-table-container').show();
        });
            $('#segment-dropdown').on('change', function() {
                changescript(this.value);
            });
    });

        function changescript(value) {
            // console.log("script =", value);
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
                    // console.log("fetch script = ", res);                
                }
            });
        }
</script>
@endsection
