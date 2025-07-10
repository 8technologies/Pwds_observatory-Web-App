
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

/* hide the real file input */
#file_name {
  display: none;
}

/* container styling */
.chat-message {
  background-color: #fff;
  border-top: 1px solid #e1e1e1;
}

/* attach & send buttons */
.attach-btn,
.send-btn {
  background: none;
  border: none;
  color: #6c757d;
  font-size: 1.25rem;
  cursor: pointer;
  transition: color .2s, transform .2s;
}
.attach-btn:hover,
.send-btn:hover {
  color: #343a40;
  transform: scale(1.1);
}

/* the textarea */
.message-input {
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 999px;
  padding: .5rem 1rem;
  resize: none;
  box-shadow: none;
  transition: border-color .2s;
}
.message-input:focus {
  border-color: #80bdff;
  box-shadow: 0 0 0 .1rem rgba(0,123,255,.25);
  outline: none;
}

/* remove extra form margins */
.chat-message form {
  margin: 0;
  padding: 0;
}

/* ensure the label is relative so preview can float over it */
.attach-btn {
  position: relative;
  font-size: 1.75rem;
  margin-right: .5rem;
}



/* thumbnail style */
.file-thumb {
  width: 30px;
  height: 30px;
  border-radius: 4px;
  object-fit: cover;
 
}

/* where the filename / thumb will appear */
.file-preview {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
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
                <span class="input-group-addon" id="getSearchUser">
                    <i class="fa fa-search"></i>
                </span>
                <input
                    id="getSearch"
                    type="text"
                    class="form-control"
                    placeholder="Search…"
                    aria-label="Search"
                />
                @php
                    // the “current” org chat you’re in, from the query string
                    $activeOrg = request()->query('receiver_id', '');
                @endphp
                <input type="hidden" id="getReceiverIDDynamic" value="{{ $activeOrg }}"/>
                </div>
                {{-- <ul class="list-unstyled chat-list mt-2 mb-0">
                    @include('vendor.admin.partials.chat._users')
                </ul> --}}
                 {{-- @php
                    // the “current” org chat you’re in, from the query string
                    $activeOrg = request()->query('receiver_id', '');
                @endphp --}}
                @php
                  use App\Models\Organisation;
                  // hard-code your “Nudipu” default – here it’s ID=1 per your DB dump
                  $defaultOrg = Organisation::find(1);
                @endphp
                <ul class="list-unstyled chat-list mt-2 mb-0" id="getSearchUserDynamic">
                    @if(! empty($getChatUser) && count($getChatUser))
                    @include('vendor.admin.partials.chat._users')
                  @else
                    {{-- show default “Nudipu” entry --}}
                    <li class="clearfix getChatWindows {{ request()->query('receiver_id') == $defaultOrg->id ? 'active' : '' }}"
                        id="{{ $defaultOrg->id }}">
                      <img style="height:45px"
                          src="{{ $defaultOrg->getProfilePic() }}"
                          alt="avatar">
                      <div class="about">
                        <div class="name">NUDIPU</div>
                        <div class="status">
                          <i class="fa fa-circle"></i>
                          Talk to the admin
                        </div>
                      </div>
                    </li>
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
                  <h2 class="mb-0">
                    Tap on a chat to start a conversation!<br>
                    Or talk to nudipu by tapping on <strong>NUDIPU</strong>.
                  </h2>
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
    $('#getReceiverIDDynamic').val(receiver_id);
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

//Search user
$('body').on('click', '#getSearchUser', function(e){
    var search = $('#getSearch').val();
    //var activeOrg = request()->query('receiver_id');
    var receiver_id = $('#getReceiverIDDynamic').val();
     $.ajax({
      type: 'POST',
      url: "{{ admin_url('get_chat_search_user') }}",
      data: {
        "search": search,
        "receiver_id": receiver_id,
        '_token': "{{ csrf_token() }}"
      },
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      success: function(data){
        
          $('#getSearchUserDynamic').html(data.success);
      },
      error: function(xhr){
        console.error('error', xhr.responseJSON);
      },
    });

});
//EndSearch user



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
            $('#file_name').val('');
            $('#getFileName').html('');
            $('#filePreview').html('');
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

    $('body').delegate('#OpenFile','click', function(e){
        
        $('#file_name').trigger('click');
    });

    $('body').delegate('#file_name','change', function(e){
        
        var filename = this.files[0].name;
        $('#getFileName').html(filename);
    });


    document.getElementById('file_name').addEventListener('change', function(){
    const preview = document.getElementById('filePreview');
    preview.innerHTML = ''; // clear old

    const file = this.files[0];
    if (!file) return;

    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'file-thumb';
        preview.appendChild(img);
      };
      reader.readAsDataURL(file);
    } else {
      // just show name for non-images
      preview.textContent = file.name;
    }
  });
</script>
