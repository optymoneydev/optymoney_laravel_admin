<?php

namespace App\Http\Controllers\mf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\customer\UserAuthController;
use App\Http\Controllers\Nsdl\NsdlController;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
Use App\Models\Bfsi_users_detail;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_bank_details;
use SoapClient;
use File;
use Image;

class PipedController extends Controller
{
    public function mandate_reg($client_code, $bank) {
		// mandate_type - X / I /N (XSIP/ISIP/Net Banking)
		$transBSEArr = array(
			"ClientCode"	=> $client_code,
			"amount"		=> "100000",
			"mandate_type"	=> "X",
			"acc_no"		=> $bank['acc_no'],
			"acc_type"		=> $bank['ac_type'],
			"IFSC"			=> $bank['ifsc_code'],
			"MICR"			=> "",
			"start_date"	=> date("d/m/Y"),
			"end_date"		=> "31/12/2099",
		);
		$pipeValues = implode("|",$transBSEArr);		
		return $pipeValues;
	}

	public function ucc_creation($client_code, $userData, $user) {
		$GeneralController = new GeneralController();
		$pipeValues = [];
		$userParam = array(
			"Client_Code_(UCC)" => $client_code,
			"Primary_Holder_First_Name" => $userData->cust_name,"Primary_Holder_Middle_Name" => "","Primary_Holder_Last_Name" => "",
			"Tax_Status" => $userData->taxStatus,
			"Gender" => $userData->sex[0],
			"Primary_Holder_DOB/Incorporation" => $GeneralController->dateFormatConversion($userData->dob),
			"Occupation_Code" => $userData->occupationCode,
			"Holding_Nature" => $userData->clientHolding,
			"Second_Holder_First_Name" => "","Second_Holder_Middle_Name" => "","Second_Holder_Last_Name" => "",
			"Third_Holder_First_Name" => "","Third_Holder_Middle_Name" => "","Third_Holder_Last_Name" => "",
			"Second_Holder_DOB" => "",
			"Third_Holder_DOB" => "",
			"Guardian_First_Name" => "","Guardian_Middle_Name" => "","Guardian_Last_Name" => "","Guardian_DOB" => "",
			"Primary_Holder_PAN_Exempt" => "N","Second_Holder_PAN_Exempt" => "","Third_Holder_PAN_Exempt" => "","Guardian_PAN_Exempt" => "",
			"Primary_Holder_PAN" => $userData->pan_number,"Second_Holder_PAN" => "","Third_Holder_PAN" => "","Guardian_PAN" => "",
			"Primary_Holder-_Exempt_Category" => "","Second_Holder_Exempt_Category" => "","Third_Holder_Exempt_Category" => "","Guardian_Exempt_Category" => "",
			"Client_Type" => "P",
			"PMS" => "",
			"Default_DP" => "",
			"CDSL_DPID" => "",
			"CDSLCLTID" => "",
			"CMBP_Id" => "",
			"NSDLDPID" => "",
			"NSDLCLTID" => "");
		$pipeValues[] = implode("|",$userParam);

		$bankdata = Bfsi_bank_details::where('fr_user_id',$user->pk_user_id)->orderBy("created_at", "asc")->get();
		$i = 1; 
		$j = 1; 
		$data = [];
		$banks = [];
		for ($i = 1; $i <= 5; $i++) {
			if($i <= count($bankdata)) {
				foreach ($bankdata as $bank) {
					if($j <= 5) {
						if($bank->ac_type == "" || $bank->ac_type == null) {
							$data['Account_Type_'.$i] = 'SB';
						} else {
							if($bank->ac_type == "savings") {
								$data['Account_Type_'.$i] = 'SB';
							} else {
								if($bank->ac_type == "current") {
									$data['Account_Type_'.$i] = 'CB';
								}
							}
						}
						$data['Account_No_'.$i] = $bank->acc_no;
						$data['MICR_No_'.$i] = "";
						$data['IFSC_Code_'.$i] = $bank->ifsc_code;
						if($bank->default_bank == null) {
							$data['Default_Bank_Flag'] = 'N';
						} else {
							$data['Default_Bank_Flag'] = $bank->default_bank;
						}
						$banks[] = implode("|",$data);
						$data = [];
					}
					$j++;
				}
				$i = $j-1;
			} else {
				$data['Account_Type_'.$i] = "";
				$data['Account_No_'.$i] = "";
				$data['MICR_No_'.$i] = "";
				$data['IFSC_Code_'.$i] = "";
				$data['Default_Bank_Flag'] = "";
				$banks[] = implode("|",$data);
				$data = [];
			}
		}
		$banks_pipevalue = implode("|",$banks);
		$pipeValues[] = $banks_pipevalue;
		if($userData->communication_mode == "" || $userData->communication_mode == NULL) {
			$communication_mode = "E";
		} else {
			$communication_mode = $userData->communication_mode;
		}
		if($userData->isAadhaarUpdated == "Y") {
			$isAadhaarUpdated = "Y";
		} else {
			$isAadhaarUpdated = "N";
		}
		
		$userParam1 = array(
			"Cheque_name" => "",
			"Div_pay_mode" => "02",
			"Address_1" => str_replace(","," ",str_replace("-","",$userData->address1)),
			"Address_2" => str_replace(","," ",str_replace("-","",$userData->address2)),
			"Address_3" => str_replace(","," ",str_replace("-","",$userData->address3)),
			"City" => $userData->city,"State" => $userData->bsestatecode,
			"Pincode" => $userData->pincode,"Country" => "INDIA",
			"Resi._Phone" => "","Resi._Fax" => "","Office_Phone" => "","Office_Fax" => "","Email" => $userData->email,
			"Communication_Mode" => $communication_mode,
			"Foreign_Address_1" => "","Foreign_Address_2" => "","Foreign_Address_3" => "",
			"Foreign_Address_City" => "","Foreign_Address_Pincode" => "","Foreign_Address_State" => "",
			"Foreign_Address_Country" => "","Foreign_Address_Resi_Phone" => "","Foreign_Address_Fax" => "",
			"Foreign_Address_Off._Phone" => "",
			"Foreign_Address_Off._Fax" => "",
			"Indian_Mobile_No." => $userData->contact_no);
		$userParam1_pipevalue = implode("|",$userParam1);
		$pipeValues[] = $userParam1_pipevalue;
		$nomineeMinorFlag = $GeneralController->getMinorFlag("2012-10-10");

		$userParam2 = array(
			"Nominee_1_Name" => $userData->nominee_name,
			"Nominee_1_Relationship" => $userData->r_of_nominee_w_app,
			"Nominee_1_Applicable(%)" => "100.00",
			"Nominee_1_Minor_Flag" => $nomineeMinorFlag,
			"Nominee_1_DOB" => ($nomineeMinorFlag == "Y") ? $GeneralController->dateFormatConversion($userData->dob) : "",
			"Nominee_1_Guardian" => ($nomineeMinorFlag == "Y") ? $userData->nominee_guardian_name : "",
			"Nominee_2_Name" => "","Nominee_2_Relationship" => "","Nominee_2_Applicable(%)" => "","Nominee_2_DOB" => "","Nominee_2_Minor_Flag" => "","Nominee_2_Guardian" => "",
			"Nominee_3_Name" => "","Nominee_3_Relationship" => "","Nominee_3_Applicable(%)" => "","Nominee_3_DOB" => "","Nominee_3_Minor_Flag" => "","Nominee3_Guardian" => "",
			"Primary_Holder_KYC_Type" => "K","Primary_Holder__CKYC_Number" => "",
			"Second_Holder_KYC_Type" => "","Second_Holder_CKYC_Number" => "",
			"Third_Holder_KYC_Type" => "","Third_Holder_CKYC_Number" => "",
			"Guardian_KYC_Type" => "","Guardian_CKYC_Number" => "",
			"Primary_Holder_KRA_Exempt_Ref._No." => "",
			"Second_Holder_KRA_Exempt_Ref._No." => "",
			"Third_Holder_KRA_Exempt_Ref._No" => "",
			"Guardian_Exempt_Ref._No" => "",
			"Aadhaar_Updated" => $isAadhaarUpdated,
			"Mapin_Id." => "",
			"Paperless_flag" => "Z",
			"LEI_No" => "",
			"LEI_Validity" => "",
			"Filler_1__(_Mobile_Declaration_Flag_)" => "SE",
			"Filler_2_(Email_Declaration_Flag_)" => "SE",
			"Filler_3" => "");
		$userParam2_pipevalue = implode("|",$userParam2);
		$pipeValues[] = $userParam2_pipevalue;
		$pipeValues_data = implode("|",$pipeValues);
		return $pipeValues_data;
	}
    
