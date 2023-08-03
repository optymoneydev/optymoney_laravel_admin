<?php

namespace App\Http\Controllers\marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Coupons;
use View;


class CouponController extends Controller
{
    public function getCoupons(Request $request) {
        $couponsData = Coupons::get()
              ->sortByDesc("cou_id")
              ->toJson();
        return $couponsData;
    }
    
    public function saveCoupon(Request $request) {
        $id = $request->session()->get('id');

        $coupon = new Coupons();
        if($request['cou_id'] != "") {
            $coupon = Coupons::find($request['cou_id']);
            $coupon->cou_id = $request['cou_id'];
            $coupon->cou_modified_by = $id;
            $coupon->cou_modified_ip = $request->ip();
        } else {
            $coupon->cou_created_by = $id;
            $coupon->cou_created_ip = $request->ip();
        }

        $coupon->cou_name = $request['cou_name'];
        $coupon->cou_per = $request['cou_per'];
        $coupon->cou_quantity = $request['cou_quantity'];
        $coupon->cou_partner_cmpny = $request['cou_partner_cmpny'];
        $coupon->cou_validity = $request['cou_validity'];
        $coupon->cou_code = $request['cou_code'];
        
        $savecoupon = $coupon->save();
        if($savecoupon==1) {
            if($request['cou_id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'Coupon updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'Coupon added successfully.'
                ];
            }
        } else {
            if($request['cou_id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'Coupon updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'Coupon adding failed.'
                ];
            }
        }
        return $data;
    }

    public function couponById(Request $request) {
        $couponData = Coupons::where('cou_id', '=', $request->id)->get()->first();
        return $couponData;
    }

    public function deleteCouponById(Request $request) {
        $couponData = Coupons::where('cou_id', '=', $request->id)->delete();
        return $couponData;
    }


}
