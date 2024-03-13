@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Files Upload</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('image.store') }}" enctype="multipart/form-data"
                              aria-label="{{ __('Upload') }}">
                            @csrf
                            <input class="form-control" type="file" name="files[]" multiple/>

                            <div class="form-group row mt-2">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Upload') }}
                                    </button>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
