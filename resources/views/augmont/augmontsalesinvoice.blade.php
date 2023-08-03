<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sales Invoice</title>
        <style type="text/css">
            .center {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="invoice">
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
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Billing Address:</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">{{$billing['name']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Address:</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$billing['address']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$billing['pincode']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$billing['mobileNumber']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$billing['email']}}</td></tr>
                            <tr><td> </td></tr>
                            <tr><td> </td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Shipping Address:</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">{{$shipping['name']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Address:</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$shipping['address']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$shipping['pincode']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$shipping['mobileNumber']}}</td></tr>
                            <tr><td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">{{$shipping['email']}}</td></tr>
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
                            <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">SKU</td>
                            <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">Product</td>
                            <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">Gram</td>
                            <td style="padding: 5px;vertical-align: top; font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px; border: 1px solid #eee">Amount</td>
                        </tr>
                        @foreach($taxes['items'] as $key=>$value)
                            <tr>
                            <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$value['sku']}}</td>
                                <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$value['productName']}}</td>
                                <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$value['quantity']}}</td>
                                <td style="padding: 5px;vertical-align: top;border: 1px solid #eee;">{{$value['amount']}}</td>
                            </tr>
                        @endforeach
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
    </body>
</html>