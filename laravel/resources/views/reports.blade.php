@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('#'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-sm-6">
                <br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;"><i class="fas fa-chart-line"></i> Reports</h1>
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

            <div class="col-lg-6 d-flex justify-content-center">
                <div class="card p-3">
                    <div class="card-body" style="position: relative; height:60vh; width:40vw">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title" style="font-weight: bold; color: rgb(61, 63, 61); ">Chat Support Ratings</h3>
                        </div>
                        <canvas id="ratingsChart" style="display: block;"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 d-flex justify-content-center">
                <div class="card p-3">
                    <div class="card-body" style="position: relative; height:60vh; width:40vw">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title" style="font-weight: bold; color: rgb(61, 63, 61); ">Feedback</h3>
                        </div>
                        <canvas id="feedbackChart" style="display: block;"></canvas>
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

    fetch('/chart-data')
        .then(response => response.json())
        .then(data => {
            let labels = data.labels;
            let values = data.values;

            var ctx = document.getElementById('ratingsChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rating Distribution',
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Rating Distribution', 
                        fontSize: 20, 
                        fontColor: '#333', 
                        fontStyle: 'bold', 
                        fontFamily: 'Arial', 
                        padding: 20 
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

        fetch('/feedback-chart-data')
        .then(response => response.json())
        .then(data => {
            let labels = data.labels;
            let values = data.values;

            var ctx = document.getElementById('feedbackChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '# of feedbacks submitted by users',
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Feedback', 
                        fontSize: 20, 
                        fontColor: '#333', 
                        fontStyle: 'bold', 
                        fontFamily: 'Arial', 
                        padding: 20 
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

        fetch('/chart-data')
            .then(response => response.json())
            .then(data => {

                let labels = data.labels;
                let values = data.values;

                var ctx = document.getElementById('feedbackChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: '# of feedbacks submitted by users',
                            data: values,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            });


        

    </script>
@endsection
