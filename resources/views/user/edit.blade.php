@extends('layouts.app')

@section('content-app')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3"><i class="fa fa-plus"></i> Edit User</h4>
                    @include('user._form')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
