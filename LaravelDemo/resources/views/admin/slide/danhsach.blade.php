@extends('admin.layout.index')
@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Slide
                        <small>Danh sách</small>
                    </h1>
                    @if (session('thongbao')){{-- kiểm tra xem
                        có thông báo k--}}
                        <div class="alert alert-success">
                            {{ session('thongbao') }}
                        </div>
                    @endif
                </div>
                <!-- /.col-lg-12 -->
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr align="center">
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Nội dungt</th>
                            <th>Hình</th>
                            <th>Link</th>
                            <th>Delete</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slide as $sd)


                            <tr class="odd gradeX" align="center">
                                <td>{{ $sd->id }}</td>
                                <td>{{ $sd->Ten }}</td>
                                <td>{{ $sd->NoiDung }}</td>
                                <td>
                                    <img width="400px" src="upload/slide/{{ $sd->Hinh }}" alt="">
                                </td>
                                <td>{{ $sd->link }}</td>
                                <td class="center"><i class="fa fa-trash-o  fa-fw"></i><a
                                        href="admin/slide/xoa/{{ $sd->id }}">
                                        Xóa</a></td>
                                <td class="center"><i class="fa fa-pencil fa-fw"></i> <a
                                        href="admin/slide/sua/{{ $sd->id }}">Sửa</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
@endsection
