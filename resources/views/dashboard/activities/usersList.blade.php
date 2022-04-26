@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Activity (User)') }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>On Date</th>
                            <th>Image</th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($activities as $activity)
                            <tr>
                              <td>{{ $activity->activity_title }}</td>
                              <td>{{ $activity->activity_description }}</td>
                              <td>{{ $activity->on_date }}</td>
                              <td>{{ $activity->activity_image }}</td>                            
                              <td>
                                <a href="{{ url('/sitemaster/users/' . $activity->id . '/edit') }}" class="btn btn-block btn-primary">Edit</a>
                              </td>
                              <td>
                                <form action="{{ route('users.destroy', $activity->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">Delete Activity</button>
                                </form>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $activities->links() }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection