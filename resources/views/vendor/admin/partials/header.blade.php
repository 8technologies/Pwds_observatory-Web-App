<?php
use App\Models\Utils;
use App\Models\User;

?>
<style>
/* make sure this appears *after* all Bootstrap/AdminLTE CSS */
.navbar-custom-menu .chat-notification .chat-badge {
  position: absolute;
  top: 0.9rem;
  right: 0.7rem;
  background-color: #dc3545;    /* red */
  color: #fff;
  font-size: 0.9rem;
  line-height: 1;
  min-width: 1.4em;
  height:   1.4em;
  padding:  0 0.3em;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  box-shadow: 0 0 0 1px #fff;   /* thin white outline */
  pointer-events: none;
  user-select: none;
  animation: chatBadgePulse 1.5s ease-in-out infinite;
}

/* "Connecting to AI" bubble */
.ai-connecting {
  position: absolute;
  top: 2.4rem;
  left: -60%;
  background: #fff;
  border: 1px solid #e5e7eb;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 0.85rem;
  white-space: nowrap;
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
  z-index: 1060;
}
.ai-connecting .spinner-border { margin-right: 6px; }

/* Modal chat layout */
#aiChatPane { display: flex; flex-direction: column; height: 62vh; }
.ai-chat-messages {
  flex: 1 1 auto;
  overflow-y: auto;
  padding: 16px;
  background: #f8fafc;
}
.ai-chat-footer { width: 100%; }

