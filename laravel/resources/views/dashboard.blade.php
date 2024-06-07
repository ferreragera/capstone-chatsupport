@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-gray.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-5">
            <div class="col-sm-6">
                <br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;"><i class="fas fa-file-alt"></i> Datasets</h1>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('main-content')
<style>
    /* Button Colors */
    .btn-default-color {
        color: #6b7381;
    }
    .btn-default-bg {
        background: #b3b7bb;
    }

    /* Toggle Sizes */
    .toggle-default-size {
        width: 2.5rem;  /* Increased width */
        height: 2.5rem; /* Increased height */
    }

    .toggle-default-label-width {
        width: 6rem;  /* Increased label width */
    }

    .toggle-default-font-size {
        font-size: 1rem;  /* Increased font size */
    }

    /* Mixin for Switch Colors */
    .toggle-color {
        color: #6b7381;
        background: #b3b7bb;
    }

    .toggle-color.active {
        background-color: #29b5a8;
    }

    /* Mixin for Default Switch Styles */
    .toggle-mixin {
        margin: 0 1rem;  /* Adjusted margin */
        padding: 0;
        position: relative;
        border: none;
        height: 2.5rem;  /* Adjusted height */
        width: 5rem;  /* Adjusted width */
        border-radius: 2.5rem;
        background: #b3b7bb; /* Default background color */
        transition: background-color .25s;
    }

    .toggle-mixin:focus,
    .toggle-mixin.focus {
        outline: none;
    }

    .toggle-mixin:before,
    .toggle-mixin:after {
        line-height: 2.5rem;  /* Adjusted line-height */
        width: 5rem;  /* Adjusted width */
        text-align: center;
        font-weight: 600;
        font-size: 1rem;  /* Adjusted font-size */
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity .25s;
    }

    .toggle-mixin:before {
        content: 'Off';
        left: -5rem;  /* Adjusted position */
    }

    .toggle-mixin:after {
        content: 'On';
        right: -5rem;  /* Adjusted position */
        opacity: .5;
    }

    .toggle-mixin > .handle {
        position: absolute;
        top: .375rem;
        left: .375rem;
        width: 1.75rem;  /* Adjusted width */
        height: 1.75rem;  /* Adjusted height */
        border-radius: 1.75rem;
        background: #fff;
        transition: left .25s;
    }

    .toggle-mixin.active > .handle {
        left: 2.875rem;  /* Adjusted left position */
        transition: left .25s;
    }

    .toggle-mixin.active {
        background-color: #29b5a8; /* Active background color */
    }

    .toggle-mixin.active:before {
        opacity: .5;
    }

    .toggle-mixin.active:after {
        opacity: 1;
    }

    .btn-toggle {
        margin: 0 1rem;  /* Adjusted margin */
        padding: 0;
        position: relative;
        border: none;
        height: 2.5rem;  /* Adjusted height */
        width: 5rem;  /* Adjusted width */
        border-radius: 2.5rem;
        background: #b3b7bb; /* Default background color */
        transition: background-color .25s;
    }

    .btn-toggle.active {
        background-color: #29b5a8; /* Active background color */
    }

    .btn-toggle .handle {
        position: absolute;
        top: .375rem;
        left: .375rem;
        width: 1.75rem;  /* Adjusted width */
        height: 1.75rem;  /* Adjusted height */
        border-radius: 1.75rem;
        background: #fff;
        transition: left .25s;
    }

    .btn-toggle.active .handle {
        left: 2.875rem;  /* Adjusted left position */
        transition: left .25s;
    }

    .btn-toggle:before,
    .btn-toggle:after {
        line-height: 2.5rem;  /* Adjusted line-height */
        width: 5rem;  /* Adjusted width */
        text-align: center;
        font-weight: 600;
        font-size: 1rem;  /* Adjusted font-size */
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity .25s;
    }

    .btn-toggle:before {
        content: 'Off';
        left: -4rem;  /* Adjusted position */
    }

    .btn-toggle:after {
        content: 'On';
        right: -4rem;  /* Adjusted position */
        opacity: .5;
    }

    .btn-toggle.active:before {
        opacity: .5;
    }

    .btn-toggle.active:after {
        opacity: 1;
    }
