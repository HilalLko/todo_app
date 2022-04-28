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