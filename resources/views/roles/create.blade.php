@extends('layouts.master')

@section('css')
    <!-- Internal Font Awesome -->
    <link href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- Internal treeview -->
    <link href="{{ asset('assets/plugins/treeview/treeview-rtl.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
    اضافة الصلاحيات - مورا سوفت للادارة القانونية
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الصلاحيات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة نوع مستخدم</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mg-b-20">
                    <div class="card-body">
                        <div class="main-content-label mg-b-5">
                            <div class="col-xs-7 col-sm-7 col-md-7">
                                <div class="form-group">
                                    <p>اسم الصلاحية :</p>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="أدخل اسم الصلاحية">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- col -->
                            <div class="col-lg-4">
                                <ul id="treeview1">
                                    <li><a href="#">الصلاحيات</a>
                                        <ul>
                                            @foreach ($permission as $value)
                                                <label style="font-size: 16px;" class="px-2">
                                                    <input type="checkbox" name="permission[]" value="{{ $value->id }}"
                                                        class="name">
                                                    {{ $value->name }}
                                                </label>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <!-- /col -->
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-main-primary">تاكيد</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row closed -->
    </form>
@endsection

@section('js')
    <!-- Internal Treeview js -->
    <script src="{{ asset('assets/plugins/treeview/treeview.js') }}"></script>
@endsection
