<?php

namespace App\Http\Controllers\Admin\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Warehouse\Category;
use App\Models\Warehouse\Product;
use App\Repositories\interfaces\Warehouse\ProductInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productInterface;

    public function __construct(ProductInterface $productInterface)
    {
        $this->middleware(['role:super-admin|developer-admin|store-admin|delivery-boy|marketing-executive|collection-executive']);
        $this->productInterface = $productInterface;
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
                $product = $this->productInterface->listProducts();
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
                return DataTables::of($product)
                    ->addIndexColumn()
                    ->addColumn('category_id', function ($row) {
                        return $row->category->name;
                    })
                    ->addColumn('action', function ($row) use($userRole){
                        if ($userRole == 'super-admin'  || $userRole == 'developer-admin' || $userRole == 'store-admin') {
                            $btn = '
                            <a href="products/' . $row->id . '" class="view btn btn-primary btn-floating btn-sm">
                            <i class="la la-eye"></i>
                        </a>
                            <a href="products/' . $row->id . '/edit" class="edit btn btn-info btn-floating btn-sm">
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
            return view('backend.warehouse.products.list-products');
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
        $categories = Category::get();
        return view('backend.warehouse.products.create-product', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'product_code' => 'required|unique:products,product_code',
            'name' => 'required|unique:products,name',
            'mrp' => 'required',
            'product_image' => 'required',
        ]);
        $slug = Str::slug($request->name,'-');
        $request['slug'] = $slug;
       if($request->status == 'on'){
              $request['status'] = 1;
       }
         else{
                  $request['status'] = 0;
         }
        try{
            $product = $this->productInterface->createProduct($request);
            return redirect()->route('products.index')->with('success', 'Product created successfully');
        }
        catch(Exception $e){
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
        $product = $this->productInterface->getProductById($id);
        return view('backend.warehouse.products.show-product', compact('product'));
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
        $product = $this->productInterface->getProductById($id);
        $categories = Category::get();
        return view('backend.warehouse.products.create-product', compact('product', 'categories'));
        //
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
            'product_code' => 'required|unique:products,product_code,'.$id,
              'name' => 'required|unique:products,name,'.$id,
            'mrp' => 'required',
        ]);
        $slug = Str::slug($request->name,'-');
        $request['slug'] = $slug;
       if($request->status == 'on'){
              $request['status'] = 1;
       }
         else{
                  $request['status'] = 0;
         }
        try{
            $product = $this->productInterface->updateProduct($request,$id);
            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        }
        catch(Exception $e){
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
        $delete = Product::destroy($id);
    }

}
