<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{url("/")}}" class="logo d-flex align-items-center">
            <img src="{{url('assets/img/wg_logo.png')}}" alt="" class="img-fluid">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    
        <div style="color: white; display: flex; justify-content: center; align-items: center; width: 100%;">
            <h5 style="text-align: center;">@yield('title')</h5> 
        </div>    
    




    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell-fill" style="font-size: 22px;"></i>
                    <div class="notification bg-primary rounded-circle"></div>
                </a><!-- End Notification Image Icon -->
            
                <ul id="notification-dropdown" class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>Loading notifications...</h6>
                    </li>
                </ul><!-- End Notification Dropdown Items -->
            </li><!-- End Notification Icon -->

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-person-fill" style="font-size: 22px;"></i>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{auth()->user()->client}} - {{auth()->user()->first_name}} {{auth()->user()->last_name}}</h6><br>
                        <h6>User</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>
                    <li>
                        <span style="margin-left: 10px; color: white">NSE : 0/0</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>
                    <li>
                        <span style="margin-left: 10px;color: white">MCX : 0/0</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>
                    <li>
                        <span style="margin-left: 10px;color: white">OPT : 0/0</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>
                    <li>
                        <span style="margin-left: 10px;color: white">GLB : 0/0</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>


                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{url("/rules")}}" style="color: red">
                            <i class="bi bi-gear-fill"></i>
                            <span>Rules</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>


                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{url("/change_password")}}" style="color: red">
                            <i class="bi bi-gear-fill"></i>
                            <span>Change Password</span>
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>

                    <li>
                        <hr class="dropdown-divider" style="border-color: #333333;">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{url("/logout")}}" style="color: red">
                            <i class="bi bi-power"></i>
                            <span>Logout</span>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

            
            @if(auth()->user()->role_id=='2')
          
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="{{ url('/kite_login') }}" type="button">
                        <i class="bi bi-skip-start-fill"></i>
                    </a>

                </li>
     
            @endif
      

        </ul>
    </nav><!-- End Icons Navigation -->
    <marquee>No real money involved. This is a Virtual Trading Application which has all the features to trade. This application is used for exchanging views on markets for individual students for training purpose only.</marquee>
    
</header><!-- End Header -->

<style>
    #notification-dropdown {
        max-height: 300px; /* Adjust the height as needed */
        overflow-y: auto; /* Adds vertical scroll if needed */
        overflow-x: hidden; /* Prevents horizontal scroll */
    }

    #notification-dropdown .dropdown-item {
        white-space: nowrap; /* Prevents text from wrapping */
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
        function fetchNotifications() {
            $.ajax({
                url: "{{ route('notifications.index') }}", 
                type: "GET",
                dataType: 'json',
                success: function(res) {
                    console.log('Notifications Response:', res);
    
                    var notificationDropdown = $('#notification-dropdown');
                    notificationDropdown.html('');
                    
                    if (res.notifications.length === 0) {
                        notificationDropdown.html('<li class="dropdown-header"><h6>No new notifications</h6></li>');
                    } else {
                        $.each(res.notifications, function(index, notification) {
                        var notificationItem = `
                            <li class="dropdown-item">
                                <a href="#" class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="m-0">${notification.notification.title}</p>
                                        <small class="text-muted">${notification.notification.message}</small>
                                    </div>
                                    <span class="badge bg-info">${new Date(notification.notification.created_at).toLocaleTimeString()}</span>
                                    <button class="btn btn-sm btn-danger ms-2" onclick="deleteNotification(${notification.id})">Delete</button>
                                </a>
                            </li>
                        `;
                        notificationDropdown.append(notificationItem);
                    });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Fetch Error:', error);
                }
            });
        };

        
        fetchNotifications(); 
        setInterval(fetchNotifications, 60000); 
    });
    function deleteNotification(notificationId) {
        $.ajax({
            url: `{{ url('/notifications') }}/${notificationId}`, 
            type: "DELETE",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res) {
                console.log('Delete Response:', res);
            },
            error: function(xhr, status, error) {
                console.error('Delete Error:', error);
            }
        });
    }

    </script>