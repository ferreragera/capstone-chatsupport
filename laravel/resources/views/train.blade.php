@extends('layouts.admin')


@section('main-content-header')
<div class="content-header" style="background-image: url('#'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container-fluid">
        <div class="row px-4">
            <div class="col-sm-6">
                <br><br>
                <h1 class="m-0" style="text-shadow: 4px 4px 6px #fdfdfd;"><i class="fas fa-file-alt"></i> Train</h1>
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


    </script>
@endsection
