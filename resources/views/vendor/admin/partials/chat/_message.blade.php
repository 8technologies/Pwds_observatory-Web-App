        <div class="chat-header clearfix">
            @include('vendor.admin.partials.chat._header')
        </div>
        <div class="chat-history">
            @include('vendor.admin.partials.chat._chat')
        </div>
        <div class="chat-message px-3 py-2 d-flex align-items-center">
  <form action="{{ url('submit_message') }}"
        id="submit_message"
        method="post"
        class="w-100 d-flex align-items-center m-0"
        enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="receiver_id" value="{{ $getReceiver->id }}">

    {{-- Attach icon + preview container --}}
    {{-- <label for="file_name" class="attach-btn mb-0">
      <h class="fa fa-paperclip"></i>
      <span id="filePreview" class="file-preview"></span>
    </label>
    <input type="file" name="file_name" id="file_name"> --}}

    {{-- Message box --}}
    <div class="flex-grow-1 mx-2">
      <textarea name="message"
            id="ClearMessage"
            class="form-control message-input emojionearea"
            rows="1"
            placeholder="Type a message…"></textarea>
    </div>

    {{-- Send icon --}}
    <button type="submit" class="send-btn mb-0">
      <i class="fa fa-paper-plane"></i>
    </button>
  </form>
</div>
