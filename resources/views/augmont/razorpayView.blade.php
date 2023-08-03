@extends('layouts.simple.master')
@section('title', 'Buy Silver')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Buy Silver</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Buy</li>
<li class="breadcrumb-item active">Silver</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2"><img class="media-object img-60" src="{{asset('assets/images/logo/login.png')}}" alt=""></td>
                        </tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr>
                            <td width="49%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Sold by:</td></tr>
                                    <!-- <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Receipt Number: 12495354273</td></tr> -->
                                    <tr><td> </td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Augmont Goldtech Private Limited </td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:8px;">(Formerly known as Augmont Precious Metals Private Limited)</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Address:</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">504, 5th Floor, Trade Link, E Wing, <br>Kamala Mills Compound, Lower Parel,<br> Mumbai, Maharashtra 400013</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">GSTIN </td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Augmont Goldtech Private Limited<br>27AATCA3030A1Z3</td></tr>
                                    <tr><td> </td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Customer Address:</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">{{$userInfo['name']}}</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Address:</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$userInfo['address']}}</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$userInfo['mobileNumber']}}</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$userInfo['email']}}</td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Augmont Unique Id: </td></tr>
                                    <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$userInfo['uniqueId']}}</td></tr>
                                    <tr><td> </td></tr>
                                    </table></td>
                                </tr>
                                </table>
                            </td>
                            <td width="51%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;"  align="right">Invoice # {{$invoiceNumber}}</td></tr>
                                <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"  align="right">Issued: {{$invoiceDate}}</td></tr>
                            </table></td>
                        </tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr>
                            <td colspan="2"><table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 1px solid #eee">
                                <tr>
                                    <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">Item Description</td>
                                    <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">HSN Code</td>
                                    <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">Gram</td>
                                    <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">Rate/gm (INR)</td>
                                    <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">Amount (INR)</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$metalType}} {{$karat}} {{$purity}}</td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$hsnCode}}</td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$quantity}}</td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$rate}}</td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$grossAmount}}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"><label>Net Total</label></td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"></td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$quantity}}</td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"></td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$grossAmount}}</td>
                                </tr>
                                @foreach($taxes['taxSplit'] as $key=>$value)
                                    <tr>
                                        <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"><label>{{$value['type']}}</label></td>
                                        <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"></td>
                                        <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"></td>
                                        <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$value['taxPerc']}}%</td>
                                        <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$value['taxAmount']}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"><label>Total</label></td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"></td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"></td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;"></td>
                                    <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$netAmount}}</td>
                                </tr>
                            </table></td>
                        </tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px;" colspan="2"><h5>Terms & Conditions :-</h5></td></tr>
                        <tr>
                            <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" colspan="2">
                                <p>1. Once goods sold cannot be returned<br>
                                2. Any disputes can be subject to Mumbai jurisdiction<br>
                                3. Additional payment gateway surcharge might be levied by the partner</p>
                            </td>
                        </tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px;" colspan="2"><h5>Authorised Signatory :-</h5></td></tr>
                        <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" colspan="2">GSTIN : 27AATCA3030A1Z3</td></tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" colspan="2" align="center">(This is a computer generated invoice, if you have any questionsconcerning this invoice)  <br />contact: support@optymoney.com</td></tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr><td colspan="2"> </td></tr>
                        <tr><td colspan="2"> </td></tr>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>
@endsection

@section('script')
<!-- <script src="{{asset('assets/js/form-wizard/form-wizard.js')}}"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{asset('assets/js/augmont/buyorder.js')}}"></script> -->
@endsection