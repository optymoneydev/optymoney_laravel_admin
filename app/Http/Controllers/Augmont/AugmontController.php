<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\OrdersAugmontController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
Use App\Models\AugmontMerchant;
use Carbon\Carbon;
use DateTime;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Razorpay_Subscription;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AugmontController extends Controller
{
    public function checkToken() {
        $id=2;
        $data = AugmontMerchant::where('id',$id)->first(); 
        return $data;
    }

    public function merchantAuth() {
        $todayDate = Carbon::now()->format('Y-m-d');

        $token = $this->checkToken();
        if($token->token!=null || $token->token!="") {
            if($token->expiresAt != null) {
                if ($todayDate >= $token->expiresAt) {
                    $tokenRes = $this->generateToken($token->email, $token->password, $token);
                } else {
                    $tokenRes = $token->token;
                }
                
            } else {
                $tokenRes = $this->generateToken($token->email, $token->password, $token);
            }
        } else {
            if($token->expiresAt != null) {
                if ($todayDate > $token->expiresAt) {
                    $tokenRes = $this->generateToken($token->email, $token->password, $token);
                } else {
                    $tokenRes = $token->token;
                }
            } else {
                $tokenRes = $this->generateToken($token->email, $token->password, $token);
            }
        }
        return $tokenRes;
    }

    public function generateToken($uname, $pswd, $token) {
        $client = new Client(['verify' => false ]);
        try {
            $res = $client->request('POST', 
                env('AUG_URL').'merchant/v1/auth/login',[
                'form_params' => [
                    'email' => $uname,
                    'password' => $pswd,
                ]
            ]);
            $tokenRes = json_decode($res->getBody()->getContents());
            // return $tokenRes;
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse();
            $tokenRes = json_decode($responseBody->getBody()->getContents()); 
        }
        
        if($tokenRes->statusCode==401) {
            // $orderData['tokenStatus'] = $tokenRes->message;
            return $tokenRes->statusCode;
        } else {
            $token->token = $tokenRes->result->data->accessToken;
            $token->expiresAt = $tokenRes->result->data->expiresAt;
            $token->tokenType = $tokenRes->result->data->tokenType;
            $token->merchantId = $tokenRes->result->data->merchantId;
            $tokensave = $token->save();
            return $tokenRes->result->data->accessToken;
        }
        
    }

    public function getCity(Request $request) {
        $client = new Client(['verify' => false ]);
        $tokentype = "Bearer ";
        $authToken = $tokentype.$this->merchantAuth();
        $url = env('AUG_URL').'merchant/v1/master/cities?stateId='.$request->state.'&count=5&page=1';
        $headers = [
            'Content-Type' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => $authToken,
        ];
        $res = $client->request('GET', 
        env('AUG_URL').'merchant/v1/master/cities?stateId='.$request->state.'&count=100',[
            'headers' => $headers
        ]);
        $statusCode = $res->getStatusCode(); 
        // $this->assertEquals($statusCode,200,"actual value is not equals to expected");
        $content = $res->getBody()->getContents();
        return $content;
    }

    public function getAugmontId(Request $request) {
        $id = $request->session()->get('id');
        $data = Bfsi_user::where('pk_user_id',$id)->get(['augid']); 
        return $data;
    }

    public function orders(Request $request) {
        $id = $request->session()->get('id');
        $data = AugmontOrders::where('user_id','$id')->get(); 
        return $data;
    }

    public function api_orders(Request $request) {
        $id = $request->uid;
        $data = AugmontOrders::where('user_id', $id)->get(); 
        return $data;
    }

    public function allOrders(Request $request) {
        $data = AugmontOrders::orderBy('id', 'DESC')->get(); 
        $clientData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
            ->whereNotNull('bfsi_user.augid')
            ->get(['bfsi_user.pk_user_id', 'bfsi_user.login_id', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_users_details.pan_number'])
            ->sortByDesc("pk_user_id");
        $data1['orders'] = $data;
        $data1['clients'] = $clientData;
        return $data1;
    }

    public function sipList(Request $request) {
        $id = $request->session()->get('id');
        $data = Razorpay_Subscription::where('fr_user_id',$id)->get(); 
        return $data;
    }

    public function api_sipList(Request $request) {
        $id = $request->uid;
        $data = Razorpay_Subscription::where('fr_user_id',$id)->get(); 
        return $data;
    }

    public function metalCount(Request $request) {
        $id = $request->session()->get('id');
        $availCount = AugmontOrders::where(['user_id' => $id])->whereNotNull('transactionId')->orderBy("created_at", "desc")->get(['goldBalance','silverBalance'])->first();
        if ($availCount === null) {
            $availCount = array('goldBalance' => 0, 'silverBalance' => 0);
        }
        return $availCount;
    }

    public function api_metalCount(Request $request) {
        $id = $request->uid;
        $availCount = AugmontOrders::where(['user_id' => $id])->whereNotNull('transactionId')->orderBy("created_at", "desc")->get(['goldBalance','silverBalance'])->first();
        if ($availCount === null) {
            $availCount = array('goldBalance' => 0, 'silverBalance' => 0);
        }
        $availSilverSell = AugmontOrders::where(['user_id' => $id])
            ->where('updated_at', '<=', Carbon::now()->subDays(2)->toDateTimeString())
            ->whereNotNull('invoiceNumber')
            ->where('metalType', '=', 'silver')
            ->sum('quantity');
        $availGoldSell = AugmontOrders::where(['user_id' => $id])
            ->where('updated_at', '<=', Carbon::now()->subDays(2)->toDateTimeString())
            ->whereNotNull('invoiceNumber')
            ->where('metalType', '=', 'gold')
            ->sum('quantity');
        $silverSold = AugmontOrders::where(['user_id' => $id])
            ->whereNotNull('transactionId')
            ->where('metalType', '=', 'silver')
            ->where('ordertype', '=', 'Sell')
            ->sum('quantity');
        $goldSold = AugmontOrders::where(['user_id' => $id])
            ->whereNotNull('transactionId')
            ->where('metalType', '=', 'gold')
            ->where('ordertype', '=', 'Sell')
            ->sum('quantity');
        $availCount['availGoldSell'] = $availGoldSell - $goldSold;;
        $availCount['availSilverSell'] = $availSilverSell - $silverSold;
        $availCount['goldBalance'] = $availCount['goldBalance'];
        $availCount['silverBalance'] = $availCount['silverBalance'];
        
        return $availCount;
    }

    public function clientRequests($method, $url, $data) {

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        $headers = [
            'Content-Type' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => $authToken,
        ];

        $client = new Client(['verify' => false ]);
        if($method=="GET") {
            try {
                $res = $client->request($method, 
                    env('AUG_URL').$url,[
                    'headers' => $headers
                ]);
                return json_decode($res->getBody()->getContents());
            }
            catch (\GuzzleHttp\Exception\RequestException $e) {
                $responseBody = $e->getResponse();
                return json_decode($responseBody->getBody()->getContents()); 
            }
        } else {
            if($method=="POST") {
                try {
                    $res = $client->request($method, 
                        env('AUG_URL').$url,[
                            'body' => json_encode($data),
                            'headers' => $headers
                        ]
                    );
                    return json_decode($res->getBody()->getContents());
                }
                catch (\GuzzleHttp\Exception\RequestException $e) {
                    $responseBody = $e->getResponse();
                    return json_decode($responseBody->getBody()->getContents()); 
                }
            }
        }
        
    }

    public function clientFileUploadRequests($query, $multipart, $url) {

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        $headers = [
            'Content-Type' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => $authToken,
        ];

        $client = new Client(['verify' => false ]);
        try {
            $res = $client->request("POST", 
                env('AUG_URL').$url,[
                    'query' => $query,
                    'multipart' => $multipart,
                    'headers' => $headers
                ]
            );
            return json_decode($res->getBody()->getContents());
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
            $responseBody = $e->getResponse();
            return json_decode($responseBody->getBody()->getContents()); 
        }
    }


    public function clientURLRequests($method, $url, $data) {

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        $headers = [
            'Accept' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => $authToken,
        ];

        $client = new Client(['verify' => false ]);
        if($method=="GET") {
            try {
                $res = $client->request($method, 
                    env('AUG_URL').$url,[
                    'headers' => $headers
                ]);
                return json_decode($res->getBody()->getContents());
            }
            catch (\GuzzleHttp\Exception\RequestException $e) {
                $responseBody = $e->getResponse();
                return json_decode($responseBody->getBody()->getContents()); 
            }
        } else {
            if($method=="POST") {
                try {
                    $res = $client->request($method, 
                        env('AUG_URL').$url,[
                            'form_params' => $data,
                            'headers' => $headers
                        ]
                    );
                    return json_decode($res->getBody()->getContents());
                }
                catch (\GuzzleHttp\Exception\RequestException $e) {
                    $responseBody = $e->getResponse();
                    return json_decode($responseBody->getBody()->getContents()); 
                }
            }
        }
        
    }

    public function errorCapture($data) {
        if(Arr::exists($data, 'errors')) {
            $error_key = "";
            foreach($data->errors as $key => $value) {
                if($key=="uniqueId") {
                    $error_key = $value[0]->code;
                }
            } 
        }
        return $error_key;
    }

    public function getDayWiseAugOrders(Request $request) {
        $date = Carbon::today();
        if($request->day == "today") {
            $date = Carbon::today();
        } else {
            if($request->day == "yesterday") {
                $date = Carbon::yesterday();
            } else {
                $date = Carbon::today();
            }
        }

        $myObj = array();
        $myObj['today'] = AugmontOrders::whereDate('updated_at', $date)->whereNotNull('transactionId')->count();
        $myObj['total'] = AugmontOrders::whereNotNull('transactionId')->count();

        $myObj['todayBuySilver'] = AugmontOrders::select(
            DB::raw("SUM(quantity) as silverQty"),
        )
        ->whereDate('updated_at', $date)
        ->whereNotNull('transactionId')
        ->where('ordertype', 'Buy')
        ->where('metalType', 'silver')
        ->get();

        $myObj['todayBuyGold'] = AugmontOrders::select(
            DB::raw("SUM(quantity) as goldQty"),
        )
        ->whereDate('updated_at', $date)
        ->whereNotNull('transactionId')
        ->where('ordertype', 'Buy')
        ->where('metalType', 'gold')
        ->get();

        $myObj['todaySellSilver'] = AugmontOrders::select(
            DB::raw("SUM(quantity) as silverQty"),
        )
        ->whereDate('updated_at', $date)
        ->whereNotNull('transactionId')
        ->where('ordertype', 'sell')
        ->where('metalType', 'silver')
        ->get();

        $myObj['todaySellGold'] = AugmontOrders::select(
            DB::raw("SUM(quantity) as goldQty"),
        )
        ->whereDate('updated_at', $date)
        ->whereNotNull('transactionId')
        ->where('ordertype', 'sell')
        ->where('metalType', 'gold')
        ->get();
        
        return $myObj; 
    }

    public function getMetalCountAPI() {
        $user = auth('userapi')->user();
        if($user) {
            
            $id = $user->pk_user_id;

            $availCount = AugmontOrders::where(['user_id' => $id])->whereNotNull('transactionId')->orderBy("created_at", "desc")->get(['goldBalance','silverBalance'])->first();
            if ($availCount === null) {
                $availCount = array('goldBalance' => 0, 'silverBalance' => 0);
            }
            $data = [
                "statusCode" => 201,
                "data" => $availCount
            ];
		    return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
        
    }
}
