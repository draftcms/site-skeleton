@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Basic Information</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="PUT" action="{{ url('/profile/update') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Social Media Connections</div>
                <div class="panel-body">

                    <!--Facebook-->
                    <div class="col-md-3 soc_med_connect">
                        <img class="soc_med_icon" src="images/social_media_icons/icon-facebook.png">
                        @if($providers->contains('facebook'))
                        <a href="/disconnect/facebook" class="btn btn-primary active">Disconnect</a>
                        @else
                        <a href="/connect/facebook" class="btn btn-info">Connect</a>
                        @endif
                    </div>

                    <!--Twitter-->
                    <div class="col-md-3 soc_med_connect">
                        <img class="soc_med_icon" src="images/social_media_icons/icon-twitter.png">
                        @if($providers->contains('twitter'))
                        <a href="/disconnect/twitter" class="btn btn-primary active">Disconnect</a>
                        @else
                        <a href="/connect/twitter" class="btn btn-info">Connect</a>
                        @endif
                    </div>

                    <!--Google-->
                    <div class="col-md-3 soc_med_connect">
                        <img class="soc_med_icon" src="images/social_media_icons/icon-google.png">
                        @if($providers->contains('google'))
                        <a href="/disconnect/google" class="btn btn-primary active">Disconnect</a>
                        @else
                        <a href="/connect/google" class="btn btn-info">Connect</a>
                        @endif
                    </div>


                    <!--GitHub-->
                    <div class="col-md-3 soc_med_connect">
                        <img class="soc_med_icon" src="images/social_media_icons/icon-github.png">
                        @if($providers->contains('github'))
                        <a href="/disconnect/github" class="btn btn-primary active">Disconnect</a>
                        @else
                        <a href="/connect/github" class="btn btn-info">Connect</a>
                        @endif
                    </div>

                    <!--Spotify-->
                    <div class="col-md-3 soc_med_connect">
                        <img class="soc_med_icon" src="images/social_media_icons/icon-spotify.png">
                        @if($providers->contains('spotify'))
                        <a href="/disconnect/spotify" class="btn btn-primary active">Disconnect</a>
                        @else
                        <a href="/connect/spotify" class="btn btn-info">Connect</a>
                        @endif
                    </div>

                    <!--SoundCloud-->
                    <div class="col-md-3 soc_med_connect">
                        <img class="soc_med_icon" src="images/social_media_icons/icon-soundcloud.png">
                        @if($providers->contains('soundcloud'))
                        <a href="/disconnect/soundcloud" class="btn btn-primary active">Disconnect</a>
                        @else
                        <a href="/connect/soundcloud" class="btn btn-info">Connect</a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Profile Image</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/avatar/') }}/{{$user->id}}" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}

                        @if($user->avatar)
                        <h5>Current avatar</h5>
                        <img src="{{$user->avatar->file_path}}">
                        @else
                        <h5>No avatar for this user</h5>
                        @endif

                        <h5>Upload a custom avatar image.</h5>
                        <input type="file" name="file"></input>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Save
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
