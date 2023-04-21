<?php

use App\Http\Controllers\Admin\Accounts\AccountRportController;
use App\Http\Controllers\Admin\Accounts\AdvanceController;
use App\Http\Controllers\Admin\Accounts\ExpenseBillController;
use App\Http\Controllers\Admin\Accounts\ExpenseController;
use App\Http\Controllers\Admin\Accounts\ExtraBonusController;
use App\Http\Controllers\Admin\Accounts\RentAllowanceController;
use App\Http\Controllers\Admin\Accounts\SalaryIncentiveController;
use App\Http\Controllers\Admin\Accounts\SalaryIndividualController;
use App\Http\Controllers\Admin\Accounts\SalesCommisionController;
use App\Http\Controllers\Admin\Accounts\TransportationAllowanceController;
use App\Http\Controllers\Admin\Accounts\WeeklyGiftController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Executive\ExecutiveReportSubmissionController;
use App\Http\Controllers\Admin\Master\AreaController;
use App\Http\Controllers\Admin\Master\BranchController;
use App\Http\Controllers\Admin\Settings\RoleController;
use App\Http\Controllers\Admin\Settings\UserController;
use App\Http\Controllers\Admin\Master\ManagerController;
use App\Http\Controllers\Admin\Master\OfficeAdminController;
use App\Http\Controllers\Admin\Office_Admin\StaffController;
use App\Http\Controllers\Admin\Executive\ExecutiveController;
use App\Http\Controllers\Admin\Office_Admin\AttendanceController;
use App\Http\Controllers\Admin\Executive\ExecutiveLeaveController;
use App\Http\Controllers\Admin\Report\BranchTargetReportController;

use App\Http\Controllers\Admin\Report\CashCollectionController;
use App\Http\Controllers\Admin\Report\CollectionAmountReportController;
use App\Http\Controllers\Admin\Report\CollectionCompleteCustomersController;
use App\Http\Controllers\Admin\Report\CollectionIncompleteCustomerController;
use App\Http\Controllers\Admin\Report\DailyBranchReportController;
use App\Http\Controllers\Admin\Report\DailybranchSchemeReportController;
use App\Http\Controllers\Admin\Report\DailyReportByCollectionController;
use App\Http\Controllers\Admin\Report\DailyReportByExecutiveController;
use App\Http\Controllers\Admin\Report\ExecutiveAmountTransferReportController;
use App\Http\Controllers\Admin\Report\ExecutiveReportController;
use App\Http\Controllers\Admin\Report\ManagerReportController;
use App\Http\Controllers\Admin\Report\MarketingExecutiveTargetReportController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Report\SchemeReportController;
use App\Http\Controllers\Admin\Report\StopCustomersController;
use App\Http\Controllers\Admin\Branch\AmountTransferDetailController;
use App\Http\Controllers\Admin\Branch\AmountTransferExecutiveController;
use App\Http\Controllers\Admin\Branch\CollectionExecutiveController;
use App\Http\Controllers\Admin\Branch\LuckyDrawController;
use App\Http\Controllers\Admin\Branch\MarketingExecutiveTargetController;
use App\Models\Executive\ExecutiveReportSubmission;
use Spatie\Permission\Contracts\Role;
use App\Http\Controllers\Admin\Branch\SchemeController;
use App\Http\Controllers\Admin\Customer\CustomerCollectionController;
use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Branch\BranchTargetController;
use App\Http\Controllers\Admin\Customer\CustomerDashBoardController;
use App\Http\Controllers\Admin\Branch\BranchSchemeController;
use App\Http\Controllers\Admin\Branch\SchemeTargetController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\Report\OrderReportController;
use App\Http\Controllers\Admin\Report\StockReportController;
use App\Http\Controllers\Admin\Warehouse\AssignDeliveryBoyController;
use App\Http\Controllers\Admin\Warehouse\CategoryController;
use App\Http\Controllers\Admin\Warehouse\DeliveryBoyController;
use App\Http\Controllers\Admin\Warehouse\OrderController;
use App\Http\Controllers\Admin\Warehouse\ProductController;
use App\Http\Controllers\Master\ClusterController;
use App\Http\Controllers\Master\DistrictController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WarehouseAdminController;
use App\Models\Accounts\ExpenseBill;
use App\Models\Accounts\TransportationAllowance;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::get('/loginnew', function () {
    return view('auth.login');
});
Route::get('/admin', function () {
    return redirect('login');
});
Route::prefix('customer')->middleware(['auth'])->middleware(['auth','role:customer'])->group(function () {
    Route::resource('customer-dashboard', CustomerDashBoardController::class);
    Route::get('dashboard', [CustomerDashBoardController::class, 'dashboard']);

});
Route::prefix('warehouse')->middleware(['auth'])->middleware(['auth','role:store-admin|delivery-boy'])->group(function () {
    Route::resource('dashboard',DashboardController::class);;
    Route::resource('warehouse-admin', WarehouseAdminController::class);

});


