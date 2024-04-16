@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-wifi.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <br><br><br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #838383;"><i class="fas fa-book"></i> Wifi Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb px-3 elevation-1 bg-white float-sm-right">
                    <li class="breadcrumb-item"><a href="?view=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Integrated Library System</li>
                    <li class="breadcrumb-item active">Wifi Logs</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('main-content')
<div class="content">
    <div class="container-fluid">
        <div class="d-block mt-3 rounded text-lg">
            <button class="btn btn-sm bg-gradient-success mr-1" data-toggle="modal" data-target="#staticBackdrop"><i class="fas fa-plus mr-1"></i>Create</button>
        </div>
        <div class="modal fade" id="staticBackdrop" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body px-5 py-5">
                        <h5 class="floatingInput">Location:</h5>
                        <select class="form-control form-control-lg border-dark mb-4" id="location_drp" aria-label="" >
                            <option value="" disabled selected hidden>Select Floor</option>
                            <option value="1st Floor">1st Floor</option>
                            <option value="2nd Floor">2nd Floor</option>
                            <option value="3rd Floor">3rd Floor</option>
                            <option value="4th Floor">4th Floor</option>
                        </select>
                    
                        <form id="formData">  
                            @csrf
                            <div class="has-float-label mb-4">
                                <h5 class="floatingInput">Card Number:</h5>
                                <input type="text" maxlength="9" class="form-control form-control-lg border-dark" id="floatingInput" placeholder="20xxxxxxx" required>
                            </div>
                            <button id="submitForm" class="btn btn-success btn-lg d-flex justify-content-center m-auto w-50" type="submit">Submit</button>
                        </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Summary of Wifi Logs Statistics</h3>
                            <a href="javascript:void(0);">View Report</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <canvas id="myChart" height="300" style="display: block; width: 764px; height: 300px;" width="764"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recently Added Wifi Logs</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="javascript:void(0)" class="uppercase">View Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

