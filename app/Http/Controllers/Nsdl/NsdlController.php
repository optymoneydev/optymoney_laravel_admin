<?php

namespace App\Http\Controllers\Nsdl;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\KycController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_users_detail;
use SoapClient;
use Illuminate\Support\Str;
Use App\Models\Bfsi_users_detail;

class NsdlController extends Controller
{
    public function getPasscodeEncyrt($pan, $mobile) {
		$user = auth('userapi')->user();
		if($user) {
            $id = $user->pk_user_id;
            $p_key = date("d").date("y").rand(000000,999999);
			$request_id = date("Y").rand(000000,999999);
			$url = "http://kra.ndml.in:80/sms-ws/PANServiceImplService";
			$wsdl = 'https://kra.ndml.in/sms-ws/PANServiceImplService/PANServiceImplService.wsdl';
			$userId   = env('NSDL_USERId');
			$mobile   = env('NSDL_MOBILE');
			$password = env('NSDL_PASSWORD');

			$xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ser=\"http://service.webservice.pan.kra.ndml.com/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <ser:getPasscode>\r\n         <!--Optional:-->\r\n         <arg0>NDML@1234</arg0>\r\n         <!--Optional:-->\r\n         <arg1>1022565410</arg1>\r\n      </ser:getPasscode>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>";
			
			$context = stream_context_create([
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => true,
					'allow_self_signed' => true
				]
			]);
			$options = array(
				'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
				'style'=>'SOAP_RPC',
				'use'=>'SOAP_ENCODED',
				'soap_version'=>'SOAP_1_1',
				'cache_wsdl'=>'WSDL_CACHE_NONE',
				'connection_timeout'=>15,
				'trace'=>true,
				'encoding'=>'UTF-8',
				'exceptions'=>true,
				'stream_context' => $context
			);
			$soap = new SoapClient($wsdl, $options); 
			try {
				$passKey = $p_key;
				$passcode_params = array('arg0' => $password, 'arg1' => $passKey);
				$encPass = $soap->getPasscode($passcode_params);            
				$encPassword = $encPass->return;
				$xml_request =  '<APP_REQ_ROOT>
									<APP_PAN_INQ>
										<APP_PAN_NO>'.$pan.'</APP_PAN_NO>
										<APP_MOBILE_NO>'.$mobile.'</APP_MOBILE_NO>
										<APP_REQ_NO>'.$request_id.'</APP_REQ_NO>
									</APP_PAN_INQ>
								</APP_REQ_ROOT>';
				$params = array('arg0' => $xml_request, 'arg1' => $userId, 'arg2' => $encPassword, 'arg3' => $passKey);
				
				$data = $soap->panInquiryDetails($params)->return;
				$temp_data = (new GeneralController)->xmlToArray($data)['APP_PAN_INQ'];
				if(array_key_exists('ERROR', $temp_data)) {
					$userData = Bfsi_users_detail::where('fr_user_id', $id)->update([
						'nsdl_kyc_status' => "NOTKYC",
						'nsdl_kyc_res' => $temp_data
					]);
					$val = [
						'status' => "FAILURE",
						'msg' => $temp_data['ERROR']." Lets Complete Your KYC to Start Investing",
						'data' => $temp_data,
						'request' => $xml_request,
						'kycStatus' => 404
					];
				} else {
					if (Str::contains($temp_data['APP_STATUS'], "Not Available")) {
						$userData = Bfsi_users_detail::where('fr_user_id', $id)->update([
							'nsdl_kyc_status' => "NOTKYC",
							'nsdl_kyc_res' => $temp_data
						]);
						$val = [
							'status' => "SUCCESS",
							'msg' => "Lets Complete Your KYC to Start Investing",
							'data' => $temp_data,
							'kycStatus' => 404
						];
					} else {
						$userData = Bfsi_users_detail::where('fr_user_id', $id)->update([
							'nsdl_kyc_status' => "KYC",
							'nsdl_kyc_res' => $temp_data
						]);
						$val = [
							'status' => "SUCCESS",
							'msg' => "Great !! You are Investment ready ! Lets Start",
							'data' => $temp_data,
							'kycStatus' => 201
						];
					}
				}
			}
			catch(Exception $e) {
				\Log::channel('itsolution')->error(json_encode(['id' => $id, 'input' => $pan, 'function' => "getPasscodeEncyrt", 'exception' => $e]));
                return $e;
			}
			return $val;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
        
    }

