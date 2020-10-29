<?php
use Illuminate\Support\Facades\Auth;
?>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tasks</div>

                <div class="card-body">
                    <Tasks token="{{ (Auth::user())->api_token }}" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
