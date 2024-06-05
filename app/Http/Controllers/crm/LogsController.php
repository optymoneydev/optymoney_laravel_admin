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
                $logEntries[] = [
                    'date' => $match[1],    // Timestamp
                    'name' => $txt[0], // Log message
                    'count' => $user1
                ];
            }
            return $logEntries;
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
