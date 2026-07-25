<?php

namespace App\Http\Controllers\OrderAndProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProduct;
use App\Http\Requests\UpdateProduct;
use App\Jobs\UpdateProductDiscount;
use App\Models\product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService=$productService;
    }

    public function index(Request $request)
    {

        return $this->productService->index($request);
    }
    public function store(AddProduct $request)
    {
        return $this->productService->store($request);
    }
    //User Circuit breaker
    //retry(3,1000)  دي معانها لو فشل يحاول لحد 3 مرات فرق بين كل مرة 1000 ملي سانكند ثانية يعين

    public function sendData(Request $request)
    {
        if(cache()->get('SERVICE-2004-DOWN') ===true)
        {
            return response()->json([
                'message'=>'Service is down now ',
                'code'=>503,
                'status'=>'Failed'
            ]);
        }
        try{
            $data=$request->all();

            $response = Http::acceptJson()->withHeaders([
                'service-key'=>env('SERVICE_KEY')
            ])->timeout(2)
                ->retry(3,1000)
                ->throw()
                ->post(env('BASE_URL').'/data/',$data);
             if($response->status() == 200){
                cache()->forget('SERVICE-2004-FAIL-COUNT');
                return response()->json([
                    'data'=>$response->json(),
                ]);
            }
        }
        catch(\Throwable $e){
            cache()->put("SERVICE-2004-FAIL-COUNT", cache()->get("SERVICE-2004-FAIL-COUNT", 0) + 1, 60);
            if(cache()->get('SERVICE-2004-FAIL-COUNT') >=3)
            {
                cache()->put('SERVICE-2004-DOWN',true,30);
                return response()->json([
                'message'=>'Service is down now ',
                'code'=>503,
                'status'=>'Failed'
            ]);
            }
        }

    }
    public function update(UpdateProduct $request,$id)
    {
        return $this->productService->update($request,$id);

    }

    public function destroy($id)
    {
        return $this->productService->destroy($id);
    }
    public function updateProductDiscount(Request $request)
    {





    








        $discount=$request->discount;
        UpdateProductDiscount::dispatch();
         return response()->json(['message' => 'Discount update queued successfully!']);

    }
}
