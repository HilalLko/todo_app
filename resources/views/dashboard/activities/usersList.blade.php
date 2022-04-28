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
                        <div class="row mb-n3">
                          <div class="col-12">
                            <div class="row">
                              <div class="col-6 form-group">
                                <input type="text" class="form-control pull-right" id="activity_title" placeholder="Activity Title">
                              </div>
                              <div class="col-6 form-group">
                                <select class="form-control filter" id="user_type">
                                  <option value="">Select User</option>
                    
                                </select>
                              </div>  
                            </div>
                            <div class="row mb-3">
                              <div class="col-6 form-group">
                                <button type="button" class="btn btn-pill btn-success btn-block" id="fileter-res"><span class="cil-filter btn-icon mr-2 mt-0"></span>&nbsp;&nbsp; Filter</button>
                              </div>
                              <div class="col-6 form-group">
                                <a role="button" class="btn btn-pill btn-warning btn-block" href="{{ route('admin.global_activities') }}"><span class="cil-loop btn-icon mr-2"></span> Refresh</a>
                              </div>  
                            </div>
                          </div>                                                    
                        </div>
                        <div id="data-holder">  
                          <table class="table table-responsive-sm table-striped">
                          <thead>
                            <tr>
                              <th>Title</th>
                              <th>Description</th>
                              <th>User</th>
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
                                <td>{{ $activity->user->name }}</td>
                                <td>{{ $activity->on_date }}</td>
                                <td>
                                  @if($activity->activity_image)
                                    <img class="img-responsive" src="{{ Storage::url($activity->activity_image) }}" style="height: 100px;">
                                  @else
                                    N/A
                                  @endif
                                </td>                            
                                <td>
                                  <a href="{{ route('admin.user_activity_edit',$activity->id) }}" class="btn btn-block btn-primary">Edit</a>
                                </td>
                                <td>
                                  <form action="{{ route('admin.user_activity_destory', $activity->id ) }}" method="POST">
                                      @method('DELETE')
                                      {{ csrf_field() }}
                                      <button class="btn btn-block btn-danger" id="delete_activity">Delete Activity</button>
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
        </div>

@endsection


@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
  $( window ).on("load", function() {
    $.ajax({
      type: "GET",
      url: '/sitemaster/users',              
      success: function( result ) {
        $('#user_type').empty();
        $('#user_type').append('<option value="">Select User</option>');
        $.each(result,function(index,user){
          $('#user_type').append('<option value="'+user.id+'">'+user.name+'</option>');
        }) 
      }
    });
  });
  $('#fileter-res').on('click', function() {
      var user = $('#user_type').val(), title = $('#activity_title').val();
      if(user == "" && title == "" )
      {        
        return false;
      }   
      $.ajax({
          type: "GET",
          beforeSend: function(){
            $('#data-holder').empty();
            $('#img-responsive').show();
          },
          url: '/sitemaster/user-activities',
          data: { 
              user: user,               
              title: title
          },              
          success: function( result ) {  
            $('#img-responsive').hide();
            $('#data-holder').html(result);
          }
        });
    });
    $('.btn-danger').on('click',function(e){
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