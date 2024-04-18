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
<div class="content" style="height: 100%; overflow-y: auto;">
    <div class="container-fluid px-3">
        <div>
            {{-- <h2 class="" style="">Automated Response System Dataset</h2> --}}
            <div class="d-flex justify-content-end">
                <div class="col-sm-1 d-block mt-3 rounded text-lg">
                    <button class="btn btn-sm bg-gradient-success mr-1" data-toggle="modal" data-target="#createIntent"><i class="fas fa-plus mr-1"></i>Create Intent</button>
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
                            @php
                                $json_data = file_get_contents('C:/xampp/htdocs/capstone/python/intents.json');
                                $data = json_decode($json_data, true);
        
                                // Pagination
                                $total_intents = count($data['intents']);
                                $limit = 5; // Number of items per page
                                $total_pages = ceil($total_intents / $limit);
                                $page = isset($_GET['page']) ? max(1, min($_GET['page'], $total_pages)) : 1; // Current page
                                $offset = ($page - 1) * $limit; // Offset for data retrieval
                                $paginated_intents = array_slice($data['intents'], $offset, $limit);
                            @endphp
        
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Tags</th>
                                        <th scope="col">Patterns</th>
                                        <th scope="col">Responses</th>
                                        <th scope="col" class="actions-column" width="100">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paginated_intents as $intent)
                                    <tr>
                                        <td>{{ $intent['tag'] }}</td>
                                        <td>{{ implode(', ', $intent['patterns']) }}</td>
                                        <td>{{ implode(', ', $intent['responses']) }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm btn-block me-2" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                            <button class="btn btn-success btn-sm btn-block text-light" data-bs-toggle="modal" data-bs-target="#deleteModal">Archive</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
        
                            <!-- Pagination -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                                        <a class="page-link" href="?page={{ $page - 1 }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    @for ($i = 1; $i <= $total_pages; $i++)
                                    <li class="page-item {{ $i == $page ? 'active' : '' }}"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>
                                    @endfor
                                    <li class="page-item {{ $page == $total_pages ? 'disabled' : '' }}">
                                        <a class="page-link" href="?page={{ $page + 1 }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
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
