<?php

namespace App\Http\Controllers\Nsdl;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\customer\UserAuthController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Session;
Use App\Models\Signzy;

class SignzyController extends Controller
{

	public function signzyAuth($signzy_username, $signzy_password, $signzy_url) {
		$client = new Client(['verify' => false ]);
        try {
			$data = [
				"username" => $signzy_username,
				"password" => $signzy_password
			];
			$res = $client->request('POST', 
					$signzy_url.'/api/channels/login',[
					'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
					'body' => json_encode($data)
            ]);
			$response = json_decode($res->getBody()->getContents());
			Session::put('signzy_userId', $response->userId);
			Session::put('signzy_accessToken', "$response->id");
			return $response;
        }
        catch (\GuzzleHttp\Exception\RequestException $e) {
			$responseBody = $e->getResponse();
            return json_decode($responseBody->getBody()->getContents())->error; 
        }
    }

	public function signzyGetURL(Request $request) {
		$result = (new NsdlController)->getPasscodeEncyrt();
		if($result->status == "SUCCESS") {
			$data = [
				'status_code' => 210,
				'message' => $result->msg
			];
		} else {
			$signzy_username = "";
			$signzy_password = "";
			$signzy_url = "";
			if(env("app_status") == "test") {
				$signzy_username = explode(',', env('SIGNZY_USERNAME'))[0];
				$signzy_password = "Fy#gfa@jCv6dSLkTs%dCvLm83ZpX";
				$signzy_url = explode(',', env('SIGNZY_URL'))[0];
			} else {
				if(env("app_status") == "live") {
					$signzy_username = explode(',', env('SIGNZY_USERNAME'))[1];
					$signzy_password = "Ld38M*9HS@rZs9nc#eK$2OcQ6%D";
					$signzy_url = explode(',', env('SIGNZY_URL'))[1];
				}
			}
			$user = auth('userapi')->user();
			if($user) {
				$username = "opty_signzy_".$user->pk_user_id;
				$signzyCheck = Signzy::where([
					'user_id' => $user->pk_user_id,
					'signzy_username' => $username
				])->first();
				if($signzyCheck) {
					$data = [
						'status_code' => 200,
						'message' => "KYC process already initiated",
						'signzy' => $signzyCheck
					];
				} else {
					$res = $this->signzyAuth($signzy_username, $signzy_password, $signzy_url);
					if(isset($res->statusCode)) {
						$data = [
							'status_code' => 200,
							'message' => "Signzy Authentication issue",
							'signzy' => $signzyCheck
						];
					} else {
						$general = new GeneralController();
						$userAuthController = new UserAuthController();
						$userData = $userAuthController->getUserDetails($user->pk_user_id);
						$username = "opty_signzy_".$user->pk_user_id;

						$postdata = array(
							"email"=> $user->login_id,
							"username"=> $username,
							"phone"=> $userData->contact_no,
							"name"=> $userData->cust_name
							// "redirectUrl"=> "url",
							// "channelEmail"=> "support@optymoney.com"
						);
						$client = new Client(['verify' => false ]);
						try {
							$res = $client->request('POST', 
								$signzy_url.'/api/channels/'.Session::get('signzy_userId').'/onboardings',[
									'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization'=> Session::get('signzy_accessToken')],
									'body' => json_encode($postdata)
							]);
							$response = json_decode($res->getBody()->getContents());
							$signzy = new Signzy();
							$signzy->user_id = $user->pk_user_id;
							$signzy->signzy_username = $username;
							$signzy->email = $user->login_id;
							$signzy->name = $userData->cust_name;
							$signzy->phone = $userData->contact_no;
							$signzy->signzy_id = $response->createdObj->id;
							$signzy->customerId = $response->createdObj->customerId;
							$signzy->initialNamespace = $response->createdObj->initialNamespace;
							$signzy->channelInfo_id = $response->createdObj->channelInfo->id;
							$signzy->channelInfo_username = $response->createdObj->channelInfo->username;
							$signzy->channelInfo_name = $response->createdObj->channelInfo->name;
							$signzy->eventualNamespace = $response->createdObj->eventualNamespace;
							$signzy->applicationUrl = $response->createdObj->applicationUrl;
							$signzy->mobileLoginUrl = $response->createdObj->mobileLoginUrl;
							$signzy->autoLoginUrL = $response->createdObj->autoLoginUrL;
							$signzy->mobileAutoLoginUrl = $response->createdObj->mobileAutoLoginUrl;
							$data = [
								'status_code' => 200,
								'message' => json_encode($signzy->save()),
								'signzy' => $signzy
							];
						}
						catch (\GuzzleHttp\Exception\RequestException $e) {
							$responseBody = $e->getResponse();
							return json_decode($responseBody->getBody()->getContents());
						}
					}
				}
			} else {
				$data = [
						'status_code' => 400,
						'message' => 'User Authentication Failed'
					];
			}
		}
		return $data;
    }

}
