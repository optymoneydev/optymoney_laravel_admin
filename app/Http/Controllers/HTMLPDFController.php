<?php

namespace App\Http\Controllers;
use PDF;
use Illuminate\Http\Request;

class HTMLPDFController extends Controller
{
    /**
     * generate PDF file from blade view.
     *
     * @return \Illuminate\Http\Response
     */
    public function htmlPdf($content) {
        $pdf = PDF::setOptions(['isRemoteEnabled' => true])->loadView('augmont.augmontinvoice', $content);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        // dd($pdf);
        return $pdf;
    }

    public function redeemInvoice($content) {
        $pdf = PDF::loadView('augmont.augmontsalesinvoice', $content);
        return $pdf;
    }
}
