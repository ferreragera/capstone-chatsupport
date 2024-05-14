@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-gray.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-5">
            <div class="col-sm-6">
                <br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;"><i class="fas fa-stream"></i> Archives</h1>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('main-content')
<div class="content">
    <div class="container-fluid px-5">
        <div class="">
            <div class="d-flex justify-content-end">
                <div class="col-sm-1 d-block mt-3 rounded text-lg">
                </div>
            </div>
        </div>

    

        <hr>
        <div class="row">

            <div class="col-lg-12 d-flex justify-content-center">
                <div class="card p-3">
                    <div class="card-body" style="position: relative; height:65vh; width:80vw">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title" style="font-weight: bold; color: rgb(61, 63, 61); ">Dataset Archives</h3>
                        </div>
                        <table class="table hover" id="archiveTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Tags</th>
                                    <th scope="col">Patterns</th>
                                    <th scope="col">Responses</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($archive as $value)
                                    <tr>
                                        <th>{{ $value->tag }}</th>
                                        <td>{{ $value->patterns }}</td>
                                        <td>{{ $value->responses }}</td>
                                        <td>
                                            <button class="btn btn-primary text-light back-btn">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            
        </div>


    </div>
</div>

@endsection

@section('script')
    @parent
    <script>

    $(document).ready(function() {
        var table = $('#archiveTable').DataTable({
            "lengthMenu": [10, 20, 50]
        });

        $('#archiveTable').on('click', '.back-btn', function() {
            Swal.fire({
                title: "Are you sure?",
                text: "This will return the data to the current dataset",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, add it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                    title: "Added!",
                    text: "Data has been added back to the dataset.",
                    icon: "success"
                    });
                }
            });
        });
    });

    </script>
@endsection
