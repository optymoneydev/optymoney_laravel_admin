<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Events;
Use App\Models\EventUsers;
Use App\Models\EventUsersFeedback;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_users_detail;
use Illuminate\Support\Facades\Hash;
use View;


class EventController extends Controller
{
    public function getEvents(Request $request) {
        $eventsData = Events::get()
              ->sortByDesc("event_id")
              ->toJson();
        return $eventsData;
    }

    public function getEventUsers(Request $request) {
        $eventUsersData = EventUsers::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'event_details.user_id')
        ->join('bfsi_user', 'bfsi_user.pk_user_id', '=', 'event_details.user_id')
        ->orderBy("event_details.event_d_id", "desc")
        ->get(['event_details.*', 'bfsi_user.login_id', 'bfsi_users_details.contact_no', 'bfsi_users_details.email', 'bfsi_users_details.cust_name']);
        return $eventUsersData;
    }
    
    public function saveEvent(Request $request) {
        $id = $request->session()->get('id');
        $event_code = str_replace(' ', '_', $request['event_code']);
		$url = "https://optymoney.com/connectEvent/".$event_code;
        $event = new Events();
        if($request['event_id'] != "") {
            $event = Events::find($request['event_id']);
            $event->event_id = $request['event_id'];
            $event->event_modified_by = $id;
            $event->event_modified_ip = $request->ip();
        } else {
            $event->event_created_by = $id;
            $event->event_created_ip = $request->ip();
        }

        $event->event_name = $request['event_name'];
        $event->event_code = $event_code;
        $event->event_url = $url;
        $event->event_date = $request['event_date'];
        $event->event_status = $request['event_status'];
        $event->event_img = $request['event_img'];
        $event->meta_keywords = $request['meta_keywords'];
        $event->meta_description = $request['meta_description'];
        $event->bm_content = $request['bm_content'];
        $event->event_img_code = $request['event_img_code'];
        $event->event_subject = $request['event_subject'];
        
        $saveevent = $event->save();
        if($saveevent==1) {
            if($request['event_id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'Event updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'Event added successfully.'
                ];
            }
        } else {
            if($request['event_id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'Event updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'Event adding failed.'
                ];
            }
        }
        return $data;
    }

    public function eventById(Request $request) {
        $eventData = Events::where('event_id', '=', $request->event_id)->get()->first();
        return $eventData;
    }

    public function eventByCode(Request $request) {
        $eventData = Events::where('event_code', '=', $request->event_id)->get()->first();
        return $eventData;
    }

    public function deleteEventById(Request $request) {
        $eventData = Events::where('event_id', '=', $request->event_id)->delete();
        return $eventData;
    }

    public function eventReg(Request $request) {
        $eventReg = new EventUsers();
        $eventData = Events::where('event_code', '=', $request->url)->get()->first();

        $user = Bfsi_user::where([
            'login_id' => $request->formemail
        ])->first();
        if($user) {
            $eventCheck = EventUsers::where([
                'user_id' => $user->pk_user_id,
                'event_p_code' => $request->url
            ])->first();
            if($eventCheck) {
                $data = [
                    'status_code' => 201,
                    'message' => 'Already registered to this event.'
                ];
            } else {
                $eventReg->user_id = $user->pk_user_id;
                $eventReg->event_p_code = $request->url;
                $eventReg->user_org = $request->formorg;
                $eventReg->save();

                $userData = (new UsersController)->getUserDataByUID($user->pk_user_id);
                $res2 = (new EmailController)->send_event_reg_status($userData, $eventData);
                $data = [
                    'status_code' => 201,
                    'message' => 'Registered to this event.'
                ];
            }
        } else {
            $pswd = str_random(8);
            $createAccountStatus = Bfsi_user::create([
                'login_id' => $request->formemail,
                'password' => "abcdef",
                'aug_pswd' => Hash::make($pswd),
                'communication_email' => "Permanent",
                'user_status' => "Active",
                'signup_ip' => getenv('REMOTE_ADDR'),
                'signup_date' => \Carbon\Carbon::now()->timestamp,
                'contact' => $request->formnumber,
                'created_from' => "Event - ".$request->url
            ])->pk_user_id;
            $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $createAccountStatus)->update([
                'password' => Hash::make($pswd),
                'aug_pswd' => Hash::make($pswd)
            ]);
            $updateUserDetails = Bfsi_users_detail::create([
                'fr_user_id' => $createAccountStatus,
                'cust_name' => $request->formname,
                'contact_no' => $request->formnumber
              ]);
              $eventCheck = EventUsers::where([
                'user_id' => $createAccountStatus,
                'event_p_code' => $request->url
            ])->first();
            if($eventCheck) {
                $data = [
                    'status_code' => 201,
                    'message' => 'Already registered to this event.'
                ];
            } else {
                $eventReg->user_id = $createAccountStatus;
                $eventReg->event_p_code = $request->url;
                $eventReg->user_org = $request->formorg;
                $eventReg->save();

                $userData = (new UsersController)->getUserDataByUID($createAccountStatus);
                $res2 = (new EmailController)->send_event_reg_status($userData, $eventData);
                $data = [
                    'status_code' => 201,
                    'message' => 'Registered to this event.'
                ];
            }
            $userData = (new UsersController)->getUserDataByUID($createAccountStatus);
            $res2 = (new EmailController)->send_user_creation_email_from_event($userData, $pswd);
            $res2 = (new EmailController)->send_event_reg_status($userData, $eventData);
            $data = [
                'status_code' => 201,
                'message' => 'Registered to this event.'
            ];
        }
        return $data;
    }

    public function eventFeedback(Request $request) {
        $eventReg = new EventUsersFeedback();
        $eventData = Events::where('event_code', '=', $request->url)->get()->first();

        $user = Bfsi_user::where([
            'login_id' => $request->formemail
        ])->first();
        if($user) {
            $eventCheck = EventUsersFeedback::where([
                'user_id' => $user->pk_user_id,
                'event_p_code' => $request->url
            ])->first();
            if($eventCheck) {
                $data = [
                    'status_code' => 201,
                    'message' => 'Already submitted the feedback to this event.'
                ];
            } else {
                $eventReg->user_id = $user->pk_user_id;
                $eventReg->event_p_code = $request->url;
                $eventReg->user_org = $request->formorg;
                $eventReg->save();

                $userData = (new UsersController)->getUserDataByUID($user->pk_user_id);
                $res2 = (new EmailController)->send_event_feedback_status($userData, $eventData);
                $data = [
                    'status_code' => 201,
                    'message' => 'Feedback submitted to this event.'
                ];
            }
        } else {
            $pswd = str_random(8);
            $createAccountStatus = Bfsi_user::create([
                'login_id' => $request->formemail,
                'password' => "abcdef",
                'aug_pswd' => Hash::make($pswd),
                'communication_email' => "Permanent",
                'user_status' => "Active",
                'signup_ip' => getenv('REMOTE_ADDR'),
                'signup_date' => \Carbon\Carbon::now()->timestamp,
                'contact' => $request->formnumber,
                'created_from' => "Event - ".$request->url
            ])->pk_user_id;
            $userStatus = \App\Models\Bfsi_user::where('pk_user_id', $createAccountStatus)->update([
                'password' => Hash::make($pswd),
                'aug_pswd' => Hash::make($pswd)
            ]);
            $updateUserDetails = Bfsi_users_detail::create([
                'fr_user_id' => $createAccountStatus,
                'cust_name' => $request->formname,
                'contact_no' => $request->formnumber
              ]);
              $eventCheck = EventUsersFeedback::where([
                'user_id' => $createAccountStatus,
                'event_p_code' => $request->url
            ])->first();
            if($eventCheck) {
                $data = [
                    'status_code' => 201,
                    'message' => 'Already submitted the feedback to this event.'
                ];
            } else {
                $eventReg->user_id = $createAccountStatus;
                $eventReg->event_p_code = $request->url;
                $eventReg->user_org = $request->formorg;
                $eventReg->save();

                $userData = (new UsersController)->getUserDataByUID($createAccountStatus);
                $res2 = (new EmailController)->send_event_feedback_status($userData, $eventData);
                $data = [
                    'status_code' => 201,
                    'message' => 'Feedback submitted to this event.'
                ];
            }
            $userData = (new UsersController)->getUserDataByUID($createAccountStatus);
            $res2 = (new EmailController)->send_user_creation_email_from_event($userData, $pswd);
            $res2 = (new EmailController)->send_event_feedback_status($userData, $eventData);
            $data = [
                'status_code' => 201,
                'message' => 'Feedback submitted to this event.'
            ];
        }
        return $data;
    }
}
