<div class="page-sidebar custom-scroll" id="sidebar">
    <div class="sidebar-header">
        <a class="sidebar-brand" @hasanyrole('super-admin|developer-admin|branch-manager|collection-manager|collection-executive|marketing-executive|marketing-manager') href = "/" @endhasanyrole>Llyomax</a>
        <a class="sidebar-brand-mini"  @hasanyrole('super-admin|developer-admin|branch-manager|collection-manager|collection-executive|marketing-executive|marketing-manager') href = "/" @endhasanyrole>LM</a>
        <span class="sidebar-points">
            <span class="badge badge-success badge-point mr-2"></span>
            <span class="badge badge-danger badge-point mr-2"></span>
            <span class="badge badge-warning badge-point"></span>
        </span>
    </div>
    <ul class="sidebar-menu metismenu">
        @hasanyrole('super-admin|developer-admin|branch-manager|collection-manager|collection-executive|marketing-executive|marketing-manager|store-admin')
            <li {{ request()->is('*/dashboard') ? 'class=mm-active' : '' }}>
                <a href="/admin/dashboard"><i class="sidebar-item-icon ft-home"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
        @endhasanyrole
        @hasanyrole('super-admin|developer-admin|marketing-manager|collection-manager |branch-manager')
            <li {{ request()->is('*/master/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Master</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    @hasanyrole('super-admin|developer-admin|marketing-manager|collection-manager')
                    <li><a href="/admin/master/districts"{{ request()->is('*/master/districts*') ? 'class=mm-active' : '' }}>Districts</a></li>
                    <li><a href="/admin/master/areas" {{ request()->is('*/master/areas*') ? 'class=mm-active' : '' }}>Areas</a></li>
                    <li><a href="/admin/master/branches"{{ request()->is('*/master/branches*') ? 'class=mm-active' : '' }}>Branches</a></li>
                    <li><a href="/admin/master/clusters"{{ request()->is('*/master/clusters*') ? 'class=mm-active' : '' }}>Clusters</a></li>
                    <li><a href="/admin/master/schemes"{{ request()->is('*/master/schemes*') ? 'class=mm-active' : '' }}>Scheme Registration</a></li>
                    {{-- @hasanyrole('developer-admin') --}}
                    {{-- <li><a href="/admin/master/branch-assigning"{{ request()->is('*/master/branch-assigning*') ? 'class=mm-active' : '' }}>Assign Clusters to Districts</a> --}}
                        {{-- @endhasanyrole --}}
                    {{-- <li><a href="/admin/branch/branch-assigning"{{ request()->is('*/branch/branch-assigning*') ? 'class=mm-active' : '' }}>Assign Branches to Scheme</a> --}}
                    <li><a href="/admin/master/managers" {{ request()->is('*/master/managers*') ? 'class=mm-active' : '' }}>Managers</a></li>
                    <li><a href="/admin/master/office-admins" {{ request()->is('*/master/office-admins*') ? 'class=mm-active' : '' }}>Office Admins</a></li>
                    <li><a href="/admin/master/executives" {{ request()->is('*/master/executives*') ? 'class=mm-active' : '' }}>Executives</a></li>
                    @endhasanyrole

                </ul>
            </li>
        @endhasanyrole
        @hasanyrole('super-admin|developer-admin|collection-executive|branch-manager|collection-manager|marketing-executive')
            <li {{ request()->is('*/executive/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Executives</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    <!-- 2-nd level-->
                    @hasanyrole('super-admin|developer-admin|collection-executive|marketing-executive')
                    <li><a href="/admin/executive/leave-form" {{ request()->is('*/executive/leave-form*') ? 'class=mm-active' : '' }}>Leave Form</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|branch-manager|collection-manager|collection-executive')
                    <li><a href="/admin/executive/amount-transfer-executive" {{ request()->is('*/executive/amount-transfer-executive*') ? 'class=mm-active' : '' }}> Transfer Amount Executive</a>
                    </li>
                    @endhasanyrole
                </ul>
            </li>
        @endhasanyrole
        @hasanyrole('super-admin|developer-admin|branch-manager|marketing-manager|collection-manager')
            <li {{ request()->is('*/branch/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Branches</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    @hasanyrole('super-admin|developer-admin|collection-manager|branch-manager')
                    <li><a href="/admin/branch/branch-targets"{{ request()->is('*/branch/branch-targets*') ? 'class=mm-active' : '' }}>Branch Targets</a></li>
                    <li><a href="/admin/branch/scheme-targets"{{ request()->is('*/branch/scheme-targets*') ? 'class=mm-active' : '' }}>Scheme Targets</a></li>
                    <li><a href="/admin/branch/collection-executives" {{ request()->is('*/branch/collection-executives*') ? 'class=mm-active' : '' }}>Assigning C.E</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|marketing-manager|branch-manager')
                    <li><a href="/admin/branch/marketing-executive-targets"{{ request()->is('*/branch/marketing-executive-targets*') ? 'class=mm-active' : '' }}>M.E Targets</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|branch-manager|marketing-manager|collection-manager')
                    <li> <a href="/admin/branch/amount-transfer" {{ request()->is('*/branch/amount-transfer*') ? 'class=mm-active' : '' }}> Transfer Amount Manager</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|marketing-manager|branch-manager|collection-manager')
                        <li {{ request()->is('*/luckydraws/*') ? 'class=mm-active' : '' }}>
                            <a href="javascript:;">
                                <i class="sidebar-item-icon ft-layout"></i>
                                <span class="nav-label">Lucky Draws</span>
                                <i class="arrow la la-angle-right"></i>
                            </a>
                            <ul class="nav-2-level">
                                <li><a href="/admin/branch/luckydraws/eligible-customers"{{ request()->is('*/branch/luckydraws/eligible-customers*') ? 'class=mm-active' : '' }}>Eligible Customers</a></li>
                                <li><a href="/admin/branch/luckydraws/winners-list"{{ request()->is('*/branch/luckydraws/winners-list*') ? 'class=mm-active' : '' }}>Winners List</a></li>
                            </ul>
                        </li>
                    @endhasanyrole
                </ul>
            </li>
        @endhasanyrole
        @hasanyrole('super-admin|developer-admin|branch-manager|collection-executive|marketing-executive')
            <li {{ request()->is('*/customer/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Customers</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    <!-- 2-nd level-->
                    <li><a href="/admin/customer/customers" {{ request()->is('*/customer/customers*') ? 'class=mm-active' : '' }}>Registration</a></li>
                    @hasanyrole('super-admin|developer-admin|branch-manager|collection-executive')
                        <li><a href="/admin/customer/customer-collection" {{ request()->is('*/customer/customer-collection*') ? 'class=mm-active' : '' }}>Customer Collection</a></li>
                    @endhasanyrole

                </ul>
            </li>
        @endhasanyrole
        @hasanyrole('office-administrator')
            <li {{ request()->is('*/customer/*') ? 'class=mm-active' : '' }}>
                <a href="/admin/customer/customers"><i class="sidebar-item-icon ft-user"></i>
                    <span class="nav-label">Customer Details</span>
                </a>
            </li>
        @endhasanyrole
        @hasanyrole('super-admin|developer-admin|office-administrator|branch-manager')
            <li {{ request()->is('*/office-admin/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Office Admin</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    <!-- 2-nd level-->
                    <li><a href="/admin/office-admin/staffs"{{ request()->is('*/office-admin/staffs*') ? 'class=mm-active' : '' }}>Staffs</a></li>
                    <li><a href="/admin/office-admin/attendances"{{ request()->is('*/office-admin/attendances*') ? 'class=mm-active' : '' }}>Attendance  Form</a></li>
                </ul>
            </li>
        @endhasanyrole
        @hasanyrole('developer-admin')
            {{-- <li {{ request()->is('*/settings/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Settings</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    <!-- 2-nd level-->
                    <li><a href="/admin/settings/roles"{{ request()->is('*/settings/roles*') ? 'class=mm-active' : '' }}>Roles</a></li>
                    <li><a href="/admin/settings/users"{{ request()->is('*/settings/users*') ? 'class=mm-active' : '' }}>Users</a></li>
                </ul>
            </li> --}}
        @endhasanyrole
        @hasanyrole('developer-admin|store-admin|delivery-boy')
        <li {{ request()->is('*/warehouse/*') ? 'class=mm-active' : '' }}>
            <a href="javascript:;">
                <i class="sidebar-item-icon ft-layout"></i>
                <span class="nav-label">Store admin</span>
                <i class="arrow la la-angle-right"></i>
            </a>
            <ul class="nav-2-level">
                <!-- 2-nd level-->
                @hasanyrole('developer-admin|store-admin')
                <li><a href="/admin/warehouse/collection-completed"{{ request()->is('*/warehouse/collection-completed*') ? 'class=mm-active' : '' }}>Completed Customers</a></li>
                <li><a href="/admin/warehouse/delivery-executives"{{ request()->is('*/warehouse/delivery-executives*') ? 'class=mm-active' : '' }}>Delivery Executives</a></li>
                @endhasanyrole
                <li><a href="/admin/warehouse/categories"{{ request()->is('*/warehouse/categories*') ? 'class=mm-active' : '' }}>Categories</a></li>
                <li><a href="/admin/warehouse/products"{{ request()->is('*/warehouse/products*') ? 'class=mm-active' : '' }}>Products</a></li>
                <li><a href="/admin/warehouse/orders"{{ request()->is('*/warehouse/orders*') ? 'class=mm-active' : '' }}>Orders</a></li>
                @hasanyrole('developer-admin|store-admin')
                <li><a href="/admin/warehouse/assigning-delivery-boys"{{ request()->is('*/warehouse/assigning-delivery-boys*') ? 'class=mm-active' : '' }}>Assigning Delivery Boys</a></li>
                @endhasanyrole
                @hasanyrole('delivery-boy')
                <li><a href="/admin/warehouse/assigning-delivery-boys"{{ request()->is('*/warehouse/assigning-delivery-boys*') ? 'class=mm-active' : '' }}>Assigned Orders</a></li>
                @endhasanyrole
            </ul>
        </li>
        @endhasanyrole
        @hasanyrole('developer-admin| super-admin')
            <li {{ request()->is('*/accounts/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Account Management</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    <!-- 2-nd level-->
                    <li><a href="/admin/accounts/expense" {{ request()->is('*/accounts/expense*') ? 'class=mm-active' : '' }}>Office Expenses</a></li>
                    <li><a href="/admin/accounts/advance" {{ request()->is('*/accounts/advance*') ? 'class=mm-active' : '' }}>Advance Salary </a></li>
                    <li><a href="/admin/accounts/weekly-gifts" {{ request()->is('*/accounts/weekly-gifts*') ? 'class=mm-active' : '' }}>Weekly Gifts</a></li>
                    <li><a href="/admin/accounts/expense-bills" {{ request()->is('*/accounts/expense-bills*') ? 'class=mm-active' : '' }}>Expense Bills</a></li>
                    <li><a href="/admin/accounts/rent-allowance" {{ request()->is('*/accounts/rent-allowance*') ? 'class=mm-active' : '' }}>Rent Allowance</a></li>
                    <li><a href="/admin/accounts/transportation-allowances"{{ request()->is('*/accounts/transportation-allowance*') ? 'class=mm-active' : '' }}>Transportation Allowance</a></li>
                    <li><a href="/admin/accounts/sales-commisions" {{ request()->is('*/accounts/sales-commisions*') ? 'class=mm-active' : '' }}>Sales Commision</a></li>
                    <li><a href="/admin/accounts/salary-of-individuals" {{ request()->is('*/accounts/salary-of-individuals*') ? 'class=mm-active' : '' }}>Salary of Individuals</a></li>
                    <li><a href="/admin/accounts/salary-incentives" {{ request()->is('*/accounts/salary-incentives*') ? 'class=mm-active' : '' }}>Salary Incentives</a></li>
                    <li><a href="/admin/accounts/extra-bonus"{{ request()->is('*/accounts/extra-bonus*') ? 'class=mm-active' : '' }}>Extra Bonus</a></li>
                </ul>
            </li>
        @endhasanyrole
        @hasanyrole('super-admin|developer-admin|marketing-manager|branch-manager|collection-manager|marketing-executive|collection-executive')
            <li {{ request()->is('*/reports/*') ? 'class=mm-active' : '' }}>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ft-layout"></i>
                    <span class="nav-label">Reports</span>
                    <i class="arrow la la-angle-right"></i>
                </a>
                <ul class="nav-2-level">
                    <!-- 2-nd level-->
                    @hasanyrole('developer-admin| super-admin')
                    <li><a href="/admin/reports/stock-reports"{{ request()->is('*/reports/stock-reports*') ? 'class=mm-active' : '' }}>Stock Reports</a></li>
                @endhasanyrole
                @hasanyrole('developer-admin| super-admin')
                 <li><a href="/admin/reports/order-reports"{{ request()->is('*/reports/order-reports*') ? 'class=mm-active' : '' }}>Order Reports</a></li>
                 @endhasanyrole
                    @hasanyrole('developer-admin| super-admin')
                        <li><a href="/admin/reports/account-reports"{{ request()->is('*/reports/account-reports*') ? 'class=mm-active' : '' }}>Account Reports</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|marketing-manager |collection-manager')
                        <li><a href="/admin/reports/manager-reports"{{ request()->is('*/reports/manager-reports*') ? 'class=mm-active' : '' }}>Managers List</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|marketing-manager |branch-manager|collection-manager')
                        <li><a href="/admin/reports/executive-reports"{{ request()->is('*/reports/executive-reports*') ? 'class=mm-active' : '' }}>Executive List </a></li>
                        <li><a href="/admin/reports/staff-attendance-reports"{{ request()->is('*/reports/staff-attendance-reports*') ? 'class=mm-active' : '' }}>Staff Attendance Report</a></li>
                        <li><a href="/admin/reports/lucky-draw-reports"{{ request()->is('*/reports/lucky-draw-reports*') ? 'class=mm-active' : '' }}>Lucky Draw Report</a> </li>
                    @endhasanyrole
                @endhasanyrole
                @hasanyrole('super-admin|developer-admin|marketing-manager|branch-manager|collection-manager')
                    @hasanyrole('super-admin|developer-admin|marketing-manager|collection-manager')
                        <li><a href="/admin/reports/branch-report-by-marketing"{{ request()->is('*/reports/branch-report-by-marketing*') ? 'class=mm-active' : '' }}>Branch Report </a></li>
                        <li><a href="/admin/reports/branch-target" {{ request()->is('*/reports/branch-target*') ? 'class=mm-active' : '' }}>Branch Target Report </a></li>
                        <li><a href="/admin/reports/collection-amount" {{ request()->is('*/reports/collection-amount*') ? 'class=mm-active' : '' }}>Collection Amount Details </a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|marketing-manager|branch-manager|collection-manager')
                        <li><a href="/admin/reports/marketing-execitive-target" {{ request()->is('*/reports/marketing-execitive-target*') ? 'class=mm-active' : '' }}>ME Target Report </a></li>
                    @endhasanyrole
                @endhasanyrole

                @hasanyrole('super-admin|developer-admin|branch-manager|collection-manager')
                    @hasanyrole('super-admin|developer-admin|branch-manager|collection-manager')
                        <li><a href="/admin/reports/branch-daily-report" {{ request()->is('*/reports/branch-daily-report*') ? 'class=mm-active' : '' }}>Executive Report </a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|branch-manager')
                        <li><a href="/admin/reports/scheme-report-by-branch" {{ request()->is('*/reports/scheme-report-by-branch*') ? 'class=mm-active' : '' }}>Scheme Report</a></li>
                        <li><a href="/admin/reports/collection-incomplete-customers" {{ request()->is('*/reports/collection-incomplete-customers*') ? 'class=mm-active' : '' }}>Collection <br> Incomplete Customers</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|branch-manager')
                        <li><a href="/admin/reports/collection-complete-customers" {{ request()->is('*/reports/collection-complete-customers*') ? 'class=mm-active' : '' }}>Collection <br> Completed Customers</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|branch-manager')
                        <li><a href="/admin/reports/stop-customers-report" {{ request()->is('*/reports/stop-customers-report*') ? 'class=mm-active' : '' }}>Stop Customer List </a></li>
                        <li><a href="/admin/reports/collection-amount-executive" {{ request()->is('*/reports/collection-amount-executive*') ? 'class=mm-active' : '' }}>Amount Transfer Executive </a></li>
                    @endhasanyrole
                @endhasanyrole
                @hasanyrole('super-admin|developer-admin|branch-manager|marketing-executive|collection-executive')
                    @hasanyrole('super-admin|developer-admin|branch-manager|marketing-executive')
                        <li><a href="/admin/reports/daily-report-by-executive" {{ request()->is('*/reports/daily-report-by-executive*') ? 'class=mm-active' : '' }}>Daily Report By ME</a></li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|branch-manager|collection-executive')
                        <li><a href="/admin/reports/daily-report-by-collection" {{ request()->is('*/reports/daily-report-by-collection*') ? 'class=mm-active' : '' }}>Daily Report By CE</a> </li>
                    @endhasanyrole
                    @hasanyrole('super-admin|developer-admin|branch-manager')
                        <li><a href="/admin/reports/cash-collection" {{ request()->is('*/reports/cash-collection*') ? 'class=mm-active' : '' }}>Cash collection By ME</a> </li>
                    @endhasanyrole
                </ul>
            </li>
        @endhasanyrole
    </ul>
</div><!-- END: Sidebar-->
