@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-report.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-sm-6">
                <br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;"><i class="fas fa-file-alt"></i> Reports</h1>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('main-content')
<div class="content">
    <div class="container-fluid px-3">
        <div class="">
            <div class="d-flex justify-content-end">
                <div class="col-sm-1 d-block mt-3 rounded text-lg">
                </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
                        <!-- Adjust the width and height attributes here -->
                        <canvas id="myChart" style="display: block;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
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
        const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
        }]
        },
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        }
    });

    </script>
@endsection
