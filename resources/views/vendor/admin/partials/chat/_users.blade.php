@php
    // gets null if no ?receiver_id in the URL
    $activeOrg = request()->query('receiver_id');
@endphp
@foreach($getChatUser as $user)
<li class="clearfix getChatWindows {{ (int)$activeOrg === $user['organisation_id'] ? 'active' : '' }}"
      id="{{ $user['organisation_id'] }}">
    {{-- <a href="{{ admin_url('chat?receiver_id=' . $user['organisation_id']) }}"> --}}
        <img style="height: 45px;" src="{{ $user['profile_photo'] }}" alt="avatar">
        <div class="about">
            <div class="name">
                {{ $user['name'] }}
                @if(! empty($user['messagecount']))
                    <span id="ClearMessage{{ $user['organisation_id'] }}" style="
                        background: green;
                        color: #fff;
                        border-radius: 5px;
                        padding: 1px 7px;
                    ">
                        {{ $user['messagecount'] }}
                    </span>
                @endif
            </div>
            <div class="status">
                <i class="fa fa-circle offline"></i>
                {{ \Carbon\Carbon::parse($user['created_date'])
                    ->setTimezone('Africa/Kampala')
                    ->diffForHumans() }}
            </div>
        </div>
 {{-- </a> --}}
</li>
@endforeach
