<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rental;
use App\Vendor;
use Carbon\Carbon;
use Session;

class AdminController extends Controller
{
    public function index ()
    {
        return view('admin');
    }

    // 取得預約中的紀錄
    public function getAllRental ()
    {
        return Rental::where('rentDate', '>=', Carbon::today()->timestamp)->orderBy('rentDate', 'asc')->get();
    }

    // 取得編輯預約紀錄資料
    public function getEditRental (Request $req)
    {
        return Rental::where('id', $req->id)->first(['title', 'phone', 'description']);
    }

    // 更新預約紀錄資料
    public function setEditRental (Request $req)
    {
        $data = [
            'title' => $req->title,
            'description' => $req->description,
            'phone' => $req->phone
        ];

        return Rental::where('id', $req->id)->update($data);
    }

    // 駁回
    public function setRejectRental (Request $req)
    {
        return Rental::where('id', $req->id)->delete();
    }

    // 新增預約紀錄資料
    public function setRental (Request $req)
    {
        $period = explode(",", $req->period);
        $timestamp = ['32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
        $room = ['102', '103', '104', '203', '205'];

        if (!in_array($req->room, $room))
        {
            $result['status'] = false;
            return $result;
        }

        // 交換位置預防 start 大於 end
        if (intval($period[0]) > intval($period[1]))
        {
            $temp  = $period[0];
            $period[0]   = $period[1];
            $period[1]   = $temp;
        }

        $start = $period[0];
        $end   = $period[1];

        // 開始與結束的間距
        $margin = $end - $start;

        if (!in_array($start, $timestamp) || !in_array($end, $timestamp) || $margin == 0)
        {
            $result['status'] = false;
            $result['start'] = $start;
            $result['end'] = $end;
            return $result;
        }
        else
        {
            $info = Rental::create([
                'username' => $req->username,
                'name' => $req->name,
                'title' => $req->title." - ".$req->name,
                'description' => $req->description,
                'phone' => $req->phone,
                'room' => $req->room,
                'rentDate' => $req->rentDate,
                'period' => $req->period
            ]);
            $result['status'] = true;
            $result['result'] = $info;
        }
    
        return $result;
    }

    // 管理登入頁面
    public function loginPage ()
    {
        return view('login');
    }

    // 登入處理
    public function loginHandle (Request $req)
    {
        $isExist = Vendor::where('account', $req->account)->where('status', 1)->where('password', hash('sha256', ($req->password)));
        
        if ($isExist->count() == 1)
        {
            $token = bin2hex(random_bytes(32));

            $isExist->update(['token' => $token]);

            Session::put('account', $req->account);
            Session::put('token', $token);

            $result['account'] = $req->session()->get('account');
            $result['token'] = $req->session()->get('token');    
            $result['status']  = true;

            return $result;
        }
        else
        {
            $result['status']  = false;

            return $result;
        }
    }

    // 登出
    public function logout ()
    {
        Session::flush();
        return redirect('/pineapple/login');
    }
}
