<?php

namespace App\Services;

use App\Http\Resources\BasicResources;
use App\Jobs\Testjob;
use App\Models\Product;
use App\Repositories\ProductRepoInterface;
use App\Trait\DataFilter;
use App\Trait\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    use MediaTrait,DataFilter;

    protected $productInterface;

    public function __construct(ProductRepoInterface $productRepoInterface)
    {
        $this->productInterface = $productRepoInterface;
    }
    public function index(Request $request)
    {
       $products=Product::all();

        return BasicResources::make(null)->result($products);
    }
    public function store($request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request->image, 'products/images');
        }
        $this->productInterface->store($data);
        return BasicResources::make(null)->result($data);
    }
    public function update($request, $id)
    {
        $data = $request->validated();
        $product = Product::find($id);
        if ($request->hasFile('image')) {
            $data['image'] = $this->updatemedia($product->image, 'products/images', $request->image);
        }
        $this->productInterface->update($data, $id);

        // delay in job
        /*
       delay(5);  this mean it started after 5 seconds

        /*
          on queue ->  pariority controlll  بتحكم بيها ف الاولوية
        Testjob::dispatch()->delay(5)->->onQueue('');   (high,low)

        ** for controller which type of job start by this command line
          php artisan queue:work --queue=high   or low
         */
        Testjob::dispatch()->delay(5)->onQueue('high');
//        Testjob::dispatch()->delay(5);// 5 seconds


        return BasicResources::make(null)->result($data);
    }

    public function destroy($id)
    {
        $item = Product::find($id);
        if ($item->image) {
            $this->delete($item->image);
        }
        $this->productInterface->delete($id);
        return BasicResources::make(null)->delete();
    }
}