	public function getKYCStatusCheck($pan, $mobile) {
        $p_key = date("d").date("y").rand(000000,999999);
        $request_id = date("Y").rand(000000,999999);
        $url = "http://kra.ndml.in:80/sms-ws/PANServiceImplService";
        $wsdl = 'https://kra.ndml.in/sms-ws/PANServiceImplService/PANServiceImplService.wsdl';
        $userId   = env('NSDL_USERId');
		$mobile   = env('NSDL_MOBILE');
		$password = env('NSDL_PASSWORD');

        $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ser=\"http://service.webservice.pan.kra.ndml.com/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <ser:getPasscode>\r\n         <!--Optional:-->\r\n         <arg0>NDML@1234</arg0>\r\n         <!--Optional:-->\r\n         <arg1>1022565410</arg1>\r\n      </ser:getPasscode>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>";
        
		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => true,
				'allow_self_signed' => true
			]
		]);
		$options = array(
			'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
			'style'=>'SOAP_RPC',
			'use'=>'SOAP_ENCODED',
			'soap_version'=>'SOAP_1_1',
			'cache_wsdl'=>'WSDL_CACHE_NONE',
			'connection_timeout'=>15,
			'trace'=>true,
			'encoding'=>'UTF-8',
			'exceptions'=>true,
			'stream_context' => $context
		);
		$soap = new SoapClient($wsdl, $options); 
        try {
			$passKey = $p_key;
			$passcode_params = array('arg0' => $password, 'arg1' => $passKey);
			$encPass = $soap->getPasscode($passcode_params);            
			$encPassword = $encPass->return;
			$xml_request =  '<APP_REQ_ROOT>
								<APP_PAN_INQ>
									<APP_PAN_NO>'.$pan.'</APP_PAN_NO>
									<APP_MOBILE_NO>'.$mobile.'</APP_MOBILE_NO>
									<APP_REQ_NO>'.$request_id.'</APP_REQ_NO>
								</APP_PAN_INQ>
							</APP_REQ_ROOT>';
			$params = array('arg0' => $xml_request, 'arg1' => $userId, 'arg2' => $encPassword, 'arg3' => $passKey);
			
			$data = $soap->panInquiryDetails($params)->return;
			$temp_data = (new GeneralController)->xmlToArray($data)['APP_PAN_INQ'];
			if(array_key_exists('ERROR', $temp_data)) {
				$userData = Bfsi_users_detail::where('fr_user_id', $id)->update([
					'nsdl_kyc_status' => "NOTKYC",
					'nsdl_kyc_res' => $temp_data
				]);
				$val = [
					'status' => "FAILURE",
					'msg' => $temp_data['ERROR']." Lets Complete Your KYC to Start Investing",
					'data' => $temp_data,
					'request' => $xml_request,
					'kycStatus' => 404
				];
			} else {
				if (Str::contains($temp_data['APP_STATUS'], "Not Available")) {
					$userData = Bfsi_users_detail::where('fr_user_id', $id)->update([
						'nsdl_kyc_status' => "NOTKYC",
						'nsdl_kyc_res' => $temp_data
					]);
					$val = [
						'status' => "SUCCESS",
						'msg' => "Lets Complete Your KYC to Start Investing",
						'data' => $temp_data,
						'kycStatus' => 404
					];
				} else {
					$userData = Bfsi_users_detail::where('fr_user_id', $id)->update([
						'nsdl_kyc_status' => "KYC",
						'nsdl_kyc_res' => $temp_data
					]);
					$val = [
						'status' => "SUCCESS",
						'msg' => "Great !! You are Investment ready ! Lets Start",
						'data' => $temp_data,
						'kycStatus' => 201
					];
				}
			}
        }
        catch(Exception $e) {
            print_r("@@@@@@@@@@      Exception occured :->      ".$e->getMessage()."\r\n");
            die ($e->getTraceAsString());
        }
        return $val;
    }

    public function getPanDetails($pan) {

        $p_key = date("d").date("y").rand(000000,999999);
        $request_id = date("Y").rand(000000,999999);
        $url = "http://kra.ndml.in:80/sms-ws/PANServiceImplService";
        $wsdl = 'https://kra.ndml.in/sms-ws/PANServiceImplService/PANServiceImplService.wsdl';
        $userId   = env('NSDL_USERId');
		$mobile   = env('NSDL_MOBILE');
		$password = env('NSDL_PASSWORD');

        $xml = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ser=\"http://service.webservice.pan.kra.ndml.com/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <ser:getPasscode>\r\n         <!--Optional:-->\r\n         <arg0>NDML@1234</arg0>\r\n         <!--Optional:-->\r\n         <arg1>1022565410</arg1>\r\n      </ser:getPasscode>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>";
        
		$context = stream_context_create([
									'ssl' => [
										// set some SSL/TLS specific options
										'verify_peer' => false,
										'verify_peer_name' => true,
										'allow_self_signed' => true
									]
								]);
		$options = array(
					'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
					'style'=>'SOAP_RPC',
					'use'=>'SOAP_ENCODED',
					'soap_version'=>'SOAP_1_1',
					'cache_wsdl'=>'WSDL_CACHE_NONE',
					'connection_timeout'=>15,
					'trace'=>true,
					'encoding'=>'UTF-8',
					'exceptions'=>true,
					'stream_context' => $context
				);
		$soap = new SoapClient($wsdl, $options); 
        try {
			$passKey = $p_key;
			$passcode_params = array('arg0' => $password, 'arg1' => $passKey);
			$encPass = $soap->getPasscode($passcode_params);            
			$encPassword = $encPass->return;
			$xml_request =  '<APP_REQ_ROOT>
								<APP_PAN_DOWN>
									<APP_PAN_NO>AXFPP0304C</APP_PAN_NO>
									<APP_MOBILE_NO>'.$mobile.'</APP_MOBILE_NO>
									<APP_REQ_NO>'.$request_id.'</APP_REQ_NO>
								</APP_PAN_DOWN>
							</APP_REQ_ROOT>';
			$params = array('arg0' => $xml_request, 'arg1' => $userId, 'arg2' => $encPassword, 'arg3' => $passKey);
			
			$data = $soap->panDownloadDetails($params)->return;
			dd($data);
			$temp_data = (new GeneralController)->xmlToArray($data)['APP_PAN_DOWN'];
			/*---------------------------------  Insert into database -------------------------------------------------------------*/
			// $this->db->db_run_query("Insert into kyc_check set kyc_PAN='".$chk_PAN."', req_id='".$request_id."',kyc_res='".$data."'");
			$xml=simplexml_load_string($data) or die("Error: Cannot create object");
			$kyc_status_xml = $xml->APP_PAN_INQ[0]->APP_STATUS; 
			if(strpos($kyc_status_xml,'Not Available') !== false) {
				$val['status'] = "failure";
				$val['msg'] =  "Lets Complete Your KYC to Start Investing";	
				$response_a = "KYCNOT";
			} else {
				$val['status'] = "success";
				$val['msg'] =  "Great !! You are Investment ready ! Lets Start";	
				$val['data'] = $temp_data;
				$response_a = "KYC";
			}
			/* update nsdl status to user details */
			// $this->db->db_run_query("update bfsi_users_details set nsdl_kyc_status = '".$response_a."', nsdl_kyc_res='".$kyc_status_xml."' where fr_user_id='".$this->CONFIG->loggedUserId."'");
        }
        catch(Exception $e) {
            print_r("@@@@@@@@@@      Exception occured :->      ".$e->getMessage()."\r\n");
            die ($e->getTraceAsString());
        }
        return json_encode($val);

    }

    public function pan_verification(Request $request) {

        $passcode = $this->getPasscodeEncyrt();
        return $passcode;
    }

	public function nsdlResponseUpdate($nsdl_response, $request) {
		$data = json_decode($nsdl_response);
		$user = auth('userapi')->user();
		if($user) {
            $id = $user->pk_user_id;
			if($data->status == "SUCCESS" ) {
				$kyc =  (new KycController)->insertKyc($id, "", "", $data->data->APP_PAN_NO, "", "", "", "", "approved", $data->data->APP_STATUS, 
				"approved", "", "", "", "", "", "", "", "nsdl");
				return "SUCCESS";
			} else {
				if($data->status == "FAILURE" ) {
					$kyc =  (new KycController)->insertKyc($id, "", "", $data->data->APP_PAN_NO, "", "", "", "", "approved", $data->data->ERROR, "pending", "", "", "", "", "", "", "", "nsdl");
					return "FAILURE";
				}
			}
		} else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
	}

	public function nsdlResponseAPIUpdate($data, $id) {
		return $data;
		if($data['status'] == "SUCCESS" ) {
			$kyc =  (new KycController)->insertKyc($id, "", "", $data['data']['APP_PAN_NO'], "", "", "", "", "approved", $data['data']['APP_STATUS'], 
			"approved", "", "", "", "", "", "", "", "nsdl");
			return "SUCCESS";
		} else {
			if($data['status'] == "FAILURE" ) {
				$kyc =  (new KycController)->insertKyc($id, "", "", $data['data']['APP_PAN_NO'], "", "", "", "", "approved", $data['data']['ERROR'], "pending", "", "", "", "", "", "", "", "nsdl");
				// $kyc =  (new KycController)->insertKyc($request->session()->get('id'), "", "", $data['data']['APP_PAN_NO'], "", "", "", "", "pending", $data['data']['ERROR'], "", $kycURL->createdObj->id, $kycURL->createdObj->username, \Carbon\Carbon::now(), $kycURL->createdObj->autoLoginUrL, $data['data']['ERROR'], "signzy");
				return "FAILURE";
			}
		}
	}
}
