<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rental;

class AdminController extends Controller
{
    public function index ()
    {
        return view('admin');
    }

    // 取得預約中的紀錄
    public function getAllRental ()
    {
        return Rental::where('rentDate', '>=', time())->orderBy('rentDate', 'asc')->get();
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
}