.ai-msg {
  max-width: 70%;
  padding: 10px 12px;
  border-radius: 12px;
  margin: 6px 0;
  line-height: 1.35;
  word-wrap: break-word;
}
.ai-msg-user { margin-left: auto; background: #007bff; color: #fff; }
.ai-msg-bot { margin-right: auto; background: #ffffff; border: 1px solid #e5e7eb; }

.ai-typing {
  padding: 8px 16px;
  border-top: 1px solid #e5e7eb;
  background: #fff;
}

/* Fallback "no-bootstrap" modal open state */
#aiChatModal.ai-open { display: block; background: rgba(0,0,0,0.5); }
#aiChatModal.ai-open .modal-dialog { margin: 10vh auto; }


/* pulse/glow animation */
@keyframes chatBadgePulse {
  0% {
    box-shadow: 0 0 0 1px #fff;
    transform: scale(1);
  }
  50% {
    box-shadow: 0 0 8px rgba(220,53,69,0.7);
    transform: scale(1.1);
  }
  100% {
    box-shadow: 0 0 0 1px #fff;
    transform: scale(1);
  }
}



</style>
<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ admin_url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{!! 'Observatory' !!}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="font-weight: bold; color: #ffffff;">{!! 'Observatory' !!}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top d-block p-0" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <ul class="nav navbar-nav hidden-sm visible-lg-block">
            {!! Admin::getNavbar()->render('left') !!}
        </ul>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav ">

                {!! Admin::getNavbar()->render() !!}

                <!-- Chat Toggle Icon -->
                @php 
                $unread = App\Models\Chat::getAllChatUserCount();
                    @endphp
                    <!-- AI Chatbot Placeholder Icon -->
                    <li class="nav-item ai-chat-notification position-relative" id="aiChatNavItem">
                    <a class="nav-link" href="javascript:void(0)" id="aiChatBtn" aria-label="Open AI assistant">
                        <!-- Use your logo path here -->
                        {{-- <img src="{{ asset('images/ai-bot-logo.png') }}" alt="AI Chatbot" style="height:20px;width:20px;"> --}}
                        <i class="bi bi-robot" aria-hidden="true"></i>
                    </a>

                    <!-- Tiny popup for "connecting" -->
                    <div id="aiConnecting" class="ai-connecting d-none" role="status" aria-live="polite">
                        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                        Connecting to AI...
                    </div>
                    </li>


                    <li class="nav-item chat-notification position-relative">
                    <a class="nav-link" href="{{ admin_url('chat') }}">
                        <i class="bi bi-chat-text-fill"></i>
                        @if($unread > 0)
                        
                        <span class="chat-badge">{{ $unread }}</span>
                        @endif
                    </a>
                    </li>
                    
               
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ Admin::user()->avatar }}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Admin::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ Admin::user()->avatar }}" class="img-circle" alt="User Image">

                            <p>
                                {{ Admin::user()->name }}
                                <small>Member since admin {{ Admin::user()->created_at }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                @php
                                    $user = User::find(Auth::user()->id); 
                                @endphp

                                @if (Auth::user()->isRole('district-union'))
                                    <a href="{{ admin_url('district-unions/' . $user->organisation->id . '/edit') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @elseif(Auth::user()->isRole('opd'))
                                    <a href="{{ admin_url('opds/' . $user->organisation->id . '/edit') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @elseif(Auth::user()->isRole('service-provider'))
                                    <a href="{{ admin_url('service-providers/' . $user->service_provider . '/edit') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @else
                                    <a href="{{ admin_url('auth/setting') }}"
                                        class="btn btn-default btn-flat">Profile</a>
                                @endif
                            </div>


                            <div class="pull-right">
                                <a href="{{ admin_url('auth/logout') }}"
                                    class="btn btn-default btn-flat">{{ trans('admin.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                {{-- <li> --}}
                {{-- <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> --}}
                {{-- </li> --}}
            </ul>
        </div>

    </nav>
    <!-- AI Chat Modal -->
<div class="modal fade" id="aiChatModal" tabindex="-1" role="dialog" aria-labelledby="aiChatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width:860px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="aiChatModalLabel">
          <i class="bi bi-robot" aria-hidden="true"></i> AI Assistant (Preview)
        </h4>
        <button type="button" class="close" aria-label="Close" id="aiModalCloseBtn">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-0">
        <div id="aiChatPane">
          <div id="aiChatMessages" class="ai-chat-messages" role="log" aria-live="polite" aria-relevant="additions"></div>

          <div id="aiTyping" class="ai-typing d-none">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            AI is typing…
          </div>
        </div>
      </div>

      <div class="modal-footer ai-chat-footer">
        <form id="aiChatForm" class="w-100" onsubmit="return false;">
          <div class="input-group">
            <input type="text" id="aiUserInput" class="form-control" placeholder="Type a message" autocomplete="off" aria-label="Type your message">
            <div class="input-group-btn input-group-append">
              <button id="aiSendBtn" class="btn btn-primary" type="button" aria-label="Send">Send</button>
            </div>
          </div>
          <small class="text-muted d-block mt-1"></small>
        </form>
      </div>
    </div>
  </div>
</div>

    <script>
        // Close modal button event
document.getElementById('aiModalCloseBtn').addEventListener('click', function () {
  if (window.jQuery && window.$ && $.fn.modal) {
    $('#aiChatModal').modal('hide');
  } else {
    modal.classList.remove('ai-open');
  }
});

(function () {
  var btn = document.getElementById('aiChatBtn');
  var bubble = document.getElementById('aiConnecting');
  var modal = document.getElementById('aiChatModal');
  var hasWelcomed = false;

  function showModal() {
    if (window.jQuery && window.$ && $.fn.modal) {
      $('#aiChatModal').modal('show');
    } else {
      modal.classList.add('ai-open');
    }
    // Seed a welcome message only once per open
    setTimeout(function(){
      if (!hasWelcomed) {
        appendBot("Hi! I’m an AI assistant. How is your day Today?.");
        hasWelcomed = true;
      }
    }, 150);
  }

  // Connecting animation + open modal
  btn.addEventListener('click', function () {
    bubble.classList.remove('d-none');

    // Reset bubble content (in case reused)
    bubble.innerHTML = '<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span> Connecting to AI...';

    setTimeout(function () {
      bubble.innerHTML = '<span class="text-success"><i class="bi bi-check-circle" aria-hidden="true"></i> Connected!</span>';

      setTimeout(function () {
        bubble.classList.add('d-none');
        // restore for next click
        bubble.innerHTML = '<span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span> Connecting to AI...';
        showModal();
      }, 900);
    }, 1400);
  });

  // Simple demo chat logic (echo + typing)
  var form = document.getElementById('aiChatForm');
  var input = document.getElementById('aiUserInput');
  var sendBtn = document.getElementById('aiSendBtn');
  var msgs = document.getElementById('aiChatMessages');
  var typing = document.getElementById('aiTyping');

  function appendMessage(text, who) {
    var div = document.createElement('div');
    div.className = 'ai-msg ' + (who === 'user' ? 'ai-msg-user' : 'ai-msg-bot');
    div.textContent = text;
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
  }
  function appendUser(text){ appendMessage(text, 'user'); }
  function appendBot(text){ appendMessage(text, 'bot'); }

  function fakeReply(userText) {
    typing.classList.remove('d-none');
    setTimeout(function(){
      typing.classList.add('d-none');
      appendBot("Demo response to: “" + userText + "”. In production, this would call your AI backend.");
    }, 900);
  }

  function sendMessage() {
    var val = (input.value || '').trim();
    if (!val) return;
    appendUser(val);
    input.value = '';
    fakeReply(val);
  }

  sendBtn.addEventListener('click', sendMessage);
  input.addEventListener('keydown', function(e){
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

})();


</script>


</header>

