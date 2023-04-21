<?php

namespace App\Repositories\interfaces\Customer;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface CustomerInterface extends RepositoryInterface
{
    public function listCustomers();
    public function listexecutiveCustomers(Int $id);
    public function listmarketingexecutiveCustomers(Int $id);
    public function listbranchmanagerCustomers(Int $id);
    public function getCustomer(Int $id);
    public function createCustomer(Request $args);
    public function customerSchemeRegister(Request $args);
    public function updateCustomer(Request $args,$id);
    public function deleteCustomer(Int $id);
    public function listofficeadministratorCustomers(Int $id);
}
