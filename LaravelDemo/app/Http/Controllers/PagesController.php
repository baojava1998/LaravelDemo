<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\LoaiTin;
use App\Slide;
use App\TheLoai;
use App\TinTuc;
use App\Comment;
use App\User;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;



class PagesController extends Controller
{
    //

    function __construct()
    {
        $theloai = TheLoai::all();
        $slide = Slide::all();
        view()->share('theloai',$theloai);
        view()->share('slide',$slide);

        // if(Auth::check())
        // {
        // view()->share('nguoidung',Auth::user());
        // }
    }

    function trangchu()
    {
        return view('pages.trangchu');
    }

    function lienhe()
    {
        return view('pages.lienhe');
    }
    function loaitin($id)
    {
        $loaitin = Loaitin::find($id);
        $tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
        return view('pages.loaitin',['loaitin'=>$loaitin,'tintuc'=>$tintuc]);
    }
    function tintuc($id)
    {
        $tintuc = TinTuc::find($id);
        $tinnoibat = TinTuc::where('NoiBat','1')->take(4)->get();
        $tinlienquan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
        return view('pages.tintuc',['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }
    function getDangnhap ()
    {
        return view('pages.dangnhap');
    }
    function postDangnhap(Request $request)
    {
        $this->validate($request,
        [
            
            'email'=>'required', 
            'password'=>'required|min:3|max:32',         
        ],
        [
            'email.required'=>'Bạn chưa nhập đúng định dạng email',
            'password.required'=>'bạn chưa nhập password',
            'password.min'=>'Password phải có it nhất 3 ký tự',
            'password.max'=>'Password không được quá 32 ký tự',
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return redirect('trangchu');
        }
        else
        {
            return redirect('dangnhap')->with('thongbao','đăng nhập thất bại');
        }
    }
    public function getDangxuat()
    {
        Auth::logout();
        return redirect('trangchu');
    }
    public function getNguoidung()
    {
        return view('pages.nguoidung');
    }
    public function postNguoidung(Request $request)
    {
        $this->validate($request,
        [
            'name'=>'required|min:3',         
        ],
        [
            'name.required'=>'Bạn chưa nhập tên người dùng',//required là có hay k
            'name.min'=>'Tên người dùng phải có it nhất 3 ký tự',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        
        if($request->changePassword == "on")  //nếu check là on
        {
            $this->validate($request,
        [ 
            'password'=>'required|min:3|max:32', 
            'passwordAgain'=>'required|same:password'          
        ],
        [
            'password.required'=>'bạn chưa nhập password',
            'password.min'=>'Password phải có it nhất 3 ký tự',
            'password.max'=>'Password không được quá 32 ký tự',
            'passwordAgain.same'=>'Mật khẩu không khớp',
            'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu'
        ]);
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect('nguoidung')->with('thongbao','Sửa thành công');
    }
    public function getDangky()
    {
        return view('pages.dangky');
    }
    public function postDangky(Request $request)
    {
        $this->validate($request,
        [
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email', 
            'password'=>'required|min:3|max:32', 
            'passwordAgain'=>'required|same:password'          
        ],
        [
            'name.required'=>'Bạn chưa nhập tên người dùng',//required là có hay k
            'name.min'=>'Tên người dùng phải có it nhất 3 ký tự',
            'email.required'=>'Bạn chưa nhập đúng định dạng email',
            'email.unique'=>'Email đã tồn tại',
            'password.required'=>'bạn chưa nhập password',
            'password.min'=>'Password phải có it nhất 3 ký tự',
            'password.max'=>'Password không được quá 32 ký tự',
            'passwordAgain.same'=>'Mật khẩu không khớp',
            'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->quyen = 0;
        $user->save();
        return redirect('dangky')->with('thongbao','Chúc mừng bạn đã đăng ký thành công');
    }
    
    function timkiem(Request $request)
    {
        $tukhoa = $request->tukhoa;
        $tintuc = TinTuc::where('TieuDe','like',"%$tukhoa%")->orWhere('TomTat','like',"%$tukhoa%")->orWhere('NoiDung','like',"%$tukhoa%")->paginate(5)->appends(['tukhoa' => $tukhoa]);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
}