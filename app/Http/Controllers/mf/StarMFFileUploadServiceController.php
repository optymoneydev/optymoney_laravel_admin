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
Use App\Models\MandateDetails;
use Illuminate\Support\Arr;
use SoapClient;
use File;
use Image;
use SimpleXMLElement;

class StarMFFileUploadServiceController extends Controller
{
    
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

    public function pswdStarMFFileUploadService($actionUrl, $toUrl, $name_space, $bseAuthData) {
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

    public function uploadMandateScanFile(Request $request) {
        $bseAuthData = $this->getBseAuthData();
        $envBseAOFUploadJson = $this->getBseAOFUploadData();
        $user = auth('userapi')->user();
        $id = $user->pk_user_id;
        if($user) {
            $userAuthController = new UserAuthController();
            $userData = $userAuthController->getUserDetails($user->pk_user_id);
            $client_code = $this->ucc_n_create($user->pk_user_id);
            $bseGenPassword = $this->pswdStarMFFileUploadService($envBseAOFUploadJson['soapFileUploadGetPswdAction'], $envBseAOFUploadJson['soapFileUploadGetPswdTo'], $envBseAOFUploadJson['soapXmlnsTem'], $envBseAOFUploadJson['soapXmlnsStar'], $bseAuthData['bseUserId'], $bseAuthData['bseMemberId'], $bseAuthData['bsePassword']);
            
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

    public function getPswdEnvData() {
        if(env("app_status") == "test") {
            $envBSEData['starMFWebServiceNameSpace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[0];
            $envBSEData['starMFWebServiceTo'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[0];
            $envBSEData['starMFWebServiceGetPswdAction'] = explode(',', env('STAR_MF_WEB_SERVICE_GETPSWD_ACTION'))[0];
            $envBSEData['starMFWebServiceGetAccessTokenAction'] = explode(',', env('STAR_MF_WEB_SERVICE_GETACCESSTOKEN_ACTION'))[0];
            $envBSEData['starMFWebServiceStarName'] = explode(',', env('STAR_NAME'))[0];
            
        } else {
            if(env("app_status") == "live") {
                $envBSEData['starMFWebServiceNameSpace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[1];
                $envBSEData['starMFWebServiceTo'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[1];
                $envBSEData['starMFWebServiceGetPswdAction'] = explode(',', env('STAR_MF_WEB_SERVICE_GETPSWD_ACTION'))[1];
                $envBSEData['starMFWebServiceGetAccessTokenAction'] = explode(',', env('STAR_MF_WEB_SERVICE_GETACCESSTOKEN_ACTION'))[1];
                $envBSEData['starMFWebServiceStarName'] = explode(',', env('STAR_NAME'))[0];
            }
        }
        return $envBSEData;
    }

    public function getMandateDetailsEnvData() {
        if(env("app_status") == "test") {
            $envBSEData['starMFWebServiceNameSpace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[0];
            $envBSEData['starName'] = explode(',', env('STAR_NAME'))[0];
            $envBSEData['starMFWebServiceTo'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[0];
            $envBSEData['starMandateDetailsAction'] = explode(',', env('STAR_MANDATE_DETAILS_ACTION'))[0];
        } else {
            if(env("app_status") == "live") {
                $envBSEData['starMFWebServiceNameSpace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[1];
                $envBSEData['starName'] = explode(',', env('STAR_NAME'))[1];
                $envBSEData['starMFWebServiceTo'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[1];
                $envBSEData['starMandateDetailsAction'] = explode(',', env('STAR_MANDATE_DETAILS_ACTION'))[1];
            }
        }
        return $envBSEData;
    }

    public function getMandateAuthEnvData() {
        if(env("app_status") == "test") {
            $envBSEData['namespace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[0];
            $envBSEData['starName'] = explode(',', env('STAR_NAME'))[0];
            $envBSEData['to'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[0];
            $envBSEData['action'] = explode(',', env('STAR_MANDATE_AUTH_ACTION'))[0];
        } else {
            if(env("app_status") == "live") {
                $envBSEData['namespace'] = explode(',', env('STAR_MF_WEB_SERVICE_NAMESPACE'))[1];
                $envBSEData['starName'] = explode(',', env('STAR_NAME'))[1];
                $envBSEData['to'] = explode(',', env('STAR_MF_WEB_SERVICE_TO'))[1];
                $envBSEData['action'] = explode(',', env('STAR_MANDATE_AUTH_ACTION'))[1];
            }
        }
        return $envBSEData;
    }

    public function eMandateAuthURL($bankid, $bse_id, $mandateId) {
        $bseAuthData = $this->getBseAuthData();
        $pswdEnvData = $this->getPswdEnvData();
        $mandateAuthEnvData = $this->getMandateAuthEnvData();
        if($bse_id != null) {
            $bankDetails = Bfsi_bank_details::where('pk_bank_detail_id',$bankid)->get()->first();
            try {
                $headers = [
                    'Content-Type' => 'application/soap+xml; charset=utf-8',
                    'SOAPAction' => $mandateAuthEnvData['action']
                ];
                $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://www.bsestarmf.in/2016/01/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFWebService">
                    <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                        <wsa:Action>'.$mandateAuthEnvData['action'].'</wsa:Action>
                        <wsa:To>'.$mandateAuthEnvData['to'].'</wsa:To>
                    </soap:Header>
                    <soap:Body>
                        <ns:EMandateAuthURL>
                            <ns:Param>
                                <star:ClientCode>'.$bse_id.'</star:ClientCode>
                                <star:MandateID>'.$mandateId.'</star:MandateID>
                                <star:MemberCode>'.$bseAuthData['bseMemberId'].'</star:MemberCode>
                                <star:Password>'.$bseAuthData['bsePassword'].'</star:Password>
                                <star:UserId>'.$bseAuthData['bseUserId'].'</star:UserId>
                            </ns:Param>
                        </ns:EMandateAuthURL>
                    </soap:Body>
                </soap:Envelope>';
                $res = $this->soapCall($mandateAuthEnvData['to'], $body, $headers);
                $xmlObject = simplexml_load_string($res);
                $xmlObject->registerXPathNamespace('b', $mandateAuthEnvData['starName']);
                $status = $xmlObject->xpath('//b:Status');
                
                
                
                if($status[1] == 100) {
                    Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                        'mandateBSEStatus' => $status[1],
                        'mandateBSEInput' => $body,
                        'mandateBSEOutput' => $res,
                        'mandateBSEStatusMessage' => $status[0]
                    ]);
                    $data = [
                        "statusCode" => $status[1],
                        "message" => $status[0]
                    ];
                } else {
                    if($status[1]==101) {
                        $message = $xmlObject->xpath('//b:ResponseString');
                        Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                            'mandateBSEStatus' => $status[1],
                            'mandateBSEInput' => $body,
                            'mandateBSEOutput' => $res,
                            'mandateBSEStatusMessage' => $message[0]
                        ]);
                        $data = [
                            "statusCode" => $status[1],
                            "message" => $message[0]
                        ];
                    } else {
                        $data = [
                            "statusCode" => 500,
                            "message" => "Server Error"
                        ];
                    }
                }
            }
            catch ( \Exception $e) {
                $data = [
                    "statusCode" => 500,
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
        
        $res = $this->soapCall('https://bsestarmfdemo.bseindia.com/StarMFWebService/StarMFWebService.svc/Secure', $body, $headers);
        $xmlObject = simplexml_load_string($res);
        $xmlObject->registerXPathNamespace('b', "http://schemas.datacontract.org/2004/07/StarMFWebService");
        $status = $xmlObject->xpath('//b:Status')[0];
        $data = [
            "statusCode" => $status,
            "message" => $xmlObject->xpath('//b:ResponseString')[0]
        ];
        return $data;
    }

    public function mandateDetails($bankid, $bse_id) {
        $bseAuthData = $this->getBseAuthData();
        $pswdEnvData = $this->getPswdEnvData();
        $mandateDetailsEnvData = $this->getMandateDetailsEnvData();
        if($bse_id != null) {
            $bankDetails = Bfsi_bank_details::where('pk_bank_detail_id',$bankid)->get()->first();
            $bseGenPassword = $this->getAccessToken($pswdEnvData['starMFWebServiceGetAccessTokenAction'], 
                $pswdEnvData['starMFWebServiceTo'], $pswdEnvData['starMFWebServiceNameSpace'], $pswdEnvData['starMFWebServiceStarName'], $bseAuthData);
            try {
                $headers = [
                    'Content-Type' => 'application/soap+xml; charset=utf-8',
                    'SOAPAction' => $mandateDetailsEnvData['starMandateDetailsAction'],
                    'To' => $mandateDetailsEnvData['starMFWebServiceTo']
                ];
                $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$mandateDetailsEnvData['starMFWebServiceNameSpace'].'" xmlns:star="'.$mandateDetailsEnvData['starName'].'">
                    <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                        <wsa:Action>'.$mandateDetailsEnvData['starMandateDetailsAction'].'</wsa:Action>
                        <wsa:To>'.$mandateDetailsEnvData['starMFWebServiceTo'].'</wsa:To></soap:Header>
                    <soap:Body>
                        <ns:MandateDetails>
                            <ns:Param>
                                <star:ClientCode>'.$bse_id.'</star:ClientCode>
                                <star:EncryptedPassword>'.$bseGenPassword.'</star:EncryptedPassword>
                                <star:FromDate>04/09/2023</star:FromDate>
                                <star:MandateId>809151</star:MandateId>
                                <star:MemberCode>'.$bseAuthData['bseMemberId'].'</star:MemberCode>
                                <star:ToDate>04/09/2123</star:ToDate>
                            </ns:Param>
                        </ns:MandateDetails>
                    </soap:Body>
                </soap:Envelope>';

                $res = $this->soapCall($mandateDetailsEnvData['starMFWebServiceTo'], $body, $headers);

                $xmlObject = simplexml_load_string($res);
                $xmlObject->registerXPathNamespace('b', $mandateDetailsEnvData['starName']);
                $status = $xmlObject->xpath('//b:Status');
                // $md = new MandateDetails();
                // $md->mandate_code = json_decode($xmlObject->xpath('//b:MandateId')[0]);
                // $md->client_code = $bse_id;
                // $md->status = json_decode($status[1]);
                // $md->bankId = $bankid;
                // $md->amount = json_decode($xmlObject->xpath('//b:Amount')[0]);
                // $md->remarks = json_decode($xmlObject->xpath('//b:Remarks')[0]);
                // // $md->collectionType = json_decode($xmlObject->xpath('//b:CollectionType')[0]);
                // // $md->regnDate = json_decode($xmlObject->xpath('//b:RegnDate')[0]);
                // // 
                // // $md->mandateStatus = json_decode($status[0]);
                // // $md->approvedDate = json_decode($xmlObject->xpath('//b:CollectionType')[0]);
                // // $md->umrnno = json_decode($xmlObject->xpath('//b:UMRNNo')[0]);
                // // $md->uploadDate = json_decode($xmlObject->xpath('//b:UploadDate')[0]);
                // return $md;
                // $mdSaveStatus = $md->save();
                
                if($status[1] == 100) {
                    Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                        'mandateBSEStatus' => $status[1],
                        'mandateBSEInput' => $body,
                        'mandateBSEOutput' => $res,
                        'mandateBSEStatusMessage' => $status[0]
                    ]);
                    $data = [
                        "statusCode" => $status[1],
                        "message" => $status[0]
                    ];
                } else {
                    $data = [
                        "statusCode" => 101,
                        "message" => "Failed"
                    ];
                }
            }
            catch ( \Exception $e) {
                $data = [
                    "statusCode" => 500,
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
