@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-gray.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-5">
            <div class="col-sm-6">
                <br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;">
                    <i class="fas fa-chart-line"></i> Reports
                </h1>
            </div>
        </div>
    </div>
</div>
@endsection 

@section('main-content')
<div class="content">
    <div class="container-fluid px-5">
        <div class="d-flex justify-content-end">
            <div class="col-sm-1 d-block mt-3 rounded text-lg"></div>
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
                        <form action="{{ route('feedback.store') }}" method="POST">
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
                                <button type="button" class="btn btn-primary mt-2" onclick="addPattern1()">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger mt-2" onclick="removePattern1()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="mb-3">
                                <label for="addResponses" class="form-label">Responses:</label>
                                <div id="responsesContainer">
                                    <textarea class="form-control" id="addResponses" name="addResponses[]" rows="3" required></textarea>
                                </div>
                                <button type="button" class="btn btn-primary mt-2" onclick="addResponse1()">
                                    <i class="fas fa-plus mr-1"></i>
                                </button>
                                <button type="button" class="btn btn-danger mt-2" onclick="removeResponse1()">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button class="btn btn-success" type="submit">Add As New Feedback</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-lg-6 d-flex justify-content-center">
                <div class="card">
                    <div class="card-body" style="position: relative; height:25vh; width:40vw">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title" style="font-weight: bold; color: rgb(61, 63, 61);">Chat Support Ratings</h3>
                        </div>
                        <canvas id="ratingsChart" width="300" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-flex justify-content-center">
                <div class="card">
                    <div class="card-body" style="position: relative; max-height: 25vh; width:40vw">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title" style="font-weight: bold; color: rgb(61, 63, 61);">Weekly Summary of Inquiries</h3>
                        </div>
                        <canvas id="feedbackChart" width="950" height="230"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg d-flex justify-content-center">
                <div class="card">
                    <div class="card-body" style="position: relative; max-height: 45vh; width:80vw">
                        <table class="table table-hover" id="queriesTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Queries</th>
                                    <th scope="col" width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedback as $value)
                                    <tr>
                                        <th>{{ $value->id }}</th>
                                        <th>{{ $value->created_at }}</th>
                                        <td>{{ $value->feedback }}</td>
                                        <td>
                                            <button class="btn btn-primary text-light add-btn" data-feedback="{{ $value->feedback }}">
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

                var ctx = document.getElementById('ratingsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Number of Ratings',
                            data: values,
                            backgroundColor: [
                                'rgba(242, 242, 235, 1)',
                                'rgba(210, 220, 230, 1)',
                                'rgba(156, 171, 180, 1)',
                                'rgba(171, 100, 75, 1)',
                                'rgba(114, 56, 61, 1)'
                            ],
                            borderColor: [
                                'rgba(242, 242, 235, 1)',
                                'rgba(210, 220, 230, 1)',
                                'rgba(156, 171, 180, 1)',
                                'rgba(171, 100, 75, 1)',
                                'rgba(114, 56, 61, 1)'
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
                            fontFamily: 'Arial'
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

        fetch('/feedback-data')
            .then(response => response.json())
            .then(data => {
                const labels = data.labels;
                const values = data.values;

                new Chart(document.getElementById('feedbackChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Weekly Feedback Count',
                            data: values,
                            borderColor: 'rgba(64, 27, 27, 1)',
                            backgroundColor: 'rgba(64, 27, 27, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {}
                });
            })
            .catch(error => {
                console.error('Error fetching feedback data:', error);
            });
    });

    $(document).ready(function() {
        var editModal = $('#queriesModal');
        var table = $('#queriesTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5, 15, 25, 50],
            "autoWidth": false,
            "scrollY": "200px",
            "scrollCollapse": true
        });

        $('#queriesTable').on('click', '.add-btn', function() {
            var feedback = $(this).data('feedback');
            $('#addPatterns').val(feedback); // Set the feedback data into the textarea
            $('#queriesModal').modal('show');
        });

        $('#queriesTable').on('click', '.del-btn', function() {
            var row = $(this).closest('tr');
            var feedbackId = row.find('th:first').text(); 

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/feedback/' + feedbackId, 
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
                            remarks: '1' 
                        },
                        success: function(response) {
                            row.remove();

                            Swal.fire({
                                title: "Deleted!",
                                text: "The feedback has been removed.",
                                icon: "success"
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: "Error!",
                                text: "An error occurred while deleting the feedback.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    });

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
