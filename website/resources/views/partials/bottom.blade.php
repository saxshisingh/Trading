<div class="bot" style="position:relative" >
<div style="position: fixed; bottom: 0; width: -webkit-fill-available; background-color: #333; margin-left: -20px; color: white; display: flex; justify-content: space-around; align-items: center; padding: 10px 0;">
    <a href="{{ url('/watchlist') }}" class="tab" style="color: white; text-decoration: none;">
        <i class="bi bi-eye"></i>
        <span>Watchlist</span>
    </a>
    <a href="{{ url('/trades') }}" class="tab" style="color: white; text-decoration: none;">
        <i class="bi bi-clipboard2-pulse-fill"></i>
        <span>Trades</span>
    </a>
    <a href="{{ url('/portfolio') }}" class="tab" style="color: white; text-decoration: none;">
        <i class="bi bi-file-bar-graph-fill"></i>
        <span>Portfolio</span>
    </a>
    <a href="{{ url('/ledger') }}" class="tab" style="color: white; text-decoration: none;">
        <i class="bi bi-person-fill"></i>
        <span>Account</span>
    </a>
</div>
</div>

<style>
    .bottom-bar .tab {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .bottom-bar .tab i {
        font-size: 1.5rem;
    }
    .bottom-bar .tab span {
        font-size: 0.75rem;
    }
</style>