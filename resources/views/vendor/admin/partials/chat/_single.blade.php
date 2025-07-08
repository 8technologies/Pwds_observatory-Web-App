 @foreach($getchat as $value)
        @if($value->sender_id == Auth::user()->id)
            <li class="clearfix">
                <div class="message-data text-right">
                    <span class="message-data-time">{{ \Carbon\Carbon::parse($value->created_date)->setTimeZone('Africa/Kampala')->diffForHumans()}}</span>
                    <img style="height: 40px;" src="{{ $value->getSender->getProfilePic() }}" alt="avatar">
                </div>
                <div class="message other-message float-right">{!! $value->message !!}</div>
            </li>
    
            @else
                    <li class="clearfix">
                        <div class="message-data">
                            <img style="height: 40px;" src="{{ $value->getSender->getProfilePic() }}" alt="avatar">
                            {{-- <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar"> --}}
                            <span class="message-data-time">{{ \Carbon\Carbon::parse($value->created_date)->setTimeZone('Africa/Kampala')->diffForHumans()}}</span>
                        </div>
                        <div class="message my-message">{!! $value->message !!}</div>                                    
                    </li>  
                
            @endif  
    @endforeach 