</style>


</style>
<div class="content">
    <div class="container-fluid px-5">
        <div class="">
            <div class="d-flex justify-content-between mt-4 rounded text-lg">
                <div id="refreshDiv" class="col-sm-3 ml-4">
                    <button type="button" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                        <div class="handle"></div>
                    </button>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <button class="btn btn-sm bg-gradient-success mr-1 py-2" data-toggle="modal" data-target="#createIntent">
                        <i class="fas fa-plus mr-2"></i>Create Intent
                    </button>
                    <button class="btn btn-sm bg-gradient-primary mr-1 py-2" id="trainButton" data-toggle="modal" data-target="#trainIntent" type="submit" name="train_chatbot" onclick="warningFunction()">
                        <i class="fas fa-cogs mr-2"></i>Train Chat Support
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createIntent" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="staticBackdropLabel">Create Intent</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body px-5 py-5">
                    <form action="{{ route('createIntent') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="createT ag" class="form-label">Tag:</label>
                            <input type="text" class="form-control" id="createTag" name="createTag" required>
                        </div>

                        <div class="mb-3">
                            <label for="createPatterns" class="form-label">Patterns:</label>
                            <div id="patternsContainer">
                                <textarea class="form-control" id="createPatterns" name="createPatterns[]" rows="2" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="addPattern()"><i class="fas fa-plus mr-1"></i></button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removePattern()"><i class="fas fa-trash mr-1"></i></button>
                        </div>

                        <div class="mb-3">
                            <label for="createResponses" class="form-label">Responses:</label>
                            <div id="responsesContainer">
                                <textarea class="form-control" id="createResponses" name="createResponses[]" rows="3" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="addResponse()"><i class="fas fa-plus mr-1"></i></button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removeResponse()"><i class="fas fa-trash mr-1"></i></button>
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

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="editModalLabel">Edit Intent</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="{{ route('editIntent') }}" method="POST"> 
                        @csrf
                        <div class="modal-body px-5 pt-3">
                            <div class="form-group">
                                <label for="editTag" class="form-label">Tag:</label>
                                <input type="text" class="form-control" id="editTag" name="newTagValue" readonly>
                            </div>
                            <div>
                                <label for="editPatterns" class="form-label">Patterns:</label>
                                    <div class="form-group" id="editpatternsContainer">
                                </div>
                                <button type="button" class="btn btn-primary" onclick="addEditPattern()"><i class="fas fa-plus mr-1"></i></button>
                                <button type="button" class="btn btn-danger" onclick="removeEditPattern()"><i class="fas fa-trash mr-1"></i></button>
                            </div>
                            <div>
                                <label for="editResponses" class="form-label">Responses:</label>
                                    <div class="form-group" id="editresponsesContainer">
                                </div>
                                <button type="button" class="btn btn-primary" onclick="addEditResponse()"><i class="fas fa-plus mr-1"></i></button>
                                <button type="button" class="btn btn-danger" onclick="removeEditResponse()"><i class="fas fa-trash mr-1"></i></button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="saveChangesBtn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        

        <!-- end of create & edit modal -->

        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-2">
                    <div class="card-body" style="height: 60vh; max-height: 80vh; overflow-y: auto; margin-top: 10px;">
                        <div class="position-relative mb-4">
                            <table id="intentsTable" class="table hover">
                                <thead>
                                    <tr>
                                        <th scope="col" width="200">Tags</th>
                                        <th scope="col">Patterns</th>
                                        <th scope="col">Responses</th>
                                        <th scope="col" class="actions-column" width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paginated_intents as $intent)
                                        <tr class="parent-row">
                                            <td>{{ $intent['tag'] }}</td>
                                            <td>
                                                <div class="details-toggle" id="togglePatterns{{ $loop->index }}">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                                <div id="patternsDetails{{ $loop->index }}" class="details-container" style="display: none;">
                                                    <table class="table">
                                                        <tbody>
                                                            @foreach ($intent['patterns'] as $pattern)
                                                            <tr>
                                                                <td>{{ $pattern }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="details-toggle" id="toggleResponses{{ $loop->index }}">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                                <div id="responsesDetails{{ $loop->index }}" class="details-container" style="display: none;">
                                                    <table class="table">
                                                        <tbody>
                                                            @foreach ($intent['responses'] as $response)
                                                            <tr>
                                                                <td>{{ $response }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm text-light edit-btn" data-tag="{{ $intent['tag'] }}" data-patterns="{{ json_encode($intent['patterns']) }}" data-responses="{{ json_encode($intent['responses']) }}"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-success btn-sm text-light archive-btn" data-tag="{{ $intent['tag'] }}" data-patterns="{{ json_encode($intent['patterns']) }}" data-responses="{{ json_encode($intent['responses']) }}"><i class="fas fa-archive"></i></button>
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
</div>

@endsection

@section('script')
    @parent
    <script>
        var intentsData = {!! json_encode($paginated_intents) !!};

        $(document).ready(function() {
            var editModal = $('#editModal');
            var table = $('#intentsTable').DataTable({
                "pageLength": 8,
                "lengthMenu": [8, 15, 25, 50],
                "autoWidth": false,
                "scrollY": "400px",
                "scrollCollapse": true,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<h4 class="" style="font-size: 15px;">Copy Dataset</h4>',
                    },
                    {
                        extend: 'csv',
                        text: '<h4 style="font-size: 15px;">CSV</h4>',
                    },
                    {
                        extend: 'pdf',
                        text: '<h4 style="font-size: 15px;">PDF</h4>',
                    },
                    {
                        extend: 'print',
                        text: '<h4 style="font-size: 15px;">Print</h4>',
                    }
                ]   
            });

            $('#intentsTable').on('click', 'tbody tr', function() {
                $(this).find('.details-container').slideToggle();
                $(this).find('.details-toggle i').toggleClass('fa-chevron-right fa-chevron-down');
            });

            $('#intentsTable').on('click', '.archive-btn', function() {
                var tag = $(this).data('tag');
                var patterns = $(this).data('patterns');
                var responses = $(this).data('responses');
                // console.log(tag,patterns,responses);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to archive the intent with tag: " + tag,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, archive it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        url: "{{ route('archiveIntent') }}", 
                        method: 'POST',
                        data: 
                            {
                                "_token": "{{ csrf_token() }}",
                                tag,
                                patterns,
                                responses
                            },
                        success: function(response) {
                            Swal.fire({
                                icon:"success",
                                title: "Data Archived Successfully!",
                                text: "The intent with tag: " + tag + " has been archived."
                            }).then(function (result){
                                if(result.isConfirmed){
                                    window.location = "{{ route('dashboard') }}";
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            alert('Failed to update intent: ' + error);
                        }
                    });
                    }
                });
            });

            $('#intentsTable').on('click', '.edit-btn', function() {
                var tag = $(this).data('tag');

                var intent = intentsData.find(function(intent) {
                    return intent.tag === tag;
                });

                if (intent) {
                    var patterns = intent.patterns;
                    var responses = intent.responses;

                    $('#editTag').val(tag); 

                    // console.log("Tag:", tag);
                    $('#editpatternsContainer').empty();
                    $('#editresponsesContainer').empty();

                    // console.log("Patterns:", patterns);
                    patterns.forEach(function(pattern) {
                        var textarea = $('<textarea class="form-control mt-2" rows="2">' + pattern + '</textarea>');
                        $('#editpatternsContainer').append(textarea);
                    });

                    // console.log("Responses:", responses);
                    responses.forEach(function(response) {
                        var textarea = $('<textarea class="form-control mt-2" rows="3">' + response + '</textarea>');
                        $('#editresponsesContainer').append(textarea);
                    });

                    $('#editModal').modal('show');
                }
            });


        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
            });
        @endif
    });

    // Add Intent Modal
    function addPattern() {
        var patternsContainer = document.getElementById('patternsContainer');
        var textarea = document.createElement('textarea');
        textarea.className = 'form-control mt-2';
        textarea.name = 'createPatterns[]';
        textarea.rows = 2;
        textarea.required = true;
        patternsContainer.appendChild(textarea);
    }

    function addResponse() {
        var responsesContainer = document.getElementById('responsesContainer');
        var textarea = document.createElement('textarea');
        textarea.className = 'form-control mt-2';
        textarea.name = 'createResponses[]';
        textarea.rows = 3;
        textarea.required = true;
        responsesContainer.appendChild(textarea);
    }

    function removePattern() {
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

    function removeResponse() {
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

    // End of Add Intent Modal

    // Edit Intent Modal

    function addEditPattern() {
        var editpatternsContainer = document.getElementById('editpatternsContainer');
        var textarea = document.createElement('textarea');
        textarea.className = 'form-control mt-2';
        textarea.name = 'patternsToEdit[]';
        textarea.rows = 2;
        textarea.required = true;
        editpatternsContainer.appendChild(textarea);
    }

    function addEditResponse() {
        var editresponsesContainer = document.getElementById('editresponsesContainer');
        var textarea = document.createElement('textarea');
        textarea.className = 'form-control mt-2';
        textarea.name = 'responsesToEdit[]';
        textarea.rows = 3;
        textarea.required = true;
        editresponsesContainer.appendChild(textarea);
    }

    function removeEditPattern() {
    var editpatternsContainer = document.getElementById('editpatternsContainer');
    var patterns = editpatternsContainer.getElementsByTagName('textarea');

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

    function removeEditResponse() {
        var editresponsesContainer = document.getElementById('editresponsesContainer');
        var responses = editresponsesContainer.getElementsByTagName('textarea');

        if (responses.length > 1) {
            responses[responses.length - 1].remove();
        } else {
            Swal.fire({
            title: "?",
            title: "At least one pattern is required.",
            icon: "warning"
            });
        }
    }

    // End of Edit Intent Modal

  
    // Handle form submission for editing intent
    $('#editForm').submit(function(event) {
        event.preventDefault(); 

        var tag = $('#editTag').val();
        var patterns = [];
        var responses = [];

        $('#editpatternsContainer textarea').each(function() {
            patterns.push($(this).val());
        });

        $('#editresponsesContainer textarea').each(function() {
            responses.push($(this).val());
        });

        $.ajax({
            url: "{{ route('editIntent') }}", 
            method: 'POST',
            data: 
                {
                    "_token": "{{ csrf_token() }}",
                    tag,
                    patterns,
                    responses
                },
            success: function(response) {
                Swal.fire({
                    title: "Data Updated Successfully!",
                    icon: "success"
                }).then(function (result){
                    if(result.isConfirmed){
                        window.location = "{{ route('dashboard') }}";
                    }
                });
            },
            error: function(xhr, status, error) {
                alert('Failed to update intent: ' + error);
            }
        });
    });



    // train button //

    let isTraining = false;

    // function updateTrainingConsole(text) {
    //     Swal.fire({
    //         title: text,
    //         icon: "success"
    //     });
    // }

    function fetchStatus() {
        fetch('http://10.10.100.147:5000/status', {
            method: 'GET'
        }).then(response => response.json())
        .then(data => {
            if (data.status === "training") {
                updateTrainingConsole("Training in progress...\n");
                setTimeout(fetchStatus, 1000);
            } else if (data.status === "completed") {
                updateTrainingConsole("Training completed.\n");
                isTraining = false;
            } else if (data.status.startsWith("error")) {
                updateTrainingConsole(`Training failed: ${data.status}\n`);
                isTraining = false;
            }
        });
    }

    

    function warningFunction() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        Swal.fire({
            title: "Train Chat Support?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {
                if (!isTraining) {
                    isTraining = true;
                    fetch('http://10.10.100.147:5000/train', {
                        method: 'POST'
                    }).then(response => {
                        if (response.ok) {
                            updateTrainingConsole("Training started...\n");
                            fetchStatus();
                        } else {
                            response.text().then(text => {
                                updateTrainingConsole(`Failed to start training: ${text}\n`);
                                isTraining = false;
                            });
                        }
                    });
                }

                Swal.fire({
                    title: "Training!",
                    text: "Chat support will be updated to the latest dataset. Please restart the chat support upon completion.",
                    icon: "success"
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "The chat support will no longer update to the latest dataset.",
                    icon: "error"
                });
            }
        });
    }

    // Power button 
    $(document).ready(function() {
    // Function to set the initial state of the toggle button
    function setButtonState(isPressed) {
        $('.btn-toggle').attr('aria-pressed', isPressed);
        if (isPressed) {
            // If the button is pressed, add the 'active' class
            $('.btn-toggle').addClass('active');
        } else {
            // If the button is not pressed, remove the 'active' class
            $('.btn-toggle').removeClass('active');
        }
    }
    
    // Check if the toggle state is stored in localStorage
    var isPressed = localStorage.getItem('toggleState') === 'true';
    
    // Set the initial state of the toggle button
    setButtonState(isPressed);
    
    $('.btn-toggle').click(function(event) {
        event.preventDefault();

        var $button = $(this);
        var isPressed = $button.attr('aria-pressed') === 'true';

        // Toggle the aria-pressed attribute
        $button.attr('aria-pressed', !isPressed);

        // Toggle the 'active' class based on the state
        $button.toggleClass('active');

        // Store the toggle state in localStorage
        localStorage.setItem('toggleState', !isPressed);

        // Enable or disable the "Train Chat Support" button based on the toggle state
        if (!isPressed) {
            disableTrainButton(); // Disable the button if the toggle button is turned off
        } else {
            enableTrainButton(); // Enable the button if the toggle button is turned on
        }

        // Perform other actions based on the toggle state (e.g., making AJAX requests)
        if (!isPressed) {
            // Make the AJAX request to start the app
            $.ajax({
                url: 'http://10.10.100.147:5001/start_app', // Change to the appropriate URL
                type: 'GET', 
                success: function(response) {
                    Swal.fire({
                        title: "Chat Support Turned On",
                        text: "app.py has been executed",
                        icon: "success"
                    }).then(function() {
                        location.reload();  // Refresh the page after the alert
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to execute app.py:', error);
                    Swal.fire({
                        title: "Error",
                        text: "Failed to execute app.py",
                        icon: "error"
                    }).then(function() {
                        location.reload();  // Refresh the page after the alert
                    });
                }
            });
        } else {
            $.ajax({
                url: 'http://10.10.100.147:5001/stop_app', // Change to the appropriate URL
                type: 'GET', 
                success: function(response) {
                    Swal.fire({
                        title: "Chat Support Turned Off",
                        icon: "info"
                    }).then(function() {
                        location.reload(); 
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to stop app.py:', error);
                    Swal.fire({
                        title: "Error",
                        text: "Failed to stop app.py",
                        icon: "error"
                    }).then(function() {
                        location.reload(); 
                    });
                }
            });
        }

        console.log("Button state toggled:", !isPressed);
    });

    function disableTrainButton() {
        $('#trainButton').prop('disabled', true); 
    }

    function enableTrainButton() {
        $('#trainButton').prop('disabled', false); 
    }

    if (isPressed) {
        enableTrainButton(); 
    } else {
        disableTrainButton(); 
    }
});





    </script>
@endsection

