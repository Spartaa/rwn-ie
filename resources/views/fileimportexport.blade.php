@extends('layouts.app')

@section('content')
    <div class="container mt-5 text-center">
        <h2 class="mb-4">
            Laravel 8 Import and Export Customers CSV & Excel to Database
        </h2>

        <form action="{{ route('customer-file-import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                <div class="custom-file text-left">
                    <input type="file" name="file" class="form-control-file" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <button class="btn btn-primary">Import data</button>
            <a class="btn btn-success" href="{{ route('file-export-customer') }}">Export data</a>
        </form>
    </div>

@endsection
