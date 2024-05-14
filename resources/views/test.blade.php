@php use App\Models\PracticeClass\PracticeClass; @endphp
@extends('layouts.app')

@section('content')
@php
    $practiceClass = PracticeClass::find(1);

    if ($practiceClass) {
        $students = $practiceClass->students;
        dd($students);
    } else {
        $students = collect(); // or handle the error appropriately
    }
@endphp
@endsection
