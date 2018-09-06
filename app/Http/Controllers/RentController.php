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

        // 取得該日期該教室已被借用之時間
        for ($i = 0; $i < count($ss); $i++) {

            $period = explode(",", $ss[$i]['period']);

            // for ($j = 0;$j < count($period); $j++) {
            //     array_push($result, $period[$j]);
            // }

            for ($k = array_search($period[0], $timestamp); $k <= array_search($period[1], $timestamp); $k++) {
                array_push($result, $timestamp[$k]);
            }
        }
        
        return $result;
    }
}
