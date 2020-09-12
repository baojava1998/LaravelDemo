<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoaiTin;
use App\Slide;
use App\TheLoai;
use App\TinTuc;
use App\Comment;
use Illuminate\Support\Str;

class TinTucController extends Controller
{
    //
    public function getDanhSach()
    {
        $tintuc = TinTuc::orderBy('id','DESC')->get();
        return view ('admin.tintuc.danhsach',['tintuc'=>$tintuc]);
    }
    public function getThem()
    {
        
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::all();
        return view ('admin.tintuc.them',['theloai'=>$theloai,'loaitin'=>$loaitin]);
    }

    public function postThem(Request $request)
    {
       

        
        $this->validate($request,
        [
            'LoaiTin'=>'required',
            'TieuDe'=>'required|min:3|unique:TinTuc,TieuDe',
            'TomTat'=>'required',
            'NoiDung'=>'required',
            'Hinh'=>'image|mimes:jpeg,png,jpg,gif,svg|max:10000',
        ],
        [
            'LoaiTin.required'=>'Bạn chọn loại tin',//required là có hay k
            'TieuDe.required'=> 'Bạn chưa nhập tiêu đề',
            'Tieude.min'=> 'Tiêu đề phải có it nhất 3 ký tự',
            'TieuDe.unique'=>'Tên tiêu đề đã tồn tại', // unique kiểm tra có trùng hay k
            'TomTat.required'=>"Bạn chưa nhập tóm tắt",
            'NoiDung.required'=>'Bạn chưa nhập nội dung',
            'Hinh.image'=>'không phải hình'
        ]);

        $tintuc = new TinTuc;
        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = changeTitle($request->TieuDe) ;
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->TomTat = $request->TomTat;
        $tintuc->NoiDung = $request->NoiDung;
        $tintuc->SoLuotXem = 0;
        $tintuc->NoiBat = $request->NoiBat;
        if($request->hasFile('Hinh'))
        {
             $file = $request->Hinh;
            //  $duoi = $file->getClientOriginalExtension();
            //  if($duoi != 'jpg' && $duoi != 'png' && $duoi !='jpeg')
            //  {
            //     return redirect('admin/tintuc/them')->with('thongbao','chỉ được chọn file có đuôi : png, jpg, jpeg');
            //  }
            
             $name = $file->getClientOriginalName();
             $hinh = Str::random(4)."_".$name;
             while(file_exists("upload/tintuc/".$hinh))
             {
                $hinh = Str::random(4)."_".$name;
             }
            $file->move("upload/tintuc",$hinh);
            $tintuc->Hinh = $hinh;
        }
        else
        {
            $tintuc->Hinh = "";
        }
        $tintuc->save();
        return redirect('admin/tintuc/them')->with('thongbao','Thêm tin tức thành công');
    }
    public function getSua($id)
    {
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::all();
        $tintuc = TinTuc::find($id);
        return view ('admin.tintuc.sua',['tintuc'=>$tintuc,'theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postSua(Request $request,$id)
    {
        $tintuc = TinTuc::find($id);
        $this->validate($request,
        [
            'LoaiTin'=>'required',
            'TieuDe'=>'required|min:3',
            'TomTat'=>'required',
            'NoiDung'=>'required',
            'Hinh'=>'image|mimes:jpeg,png,jpg,gif,svg|max:10000',
        ],
        [
            'LoaiTin.required'=>'Bạn chọn loại tin',//required là có hay k
            'TieuDe.required'=> 'Bạn chưa nhập tiêu đề',
            'Tieude.min'=> 'Tiêu đề phải có it nhất 3 ký tự',
            'TieuDe.unique'=>'Tên tiêu đề đã tồn tại', // unique kiểm tra có trùng hay k
            'TomTat.required'=>"Bạn chưa nhập tóm tắt",
            'NoiDung.required'=>'Bạn chưa nhập nội dung',
            'Hinh.image'=>'không phải hình'
        ]);
        
        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = changeTitle($request->TieuDe) ;
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->TomTat = $request->TomTat;
        $tintuc->NoiDung = $request->NoiDung;
        $tintuc->NoiBat = $request->NoiBat;
        if($request->hasFile('Hinh'))
        {
             $file = $request->Hinh;
            //  $duoi = $file->getClientOriginalExtension();
            //  if($duoi != 'jpg' && $duoi != 'png' && $duoi !='jpeg')
            //  {
            //     return redirect('admin/tintuc/them')->with('thongbao','chỉ được chọn file có đuôi : png, jpg, jpeg');
            //  }
            
             $name = $file->getClientOriginalName();
             $hinh = Str::random(4)."_".$name;
             while(file_exists("upload/tintuc/".$hinh))
             {
                $hinh = Str::random(4)."_".$name;
             }
            $file->move("upload/tintuc",$hinh);
            unlink("upload/tintuc/".$tintuc->Hinh);
            $tintuc->Hinh = $hinh;
        }
        
        $tintuc->save();
        
        return redirect('admin/tintuc/sua/'.$id)->with('thongbao','Sửa thành công');
    }
    public function getXoa($id)
    {
        $tintuc = TinTuc::find($id);
        $tintuc->delete();
        return redirect('admin/tintuc/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}