@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">New Practice Class</h1>
        </div>
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Class details</h5>
                    </div>
                    <div class="card-body">
                        {{--<form>
                            <!-- 2 column grid layout with text inputs for the first and last names -->
                            <div class="row mb-4">
                                <div class="col">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" id="form7Example1" class="form-control" />
                                        <label class="form-label" for="form7Example1">First name</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div data-mdb-input-init class="form-outline">
                                        <input type="text" id="form7Example2" class="form-control" />
                                        <label class="form-label" for="form7Example2">Last name</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Text input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="form7Example3" class="form-control" />
                                <label class="form-label" for="form7Example3">Company name</label>
                            </div>

                            <!-- Text input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="form7Example4" class="form-control" />
                                <label class="form-label" for="form7Example4">Address</label>
                            </div>

                            <!-- Email input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="email" id="form7Example5" class="form-control" />
                                <label class="form-label" for="form7Example5">Email</label>
                            </div>

                            <!-- Number input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="number" id="form7Example6" class="form-control" />
                                <label class="form-label" for="form7Example6">Phone</label>
                            </div>

                            <!-- Message input -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <textarea class="form-control" id="form7Example7" rows="4"></textarea>
                                <label class="form-label" for="form7Example7">Additional information</label>
                            </div>

                            <!-- Checkbox -->
                            <div class="form-check d-flex justify-content-center mb-2">
                                <input class="form-check-input me-2" type="checkbox" value="" id="form7Example8" checked />
                                <label class="form-check-label" for="form7Example8">
                                    Create an account?
                                </label>
                            </div>
                        </form>--}}
                        <form action="{{route('practice-classes.save')}}">
                            <div class="">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="module" id="moduleSelect" class="form-select">
                                                <option value="1">Nhập môn lập trình máy tính</option>
                                                <option value="2">Kỹ thuật lập trình</option>
                                                <option value="3">Cơ sở dữ liệu</option>
                                                <option value="4">Kiến trúc máy tính</option>
                                            </select>
                                            <label for="moduleSelect" class="form-label">Module</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="First Name" required>
                                            <label for="lastName" class="form-label">Last Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                                            <label for="email" class="form-label">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" value="" name="iAgree" id="iAgree" required>
                                            <label class="form-check-label text-secondary" for="iAgree">
                                                I agree to the <a href="#!" class="link-primary text-decoration-none">terms and conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-dark btn-lg" type="submit">Sign up</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
