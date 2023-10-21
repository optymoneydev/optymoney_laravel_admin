<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\HTMLPDFController;
use App\Http\Controllers\Augmont\OrdersAugmontController;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_users_detail;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Mail;
Use \App\Models\EmailFormat;
Use App\Mail\OptyEmail;

class InvoiceAugmontController extends Controller
{
    public function buyInvoice(Request $request) {
        try {
            $client = new Client(['verify' => false ]);
            $tokentype = "Bearer ";
            $authToken = $tokentype.(new AugmontController)->merchantAuth();
            $headers = [
                'Content-Type'=> 'application/pdf',
                'AccessToken' => 'key',
                'Authorization' => $authToken,
            ];
            $res = $client->request('GET', 
                env('AUG_URL').'merchant/v1/invoice/'.$request->invoice,[
                'headers' => $headers
            ]);
            $statusCode = $res->getStatusCode(); 
            $content = json_decode($res->getBody()->getContents());
            $pdf = (new HTMLPDFController)->htmlPdf($this->object_to_array($content->result->data));

            return $pdf->download($request->invoice.'_pdf.pdf');
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function dbInvoice(Request $request) {
        try {
            $data = (new OrdersAugmontController)->OrdersByInvoice($request->invoice);
            $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                ->where('bfsi_user.pk_user_id', $data->user_id)
                ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();
            $content =  [
                "invoiceNumber" => $data->invoiceNumber,
                "userInfo" => [
                "name" => $data->userName,
                "address" => $data->userAddress,
                "city" => $userData->city,
                "state" => $userData->state,
                "pincode" => $data->userPincode,
                "email" => $data->emailId,
                "mobileNumber" => $userData->contact_no,
                "uniqueId" => $userData->augid,
                ],
                "transactionId" => $data->transactionId,
                "quantity" => $data->quantity,
                "metalType" => $data->metalType,
                "hsnCode" => "710692",
                "rate" => $data->lockPrice,
                "unitType" => "Gram",
                "grossAmount" => $data->preTaxAmount,
                "netAmount" => $data->totalAmount,
                "taxes" => [
                "totalTaxAmount" => $data->totalTaxAmount,
                "taxSplit" => [
                    0 => [
                    "type" => "CGST",
                    "taxPerc" => $data->taxSplit_cgst_taxPerc,
                    "taxAmount" => $data->taxSplit_cgst_taxAmount,
                    ],
                    1 => [
                    "type" => "SGST",
                    "taxPerc" => $data->taxSplit_sgst_taxPerc,
                    "taxAmount" => $data->taxSplit_sgst_taxAmount,
                    ],
                    2 => [
                    "type" => "IGST",
                    "taxPerc" => $data->taxSplit_igst_taxPerc,
                    "taxAmount" => $data->taxSplit_igst_taxAmount,
                    ]
                ]
                    ],
                "tcsInfo" => [
                "tcsPerc" => "0",
                "tcsAmount" => "0",
                ],
                "discount" => [
                0 => [
                    "type" => "percentage",
                    "title" => "tcs",
                    "value" => "0",
                    "amount" => "0",
                    "description" => "TCS discount",
                ]
                ],
                "invoiceDate" => $data->created_at,
                "purity" => "999",
                "karat" => "24K",
            ];
            $pdf = (new HTMLPDFController)->htmlPdf($content);

            return $pdf->download($request->invoice.'_pdf.pdf');
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function generatePDF($content) {
        return (new HTMLPDFController)->htmlPdf($this->object_to_array($content->result->data));
    }

    public function getInvoiceData($invoice) {
        try {
            $client = new Client(['verify' => false ]);
            $tokentype = "Bearer ";
            $authToken = $tokentype.(new AugmontController)->merchantAuth();

            if($authToken==401) {
                return 401;
            } else {
                return (new AugmontController)->clientRequests('GET', 'merchant/v1/invoice/'.$invoice, '');
            }
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function emailInvoice(Request $request) {
        try {
            $content = $this->getInvoiceData($request->invoice);
            $dataTemp = $content->result->data;
            $pdf = $this->generatePDF($content);

            $mailInfo = new \stdClass();
            $mailInfo->recieverName = $dataTemp->userInfo->name;
            $mailInfo->sender = "Optymoney";
            $mailInfo->senderCompany = "Optymoney";
            $mailInfo->to = $dataTemp->userInfo->email;
            $mailInfo->subject = "Optymoney - Invoice : ".$dataTemp->invoiceNumber;
            $mailInfo->name = "Optymoney";
            $mailInfo->from = "no-reply@optymoney.com";
            $mailInfo->template = "email-templates.purchase-success";
            $mailInfo->attachment = "yes";
            $mailInfo->files = $pdf->download($request->invoice.'_pdf.pdf');
            $mailInfo->metalType = $dataTemp->metalType;
            $mailInfo->amount = $dataTemp->netAmount;

            Mail::to($dataTemp->userInfo->email)->send(new OptyEmail($mailInfo));

            return $pdf->download($request->invoice.'_pdf.pdf');
        } catch (\Exception $e) {
            \Log::channel('itsolution')->error($e);
            return $e;
        }
    }

    public function sellInvoice(Request $request) {
        try {
            $client = new Client(['verify' => false ]);
            $tokentype = "Bearer ";
            $authToken = $tokentype.(new AugmontController)->merchantAuth();
            $headers = [
                'Content-Type'=> 'application/pdf',
                'AccessToken' => 'key',
                'Authorization' => $authToken,
            ];
            $res = $client->request('GET', 
            env('AUG_URL').'merchant/v1/invoice/order/'.$request->invoice,[
                'headers' => $headers
            ]);
            $statusCode = $res->getStatusCode(); 
            $content = json_decode($res->getBody()->getContents());
            $pdf = (new HTMLPDFController)->redeemInvoice($this->object_to_array($content->result->data));
            return $pdf->download($request->invoice.'_pdf.pdf');
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function buyInvoiceContent($invoice) {
        try {
            $content = $this->getInvoiceData($invoice);
            return $this->object_to_array($content->result->data);
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    function object_to_array($data) {
        if(is_array($data) || is_object($data)){
            $result = array();
            foreach($data as $key => $value){
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
    }
}
