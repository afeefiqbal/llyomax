<?php

namespace App\Repositories\interfaces\Customer;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface CustomerCollectionInterface extends RepositoryInterface
{
    public function listCustomers();
    public function listexecutiveCustomers(Int $id);
    public function stopCustomerScheme(Request $args);
    public function restartCustomerScheme(Request $args);
    // public function customerSchemeRegister(Request $args);
    public function updateCustomerCollection(Request $args,$id);
    public function listmarketingexecutiveCustomers(Int $id);
    public function listbranchmanagerCustomers(Int $id);
}
