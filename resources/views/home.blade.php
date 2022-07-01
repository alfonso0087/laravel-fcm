@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <center>
        <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()"
          class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
      </center>
      <div class="card">
        <div class="card-header">{{ __('Dashboard') }}</div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          <form action="{{ route('send-notification') }}" method="POST">
            @csrf
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" name="title">
            </div>
            <div class="form-group">
              <label>Body</label>
              <textarea class="form-control" name="body"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Notification</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>

<script>
  const firebaseConfig = {
    apiKey: "AIzaSyBDsYFBs8GsP55o_IQ_RA9H4JcLszPUbts",
    databaseURL: "https://web-notification-80206.firebaseio.com",
    authDomain: "web-notification-80206.firebaseapp.com",
    projectId: "web-notification-80206",
    storageBucket: "web-notification-80206.appspot.com",
    messagingSenderId: "1087590402975",
    appId: "1:1087590402975:web:e8084769b595c5d6bacbaa",
    measurementId: "G-7MHVFGYWL1"
  };

  firebase.initializeApp(firebaseConfig);
  firebase.analytics();

  const messaging = firebase.messaging();

  function initFirebaseMessagingRegistration() {
    messaging
    .requestPermission()
    .then(function () {
      return messaging.getToken()
    })
    .then(function(token) {
      // console.log(token);
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
          url: '{{ route("save-token") }}',
          type: 'POST',
          data: {
              token: token
          },
          dataType: 'JSON',
          success: function (response) {
            alert('Token saved successfully.');
          },
          error: function (err) {
              alert('User Chat Token Error'+ err);
          },
      });
    }).catch(function (err) {
      alert('User Chat Token Error'+ err);
    });
  }  
  
  messaging.onMessage(function(payload) {
    const noteTitle = payload.notification.title;
    const noteOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon,
    };
    new Notification(noteTitle, noteOptions);
  });
</script>
@endsection