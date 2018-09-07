<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rental;

class RentController extends Controller
{
    public function setRental (Request $req)
    {
        $period = implode(",", json_decode($req->period));
        Rental::create([
            'title' => $req->title,
            'description' => $req->description,
            'room' => $req->room,
            'rentDate' => $req->rentDate,
            'period' => $period
        ]);
    }

    public function getRental (Request $req)
    {
        $timestamp = ['28800', '30600', '32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
        $ss = Rental::where('rentDate', $req->date)->where('room', $req->room)->get();
        $result = [];
        $user = [];
        $temp = [];
        $data = [];

        // 取得該日期該教室已被借用之時間
        for ($i = 0; $i < count($ss); $i++)
        {
            // 拆散 period 的兩個 timestamp
            $period = explode(",", $ss[$i]['period']);

            // 判斷 timestamp 數值給予順序
            if ($period[1] > $period[0])
            {
                // 把已被租借中的時段找出來
                for ($k = array_search($period[0], $timestamp); $k < array_search($period[1], $timestamp)-1; $k++) {
                    array_push($result, $timestamp[$k+1]);
                }
                // 有可能該時段還是能被租借，例如 14:00~15:00 被借，但 14:00 跟 15:00 還能再被選取一次
                for ($k = array_search($period[0], $timestamp); $k <= array_search($period[1], $timestamp); $k++) {
                    array_push($temp, $timestamp[$k]);
                    array_push($user, $ss[$i]['user']);
                }
            } 
            else
            {
                // 把已被租借中的時段找出來
                for ($k = array_search($period[1], $timestamp); $k < array_search($period[0], $timestamp)-1; $k++) {
                    array_push($result, $timestamp[$k+1]);
                    array_push($temp, $timestamp[$k]); 
                }
                // 有可能該時段還是能被租借，例如 14:00~15:00 被借，但 14:00 跟 15:00 還能再被選取一次
                for ($k = array_search($period[1], $timestamp); $k <= array_search($period[0], $timestamp); $k++) {
                    array_push($temp, $timestamp[$k]);
                    array_push($user, $ss[$i]['user']);
                }
            } 
        }

        // 判斷時段是否真正為租借中的時間
        $unique_arr = array_unique($temp);
        $repeat_arr = array_diff_assoc($temp, $unique_arr);

        // 將確定是被租借中的時段插入到 result
        for ($l = 0; $l < count(array_values($repeat_arr)); $l++ ) {
            array_push($result, array_values($repeat_arr)[$l]);
        }

        $data['period'] = $result;
        $data['user'] = $user;
        $data['original'] = $temp;
        
        return $data;
    }
}
