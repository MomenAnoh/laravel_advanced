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
        $this->productService = $productService;
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
        if (cache()->get('SERVICE-2004-DOWN') === true) {
            return response()->json([
                'message' => 'Service is down now ',
                'code' => 503,
                'status' => 'Failed'
            ]);
        }
        try {
            $data = $request->all();

            $response = Http::acceptJson()->withHeaders([
                'service-key' => env('SERVICE_KEY')
            ])->timeout(2)
                ->retry(3, 1000)
                ->throw()
                ->post(env('BASE_URL') . '/data/', $data);
            if ($response->status() == 200) {
                cache()->forget('SERVICE-2004-FAIL-COUNT');
                return response()->json([
                    'data' => $response->json(),
                ]);
            }
        } catch (\Throwable $e) {
            cache()->put("SERVICE-2004-FAIL-COUNT", cache()->get("SERVICE-2004-FAIL-COUNT", 0) + 1, 60);
            if (cache()->get('SERVICE-2004-FAIL-COUNT') >= 3) {
                cache()->put('SERVICE-2004-DOWN', true, 30);
                return response()->json([
                    'message' => 'Service is down now ',
                    'code' => 503,
                    'status' => 'Failed'
                ]);
            }
        }
    }
    public function update(UpdateProduct $request, $id)
    {
        return $this->productService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->productService->destroy($id);
    }

    public function updateProductDiscount(Request $request)
    {
        $discount = $request->discount;
        UpdateProductDiscount::dispatch();
        return response()->json(['message' => 'Discount update queued successfully!']);
    }
    

   public function payWithJazzCash(Request $request)
{
    $merchantId = env('JAZZCASH_MERCHANT_ID');
    $password   = env('JAZZCASH_PASSWORD');
    $salt       = env('JAZZCASH_INTEGRITY_SALT');

    $txnRefNo = 'T' . now()->format('YmdHis');
    $dateTime = now()->format('YmdHis');
    $expiryDateTime = now()->addMinutes(15)->format('YmdHis');
    $amount = "10000";
    $data = [



     "pp_Amount"            => "100",
    "pp_BankID"            => "",
    "pp_BillReference"     => "billRef185",
    "pp_CNIC"              => "345678",
    "pp_Description"       => "product description",
    "pp_Language"          => "EN",
    "pp_MerchantID"        => $merchantId,
    "pp_MobileNumber"      => "03123456789",
    "pp_Password"          => $password,
    "pp_ProductID"         => "",
    "pp_SubMerchantID"     => "",
    "pp_TxnCurrency"       => "PKR",
    "pp_TxnDateTime"       => $dateTime,
    "pp_TxnExpiryDateTime" => $expiryDateTime,
    "pp_TxnRefNo"          => $txnRefNo,
    "ppmpf_1"              => "",
    "ppmpf_2"              => "",
    "ppmpf_3"              => "",
    "ppmpf_4"              => "",
     
    ];

    ksort($data);

    $hashValues = [];

    foreach ($data as $key => $value) {
        if ($key == 'pp_SecureHash') {
            continue;
        }
        if ($value !== '' && $value !== null) {
            $hashValues[] = $value;
        }
    }

    $hashString = $salt . '&' . implode('&', $hashValues);
    $secureHash = hash_hmac('sha256', $hashString, $salt);

    $data['pp_SecureHash'] = strtoupper($secureHash);

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
    ])->post(
        'https://onlinepayments.jazzcash.com.pk/payment-orchestrator/api/v2/rest/payments/m-wallet',
        $data
    );

    return response()->json([
        'hash_string' => $hashString,
        'request'     => $data,
        'response'    => $response->json(),
    ]);
}
public function payWithCard(Request $request)
{
    $merchantId = env('JAZZCASH_MERCHANT_ID');
    $password   = env('JAZZCASH_PASSWORD');
    $salt       = env('JAZZCASH_INTEGRITY_SALT');
    $milliTime   = sprintf("%03d", (microtime(true) * 1000) % 1000); 
    $uniqueRefNo = "TRN" . now()->format('YmdHis') . $milliTime; 
    $dateTime       = now()->format('YmdHis');
    $expiryDateTime = now()->addDay()->format('YmdHis'); 
    $amount         = 100 * 100; 
    $params = [
        "pp_Version"           => '1.1',
        "pp_TxnType"           => "MPAY", 
        "pp_Language"          => "EN",
        "pp_MerchantID"        => $merchantId,
        "pp_Password"          => $password,
        "pp_TxnRefNo"          => $uniqueRefNo,
        "pp_Amount"            => $amount,
        "pp_TxnCurrency"       => "PKR",
        "pp_TxnDateTime"       => $dateTime,
        "pp_BillReference"     => "billref001",
        "pp_Description"       => "Test transaction description",
        "pp_TxnExpiryDateTime" => $expiryDateTime,
        "pp_ReturnURL"         => env('JAZZCASH_RETURN_URL'),
        "pp_SubMerchantID"     => "",
        "pp_BankID"            => "",
        "pp_ProductID"         => "",
        "ppmpf_1"              => "", 
        "ppmpf_2"              => "", 
        "ppmpf_3"              => "", 
        "ppmpf_4"              => "", 
        "ppmpf_5"              => "", 
    ];
    ksort($params);
    $sortedString = $salt;
    foreach ($params as $key => $value) {
        if ($value !== null && $value !== "") {
            $sortedString .= "&" . $value;
        }
    }

    $secureHash = hash_hmac('sha256', $sortedString, $salt);
    $params['pp_SecureHash'] = strtoupper($secureHash);

    $postURL = "https://onlinepayments.jazzcash.com.pk/payment-orchestrator/CustomerPortal/transactionmanagement/merchantform";

    $htmlForm = '<form id="jazzcash_card_form" method="post" action="' . $postURL . '">';
    foreach ($params as $key => $value) {
        $htmlForm .= '<input type="hidden" name="' . $key . '" value="' . e($value) . '">';
    }
    $htmlForm .= '</form>';
    $htmlForm .= '<script>document.getElementById("jazzcash_card_form").submit();</script>';

    return response($htmlForm);
}

}