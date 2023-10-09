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

class StarMFWebServiceController extends Controller
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

    public function pswdStarMFWebService($actionUrl, $toUrl, $name_space, $bseAuthData) {
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

    public function getAccessToken($actionUrl, $toUrl, $name_space, $starname, $bseAuthData) {
        try {
            $headers = [
                'Content-Type' => 'application/soap+xml; charset=utf-8',
                'SOAPAction' => $actionUrl,
                'To' => $toUrl
            ];
            $body = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$name_space.'" xmlns:star="'.$starname.'">
                <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
                    <wsa:Action>'.$actionUrl.'</wsa:Action>
                    <wsa:To>'.$toUrl.'</wsa:To>
                </soap:Header>
                <soap:Body>
                    <ns:GetAccessToken>
                        <ns:Param>
                            <star:MemberId>'.$bseAuthData['bseMemberId'].'</star:MemberId>
                            <star:PassKey>'.$bseAuthData['bsePassKey'].'</star:PassKey>
                            <star:Password>'.$bseAuthData['bsePassword'].'</star:Password>
                            <star:RequestType>Mandate</star:RequestType>
                            <star:UserId>'.$bseAuthData['bseUserId'].'</star:UserId>
                        </ns:Param>
                    </ns:GetAccessToken>
                </soap:Body>
            </soap:Envelope>';
            $res = $this->soapCall($toUrl, $body, $headers);
            $xmlObject = simplexml_load_string($res);
            $xmlObject->registerXPathNamespace('b', $starname);
            $status = $xmlObject->xpath('//b:Status')[0];
            if($status == 100) {
                $getPswd = $xmlObject->xpath('//b:ResponseString');
                return $getPswd[0];
            } else {
                return NULL;
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
                $message = $xmlObject->xpath('//b:ResponseString');
                Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                    'mandateAuthInput' => $body,
                    'mandateAuthOutput' => $res,
                    'mandateAuthStatusMessage' => $message[0][0]
                ]);
                if($status[0] == 100) {
                    Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                        'mandateAuthStatus' => "SUCCESS",
                    ]);
                    $data = [
                        "statusCode" => $status[0][0],
                        "message" => $message[0][0]
                    ];
                } else {
                    if($status[0]==101) {
                        Bfsi_bank_details::where('pk_bank_detail_id', $bankid)->update([
                            'mandateAuthStatus' => "FAILURE",
                        ]);
                        $data = [
                            "statusCode" => $status[0][0],
                            "errormessage" => $message[0][0]
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
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $bankDetails['mandate_start_dt'])->format('d/m/Y');
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $bankDetails['mandate_end_dt'])->addYears(100)->format('d/m/Y');
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
                                <star:FromDate>'.$startDate.'</star:FromDate>
                                <star:MandateId>'.$bankDetails['mandate_id'].'</star:MandateId>
                                <star:MemberCode>'.$bseAuthData['bseMemberId'].'</star:MemberCode>
                                <star:ToDate>'.$endDate.'</star:ToDate>
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
