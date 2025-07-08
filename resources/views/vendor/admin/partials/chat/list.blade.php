
<style>

.card {
    background: #fff;
    transition: .5s;
    border: 0;
    margin-bottom: 30px;
    border-radius: .55rem;
    position: relative;
    width: 100%;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
}




.chat-list {
    height: 672px;
    overflow: auto;
}
.chat-app .people-list {
    width: 280px;
    position: absolute;
    left: 0;
    top: 0;
    padding: 20px;
    z-index: 7;
    background: #fff;
}

.chat-app .chat {
    margin-left: 280px;
    border-left: 1px solid #eaeaea
}

.people-list {
    -moz-transition: .5s;
    -o-transition: .5s;
    -webkit-transition: .5s;
    transition: .5s
}

.people-list .chat-list li {
    padding: 10px 15px;
    list-style: none;
    border-radius: 3px
}

.people-list .chat-list li:hover {
    background: #efefef;
    cursor: pointer
}

.people-list .chat-list li.active {
    background: #efefef
}

.people-list .chat-list li .name {
    font-size: 15px
}

.people-list .chat-list img {
    width: 45px;
    border-radius: 50%
}

.people-list img {
    float: left;
    border-radius: 50%
}

.people-list .about {
    float: left;
    padding-left: 8px;
    /* height: 600px;
    overflow: auto; */
}

.people-list .status {
    color: #999;
    font-size: 13px
}

.chat .chat-header {
    padding: 15px 20px;
    border-bottom: 2px solid #f4f7f6
}

.chat .chat-header img {
    float: left;
    border-radius: 40px;
    width: 40px
}

.chat .chat-header .chat-about {
    float: left;
    padding-left: 10px
}

.chat .chat-history {
    padding: 20px;
    border-bottom: 2px solid #fff;
    height: 600px;
    overflow: auto;
}

.chat .chat-history ul {
    padding: 0
}

.chat .chat-history ul li {
    list-style: none;
    margin-bottom: 30px
}

.chat .chat-history ul li:last-child {
    margin-bottom: 0px
}

.chat .chat-history .message-data {
    margin-bottom: 15px
}

.chat .chat-history .message-data img {
    border-radius: 40px;
    width: 40px
}

.chat .chat-history .message-data-time {
    color: #434651;
    padding-left: 6px
}

.chat .chat-history .message {
    color: #444;
    padding: 18px 20px;
    line-height: 26px;
    font-size: 16px;
    border-radius: 7px;
    display: inline-block;
    position: relative
}

.chat .chat-history .message:after {
    bottom: 100%;
    left: 7%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-bottom-color: #fff;
    border-width: 10px;
    margin-left: -10px
}

.chat .chat-history .my-message {
    background: #efefef
}

.chat .chat-history .my-message:after {
    bottom: 100%;
    left: 30px;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-bottom-color: #efefef;
    border-width: 10px;
    margin-left: -10px
}

.chat .chat-history .other-message {
    background: #e8f1f3;
    text-align: right
}

.chat .chat-history .other-message:after {
    border-bottom-color: #e8f1f3;
    left: 93%
}

.chat .chat-message {
    padding: 20px
}

.online,
.offline,
.me {
    margin-right: 2px;
    font-size: 8px;
    vertical-align: middle
}

.online {
    color: #86c541
}

.offline {
    color: #e47297
}

.me {
    color: #1d8ecd
}

.float-right {
    float: right
}

.clearfix:after {
    visibility: hidden;
    display: block;
    font-size: 0;
    content: " ";
    clear: both;
    height: 0
}

