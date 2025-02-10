@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Settings') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($settings->first())
                        <form method="POST" action="{{ route('settings.update', ['setting' => $settings->first()->id]) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Application Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $settings->first()->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Maximum Files per Sale</label>
                                <input type="number" class="form-control" name="maxFiles" min="1" value="{{ $settings->first()->maxFiles }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Settings</button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            No settings found
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection