@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('#'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-sm-6">
                <br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;"><i class="fas fa-file-alt"></i> Datasets</h1>
            </div>
        </div>
    </div>
</div>

@endsection 

@section('main-content')
{{-- <style>
    .dataTables_wrapper .dt-buttons {
    margin-right: 10px; /* Adjust the value as needed */
}
</style> --}}
<div class="content">
    <div class="container-fluid px-3">
        <div class="">
            <div class="d-flex justify-content-end">
                <div class="col-sm-1 d-block mt-3 rounded text-lg">
                    <button class="btn btn-sm bg-gradient-success mr-1" data-toggle="modal" data-target="#createIntent"><i class="fas fa-plus mr-1"></i>Create Intent</button>
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
                            <label for="createTag" class="form-label">Tag:</label>
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
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="editTag" class="form-label">Tag:</label>
                                <input type="text" class="form-control" id="editTag" name="newTagValue">
                            </div>
                            <div class="form-group">
                                <label for="editPatterns" class="form-label">Patterns:</label>
                                <textarea class="form-control" id="editPatterns" name="patternsToEdit" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editResponses" class="form-label">Responses:</label>
                                <textarea class="form-control" id="editResponses" name="responsesToEdit" rows="3"></textarea>
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
                <div class="card">
                    <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
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
                                                <button class="btn btn-primary btn-sm text-light edit-btn" data-tag="{{ $intent['tag'] }}"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-success btn-sm text-light archive-btn" data-tag="{{ $intent['tag'] }}"><i class="fas fa-archive"></i></button>
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
        $(document).ready(function() {
            var editModal = $('#editModal');
            var table = $('#intentsTable').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "autoWidth": false,
                "scrollY": "300px",
                "scrollCollapse": true,
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<h4 style="font-size: 15px;">Copy Dataset</h4>',
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
                        // Perform the archiving action here
                        // For example, you can make an AJAX request to your server to archive the intent
                        // $.post('/archive-intent', { tag: tag }, function(response) {
                        //     if (response.success) {
                        //         // Show success message
                        //         Swal.fire('Archived!', 'The intent has been archived.', 'success');
                        //     } else {
                        //         // Show error message
                        //         Swal.fire('Error!', 'Failed to archive the intent.', 'error');
                        //     }
                        // });
                        Swal.fire('Archived!', 'The intent with tag: ' + tag + ' has been archived.', 'success');
                    }
                });
            });


            $('#intentsTable').on('click', '.edit-btn', function() {
                var rowData = table.row($(this).closest('tr')).data(); // Get row data
                editModal.modal('show');

                // Populate modal fields with row data
                $('#editTag').val(rowData[0]); // Assuming the first column is tag

                // Retrieve and set patterns data
                var patternsData = $(rowData[1]).find('td').map(function() {
                    return $(this).text().trim();
                }).get().join('\n');
                $('#editPatterns').val(patternsData);

                // Retrieve and set responses data
                var responsesData = $(rowData[2]).find('td').map(function() {
                    return $(this).text().trim();
                }).get().join('\n');
                $('#editResponses').val(responsesData);

                // Handle save button click inside the modal
                $('#saveChangesBtn').off('click').on('click', function() {
                    // Update the row data
                    rowData[0] = $('#editTag').val();
                    rowData[1] = '<div>' + $('#editPatterns').val().split('\n').map(function(pattern) {
                        return '<td>' + pattern + '</td>';
                    }).join('') + '</div>';
                    rowData[2] = '<div>' + $('#editResponses').val().split('\n').map(function(response) {
                        return '<td>' + response + '</td>';
                    }).join('') + '</div>';

                    // Update table with new data
                    table.row($(this).closest('tr')).data(rowData).draw(false);

                    // Hide the modal
                    editModal.modal('hide');

                    // Save changes to JSON (implement this function)
                    saveChangesToJSON();
                });
            });

            function saveChangesToJSON() {
            // Submit the form data using AJAX
                $.ajax({
                    url: "{{ route('editIntent') }}",
                    method: 'POST',
                    data: $('#editForm').serialize(), // Serialize form data
                    success: function(response) {
                        console.log('Form data saved successfully!');
                    },
                    error: function(error) {
                        console.error('Error saving form data:', error);
                    }
                });
            }


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












    </script>
@endsection