@media only screen and (max-width: 767px) {
    .chat-list {
     height: 465px;
    
    }
    .chat-app .people-list {
        /* height: 465px; */
        width: 100%;
        overflow-x: auto;
        background: #fff;
        /* left: -400px;
        display: none */
        position: relative;
        border-bottom: 2px solid rgb(216, 213, 213);
    }
    .chat-app .people-list.open {
        left: 0
    }
    .chat-app .chat {
        margin: 0
    }
    .chat-app .chat .chat-header {
        border-radius: 0.55rem 0.55rem 0 0
    }
    .chat-app .chat-history {
        height: 500px;
        overflow-x: auto
    }
}

@media only screen and (min-width: 768px) and (max-width: 992px) {
    .chat-app .chat-list {
        height: 650px;
        overflow-x: auto
    }
    .chat-app .chat-history {
        height: 600px;
        overflow-x: auto
    }
}

@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
    .chat-app .chat-list {
        height: 480px;
        overflow-x: auto
    }
    .chat-app .chat-history {
        height: calc(100vh - 350px);
        overflow-x: auto
    }
}


/* Addition */

/* Make sure .chat-history is a flex container when empty */
.chat-history.d-flex {
  display: flex !important;
}
.empty-chat {
  max-width: 60%;
  color: #666;
}
.empty-chat i {
  color: #ccc;
}
.empty-chat p {
  font-size: 1.1rem;
}

</style>

<div class="row clearfix">
     
    <div class="col-lg-12">
        <div class="card chat-app">
            <div id="plist" class="people-list">
                <div class="input-group">
                <!-- BS3 uses input-group-addon -->
                <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                </span>
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search…"
                    aria-label="Search"
                />
                </div>
                {{-- <ul class="list-unstyled chat-list mt-2 mb-0">
                    @include('vendor.admin.partials.chat._users')
                </ul> --}}
                 
                <ul class="list-unstyled chat-list mt-2 mb-0">
                    @if(! empty($getChatUser) && count($getChatUser))
                        @include('vendor.admin.partials.chat._users')
                    @else
                        <li class="text-muted">No chats yet…</li>
                    @endif
                </ul>
            </div>
            <div class="chat" id="getChatMessageAll">
            @if(!empty($getReceiver))
                @include('vendor.admin.partials.chat._message')
            @else 
            {{-- keep the same structure so .chat-history has height --}}
                <div class="chat-header"></div>
                <div class="chat-history d-flex align-items-center justify-content-center">
                <div class="empty-chat text-center">
                    <i class="fa fa-comment-dots fa-3x text-muted mb-3"></i>
                    <h2 class="mb-0">Tap on a chat to start a conversation!</h2>
                </div>
                </div>           
            @endif
        </div>
        </div>
    </div>
</div>


<script>

$('body').on('click', '.getChatWindows', function(e){
    e.preventDefault();
    var receiver_id = $(this).attr('id');
    let orgId = $(this).data('org');   // <-- this is the organisation ID
    $('.getChatWindows').removeClass('active');
    $(this).addClass('active')
    $.ajax({
      type: 'POST',
      url: "{{ admin_url('get_chat_windows') }}",
      data: {

        "receiver_id": receiver_id,
        '_token': "{{ csrf_token() }}"
      },
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      success: function(data){
        $('#ClearMessage'+receiver_id).hide();
        $('#getChatMessageAll').html(data.success);
        window.history.pushState("","","{{ admin_url('chat?receiver_id=')}}" + data.org_id);
        scrolldown();
        
      },
      error: function(xhr){
        console.error('error', xhr.responseJSON);
      },
    });
  });



  $('body').on('submit', '#submit_message', function(e){
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: "{{ admin_url('submit_message') }}",
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      success: function(data){
        // console.log('sent!', data);
        // optionally append the new message to the list
            $('#AppendMessage').append(data.success);
            $('#ClearMessage').val('');
            scrolldown();
      },
      error: function(xhr){
        console.error('error', xhr.responseJSON);
      },
    });
  });

  function scrolldown(){
    $('.chat-history').animate({scrollTop: $('.chat-history').prop("scrollHeight") + 3000000},500);
  }

  scrolldown();
</script>
