<?php

namespace App\Http\Controllers\cron;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\WithdrawAugmontController;
use App\Http\Controllers\mf\MFController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use ZipArchive;
use XBase\TableReader;
// use Webklex\IMAP\Facades\Client;
// use Webklex\IMAP\Client;
use Mail;

use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

Use App\Models\NavPrice;
Use App\Models\SchemeFilters;
Use App\Models\NavOffers;
Use App\Models\Mf_cams;
Use App\Models\Mf_karvy;
Use App\Models\Mfscheme;
Use App\Models\ConsolidatedData;
Use App\Models\FetchEmail;
Use App\Models\AugmontOrders;
Use App\Mail\OptyEmail;
use Illuminate\Support\Arr;

use DateTime;

class CronController extends Controller
{
    private $apiKey;
    private $pin;
    private $version;

    public function __construct() {
        //FOR KARVY & CAM
        $this->purchase = array('P','SI','TI','DR');
        //FOR KARVY & CAM
        $this->sell = array('R','SO','TO', 'DP');
        $this->doubleChar = array('SI', 'SO', 'TI', 'TO', 'DR', 'DP');
        $this->singleChar = array('P', 'R');
        $this->singleCharKarvy = array('P', 'R', 'D');

        set_time_limit(10000000);
    }

    public function navBSEPrice() {

        $url = "https://www.bsestarmf.in/RPTNAVMASTER.aspx";	//jsessionid=".trim($_COOKIE['JSESSIONID']);
        echo "URL:-".$url;
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        print_r($result);
        
        preg_match_all('/<input type="hidden" name="([^"]*)" id="([^"]*)" value="([^"]*)"/', $result, $matches);
        //echo "<pre>";
        //print_r($matches);
        $param = $matches[2][0]."=".urlencode($matches[3][0])."&".$matches[2][1]."=".urlencode($matches[3][1])."&".
                $matches[2][2]."=".urlencode($matches[3][2])."&".$matches[2][3]."=".urlencode($matches[3][3]);
        
        //$useragent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "__VIEWSTATE=8W8ftWTuqDSHVu4vr4l5dqIA6QtFU3gU5POJ8qjASqutwKjt%2FSfrs89sWW7pKI33Qx1Qhn6zWDavJf%2Flim1dUOW%2BCkqQSDTlxgRMbd5mcg8mdu1zbpmw48hnBBoxk01HZUcKCw%3D%3D&__VIEWSTATEGENERATOR=8EE3ED57&__VIEWSTATEENCRYPTED=&__EVENTVALIDATION=%2BaAQyJDFUdIbvGKQVluXs3bib4BsgkBXII12LqjUFiEn9nQsBRDbcGTTdDQCW7ckFdiM4k5Mgo7SC%2BOqfSU%2B4RKO%2FzYPdkMLGlGnTuSmB8QiaXo4Mt6nRqoQcJI6J1l%2FtlgqXT3qvRyn8z%2B0D96idERnlrTPGDN1iDstvqJuj2f81eMn&txtToDate=02-Aug-2022&btnText=Export+to+Text");
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie:  ASP.NET_SessionId=pirg1qnldmbiloeeaufotagx"));
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        $server_output = curl_exec ($ch);	
        $a = curl_getinfo($ch);
        $error = curl_error($ch); 


        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://bsestarmf.in/RPTNAVMASTER.aspx",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "__VIEWSTATE=8W8ftWTuqDSHVu4vr4l5dqIA6QtFU3gU5POJ8qjASqutwKjt%2FSfrs89sWW7pKI33Qx1Qhn6zWDavJf%2Flim1dUOW%2BCkqQSDTlxgRMbd5mcg8mdu1zbpmw48hnBBoxk01HZUcKCw%3D%3D&__VIEWSTATEGENERATOR=8EE3ED57&__VIEWSTATEENCRYPTED=&__EVENTVALIDATION=%2BaAQyJDFUdIbvGKQVluXs3bib4BsgkBXII12LqjUFiEn9nQsBRDbcGTTdDQCW7ckFdiM4k5Mgo7SC%2BOqfSU%2B4RKO%2FzYPdkMLGlGnTuSmB8QiaXo4Mt6nRqoQcJI6J1l%2FtlgqXT3qvRyn8z%2B0D96idERnlrTPGDN1iDstvqJuj2f81eMn&txtToDate=02-Aug-2022&btnText=Export+to+Text",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded",
            "postman-token: 705f867f-1c65-cfc8-73a2-6be3fdbc2b83"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        // echo "Date : ".date("Y-m-d");
        // exit;
        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {

        echo $response;
        $res = explode(PHP_EOL, $response);
        foreach($res as $data){
            $dataArr = explode('|', $data);
            //print_r($dataArr);
            $sqlNavPrice	= "SELECT * FROM mf_master WHERE isin = '".$dataArr[5]."'";
                $navPriceResult = $db->db_run_query($sqlNavPrice);
            //echo "MF_Master : SELECT * FROM mf_master WHERE isin = '".$dataArr[5]."'<br>";
                while($val = $db->db_fetch_array($navPriceResult)) {
            //print_r($val);
            $sqlNavPrice1	= "SELECT * FROM mf_nav_price WHERE price_date= '".$dataArr[0]."'and dividend_reinvest='".$dataArr[4]."'and isin = '".$dataArr[5]."'";
            //echo "sql : ".$sqlNavPrice1. "<br>";
                $navPriceResult = $db->db_run_query($sqlNavPrice1);
            if($db->db_fetch_array($navPriceResult)) {
                echo "exist";
            } else {
                $res = $db->db_run_query("INSERT INTO mf_nav_price SET fr_nav_id = '".$val['pk_nav_id']."',price_date= '".$dataArr[0]."',net_asset_value='".$dataArr[6]."',sale_price='0',
                                                dividend_reinvest='".$dataArr[4]."',fr_unique_no='".$val['unique_no']."',repurchase_price=0,fr_scheme_code='".$val['scheme_code']."',
                                                fr_scheme_name='".addslashes($val['scheme_name'])."',ISIN = '".$dataArr[5]."'");
                echo "Result : ".$res;
                echo "<br><br>INSERT INTO mf_nav_price SET fr_nav_id = '".$val['pk_nav_id']."',price_date= '".$dataArr[0]."',net_asset_value='".$dataArr[6]."',sale_price='0',
                dividend_reinvest='".$dataArr[4]."',fr_unique_no='".$val['unique_no']."',repurchase_price=0,fr_scheme_code='".$val['scheme_code']."',
                fr_scheme_name='".addslashes($val['scheme_name'])."',ISIN = '".$dataArr[5]."'<br><br>";
            }
            }
        }
        }
    }

    public function amfiData() {
        $yesterdayDate = Carbon::yesterday()->format('Y-m-d');
        $navStatus = NavPrice::where('price_date', $yesterdayDate)->first();
        echo date("dmYHmi");
        if($navStatus) {
            echo "yes";
            return $this->cronEmails(" - NAV's Data updated in Optymoney Portal", " - NAV's Data updated in Optymoney Portal. Please verify the data in DB for confirmation.");
        } else {
            $url = "https://www.amfiindia.com/spages/NAVAll.txt?t=".date("dmYHmi")."";	//jsessionid=".trim($_COOKIE['JSESSIONID']);
            $ch = curl_init($url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $dataList = explode(PHP_EOL, $result);
            foreach ($dataList as $value) {
                $contains = str_contains($value, ';');
                if($contains) {
                    $column = explode(';', $value);
                    if($column[0]!="Scheme Code") {
                        $date = Carbon::createFromFormat('d-M-Y', trim($column[5]))->format('Y-m-d');
                        
                        $oneYear = Carbon::createFromFormat('d-M-Y', trim($column[5]))->subDays(365)->format('Y-m-d');
                        $threeYear = Carbon::createFromFormat('d-M-Y', trim($column[5]))->subDays(1095)->format('Y-m-d');
                        $fiveYear = Carbon::createFromFormat('d-M-Y', trim($column[5]))->subDays(1825)->format('Y-m-d');
                        $net_asset_value = 0;
                        if($column[4]=='N.A.') {
                            $net_asset_value = 0;
                            $mfschemeRecord = Mfscheme::where('isin', $column[1])->update([
                                'price_date' => $date, 
                                'net_asset_value' => $net_asset_value,
                                'repurchase_price' => 0,
                                'sale_price' => 0,
                            ]);
                        } else {
                            $net_asset_value = $column[4];
                            $mfschemeRecord = Mfscheme::where('isin', $column[1])->update([
                                'price_date' => $date, 
                                'net_asset_value' => $net_asset_value,
                                'repurchase_price' => 0,
                                'sale_price' => 0,
                                'one_year_return' => $this->yearlyReturn($column[1], $oneYear, $date, $net_asset_value, 1),
                                'three_year_return' => $this->yearlyReturn($column[1], $threeYear, $date, $net_asset_value, 3),
                                'five_year_return' => $this->yearlyReturn($column[1], $fiveYear, $date, $net_asset_value, 5)
                            ]);
                        }
                        
                        $nav = new NavPrice();
                        $nav->price_date = $date;
                        $nav->net_asset_value = $net_asset_value;
                        $nav->ISIN = $column[1];
                        $nav->repurchase_price = 0;
                        $nav->sale_price = 0;
                        $nav->save();
                    }
                }
            }
            return response()->json($this->cronEmails(" - NAV's Data updated in Optymoney Portal", " - NAV's Data updated in Optymoney Portal. Please verify the data in DB for confirmation."));
        }
    }

    public function updateExistingPrices() {
        $schData = Mfscheme::whereNull('net_asset_value')->get(['isin']);
        
        foreach ($schData as $sch) {
            echo $sch->isin."<br>";
            $priceData = NavPrice::where('isin', $sch->isin)->orderBy('price_date','desc')->first(['net_asset_value', 'price_date', 'repurchase_price', 'sale_price']);
            if($priceData) {
                $date = $priceData->price_date;
                
                $oneYear = (new Carbon($priceData->price_date))->subDays(365)->format('Y-m-d');
                $threeYear = (new Carbon($priceData->price_date))->subDays(1095)->format('Y-m-d');
                $fiveYear = (new Carbon($priceData->price_date))->subDays(1825)->format('Y-m-d');

                $oneYearReturn = $this->yearlyReturn($sch->isin, $oneYear, $date, $priceData->net_asset_value, 1);
                $threeYearReturn = $this->yearlyReturn($sch->isin, $threeYear, $date, $priceData->net_asset_value, 3);
                $fiveYearReturn = $this->yearlyReturn($sch->isin, $fiveYear, $date, $priceData->net_asset_value, 5);
                
                $mfschemeRecord = Mfscheme::where('isin', $sch->isin)->update([
                    'price_date' => $priceData->price_date, 
                    'net_asset_value' => $priceData->net_asset_value,
                    'repurchase_price' => $priceData->repurchase_price,
                    'sale_price' => $priceData->sale_price,
                    'one_year_return' => $oneYearReturn,
                    'three_year_return' => $threeYearReturn,
                    'five_year_return' => $fiveYearReturn
                ]);
                echo $mfschemeRecord."<br><br>";
            }
        }
    }

    public function yearlyReturn($isin, $startYear, $endYear, $endYearNAV, $time) {
        $navPrevious = NavPrice::where('isin', $isin)
        ->whereBetween('price_date', [$startYear, $endYear])
        ->orderBy("price_date", "asc")
        ->first(['net_asset_value']);
        if($navPrevious) {
            if(is_numeric($endYearNAV)) {
                if($navPrevious->net_asset_value!=0) {
                    $nav = round((pow(($endYearNAV/$navPrevious->net_asset_value), 1/$time)-1)*100, 2);
                    return $nav;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getNavUpdates(Request $request) {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = 'desc'; // $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = NavPrice::select('count(*) as allcount')->groupBy('price_date')->count();
        $totalRecordswithFilter = NavPrice::select('count(*) as allcount')->where('price_date', 'like', '%' . $searchValue . '%')->groupBy('price_date')->count();

        // Get records, also we have included search filter as well
        $records = NavPrice::orderBy($columnName, $columnSortOrder)
            ->where([
                ['mf_nav_price.price_date', 'like', '%' . $searchValue . '%']
            ])
            ->groupBy('price_date')
            // ->orWhere('mf_nav_price.scheme_type', 'like', '%' . $searchValue . '%')
            // ->orWhere('mf_nav_price.branch', 'like', '%' . $searchValue . '%')
            ->select('mf_nav_price.price_date')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "price_date" => $record->price_date,
                "action" => '<div class="btn-group"><button type="button" class="btn btn-primary navView" data-id="'.$record->price_date.'"><i class="fa fa-eye"></i></button></div>',
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
        // $navData = NavPrice::groupBy('price_date')
        //         ->get(['price_date'])
        //         ->sortByDesc("price_date")
        //         ->toJson();
        // return $navData;
    }

    public function getNavSchemes(Request $request) {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = 'desc'; // $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $date = date('d.m.Y',strtotime("-1 days"));
        // Total records
        $totalRecords = NavPrice::select('count(*) as allcount')->where('traddate', '=', $date)->count();
        $totalRecordswithFilter = NavPrice::select('count(*) as allcount')->where('traddate', '=', $date)->count();

        // Get records, also we have included search filter as well
        $records = NavPrice::orderBy($columnName, $columnSortOrder)
            ->where('mf_cam.traddate', '=', $date)
            // ->orWhere('mf_nav_price.scheme_type', 'like', '%' . $searchValue . '%')
            // ->orWhere('mf_nav_price.branch', 'like', '%' . $searchValue . '%')
            ->select('mf_cam.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "fr_scheme_name" => $record->fr_scheme_name,
                "ISIN" => $record->ISIN,
                "net_asset_value" => $record->net_asset_value,
                "fr_unique_no" => $record->fr_unique_no
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
        // $navData = NavPrice::groupBy('price_date')
        //         ->get(['price_date'])
        //         ->sortByDesc("price_date")
        //         ->toJson();
        // return $navData;
    }

    public function getCamsTransactions(Request $request) {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = 5; //$columnIndex_arr[0]['column']; // Column index
        $columnName = "traddate"; // $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Mf_cams::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Mf_cams::select('count(*) as allcount')->where('folio_no', 'like', '%' . $searchValue . '%')->count();

        // Get records, also we have included search filter as well
        $records = Mf_cams::orderBy($columnName, $columnSortOrder)
            ->where('mf_cam.folio_no', 'like', '%' . $searchValue . '%')
            ->orWhere('mf_cam.inv_name', 'like', '%' . $searchValue . '%')
            ->orWhere('mf_cam.scheme', 'like', '%' . $searchValue . '%')
            ->select('mf_cam.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "id" => $record->pk_cam_id,
                "folio_no" => $record->folio_no,
                "scheme" => $record->scheme,
                "inv_name" => $record->inv_name,
                "pan" => $record->pan,
                "amount" => $record->amount,
                "traddate" => $record->traddate,
                "trxn_nature" => $record->trxn_nature,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
        
    }

    public function getKarvyTransactions(Request $request) {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = 5; //$columnIndex_arr[0]['column']; // Column index
        $columnName = "traddate"; // $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Mf_karvy::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Mf_karvy::select('count(*) as allcount')->where('folio_no', 'like', '%' . $searchValue . '%')->count();

        // Get records, also we have included search filter as well
        $records = Mf_karvy::orderBy($columnName, $columnSortOrder)
            ->where('mf_karvy.folio_no', 'like', '%' . $searchValue . '%')
            ->orWhere('mf_karvy.inv_name', 'like', '%' . $searchValue . '%')
            ->orWhere('mf_karvy.scheme', 'like', '%' . $searchValue . '%')
            ->select('mf_karvy.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "id" => $record->pk_cam_id,
                "folio_no" => $record->folio_no,
                "scheme" => $record->scheme,
                "inv_name" => $record->inv_name,
                "pan" => $record->pan,
                "amount" => $record->amount,
                "traddate" => $record->traddate,
                "trxn_nature" => $record->trxn_nature,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
        
    }

    /* fetch Emails from Karvy */
    public function karvyEmails() {
        try {
            $now = Carbon::now();
            $date =  Carbon::yesterday()->format('d-m-Y');
            $cm = new ClientManager(base_path().'/config/imap.php');

            // $cm = new ClientManager($options["ssl"] = [ 
            //     'allow_self_signed' => true,
            //     "verify_peer"=>false,
            //     "verify_peer_name"=>false
            // ] );
            $client = $cm->account('karvy');

            //Connect to the IMAP Server
            $client->connect();
            ini_set('memory_limit','-1');
            $status = $client->isConnected();
            $folder = $client->getFolderByPath('INBOX');
            $info = $folder->examine();
            $query = $folder->messages();
            $query->setFetchOrder("desc");
            $query->setFetchOrderDesc();
            $query->fetchOrderDesc();
            $emailList = array();
            $count = $query->from('distributorcare@kfintech.com')->where([["SUBJECT" => "Subscribed Transaction Feeds Report for Ref.no."]])->since($date)->unseen()->get()->count();
            // return response()->json($count);
            if($count > 0) {
                $data = $query->from('distributorcare@kfintech.com')->where([["SUBJECT" => "Subscribed Transaction Feeds Report for Ref.no."]])->since($date)->unseen()->setFetchOrder("asc")->get();
                foreach($data as $item) {
                    $myObj = array();
                    $myObj['subject'] = "".$item->getSubject().'';
                    // return $item->getSubject();
                    
                    // $emailFetchStatus = FetchEmail::where([
                    //     'email_date' => Carbon::yesterday()->format('Y-m-d')
                    // ])->first();
                    // if($emailFetchStatus) {
                    //     $myObj['status_exist'] = $emailFetchStatus;
                    //     return response()->json($myObj);
                    // } else {
                        $body = $item->getHTMLBody(true);
                        $b = e($body);
                        $dom = new \DomDocument();
                        $dom->load($b); 
                        $xpath = new \DomXPath($dom);
                        $nodes = $xpath->query('//a');
                        // echo "node length :".$nodes->length;
                        if ($nodes->length) {
                            echo $nodes[1]->getAttribute('href');
                        }
                        $result=preg_split('/href=/',e($body));
                        $links = explode(" ", $result[2]);
                        $myObj['link'] = substr($links[0],6,-6);

                        $options=array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        );  

                        $contents = file_get_contents(substr($links[0],6,-6), false, stream_context_create($options));
                        if(Storage::disk('public_uploads_karvy')->put($date.'_karvy.zip', $contents)) {
                            $fileName = $this->extractUploadedKarvyZip($date.'_karvy.zip', $date);
                            Storage::disk('public_uploads_karvy')->delete($date.'_karvy.zip');
                            $dbfStatus = $this->dbfKarvyDataExtract(public_path("uploads/rta_data/karvy/").$fileName, $now);
                            foreach($dbfStatus as $pan){
                                $consolidateStatus = $this->consolidateKarvy($pan);
                                echo "<br>".$consolidateStatus;
                            }
                            // dd($dbfStatus);
                            $myObj['filename'] = $fileName;
                            $myObj['dbfData'] = json_encode($dbfStatus);
                            // $myObj['consolidate'] = json_encode($consolidateStatus);
                            $fetchEmail = new FetchEmail();
                            $fetchEmail->email_date = Carbon::yesterday()->format('Y-m-d');
                            $fetchEmail->email_subject = $item->getSubject();
                            $fetchEmail->email_amc = "KARVY";
                            $emailFetchStatus = $fetchEmail->save();
                            $myObj['status'] = $emailFetchStatus;
                            
                            $emailList['fileupload'] = $myObj;
                        } else {
                            $myObj['filename'] = "";
                        }
                    // }
                    $item->setFlag('Seen');
                }
                $emailStat = $this->cronEmails(" - KARVY's transaction data updated in Optymoney Portal", " - KARVY's transaction data updated in Optymoney Portal. Please verify the data in DB for confirmation.");
                $emailList['mail_status'] = $emailStat;
            } else {
                $emailStat = $this->cronEmails(" - KARVY's transaction no transactions", " - There are no KARVY's transaction data.");
                $emailList['mail_count'] = $count;
                $emailList['mail_status'] = $emailStat;
            }
            return response()->json($emailList);
        } catch (\Exception $e) {
            $emailStat = $this->cronEmails(" - KARVY's transaction exception", " - ".$e->getMessage());
            return "Message : ".$e->getMessage();
        }
    }

    /* extract the zip file from karvy */
    public function extractUploadedKarvyZip($fileName, $date){
        try {
            $zip = new ZipArchive();
            $filePath = public_path('uploads/rta_data/karvy/').$fileName;
            echo "<br>File Path : ".$filePath;
            
            $storageDestinationPath = public_path("uploads/rta_data/karvy/");
            echo "<br>Storage Path : ".$storageDestinationPath;
            if ($zip->open($filePath) == TRUE) {
                echo "<br>Password Status : ".$zip->setPassword('dEVMANTRA@123');
                // echo "<br>Password Status : ".$zip->setPassword('Optybs@24');
                echo "<br>length : ".$zip->numFiles;
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    echo "<br>Files Name : ".$filename;
                }
                $extractStatus = $zip->extractTo($storageDestinationPath);
                echo "extract status : ".$extractStatus;
                $zip->close();
                if ($extractStatus==1) {
                    return $filename;
                } else {
                    return "FAILURE";    
                }
            } else {
                echo "<br>Error reading zip-archive!";
                return "FAILURE";
            }
        } catch (\Exception $e) {
            $emailStat = $this->cronEmails(" - KARVY's Extract ZIP exception", " - ".$e->getMessage());
            return "Message : ".$e->getMessage();
        }
    }

    /* extract karvy records from dbf file */
    public function dbfKarvyDataExtract($filePath, $now) {
        try {
            $recentPAN = array();
            $table = new TableReader($filePath);

            $d = [];
            $i = 1;
            $j = 1;
            $recordCount = $table->getRecordCount();
            echo "Total Records : ".$recordCount;
            while ($record = $table->nextRecord()) {
                $i++;
                $dataCheck = Mf_karvy::where([
                    'PAN1' => $record->pan1,
                    'TD_ACNO' => $record->td_acno,
                    'FMCODE' => $record->fmcode,
                    'TD_TRNO' => $record->td_trno,
                    'TD_UNITS' => $record->td_units,
                    'TD_AMT' => $record->td_amt,
                    'UNQNO' => $record->UNQNO,
                    'TRFLAG' => $record->TRFLAG
                ])->first();
                $recentPAN[] = $record->pan1;
                if ($dataCheck) {
                    echo 'Already Exist<br><br>';
                    if($dataCheck->td_ptrno == "0" || $dataCheck->td_ptrno == null) {
                        $mfkarvyRecord = Mf_karvy::where('pk_karvy_id', $dataCheck->pk_karvy_id)->update([
                            'td_ptrno' => $record->td_ptrno
                        ]);
                    } else {
                        // dd("abc");
                    }
                } else {
                    $mf_karvy = new Mf_karvy();
                    $mf_karvy->fmcode = $this->checkNull($record->fmcode);
                    $mf_karvy->td_fund = $this->checkNull($record->td_fund);
                    $mf_karvy->td_acno = $this->checkNull($record->td_acno);
                    $mf_karvy->schpln = $this->checkNull($record->schpln);
                    $mf_karvy->funddesc = $this->checkNull($record->funddesc);
                    $mf_karvy->td_purred = $this->checkNull($record->td_purred);
                    $mf_karvy->td_trno = $this->checkNull($record->td_trno);
                    $mf_karvy->smcode = $this->checkNull($record->smcode);
                    $mf_karvy->chqno = isset($record->chqno)?$record->chqno:0;
                    $mf_karvy->invname = $this->checkNull($record->invname);
                    $mf_karvy->trnmode = $this->checkNull($record->trnmode);
                    $mf_karvy->trnstat = $this->checkNull($record->trnstat);
                    $mf_karvy->td_branch = $this->checkNull($record->td_branch);
                    $mf_karvy->isctrno = $this->checkNull($record->isctrno);
                    
                    $idval = $record->td_trdt;
                    $mf_karvy->td_trdt = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;

                    $idval = $record->td_prdt;
                    $mf_karvy->td_prdt = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;
                    
                    $mf_karvy->td_pop = $this->checkNull($record->td_pop);
                    $mf_karvy->td_units = $this->checkNull($record->td_units);
                    $mf_karvy->td_amt = $this->checkNull($record->td_amt);
                    $mf_karvy->td_agent = $this->checkNull($record->td_agent);
                    $mf_karvy->td_broker = $this->checkNull($record->td_broker);
                    $mf_karvy->brokper = $this->checkNull($record->brokper);
                    $mf_karvy->brokcomm = $this->checkNull($record->brokcomm);
                    $mf_karvy->invid = $this->checkNull($record->invid);

                    $idval = $record->crdate;
                    $mf_karvy->crdate = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;

                    $mf_karvy->crtime = $this->checkNull($record->crtime);
                    $mf_karvy->trnsub = $this->checkNull($record->trnsub);
                    $mf_karvy->td_appno = $this->checkNull($record->td_appno);
                    $mf_karvy->unqno = $this->checkNull($record->unqno);
                    $mf_karvy->trdesc = $this->checkNull($record->trdesc);
                    $mf_karvy->td_trtype = $this->checkNull($record->td_trtype);
                    $mf_karvy->chqdate = $this->checkNull($record->chqdate);
                    $mf_karvy->chqbank = $this->checkNull($record->chqbank);
                    $mf_karvy->divopt = $this->checkNull($record->divopt);
                    $mf_karvy->puramt = isset($record->puramt)?$record->puramt:0;
                    
                    // $mf_karvy->purdate = $this->checkNull($record->purdate); 
                    $idval = isset($record->purdate);
                    if($idval) {
                        if(Str::contains($idval, '/')) {
                            $date = Carbon::createFromFormat('d/m/Y', $idval)->format('Y-m-d');
                            $mf_karvy->purdate = $date;
                        } else {
                            $mf_karvy->purdate = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;
                        }
                    } else {
                        $mf_karvy->purdate = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;    
                    }
                    // $mf_karvy->purdate = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;

                    $mf_karvy->sfunddt = isset($record->sfunddt)? $this->checkNull($record->sfunddt) : null; 
                    $mf_karvy->trflag = $this->checkNull($record->trflag);
                    $mf_karvy->td_nav = $this->checkNull($record->td_nav);
                    $mf_karvy->td_ptrno = $this->checkNull($record->td_ptrno);
                    $mf_karvy->stt = $this->checkNull($record->stt);
                    $mf_karvy->loadper = $this->checkNull($record->loadper);
                    $mf_karvy->load1 = $this->checkNull($record->load1);

                    $mf_karvy->purunits = isset($record->purunits)? $this->checkNull($record->purunits) : null;
                    $mf_karvy->ihno = $this->checkNull($record->ihno);
                    $mf_karvy->branchcode = $this->checkNull($record->branchcode);

                    $mf_karvy->inwardnum0 = isset($record->inwardnum0)?$record->inwardnum0:null;
                    $mf_karvy->pan1 = $this->checkNull($record->pan1);
                    $mf_karvy->nctremarks = iconv("UTF-8","UTF-8//IGNORE",$this->checkNull($record->nctremarks));
                    $idval = $record->navdate;
                    $mf_karvy->navdate = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;
                    // -----------
                    $mf_karvy->pan2 = $this->checkNull($record->pan2);
                    $mf_karvy->pan3 = $this->checkNull($record->pan3);
                    $mf_karvy->tdsamount = $this->checkNull($record->tdsamount);
                    $mf_karvy->sch1 = $this->checkNull($record->sch1);
                    $mf_karvy->pln1 = $this->checkNull($record->pln1);
                    $mf_karvy->prcode1 = $this->checkNull($record->prcode1);
                    $mf_karvy->td_trxnmo1 = isset($record->td_trxnmo1)? $this->checkNull($record->td_trxnmo1) : null;
                    $mf_karvy->clientid = $this->checkNull($record->clientid);
                    $mf_karvy->dpid = $this->checkNull($record->dpid);
                    $mf_karvy->status = $this->checkNull($record->status);
                    $mf_karvy->rejtrnoor2 = $this->checkNull($record->rejtrnoor2);
                    $mf_karvy->subtrtype = $this->checkNull($record->subtrtype);
                    $mf_karvy->trcharges = $this->checkNull($record->trcharges);
                    $mf_karvy->atmcardst3 = isset($record->atmcardst3)? $this->checkNull($record->atmcardst3) : null;
                    $mf_karvy->atmcardre4 = isset($record->atmcardre4)? $this->checkNull($record->atmcardre4) : null;
                    $mf_karvy->brok_entdt = $this->checkNull($record->brok_entdt);
                    $mf_karvy->schemeisin = $this->checkNull($record->schemeisin);
                    $mf_karvy->citycateg5 = isset($record->citycateg5)? $this->checkNull($record->citycateg5) : null;
                    
                    $idval = $record->portdt;
                    $mf_karvy->portdt = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;
                    
                    $mf_karvy->newunqno = $this->checkNull($record->newunqno);
                    $mf_karvy->euin = $this->checkNull($record->euin);
                    $mf_karvy->subarncode = $this->checkNull($record->subarncode);
                    $mf_karvy->evalid = $this->checkNull($record->evalid);
                    $mf_karvy->edeclflag = $this->checkNull($record->edeclflag);
                    $mf_karvy->assettype = $this->checkNull($record->assettype);
                    
                    $idval = $record->sipregdt;
                    if($idval) {
                        if(Str::contains($idval, '/')) {
                            $date = Carbon::createFromFormat('d/m/Y', $idval)->format('Y-m-d');
                            $mf_karvy->sipregdt = $date;
                        } else {
                            $mf_karvy->sipregdt = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;
                        }
                    } else {
                        $mf_karvy->sipregdt = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;    
                    }
                    $mf_karvy->divper = $this->checkNull($record->divper);
                    
                    $mf_karvy->guardpanno = isset($record->guardpanno)? $this->checkNull($record->guardpanno) : null;
                    $mf_karvy->can = isset($record->can)? $this->checkNull($record->can) : null;
                    $mf_karvy->exchorgtr6 = isset($record->exchorgtr6)? $this->checkNull($record->exchorgtr6) : null;
                    $mf_karvy->electrxnf7 = isset($record->electrxnf7)? $this->checkNull($record->electrxnf7) : null;
                    $mf_karvy->sipregslno = isset($record->sipregslno)? $this->checkNull($record->sipregslno) : null;
                    $mf_karvy->cleared = isset($record->cleared)? $this->checkNull($record->cleared) : null;
                    $mf_karvy->invstate = isset($record->invstate)? $this->checkNull($record->invstate) : null;
                    $mf_karvy->tercat = isset($record->tercat)? $this->checkNull($record->tercat) : null;
                    // reinvest_flag, avail_units, avail_amount, active_flag
                
                    $mf_karvy->in_report = 0;
                    $mf_karvy->in_c_report = 0;
                    $mf_karvy->imported_date = $now->year."-".$now->month."-".$now->day;
                    $d[] = $mf_karvy;
                    // $mf_karvy->save();
                }
            }

            $sortedData = $d;
            $sortedData = Arr::sort($d, function($mf_karvy) {
                return $mf_karvy->td_trdt;
            });
            
            foreach($sortedData as $val) {
                $mf_karvy = new Mf_karvy();
                $mf_karvy = $val;
                $calStatus = $this->updateKarvyTransaction($mf_karvy);
                $saveMf_karvy = $calStatus->save();
            }
            return array_unique($recentPAN);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /* calculate karvy transactions */
    public function updateKarvyTransaction($val) {
        $result = Str::substr($val->td_purred, 0, 1);
        if(in_array($result, $this->singleCharKarvy)) {
            $trantype =  $result; 
        }
        
        $result = Str::substr($val->td_purred, 0, 2);
        if(in_array($result, $this->doubleChar)) {
            $trantype =  $result; 
        }

        $dr_flag = '';
        if($val->divopt == 'R') {
            $dr_flag = 'Y';
        } else {
            if($val->divopt == 'D') {
                $dr_flag = 'N';
            } else {
                if($val->divopt == 'G') {
                    $dr_flag = 'Z';
                } else {
                    if($val->divopt == 'B') {
                        $dr_flag = 'Z';
                    } else {
                        if($val->divopt == 'S') {
                            $dr_flag = 'Z';
                        } else {
                            
                        }
                    }
                }   
            }   
        }
        $val->reinvest_flag = $dr_flag;
        if(in_array($trantype, $this->purchase)) {
            $date2 = "2020-07-01";
            $sd = 0;
            if ($val->td_trdt > $date2) {
                $sd = round(floatval($val->td_amt)*0.005/100, 4);
            }
            $val->stamp_duty = $sd;
            if($val->trnmode == 'R') {
                $dataUpdateMode = Mf_karvy::where([
                    'td_trno' => $val->td_trno
                ])->update([
                    'avail_units' => 0,
                    'avail_amount' => 0
                ]);
                $val->avail_units = 0;
                $val->avail_amount = 0;
            } else {
                $val->avail_units = $val->td_units;
                $val->avail_amount = $val->td_amt;
            }
        } else {
            $sd = 0;
            if(in_array($trantype, $this->sell)) {
                $val->avail_units = 0;
                $val->avail_amount = 0;
                $redemptionUnits = $val->td_units;
                if($val->td_purred == 'R' && $val->trnmode == 'R' && $val->td_ptrno == "0") {
                    // dd($val);
                    $dataUpdateMode = Mf_karvy::where([
                        'td_trno' => $val->td_trno,
                        'td_pop' => $val->td_pop
                    ])->update([
                        'avail_units' => 0,
                        'avail_amount' => 0
                    ]);
                } else {
                    $dataCal = Mf_karvy::where([
                        'td_trno' => $val->td_ptrno
                    ])->get();
                    foreach ($dataCal as $singleRow) {
                        if($val->td_purred == 'R' && $val->trnmode == 'R') {
                            $val->avail_units = 0;
                            $val->avail_amount = 0;
                            $dataUpdateMode = Mf_karvy::where([
                                'pk_karvy_id' => $singleRow->pk_karvy_id
                            ])->update([
                                'avail_units' => 0,
                                'avail_amount' => 0
                            ]);
                            // echo "<br>:".$val;
                            // dd($singleRow);
                        } else {
                            echo "<br>Row : ".$singleRow;
                            if($redemptionUnits>0) { 
                                if($redemptionUnits > $singleRow['avail_units']) {
                                    $redemptionUnits = $redemptionUnits - $singleRow['avail_units'];
                                    $avail_amt = 0;
                                    $avail_units = 0;
                                } else {
                                    $avail_amt = round(($singleRow['avail_units']-$redemptionUnits)*$singleRow['td_pop'], 4);
                                    $avail_units = round($singleRow['avail_units']-$redemptionUnits, 4);
                                    $redemptionUnits = 0;
                                }
                            } else {
                                $avail_amt = $singleRow['td_amt'];
                                $avail_units = $singleRow['avail_units'];
                            }
                            // if(floatval($singleRow['td_pop'])>0) {
                            //     $redactpuramt = floatval($singleRow['td_pop']) * floatval($val->td_units);
                            // } else {
                            //     $redactpuramt = floatval($singleRow['td_amt']);
                            // }
                            // if($singleRow['avail_amount'] > 0) {
                            //     $avail_amt = round(floatval($singleRow['avail_amount']) - floatval($redactpuramt), 4);
                            //     $avail_units = round(floatval($singleRow['avail_units']) - floatval($val->td_units), 4);
                            // } else {
                            //     $avail_amt = round(floatval($singleRow['trans_amount']) - floatval($redactpuramt), 4);
                            //     $avail_units = round(floatval($singleRow['trans_units']) - floatval($val->td_units), 4);
                            // }
                            
                            $dataUpdateMode = Mf_karvy::where([
                                'pk_karvy_id' => $singleRow->pk_karvy_id
                            ])->update([
                                'avail_units' => floatval($avail_units),
                                'avail_amount' => floatval($avail_amt)
                            ]);
                        }
                    }
                }
            }
        }
        return $val;
    }

    /* Consolidate karvy transactions */
    public function consolidateKarvy($pan) {
        $products = Mf_karvy::select(
                "PAN1", 
                "schemeisin",
                "TD_ACNO",
                "FMCODE",
                "reinvest_flag",
                DB::raw("SUM(stamp_duty) as stampduty"),
                DB::raw("SUM(avail_units) as units"),
                DB::raw("SUM(avail_amount) as amount")
            )
        ->where('PAN1','=',$pan)
        ->groupBy(["PAN1", "schemeisin", "TD_ACNO", "FMCODE", "reinvest_flag"])
        ->get();
        $scheme = array();
        foreach($products as $product) {
            $schemeData = Mfscheme::where([
                'channel_partner_code' => $product->FMCODE,
                'dividend_reinvestment_flag' => $product->reinvest_flag,
                ['scheme_code' ,'not like','%'.'-DR-L0'.'%'],
                ['scheme_code' ,'not like','%'.'-L1'.'%'],
                ['scheme_code' ,'not like','%'.'-L0'.'%']
            ])->first();
            if($schemeData) {
                $schemeLive = new ConsolidatedData();
                $schemeLiveExisting = ConsolidatedData::where([
                    'mf_con_isin' => $product->schemeisin,
                    'mf_con_pan' => $product->PAN1,
                    'mf_con_sch_code' => $product->FMCODE,
                    'mf_con_folio' => $product->TD_ACNO
                ])->first();
                if($schemeLiveExisting) {
                    $schemeLive = $schemeLiveExisting;
                }
                
                $schemeLive->mf_con_pan = $product->PAN1; 
                $schemeLive->mf_con_sch_type = $schemeData->scheme_type;
                $schemeLive->mf_con_sch_name = $schemeData->scheme_name;
                $schemeLive->mf_con_sch_code = $schemeData->channel_partner_code;
                $schemeLive->mf_con_folio = $product->TD_ACNO; 
                $schemeLive->mf_con_tot_inv = round($product->amount, 2);
                $schemeLive->mf_con_cur_val = round($schemeData->net_asset_value * $product->units, 2);
                $schemeLive->mf_con_profit = round($schemeData->net_asset_value * $product->units - $product->amount, 2);
                // $schemeLive->mf_con_tran_ids = "abcdef";
                // $schemeLive->mf_con_updated_date = $products->pan;
                $schemeLive->mf_con_stamp_duty = $product->stampduty;
                $schemeLive->mf_con_isin = $product->schemeisin;
                $schemeLive->mf_con_tot_units = $product->units;
                $schemeLive->mf_con_nav_id = $schemeData->pk_nav_id;
                $schemeLive->mf_con_amc = $schemeData->amc_code;

                $schemeLiveStatus = $schemeLive->save();
                
                $myObj = array();
                $myObj['name'] = $product->FMCODE;
                $myObj['status'] = $schemeLiveStatus;
                
                $scheme[$product->FMCODE] = json_encode($myObj);
            }
        }
        $pans[$pan] = $scheme;
    }

    /* fetch Emails from cams */
    public function camsEmails() {
        try {
            $now = Carbon::now();
            $date =  Carbon::today()->format('d-m-Y');
            // dd(base_path().'/config/imap.php');
            $cm = new ClientManager(base_path().'/config/imap.php');
            // dd($cm);
            // $cm = new ClientManager($options["ssl"] = [ 
            //     'allow_self_signed' => true,
            // ] );
            $client = $cm->account('cams');
            
            //Connect to the IMAP Server
            $client->connect();
            ini_set('memory_limit','-1');
            $status = $client->isConnected();
            $folder = $client->getFolderByPath('INBOX');
            $info = $folder->examine();
            $query = $folder->messages();
            $query->setFetchOrder("desc");
            $query->setFetchOrderDesc();
            $query->fetchOrderDesc();
            $emailList = array();
            $count = $query->from('donotreply@camsonline.com')->where([["SUBJECT" => "WBR2. Investor Transactions for a Period"]])->since($date)->unseen()->get()->count();
            if($count > 0) {
                $data = $query->from('donotreply@camsonline.com')->where([["SUBJECT" => "WBR2. Investor Transactions for a Period"]])->since($date)->unseen()->setFetchOrder("asc")->get();
                foreach($data as $item){
                    $myObj = array();
                    $myObj['subject'] = "".$item->getSubject().'';
                    $emailFetchStatus = FetchEmail::where([
                        'email_subject' => $item->getSubject()
                    ])->first();
                    if($emailFetchStatus) {
                        $myObj['status'] = $emailFetchStatus;
                    } else {
                        $fetchEmail = new FetchEmail();
                        $fetchEmail->email_date = Carbon::yesterday()->format('Y-m-d');
                        $fetchEmail->email_subject = $item->getSubject();
                        $fetchEmail->email_amc = "CAMS";
                        $emailFetchStatus = $fetchEmail->save();
                        
                        $myObj['status'] = $emailFetchStatus;

                        $body = $item->getHTMLBody(true);
                        $b = e($body);
                        
                        $myArray = explode('Download Link : ', $body);
                        $myArray1 = explode("href='", $myArray[1]);
                        $myArray2 = explode("'", $myArray1[1]);
                        
                        $contents = file_get_contents($myArray2[0]);

                        if(Storage::disk('public_uploads_cams')->put($date.'_cams.zip', $contents)) {
                            $fileName = $this->extractUploadedCamsZip($date.'_cams.zip', $date);
                            Storage::disk('public_uploads_cams')->delete($date.'_cams.zip');
                            $myObj['fileName'] = $fileName;
                            $dbfStatus = $this->dbfCamsDataExtract(public_path("uploads/rta_data/cams/").$fileName, $now);
                            $myObj['dbf_status'] = $dbfStatus;
                            foreach($dbfStatus as $pan){
                                $consolidateStatus = $this->consolidateCams($pan);
                                $consolidate = array();
                                $consolidate[$pan] = $consolidateStatus;
                            }
                            $myObj['consolidate_status'] = $consolidate;
                        } else {
                            echo "false";
                        }
                    }
                    $emailList[] = $myObj;
                }
                $emailStat = $this->cronEmails(" - CAM's transaction data updated in Optymoney Portal", " - CAMS's transaction data updated in Optymoney Portal. Please verify the data in DB for confirmation.");
                $emailList['mail_status'] = $emailStat;
            } else {
                $emailStat = $this->cronEmails(" - CAM's transaction no transactions", " - There are no CAMS's transaction data.");
                $emailList['mail_count'] = $count;
                $emailList['mail_status'] = $emailStat;
            }
            return response()->json($emailList);
        } catch (\Exception $e) {
            $emailStat = $this->cronEmails(" - CAM's transaction exception", " - ".$e->getMessage());
            return "Message : ".$e->getMessage();
        }
    }

    /* extract the zip file from cams */
    public function extractUploadedCamsZip($fileName, $date){
         
        $zip = new ZipArchive();
        $filePath = public_path('uploads/rta_data/cams/').$fileName;
        echo "<br>File Path : ".$filePath;
        
        $storageDestinationPath = public_path("uploads/rta_data/cams/");
        echo "<br>Storage Path : ".$storageDestinationPath;
        if ($zip->open($filePath) == TRUE) {
            echo "<br>Password Status : ".$zip->setPassword('mantra12');
            echo "<br>length : ".$zip->numFiles;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                echo "<br>Files Name : ".$filename;
            }
            $extractStatus = $zip->extractTo($storageDestinationPath);
            $zip->close();
            if ($extractStatus==1) {
                return $filename;
            } else {
                return "FAILURE";    
            }
        } else {
            echo "<br>Error reading zip-archive!";
            return "FAILURE";
        }
        
    }

    /* extract cams records from dbf file */
    public function dbfCamsDataExtract($filePath, $now) {
        $recentPAN = array();
        $table = new TableReader($filePath);
        $d = [];
        $i = 1;
        $j = 1;
        $recordCount = $table->getRecordCount();
        echo "Total Records : ".$recordCount."<br>";
        try {
            while ($record = $table->nextRecord()) {
                $i++;
                $dataCheck = Mf_cams::where([
                    'pan' => $record->pan,
                    'folio_no' => $record->folio_no,
                    'prodcode' => $record->prodcode,
                    'trxnno' => $record->trxnno,
                    'trxnmode' => $record->trxnmode,
                    'units' => $record->units,
                    'amount' => $record->amount
                ])->first();
                $recentPAN[] = $record->pan;
                if ($dataCheck) {
                    echo '<br>Already Exist, pan : '.$record->pan.'<br>folio_no : '.$record->folio_no.'<br>prodcode : '.$record->prodcode.'<br>trxnno : '.$record->trxnno.'<br>units : '.$record->units.'<br>amount : '.$record->amount;
                } else {
                    $mf_cams = new Mf_cams();
                    $mf_cams->amc_code = $this->checkNull($record->amc_code);
                    $mf_cams->folio_no = $this->checkNull($record->folio_no);
                    $mf_cams->prodcode = $this->checkNull($record->prodcode);
                    $mf_cams->scheme = $this->checkNull($record->scheme);
                    $mf_cams->inv_name = $this->checkNull($record->inv_name);
                    $mf_cams->trxntype = $this->checkNull($record->trxntype);
                    $mf_cams->trxnno = $this->checkNull($record->trxnno);
                    $mf_cams->trxnmode = $this->checkNull($record->trxnmode);
                    $mf_cams->trxnstat = $this->checkNull($record->trxnstat);
                    $mf_cams->usercode = $this->checkNull($record->usercode);
                    $mf_cams->usrtrxno = $this->checkNull($record->usrtrxno);

                    $idval = $record->TRADDATE;
                    $mf_cams->traddate = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;

                    $idval = $record->postdate;
                    $mf_cams->postdate = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;

                    $mf_cams->purprice = $this->checkNull($record->purprice);
                    $mf_cams->units = $this->checkNull($record->units);
                    $mf_cams->amount = $this->checkNull($record->amount);
                    $mf_cams->brokcode = $this->checkNull($record->brokcode);
                    $mf_cams->subbrok = $this->checkNull($record->subbrok);
                    $mf_cams->brokperc = $this->checkNull($record->brokperc);
                    $mf_cams->brokcomm = $this->checkNull($record->brokcomm);
                    $mf_cams->altfolio = $this->checkNull($record->altfolio);
                    
                    $idval = $record->postdate;
                    $mf_cams->rep_date = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;
                    $mf_cams->time1 = $this->checkNull($record->time1);
                    $mf_cams->trxnsubtyp = $this->checkNull($record->trxnsubtyp);
                    $mf_cams->application_no = $this->checkNull($record->applicatio);
                    $mf_cams->trxn_nature = $this->checkNull($record->trxn_natur);
                    $mf_cams->tax = $this->checkNull($record->tax);
                    $mf_cams->total_tax = $this->checkNull($record->total_tax);
                    $mf_cams->te_15h = $this->checkNull($record->te_15h);
                    $mf_cams->micr_no = $this->checkNull($record->micr_no);
                    $mf_cams->remarks = $this->checkNull($record->remarks);
                    $mf_cams->swflag = $this->checkNull($record->swflag);
                    $mf_cams->old_folio = $this->checkNull($record->old_folio);
                    $mf_cams->seq_no = $this->checkNull($record->seq_no);
                    $mf_cams->reinvest_f = $this->checkNull($record->reinvest_f);
                    $mf_cams->mult_brok = $this->checkNull($record->mult_brok);
                    $mf_cams->stt = $this->checkNull($record->stt);
                    $mf_cams->location = $this->checkNull($record->location);
                    $mf_cams->scheme_type = $this->checkNull($record->scheme_typ);
                    $mf_cams->tax_status = $this->checkNull($record->tax_status);
                    $mf_cams->load_1 = $this->checkNull($record->load);
                    $mf_cams->scanrefno = $this->checkNull($record->scanrefno);
                    $mf_cams->pan = $this->checkNull($record->pan);
                    $mf_cams->inv_iin = $this->checkNull($record->inv_iin);
                    $mf_cams->targ_src_scheme = $this->checkNull($record->targ_src_s);
                    $mf_cams->trxn_type_flag = $this->checkNull($record->trxn_type_);
                    $mf_cams->ticob_trtype = $this->checkNull($record->ticob_trty);
                    $mf_cams->ticob_trno = $this->checkNull($record->ticob_trno);

                    $idval = $record->ticob_post;
                    $mf_cams->ticob_posted_date = isset($idval) ? (date_format(date_create($idval),"Y-m-d")) : null;
                    
                    $mf_cams->dp_id = $this->checkNull($record->dp_id);
                    $mf_cams->trxn_charges = $this->checkNull($record->trxn_charg);
                    $mf_cams->eligib_amt = $this->checkNull($record->eligib_amt);
                    $mf_cams->src_of_txn = $this->checkNull($record->src_of_txn);
                    $mf_cams->trxn_suffix = $this->checkNull($record->trxn_suffi);
                    $mf_cams->siptrxnno = $this->checkNull($record->siptrxnno);
                    $mf_cams->ter_location = $this->checkNull($record->ter_locati);
                    $mf_cams->euin = $this->checkNull($record->euin);
                    $mf_cams->euin_valid = $this->checkNull($record->euin_valid);
                    $mf_cams->euin_opted = $this->checkNull($record->euin_opted);
                    $mf_cams->sub_brk_arn = $this->checkNull($record->sub_brk_ar);
                    $mf_cams->exch_dc_flag = $this->checkNull($record->exch_dc_fl);
                    $mf_cams->src_brk_code = $this->checkNull($record->src_brk_co);
                    
                    $idval = $record->sys_regn_d;
                    $mf_cams->sys_regn_date = isset($idval) ? DateTime::createFromFormat('Y-m-d', $idval) : null;
                    
                    $mf_cams->ac_no = $this->checkNull($record->ac_no);
                    $mf_cams->bank_name = $this->checkNull($record->bank_name);
                    $mf_cams->reversal_code = $this->checkNull($record->reversal_c);
                    $mf_cams->exchange_flag = $this->checkNull($record->exchange_f);
                    
                    $mf_cams->ca_initiated_date = $this->checkNull($record->ca_initiat);
                    
                    $mf_cams->gst_state_code = $this->checkNull($record->gst_state_);
                    $mf_cams->igst_amount = $this->checkNull($record->igst_amoun);
                    $mf_cams->cgst_amount = $this->checkNull($record->cgst_amoun);
                    $mf_cams->sgst_amount = $this->checkNull($record->sgst_amoun);
                    $mf_cams->rev_remark = $this->checkNull($record->rev_remark);
                    $mf_cams->original_t = $this->checkNull($record->original_t);
                    $mf_cams->stamp_duty = $this->checkNull($record->stamp_duty);
                    $mf_cams->folio_old = $this->checkNull($record->folio_old);
                    $mf_cams->scheme_fol = $this->checkNull($record->scheme_fol);
                    $mf_cams->amc_ref_no = $this->checkNull($record->amc_ref_no);
                    $mf_cams->request_re = $this->checkNull($record->request_re);
                    $mf_cams->in_report = 0;
                    $mf_cams->imported_date = $now->year."-".$now->month."-".$now->day;
                    $mf_cams->in_c_report = 0;
                    $d[] = $mf_cams;
                }
                // if ($i>=1000) {
                //     $i=1;
                //     $sortedData = $d;
                //     $d = [];
                //     // echo "<br><br> 1000 count : ".$j*1000;
                //     $j++; 
                //     // $sortedData = Arr::sort($d, function($mf_cams) {
                //     //     return $mf_cams->trxnno;
                //     // });
                //     foreach($sortedData as $val) {
                //         $mf_cams = new Mf_cams();
                //         $mf_cams = $val;
                //         $saveMf_Cams = $mf_cams->save();
                //         // $calStatus = $this->updateCamsTransaction($mf_cams);
                //         // $saveMf_Cams = $calStatus->save();
                //         echo '<br>Data Inserted, pan : '.$mf_cams->pan.', folio_no : '.$mf_cams->folio_no.', prodcode : '.$mf_cams->prodcode.', trxnno : '.$mf_cams->trxnno.', units : '.$mf_cams->units.', amount : '.$mf_cams->amount;
                //     }
                // }
            }
            // $sortedData = $d;
            $sortedData = Arr::sort($d, function($mf_cams) {
                return $mf_cams->trxnno;
            });
            // echo $d;
            $i = 1;
            foreach($sortedData as $val) {
                $mf_cams = new Mf_cams();
                $mf_cams = $val;
                $calStatus = $this->updateCamsTransaction($mf_cams);
                $saveMf_Cams = $calStatus->save();

                echo '<br>Data Inserted, pan : '.$i.', Status : '.$saveMf_Cams.', trxnno : '.$mf_cams->trxnno;
                $i++;
            }
            return array_unique($recentPAN);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /* calculate cams transactions */
    public function updateCamsTransaction($val) {
        $trantype = '';
        $result = Str::substr($val->trxntype, 0, 1);
        if(in_array($result, $this->singleChar)) {
            $trantype =  $result; 
        }
        
        $result = Str::substr($val->trxntype, 0, 2);
        if(in_array($result, $this->doubleChar)) {
            $trantype =  $result; 
        }
        
        if(in_array($trantype, $this->purchase)) {
            echo "<br>".$val->scheme."->".$val->trantype."-".$val->tranmode;
            if($val->trxnmode=='R') {
                $dataUpdateMode = Mf_cams::where([
                    'usrtrxno' => $val->usrtrxno,
                    'trxnmode' => 'N'
                ])->update([
                    'avail_units' => 0,
                    'avail_amount' => 0
                ]);
                // $dataUpdateMode = Mf_cams::where([
                //     'usrtrxno' => $val->usrtrxno,
                //     'trxnmode' => 'M'
                // ])->update([
                //     'avail_units' => 0,
                //     'avail_amount' => 0
                // ]);
                $val->avail_units = 0;
                $val->avail_amount = 0;
            } else {
                $val->avail_units = $val->units;
                $val->avail_amount = $val->amount;
            }
        } else {
            //Redemption Units
            $ded_units = 0;
            $ded_amount = 0;
            if(in_array($trantype, $this->sell)) {
                $val->avail_units = 0;
                $val->avail_amount = 0;
                $dataCalc = Mf_cams::where([
                    'pan' => $val->pan,
                    'folio_no' => $val->folio_no,
                    'prodcode' => $val->prodcode,
                ])
                ->where('avail_units', '>', 0)
                ->orderBy('trxnno', 'ASC')
                ->get();
                $redemptionUnits = $val->units;

                foreach ($dataCalc as $singleRow) {
                    if($redemptionUnits!=0) {
                        if($redemptionUnits >= floatval($singleRow->avail_units)) {
                            $redemptionUnits = $redemptionUnits - floatval($singleRow->avail_units);
                            $ded_units = 0;
                            $ded_amount = 0;
                        } else {
                            $ded_units = round((floatval($singleRow->avail_units) - $redemptionUnits), 4);
                            $redemptionUnits = 0;
                            $ded_amount = round(($ded_units * $singleRow->purprice), 4);
                        }
                        $dataUpdateMode = Mf_cams::where([
                            'pk_cam_id' => $singleRow->pk_cam_id
                        ])->update([
                            'avail_units' => floatval($ded_units),
                            'avail_amount' => floatval($ded_amount)
                        ]);

                    }
                }
            }
        }
        return $val;
    }

    /* consolidate cams transaction */
    public function consolidateCams($pan) {
        echo "<br>".$pan;
        $scheme = array();
        $products = Mf_cams::select(
                "pan", 
                "folio_no",
                "prodcode",
                "reinvest_f",
                DB::raw("SUM(stamp_duty) as stampduty"),
                DB::raw("SUM(avail_units) as units"),
                DB::raw("SUM(avail_amount) as amount")
            )
        ->where('pan','=',$pan)
        ->groupBy(["pan", "folio_no", "prodcode", "reinvest_f"])
        ->get();
        foreach($products as $product){
            $schemeData = Mfscheme::where([
                'channel_partner_code' => $product->prodcode,
                'dividend_reinvestment_flag' => $product->reinvest_f,
                ['scheme_code' ,'not like','%'.'-DR-L0'.'%'],
                ['scheme_code' ,'not like','%'.'-L1'.'%'],
                ['scheme_code' ,'not like','%'.'-L0'.'%']
            ])->first();
            if($schemeData) {
                $schemeLive = new ConsolidatedData();
                $schemeLiveExisting = ConsolidatedData::where([
                    'mf_con_isin' => $schemeData->isin,
                    'mf_con_pan' => $product->pan,
                    'mf_con_sch_code' => $product->prodcode,
                    'mf_con_folio' => $product->folio_no
                ])->first();
                if($schemeLiveExisting) {
                    $schemeLive = $schemeLiveExisting;
                }
                $schemeLive->mf_con_pan = $product->pan; 
                $schemeLive->mf_con_sch_type = $schemeData->scheme_type;
                $schemeLive->mf_con_sch_name = $schemeData->scheme_name;
                $schemeLive->mf_con_sch_code = $schemeData->channel_partner_code;
                $schemeLive->mf_con_folio = $product->folio_no; 
                $schemeLive->mf_con_tot_inv = $product->amount;
                $schemeLive->mf_con_cur_val = $schemeData->net_asset_value * $product->units;
                $schemeLive->mf_con_profit = $schemeData->net_asset_value * $product->units - $product->amount;
                // $schemeLive->mf_con_tran_ids = $products->pan;
                // $schemeLive->mf_con_updated_date = $products->pan;
                $schemeLive->mf_con_stamp_duty = $product->stamp_duty;
                $schemeLive->mf_con_isin = $schemeData->isin;
                $schemeLive->mf_con_tot_units = $product->units;
                $schemeLive->mf_con_nav_id = $schemeData->pk_nav_id;
                $schemeLive->mf_con_amc = $schemeData->amc_code;
                $schemeLiveStatus = $schemeLive->save();

                $myObj = array();
                $myObj['name'] = $product->prodcode;
                $myObj['status'] = $schemeLiveStatus;
                
                $scheme[$product->prodcode] = json_encode($myObj);
            }
        }
        return response()->json($scheme);
    }

    public function schemeMaster() {
        $url = "https://www.bsestarmf.in/RptSchemeMaster.aspx";	//jsessionid=".trim($_COOKIE['JSESSIONID']);
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        
        preg_match_all('/<input type="hidden" name="([^"]*)" id="([^"]*)" value="([^"]*)"/', $result, $matches);
        $param = $matches[2][0]."=".urlencode($matches[3][0])."&".$matches[2][1]."=".urlencode($matches[3][1])."&".
                $matches[2][2]."=".urlencode($matches[3][2])."&".$matches[2][3]."=".urlencode($matches[3][3]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$param.'&ddlTypeOption=SCHEMEMASTERPHYSICAL&btnText=Export+to+Text');
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie:  ASP.NET_SessionId=pirg1qnldmbiloeeaufotagx"));
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        $server_output = curl_exec ($ch);	
        $a = curl_getinfo($ch);
        $error = curl_error($ch); 
        
        $array = explode("\n",$server_output);
        foreach ($array as $value) {
            $schemeData = explode("|",$value);
            if($schemeData[0] == "Unique No" || $schemeData[0] == "") {

            } else {
                $mfschemeRecord = Mfscheme::where([
                    'unique_no' => $schemeData[0],
                ])->first();
                if($mfschemeRecord) {

                } else {
                    $mfschemeRecord = new Mfscheme();
                    $mfschemeRecord->unique_no = $schemeData[0];
                }
                $mfschemeRecord->scheme_code = $schemeData[1];
                $mfschemeRecord->rta_scheme_code = $schemeData[2];
                $mfschemeRecord->amc_scheme_code = $schemeData[3];
                $mfschemeRecord->isin = $schemeData[4];
                $mfschemeRecord->amc_code = $schemeData[5];
                $mfschemeRecord->scheme_type = $schemeData[6];
                $mfschemeRecord->scheme_plan = $schemeData[7];
                $mfschemeRecord->scheme_name = $schemeData[8];
                $mfschemeRecord->purchase_allowed = $schemeData[9];
                $mfschemeRecord->purchase_transaction_mode = $schemeData[10];
                $mfschemeRecord->minimum_purchase_amount = $schemeData[11];
                $mfschemeRecord->additional_purchase_amount = $schemeData[12];
                $mfschemeRecord->maximum_purchase_amount = $schemeData[13];
                $mfschemeRecord->purchase_amount_multiplier = $schemeData[14];
                $mfschemeRecord->purchase_cutoff_time = $schemeData[15];
                $mfschemeRecord->redemption_allowed = $schemeData[16];
                $mfschemeRecord->redemption_transaction_mode = $schemeData[17];
                $mfschemeRecord->minimum_redemption_qty = $schemeData[18];
                $mfschemeRecord->redemption_qty_multiplier = $schemeData[19];
                $mfschemeRecord->maximum_redemption_qty = $schemeData[20];
                $mfschemeRecord->redemption_amount_minimum = $schemeData[21];
                $mfschemeRecord->redemption_amount_maximum = $schemeData[22];
                $mfschemeRecord->redemption_amount_multiple = $schemeData[23];
                $mfschemeRecord->redemption_cutoff_time = $schemeData[24];
                $mfschemeRecord->rta_agent_code = $schemeData[25];
                $mfschemeRecord->amc_active_flag = $schemeData[26];
                $mfschemeRecord->dividend_reinvestment_flag = $schemeData[27];
                $mfschemeRecord->sip_flag = $schemeData[28];
                $mfschemeRecord->stp_flag = $schemeData[29];
                $mfschemeRecord->swp_flag = $schemeData[30];
                $mfschemeRecord->switch_flag = $schemeData[31];
                $mfschemeRecord->settlement_type = $schemeData[32];
                $mfschemeRecord->amc_ind = $schemeData[33];
                $mfschemeRecord->face_value = $schemeData[34];
                $mfschemeRecord->start_date = $schemeData[35];
                $mfschemeRecord->end_date = $schemeData[36];
                $mfschemeRecord->exit_load_flag = $schemeData[37];
                $mfschemeRecord->exit_load = (empty($schemeData[38]))?null:$schemeData[38];
                $mfschemeRecord->lockin_period_flag = $schemeData[39];
                $mfschemeRecord->lockin_period = (empty($schemeData[40]))?null:$schemeData[40];
                $mfschemeRecord->channel_partner_code = $schemeData[41];
                $mfschemeRecord->reOpening_Date = (empty($schemeData[42]))?null:$schemeData[42];
                $mfschemeRecord->save();
            }
        }
        return $this->cronEmails(" - Scheme's Data updated in Optymoney Portal", " - Scheme's Data updated in Optymoney Portal. Please verify the data in DB for confirmation.");
    }

    public function cronEmails ($subject, $message) {
        $now = Carbon::now();
        $date = $now->day."-".$now->month."-".$now->year;

        $mailInfo = new \stdClass();
        $mailInfo->recieverName = "Administrator";
        $mailInfo->sender = "Optymoney";
        $mailInfo->senderCompany = "Optymoney";
        $mailInfo->to = "support@devmantra.com";
        $mailInfo->subject = $date.$subject;
        $mailInfo->name = "Optymoney";
        $mailInfo->from = "no-reply@optymoney.com";
        $mailInfo->template = "email-templates.Scheme_master_Update";
        $mailInfo->attachment = "no";
        $mailInfo->files = "";
        $mailInfo->message = $date.$message;
        
        $emailSent = Mail::to("support@devmantra.com")->send(new OptyEmail($mailInfo));
        return response()->json($emailSent);
    }

    public function manualCamsData(Request $request) {
        $now = Carbon::now();
        $date = $now->day."-".$now->month."-".$now->year;
        $dbfStatus = $this->dbfCamsDataExtract(public_path("uploads/rta_data/cams/").$request->fileName, $now);
        echo "<br> Pan Data : ".json_encode($dbfStatus);
        foreach($dbfStatus as $pan){
            $consolidateStatus = $this->consolidateCams($pan);
            echo "<br>".$consolidateStatus;
        }
    }

    public function manualKarvyData(Request $request) {
        $now = Carbon::now();
        $date = $now->day."-".$now->month."-".$now->year;
        $dbfStatus = $this->dbfKarvyDataExtract(public_path("uploads/rta_data/karvy/").$request->fileName, $now);
        echo "<br> Pan Data : ".json_encode($dbfStatus);
        foreach($dbfStatus as $pan){
            $consolidateStatus = $this->consolidateKarvy($pan);
            echo "<br>".$consolidateStatus;
        }
    }

    public function manualConsolidateCams(Request $request) {
        $data = $request->json()->all();
        $pans = array();
        foreach ($data as $key => $val) {
            $pan = $val;
            $products = Mf_cams::select(
                    "pan", 
                    "folio_no",
                    "prodcode",
                    "reinvest_f",
                    DB::raw("SUM(stamp_duty) as stampduty"),
                    DB::raw("SUM(avail_units) as units"),
                    DB::raw("SUM(avail_amount) as amount")
                )
            ->where('pan','=',$pan)
            ->groupBy(["pan", "folio_no", "prodcode", "reinvest_f"])
            ->get();
            foreach($products as $product){
                $schemeData = Mfscheme::where([
                    'channel_partner_code' => $product->prodcode,
                    'dividend_reinvestment_flag' => $product->reinvest_f,
                    ['scheme_code' ,'not like','%'.'-DR-L0'.'%'],
                    ['scheme_code' ,'not like','%'.'-L1'.'%'],
                    ['scheme_code' ,'not like','%'.'-L0'.'%']
                ])->first();
                if($schemeData) {
                    $schemeLive = new ConsolidatedData();
                    $schemeLiveExisting = ConsolidatedData::where([
                        'mf_con_isin' => $schemeData->isin,
                        'mf_con_pan' => $product->pan,
                        'mf_con_sch_code' => $product->prodcode,
                        'mf_con_folio' => $product->folio_no
                    ])->first();
                    if($schemeLiveExisting) {
                        $schemeLive = $schemeLiveExisting;
                    }
                    $schemeLive->mf_con_pan = $product->pan; 
                    $schemeLive->mf_con_sch_type = $schemeData->scheme_type;
                    $schemeLive->mf_con_sch_name = $schemeData->scheme_name;
                    $schemeLive->mf_con_sch_code = $schemeData->channel_partner_code;
                    $schemeLive->mf_con_folio = $product->folio_no; 
                    $schemeLive->mf_con_tot_inv = $product->amount;
                    $schemeLive->mf_con_cur_val = $schemeData->net_asset_value * $product->units;
                    $schemeLive->mf_con_profit = $schemeData->net_asset_value * $product->units - $product->amount;
                    // $schemeLive->mf_con_tran_ids = $products->pan;
                    // $schemeLive->mf_con_updated_date = $products->pan;
                    $schemeLive->mf_con_stamp_duty = $product->stampduty;
                    $schemeLive->mf_con_isin = $schemeData->isin;
                    $schemeLive->mf_con_tot_units = $product->units;
                    $schemeLive->mf_con_nav_id = $schemeData->pk_nav_id;
                    $schemeLive->mf_con_amc = $schemeData->amc_code;
                    $schemeLiveStatus = $schemeLive->save();

                    $myObj = array();
                    $myObj['name'] = $product->prodcode;
                    $myObj['status'] = $schemeLiveStatus;
                    
                    $scheme[$product->prodcode] = json_encode($myObj);
                }
            }
            $pans[$val] = $scheme;
        }
        return response()->json($pans);
    }

    public function manualConsolidateKarvy(Request $request) {
        // $dataList = $request->dataList;
        $data = $request->json()->all();
        $pans = array();
        foreach ($data as $key => $val) {
            $pan = $val;
            $products = Mf_karvy::select(
                    "PAN1", 
                    "schemeisin",
                    "TD_ACNO",
                    "FMCODE",
                    "FUNDDESC",
                    "reinvest_flag",
                    DB::raw("SUM(stamp_duty) as stampduty"),
                    DB::raw("SUM(avail_units) as units"),
                    DB::raw("SUM(avail_amount) as amount")
                )
            ->where('PAN1','=',$pan)
            ->groupBy(["PAN1", "schemeisin", "TD_ACNO", "FMCODE", "FUNDDESC", "reinvest_flag"])
            ->get();
            $scheme = array();
            foreach($products as $product) {
                $schemeData = Mfscheme::where([
                    'channel_partner_code' => $product->FMCODE,
                    'dividend_reinvestment_flag' => $product->reinvest_flag,
                    ['scheme_code' ,'not like','%'.'-DR-L0'.'%'],
                    ['scheme_code' ,'not like','%'.'-L1'.'%'],
                    ['scheme_code' ,'not like','%'.'-L0'.'%']
                ])->first();
                if($schemeData) {
                    $schemeLive = new ConsolidatedData();
                    $schemeLiveExisting = ConsolidatedData::where([
                        'mf_con_isin' => $product->schemeisin,
                        'mf_con_pan' => $product->PAN1,
                        'mf_con_sch_code' => $product->FMCODE,
                        'mf_con_folio' => $product->TD_ACNO
                    ])->first();
                    if($schemeLiveExisting) {
                        $schemeLive = $schemeLiveExisting;
                    }
                    
                    $schemeLive->mf_con_pan = $product->PAN1; 
                    $schemeLive->mf_con_sch_type = $schemeData->scheme_type;
                    $schemeLive->mf_con_sch_name = $schemeData->scheme_name;
                    $schemeLive->mf_con_sch_code = $schemeData->channel_partner_code;
                    $schemeLive->mf_con_folio = $product->TD_ACNO; 
                    $schemeLive->mf_con_tot_inv = round($product->amount, 2);
                    $schemeLive->mf_con_cur_val = round($schemeData->net_asset_value * $product->units, 2);
                    $schemeLive->mf_con_profit = round($schemeData->net_asset_value * $product->units - $product->amount, 2);
                    // $schemeLive->mf_con_tran_ids = "abcdef";
                    // $schemeLive->mf_con_updated_date = $products->pan;
                    $schemeLive->mf_con_stamp_duty = $product->stampduty;
                    $schemeLive->mf_con_isin = $product->schemeisin;
                    $schemeLive->mf_con_tot_units = $product->units;
                    $schemeLive->mf_con_nav_id = $schemeData->pk_nav_id;
                    $schemeLive->mf_con_amc = $schemeData->amc_code;

                    $schemeLiveStatus = $schemeLive->save();
                    
                    $myObj = array();
                    $myObj['name'] = $product->FMCODE;
                    $myObj['status'] = $schemeLiveStatus;
                    
                    $scheme[$product->FMCODE] = json_encode($myObj);
                }
            }
            $pans[$val] = $scheme;
        }
        return response()->json($pans);
    }

    public function augmontSellStatusUpdate() {
        $records = AugmontOrders::select(
            "transactionId", 
            "uniqueId",
            "id"
        )
        ->whereNull('augmont_sell_status')
        ->where('ordertype', 'sell')
        ->get();
        foreach ($records as $record) {
            $withdrawStatus = (new WithdrawAugmontController)->withdrawInfo ($record['transactionId'], $record['unique_id']);
            if($withdrawStatus->statusCode==200) {
                $data = $withdrawStatus->result->data;
                $augSellUpdate = AugmontOrders::where('id', $record['id'])->update([
                    'augmont_sell_status' => $data->status,
                    'bankTransactionId' => $data->bankTransactionId,
                    'paymentDate' => $data->paymentDate,
                    'modeOfPayment' => $data->modeOfPayment
                  ]);
            } else {
                dd('no');
            }
            
        }
    }

    /* calculate cams transactions by PAN*/
    public function updateCamsTransactionByPAN(Request $request) {
        $data = $request->json()->all();
        $dataStat = [];
        foreach ($data as $key => $val) {
            $pan = $val;
            $dataUpdateMode = Mf_cams::where([
                'pan' => $pan
            ])->update([
                'avail_units' => null,
                'avail_amount' => null
            ]);
            // $dataUpdateMode = Mf_cams::where([
            //     'pan' => $pan,
            //     'prodcode' => 'G201'
            // ])->update([
            //     'avail_units' => null,
            //     'avail_amount' => null
            // ]);
            // $schemes = Mf_cams::where(['pan' => $pan, 'prodcode' => 'G201'])->get();
            $schemes = Mf_cams::where(['pan' => $pan])->get();
            $sortedData = Arr::sort($schemes, function($mf_cams) {
                return $mf_cams->trxnno;
            });

            // $sortedData1 = Arr::sort($sortedData, function($mf_cams) {
            //     return $mf_cams->trxnno;
            // });
            // return $sortedData1;
            foreach($sortedData as $val) {
                $mf_cams = new Mf_cams();
                $mf_cams = $val;
                $calStatus = $this->updateCamsTransaction($mf_cams);
                $saveMf_Cams = $calStatus->save();
                $dataStat[] = '<br>Data Inserted, pan : '.$mf_cams->pan.', folio_no : '.$mf_cams->folio_no.', prodcode : '.$mf_cams->prodcode.', trxnno : '.$mf_cams->trxnno.', units : '.$mf_cams->units.', amount : '.$mf_cams->amount;
            }
            $this->consolidateCams($pan);
            return response()->json($dataStat);
        }
    }

    /* calculate karvy transactions by PAN*/
    public function updateKarvyTransactionByPAN(Request $request) {
        $data = $request->json()->all();
        $dataStat = [];
        foreach ($data as $key => $val) {
            $pan = $val;
            $dataUpdateMode = Mf_karvy::where([
                'pan1' => $pan
            ])->update([
                'avail_units' => null,
                'avail_amount' => null
            ]);
            // $dataUpdateMode = Mf_karvy::where([
            //     'pan1' => $pan,
            //     'fmcode' => 'RMFLFIG'
            // ])->update([
            //     'avail_units' => null,
            //     'avail_amount' => null
            // ]);
            // $schemes = Mf_karvy::where(['pan1' => $pan, 'fmcode' => 'RMFLFIG'])->get();
            $schemes = Mf_karvy::where(['pan1' => $pan])->get();
            $sortedData = Arr::sort($schemes, function($mf_karvy) {
                // return $mf_karvy->td_trno;
                return $mf_karvy->td_trdt;
            });
            foreach($sortedData as $val) {
                $mf_karvy = new Mf_karvy();
                $mf_karvy = $val;
                $calStatus = $this->updateKarvyTransaction($mf_karvy);
                $saveMf_Karvy = $calStatus->save();
                $dataStat[] = '<br>Data Inserted, pan : '.$mf_karvy->pan1.', folio_no : '.$mf_karvy->td_acno.', prodcode : '.$mf_karvy->fmcode.', trxnno : '.$mf_karvy->td_trno.', units : '.$mf_karvy->td_units.', amount : '.$mf_karvy->td_amt;
            }
            return $this->consolidateKarvy($pan);
        }
    }

    /* calculate cams transactions by PAN*/
    public function updateCamsTransactionByPANScheme(Request $request) {
        $dataStat = [];
        $pan = $request->pan;;
        $dataUpdateMode = Mf_cams::where([
            'pan' => $pan,
            'prodcode' => $request->scheme
        ])->update([
            'avail_units' => null,
            'avail_amount' => null
        ]);
        $schemes = Mf_cams::where(['pan' => $pan, 'prodcode' => $request->scheme])->orderBy('traddate', 'ASC')
        ->orderBy('trxnmode', 'ASC')->get();
        $sortedData = $schemes;
        // $sortedData = Arr::sort($schemes, function($mf_cams) {
        //     return $mf_cams->traddate;
        // });
        foreach($sortedData as $val) {
            $mf_cams = new Mf_cams();
            $mf_cams = $val;
            $calStatus = $this->updateCamsTransaction($mf_cams);
            $saveMf_Cams = $calStatus->save();
            $dataStat[] = '<br>Data Inserted, pan : '.$mf_cams->pan.', folio_no : '.$mf_cams->folio_no.', prodcode : '.$mf_cams->prodcode.', trxnno : '.$mf_cams->trxnno.', units : '.$mf_cams->units.', amount : '.$mf_cams->amount;
        }
        $this->consolidateCams($pan);
        return response()->json($dataStat);
    }

    /* calculate karvy transactions by PAN*/
    public function updateKarvyTransactionByPANScheme(Request $request) {
        $dataStat = [];
        $pan = $request->pan;
        $dataUpdateMode = Mf_karvy::where([
            'pan1' => $pan,
            'fmcode' => $request->scheme
        ])->update([
            'avail_units' => null,
            'avail_amount' => null
        ]);
        $sortedData = Mf_karvy::where(['pan1' => $pan, 'fmcode' => $request->scheme])->orderBy('td_trdt', 'ASC')->get();
        // return $sortedData;
        // $sortedData = Arr::sort($schemes, function($mf_karvy) {
        //     return $mf_karvy->td_trdt;
        // });
        foreach($sortedData as $val) {
            $mf_karvy = new Mf_karvy();
            $mf_karvy = $val;
            $calStatus = $this->updateKarvyTransaction($mf_karvy);
            $saveMf_Karvy = $calStatus->save();
            $dataStat[] = '<br>Data Inserted, pan : '.$mf_karvy->pan1.', folio_no : '.$mf_karvy->td_acno.', prodcode : '.$mf_karvy->fmcode.', trxnno : '.$mf_karvy->td_trno.', units : '.$mf_karvy->td_units.', amount : '.$mf_karvy->td_amt;
        }
        return $this->consolidateKarvy($pan);
    }

    public function reevaluate(Request $request) {
        $scheme = (new MFController)->getSchemeData($request->scheme);
        if($scheme == 'KARVY') {
            return $this->updateKarvyTransactionByPANScheme($request);
        } else {
            if($scheme == 'CAMS') {
                return $this->updateCamsTransactionByPANScheme($request);
            } else {
                return "Not Listed";
            }
        }
    }

    public function checkNull($var) {
        if($var!="") {
            return $var;
        } else {
            return null;
        }
    }
}
