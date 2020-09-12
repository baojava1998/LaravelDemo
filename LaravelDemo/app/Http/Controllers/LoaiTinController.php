<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaiTin;
use App\TheLoai;

class LoaiTinController extends Controller
{
    //
    public function getDanhSach()
    {
        $loaitin = LoaiTin::all();
        return view ('admin.loaitin.danhsach',['loaitin'=>$loaitin]);
    }
    public function getThem()
    {
        $theloai = TheLoai::all();
        return view ('admin.loaitin.them',['theloai'=>$theloai]);
    }
    public function postThem(Request $request)
    {
        $this->validate($request,
        [
            'Ten'=>'required|unique:LoaiTin,Ten|min:3|max:100',
            'idTheLoai'=>'required'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên loại tin',//required là có hay k
            'Ten.min'=> 'Tên độ dài phải có từ 3 đến 100 ký tự',
            'Ten.max'=> 'Tên độ dài không được quá 100 ký tự',
            'Ten.unique'=>'Tên loại tin đã tồn tại', // unique kiểm tra có trùng hay k
            'TheLoai'=>'Bạn chưa chọn tên thể loại'
        ]);

        $loaitin = new LoaiTin;
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten) ;
        $loaitin->idTheLoai = $request->idTheLoai;
        $loaitin->save();
        return redirect('admin/loaitin/them')->with('thongbao','Thêm thành công');
    }
    public function getSua($id)
    {
        $theloai = TheLoai::all();
        //$theloai = TheLoai::find($id);
        $loaitin = LoaiTin::find($id);
        return view ('admin.loaitin.sua',['loaitin'=>$loaitin,'theloai'=>$theloai]);
    }
    public function postSua(Request $request,$id)
    {
        $loaitin = LoaiTin::find($id);
        $this->validate($request,
        [
            'Ten'=>'required|unique:LoaiTin,Ten|min:3|max:100'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên thể loại',//required là có hay k
            'Ten.unique'=>'Tên thể loại đã tồn tại', // unique kiểm tra có trùng hay k
            'Ten.min'=> 'Tên độ dài phải có từ 3 đến 100 ký tự',
            'Ten.max'=> 'Tên độ dài không được quá 100 ký tự'
            
        ]);
        $loaitin->Ten = $request->Ten;
        $loaitin->TenKhongDau = changeTitle($request->Ten) ;
        $loaitin->idTheLoai = $request->TheLoai;
        $loaitin->save();
        
        return redirect('admin/loaitin/sua/'.$id)->with('thongbao','Sửa thành công');
    }
    public function getXoa($id)
    {
        $loaitin = LoaiTin::find($id);
        $loaitin->delete();
        return redirect('admin/loaitin/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