	public function purchaseLumpsum($bseAuthData, $user, $request, $ref_no, $scheme_code, $ip, $bseGenPassword) {
		$pipeValues = "<bses:orderEntryParam>
			<bses:TransCode>NEW</bses:TransCode>
			<bses:TransNo>".$ref_no."</bses:TransNo>
			<bses:OrderId/>
			<bses:UserID>".$bseAuthData['bseUserId']."</bses:UserID>
			<bses:MemberId>".$bseAuthData['bseMemberId']."</bses:MemberId>
			<bses:ClientCode>".$user->bse_id."</bses:ClientCode>
			<bses:SchemeCd>".$scheme_code."</bses:SchemeCd>
			<bses:BuySell>P</bses:BuySell>
			<bses:BuySellType>FRESH</bses:BuySellType>
			<bses:DPTxn>P</bses:DPTxn>
			<bses:OrderVal>".$request->lumpsumPurchaseAmount."</bses:OrderVal>
			<bses:Qty/>
			<bses:AllRedeem>N</bses:AllRedeem>
			<bses:FolioNo/>
			<bses:Remarks/>
			<bses:KYCStatus>Y</bses:KYCStatus>
			<bses:RefNo/>
			<bses:SubBrCode/>
			<bses:EUIN/>
			<bses:EUINVal>N</bses:EUINVal>
			<bses:MinRedeem>N</bses:MinRedeem>
			<bses:DPC>Y</bses:DPC>
			<bses:IPAdd/>
			<bses:Password>".$bseGenPassword."</bses:Password>
			<bses:PassKey>".$bseAuthData['bsePassKey']."</bses:PassKey>
			<bses:Parma1/>
			<bses:Param2/>
			<bses:Param3/>
			<bses:MobileNo>".$user->contact_no."</bses:MobileNo>
			<bses:EmailID>".$user->login_id."</bses:EmailID>
			<bses:MandateID/>
			<bses:Filler1/>
			<bses:Filler2/>
			<bses:Filler3/>
			<bses:Filler4/>
			<bses:Filler5/>
			<bses:Filler6/>
		</bses:orderEntryParam>";
		return $pipeValues;
	}
}
