<?php

namespace App\Http\Controllers\mf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\customer\UserAuthController;
use App\Http\Controllers\Nsdl\NsdlController;
use Illuminate\Http\Request;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_bank_details;
use SoapClient;
use File;

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

    public function createFatcaTest(Request $request) {
        $envBSEDataJson = $this->getEnvData();
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
                        <ns:Flag>01</ns:Flag>
                        <ns:UserId>'.$envBSEDataJson['bseUserId'].'</ns:UserId>
                        <ns:EncryptedPassword>'.$bseGenPassword.'</ns:EncryptedPassword>
                        <ns:param>'.$pipeValues.'</ns:param>
                    </ns:MFAPI>
                </soap:Body>
                </soap:Envelope>';
                $res = $this->soapCall($envBSEDataJson['svcUploadUrl'], $body, $headers);
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

    public function createBSE(Request $request) {
        $envBSEDataJson = $this->getEnvData();
        $user = auth('userapi')->user();
        $id = $user->pk_user_id;
        if($user) {
            $userAuthController = new UserAuthController();
            $userData = $userAuthController->getUserDetails($user->pk_user_id);
            $date = date('Y-m-d H:i:s');
            $dobFormatted = \Carbon\Carbon::createFromFormat('Y-m-d', $userData->dob)->format('d/m/Y');
            $client_code = $this->ucc_n_create($user->pk_user_id);
            $pipeValues = [];
        //     $userParam = array(
        //         "Client_Code_(UCC)" => $client_code,
        //         "Primary_Holder_First_Name" => $userData->cust_name,"Primary_Holder_Middle_Name" => "","Primary_Holder_Last_Name" => "",
        //         "Tax_Status" => $userData->taxStatus,
        //         "Gender" => $userData->sex[0],
        //         "Primary_Holder_DOB/Incorporation" => \Carbon\Carbon::createFromFormat('Y-m-d', $userData->dob)->format('d/m/Y'),
        //         "Occupation_Code" => $userData->occupationCode,
        //         "Holding_Nature" => $userData->clientHolding,
        //         "Second_Holder_First_Name" => "","Second_Holder_Middle_Name" => "","Second_Holder_Last_Name" => "",
        //         "Third_Holder_First_Name" => "","Third_Holder_Middle_Name" => "","Third_Holder_Last_Name" => "",
        //         "Second_Holder_DOB" => "",
        //         "Third_Holder_DOB" => "",
        //         "Guardian_First_Name" => "","Guardian_Middle_Name" => "","Guardian_Last_Name" => "","Guardian_DOB" => "",
        //         "Primary_Holder_PAN_Exempt" => "N","Second_Holder_PAN_Exempt" => "","Third_Holder_PAN_Exempt" => "","Guardian_PAN_Exempt" => "",
        //         "Primary_Holder_PAN" => $userData->pan_number,"Second_Holder_PAN" => "","Third_Holder_PAN" => "","Guardian_PAN" => "",
        //         "Primary_Holder-_Exempt_Category" => "","Second_Holder_Exempt_Category" => "","Third_Holder_Exempt_Category" => "","Guardian_Exempt_Category" => "",
        //         "Client_Type" => "P",
        //         "PMS" => "",
        //         "Default_DP" => "",
        //         "CDSL_DPID" => "",
        //         "CDSLCLTID" => "",
        //         "CMBP_Id" => "",
        //         "NSDLDPID" => "",
        //         "NSDLCLTID" => "");
        //     $pipeValues[] = implode("|",$userParam);

        //     $bankdata = Bfsi_bank_details::where('fr_user_id',$user->pk_user_id)->get();
        //     $i = 1; 
        //     $j = 1; 
        //     $data = [];
        //     $banks = [];
        //     for ($i = 1; $i <= 5; $i++) {
        //         if($i <= count($bankdata)) {
        //             foreach ($bankdata as $bank) {
        //                 if($j <= 5) {
        //                     if($bank->ac_type == "" || $bank->ac_type == null) {
        //                         $data['Account_Type_'.$i] = 'SB';
        //                     } else {
        //                         if($bank->ac_type == "savings") {
        //                             $data['Account_Type_'.$i] = 'SB';
        //                         } else {
        //                             if($bank->ac_type == "current") {
        //                                 $data['Account_Type_'.$i] = 'CB';
        //                             }
        //                         }
        //                     }
        //                     $data['Account_No_'.$i] = $bank->acc_no;
        //                     $data['MICR_No_'.$i] = "";
        //                     $data['IFSC_Code_'.$i] = $bank->ifsc_code;
        //                     if($bank->default_bank == null) {
        //                         $data['Default_Bank_Flag'] = 'N';
        //                     } else {
        //                         $data['Default_Bank_Flag'] = $bank->default_bank;
        //                     }
        //                     $banks[] = implode("|",$data);
        //                     $data = [];
        //                 }
        //                 $j++;
        //             }
        //             $i = $j-1;
        //         } else {
        //             $data['Account_Type_'.$i] = "";
        //             $data['Account_No_'.$i] = "";
        //             $data['MICR_No_'.$i] = "";
        //             $data['IFSC_Code_'.$i] = "";
        //             $data['Default_Bank_Flag'] = "";
        //             $banks[] = implode("|",$data);
        //             $data = [];
        //         }
        //     }
        //     $banks_pipevalue = implode("|",$banks);
        //     $pipeValues[] = $banks_pipevalue;
        //     if($userData->communication_mode == "" || $userData->communication_mode == NULL) {
        //         $communication_mode = "E";
        //     } else {
        //         $communication_mode = $userData->communication_mode;
        //     }
        //     if($userData->isAadhaarUpdated == "Y") {
        //         $isAadhaarUpdated = "Y";
        //     } else {
        //         $isAadhaarUpdated = "N";
        //     }
        //     $userParam1 = array(
        //         "Cheque_name" => "",
        //         "Div_pay_mode" => "02",
        //         "Address_1" => str_replace(","," ",str_replace("-","",$userData->address1)),
        //         "Address_2" => str_replace(","," ",str_replace("-","",$userData->address2)),
        //         "Address_3" => str_replace(","," ",str_replace("-","",$userData->address3)),
        //         "City" => $userData->city,"State" => $userData->bsestatecode,
        //         "Pincode" => $userData->pincode,"Country" => "INDIA",
        //         "Resi._Phone" => "","Resi._Fax" => "","Office_Phone" => "","Office_Fax" => "","Email" => $userData->email,
        //         "Communication_Mode" => $communication_mode,
        //         "Foreign_Address_1" => "","Foreign_Address_2" => "","Foreign_Address_3" => "",
        //         "Foreign_Address_City" => "","Foreign_Address_Pincode" => "","Foreign_Address_State" => "",
        //         "Foreign_Address_Country" => "","Foreign_Address_Resi_Phone" => "","Foreign_Address_Fax" => "",
        //         "Foreign_Address_Off._Phone" => "",
        //         "Foreign_Address_Off._Fax" => "",
        //         "Indian_Mobile_No." => $userData->contact_no);
        //     $userParam1_pipevalue = implode("|",$userParam1);
        //     $pipeValues[] = $userParam1_pipevalue;
        //     $userParam2 = array(
        //         // "Nominee_1_Name" => $userData->nominee_name,"Nominee_1_Relationship" => $userData->r_of_nominee_w_app,"Nominee_1_Applicable(%)" => "100.00","Nominee_1_DOB" => $userData->nominee_dob,"Nominee_1_Minor_Flag" => "N","Nominee_1_Guardian" => "",
        //         "Nominee_1_Name" => "","Nominee_1_Relationship" => "","Nominee_1_Applicable(%)" => "","Nominee_1_DOB" => "","Nominee_1_Minor_Flag" => "","Nominee_1_Guardian" => "",
        //         "Nominee_2_Name" => "","Nominee_2_Relationship" => "","Nominee_2_Applicable(%)" => "","Nominee_2_DOB" => "","Nominee_2_Minor_Flag" => "","Nominee_2_Guardian" => "",
        //         "Nominee_3_Name" => "","Nominee_3_Relationship" => "","Nominee_3_Applicable(%)" => "","Nominee_3_DOB" => "","Nominee_3_Minor_Flag" => "","Nominee3_Guardian" => "",
        //         "Primary_Holder_KYC_Type" => "K","Primary_Holder__CKYC_Number" => "",
        //         "Second_Holder_KYC_Type" => "","Second_Holder_CKYC_Number" => "",
        //         "Third_Holder_KYC_Type" => "","Third_Holder_CKYC_Number" => "",
        //         "Guardian_KYC_Type" => "","Guardian_CKYC_Number" => "",
        //         "Primary_Holder_KRA_Exempt_Ref._No." => "",
        //         "Second_Holder_KRA_Exempt_Ref._No." => "",
        //         "Third_Holder_KRA_Exempt_Ref._No" => "",
        //         "Guardian_Exempt_Ref._No" => "",
        //         "Aadhaar_Updated" => $isAadhaarUpdated,
        //         "Mapin_Id." => "",
        //         "Paperless_flag" => "Z",
        //         "LEI_No" => "",
        //         "LEI_Validity" => "",
        //         "Filler_1__(_Mobile_Declaration_Flag_)" => "SE",
        //         "Filler_2_(Email_Declaration_Flag_)" => "SE",
        //         "Filler_3" => "");
        //     $userParam2_pipevalue = implode("|",$userParam2);
        //     $pipeValues[] = $userParam2_pipevalue;
        //     $pipeValues_data = implode("|",$pipeValues);
            $bseGenPassword = $this->bseGenPassword($envBSEDataJson['soapPswdUrl'], $envBSEDataJson['svcUploadUrl'], $envBSEDataJson['name_space'], $envBSEDataJson['bseUserId'], $envBSEDataJson['bseMemberID'], $envBSEDataJson['bsePassword']);
            return $bseGenPassword;
        //     if($user->bse_id != null) {
        //         $reg = "MOD";
        //     } else {
        //         $reg = "NEW";
        //     }
        //     try {
        //         $body = array(
        //                 "UserId" => $envBSEDataJson['bseUserId'],
        //                 "MemberCode" => $envBSEDataJson['bseMemberID'],
        //                 "Password" => $envBSEDataJson['bsePassword'],
        //                 "RegnType" => $reg,
        //                 "Param" => $pipeValues_data,
        //                 "Filler1" => "",
        //                 "Filler2" => ""
        //         );
        //         $body_json = json_encode($body);
        //         $client = new \GuzzleHttp\Client();
        //         $res = $client->request('POST', 
        //         explode(',', env('UCC_REG'))[0],[
        //             'body' => $body_json,
        //             'headers' => [
        //                 'Content-Type' => 'application/json',
        //             ]
        //         ]);
        //         $bseStatus = json_decode($res->getBody()->getContents());
        //         if($bseStatus->Status == 0) {
        //             Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
        //                 'bseInput' => $pipeValues_data,
        //                 'bseOutput' => json_encode($bseStatus),
        //                 'bseStatus' => "SUCCESS"
        //             ]);
        //             $responseObj['status'] = "SUCCESS";
        //             $responseObj['message'] = $bseStatus->message;
        //         } else {
        //             Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
        //                 'bseInput' => $pipeValues_data,
        //                 'bseOutput' => json_encode($bseStatus),
        //                 'bseStatus' => "FAILURE"
        //             ]);
        //             $responseObj['status'] = "FAILURE";
        //             $responseObj['message'] = $bseStatus->Remarks;
        //         }
        //     }
        //     catch ( \Exception $e) {
        //         $responseObj['status'] = "FAILURE";
        //         $responseObj['message'] = $e->getMessage();
        //         return $responseObj;
        //     }

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

            // Path where the image is going to be saved
            $uccfileName = $envBSEDataJson['bseMemberID'].$client_code.date("dmY").'.tiff';
            $filePath = $profile_path."/".$uccfileName;
            
            // Write $imgData into the image file
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
                $ucc_file_data = json_encode($r);
                $body = array(
                    "Flag" => "UCC", 
                    "UserId" => $envBSEDataJson['bseUserId'],
                    "EncryptedPassword" => $bseGenPassword, 
                    "MemberCode" => $envBSEDataJson['bseMemberID'],
                    "ClientCode" => $client_code,
                    "FileName" => $uccfileName,
                    "DocumentType" => "NRM",
                    "pFileBytes" => $ucc_file_data,
                    "Filler1" => "",
                    "Filler2" => ""
                );

                $pipeValues = implode("|",$body);
                

                $bseUploadImg = $this->bseUploadImg($index, $bselog_json->ResponseString, $pipeValues, $ucc_file_data);
                $bseUploadImg_json = json_decode($bseUploadImg);
                if($bseUploadImg_json->Status=="101") {
                    $res['msg'] = $bseUploadImg_json->ResponseString;
                    $res['status'] = "failed";
                } else {
                    $res['msg'] = $bseUploadImg->ResponseString;
                    $res['status'] = "success";
                }
            function bseUploadImg($index, $bselog_ep, $pipeValues, $filedata) {
                $data = explode("|",$pipeValues);
                $curl = curl_init();
                $doc_type = "NRM";
                $postfileds = "{\n\t\"ClientCode\":\"".$data[0]."\",\n\t\"DocumentType\":\"".$doc_type."\",\n\t\"EncryptedPassword\":\"".$bseGenPassword."\",\n\t\"FileName\":\"".$data[3]."\",\n\t\"Filler1\":\"null\",\n\t\"Filler2\":\"null\",\n\t\"Flag\":\"UCC\",\n\t\"MemberCode\":\"".$this->CONFIG->bseMemberIds[$index]."\",\n\t\"UserId\":\"".$this->CONFIG->bseUserIds[$index]."\",\n\t\"pFileBytes\":".$filedata."\n}";
                // $postfileds1 = "{\n\t\"ClientCode\":\"".$data[0]."\",\n\t\"DocumentType\":\"".$doc_type."\",\n\t\"EncryptedPassword\":\"".$bselog_ep."\",\n\t\"FileName\":\"".$data[3]."\",\n\t\"Filler1\":\"null\",\n\t\"Filler2\":\"null\",\n\t\"Flag\":\"UCC\",\n\t\"MemberCode\":\"".$this->CONFIG->bseMemberIds[$index]."\",\n\t\"UserId\":\"".$this->CONFIG->bseUserIds[$index]."\",\n\t\"pFileBytes\":\n}";
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->CONFIG->UploadFile_imgUploadBSE[$index],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $postfileds,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json"
                    ),
                ));
        
                $response = curl_exec($curl);
                $err = curl_error($curl);
        
                curl_close($curl);
        
                if ($err) {
                    return "cURL Error #:" . $err;
                } else {
                    return $response;
                }
            }

                if($bseStatus->Status == 0) {
                    Bfsi_users_detail::where('fr_user_id', $user->pk_user_id)->update([
                        'bseInput' => $pipeValues_data,
                        'bseOutput' => json_encode($bseStatus),
                        'bseStatus' => "SUCCESS"
                    ]);
                    $responseObj['status'] = "SUCCESS";
                    $responseObj['message'] = $bseStatus->message;
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

            

            
            $responseObj['status'] = "SUCCESS";
            $responseObj['message'] = "UCC Updated";
            $responseObj['ucc_update'] = $ucc_form_db_status;
        } else {
            $responseObj['status'] = "FAILURE";
            $responseObj['message'] = "Authentication Failed";
        }
        return $responseObj;
    }

    public function soapPswdResToArr($res, $name_space) {
        $xmlObject = simplexml_load_string($res);
        $xmlObject->registerXPathNamespace('ns1', $name_space);
        $getPswd = $xmlObject->xpath('//ns1:getPasswordResponse/ns1:getPasswordResult');
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
                $envBSEData['soapFatcaUrl'] = explode(',', env('SOAP_FATCA_ACTION'))[2];
            }
        }
        return $envBSEData;
    }
}
