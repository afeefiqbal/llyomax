<?php

namespace App\Repositories\interfaces\Branch;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface SchemeInterface extends RepositoryInterface
{
    public function listSchemes();
    public function listBranchSchemes($id);
    public function getScheme(Int $id);
    public function createScheme(Request $args);
    public function updateScheme(Request $args,$id);
    public function deleteScheme(Int $id);

}
