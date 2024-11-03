<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @if(auth()->check() && auth()->user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="{{ url('/watchlist') }}">
                    <i class="bi bi-eye-fill"></i><span>Trading
                       
                    </span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    
                        <li>
                            <a href="{{ url('/watchlist') }}">
                                <i class="bi bi-record-circle"></i><span>Watchlist</span>
                            </a>
                        </li>
                    
                        <li>
                            <a href="{{ url('/trades') }}">
                                <i class="bi bi-record-circle"></i><span>Trades</span>
                            </a>
                        </li>
                    
                        <li>
                            <a href="{{ url('/portfolio') }}">
                                <i class="bi bi-record-circle"></i><span>Portfolio/Position
                                    

                                </span>
                            </a>
                        </li>
                   
                        <li>
                            <a href="{{ url('/banned') }}">
                                <i class="bi bi-record-circle"></i><span>Banned/Blocked Scripts
                                        <span class="badge bg-danger rounded-pill"></span>
                                    
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/details') }}">
                                <i class="bi bi-record-circle"></i><span>Max Quantity Details
                                        <span class="badge bg-danger rounded-pill"></span>
                                    
                                </span>
                            </a>
                        </li>
                    
                </ul>

            </li><!-- End Trading Nav -->
        @endif
       
        @if(auth()->check() && auth()->user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="{{ url('/watchlist') }}">
                    <i class="bi bi-clipboard-data-fill"></i><span>Utilities
                            <span class="badge bg-danger rounded-pill"></span>
                        
                    </span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                   
                        {{-- <li>
                            <a href="{{ url('/edit_delete') }}">
                                <i class="bi bi-record-circle"></i><span>Trade Edit/Delete Log</span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ url('/rejection') }}">
                                <i class="bi bi-record-circle"></i><span>Rejection Log</span>
                            </a>
                        </li>
                     
                </ul>
            </li><!-- End Utilities Nav -->
        @endif

        @if(auth()->check() && auth()->user()->role_id == 1)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#accounts-nav" data-bs-toggle="collapse" href="{{ url('/watchlist') }}">
                    <i class="bi bi-briefcase-fill"></i><i class="bi bi-suitcase-lg-fill"></i><span>Accounts
                            <span class="badge bg-danger rounded-pill"></span>
                        
                    </span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="accounts-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ url('/ledger') }}">
                            <i class="bi bi-record-circle"></i><span>Ledger</span>
                        </a>
                    </li> 
                </ul>
            </li><!-- End Accounts Nav -->
        @endif
        @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav" href="{{ url('/dashboard') }}">
                    <i class="bi bi-grid"></i></i><span>Dashboard
                            <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Dashboard Nav -->
        @endif

        @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('blockscript.index')}}">
                    <i class="bi bi-graph-up-arrow"></i><span>Market Watch
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Banned Script Management Nav -->
            @endif

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('active_position.index')}}">
                    <i class="bi bi-person-badge-fill"></i><span>Active Position
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Active Position Nav -->
            @endif

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('close_position.index')}}">
                    <i class="bi bi-person-badge"></i><span>Closed Position
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Closed Position Nav -->
            @endif

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('notification.index')}}">
                    <i class="bi bi-bell-fill"></i><span>Notifications
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Notification Nav -->
            @endif

        @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#user-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people-fill"></i><span>User Management
                        <span class="badge bg-danger rounded-pill"></span>
                    </span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="user-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('users.create')}}">
                                <i class="bi bi-record-circle"></i><span>New Registration</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('users.index')}}">
                                <i class="bi bi-record-circle"></i><span>View Members</span>
                            </a>
                        </li>
                </ul>

            </li><!-- End User Management Nav -->
            @endif
        

        {{-- @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('tradeRequest.index')}}">
                    <i class="bi bi-tag-fill"></i><span>Trades
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li>
            @endif --}}

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('closed_trades.index')}}">
                    <i class="bi bi-tag-fill"></i><span>Closed Trades
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Trade Request Nav -->
            @endif

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('deleted_trades.index')}}">
                    <i class="bi bi-tag-fill"></i><span>Deleted Trades
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Trade Request Nav -->
            @endif

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('trader_funds.index')}}">
                    <i class="bi bi-tag-fill"></i><span>Trader Funds
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Trade Request Nav -->
            @endif
            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('transaction_history.index')}}">
                    <i class="bi bi-tag-fill"></i><span>Transaction History
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Trade Request Nav -->
            @endif
            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('trade_history.index')}}">
                    <i class="bi bi-tag-fill"></i><span>Trade History
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Trade Request Nav -->
            @endif


            

            {{-- @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('pending.index')}}">
                    <i class="bi bi-journal-bookmark-fill"></i><span>Pending Orders
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Max Quantity Details Management Nav --> --}}
            
            {{-- @endif --}}
            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('quantity.index')}}">
                    <i class="bi bi-journal-bookmark-fill"></i><span>Edit Max Order Details
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Max Quantity Details Management Nav -->
            
            @endif

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('login_password.index')}}">
                    <i class="bi bi-person-fill"></i><span>Change Login Password
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Trade Request Nav -->
            @endif

            @if(auth()->check() && auth()->user()->role_id == 2)
            <li class="nav-item">
                <a class="nav-link collapsed"  href="{{ route('transaction_password.index')}}">
                    <i class="bi bi-gear-fill"></i><span>Change Transaction Password
                    <span class="badge bg-danger rounded-pill"></span>
                </a>
            </li><!-- End Trade Request Nav -->
            @endif

            

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav" href="{{ url('/logout') }}">
                    <i class="bi bi-box-arrow-left" style="color: red"></i><span>Logout
                            <span class="badge bg-danger rounded-pill"></span>
                        
                    </span>
                </a>
                
            </li><!-- End Logout Nav -->
        
            
            
        </ul>

    </aside><!-- End Sidebar-->