// Route::prefix('warehouse')->middleware(['auth'])->middleware(['auth','role:store-admin'])->group(function () {
//     return redirect('admin/reports/collection-complete-customers');
// });
Route::prefix('admin')->middleware(['auth'])->middleware(['auth','role:super-admin|store-admin|developer-admin|marketing-manager|collection-manager|branch-manager|collection-executive|marketing-executive|office-administrator|customer|delivery-boy'])->group(function () {
    Route::post('/get-product-price', [CustomerController::class, 'getProductPrice'])->name('getProductPrice');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/invoice',function(){
        return view('invoice');
    });
    Route::get('print-invoice', [CustomerController::class, 'printInvoice'])->name('printInvoice');
    Route::get('print/{id}',[CustomerController::class, 'print'])->name('print');
    Route::resource('settings', SettingController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-executives-list', [DashboardController::class, 'getExecutivesList'])->name('get-executives-list');
    Route::get('get-collection-data', [DashboardController::class, 'getCollectionData']);
    Route::get('get-attendence-data', [DashboardController::class, 'getAttendenceData']);
    Route::get('get-branch-target', [DashboardController::class, 'getBranchTarget']);
    Route::get('get-scheme-data', [DashboardController::class, 'getSchemeData']);
    Route::post('getCustomer', [OrderController::class, 'getCustomer']);
    Route::post('set-session-cart', [OrderController::class, 'setSessionCart']);

    Route::post('get-product', [OrderController::class, 'getProduct']);
    Route::post('get-product-array', [OrderController::class, 'getProductArray']);
    Route::post('order/customer', [OrderController::class, 'newCustomer']);
    Route::post('get-branches', [BranchController::class, 'getBranches']);
    Route::POST('get-branches-by-scheme/', [BranchSchemeController::class, 'getBranchesByScheme']);
    Route::POST('get-branchs/', [WeeklyGiftController::class, 'getBranchs']);
    Route::POST('get-customers/', [WeeklyGiftController::class, 'getCustomers']);
    Route::POST('status-changer', [AmountTransferDetailController::class, 'statusChanger']);

    Route::prefix('master')->middleware(['auth','role:super-admin|developer-admin|marketing-manager|collection-manager|branch-manager|collection-executive|marketing-executive'])->group(function () {
        Route::resource('managers', ManagerController::class);
        Route::resource('branches', BranchController::class);
        Route::resource('schemes', SchemeController::class);
        Route::get('branch-id', [BranchController::class, 'id']);
        Route::resource('office-admins', OfficeAdminController::class);
        Route::resource('areas', AreaController::class);
        Route::resource('districts', DistrictController::class);
        Route::resource('clusters', ClusterController::class);
        Route::resource('branch-assigning', BranchSchemeController::class);
        Route::resource('executives', ExecutiveController::class);

        Route::get('area-id', [AreaController::class, 'id']);
    });
    Route::prefix('executive')->middleware(['auth','role:super-admin|developer-admin|collection-manager|collection-executive|marketing-executive|branch-manager'])->group(function () {
        Route::resource('report-submission', ExecutiveReportSubmissionController::class);
        Route::post('get-schemes', [ExecutiveReportSubmissionController::class, 'getSchemes']);
        Route::post('/get-customer-details', [ExecutiveReportSubmissionController::class, 'getCustomerDetails']);
        Route::post('get-customer', [ExecutiveReportSubmissionController::class, 'getCustomer']);
        Route::get('get-data', [ExecutiveController::class, 'getData']);
        Route::resource('leave-form', ExecutiveLeaveController::class);
        Route::post('leave-form-approve/{id}', [ExecutiveLeaveController::class, 'leave_status']);
        Route::resource('report-submission', ExecutiveReportSubmissionController::class);
        Route::resource('amount-transfer-executive', AmountTransferExecutiveController::class);
    });
    Route::prefix('office-admin')->middleware(['auth','role:super-admin|developer-admin|office-administrator|branch-manager'])->group(function () {
        Route::resource('staffs', StaffController::class);
        Route::get('get-manager', [StaffController::class, 'manager']);
        Route::get('staff-id', [StaffController::class, 'staff_id']);
        Route::resource('attendances', AttendanceController::class);
        Route::get('get-staff', [AttendanceController::class, 'getStaff']);
        Route::get('attendances/{date}/{branch}', [AttendanceController::class, 'show']);
        Route::get('attendances/edit-view/{date}/{branch}', [AttendanceController::class, 'editView']);
        Route::post('attendances/update/{date}/{branch}', [AttendanceController::class, 'update']);
        Route::delete('attendances/delete/{date}', [AttendanceController::class, 'destroy']);
    });
    Route::prefix('customer')->middleware(['auth','role:super-admin|developer-admin|marketing-manager|collection-manager|office-administrator|branch-manager|collection-executive|marketing-executive|customer'])->group(function () {
        Route::post('get-schemes-area', [CustomerController::class, 'getData']);
        Route::post('get-scheme-report', [CustomerController::class, 'getSchemeReport']);
        Route::post('get-otp', [CustomerController::class, 'getOTP']);
        Route::post('get-edit-otp', [CustomerController::class, 'getEditOTP']);
        Route::post('assignce', [CollectionExecutiveController::class, 'assignce']);
        Route::post('validate-otp', [CustomerController::class, 'validateOTP']);
        Route::post('delete-customer-scheme', [CustomerController::class, 'DeleteCustomerScheme']);
        Route::post('edit-customer/{id}', [CustomerController::class, 'updateCustomer']);
        Route::get('scheme-register/{id}', [CustomerController::class, 'schemeRegister']);
        Route::resource('customers', CustomerController::class);
        Route::resource('customer-collection', CustomerCollectionController::class);
        Route::post('stop-customer-scheme', [CustomerCollectionController::class, 'stopCustomerScheme']);
        Route::post('restart-customer-scheme', [CustomerCollectionController::class, 'restartCustomerScheme']);
    });

    Route::prefix('branch')->middleware(['auth','role:super-admin|developer-admin|branch-manager|marketing-manager|collection-manager|branch-manager|collection-executive|marketing-executive'])->group(function () {
        Route::prefix('luckydraws')->group(function () {
            Route::get('eligible-customers', [LuckyDrawController::class, 'eligibleCustomers']);
            Route::resource('winners-list', LuckyDrawController::class);
        });

        Route::post('get-branch-schemes', [CollectionExecutiveController::class, 'getBranchSchemes']);
        Route::post('get-customer', [CollectionExecutiveController::class, 'getCustomers']);
        Route::post('get-area', [CollectionExecutiveController::class, 'getAreas']);
        Route::post('get-executive', [CollectionExecutiveController::class, 'getExecutives']);
        Route::get('get-executive', [CollectionExecutiveController::class, 'getExecutives']);
        Route::get('get-schemes', [LuckyDrawController::class, 'getSchemes']);
    Route::get('get-week', [LuckyDrawController::class, 'getWeek']);
    Route::get('get-branch', [LuckyDrawController::class, 'getBranch']);
        Route::get('get-customers', [LuckyDrawController::class, 'getCustomers']);
        Route::get('get-select-customers', [LuckyDrawController::class, 'getSelectCustomers']);
        Route::resource('collection-executives', CollectionExecutiveController::class);
        Route::post('get-branch-area', [MarketingExecutiveTargetController::class, 'getBranchArea']);
        Route::post('get-maraketing-executive', [MarketingExecutiveTargetController::class, 'getMarketingExecutives']);
        Route::resource('marketing-executive-targets', MarketingExecutiveTargetController::class);
        Route::resource('branch-targets', BranchTargetController::class);
        Route::resource('scheme-targets', SchemeTargetController::class);
        Route::post('branch-schemes', [BranchTargetController::class, 'getBranchSchemes']);
        Route::resource('amount-transfer', AmountTransferDetailController::class);

    });
    Route::prefix('settings')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::get('list-roles', [RoleController::class, 'listRoles']);
    });
    Route::prefix('warehouse')->middleware(['auth','role:super-admin|developer-admin|store-admin|delivery-boy|marketing-executive|collection-executive'])->group(function () {

        // Route::post('print/{$id}', [OrderController::class, 'printInvoice'])->name('print');
        Route::resource('delivery-executives', DeliveryBoyController::class);
        Route::resource('products', ProductController::class);
        Route::resource('categories',CategoryController::class);
        Route::resource('orders',OrderController::class);
        Route::resource('assigning-delivery-boys',AssignDeliveryBoyController::class);
        Route::post('get-order-status',[AssignDeliveryBoyController::class,'getOrderStatus']);
        Route::post('update-order-status',[AssignDeliveryBoyController::class,'updateOrderStatus']);

        Route::post('get-customers', [AssignDeliveryBoyController::class, 'getCustomers']);
        Route::resource('collection-completed',CollectionCompleteCustomersController::class);
    });
    Route::prefix('reports')->middleware(['auth','role:super-admin|developer-admin|marketing-manager|collection-manager|branch-manager|collection-executive|marketing-executive|store-admin'])->group(function () {
        Route::get('lucky-draw-reports', [ReportController::class, 'luckyDraw']);
        Route::resource('account-reports', AccountRportController::class);
        Route::resource('stock-reports',StockReportController::class);
        Route::resource('order-reports',OrderReportController::class);
        Route::get('staff-attendance-reports', [ReportController::class, 'staffAttendance']);
        Route::get('staff-attendance-details/{branch_id}/{date}', [ReportController::class, 'showStaffAttendence']);
        Route::get('get-data-scheme-area', [DailyReportByExecutiveController::class, 'getData']);
        Route::get('get-executive', [DailyReportByExecutiveController::class, 'getExecutives']);
        Route::resource('manager-reports', ManagerReportController::class);
        Route::resource('executive-reports', ExecutiveReportController::class);
        Route::resource('daily-report-by-executive', DailyReportByExecutiveController::class);
        Route::get('get-data', [CollectionIncompleteCustomerController::class, 'getData']);
        Route::get('get-scheme-date', [CollectionIncompleteCustomerController::class, 'getSchemeDate']);
        Route::resource('collection-incomplete-customers', CollectionIncompleteCustomerController::class);
        Route::resource('collection-complete-customers', CollectionCompleteCustomersController::class);
        Route::resource('cash-collection', CashCollectionController::class);
        Route::resource('daily-report-by-collection', DailyReportByCollectionController::class);
        Route::get('get-collection-executive', [DailyReportByCollectionController::class,'getExecutives']);
        Route::get('get-branch-scheme', [CashCollectionController::class, 'getData']);
        Route::resource('scheme-report-by-branch', SchemeReportController::class);
        Route::resource('branch-report-by-marketing', DailyBranchReportController::class);
        Route::resource('branch-target', BranchTargetReportController::class);
        Route::resource('collection-amount', CollectionAmountReportController::class);
        Route::resource('branch-daily-report', DailybranchSchemeReportController::class);
        Route::get('get-scheme', [DailybranchSchemeReportController::class, 'getData']);
        Route::resource('stop-customers-report', StopCustomersController::class);
        Route::resource('collection-amount-executive', ExecutiveAmountTransferReportController::class);
        Route::resource('marketing-execitive-target', MarketingExecutiveTargetReportController::class);
    });
    Route::prefix('accounts')->middleware(['auth','role:developer-admin'])->group(function () {
        Route::resource('expense', ExpenseController::class);
        Route::resource('advance', AdvanceController::class);
        Route::resource('weekly-gifts', WeeklyGiftController::class);
        Route::resource('expense-bills', ExpenseBillController::class);
        Route::resource('rent-allowance', RentAllowanceController::class);
        Route::resource('transportation-allowances', TransportationAllowanceController::class);
        Route::resource('sales-commisions', SalesCommisionController::class);
        Route::resource('salary-of-individuals', SalaryIndividualController::class);
        Route::resource('extra-bonus', ExtraBonusController::class);
        Route::resource('salary-incentives', SalaryIncentiveController::class);
        Route::get('get-reports',[AccountRportController::class, 'getReports']);

    });
});
require __DIR__ . '/auth.php';
