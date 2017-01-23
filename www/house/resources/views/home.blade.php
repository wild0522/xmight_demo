@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <label for="usr">Your Api Token (Headers)</label><br />
                    Key:<input type="text" class="form-control" value="Authorization">
                    Value:<input type="text" class="form-control" value="Bearer {{Auth::user()->api_token}}">
                    Click me! <p><a href="http://dashboard.dev/token/{{Auth::user()->api_token}}">http://dashboard.dev/token/{{Auth::user()->api_token}}</a></p>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
