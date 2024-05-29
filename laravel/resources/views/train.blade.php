@extends('layouts.admin')

@section('main-content-header')
<div class="content-header" style="background-image: url('/images/bg-gray.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-5">
            <div class="col-sm-6">
                <br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;"><i class="fas fa-stream"></i> Train</h1>
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
                    <button class="btn btn-sm bg-gradient-success" id="trainButton" data-toggle="modal" data-target="#createIntent" type="submit" name="train_chatbot" onclick="warningFunction()"><i class="fas fa-plus mr-1"></i>Train Chat Support</button>
                </div>
            </div>
        </div>

        <!-- end of create & edit modal -->

        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="max-height: 80vh;">
                        <div class="div">
                            <h3 class="card-title" style="font-weight: bold; color: rgb(61, 63, 61);">Train Chat Support</h3>
                        </div>
                        <div id="training-console" class="" style="height: 60vh;">
                            <br>
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
    let isTraining = false;

    function updateTrainingConsole(text) {
        const trainingConsole = document.getElementById("training-console");
        trainingConsole.innerHTML += text;
    }

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

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("training-console").innerHTML = "";
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

                swalWithBootstrapButtons.fire({
                    title: "Training!",
                    text: "Chat support will be updated to the latest dataset.",
                    icon: "success"
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "The chat support will not be updated to the latest dataset.",
                    icon: "error"
                });
            }
        });
    }
</script>
@endsection
