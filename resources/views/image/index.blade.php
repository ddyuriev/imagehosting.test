@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">Files</div>
                <div class="card-body">
                    <table id="images" class="table table-borderless table-hover">
                        <thead>
                        <tr>
                            <td>filename</td>
                            <td>uploaded_at</td>
                            <td>url</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        <form action="" method="GET" class="form-inline mb-3">
                            <label class="mr-2" for="sort_by">Sort:</label>
                            <select name="sort_by" id="sort_by" class="form-control mr-2 mb-5">
                                <option value="">--sort--</option>
                                <option value="name_asc" {{ Request::get('sort_by') == 'name_asc' ? 'selected' : '' }}>
                                    name (A-Z)
                                </option>
                                <option
                                    value="name_desc" {{ Request::get('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                    name (Z-A)
                                </option>
                                <option
                                    value="datetime_asc" {{ Request::get('sort_by') == 'datetime_asc' ? 'selected' : '' }}>
                                    upload time: ascending
                                </option>
                                <option
                                    value="datetime_desc" {{ Request::get('sort_by') == 'datetime_desc' ? 'selected' : '' }}>
                                    upload time: descending
                                </option>
                            </select>
                        </form>

                        @foreach($images as $image)
                            <tr>
                                <td>
                                    <a href="/storage/uploads/{{ $image['filename'] }}">
                                        {{ $image['filename']}}
                                    </a>
                                </td>
                                <td>{{$image['uploaded_at']}}</td>
                                <td>
                                    <img src="/storage/uploads/{{ $image['filename'] }}" alt="{{ $image['filename'] }}"
                                         width="100">
                                </td>
                                <td>
                                    <a class="nav-link download-arrow" filename="{{ $image['filename'] }}"
                                       href="{{ route('image.download', $image['filename']) }}">
                                        &#11123
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    {{ $images->onEachSide(1)->links() }}

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('sort_by').addEventListener('change', function () {
            this.form.submit();
        });

        let downloadElements = document.querySelectorAll('#images .download-arrow');

        for (const [key, downloadElement] of Object.entries(downloadElements)) {
            downloadElement.addEventListener('click', function (event) {
                event.preventDefault();
                fetch('/download/' + downloadElement.getAttribute('filename'), {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                    .then(response => {
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) {
                            return response.json().then(data => {
                                alert(data.error)
                            });
                        } else {
                            return response.blob().then(blob => {
                                let file = window.URL.createObjectURL(blob);
                                window.location.assign(file);
                            });
                        }
                    });
            })
        }
    </script>
@endsection
