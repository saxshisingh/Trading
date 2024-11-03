<div class="d-flex align-items-center">
    <label class="form-control-label mr-2">Show</label>
    <div class="input-group" style="width: auto; margin-left:10px; margin-right:10px">
        <select class="form-select page " style="width: 80px;">
            <option value="10" {{ \Request::get('page_size') == "10" ? "selected" : "" }}>10</option>
            <option value="20" {{ \Request::get('page_size') == "20" ? "selected" : "" }}>20</option>
            <option value="50" {{ \Request::get('page_size') == "50" ? "selected" : "" }}>50</option>
            <option value="100" {{ \Request::get('page_size') == "100" ? "selected" : "" }}>100</option>
            <option value="200" {{ \Request::get('page_size') == "200" ? "selected" : "" }}>200</option>
            <option value="500" {{ \Request::get('page_size') == "500" ? "selected" : "" }}>500</option>
            <option value="1000" {{ \Request::get('page_size') == "1000" ? "selected" : "" }}>1000</option>
        </select>
    </div>
    <label class="form-control-label "> Entries</label>
</div>
