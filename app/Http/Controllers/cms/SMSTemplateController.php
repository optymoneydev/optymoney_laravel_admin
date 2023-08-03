<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\SMS_Template;
use View;


class SMSTemplateController extends Controller
{
    public function getSMSTemplates(Request $request) {
        $smsData = SMS_Template::get()
              ->sortByDesc("sms_id")
              ->toJson();
        $data = [
            'smsType' => $this->getSmsType(),
            'sms' => $smsData
        ];
        return $data;
    }
    
    public function getSmsType() {
        $sms_typeData = SMS_Template::get(['sms_type'])
                ->groupBy('sms_type')
                ->sortBy("sms_type");
        return $sms_typeData;
    }

    public function saveSMSTemplate(Request $request) {
        $id = $request->session()->get('id');

        $sms = new SMS_Template();
        if($request['id'] != "") {
            $sms = SMS::find($request['id']);
            $sms->id = $request['id'];
        } else {

        }

        $sms->sms_name = $request['sms_name'];
        $sms->sms_type = $request['sms_type'];
        $sms->sms_status = $request['sms_status'];
        $sms->sms_template_id = $request['sms_template_id'];
        $sms->sms_content = $request['sms_content'];
        
        $savesms = $sms->save();
        if($savesms==1) {
            if($request['sms_id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'SMS Template updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'SMS Template added successfully.'
                ];
            }
        } else {
            if($request['sms_id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'SMS Template updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'SMS Template adding failed.'
                ];
            }
        }
        return $data;
    }

    public function smsTemplateById(Request $request) {
        $smsData = SMS_Template::where('sms_id', '=', $request->id)->get()->first();
        return $smsData;
    }

    public function deletesmsById(Request $request) {
        $smsData = SMS_Template::where('sms_id', '=', $request->id)->delete();
        if($smsData==1) {
            $data = [
                'status_code' => 201,
                'message' => 'SMS Deleted Successfully.'
            ];
        } else {
            $data = [
                'status_code' => 400,
                'message' => 'SMS Deletion Failed.'
            ];
        }
        return $smsData;
    }

}
