@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-8 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Activity (Global)') }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>On Date</th>
                            <th>Image</th>                      
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($activities as $activity)
                            <tr>
                              <td>{{ $activity->activity_title }}</td>
                              <td>{{ $activity->activity_description }}</td>
                              <td>{{ $activity->on_date }}</td>
                              <td>
                                @if($activity->activity_image)
                                  <img class="img-responsive" src="{{ Storage::url($activity->activity_image) }}" style="height: 100px;">
                                @else
                                  N/A
                                @endif
                              </td>
                              <td>
                                <form method="POST" action="{{ route('admin.global_activity_destory', $activity->id) }}">                                
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">{{ __('Delete') }}</button>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript">
    $('.btn-danger').on('click',function(e){
      console.log('amClicked');
      e.preventDefault();
      var form = $(this).parents('form');
      swal({ 
        title: "Are you sure ?",
        text: "You will not be able to recover this activity!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((confirmed) => {
        if (confirmed) {
          form.submit();
        } else {
          swal("Cancelled", "Selected  activity's information are still safe!", "error");   
        }
      });
    });
  </script>
@endsection

