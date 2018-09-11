<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ArrayObject;
use App\Rental;
use App\Member;

class RentController extends Controller
{
    public function setRental (Request $req)
    {
        $isToken = Member::where('username', $req->username)->where('name', $req->name)->where('token', $req->token)->count();

        if ($isToken != 1) {
            $result['status'] = false;
            return $result;
        }

        $period = implode(",", json_decode($req->period));
        Rental::create([
            'title' => $req->title,
            'phone' => $req->phone,
            'description' => $req->description,
            'name' => $req->name,
            'username' => $req->username,
            'room' => $req->room,
            'rentDate' => $req->rentDate,
            'period' => $period
        ]);

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
            $result['rent'] = Rental::where('username', $result['login']['username'])->where('rentDate', '>=', (now()->timestamp)-86399)->get(['title', 'description', 'rentDate', 'room']);
            $result['status'] = true;
            return $result;
        }
        $result['status'] = false;
        return $result;
    }

    // 登入
    public function login (Request $req)
    {
        $url        = "http://www.cross.cycu.edu.tw/index.do" ;
        $ref_url    = "http://www.cross.cycu.edu.tw/newss.do";
        $userId     = $req->username;
        $password   = $req->password;
    
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$userId."&password=".$password);
        curl_exec ($ch);

        curl_close ($ch);
        
        $ch2 = curl_init();

        curl_setopt($ch2, CURLOPT_URL, "http://www.cross.cycu.edu.tw/newss.do");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)" );
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 0);

        curl_setopt($ch2, CURLOPT_COOKIEFILE, '/tmp/cookie.txt');
        
        $orders = curl_exec($ch2);
        curl_close($ch2);

        $output = iconv("Big5", "UTF-8", $orders);

        preg_match_all("/\<span class=\"name\"\>(.*?)\<\/span\>/is", $output, $arr);

        // 判斷是否成功登入 iTouch
        if (empty($arr[0]) == 1)
        {
            $result['status'] = 0;
            return json_encode($result);
        }

        $userExists = Member::where('username', $req->username);
        $token = md5(uniqid(rand()));

        if ( $userExists->count() == 1 )
        {
            $userExists->update(['token' => $token]);
            $userInfo = $userExists->first();

            $name = $userInfo['name'];
            $token = $userInfo['token'];
        }
        else
        {
            $name = mb_substr(preg_replace('/\s+/', '', strip_tags($arr[0][0])), 0, -8, 'utf-8');

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
        $result['status'] = 1;
    
        return json_encode($result);
    }

    // 取得該日所有租借紀錄
    public function getAllRental (Request $req)
    {
       return Rental::where('rentDate', $req->date)->get(['title', 'room', 'period']);
    }
}
