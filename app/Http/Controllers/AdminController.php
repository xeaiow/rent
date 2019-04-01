<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rental;
use App\Vendor;
use App\Member;
use App\Recipient;
use Carbon\Carbon;
use Session;
use DB;

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
        return Rental::where('id', $req->id)->first(['title', 'phone', 'description', 'name', 'period']);
    }

    // 更新預約紀錄資料
    public function setEditRental (Request $req)
    {
        $timestamp = ['28800', '30600', '32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];

        // 判斷時間是否合法
        if (!in_array($req->start, $timestamp) || !in_array($req->end, $timestamp))
        {
            $response['status'] = false;
            return json_encode($response);
        }
        

        $data = [
            'title' => $req->title,
            'description' => $req->description,
            'phone' => $req->phone,
            'period' => $req->start.",".$req->end,
        ];

        $response['status'] = true;
        $response['result'] = Rental::where('id', $req->id)->update($data);
        return $response;
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
        $timestamp = ['28800', '30600', '32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
        $room = ['102', '103', '104', '203', '205'];

        // 判斷教室是否合法
        if (!in_array($req->room, $room))
        {
            $result['status'] = false;
            $result['error'] = 1;
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
            $result['error'] = 2;
            $result['start'] = $start;
            $result['end'] = $end;
            return $result;
        }
        else
        {

            // 判斷該時段是否已有他人預約
            $exists = DB::select("SELECT title, name, username, phone, rentDate, period FROM rental WHERE ((start >= '$start' AND start < '$end') OR (start <= '$start' AND end >= '$end') OR (end > '$start' AND end <= '$end')) AND rentDate = '$req->rentDate' AND room = $req->room");
            
            $existsRental = COUNT($exists);

            if ($existsRental > 0)
            {
                $result['status'] = false;
                $result['error'] = 3;
                $result['result'] = $exists;
                return $result;
            }

            $info = Rental::create([
                'username' => $req->username,
                'name' => $req->name,
                'title' => $req->title." - ".$req->name,
                'description' => $req->description,
                'phone' => $req->phone,
                'room' => $req->room,
                'rentDate' => $req->rentDate,
                'start' => $start,
                'end' => $end,
                'period' => $req->period
            ]);
            $result['status'] = true;
            $result['result'] = $info;
        }
    
        return $result;
    }

    // 批次預約
    public function setMulRental (Request $req)
    {

        // 開始與結束的 timestamp
        $period = explode(",", $req->period);
        $timestamp = ['28800', '30600', '32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
        $room = ['102', '103', '104', '203', '205'];

        if (!in_array($req->room, $room))
        {
            $result['error'] = 1;
            $result['status'] = true;
            return $result;
        }

        // 判斷是否正確的開始與結束
        if (count($period) < 2)
        {
            $result['error'] = 2;
            $result['status'] = true;
            return $result;
        }

        // 交換位置預防 start 大於 end
        if ($period[0] > $period[1])
        {
            $temp        = $period[0];
            $period[0]   = $period[1];
            $period[1]   = $temp;
        }

        $start = $period[0];
        $end   = $period[1];

        $rentDates = join(',', $req->rentDate);
        // 判斷該時段是否已有他人預約
        $exists = DB::select("SELECT title, name, username, phone, rentDate, period FROM rental WHERE ((start >= '$start' AND start < '$end') OR (start <= '$start' AND end >= '$end') OR (end > '$start' AND end <= '$end')) AND rentDate IN ($rentDates) AND room = '$req->room'");
        
        $existsRental = COUNT($exists);

        if ($existsRental > 0)
        {
            $result['error'] = 3;
            $result['status'] = true;
            $result['exists'] = true;
            $result['result'] = $exists;
            return $result;
        }


        $periods = [];
        

        // 開始與結束的間距
        $margin = $end - $start;
            
        // 判斷數值是否合法範圍
        if (!in_array($start, $timestamp) || !in_array($end, $timestamp))
        {
            $result['error'] = 2;
            $result['start'] = $start;
            $result['end'] = $end;
            $result['a'] = in_array($start, $timestamp);
            $result['b'] = in_array($end, $timestamp);
        }
        else {

            $title = $req->title;
            $phone = $req->phone;
            $desc = $req->description;
            $name = $req->name;
            $username = $req->username;
            $room = $req->room;

            $info = array();
            foreach ($req->rentDate as $value) {
                $info[] = Rental::create([
                    'title' => $title." - ".$name,
                    'phone' => $phone,
                    'description' => $desc,
                    'name' => $name,
                    'username' => $username,
                    'room' => $room,
                    'rentDate' => $value,
                    'period' => $req->period,
                    'start' => $start,
                    'end' => $end
    
                ]);
                
            }
            
            $result['result'] = $info;
            $result['error'] = false;
            $result['status'] = true;
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

    // 統計資料
    public function statistics ()
    {
        $response['total'] = Rental::count();
        $response['book'] = Rental::where('rentDate', '>=', Carbon::today()->timestamp)->orderBy('rentDate', 'asc')->count();
        $response['members'] = Member::count();

        return $response;
    }

    // 設定收件人
    public function setRecipient (Request $req)
    {
        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'switch' => 1
        ];

        return Recipient::create($data)->id;
    }

    // 載入收件者列表
    public function getRecipient ()
    {
        return Recipient::get();  
    }

    public function excel () {
        return Member::make('excel');
    }

    public function exe ()
    {
        // $get = Rental::all();

        // $period = [];

        // foreach ($get as $key => $item) {
        //     $period[] = explode(",", $item['period']);

        //     $data = [
        //         'start' => $period[$key][0],
        //         'end' => $period[$key][1]
        //     ];

        //     Rental::where('id', $item['id'])->update($data);
        // }
        // return $data;
        $start = '37800';
        $end = '75600';
        $rentDate = '1552406400';
        $room = '203';
        $exists = DB::select("SELECT id AS count FROM rental WHERE ((start >= '$start' AND start <= '$end') OR (start <= '$start' AND end >= '$end') OR (end >= '$start' AND end <= '$end')) AND rentDate = '$rentDate' AND room = $room");
        echo COUNT($exists);
    }
}
