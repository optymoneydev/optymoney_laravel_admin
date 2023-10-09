<?php

namespace App\Http\Controllers\mf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\customer\UserAuthController;
use App\Http\Controllers\Nsdl\NsdlController;
use App\Http\Controllers\mf\PipedController;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_bank_details;
Use App\Models\Mf_order;
Use App\Models\Mfscheme;
use Illuminate\Support\Arr;
use SoapClient;
use File;
use Image;

class BSEController extends Controller
{
    public function ucc_n_create($id) {
		$x =strlen($id);
		$y=6;
		$z= $y-$x;
		$p = "0";
		for($i=1;$i<$z;$i++) {
		    $p =+ $p.$p;
		}
		$client_code ="OPMY".$p.$id;
		return $client_code;
	}

    public function updatePswd(Request $request) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://bsestarmfdemo.bseindia.com/Index.aspx');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language: en-US,en;q=0.9,hi;q=0.8',
            'Cache-Control: max-age=0',
            'Connection: keep-alive',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://bsestarmfdemo.bseindia.com',
            'Referer: https://bsestarmfdemo.bseindia.com/Index.aspx',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: same-origin',
            'Sec-Fetch-User: ?1',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
            'sec-ch-ua: "Chromium";v="116", "Not)A;Brand";v="24", "Google Chrome";v="116"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "macOS"',
            'Accept-Encoding: gzip',
        ]);
        curl_setopt($ch, CURLOPT_COOKIE, 'ASP.NET_SessionId=j2sh1tiimqdkblslrl423unr');
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'ToolkitScriptManager1_HiddenField=%3B%3BAjaxControlToolkit%2C+Version%3D3.0.20820.16598%2C+Culture%3Dneutral%2C+PublicKeyToken%3D28f01b0e84b6d53e%3Aen-US%3A707835dd-fa4b-41d1-89e7-6df5d518ffb5%3A411fea1c%3A865923e8%3A77c58d20%3A91bd373d%3A14b56adc%3A596d588c%3A8e72a662%3Aacd642d2%3A269a19ae&__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=EPVisE1TzGOux4zDVznAqVA1%2FOvDszM8Y49jz%2BACUf0Ed%2B6IjK%2BRt50tUbR6Kw0Qho5c3Qa0KBgNbcpfMkmkArdLNfV58iQlXtkfA0RtjhIUHb5YqvD7WRSYFPs6xgzMAi1G8gLb35zsh9%2BErciN39edcjWCrZ15QCRsTOVA3KOL%2FclOYtFQBcnUmINcpCw3JktbVgO2no1z9M9oYDNYFcluz7n6dhCxLrts9uQNKP1y6cYfnME%2BIZFWDUqNgg21CNCsjvg2JsgOyE4kE1FqfJvqk%2Fc%3D&__VIEWSTATEGENERATOR=90059987&__VIEWSTATEENCRYPTED=&__EVENTVALIDATION=NA5eWpFDh4XLu16r1In8r%2FVoTUE66ojBJEhbb89Y3sCPhncxO67MP1kz16KoKp0ahDwYlPb5ESoWGB5IuKxPoKf7m5o7mlj48RuIFVViFBn2Koku95JoFKJACRUcvbiVQobioMszH5GsAkGJs4LWxP%2B7rQ1IP6sKcPayr4C73p4bGOp%2FyksGAAxu8VlmgXfWFJTBAZfThb7cq5CvaGviW6RnlyS9xJJ94RtQ%2B4MNOJFJ42bJArBBJszOuS6DEN3LGvnoVMjH5wzoAZ%2For34%2BbPRU6u%2Fa4Kn9Y1mAmV1IEh5%2BHcC%2BlHoDAwI59U7xP%2B3i6dTKMQ%3D%3D&txtUserId=1513309&txtMemberId=15133&txtPassword=123456&btnLogin=Login&txtMobileNo=&txtOTP=');

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
        curl_close($ch);



        $envBSEDataJson = $this->getEnvData();
        $user = auth('userapi')->user();
        if($user) {
            // if($envBSEDataJson['bsePassword'] == "")
            $newpswd = "654321";
            $pswdBSEArr = array("OLD PASSWORD" => $envBSEDataJson['bsePassword'],
						"NEW PASSWORD" => $newpswd,
						"CONF PASSWORD" => $newpswd);
            $pipeValues = implode("|",$pswdBSEArr);
            $bseGenPassword = $this->bseGenPassword($envBSEDataJson['soapPswdUrl'], $envBSEDataJson['svcUploadUrl'], $envBSEDataJson['name_space'], $envBSEDataJson['bseUserId'], $envBSEDataJson['bseMemberID'], $envBSEDataJson['bsePassword']);
            try {
                $headers = [
                'Content-Type' => 'application/soap+xml; charset=utf-8',
                'SOAPAction' => $envBSEDataJson['soapFatcaUrl'],
                'To' => $envBSEDataJson['svcUploadUrl']
                ];
                $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$envBSEDataJson['name_space'].'">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                    <wsa:Action>'.$envBSEDataJson['soapFatcaUrl'].'</wsa:Action>
                    <wsa:To>'.$envBSEDataJson['svcUploadUrl'].'</wsa:To>
                </soap:Header>
                <soap:Body>
                    <ns:MFAPI>
                        <ns:Flag>04</ns:Flag>
                        <ns:UserId>'.$envBSEDataJson['bseUserId'].'</ns:UserId>
                        <ns:EncryptedPassword>'.$bseGenPassword.'</ns:EncryptedPassword>
                        <ns:param>'.$pipeValues.'</ns:param>
                    </ns:MFAPI>
                </soap:Body>
                </soap:Envelope>';
                return $body;
                $res = $this->soapCall($envBSEDataJson['svcUploadUrl'], $body, $headers);
                return $res;
                $data = $this->soapFatcaResToArr($res, $envBSEDataJson['name_space']);
                $bseFatcaStatus = explode('|', $data[0]);
                if($bseFatcaStatus[0] == "100") {
                    Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
                        'fatcaInput' => $body,
                        'fatcaOutput' => $data,
                        'fatcaStatus' => "SUCCESS"
                    ]);
                    $responseObj['status'] = "SUCCESS";
                    $responseObj['message'] = $bseFatcaStatus[1];
                } else {
                    Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
                        'fatcaInput' => $body,
                        'fatcaOutput' => $data,
                        'fatcaStatus' => "FAILURE"
                    ]);
                    $responseObj['status'] = "FAILURE";
                    $responseObj['message'] = $bseFatcaStatus[1];
                }
            }
            catch ( \Exception $e) {
                $responseObj['status'] = "FAILURE";
                $responseObj['message'] = $e->getMessage();
                return $responseObj;
            }
        } else {
            $responseObj['status'] = "FAILURE";
            $responseObj['message'] = "Authentication Failed";
        }
        return $responseObj;
    }

    public function createFatcaTest(Request $request) {
        $bseAuthData = $this->getBseAuthData();
        $envBSEFatcaDataJson = $this->getFatcaData();
        $user = auth('userapi')->user();
        if($user) {
            $userAuthController = new UserAuthController();
            $userData = $userAuthController->getUserDetails($user->pk_user_id);
            $client_code = $this->ucc_n_create($user->pk_user_id);
            $date = date('Y-m-d H:i:s');
            $dobFormatted = \Carbon\Carbon::createFromFormat('Y-m-d', $userData->dob)->format('d/m/Y');
            $transBSEArr = array("PAN_RP" => $userData->pan_number,
						"PEKRN" => "",
						"INV_NAME" => $userData->cust_name,
						"DOB" => $dobFormatted,
						"FR_NAME" => "",
						"SP_NAME" => "",
						"TAX_STATUS" => $userData->taxStatus,
						"DATA_SRC" => "E",
						"ADDR_TYPE" => "1",
						"PO_BIR_INC" => "IN",
						"CO_BIR_INC" => "IN",
						"TAX_RES1" => "IN",
						"TPIN1" => $userData->pan_number,
						"ID1_TYPE" => "C",
						"TAX_RES2" => "",
						"TPIN2" => "",
						"ID2_TYPE" => "",
						"TAX_RES3" => "",
						"TPIN3" => "",
						"ID3_TYPE" => "",
						"TAX_RES4" => "",
						"TPIN4" => "",
						"ID4_TYPE" => "",
						"SRCE_WEALT" => $userData->sourceOfWealth,
						"CORP_SERVS" => "",
						"INC_SLAB" => "32",
						"NET_WORTH" => "",
						"NW_DATE" => "",
						"PEP_FLAG" => "N",
						"OCC_CODE" => $userData->occupationCode,
						"OCC_TYPE" => "S",
						"EXEMP_CODE" => "",
						"FFI_DRNFE" => "",
						"GIIN_NO" => "",
						"SPR_ENTITY" => "",
						"GIIN_NA" => "",
						"GIIN_EXEMC" => "",
						"NFFE_CATG" => "",
						"ACT_NFE_SC" => "",
						"NATURE_BUS" => "",
						"REL_LISTED" => "",
						"EXCH_NAME" => "O",
						"UBO_APPL" => "N",
						"UBO_COUNT" => "",
						"UBO_NAME" => "",
						"UBO_PAN" => "",
						"UBO_NATION" => "",
						"UBO_ADD1" => "",
						"UBO_ADD2" => "",
						"UBO_ADD3" => "",
						"UBO_CITY" => "",
						"UBO_PIN" => "",
						"UBO_STATE" => "",
						"UBO_CNTRY" => "",
						"UBO_ADD_TY" => "",
						"UBO_CTR" => "",
						"UBO_TIN" => "",
						"UBO_ID_TY" => "",
						"UBO_COB" => "",
						"UBO_DOB" => "",
						"UBO_GENDER" => "",
						"UBO_FR_NAM" => "",
						"UBO_OCC" => "",
						"UBO_OCC_TY" => "",
						"UBO_TEL" => "",
						"UBO_MOBILE" => "",
						"UBO_CODE" => "",
						"UBO_HOL_PC" => "",
						"SDF_FLAG" => "",
						"UBO_DF" => "N",
						"AADHAAR_RP" => "",
						"NEW_CHANGE" => "N",
						"LOG_NAME" => $client_code,
						"DOC1" => "",
						"DOC2" => "");
            $pipeValues = implode("|",$transBSEArr);
            $bseGenPassword = $this->bseGenPassword($envBSEFatcaDataJson['soapPswdUrl'], $envBSEFatcaDataJson['svcUploadUrl'], $envBSEFatcaDataJson['name_space'], $bseAuthData['bseUserId'], $bseAuthData['bseMemberId'], $bseAuthData['bsePassword']);
            try {
                $headers = [
                'Content-Type' => 'application/soap+xml; charset=utf-8',
                'SOAPAction' => $envBSEFatcaDataJson['soapFatcaUrl'],
                'To' => $envBSEFatcaDataJson['svcUploadUrl']
                ];
                $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$envBSEFatcaDataJson['name_space'].'">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                    <wsa:Action>'.$envBSEFatcaDataJson['soapFatcaUrl'].'</wsa:Action>
                    <wsa:To>'.$envBSEFatcaDataJson['svcUploadUrl'].'</wsa:To>
                </soap:Header>
                <soap:Body>
                    <ns:MFAPI>
                        <ns:Flag>01</ns:Flag>
                        <ns:UserId>'.$bseAuthData['bseUserId'].'</ns:UserId>
                        <ns:EncryptedPassword>'.$bseGenPassword.'</ns:EncryptedPassword>
                        <ns:param>'.$pipeValues.'</ns:param>
                    </ns:MFAPI>
                </soap:Body>
                </soap:Envelope>';
                $res = $this->soapCall($envBSEFatcaDataJson['svcUploadUrl'], $body, $headers);
                $data = $this->soapFatcaResToArr($res, $envBSEFatcaDataJson['name_space']);
                $bseFatcaStatus = explode('|', $data[0]);
                if($bseFatcaStatus[0] == "100") {
                    Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
                        'fatcaInput' => $body,
                        'fatcaOutput' => $data,
                        'fatcaStatus' => "SUCCESS"
                    ]);
                    $responseObj['status'] = "SUCCESS";
                    $responseObj['message'] = $bseFatcaStatus[1];
                } else {
                    Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
                        'fatcaInput' => $body,
                        'fatcaOutput' => $data,
                        'fatcaStatus' => "FAILURE"
                    ]);
                    $responseObj['status'] = "FAILURE";
                    $responseObj['message'] = $bseFatcaStatus[1];
                }
            }
            catch ( \Exception $e) {
                $responseObj['status'] = "FAILURE";
                $responseObj['message'] = $e->getMessage();
                return $responseObj;
            }
        } else {
            $responseObj['status'] = "FAILURE";
            $responseObj['message'] = "Authentication Failed";
        }
        return $responseObj;
    }

    public function createBSE(Request $request) {
        $bseAuthData = $this->getBseAuthData();
        $envBseAOFUploadJson = $this->getBseAOFUploadData();
        $envBSEDataJson = $this->getUCCData();
        $user = auth('userapi')->user();
        $id = $user->pk_user_id;
        if($user) {
            $userAuthController = new UserAuthController();
            $userData = $userAuthController->getUserDetails($user->pk_user_id);
            $date = date('Y-m-d H:i:s');
            $dobFormatted = \Carbon\Carbon::createFromFormat('Y-m-d', $userData->dob)->format('d/m/Y');
            if($user->bse_id != null) {
                $reg = "MOD";
                $client_code = $user->bse_id;
            } else {
                $reg = "NEW";
                $client_code = $this->ucc_n_create($user->pk_user_id);
            }
            $PipedController = new PipedController();
            $pipeValues_data = $PipedController->ucc_creation($client_code, $userData, $user);
            $bseGenPassword = $this->bseGenPassword($envBSEDataJson['soapPswdUrl'], $envBSEDataJson['svcUploadUrl'], $envBSEDataJson['name_space'], $bseAuthData['bseUserId'], $bseAuthData['bseMemberId'], $bseAuthData['bsePassword']);
            
            try {
                $body = array(
                        "UserId" => $bseAuthData['bseUserId'],
                        "MemberCode" => $bseAuthData['bseMemberID'],
                        "Password" => $bseAuthData['bsePassword'],
                        "RegnType" => $reg,
                        "Param" => $pipeValues_data,
                        "Filler1" => "",
                        "Filler2" => ""
                );
                $body_json = json_encode($body);
                $client = new \GuzzleHttp\Client();
                $res = $client->request('POST', 
                explode(',', $envBSEDataJson['ucc_reg'])[0],[
                    'body' => $body_json,
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]);
                $bseStatus = json_decode($res->getBody()->getContents());
                if($bseStatus->Status == 0) {
                    Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
                        'bseInput' => $pipeValues_data,
                        'bseOutput' => json_encode($bseStatus),
                        'bseStatus' => "SUCCESS"
                    ]);
                    Bfsi_user::where('pk_user_id', $user->pk_user_id)->update([
                        'bse_id' => $client_code
                    ]);
                    $responseObj['status'] = "SUCCESS";
                    $responseObj['message'] = $bseStatus->message;
                    if($user->bse_id == null) {
                        $responseObj['aof_upload'] = $this->uploadAOF($request);
                    }
                    
                } else {
                    Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
                        'bseInput' => $pipeValues_data,
                        'bseOutput' => json_encode($bseStatus),
                        'bseStatus' => "FAILURE"
                    ]);
                    $responseObj['status'] = "FAILURE";
                    $responseObj['message'] = $bseStatus->Remarks;
                }
            }
            catch ( \Exception $e) {
                $responseObj['status'] = "FAILURE";
                $responseObj['message'] = $e->getMessage();
                return $responseObj;
            }
        } else {
            $responseObj['status'] = "FAILURE";
            $responseObj['message'] = "Authentication Failed";
        }
        return $responseObj;
    }

    public function uploadAOF(Request $request) {
        $bseAuthData = $this->getBseAuthData();
        $envBseAOFUploadJson = $this->getBseAOFUploadData();
        $user = auth('userapi')->user();
        $id = $user->pk_user_id;
        if($user) {
            $userAuthController = new UserAuthController();
            $userData = $userAuthController->getUserDetails($user->pk_user_id);
            $client_code = $this->ucc_n_create($user->pk_user_id);
            $bseGenPassword = $this->bseGenPasswordForUpload($envBseAOFUploadJson['soapFileUploadGetPswdAction'], $envBseAOFUploadJson['soapFileUploadGetPswdTo'], $envBseAOFUploadJson['soapXmlnsTem'], $envBseAOFUploadJson['soapXmlnsStar'], $bseAuthData['bseUserId'], $bseAuthData['bseMemberId'], $bseAuthData['bsePassword']);
            
            $old = ini_set('memory_limit', '8192M');
            
            $path = public_path('uploads').'/users/'.$id;
            $uccFile = request('uccfile');
            
            if(!File::exists($path)) {
                $profile_path = $path.'/profile';
                File::makeDirectory($path, 0777, true, true);
                File::makeDirectory($profile_path, 0777, true, true);
            } else {
                $profile_path = $path.'/profile';
                File::makeDirectory($profile_path, 0777, true, true);
            }

            $imgData = str_replace(' ','+',$uccFile);
            $imgData = substr($imgData,strpos($imgData,",")+1);
            $imgData1 = base64_decode($imgData);

            $uccfileName = $bseAuthData['bseMemberId'].$client_code.date("dmY").'.tiff';
            $filePath = $profile_path."/".$uccfileName;
            
            $file = fopen($filePath, 'w');
            fwrite($file, $imgData1);
            fclose($file);
            chmod($filePath,0777);

            $imagedata = file_get_contents($filePath);
            $base64 = base64_encode($imagedata);

            $r = array();
            $chars = str_split($base64);
        
            foreach ($chars as $char) {
                $r[] = ord($char);
            }
            $ucc_form_db_status = Bfsi_users_detail::where('fr_user_id', $id)->update([
                'ucc_form_filename' => $uccfileName,
                'ucc_submission' => "Yes"
            ]);

            try {
                $headers = [
                    'Content-Type' => 'application/soap+xml; charset=utf-8',
                    'SOAPAction' => $envBseAOFUploadJson['soapFileUploadAction'],
                    'To' => $envBseAOFUploadJson['soapFileUploadGetPswdTo']
                ];

                $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="'.$envBseAOFUploadJson['soapXmlnsTem'].'" xmlns:star="'.$envBseAOFUploadJson['soapXmlnsStar'].'">
                    <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                        <wsa:Action>'.$envBseAOFUploadJson['soapFileUploadAction'].'</wsa:Action>
                        <wsa:To>'.$envBseAOFUploadJson['soapFileUploadGetPswdTo'].'</wsa:To>
                    </soap:Header>
                    <soap:Body>
                        <tem:UploadFile>
                            <tem:data>
                                <star:ClientCode>'.$user->bse_id.'</star:ClientCode>
                                <star:DocumentType>Nrm</star:DocumentType>
                                <star:EncryptedPassword>'.$bseGenPassword.'</star:EncryptedPassword>
                                <star:FileName>'.$uccfileName.'</star:FileName>
                                <star:Filler1>?</star:Filler1>
                                <star:Filler2>?</star:Filler2>
                                <star:Flag>UCC</star:Flag>
                                <star:MemberCode>'.$bseAuthData['bseMemberId'].'</star:MemberCode>
                                <star:UserId>'.$bseAuthData['bseUserId'].'</star:UserId>
                                <star:pFileBytes>'.$base64.'</star:pFileBytes>
                            </tem:data>
                        </tem:UploadFile>
                    </soap:Body>
                </soap:Envelope>';
                $res = $this->soapCall($envBseAOFUploadJson['soapFileUploadGetPswdTo'], $body, $headers);
                $xmlObject = simplexml_load_string($res);
                $xmlObject->registerXPathNamespace('b', "http://schemas.datacontract.org/2004/07/StarMFFileUploadService");
                $status = $xmlObject->xpath('//b:Status')[0];
                $responseString = $xmlObject->xpath('//b:ResponseString');
                if($status == "100") {
                    $ucc_form_db_status = Bfsi_users_detail::where('fr_user_id', $id)->update([
                        'aofInput' => $body,
                        'aofOutput' => $res,
                        'aofStatus' => $status
                    ]);
                    $responseObj['status'] = "SUCCESS";
                    $responseObj['message'] = $responseString[0];
                } else {
                    $ucc_form_db_status = Bfsi_users_detail::where('fr_user_id', $id)->update([
                        'aofInput' => $body,
                        'aofOutput' => $res,
                        'aofStatus' => $status
                    ]);
                    $responseObj['status'] = "FAILURE";
                    $responseObj['message'] = $responseString[0];
                }
            }
            catch ( \Exception $e) {
                return $e;
            }
        } else {
            $responseObj['status'] = "FAILURE";
            $responseObj['message'] = "Authentication Failed";
        }
        return $responseObj;
    }

    public function purchaseLumpsum(Request $request) {
        $bseAuthData = $this->getBseAuthData();
        $envBseMFOrderJson = $this->getBseOrderEnvData();
        $PipedController = new PipedController();
        $GeneralController = new GeneralController();
        $user = auth('userapi')->user();
        if($user) {
            $order_id = Mf_order::all()->last()->pk_order_id;
            $unique_id = "order_".sprintf('%04d', $order_id+1);
            $ref_no    = date("Ymd").$user->pk_user_id.sprintf('%05d', $order_id+1);
            $schemeDetails = Mfscheme::where('pk_nav_id', $request->sch_id)->get()->first();
            $bseGenPassword = $this->bseGenPasswordForOrder($envBseMFOrderJson['bseMforderGetPswdAction'], $envBseMFOrderJson['bserMforderTo'], 
                    $envBseMFOrderJson['bserMforderNameSpace'], $bseAuthData);
            $pipeValues_data = $PipedController->purchaseLumpsum($bseAuthData, $user, $request, $ref_no, $schemeDetails->scheme_code, $GeneralController->getIp($request), $bseGenPassword);
            $order_db_status = Mf_order::create([
                'order_ref_no' => $ref_no, 
                'unique_ref_no' => $unique_id,
                // 'bse_order_id', 
                'fr_user_id' => $user->pk_user_id,
                'folio_no', 
                'scheme_code' => $schemeDetails->scheme_code,
                'scheme_name' => $schemeDetails->scheme_name,
                'scheme_type' => $schemeDetails->scheme_type,
                // 'inv_name', 
                // 'trxntype', 
                // 'trxnno', 
                // 'trxnmode', 
                'trxnstatus' => "Pending",
                // 'traddate', 
                // 'postdate', 
                'purprice' => $schemeDetails->net_asset_value,
                // 'units', 
                'amount' => $request->lumpsumPurchaseAmount,
                // 'pan', 
                // 'euin', 
                'pipe_value' => $pipeValues_data,
                // 'bse_remarks', 
                // 'payment_option', 
                'order_date' => date("Ymd"),
                'created_ip' => $GeneralController->getIp($request),
            ]);
            try {
                $headers = [
                    'Content-Type' => 'application/soap+xml; charset=utf-8',
                    'SOAPAction' => $envBseMFOrderJson['bserMforderOrderEntryAction'],
                    'To' => $envBseMFOrderJson['bserMforderTo']
                ];
                $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:bses="http://bsestarmf.in/">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                <wsa:Action>'.$envBseMFOrderJson['bserMforderOrderEntryAction'].'</wsa:Action>
                <wsa:To>'.$envBseMFOrderJson['bserMforderTo'].'</wsa:To>
                </soap:Header>
                <soap:Body>
                    '.$pipeValues_data.'
                </soap:Body>
                </soap:Envelope>';
                $res = $this->soapCall($envBseMFOrderJson['bserMforderTo'], $body, $headers);
                $data = $this->soapOrderEntryParamResToArr($res, $envBseMFOrderJson['bserMforderNameSpace']);
                $orderStatus = explode('|', $data[0]);
                if(Arr::last($orderStatus) == "0") {
                    Mf_order::where('pk_order_id', $order_db_status->pk_order_id)->update([
                        'bse_remarks' => $orderStatus,
                        'order_status' => "SUCCESS"
                    ]);
                    $responseObj['status'] = "SUCCESS";
                    $responseObj['message'] = $orderStatus[count($orderStatus)-2];
                } else {
                    Mf_order::where('pk_order_id', $order_db_status->pk_order_id)->update([
                        'bse_remarks' => $orderStatus,
                        'order_status' => "FAILURE"
                    ]);
                    $responseObj['status'] = "FAILURE";
                    $responseObj['message'] = $orderStatus[count($orderStatus)-2];
                }
            }
            catch ( \Exception $e) {
                $responseObj['status'] = "FAILURE";
                $responseObj['message'] = $e->getMessage();
                return $responseObj;
            }
            return $responseObj;
        }
    }

    public function purchaseSip(Request $request) {
        $envBSEDataJson = $this->getEnvData();
        $user = auth('userapi')->user();
        return $user;
        // if($user) {
        //     // Create order for Lumpsum and SIP
        //     while (list($key,$val) = each($userId)) {
        //         // create unqiue id
        //         $unique_id = time();
        //         $unique_id = $unique_id.sprintf('%06d', $val['mf_cart_id']);
        //         //create reference id
        //         $ref_no    = date("Ymd")."1".$this->CONFIG->loggedUserId.sprintf('%06d', $val['mf_cart_id']);
        //         if($val['p_method'] == 1) { // Lumpsum
        //             $para_val = $this->lumpsum($val,$unique_id,$ref_no);
        //             $order_id = $this->bseSync->placeOrderBSE($para_val,$val['mf_cart_id']);
        //             $pos = strpos($order_id, "FAILED");
        //             if($pos > 0) {
        //                 return $order_id;
        //             } else {
        //                 $this->db->db_run_query("Update mf_cart_sys set  pipe_val='".$para_val."', bse_order_id='".$order_id."' where mf_cart_id='".$val['mf_cart_id']."'");
        //             }
        //         }
        //         elseif($val['p_method'] == 2) { // SIP
        //             $para_val = $this->sip($val,$unique_id,$ref_no);
        //             $order_id = $this->bseSync->placeSIPOrderBSE($para_val,$val['mf_cart_id']);
        //             $pos = strpos($order_id, "FAILED");
        //             if($pos > 0) {
        //                 return $order_id;
        //             } else {
        //                 $this->db->db_run_query("Update mf_cart_sys set pipe_val='".$para_val."', bse_order_id='".$order_id."' where mf_cart_id='".$val[mf_cart_id]."'");
        //             }
        //         }
        //         $bse_user_id = $val['bse_id'];
        //     }
        //     $p_link_para = $this->p_link_p($bse_user_id);
        //     $p_link = $this->bseSync->getPLink($p_link_para);
        //     $link = 'http'.$p_link;
        //     return $link;
        // }
    }

    public function soapPswdResToArr($res, $name_space) {
        $xmlObject = simplexml_load_string($res);
        $xmlObject->registerXPathNamespace('ns1', $name_space);
        $getPswd = $xmlObject->xpath('//ns1:getPasswordResponse/ns1:getPasswordResult');
        return $getPswd;
    }

    public function soapOrderEntryParamResToArr($res, $name_space) {
        $xmlObject = simplexml_load_string($res);
        $xmlObject->registerXPathNamespace('ns1', $name_space);
        $getPswd = $xmlObject->xpath('//ns1:orderEntryParamResponse/ns1:orderEntryParamResult');
        return $getPswd;
    }

    public function soapFatcaResToArr($res, $name_space) {
        $xmlObject = simplexml_load_string($res);
        $xmlObject->registerXPathNamespace('ns1', $name_space);
        $getPswd = $xmlObject->xpath('//ns1:MFAPIResponse/ns1:MFAPIResult');
        return $getPswd;
    }

    public function soapCall($svcUploadUrl, $body, $headers) {
        $client = new \GuzzleHttp\Client();
        $res = $client->post(
            $svcUploadUrl,
                [
                    'body'    => $body,
                    'headers' => $headers
                ]
            );
            return $res->getBody();
    }

    public function bseGenPassword($soapPswdUrl, $svcUploadUrl, $name_space, $bseUserId, $bseMemberID, $bsePassword) {
        try {
            $headers = [
            'Content-Type' => 'application/soap+xml; charset=utf-8',
            'SOAPAction' => $soapPswdUrl,
            'To' => $svcUploadUrl
            ];
            $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$name_space.'">
            <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                <wsa:Action>'.$soapPswdUrl.'</wsa:Action>
                <wsa:To>'.$svcUploadUrl.'</wsa:To>
            </soap:Header>
            <soap:Body>
                <ns:getPassword>
                    <ns:UserId>'.$bseUserId.'</ns:UserId>
                    <ns:MemberId>'.$bseMemberID.'</ns:MemberId>
                    <ns:Password>'.$bsePassword.'</ns:Password>
                    <ns:PassKey>ts</ns:PassKey>
                </ns:getPassword>
            </soap:Body>
            </soap:Envelope>';
            $res = $this->soapCall($svcUploadUrl, $body, $headers);
            $data = $this->soapPswdResToArr($res, $name_space);
            $bseGenPassword = explode('|', $data[0]);
            if($bseGenPassword == 100) {
                return $bseGenPassword[1];
            } else {
                return $bseGenPassword[1];
            }
        }
        catch ( \Exception $e) {
            return $e->getMessage();
        }
    }

    public function bseGenPasswordForUpload($soapFileUploadAction, $soapFileUploadTo, $soapXmlnsTem, $soapXmlnsStar, $bseUserId, $bseMemberID, $bsePassword) {
        try {
            $headers = [
                'Content-Type' => 'application/soap+xml; charset=utf-8',
                'SOAPAction' => $soapFileUploadAction,
                'To' => $soapFileUploadTo
            ];
            $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="'.$soapXmlnsTem.'" xmlns:star="'.$soapXmlnsStar.'">
                 <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                    <wsa:Action>'.$soapFileUploadAction.'</wsa:Action>
                    <wsa:To>'.$soapFileUploadTo.'</wsa:To>
                </soap:Header>
                <soap:Body>
                    <tem:GetPassword>
                       <tem:Param>
                          <star:MemberId>'.$bseMemberID.'</star:MemberId>
                          <star:Password>'.$bsePassword.'</star:Password>
                          <star:UserId>'.$bseUserId.'</star:UserId>
                       </tem:Param>
                    </tem:GetPassword>
                 </soap:Body>
            </soap:Envelope>';
            $res = $this->soapCall($soapFileUploadTo, $body, $headers);
            $xmlObject = simplexml_load_string($res);
            $xmlObject->registerXPathNamespace('b', $soapXmlnsStar);
            $status = $xmlObject->xpath('//b:Status')[0];
            if($status == 100) {
                $getPswd = $xmlObject->xpath('//b:ResponseString');
                return $getPswd[0];
            } else {
                return NULL;
            }
        }
        catch ( \Exception $e) {
            return $e;
        }
    }

    public function bseGenPasswordForOrder($actionUrl, $toUrl, $name_space, $bseAuthData) {
        try {
            $headers = [
                'Content-Type' => 'application/soap+xml; charset=utf-8',
                'SOAPAction' => $actionUrl,
                'To' => $toUrl
            ];
            $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$name_space.'">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                    <wsa:Action>'.$actionUrl.'</wsa:Action>
                    <wsa:To>'.$toUrl.'</wsa:To>
                </soap:Header>
                <soap:Body>
                    <ns:getPassword>
                        <ns:UserId>'.$bseAuthData['bseUserId'].'</ns:UserId>
                        <ns:MemberId>'.$bseAuthData['bseMemberId'].'</ns:MemberId>
                        <ns:Password>'.$bseAuthData['bsePassword'].'</ns:Password>
                        <ns:PassKey>'.$bseAuthData['bsePassKey'].'</ns:PassKey>
                    </ns:getPassword>
                </soap:Body>
                </soap:Envelope>';
            $res = $this->soapCall($toUrl, $body, $headers);
            $data = $this->soapPswdResToArr($res, $name_space);
            $bseGenPassword = explode('|', $data[0]);
            if($bseGenPassword == 100) {
                return $bseGenPassword[1];
            } else {
                return $bseGenPassword[1];
            }
        }
        catch ( \Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBseAuthData() {
        if(env("app_status") == "test") {
            $envBSEData['bseMemberId'] = explode(',', env('MEMBERID'))[0];
            $envBSEData['bseUserId'] = explode(',', env('USERID'))[0];
            $envBSEData['bsePassword'] = explode(',', env('PASSWORD'))[0];
            $envBSEData['bsePassKey'] = explode(',', env('PASSKEY'))[0];
        } else {
            if(env("app_status") == "live") {
                $envBSEData['bseMemberID'] = explode(',', env('MEMBERID'))[1];
                $envBSEData['bseUserId'] = explode(',', env('USERID'))[1];
                $envBSEData['bsePassword'] = explode(',', env('PASSWORD'))[1];
                $envBSEData['bsePassKey'] = explode(',', env('PASSKEY'))[1];
            }
        }
        return $envBSEData;
    }

    public function getFatcaData() {
        if(env("app_status") == "test") {
            $envBSEData['soapPswdUrl'] = explode(',', env('SOAP_PSWD_ACTION'))[0];
            $envBSEData['svcUploadUrl'] = explode(',', env('SVC_UPLOAD_URL'))[0];
            $envBSEData['name_space'] = explode(',', env('NAME_SPACE'))[0];
            $envBSEData['soapFatcaUrl'] = explode(',', env('SOAP_FATCA_ACTION'))[0];            
        } else {
            if(env("app_status") == "live") {
                $envBSEData['svcUploadUrl'] = explode(',', env('SVC_UPLOAD_URL'))[1];
                $envBSEData['soapPswdUrl'] = explode(',', env('SOAP_PSWD_ACTION'))[1];
                $envBSEData['name_space'] = explode(',', env('NAME_SPACE'))[1];
                $envBSEData['soapFatcaUrl'] = explode(',', env('SOAP_FATCA_ACTION'))[1];
            }
        }
        return $envBSEData;
    }

    public function getUCCData() {
        if(env("app_status") == "test") {
            $envBSEData['soapPswdUrl'] = explode(',', env('SOAP_PSWD_ACTION'))[0];
            $envBSEData['svcUploadUrl'] = explode(',', env('SVC_UPLOAD_URL'))[0];
            $envBSEData['name_space'] = explode(',', env('NAME_SPACE'))[0];
            $envBSEData['ucc_reg'] = explode(',', env('UCC_REG'))[0];            
        } else {
            if(env("app_status") == "live") {
                $envBSEData['svcUploadUrl'] = explode(',', env('SVC_UPLOAD_URL'))[1];
                $envBSEData['soapPswdUrl'] = explode(',', env('SOAP_PSWD_ACTION'))[1];
                $envBSEData['name_space'] = explode(',', env('NAME_SPACE'))[1];
                $envBSEData['ucc_reg'] = explode(',', env('UCC_REG'))[1];
            }
        }
        return $envBSEData;
    }

    public function getEnvData() {
        if(env("app_status") == "test") {
            $envBSEData['bseMemberID'] = explode(',', env('MEMBERID'))[0];
            $envBSEData['bseUserId'] = explode(',', env('USERID'))[0];
            $envBSEData['bsePassword'] = explode(',', env('PASSWORD'))[0];
            $envBSEData['bsePassKey'] = explode(',', env('PASSKEY'))[0];
            $envBSEData['bseUrl'] = explode(',', env('WSDL_UPLOAD_URL'))[0];
            $envBSEData['svcUploadUrl'] = explode(',', env('SVC_UPLOAD_URL'))[0];
            $envBSEData['soapPswdUrl'] = explode(',', env('SOAP_PSWD_ACTION'))[0];
            $envBSEData['name_space'] = explode(',', env('NAME_SPACE'))[0];
            $envBSEData['soapFatcaUrl'] = explode(',', env('SOAP_FATCA_ACTION'))[0];
            $envBSEData['svcUploadFileUrl'] = explode(',', env('SVC_UPLOADFILE_URL'))[0];
            $envBSEData['soapPswdUploadUrl'] = explode(',', env('SOAP_PSWD_UPLOAD_ACTION'))[0];
            $envBSEData['soapUploadFileUrl'] = explode(',', env('SOAP_UPLOAD_FILE_ACTION'))[0];
            
        } else {
            if(env("app_status") == "live") {
                $envBSEData['bseMemberID'] = explode(',', env('MEMBERID'))[1];
                $envBSEData['bseUserId'] = explode(',', env('USERID'))[1];
                $envBSEData['bsePassword'] = explode(',', env('PASSWORD'))[1];
                $envBSEData['bsePassKey'] = explode(',', env('PASSKEY'))[1];
                $envBSEData['bseUrl'] = explode(',', env('WSDL_UPLOAD_URL'))[1];
                $envBSEData['svcUploadUrl'] = explode(',', env('SVC_UPLOAD_URL'))[1];
                $envBSEData['soapPswdUrl'] = explode(',', env('SOAP_PSWD_ACTION'))[1];
                $envBSEData['name_space'] = explode(',', env('NAME_SPACE'))[1];
                $envBSEData['soapFatcaUrl'] = explode(',', env('SOAP_FATCA_ACTION'))[1];
                $envBSEData['svcUploadFileUrl'] = explode(',', env('SVC_UPLOADFILE_URL'))[1];
                $envBSEData['soapPswdUploadUrl'] = explode(',', env('SOAP_PSWD_UPLOAD_ACTION'))[1];
                $envBSEData['soapUploadFileUrl'] = explode(',', env('SOAP_UPLOAD_FILE_ACTION'))[1];
            }
        }
        return $envBSEData;
    }

    public function getBseAOFUploadData() {
        if(env("app_status") == "test") {
            $envBSEData['soapFileUploadGetPswdAction'] = explode(',', env('SOAP_FILE_UPLOAD_GET_PSWD_ACTION'))[0];
            $envBSEData['soapFileUploadGetPswdTo'] = explode(',', env('SOAP_FILE_UPLOAD_GET_PSWD_TO'))[0];
            $envBSEData['soapXmlnsTem'] = explode(',', env('SOAP_XMLNS_TEM'))[0];
            $envBSEData['soapXmlnsStar'] = explode(',', env('SOAP_XMLNS_STAR'))[0];
            $envBSEData['soapFileUploadAction'] = explode(',', env('SOAP_FILE_UPLOAD_ACTION'))[0];
        } else {
            if(env("app_status") == "live") {
                $envBSEData['soapFileUploadAction'] = explode(',', env('SOAP_FILE_UPLOAD_GET_PSWD_ACTION'))[1];
                $envBSEData['soapFileUploadTo'] = explode(',', env('SOAP_FILE_UPLOAD_GET_PSWD_TO'))[1];
                $envBSEData['soapXmlnsTem'] = explode(',', env('SOAP_XMLNS_TEM'))[1];
                $envBSEData['soapXmlnsStar'] = explode(',', env('SOAP_XMLNS_STAR'))[1];
                $envBSEData['soapFileUploadAction'] = explode(',', env('SOAP_FILE_UPLOAD_ACTION'))[1];

            }
        }
        return $envBSEData;
    }

    public function getBseOrderEnvData() {
        if(env("app_status") == "test") {
            $envBSEData['bseMforderUrl'] = explode(',', env('BSE_MFORDER_URL'))[0];
            $envBSEData['bseMforderGetPswdAction'] = explode(',', env('BSE_MFORDER_GETPSWD_ACTION'))[0];
            $envBSEData['bserMforderTo'] = explode(',', env('BSE_MFORDER_TO'))[0];
            $envBSEData['bserMforderNameSpace'] = explode(',', env('BSE_MFORDER_NAMESPACE'))[0];
            $envBSEData['bserMforderNSFor'] = explode(',', env('BSE_MFORDER_NS_MFOR'))[0];
            $envBSEData['bserMforderOrderEntryAction'] = explode(',', env('BSE_MFORDER_ORDERENTRY_ACTION'))[0];
            
        } else {
            if(env("app_status") == "live") {
                $envBSEData['bseMforderUrl'] = explode(',', env('BSE_MFORDER_URL'))[1];
                $envBSEData['bseMforderGetPswdAction'] = explode(',', env('BSE_MFORDER_GETPSWD_ACTION'))[1];
                $envBSEData['bserMforderTo'] = explode(',', env('BSE_MFORDER_TO'))[1];
                $envBSEData['bserMforderNameSpace'] = explode(',', env('BSE_MFORDER_NAMESPACE'))[1];
                $envBSEData['bserMforderNSFor'] = explode(',', env('BSE_MFORDER_NS_MFOR'))[1];
                $envBSEData['bserMforderOrderEntryAction'] = explode(',', env('BSE_MFORDER_ORDERENTRY_ACTION'))[1];
            }
        }
        return $envBSEData;
    }

    public function getStarMFWebServiceEnvData() {
        if(env("app_status") == "test") {
            $envBSEData['starMFWebServiceNameSpace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[0];
            $envBSEData['starMFWebServiceTo'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[0];
            $envBSEData['starMFWebServiceGetPswdAction'] = explode(',', env('STAR_MF_WEB_SERVICE_GETPSWD_ACTION'))[0];
        } else {
            if(env("app_status") == "live") {
                $envBSEData['starMFWebServiceNameSpace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[1];
                $envBSEData['starMFWebServiceTo'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[1];
                $envBSEData['starMFWebServiceGetPswdAction'] = explode(',', env('STAR_MF_WEB_SERVICE_GETPSWD_ACTION'))[1];
            }
        }
        return $envBSEData;
    }

    public function mandateRegistration($accountNo, $accType, $ifsc, $bse_id, $user_id, $bankid) {
        $bseAuthData = $this->getBseAuthData();
        $envBSEFatcaDataJson = $this->getFatcaData();
        if($bse_id != null) {
            $userAuthController = new UserAuthController();
            $userData = $userAuthController->getUserDetails($user_id);
            $date = date('Y-m-d');
            $date1 = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $date2 = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->addYears(100)->format('d/m/Y');
            $transBSEArr = array(
                "CLIENT CODE" => $bse_id,
                "AMOUNT" => 100000,
                "Mandate Type" => "N",
                "ACCOUNT NO" => $accountNo,
                "A/C TYPE" => $accType,
                "IFSC CODE" => $ifsc,
                "MICR CODE" => "",
                "START DATE" => $date1,
                "END DATE" => $date2
            );
            $pipeValues = implode("|",$transBSEArr);
            $bseGenPassword = $this->bseGenPassword($envBSEFatcaDataJson['soapPswdUrl'], $envBSEFatcaDataJson['svcUploadUrl'], $envBSEFatcaDataJson['name_space'], $bseAuthData['bseUserId'], $bseAuthData['bseMemberId'], $bseAuthData['bsePassword']);
            try {
                $headers = [
                    'Content-Type' => 'application/soap+xml; charset=utf-8',
                    'SOAPAction' => $envBSEFatcaDataJson['soapFatcaUrl'],
                    'To' => $envBSEFatcaDataJson['svcUploadUrl']
                ];
                $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$envBSEFatcaDataJson['name_space'].'">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                    <wsa:Action>'.$envBSEFatcaDataJson['soapFatcaUrl'].'</wsa:Action>
                    <wsa:To>'.$envBSEFatcaDataJson['svcUploadUrl'].'</wsa:To>
                </soap:Header>
                <soap:Body>
                    <ns:MFAPI>
                        <ns:Flag>06</ns:Flag>
                        <ns:UserId>'.$bseAuthData['bseUserId'].'</ns:UserId>
                        <ns:EncryptedPassword>'.$bseGenPassword.'</ns:EncryptedPassword>
                        <ns:param>'.$pipeValues.'</ns:param>
                    </ns:MFAPI>
                </soap:Body>
                </soap:Envelope>';
                $res = $this->soapCall($envBSEFatcaDataJson['svcUploadUrl'], $body, $headers);
                $data = $this->soapFatcaResToArr($res, $envBSEFatcaDataJson['name_space']);
                $bseMandateStatus = explode('|', $data[0]);
                if($bseMandateStatus[0] == "100") {
                    Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                        'mandateInput' => $body,
                        'mandateOutput' => $data,
                        'mandateStatus' => "SUCCESS",
                        'mandate_id' => $bseMandateStatus[2],
                        'mandate_start_dt' => \Carbon\Carbon::createFromFormat('d/m/Y', $date1)->format('Y-m-d'),
                        'mandate_end_dt' => \Carbon\Carbon::createFromFormat('d/m/Y', $date2)->format('Y-m-d')
                    ]);
                    $data = [
                        "statusCode" => $bseMandateStatus[0],
                        "message" => $bseMandateStatus[1],
                        "mandate_id" => $bseMandateStatus[2]
                    ];
                } else {
                    Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                        'mandateInput' => $body,
                        'mandateOutput' => $data,
                        'mandateStatus' => "FAILURE",
                        'mandate_start_dt' => \Carbon\Carbon::createFromFormat('d/m/Y', $date1)->format('Y-m-d'),
                        'mandate_end_dt' => \Carbon\Carbon::createFromFormat('d/m/Y', $date2)->format('Y-m-d')
                    ]);
                    $data = [
                        "statusCode" => $bseMandateStatus[0],
                        "message" => $bseMandateStatus[1]
                    ];
                }
            }
            catch ( \Exception $e) {
                $data = [
                    "statusCode" => 101,
                    "message" => $e->getMessage()
                ];
            }
        } else {
            $data = [
                "statusCode" => 500,
                "message" => "BSE User account not found. Contact customer support"
            ];
        }
        return $data;
    }



}
