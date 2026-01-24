<?php
namespace App\Repositories;
 use App\Models\product;
 use Illuminate\Http\Request;

class ProductRepo implements ProductRepoInterface
{

     public function index(Request $request)
     {
       return product::all();
     }
    public function store($data)
    {
         Product::create($data);
    }
    public function update($data,$id)
    {
        $product=Product::find($id);
         $product->update($data);
    }
    public function show($id)
    {

    }
    public function delete($id)
    {
       Product::find($id)->delete();
    }
}
