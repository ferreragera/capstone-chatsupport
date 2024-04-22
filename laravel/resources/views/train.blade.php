@extends('layouts.admin')

{{-- @section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-wifi.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <br><br><br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #838383;"><i class="fas fa-book"></i> Test</h1>
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

@endsection  --}}

@section('main-content')
<div class="content">
    <div class="container-fluid">
        <div>
            {{-- <h2 class="" style="">Automated Response System Dataset</h2> --}}
            
        </div>
        


        
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Feedback Logs</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">

                            <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th scope="col" width="50">#</th>
                                    <th scope="col">Tags</th>
                                    <th scope="col">Patterns</th>
                                    <th scope="col">Responses</th>
                                    <th scope="col" class="actions-column">Actions</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                  </tr>
                                  <tr>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                  </tr>
                                  <tr>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                  </tr>
                                </tbody>
                              </table>

                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

