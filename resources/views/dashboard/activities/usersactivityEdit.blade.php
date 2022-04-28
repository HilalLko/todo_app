@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Edit') }} {{ $userActivity->title }}</div>
                    <div class="card-body">
                        <br>
                        <form method="POST" action="{{ route('admin.user_activity_update', $userActivity->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                              <label>Title</label>
                              <input type="hidden" name="on_date" id="on_date">
                              <input type="text" placeholder="Title" id="activity_title" name="activity_title" class="form-control" value="{{ $userActivity->activity_title }}">
                            </div>
                            <div class="form-group">
                              <label>Acitvity Date</label>                              
                              <input type="text" placeholder="Date" id="on_date" name="on_date" class="form-control" value="{{ $userActivity->on_date }}">
                            </div>
                            <div class="form-group">
                              <label>User</label>                              
                              <input type="text" id="user" class="form-control" disabled value="{{ $userActivity->user->name }}">
                            </div>
                            <div class="form-group">
                              <label>Description</label>
                              <textarea class="form-control" name="activity_description" id="activity_description" rows="3">{{ $userActivity->activity_description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="formFile">Image</label>
                                @if($userActivity->activity_image != "")
                                    <img class="img-responsive" src="{{ Storage::url($userActivity->activity_image) }}" style="height: 100px;">
                                @else  
                                    <input class="form-control" id="activity_image" name="activity_image" type="file">
                                @endif
                            </div>                                                    
                            <button class="btn btn-block btn-success" type="submit">{{ __('Save') }}</button>
                            <a href="{{ route('admin.user_activities') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a> 
                        </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('javascript')

@endsection