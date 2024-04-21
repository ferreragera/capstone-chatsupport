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
        <div>
            {{-- <h2 class="">Automated Response System Dataset</h2> --}}
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
                <h5 class="modal-title" id="staticBackdropLabel">Add Intent</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body px-5 py-5">

                    <form action="process_add_intent.php" method="POST">
                        <div class="mb-3">
                            <label for="tag" class="form-label">Tag:</label>
                            <input type="text" class="form-control" id="tag" name="tag" required>
                        </div>

                        <div class="mb-3">
                            <label for="patterns" class="form-label">Patterns:</label>
                            <div id="patternsContainer">
                            <textarea class="form-control" name="patterns[]" rows="2" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="">Add Pattern</button>
                            <button type="button" class="btn btn-danger mt-2" onclick="">Remove Pattern</button>

                        </div>

                        <div class="mb-3">
                            <label for="responses" class="form-label">Responses:</label>
                            <div id="responsesContainer">
                                <textarea class="form-control" name="responses[]" rows="3" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="">Add Response</button>
                            <button type="button" class="btn btn-danger mt-2" onclick="">Remove Response</button>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-success" type="submit">Add Intent</button>
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
                        <!-- Your edit form content here -->
                        <form action="process_edit_intent.php" method="POST">
                            <!-- Form fields for editing -->
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
            $('#intentsTable').DataTable({
                "pageLength": 5, // Show 5 rows per page initially
                "lengthMenu": [5, 10, 25, 50], // Set the available page lengths
                "autoWidth": false // Optional: Disable auto width adjustment
            });
            // Add click event listener to archive buttons
            $('.archive-btn').click(function() {
                var tag = $(this).data('tag');
                // Display confirmation dialog
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
        });
    </script>
@endsection


{{-- <div class="col-lg-4">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Automated Response System Dataset</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">

                            
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-lg-4 col-12">
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
            </div> --}}
            

            {{-- rating
            <div class="d-flex justify-content-between align-items-center">
                <div class="ratings">
                    <i class="fa fa-star rating-color"></i>
                    <i class="fa fa-star rating-color"></i>
                    <i class="fa fa-star rating-color"></i>
                    <i class="fa fa-star rating-color"></i>
                    <i class="fa fa-star"></i>
                </div>
            </div> --}}
