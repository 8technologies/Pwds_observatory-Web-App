<div>
    <!-- start modal -->
    <div wire:ignore.self
        class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white w-full md:max-w-3xl mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <!-- start close button -->
            <div
                class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                    </path>
                </svg>
                <span class="text-sm">(Esc)</span>
            </div>
            <!-- end close button -->
            <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between pb-3">
                    <div class="w-full">
                        <input wire:model.debounce.300ms="search" type="text"
                            class="search-input w-full rounded-lg px-4 py-3" placeholder="Search for anything...">
                    </div>
                </div>
                <!-- show results here using a grid list -->
                <div class="result-list-container">
                    <ul>
                        {{-- the results an array of objects --}}
                        @if (count($results) > 0)
                            <!-- show the news results -->
                            @if ($results['news']->count())
                                @foreach ($results['news'] as $result)
                                    @once
                                        <li>
                                            <h3 class="h3">News</h3>
                                        </li>
                                    @endonce
                                    <li>
                                        <a href="{{url('news/'.$result->id)}}">
                                            <div class="list-item-content">
                                                <img src="{{ asset('storage/' . $result->photo) }}" alt=""
                                                    class="item-photo">
                                                <div class="item-text">
                                                    <h3 class="item-title">{{ $result->title }}</h3>
                                                    <p class="item-description">{!! Str::words($result->description, 10, '...') !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                                <hr>
                            @endif

                            <!-- show the resources results -->
                            @if ($results['resources']->count())
                                @foreach ($results['resources'] as $result)
                                    @once
                                        <li>
                                            <h3 class="h3">Resources</h3>
                                        </li>
                                    @endonce
                                    <li>
                                        <a href="{{url('resources/'.$result->id)}}">
                                            <div class="list-item-content">
                                                <img src="{{ asset('storage/' . $result->cover_photo) }}" alt=""
                                                    class="item-photo">
                                                <div class="item-text">
                                                    <h3 class="item-title">{{ $result->title }}</h3>
                                                    <p class="item-description">{!! Str::words($result->description, 10,'...') !!} <p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif

                            <!-- show the events results -->
                            @if ($results['events']->count())
                                @foreach ($results['events'] as $result)
                                    @once
                                        <li>
                                            <h3 class="h3">Events</h3>
                                        </li>
                                    @endonce
                                    <li>
                                        <a href="{{ url('events/'.$result->id)}}">
                                            <div class="list-item-content">
                                                <img src="{{ asset('storage/' . $result->photo) }}" alt=""
                                                    class="item-photo">
                                                <div class="item-text">
                                                    <h3 class="item-title">{{ $result->title }}</h3>
                                                    <p class="item-description">{!! Str::words($result->details,10,'...') !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif

                            <!-- show service providers -->
                            @if ($results['service_providers']->count())
                                @foreach ($results['service_providers'] as $result)
                                    @once
                                        <li>
                                            <h3 class="h3">Service Providers</h3>
                                        </li>
                                    @endonce
                                    <li>
                                        <a href="{{url('service-providers/'.$result->id)}}">
                                            <div class="list-item-content">
                                                <img src="{{ asset('storage/' . $result->photo) }}" alt=""
                                                    class="item-photo">
                                                <div class="item-text">
                                                    <h3 class="item-title">{{ $result->name }}</h3>
                                                    <p class="item-description">{!! Str::words($result->services_offered,10,'...') !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif

                            <!-- show the innovations results -->
                            @if ($results['innovations']->count())
                                @foreach ($results['innovations'] as $result)
                                    @once
                                        <li>
                                            <h3 class="h3">Innovations</h3>
                                        </li>
                                    @endonce
                                    <li>
                                        <a href="{{url('innovations/'.$result->id)}}">
                                            <div class="list-item-content">
                                                <img src="{{ asset('storage/' . $result->photo) }}" alt=""
                                                    class="item-photo">
                                                <div class="item-text">
                                                    <h3 class="item-title">{{ $result->title }}</h3>
                                                    <p class="item-description">{!! Str::words($result->description,10,'...') !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif

                            <!-- show the disability types results -->
                            @if ($results['disabilities']->count())
                                @foreach ($results['disabilities'] as $result)
                                    @once
                                        <li>
                                            <h3 class="h3">Disabilities</h3>
                                        </li>
                                    @endonce
                                    <li>
                                        <a href="{{url('disabilities/'. $result->id)}}">
                                            <div class="list-item-content">
                                                <img src="{{ asset('storage/' . $result->photo) }}" alt=""
                                                    class="item-photo">
                                                <div class="item-text">
                                                    <h3 class="item-title">{{ $result->name }}</h3>
                                                    <p class="item-description">{!! Str::words($result->description,10,'...') !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif

                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
</div>
