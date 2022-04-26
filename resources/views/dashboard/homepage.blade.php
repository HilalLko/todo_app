@extends('dashboard.base')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
@endsection
@section('content')
  <div class="container-fluid">
    <div id='full_calendar_activities'>
      
    </div>
  </div>
  <div class="modal fade" id="schedule-add" tabindex="-1" aria-labelledby="Schedule-add" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Add Activity</h5>
        </div>
        <div class="modal-body">
          <form method="POST" action="" id="add_activity_form" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label>Title</label>
              <input type="hidden" name="on_date" id="on_date">
              <input type="text" placeholder="Title" id="activity_title" name="activity_title" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" name="activity_description" id="activity_description" rows="3" required></textarea>
            </div>
            <div class="form-group">
              <label class="form-label" for="formFile">Image</label>
              <input class="form-control" id="activity_image" name="activity_image" type="file">
            </div>
            <button class="btn btn-primary" type="submit" id="Save_activity">Save</button>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" id="btnClosePopup" data-coreui-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>
  

  <!-- Edit Modal -->
  <div class="modal fade" id="schedule-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Your Schedule</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">          
          <div class="form-group">
            <label>Title</label>
            <input type="hidden" name="on_date" id="on_date">
            <input type="text" placeholder="Title" id="activity_title" name="activity_title" class="form-control">
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="activity_description" id="activity_description" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="formFile">Image</label>
            <input class="form-control" id="activity_image" name="activity_image" type="file">
          </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success">Save Your Schedule</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script>
    $(document).ready(function () {
      var addModal = new coreui.Modal(document.getElementById('schedule-add'), {
        keyboard: true
      });
      var ADDURL = "{{ route('admin.activity.add') }}";
      var LISTURL = "{{ route('admin.dashboard') }}";
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      var calendar = $('#full_calendar_activities').fullCalendar({
        editable: true,
        editable: true,
        events: LISTURL,
        displayEventTime: false,
        eventRender: function (event, element, view) {
          if (event.allDay === 'true') {
              event.allDay = true;
          } else {
              event.allDay = false;
          }
        },
        selectable: true,
        selectHelper: true,
        select: function (event_start, event_end, allDay) {
          $('#on_date').val(event_start.format('Y-MM-DD'));
          addModal.show();
        },
        eventDrop: function (event, delta) {
            var event_start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
            var event_end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
            $.ajax({
                url: SITEURL + '/calendar-crud-ajax',
                data: {
                    title: event.event_name,
                    start: event_start,
                    end: event_end,
                    id: event.id,
                    type: 'edit'
                },
                type: "POST",
                success: function (response) {
                    displayMessage("Event updated");
                }
            });
        },
        eventClick: function (event) {
          console.log(event);
          // var eventDelete = confirm("Are you sure?");
          // if (eventDelete) {
          //     $.ajax({
          //         type: "POST",
          //         url: SITEURL + '/calendar-crud-ajax',
          //         data: {
          //             id: event.id,
          //             type: 'delete'
          //         },
          //         success: function (response) {
          //             calendar.fullCalendar('removeEvents', event.id);
          //             displayMessage("Event removed");
          //         }
          //     });
          // }
        }        
      });
      $("#btnClosePopup").click(function () {
        addModal.hide();
        //displayInfoMessage('Popup Closed');
      });

      //$('body').on('submit', '#add_activity_form',function(e) {
      $('#add_activity_form').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);        
        let title = $('#activity_title').val();
        let description = $('#activity_description').val();
        let event_start = $('#on_date').val();
        $.ajax({
            type: "POST",
            url: ADDURL,
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function (response) {
              $(this).trigger("reset");
              if (response.code != 200) {
                displayErrorMessage(response.message);
              } else {
                displayMessage("Activity created successfully.");
                calendar.fullCalendar('renderEvent', {
                    id: response.data.id,
                    title: title,
                    start: event_start,
                    end: event_start
                }, true);
                calendar.fullCalendar('unselect');
              }
              addModal.hide();
            }
        });
      });
    });
    function displayMessage(message) {
        toastr.success(message, 'Event');            
    }
    function displayErrorMessage(message) {
        toastr.error(message, 'Event');            
    }
    function displayWarningMessage(message) {
        toastr.warning(message, 'Event');            
    }
    function displayInfoMessage(message) {
        toastr.info(message, 'Event');            
    }
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-center",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
  </script>
@endsection
