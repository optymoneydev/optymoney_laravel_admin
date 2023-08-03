<?php

namespace App\Http\Controllers\mf;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Http\Request;
Use App\Models\Mfscheme;
Use App\Models\SchemeFilters;
Use App\Models\NavOffers;
Use App\Models\MfConsolidated;
Use App\Models\NavPrice;
Use App\Models\SchemeOffers;
Use App\Models\Mf_cams;
Use App\Models\Mf_karvy;

class MFController extends Controller
{

    public function autocompleteSchemes(Request $request) {
        $schemes = Mfscheme::select("scheme_name")
                    ->where('scheme_name', 'LIKE', '%'. $request->get('query'). '%')
                    ->get();
        $data = array();
        foreach ($schemes as $scheme) {
            $data[] = $scheme->scheme_name;
        }
        return response()->json($data);
    }

    public function getSchemes(Request $request) {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Mfscheme::select('count(*) as allcount')->where('mf_master.scheme_plan', '=', 'NORMAL')->count();
        $totalRecordswithFilter = Mfscheme::select('count(*) as allcount')->where([['amc_code', 'like', '%' . $searchValue . '%'], ['mf_master.scheme_plan', '=', 'NORMAL']])->count();

        // Get records, also we have included search filter as well
        $records = Mfscheme::orderBy($columnName, $columnSortOrder)
            ->where([
                ['mf_master.scheme_plan', '=', 'NORMAL'],
                ['mf_master.scheme_name', 'like', '%' . $searchValue . '%']
            ])
            ->orWhere('mf_master.scheme_type', 'like', '%' . $searchValue . '%')
            // ->orWhere('mf_master.branch', 'like', '%' . $searchValue . '%')
            ->select('mf_master.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "pk_nav_id" => $record->pk_nav_id,
                "scheme_code" => $record->scheme_code,
                "scheme_name" => $record->scheme_name,
                "scheme_type" => $record->scheme_type,
                "unique_no" => $record->unique_no,
                "action" => '<div class="btn-group"><button type="button" class="btn btn-primary schemeView" data-id="'.$record->pk_nav_id.'"><i class="fa fa-eye"></i></button></div>',
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

    public function getSchemesAPI(Request $request) {
        // $records = Mfscheme::where([
        //         ['mf_master.scheme_plan', '=', 'NORMAL'],
        //         ['mf_master.sch_popularity', '=', '5'],
        //         ['mf_master.one_year_return', '>', '0'],
        //         // ['mf_master.scheme_name', 'like', '%' . $searchValue . '%']
        //     ])
        //     ->orderBy("one_year_return", 'DESC')
        //     // ->orWhere('mf_master.scheme_type', 'like', '%' . $searchValue . '%')
        //     // ->orWhere('mf_master.branch', 'like', '%' . $searchValue . '%')
        //     ->select('mf_master.*')
        //     // ->skip($start)
        //     // ->take($rowperpage)
        //     // ->limit(20)
        //     ->get();

        $records = Mfscheme::where('scheme_plan', 'NORMAL')
            ->where('mf_master.sch_popularity', '=', '5')
                // ['mf_master.one_year_return', '>', '0']])
            ->orderBy("one_year_return", 'DESC')
            ->limit(20)
            ->get(['mf_master.amc_code', 'mf_master.scheme_name', 'mf_master.scheme_type', 'mf_master.one_year_return', 'mf_master.three_year_return', 'mf_master.five_year_return', 'mf_master.scheme_name', 'mf_master.pk_nav_id']);

        // return $records;
        $data = [
            "statusCode" => 201,
            "data" => $records
        ];
        return $data;
    }

    public function getSchemesByOfferAPI(Request $request) {
        $records = SchemeOffers::join('mf_master', 'mf_master.pk_nav_id', '=', 'sch_offers.sch_id')
        ->where('mf_master.scheme_plan', 'NORMAL')
        ->where('sch_offers.offer_id', $request->offerSearch)
        ->get(['mf_master.amc_code', 'mf_master.scheme_name', 'mf_master.scheme_type', 'mf_master.one_year_return', 'mf_master.three_year_return', 'mf_master.five_year_return', 'mf_master.scheme_name', 'mf_master.pk_nav_id']);

        // $records = Mfscheme::orderBy("one_year_return", 'DESC')
        //     ->where([
        //         ['mf_master.scheme_plan', '=', 'NORMAL'],
        //         ['mf_master.offer', '=', $request->offerSearch]
        //         // ['mf_master.scheme_name', 'like', '%' . $searchValue . '%']
        //     ])
        //     // ->orWhere('mf_master.scheme_type', 'like', '%' . $searchValue . '%')
        //     // ->orWhere('mf_master.branch', 'like', '%' . $searchValue . '%')
        //     ->select('mf_master.*')
        //     // ->skip($start)
        //     // ->take($rowperpage)
        //     ->limit(10)
        //     ->get();
        // // return $records;
        $data = [
            "statusCode" => 201,
            "data" => $records
        ];
        return $data;
    }

    public function getSchemeById(Request $request) {
        $schemeData = Mfscheme::where('pk_nav_id', '=', $request->id)->get()->first();
        $data = [
            'risk' => $this->getRiskList(),
            'scheme' => $schemeData
        ];
        return $data;
    }

    public function getSchemeByName(Request $request) {
        $schemeData = Mfscheme::where('scheme_name', '=', $request->schemeSearch)->get()->first();
        $data = [
            // 'risk' => $this->getRiskList(),
            'scheme' => $schemeData,
            'nav_data' => $this->get_nav($schemeData->isin)
        ];
        return $data;
    }

    public function get_nav($isin) {
        $nav_data = NavPrice::where('isin', '=', $isin)
        ->orderBy('price_date', 'asc')
        ->distinct('price_date')
        ->get(['price_date','net_asset_value']);
		return $nav_data;
	}

    public function getValuesByOptions(Request $request) {
        $schemeData = SchemeFilters::where([['scheme_id', '=', $request->sch_id], ['options', '=', $request->options]])->get();
        return $schemeData;
    }

    public function getNavOffers(Request $request) {
        $navOffers = NavOffers::where('offer_status', "Active")->get();
        return $navOffers;
    }

    // public function getNav(Request $request) {
    //     $blogsData = Blogs::get(['id','post_title', 'post_category', 'post_status', 'post_date', 'post_created_by'])
    //           ->sortByDesc("id")
    //           ->toJson();
    //     $data = [
    //         'blogsCategory' => $this->getBlogsCategory(),
    //         'blogs' => $blogsData
    //     ];
    //     return $data;
    // }

    // public function getNavByScheme(Request $request) {
    //     $blogsData = Blogs::get(['id','post_title', 'post_category', 'post_status', 'post_date', 'post_created_by'])
    //           ->sortByDesc("id")
    //           ->toJson();
    //     $data = [
    //         'blogsCategory' => $this->getBlogsCategory(),
    //         'blogs' => $blogsData
    //     ];
    //     return $data;
    // }
    
    public function getRiskList() {
        $riskData = Mfscheme::get(['sch_risk'])
                ->groupBy('sch_risk')
                ->sortBy("sch_risk");
        return $riskData;
    }

    public function getPortfolioByUser(Request $request) {

        $userData = (new UsersController)->getUserDataByUID($request->id);
        //For Getting all Folio
		$fetch_portfolio1 = array();
		if ($userData->pan_number!="") {
            $userPortfolio = MfConsolidated::where('mf_con_pan', $userData->pan_number)->get();
			$navPrice = array();
            foreach($userPortfolio as $item) { //foreach element in $arr
                // $fetch_portfolio1[] = $item->mf_con_tot_inv;
                if($item->mf_con_tot_inv>0) {
					$navPrice[$item->mf_con_isin] = $this->get_nav_latest_isin($item->mf_con_isin);
					$item->nav = $navPrice[$item->mf_con_isin];
					$fetch_portfolio1[$item->mf_con_isin.'_'.$item->mf_con_folio] = $item;
				}
            }
		} else {
			
		}
		return $fetch_portfolio1;
    }

    public function get_nav_latest_isin($sch_isin) {
        $navPrice = Mfscheme::where('isin', $sch_isin)->orderBy('price_date', 'desc')->first(['isin', 'net_asset_value', 'price_date', 'scheme_code']);
        if($navPrice) {
            return $navPrice;
        } else {
            $myObj->isin = $sch_isin;
            $myObj->net_asset_value = 0;
            $myObj->price_date = 0;
            $myObj->fr_scheme_code = "";

            $myJSON = json_encode($myObj);
            return $myJSON;
        }
	}

    public function getPortfolioByUserAPI(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $userData = (new UsersController)->getUserDataByUID($id);
            $pan = "";
            if($request->pan) {
                $pan = $request->pan;
            } else {
                $pan = $userData->pan_number;
            }

            //For Getting all Folio
            $fetch_portfolio = array();
            if ($pan!="") {
                $userPortfolio = MfConsolidated::where('mf_con_pan', $pan)->get();
                $navPrice = array();
                foreach($userPortfolio as $item) { //foreach element in $arr
                    // $fetch_portfolio1[] = $item->mf_con_tot_inv;
                    if($item->mf_con_tot_inv>0) {
                        $navPrice[$item->mf_con_isin] = $this->get_nav_latest_isin($item->mf_con_isin);
                        $item->nav = $navPrice[$item->mf_con_isin];
                        $fetch_portfolio[$item->mf_con_isin.'_'.$item->mf_con_folio] = $item;
                    }
                }
                $data = [
                    "statusCode" => 201,
                    "data" => $fetch_portfolio
                ];
            } else {
                $data = [
                    "statusCode" => 400,
                    "message" => "No Data Available!"
                ];
            }
		    return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
        
    }

    public function getSchemeData($scheme_code) {
        $scheme = Mfscheme::where('channel_partner_code', $scheme_code)->first(['rta_agent_code']);
        return $scheme->rta_agent_code;
    }

    public function getTransactionsById(Request $request) {
        $scheme = $this->getSchemeData($request->scheme_code);
        if($scheme == 'KARVY') {
            $data = [
                'schemetype' => "karvy",
                'data' => $this->getTransactionsFromKarvy($request->pan, $request->scheme_code, $request->folio_no)
            ];
            return $data;
        } else {
            if($scheme == 'CAMS') {
                $data = [
                    'schemetype' => "cams",
                    'data' => $this->getTransactionsFromCams($request->pan, $request->scheme_code, $request->folio_no)
                ];
                return $data;
            } else {
                $data = [
                    'schemetype' => "",
                    'data' => ""
                ];
                return $data;
            }
        }
    }

    public function getTransactionsFromCams($pan, $scheme_code, $folio_no) {
        $transactions = Mf_cams::where(['pan' => $pan, 'prodcode' => $scheme_code, 'folio_no' => $folio_no])->get();
        return $transactions;
    }

    public function getTransactionsFromKarvy($pan, $scheme_code, $folio_no) {
        $transactions = Mf_karvy::where(['pan1' => $pan, 'fmcode' => $scheme_code, 'td_acno' => $folio_no])->get();
        return $transactions;
    }

}
