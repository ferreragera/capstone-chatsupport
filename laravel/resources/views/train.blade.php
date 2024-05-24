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

        function updateTrainingConsole(text) {
            const trainingConsole = document.getElementById("training-console");
            trainingConsole.innerHTML += text;
        }

        function fetchUpdates() {
            fetch('', {
                method: 'POST'
            }).then(function (response) {
                if (response.ok) {
                    return response.text(); 
                } else {
                    console.error('Error starting training:', response.statusText);
                }
            })
            .then(function (data) {
                updateTrainingConsole(data);
                setTimeout(fetchUpdates, 1000);
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
                fetch('/train', {
                    method: 'POST'
                }).then(function (response) {
                    if (response.ok) {
                        return response.text();
                    } else {
                        console.error('Error starting training:', response.statusText);
                    }
                }).then(function (data) {
                    updateTrainingConsole(data);
                    setTimeout(fetchUpdates, 1000);
                });
                
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
