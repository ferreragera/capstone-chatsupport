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
            <div class="d-flex justify-content-end">
                <div class="col-sm-1 d-block mt-3 rounded text-lg">
                    <button class="btn btn-sm bg-gradient-success mr-1" data-toggle="modal" data-target="#createIntent"><i class="fas fa-plus mr-1"></i>Create Intent</button>
                </div>
                <div class="col-sm-1 d-block mt-3 rounded text-lg">
                    <button class="btn btn-sm bg-gradient-success mr-1" data-toggle="modal" data-target="#trainModal"><i class="fas fa-plus mr-1"></i>Train</button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createIntent" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Intent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <button type="button" class="btn btn-primary mt-2" onclick="addPattern()">Add Pattern</button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removePattern()">Remove Pattern</button>

                        </div>

                        <div class="mb-3">
                            <label for="responses" class="form-label">Responses:</label>
                            <div id="responsesContainer">
                                <textarea class="form-control" name="responses[]" rows="3" required></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-2" onclick="addResponse()">Add Response</button>
                            <button type="button" class="btn btn-danger mt-2" onclick="removeResponse()">Remove Response</button>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-success" type="submit">Add Intent</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>


        <div class="modal fade" id="trainModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Train</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body text-center">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <button id="trainButton" class="btn btn-success btn-md mb-4" type="submit" name="train_chatbot">
                            Train Chatbot
                        </button>
                    </form>
                    <div id="training-console" class="text-left">
                        <?php
                        if (isset($_POST['train_chatbot'])) {
                            try {
                                // Execute the Python training script and capture output and errors
                                $output = shell_exec("python train.py 2>&1");
    
                                // Check if there was an error
                                if ($output === null) {
                                    throw new Exception("Command execution failed");
                                }
    
                                // Display the training progress or logs (including errors)
                                echo '<div class="container mt-4">';
                                echo '<div class="row justify-content-center">';
                                echo '<div class="col-md-10">';
                                echo '<pre>' . $output . '</pre>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            } catch (Exception $e) {
                                // Handle the exception (e.g., display an error message)
                                echo '<div class="container mt-4">';
                                echo '<div class="row justify-content-center">';
                                echo '<div class="col-md-10">';
                                echo '<div class="alert alert-danger" role="alert">';
                                echo 'An error occurred: ' . $e->getMessage();
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Automated Response System Dataset</h3>
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
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                    <td>@mdo</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">2</th>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                    <td>@mdo</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">3</th>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                    <td>@mdo</td>
                                  </tr>
                                </tbody>
                              </table>





                            
                        </div>
                    </div>
                </div>
            </div>
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
        </div>
    </div>
</div>

@endsection

