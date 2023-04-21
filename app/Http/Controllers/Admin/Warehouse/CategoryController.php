<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Warehouse\Category;
use App\Repositories\interfaces\Warehouse\CategoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    protected $categoryInterface;

    public function __construct(CategoryInterface $categoryInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|store-admin|delivery-boy|marketing-executive|collection-executive']);
        $this->categoryInterface = $categoryInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $category = $this->categoryInterface->listCategories();
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                return DataTables::of($category)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) use($userRole) {
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin' || $userRole == 'store-admin') {

                        $btn = '
                        <a href="categories/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
                            <i class="la la-pencil"></i>
                        </a>
                        <a data-id="' . $row->id . '" class="delete btn btn-danger btn-floating btn-sm">
                            <i class="la la-trash"></i>
                        </a>';
                        return $btn;
                        }
                    })
                    ->rawColumns(['action', 'branch'])
                    ->make(true);
            }
            return view('backend.warehouse.categories.list-categories');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('backend.warehouse.categories.create-category');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'cat_id' => 'required|unique:categories,cat_id,',
            'name' => 'required|unique:categories,name',
        ]);
        $slug = Str::slug($request->name,'-');
        $request['slug'] = $slug;
        try {
            $this->categoryInterface->createCategory($request);
            return redirect()->route('categories.index')->with('success', 'Category created successfully');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $category = $this->categoryInterface->getCategoryById($id);
            return view('backend.warehouse.categories.create-category', compact('category'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cat_id' => 'required|unique:categories,cat_id,' . $id,
            'name' => 'required|unique:categories,name,' . $id,
        ]);
        $slug = Str::slug($request->name,'-');
        $request['slug'] = $slug;
        try {
            return redirect()->route('categories.index')->with('success', 'Category updated successfully');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $e->getCode();
            $e->getMessage();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Category::destroy($id);
    }
}
