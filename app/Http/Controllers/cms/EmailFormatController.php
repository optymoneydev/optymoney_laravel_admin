<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\EmailFormat;
use View;


class EmailFormatController extends Controller
{
    public function getEmailFormat(Request $request) {
        $emailFormatData = EmailFormat::get(['emailformat_id', 'emailformat_content', 'emailformat_name', 'emailformat_type', 'emailformat_status', 'emailformat_created_by', 'emailformat_template_choose', 'emailformat_created_date'])
              ->sortByDesc("emailformat_id")
              ->toJson();
        $data = [
            'emailFormatsType' => $this->getEmailFormatsType(),
            'emailsformats' => $emailFormatData
        ];
        return $data;
    }
    
    public function getEmailFormatsType() {
        $emailformat_typeData = EmailFormat::get(['emailformat_type'])
                ->groupBy('emailformat_type')
                ->sortBy("emailformat_type");
        return $emailformat_typeData;
    }

    public function saveEmailFormats(Request $request) {
        $id = $request->session()->get('id');

        $emailFormat = new EmailFormat();
        if($request['emailformat_id'] != "") {
            $emailFormat = EmailFormat::find($request['emailformat_id']);
            $emailFormat->emailformat_id = $request['emailformat_id'];
            $emailFormat->emailformat_modified_by = $id;
            $emailFormat->emailformat_modified_ip = $request->ip();
        } else {
            $emailFormat->emailformat_created_by = $id;
            $emailFormat->emailformat_created_ip = $request->ip();
        }

        $emailFormat->emailformat_name = $request['emailformat_name'];
        $emailFormat->emailformat_type = $request['emailformat_type'];
        $emailFormat->emailformat_status = $request['emailformat_status'];
        $emailFormat->emailformat_template_choose = $request['emailformat_template_choose'];
        $emailFormat->emailformat_content = $request['emailformat_content'];
        
        $saveemailFormat = $emailFormat->save();
        if($saveemailFormat==1) {
            if($request['emailformat_id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'Email Format updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'Email Format added successfully.'
                ];
            }
        } else {
            if($request['emailformat_id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'Email Format updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'Email Format adding failed.'
                ];
            }
        }
        return $data;
    }

    public function emailFormatById(Request $request) {
        $emailFormatData = EmailFormat::where('emailformat_id', '=', $request->id)->get()->first();
        return $emailFormatData;
    }

    public function deleteEmailFormatById(Request $request) {
        $emailFormatData = EmailFormat::where('emailformat_id', '=', $request->id)->delete();
        if($emailFormatData==1) {
            $data = [
                'status_code' => 201,
                'message' => 'Email Format Deleted Successfully.'
            ];
        } else {
            $data = [
                'status_code' => 400,
                'message' => 'Email Format Deletion Failed.'
            ];
        }
        return $emailFormatData;
    }


}
