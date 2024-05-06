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
                    {{-- <button class="btn btn-sm bg-gradient-success mr-1" data-toggle="modal" data-target="#createIntent"><i class="fas fa-plus mr-1"></i>Create Intent</button> --}}
                </div>
            </div>
        </div>
        
        <!-- end of create & edit modal -->

        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
                        <form method="post" action="s">
                            <h3 class="card-title">Train Chat Support</h3>
                            <button id="trainButton" class="btn btn-success btn-md mb-4 d-flex float-right" type="submit" name="train_chatbot">
                                Start Training
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

    </div>
</div>

@endsection


@section('script')
    <script>


    </script>
@endsection
