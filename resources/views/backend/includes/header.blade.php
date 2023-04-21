                <!-- BEGIN: Header-->
                <nav class="navbar navbar-expand navbar-light fixed-top header">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link navbar-icon sidebar-toggler" id="sidebar-toggler"
                                href="#"><span class="icon-bar"></span><span class="icon-bar"></span><span
                                    class="icon-bar"></span></a></li>
                        {{-- <li class="nav-item dropdown d-none d-sm-inline-block"><a class="nav-link dropdown-toggle megamenu-link" href="#" data-toggle="dropdown"><span>Apps<i class="ti-angle-down arrow ml-2"></i></span></a>
                            <div class="dropdown-menu nav-megamenu" style="min-width: 400px">
                                <div class="row m-0">
                                    <div class="col-6"><a class="mega-menu-item" href="#"><i class="ft-activity item-badge mb-4"></i>
                                            <h5 class="mb-2">Activity</h5>
                                            <div class="text-muted font-12">Lorem Ipsum dolar.</div></a></div>
                                    <div class="col-6"><a class="mega-menu-item bg-primary text-white" href="#"><i class="ft-globe item-badge mb-4 text-white"></i>
                                            <h5 class="mb-2">Customers</h5>
                                            <div class="text-white font-12">Lorem Ipsum dolar.</div></a></div>
                                    <div class="col-6"><a class="mega-menu-item" href="#"><i class="ft-layers item-badge mb-4"></i>
                                            <h5 class="mb-2">My Projects</h5>
                                            <div class="text-muted font-12">Lorem Ipsum dolar.</div></a></div>
                                    <div class="col-6"><a class="mega-menu-item" href="#"><i class="ft-shopping-cart item-badge mb-4"></i>
                                            <h5 class="mb-2">My Orders</h5>
                                            <div class="text-muted font-12">Lorem Ipsum dolar.</div></a></div>
                                </div>
                            </div>
                        </li> --}}
                    </ul>
                    <ul class="navbar-nav"> @hasanyrole('super-admin|developer-admin')
                        <li class="nav-item"><span style="color : green">SMS BAL : <span id="sms-balance"></span> </span> &nbsp; &nbsp; &nbsp;
                            &nbsp; &nbsp; &nbsp;</li>
                            @endhasanyrole
                        <li class="nav-divider"></li>
                        <li class="nav-item dropdown"><a
                                class="nav-link dropdown-toggle no-arrow d-inline-flex align-items-center"
                                data-toggle="dropdown" href="#"><span
                                    class="d-none d-sm-inline-block mr-2">{{ auth()->user()->name }}({{ auth()->user()->roles->pluck('name')->first() }})</span><span
                                    class="position-relative d-inline-block"><img class="rounded-circle"
                                        src="{{ asset('backend/assets/img/users/admin-image.png') }}" alt="image"
                                        width="36" /><span
                                        class="badge-point badge-success avatar-badge"></span></span></a>
                            <div class="dropdown-menu dropdown-menu-right pt-0 pb-4" style="min-width: 280px;">
                                <div class="p-4 mb-4 media align-items-center text-white"
                                    style="background-color: #2c2f48;"><img class="rounded-circle mr-3"
                                        src="{{ asset('backend/assets/img/users/admin-image.png') }}" alt="image"
                                        width="55" />
                                    <div class="media-body">
                                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                                        <div class="font-13">{{ auth()->user()->roles->pluck('name')->first() }}
                                        </div>
                                    </div>
                                </div>
                                {{-- <a class="dropdown-item d-flex align-items-center" href="/admin/profile"><i
                                    class="ft-user mr-3 font-18 text-muted"></i>Profile</a> --}}
                                    <a class="dropdown-item d-flex align-items-center" href="/admin/settings"><i
                                        class="ft-settings mr-3 font-18 text-muted"></i>Settings</a>
                                <div class="dropdown-divider my-3"></div>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <div class="mx-4"><a class="btn btn-link p-0" href="#"><span
                                                onclick="$('#logout-form').submit()" class="btn-icon"><i
                                                    class="ft-power mr-2 font-18"></i>Logout</span></a></div>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav><!-- END: Header-->
                @push('scripts')
                     <script>
                        $(document).ready(function() {
                            $.ajax({
                                url: "https://2factor.in/API/V1/0036d9eb-6ee6-11ec-b710-0200cd936042/ADDON_SERVICES/BAL/TRANSACTIONAL_SMS",
                                method: 'get',
                                success: function(response) {

                                    var response = (response.Details);
                                    if (response <= 200) {
                                        $("#sms-balance").css("color", "red");

                                        $("#sms-balance").css("color", "green");
                                    } else {
                                    }
                                    console.log(response);
                                    $('#sms-balance').text(response);

                                },
                                error: function(xhr) {
                                    if (xhr.status == 404) {
                                        console.log('', xhr.error);
                                    } else {
                                        console.log('', (JSON.stringify(xhr)));
                                    }
                                }
                            });
                        });
                    </script>
                @endpush
