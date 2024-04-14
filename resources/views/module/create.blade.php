@extends('layouts.app')

@section('content')
    <div class="container h-100 right-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">New Practice Class</h1>
        </div>
        <div class="row">
            <div class="col-6 mb-4">
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
                        <form action="{{route('practice-classes.store')}}" method="post">
                            @csrf
                            <fieldset class="">
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
                                            <input type="text" class="form-control" name="className" id="className" placeholder="Class Name" required>
                                            <label for="className" class="form-label">Class Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="practiceRoom" id="roomSelect" class="form-select">
                                                <option value="1">601 A1</option>
                                                <option value="2">702 A1</option>
                                                <option value="3">802 A1</option>
                                            </select>
                                            <label for="roomSelect" class="form-label">Practice Room</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="registerQty" id="registerQty" placeholder="99" disabled>
                                            <label for="registerQty" class="form-label">PC QTY</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <input type="date" class="form-control" name="startDate" id="startDate" required>
                                            <label for="startDate" class="form-label">Start Date</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="session" id="sessionSelect" class="form-select">
                                                <option value="1">Morning (1-6)</option>
                                                <option value="2">Afternoon (7-12)</option>
                                                <option value="3">Evening (13-16)</option>
                                            </select>
                                            <label for="sessionSelect" class="form-label">Session</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="recurring" id="recurringSelect" class="form-select">
                                                <option value="1">Once (No repeat)</option>
                                                <option value="2">Weekly</option>
                                                <option value="3">Biweekly</option>
                                            </select>
                                            <label for="recurringSelect" class="form-label">Recurring</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating mb-3">
                                            <select name="teacher" id="teacherSelect" class="form-select">
                                                <option value="1">Nguyen Van A</option>
                                                <option value="2">Tran Thi B</option>
                                                <option value="3">Ngo Trong C</option>
                                            </select>
                                            <label for="teacherSelect" class="form-label">Teacher</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-dark btn-lg" type="submit">Create</button>
                                        <a href="{{route('practice-classes.index')}}" class="btn btn-secondary btn-lg">Cancel</a>
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
