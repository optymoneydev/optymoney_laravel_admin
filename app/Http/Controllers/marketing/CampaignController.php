<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Campaigns;
Use App\Models\Bfsi_users_detail;
use View;


class CampaignController extends Controller
{
    public function getCampaigns(Request $request) {
        $clientData = Campaigns::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'campaigns.user_id')
        ->join('bfsi_user', 'bfsi_user.pk_user_id', '=', 'campaigns.user_id')
        ->get(['campaigns.*', 'bfsi_users_details.cust_name', 'bfsi_users_details.contact_no', 'bfsi_user.login_id'])
        ->sortByDesc("pk_goal_id")->all();
        return response()->json($clientData);
    }
    
}
