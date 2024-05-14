@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-gray.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-5">
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
    <div class="container-fluid px-5">
        <div class="">
            <div class="d-flex justify-content-end">
                <div class="col-sm-1 d-block mt-3 rounded text-lg">
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="queriesModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Unanswered Query</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body px-5 py-5">
                    <form action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="addTag" class="form-label">Tag:</label>
                            <input type="text" class="form-control" id="addTag" name="addTag" required>
                        </div>

                        <div class="mb-3">
                            <label for="addPatterns" class="form-label">Patterns:</label>
                            <div id="patternsContainer">
                                <textarea class="form-control" id="addPatterns" name="addPatterns[]" rows="2" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="addPattern1()"><i class="fas fa-plus mr-1"></i></button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removePattern1()"><i class="fas fa-trash mr-1"></i></button>
                        </div>

                        <div class="mb-3">
                            <label for="addResponses" class="form-label">Responses:</label>
                            <div id="responsesContainer">
                                <textarea class="form-control" id="addResponses" name="addResponses[]" rows="3" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="addResponse1()"><i class="fas fa-plus mr-1"></i></button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removeResponse1()"><i class="fas fa-trash mr-1"></i></button>
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" type="submit">Add New Intent</button>
                </div>
            </form>
            </div>
            </div>
        </div>


        <hr>
        <div class="row">

            <div class="col-lg-6 d-flex justify-content-center">
                <div class="card p-3">
                    <div class="card-body" style="position: relative; height:65vh; width:35vw">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title" style="font-weight: bold; color: rgb(61, 63, 61); ">Chat Support Ratings</h3>
                        </div>
                        <canvas id="ratingsChart" width="400" height="200"></canvas>

                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 d-flex justify-content-center">
                <div class="card p-3">
                    <div class="card-body" style="position: relative; height:65vh; width:35vw">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title mb-4" style="font-weight: bold; color: rgb(61, 63, 61); ">Unanswered Queries</h3>
                        </div>
                        <table class="table hover" id="queriesTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" style="width:70%">Queries</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedback as $value)
                                    <tr>
                                        <th>{{ $value->id }}</th>
                                        <td>{{ $value->feedback }}</td>
                                        <td>
                                            <button class="btn btn-primary text-light add-btn">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                            <button class="btn btn-danger text-light del-btn">
                                                <i class="fas fa-trash"></i>
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

document.addEventListener('DOMContentLoaded', function() {
    fetch('/chart-data')
        .then(response => response.json())
        .then(data => {
            let labels = data.labels;
            let values = data.values;
            console.log(labels);
            
            var ctx = document.getElementById('ratingsChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Ratings',
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
                        text: 'Monthly Ratings Distribution',
                        fontSize: 20,
                        fontColor: '#333',
                        fontStyle: 'bold',
                        fontFamily: 'Arial',
                        padding: 20
                    },
                    legend: {
                        display: false 
                    },
                    responsive: true,
                    maintainAspectRatio: false,
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
});





        $(document).ready(function() {
            var editModal = $('#queriesModal');
            var table = $('#queriesTable').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 15, 25, 50]
            });

            $('#queriesTable').on('click', '.add-btn', function() {

                $('#queriesModal').modal('show');
            });

            $('#queriesTable').on('click', '.del-btn', function() {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                        });
                    }
                });
            });
        });

    // Add Intent Modal
    function addPattern1() {
        var patternsContainer = document.getElementById('patternsContainer');
        var textarea = document.createElement('textarea');
        textarea.className = 'form-control mt-2';
        textarea.name = 'addPatterns[]';
        textarea.rows = 2;
        textarea.required = true;
        patternsContainer.appendChild(textarea);
    }

    function addResponse1() {
        var responsesContainer = document.getElementById('responsesContainer');
        var textarea = document.createElement('textarea');
        textarea.className = 'form-control mt-2';
        textarea.name = 'addResponses[]';
        textarea.rows = 3;
        textarea.required = true;
        responsesContainer.appendChild(textarea);
    }

    function removePattern1() {
    var patternsContainer = document.getElementById('patternsContainer');
    var patterns = patternsContainer.getElementsByTagName('textarea');

        if (patterns.length > 1) {
            patterns[patterns.length - 1].remove();
        } else {
            Swal.fire({
            title: "?",
            title: "At least one pattern is required.",
            icon: "warning"
            });
        }
    }

    function removeResponse1() {
        var responsesContainer = document.getElementById('responsesContainer');
        var responses = responsesContainer.getElementsByTagName('textarea');

        if (responses.length > 1) {
            responses[responses.length - 1].remove();
        } else {
            Swal.fire({
            title: "?",
            title: "At least one response is required.",
            icon: "warning"
            });
        }
    }

    </script>
@endsection
