@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">New Module</h1>
        </div>
        <div class="row">
            <div class="col-6 mb-4">
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Module details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('modules.store') }}" method="post">
                            @csrf
                            <fieldset class="">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="module_code" id="moduleCode"
                                                value="{{ $oldData ? $oldData['module_code'] : '' }}"
                                                placeholder="Module Code" required>
                                            <label for="moduleCode" class="form-label">Module Code</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="module_name" id="moduleName"
                                                value="{{ $oldData ? $oldData['module_name'] : '' }}"
                                                placeholder="Module Name" required>
                                            <label for="moduleName" class="form-label">Module Name</label>
                                        </div>
                                    </div>
                                </div>
                                @if ($hasError)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-danger" role="alert">
                                                Module with the same code exists or unknown error occurred
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-dark btn-lg" type="submit">Create</button>
                                        <a href="{{ route('modules.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
