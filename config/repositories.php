<?php

return [
	\App\Repositories\User\UserInterface::class => \App\Repositories\User\UserRepository::class,
	\App\Repositories\interfaces\ManagerInterface::class => \App\Repositories\Master\ManagerRepository::class,
	\App\Repositories\interfaces\BranchInterface::class => \App\Repositories\Master\BranchRepository::class,
	\App\Repositories\interfaces\Branch\SchemeInterface::class => \App\Repositories\Branch\SchemeRepository::class,
	\App\Repositories\interfaces\OfficeAdminInterface::class => \App\Repositories\Master\OfficeAdminRepository::class,
	\App\Repositories\interfaces\ExecutiveInterface::class => \App\Repositories\Executive\ExecutiveRepository::class,
	\App\Repositories\interfaces\ExecutiveLeaveInterface::class => \App\Repositories\Executive\ExecutiveLeaveRepository::class,
	\App\Repositories\interfaces\Executive\ExecutiveReportSubmissionInterface::class => \App\Repositories\Executive\ExecutiveReportSubmissionRepository::class,
	\App\Repositories\interfaces\office_admin\StaffInterface::class => \App\Repositories\office_admin\StaffRepository::class,
	\App\Repositories\interfaces\Master\AreaInterface::class => \App\Repositories\Master\AreaRepository::class,
	\App\Repositories\interfaces\Customer\CustomerInterface::class => \App\Repositories\Customer\CustomerRepository::class,
	\App\Repositories\interfaces\Branch\CollectionExecutiveInterface::class => \App\Repositories\Branch\CollectionExecutiveRepository::class,
	\App\Repositories\interfaces\Branch\MarketingExecutiveTargetInterface::class => \App\Repositories\Branch\MarketingExecutiveTargetRepository::class,
	\App\Repositories\interfaces\Branch\BranchTargetInterface::class => \App\Repositories\Branch\BranchTargetRepository::class,
	\App\Repositories\interfaces\Branch\AmountTransferDetailInterface::class => \App\Repositories\Branch\AmountTransferDetailRepository::class,
	\App\Repositories\interfaces\office_admin\AttendanceInterface::class => \App\Repositories\office_admin\AttendanceRepository::class,
	\App\Repositories\interfaces\Settings\RoleInterface::class => \App\Repositories\Settings\RoleRepository::class,
	\App\Repositories\interfaces\Settings\UserInterface::class => \App\Repositories\Settings\UserRepository::class,
	\App\Repositories\Report\ManagerReportInterface::class => \App\Repositories\Report\ManagerReportRepository::class,
	\App\Repositories\Report\ExecutiveReportInterface::class => \App\Repositories\Report\ExecutiveReportRepository::class,
	\App\Repositories\interfaces\Customer\CustomerCollectionInterface::class => \App\Repositories\Customer\CustomerCollectionRepository::class,
	\App\Repositories\Branch\LuckyDrawInterface::class => \App\Repositories\Branch\LuckyDrawRepository::class,
	\App\Repositories\Report\LuckyDrawReportInterface::class => \App\Repositories\Report\LuckyDrawReportRepository::class,
	\App\Repositories\Report\ReportInterface::class => \App\Repositories\Report\ReportRepository::class,
	\App\Repositories\Master\CLusterInterface::class => \App\Repositories\Master\CLusterRepository::class,
	\App\Repositories\Master\DistrictInterface::class => \App\Repositories\Master\DistrictRepository::class,
	\App\Repositories\Branch\BranchSchemeInterface::class => \App\Repositories\Branch\BranchSchemeRepository::class,
	\App\Repositories\interfaces\Warehouse\DeliveryBoyInterface::class => \App\Repositories\Warehouse\DeliveryBoyRepository::class,
	\App\Repositories\interfaces\Accounts\ExpenseInterface::class => \App\Repositories\Accounts\ExpenseRepository::class,
	\App\Repositories\interfaces\Accounts\AdvanceInterface::class => \App\Repositories\Accounts\AdvanceRepository::class,
	\App\Repositories\interfaces\Accounts\WeeklyGiftInterface::class => \App\Repositories\Accounts\WeeklyGiftRepository::class,
	\App\Repositories\interfaces\Accounts\ExpenseBillInterface::class => \App\Repositories\Accounts\ExpenseBillRepository::class,
	\App\Repositories\interfaces\Accounts\RentAllowanceInterface::class => \App\Repositories\Accounts\RentAllowanceRepository::class,
	\App\Repositories\interfaces\Accounts\TransportationAllowanceInterface::class => \App\Repositories\Accounts\TransportationAllowanceRepository::class,
	\App\Repositories\interfaces\Accounts\SalesCommisionInterface::class => \App\Repositories\Accounts\SalesCommisionRepository::class,
	\App\Repositories\interfaces\Accounts\SalaryIndividualInterface::class => \App\Repositories\Accounts\SalaryIndividualRepository::class,
	\App\Repositories\interfaces\Accounts\ExtraBonusInterface::class => \App\Repositories\Accounts\ExtraBonusRepository::class,
	\App\Repositories\interfaces\Accounts\SalaryIncentiveInterface::class => \App\Repositories\Accounts\SalaryIncentiveRepository::class,
	\App\Repositories\interfaces\Accounts\AccountReportInterface::class => \App\Repositories\Accounts\AccountReportRepository::class,
	\App\Repositories\interfaces\Warehouse\ProductInterface::class => \App\Repositories\Warehouse\ProductRepository::class,
	\App\Repositories\interfaces\Warehouse\CategoryInterface::class => \App\Repositories\Warehouse\CategoryRepository::class,
	\App\Repositories\interfaces\Warehouse\OrderInterface::class => \App\Repositories\Warehouse\OrderRepository::class,
	\App\Repositories\interfaces\Warehouse\AssignDeliveryBoyInterface::class => \App\Repositories\Warehouse\AsssignDeliveryBoyRepository::class,
	\App\Repositories\interfaces\Report\StockReportInterface::class => \App\Repositories\Report\StockReportRepository::class,
	\App\Repositories\interfaces\Report\OrderReportInterface::class => \App\Repositories\Report\OrderReportRepository::class,

];
