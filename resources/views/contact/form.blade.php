@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Contact Form</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/contact/submit') }}" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="POST">
                        {!! csrf_field() !!}

                        <!--Name-->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            </div>
                        </div>

                        <!--E-mail-->
                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <!--Type-->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Type</label>

                            <div class="col-md-6">
                                <select class="form-control" name="type_id">
                                    @foreach($types as $type)
                                    <option value="{{$type->id}}">{{$type->friendly_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!--Notes-->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Message</label>

                            <div class="col-md-6">
                                <textarea class="form-control" name="notes">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                        
                         
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
