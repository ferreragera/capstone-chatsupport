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
                            <button type="button" class="btn btn-primary mt-2" onclick="addPattern()"><i class="fas fa-plus mr-1"></i>Add</button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removePattern()"><i class="fas fa-trash mr-1"></i>Remove</button>
                        </div>
                    
                        <div class="mb-3">
                            <label for="createResponses" class="form-label">Responses:</label>
                            <div id="responsesContainer">
                                <textarea class="form-control" id="createResponses" name="createResponses[]" rows="3" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="addResponse()"><i class="fas fa-plus mr-1"></i>Add</button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removeResponse()"><i class="fas fa-trash mr-1"></i>Remove</button>
                        </div>
                    
                        <div class="text-center">
                            <button class="btn btn-success mt-3" type="submit">Add New Intent</button>
                        </div>
                    </form>
                    
                </div>
            </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="editModalLabel">Edit Intent</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-5 py-5">
                            <form id="">
                                <!-- Use id instead of tag for editing -->
                                <div class="form-group mb-3">
                                    <label for="newTagValue">Tag:</label>
                                       <div id="newTagValueContainer">
                                            <input type="text" name="newTagValue" class="form-control mb-2" value="">
                                       </div>
                                </div>
            
                                <div class="form-group mb-3">
                                 <label for="patternsToEdit">Patterns:</label>
                                    <div id="patternsContainer">
                                        <input type="text" name="patternsToEdit[]" class="form-control mb-2" value="">
                                        <button type="button" class="btn btn-primary" onclick="">Add Pattern</button>
                                        <button type="button" class="btn btn-danger" onclick="">Remove Pattern</button>
                                    </div>
                                </div>
            
                                <div class="form-group mb-3">
                                    <label for="responsesToEdit">Responses:</label>
                                    <div id="responsesContainer">
                                        <input type="text" name="responsesToEdit[]" class="form-control mb-2" value="">
                                        <button type="button" class="btn btn-primary" onclick="">Add Response</button>
                                        <button type="button" class="btn btn-danger" onclick="">Remove Response</button>
                                    </div>
                                </div>
            
                                <div class="text-center">
                                    <button class="btn btn-success" type="submit">Edit Intent</button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
                        <div class="position-relative mb-4">
                            <table id="intentsTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Tags</th>
                                        <th scope="col">Patterns</th>
                                        <th scope="col">Responses</th>
                                        <th scope="col" class="actions-column" width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
                                    $data = json_decode($json_data, true);
                                    $paginated_intents = $data['intents'];

                                    foreach ($paginated_intents as $intent) {
                                        echo "<tr>";
                                        echo "<td>" . $intent['tag'] . "</td>";
                                        echo "<td>" . implode(', ', $intent['patterns']) . "</td>";
                                        echo "<td>" . implode(', ', $intent['responses']) . "</td>";
                                        echo "<td>
                                            <button class='btn btn-primary btn-sm me-2' data-toggle='modal' data-target='#editModal'><i class='fas fa-edit'></i></button>
                                            <button class='btn btn-success btn-sm text-light archive-btn' data-tag='" . $intent['tag'] . "'><i class='fas fa-archive'></i></button>
                                            </td>";
                                        echo "</tr>";
                                    }
                                    ?>
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
            var table = $('#intentsTable').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "autoWidth": false,
                dom: 'lBfrtip', 
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            $('.archive-btn').click(function() {
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

            // SweetAlert2 for intent added successfully
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
                title: "At least one pattern is required.",
                icon: "warning"
                });
            }
        }

        // End of Add Intent Modal

        

        

        


    </script>
@endsection

