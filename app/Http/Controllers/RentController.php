<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ArrayObject;
use App\Rental;
use App\Member;
use Carbon\Carbon;
use DB;

class RentController extends Controller
{
    public function setRental (Request $req)
    {
        $isToken = Member::where('username', $req->username)->where('name', $req->name)->where('token', $req->token)->count();

        if ($isToken != 1) {
            $result['status'] = false;
            return $result;
        }

        if ( mb_strlen( $req->title, "utf-8") > 15 || mb_strlen( $req->description, "utf-8") > 100 || mb_strlen( $req->phone, "utf-8") > 15)
        {
            $result['error'] = true;
            $result['status'] = true;
            return false;
        }

        // 開始與結束的 timestamp
        $decode = substr(substr($req->period, 0, -1), 1);
        $period = explode(",", $decode);
        $timestamp = ['32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
        $room = ['103', '104', '203', '205'];

        if (!in_array($req->room, $room))
        {
            $result['error'] = true;
            $result['status'] = true;
            return $result;
        }

        // 判斷是否正確的開始與結束
        if (count($period) < 2)
        {
            $result['error'] = true;
            $result['status'] = true;
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

        // 判斷該時段是否已有他人預約
        $exists = DB::select("SELECT id FROM rental WHERE ((start >= '$start' AND start < '$end') OR (start <= '$start' AND end >= '$end') OR (end > '$start' AND end <= '$end')) AND rentDate = '$req->rentDate' AND room = $req->room");
        
        $existsRental = COUNT($exists);

        if ($existsRental > 0)
        {
            $result['error'] = true;
            $result['status'] = true;
            $result['exists'] = true;
            $result['code'] = 3;
            return $result;
        }


        // 判斷是否重複預約同個時段
        $rentalRecord = Rental::where('username', $req->username)->where('rentDate', $req->rentDate)->where('room', $req->room)->count();

        if ($rentalRecord > 0) {
            $result['error'] = true;
            $result['status'] = true;
            $result['exists'] = true;
            $result['code'] = 2;
            return $result;
        }

        $periods = [];
        
        $ss = Rental::where('username', $req->username)->where('rentDate', $req->rentDate)->get();
    
        foreach ($ss as $val) {
            $periodRecord = explode(",", $val['period']);
            $periods['start'] = $periodRecord[0];
            $periods['end'] = $periodRecord[1];

            if ( ( $period[0] >= $periodRecord[0] && $period[0] < $periodRecord[1] ) || ( $period[1] >= $periodRecord[0] && $period[1] < $periodRecord[1] ) ) {
                $result['error'] = true;
                $result['status'] = true;
                $result['exists'] = true;
                $result['code'] = 1;
                $result['aa'] = $periods;
                $result['bb'] = $period;
                return $result;
            }
        }
        

        // 開始與結束的間距
        $margin = $end - $start;
            
        // 判斷數值是否合法範圍
        if (!in_array($start, $timestamp) || !in_array($end, $timestamp) || $margin > 10800 || $margin == 0)
        {
            $result['error'] = true;
        }
        else {
            $id = Rental::create([
                'title' => $req->title." - ".$req->name,
                'phone' => $req->phone,
                'description' => $req->description,
                'name' => $req->name,
                'username' => $req->username,
                'room' => $req->room,
                'rentDate' => $req->rentDate,
                'period' => implode(",", json_decode($req->period)),
                'start' => $start,
                'end' => $end

            ])->id;
            $result['id'] = $id;
            $result['error'] = false;
        }

        $result['status'] = true;

        return $result;
    }



    public function getRental (Request $req)
    {
        $timestamp = ['32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
        $ss = Rental::where('rentDate', $req->date)->where('room', $req->room)->get();
        $result = [];
        $user = [];
        $temp = [];
        $data = [];
        $renter = [];
        $original = [];

        // 取得該日期該教室已被借用之時間
        for ($i = 0; $i < count($ss); $i++)
        {
            // 拆散 period 的兩個 timestamp
            $period = explode(",", $ss[$i]['period']);

            $temp = [];

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
                    array_push($original, $timestamp[$k]);
                    array_push($user, $ss[$i]['name']);
                }
            } 
            else
            {
                // 把已被租借中的時段找出來
                for ($k = array_search($period[1], $timestamp); $k < array_search($period[0], $timestamp)-1; $k++) {
                    array_push($result, $timestamp[$k+1]);
                    array_push($temp, $timestamp[$k]);
                    array_push($original, $timestamp[$k]);
                }
                // 有可能該時段還是能被租借，例如 14:00~15:00 被借，但 14:00 跟 15:00 還能再被選取一次
                for ($k = array_search($period[1], $timestamp); $k <= array_search($period[0], $timestamp); $k++) {
                    array_push($temp, $timestamp[$k]);
                    array_push($original, $timestamp[$k]);
                    array_push($user, $ss[$i]['name']);
                }
            }
            
            $renter[$i] = $temp;
        }

        // 判斷時段是否真正為租借中的時間
        $unique_arr = array_unique($original);
        $repeat_arr = array_diff_assoc($original, $unique_arr);

        // 將確定是被租借中的時段插入到 result
        for ($l = 0; $l < count(array_values($repeat_arr)); $l++ ) {
            array_push($result, array_values($repeat_arr)[$l]);
        }

        $data['period'] = $result;
        $data['user'] = $user;
        $data['original'] = $original;
        $data['renter'] = $renter;
        
        return $data;
    }

    // 取得使用者近期的租借
    public function getUserRental (Request $req)
    {
        $userIsExists = Member::where('token', $req->token);
        
        if ($userIsExists->count() > 0) {
            $result['login'] = $userIsExists->first(['username', 'name']);
            $result['rent'] = Rental::where('username', $result['login']['username'])->where('rentDate', '>=', (now()->timestamp)-86399)->orderBy('rentDate', 'asc')->get(['id', 'title', 'description', 'rentDate', 'room']);
            $result['status'] = true;
            return $result;
        }
        $result['status'] = false;
        return $result;
    }

    // 登入
    public function login (Request $req)
    {
        $url        = "https://itouch.cycu.edu.tw/active_system/login/login2.jsp";
        $ref_url    = "https://itouch.cycu.edu.tw/active_project/cycu2100h_06/acpm3/json/ss_loginUser.jsp";
        $userId     = $req->username;
        $password   = $req->password;
    
        $ch = curl_init();

        $cookie_jar = "./cookie.txt";
        //$cookie_jar = '/tmp/cookie.txt';

        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $ref_url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "UserNm=".$userId."&UserPasswd=".$password);
        curl_exec ($ch);

        curl_close ($ch);
        
        $ch2 = curl_init();

        curl_setopt($ch2, CURLOPT_URL, "https://itouch.cycu.edu.tw/active_project/cycu2100h_06/acpm3/json/ss_loginUser.jsp");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.117 Safari/537.36" );
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 0);

        curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_jar);
        
        $orders = curl_exec($ch2);
        curl_close($ch2);

        $userInfo = (array) json_decode($orders);

        // 判斷是否登入失敗
        if (count($userInfo) == 0)
        {
            $result['status'] = false;
            return json_encode($result);
        }

        // 取得姓名跟系級
        $name   = $userInfo['name'];
        $dept   = $userInfo['i_DEPT_NAME_C'];

        // preg_match_all("/\<div align=\"center\"\>(.*?)\<\/div\>/is", $orders, $arr);
        // preg_match_all("/\<font color=\"#990000\"\>(.*?)\<\/font\>/is", $arr[0][7], $nameArr);
        // preg_match_all("/\<font color=\"#990000\"\>(.*?)\<\/font\>/is", $arr[0][2], $deptArr);

        // // 取得系級
        // $dept = mb_substr(strip_tags($deptArr[0][0]), 0, 2, 'utf-8');
      
        // 判斷是否為不合格成員
        if (mb_substr($dept, 0, 4) != "資訊管理")
        {
            $result['status'] = false;
            return json_encode($result);
        }

        // 產生 Token
        $token = bin2hex(random_bytes(32));

        // 判斷該使用者是否已註冊過
        $userExists = Member::where('username', $userId);

        if ( $userExists->count() > 0 )
        {
            $userExists->update(['token' => $token]);
        }
        else
        {
            // 姓名
            //$name = strip_tags($nameArr[0][0]);
            Member::create([
                'username' => $userId,
                'name' => $name, 
                'token' => $token,
                'status' => 1
            ]);
        }

        // 回傳個人資料
        $result['name'] = $name;
        $result['username'] = $userId;
        $result['token'] = $token;
        $result['status'] = true;
    
        return json_encode($result);
    }

    // 取得該日所有租借紀錄
    public function getAllRental (Request $req)
    {
       return Rental::where('rentDate', $req->date)->where('course', 0)->get(['title', 'room', 'period']);
    }

    // 取消預約
    public function setRemoveUserRental (Request $req) {
        
        $existMember = Member::where('token', $req->token);

        if ($existMember->count() == 1)
        {
            $userInfo = $existMember->first();

            return Rental::where('username', $userInfo['username'])->where('id', $req->id)->where('rentDate', '>=', Carbon::today()->timestamp)->delete();
        }
    }
}
