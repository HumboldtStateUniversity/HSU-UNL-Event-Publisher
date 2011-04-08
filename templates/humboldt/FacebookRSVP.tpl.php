<?php 
if($this->account->createEvents()){
?>
<div id="fb-root"></div>
<script type="text/javascript">
  var fbEventId = <?php echo $this->facebook->facebook_id;?>;
  window.fbAsyncInit = function() {
    FB.init({appId: <?php echo $this->config["appID"];?>, status: true, cookie: true,
             xfbml: true});
    //alert(fbEventId);
    FB.Event.subscribe('auth.login', function() {
        window.location.reload();
    });
    FB.Event.subscribe('auth.logout', function() {
        window.location.reload();
    });
    FB.api('/me', function(response) {
        var uid = response.id;
        //alert(uid);
        if(response.id != null){
            document.getElementById("name").innerHTML = response.name;
            document.getElementById('profile').style.visibility = 'visible';
            document.getElementById('fbLogin').style.visibility = 'hidden';
            document.getElementById("profilePic").innerHTML = "<img src='http://graph.facebook.com/"+uid+"/picture'>";
            var query = FB.Data.query('SELECT rsvp_status FROM event_member WHERE uid={0} AND eid={1}',uid,fbEventId);
            query.wait(function(rows) {
                if(typeof rows[0] != 'undefined' && rows[0].rsvp_status != 'not_replied'){
                    if(rows[0].rsvp_status == "unsure"){
                        document.getElementById("rsvpStatus").innerHTML = "maybe";
                        enableButtons("maybe");
                    }else{
                        document.getElementById("rsvpStatus").innerHTML = rows[0].rsvp_status;
                        enableButtons(rows[0].rsvp_status);
                    }
                        document.getElementById(rows[0].rsvp_status).disabled = true
                    }else{
                        //enable all buttons
                        enableButtons("none");
                }
            });
        }else{
            document.getElementById('profile').style.display = "none";
            document.getElementById('fbLogin').style.visibility = 'visible';
        }
    });
    function rsvp(){
        alert("RSVPing");
    }
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
  function disableButtons(){
      document.getElementById("declined").disabled = true;
      document.getElementById("unsure").disabled = true;
      document.getElementById("attending").disabled = true;
  }
  function enableButtons(disabled){
      if(disabled != "declined")
          document.getElementById("declined").disabled = false;
      if(disabled != "maybe")
          document.getElementById("unsure").disabled = false;
      if(disabled != "attending")
          document.getElementById("attending").disabled = false;
      document.getElementById("rsvpStatus").innerHTML = disabled;
  }
  function rsvp(attending){
    //alert(fbEventId + " " + attending);
    FB.api('/'+fbEventId+"/"+attending, "POST", function(response) {
        if (!response || response.error) {
            alert('Error occured');
        } else {
            //success.
            disableButtons();
            enableButtons(attending);
            //2. enable all buts except for the one just used.
            //alert('Post ID: ' + response);
        }
    });
  }
</script>

<div id='rsvpBox' class="fb_widget">
    <h3>Facebook</h3>
    <div id='profile'>
        <span id='profilePic'></span>
        Welcome <span id='name'></span> (<a href="#" onclick="FB.logout()">Logout</a>)<br>
        Current RSVP: <span id='rsvpStatus'></span>
    </div>
    <div>
    <span id='fbLogin'><a href="#" onclick="FB.login(function(response) {
       if (response.session) {
            if (response.perms) {
              // user is logged in and granted some permissions.
              // perms is a comma separated list of granted permissions
              //alert('Permissions Granted');
            } else {
              // user is logged in, but did not grant any permissions
            }
        } else {
            // user is not logged in
        }
    }, {perms:'rsvp_event,user_events'});">Log into Facebook</a></span>
   </div>
    
    <div id='rsvp'>
        <button id="attending" type="button" onclick="rsvp('attending')" disabled="disabled">Attending</button>
        <button id="unsure" type="button" onclick="rsvp('maybe')" disabled="disabled">Maybe</button>
        <button id="declined" type="button" onclick="rsvp('declined')" disabled="disabled">Decline</button>
    </div>

</div>
<?php 
}
?>


