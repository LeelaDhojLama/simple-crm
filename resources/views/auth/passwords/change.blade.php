@extends('layouts.sidebar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Change Password</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('change.password') }}">
                        @csrf

                        @foreach ($errors->all() as $error)
                        <p class="text-danger">{{ $error }}</p>
                        @endforeach

                        <div class="row" style="margin-top: 8px;">
                            <div class="col-sm-12">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">@</div>
                                    </div>
                                    <input id="password" placeholder="Current Password" type="password" class="form-control" name="current_password" autocomplete="current-password">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 8px;">
                            <div class="col-sm-12">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">@</div>
                                    </div>
                                    <input id="new_password" placeholder="New Password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 8px;">
                            <div class="col-sm-12">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">@</div>
                                    </div>
                                    <input id="new_confirm_password" placeholder="Confirm New Password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection