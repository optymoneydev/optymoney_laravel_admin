<?php

namespace App\Http\Controllers\logs;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\OrdersAugmontController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
Use App\Models\AugmontMerchant;
use Carbon\Carbon;
use DateTime;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Razorpay_Subscription;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use File;

class LogsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:userapi', ['except' => ['showLoginLog', 'showLoginLogCount']]);
    }

    public function showLoginLog() {
        $logPath = storage_path("logs/userlogindata.log");
        if (File::exists($logPath)) {
            $logContents = File::get($logPath);
            $matches = $this->extractInfo($logContents);
            $logEntries = [];
            foreach ($matches as $match) {
                $txt = explode(": ", $match[2]);
                $logEntries[] = [
                    'date' => $match[1],    // Timestamp
                    'message' => $txt[0], // Log message
                    'content' => json_decode($txt[1])
                ];
            }
            return $logEntries;
        } else {
            return null;
        }
    }

    public function showLoginLogCount() {
        $logPath = storage_path("logs/userlogindata.log");
        if (File::exists($logPath)) {
            $logContents = File::get($logPath);
            $matches = $this->extractInfo($logContents);
            $logEntries = [];
            foreach ($matches as $match) {
                $txt = explode(": ", $match[2]);
                $user1 = json_decode($txt[1]);
                $uid = $user1->user->pk_user_id;
                if(isset($logEntries[$uid])){
                    $i = $logEntries[$uid]['count'];
                    $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $logEntries[$uid]['date']);
                    $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $match[1]);
                    $result = $date1->lt($date2);
                    if($result == 1) {
                        $latestDate = $date2;
                    } else {
                        $latestDate = $date1;
                    }
                    $logEntries[$uid] = [
                        'date' => $latestDate,
                        'name' => $txt[0],
                        'content' => $user1,
                        'count' => $i+1
                    ];
                } else {
                    $logEntries[$uid] = [
                        'date' => $match[1],
                        'name' => $txt[0],
                        'content' => $user1,
                        'count' => 1
                    ];
                }
            }
            $logs = [];
            foreach ($logEntries as $log) {
                $logs[] = $log;
            }
            return $logs;
        } else {
            return null;
        }
    }

    public function showLoginLogByDates(Request $request) {
        $startDate = Carbon::createFromFormat('d/m/Y', $request->startDate);
        $endDate = Carbon::createFromFormat('d/m/Y', $request->endDate);
        $logPath = storage_path("logs/userlogindata.log");
        if (File::exists($logPath)) {
            $logContents = File::get($logPath);
            $matches = $this->extractInfo($logContents);
            $logEntries = [];
            foreach ($matches as $match) {
                $txt = explode(": ", $match[2]);
                $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $match[1]);
                if ($date1->between($startDate, $endDate)) {
                    $logEntries[] = [
                        'date' => $match[1],    // Timestamp
                        'message' => $txt[0], // Log message
                        'content' => json_decode($txt[1]),
                    ];
                } else {
                    
                }
            }
            return $logEntries;
        } else {
            return null;
        }
    }

    protected function extractErrors($logContents)
    {
        // Example: Extract error messages containing the word 'error'
        preg_match_all('/\[[^\]]+\] local.ERROR: (.+?)(?=\[|$)/', $logContents, $matches);

        // Flatten the matches array
        return array_flatten($matches);
    }

    protected function extractInfo($logContents)
    {
        preg_match_all('/^\[([^\]]+)\]\s*([^\r\n]+)/m', $logContents, $matches, PREG_SET_ORDER);
        return $matches;
    }
}
