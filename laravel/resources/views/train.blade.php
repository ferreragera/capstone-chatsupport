@extends('layouts.admin')


@section('main-content-header')
<div class="content-header" style="background-image: url('#'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-4">
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
    <div class="container-fluid px-3">
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
                    <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
                        <form method="post" action="s">
                            <h3 class="card-title">Train Chat Support</h3>
                        </form>
                        <div id="training-console" class="text-left" style="height: 60vh;">
                            
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
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalWithBootstrapButtons.fire({
                        title: "Training!",
                        text: "Chat support will be updated to the latest dataset.",
                        icon: "success"
                        });
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error"
                        });
                    }
                });
        }

        

    </script>
@endsection
