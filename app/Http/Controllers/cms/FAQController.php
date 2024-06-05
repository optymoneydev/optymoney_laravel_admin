<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Faq;
use View;


class FAQController extends Controller
{
    public function getFaq(Request $request) {
        $clientData = Faq::get()
        ->sortByDesc("faq_id")
        ->toJson();
        return $clientData;
    }

    public function getFaqByCategory(Request $request) {
        $blogsData = Faq::where('faq_category', '=', $request->category)
            ->orderBy('faq_id', 'DESC')
            ->get()
            ->toJson();
        $data = [
            'faq' => $blogsData
        ];
        return $data;
    }

    public function saveFaq(Request $request) {
        $id = $request->session()->get('id');

        $faq = new Faq();
        if($request['faq_id'] != "") {
            $faq = Faq::find($request['faq_id']);
            $faq->faq_id = $request['faq_id'];
            $faq->faq_modified_by = $id;
            $faq->faq_modified_ip = $request->ip();
        } else {
            $faq->faq_created_by = $id;
            $faq->faq_created_ip = $request->ip();
        }
        $faq->faq_category = $request['faq_category'];
        $faq->faq_question = $request['faq_question'];
        $faq->faq_answer = $request['faq_answer'];
        $faq->faq_keywords = $request['faq_keywords'];
        $saveFaq = $faq->save();
        if($saveFaq==1) {
            if($request['faq_id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'FAQ updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'FAQ added successfully.'
                ];
            }
        } else {
            if($request['faq_id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'FAQ updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'FAQ adding failed.'
                ];
            }
        }
        return $data;
    }

    public function faqById(Request $request) {
        $faqData = Faq::where('faq_id', '=', $request->faq_id)->get()->first();
        return $faqData;
    }

    public function deletefaqById(Request $request) {
        $faqData = Faq::where('faq_id', '=', $request->faq_id)->delete();
        return $faqData;
    }
    
}
