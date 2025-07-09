        <div class="chat-header clearfix">
            @include('vendor.admin.partials.chat._header')
        </div>
        <div class="chat-history">
            @include('vendor.admin.partials.chat._chat')
        </div>
        <div class="chat-message d-flex align-items-center">
                <form action="{{ url('submit_message') }}"
                        id="submit_message"
                        method="post"
                        class="w-100 d-flex align-items-center"
                        enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="receiver_id" value="{{ $getReceiver->id }}">

                    <!-- Image upload / attach button on the left -->
                    <button type="button"
                            class="btn btn-outline-primary mr-2"
                            title="Attach Image">
                    <i class="fa fa-image"></i>
                    </button>

                    <!-- Expanding textarea in the middle -->
                    <textarea name="message"
                            id="ClearMessage"
                            class="form-control flex-grow-1"
                            rows="1"
                            placeholder="Type a message…"></textarea>

                    <!-- Send button stays on the right -->
                    <button type="submit"
                            class="btn btn-success ml-2"
                            title="Send">
                    <i class="bi bi-send"></i>
                    </button>
                </form>
        </div>