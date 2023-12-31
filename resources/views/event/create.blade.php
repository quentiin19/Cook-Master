<x-layout title="{{ __('Add an event') }}" calendar=1>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="my-auto mx-auto">
            <img src="{{ asset('images/event_create.png') }}" alt="">
        </div>
        <div class="my-auto">
            <x-utils.card-grid>
                <form action="/events"method="POST" enctype="multipart/form-data" class="card-body">
                    @csrf
                    <h2 class="card-title text-2xl flex justify-center pb-2">{{ __('Add an event') }}</h2>

                    {{-- Title --}}
                    <x-utils.input type="text" name="title" hint="{!! __('Enter the name of the event') !!}" error=1 />

                    {{-- Image --}}
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text-alt">{{ __('Event image') }} (1280x720)</span>
                        </label>
                        <input type="file" name="image"
                            class="file-input file-input-bordered border-2 w-full @error('image') border-error @enderror" />
                        <x-utils.form-error name="image" />
                    </div>

                    {{-- Room --}}
                    <div class="grid grid-cols-1 @if (Auth::user()->isAdmin()) lg:grid-cols-2 @endif gap-2">
                        {{-- Capacity --}}
                        <x-utils.input type="text" name="capacity" hint="{{ __('Capacity') }}" error="0" />

                        {{-- Organizer --}}
                        @if (Auth::user()->isAdmin())
                            <x-utils.select name="user_id">
                                <option disabled selected>{{ __('Choose the organizer') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @if (old('user_id') == $user->id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-utils.select>
                        @endif
                        <x-utils.form-error name="capacity" />
                        <x-utils.form-error name="user_id" />
                    </div>

                    {{-- Is course --}}
                    <div class="flex items-center my-4">
                        <input type="checkbox" class="checkbox checkbox-primary" name="is_course" />
                        <p class="label-text ms-2 text-md">{{ __('This event is a course') }}</p>
                    </div>

                    {{-- Description --}}
                    <textarea id="editor" class="textarea textarea-bordered border-2 @error('description') border-error @enderror" rows=4
                        name="description" placeholder="{{ __('Product description') }}">{{ old('description') }}</textarea>
                    <x-utils.form-error name="description" />

                    <div class="divider"></div>

                    {{-- Room  --}}
                    <label class="label">
                        <span class="label-text-alt">{{ __('See all rooms') }}</span>
                        <i>
                            <a href="{{ route('room.index') }}" target="_blank"
                                class="fa-solid fa-up-right-from-square">
                            </a>
                        </i>
                    </label>
                    <select class="select select-bordered w-full @error('room_id') border-2 border-error @enderror"
                        name="room_id" id="room_select">
                        <option disabled selected>{{ __('Choose the room') }}</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" @if (old('room_id') == $room->id) selected @endif>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-utils.form-error name="room_id" />

                    {{-- Calendar open button --}}
                    <label for="calendar-modal" class="btn btn-disabled" id="calendar_btn">
                        {{ __('Open event calendar') }}
                    </label>

                    {{-- Date --}}
                    <x-utils.input type="date" name="date" hint="{{ __('Date') }}" error="1" />

                    {{-- Range time --}}
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-2 pb-2">
                        {{-- Start time --}}
                        <select
                            class="select select-bordered w-full @error('start_time') border-2 border-error @enderror"
                            name="start_time" id="start-time">
                            <option disabled selected>{{ __('Choose the start time') }}</option>
                            @foreach (range(0, 23) as $item)
                                <option value="{{ str_pad($item, 2, '0', STR_PAD_LEFT) }}:00:00">
                                    {{ $item }}:00
                                </option>
                            @endforeach
                        </select>

                        {{-- End time --}}
                        <select class="select select-bordered w-full @error('end_time') border-2 border-error @enderror"
                            name="end_time" id="end-time">
                            <option disabled selected>{{ __('Choose the end time') }}</option>
                        </select>

                        <x-utils.form-error name="start_time" />
                        <x-utils.form-error name="end_time" />
                    </div>

                    {{-- Submit --}}
                    <div class="card-actions justify-center">
                        <button class="btn btn-primary w-full"> {{ __('Add event') }}</button>
                    </div>
                </form>
            </x-utils.card-grid>
        </div>
    </div>

    {{-- Calendar modal --}}
    <input type="checkbox" id="calendar-modal" class="modal-toggle" />
    <div class="modal lg:px-24 px-2">
        <div class="modal-box max-w-5xl h-full lg:h-auto w-full">
            <h3 class="font-bold text-lg pb-2">{{ __('Events planning') }}</h3>
            <div id='calendar' class="h-screen"></div>
            <div class="modal-action">
                <label for="calendar-modal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
            </div>
        </div>
    </div>

    <x-event.time />
    <x-utils.editor />

    @foreach ($events as $event)
        <x-event.modal :event="$event" />
    @endforeach

    <script>
        let calendarButton = document.querySelector('#calendar_btn');

        let select = document.querySelector('#room_select');
        select.addEventListener("change", function() {
            calendarButton.classList.remove('btn-disabled');

            let allEvents = [];
            @foreach ($events as $event)
                if ('{{ $event->room->id }}' == select.value) {
                    allEvents.push({
                        id: '{{ $event->id }}',
                        title: '{{ $event->title }}',
                        start: '{{ $event->start() }}',
                        end: '{{ $event->end() }}',
                    });
                }
            @endforeach

            var calendarElement = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarElement, {
                initialView: 'timeGridDay',
                events: allEvents,
                eventClick: function(info) {
                    document.querySelector(`#modal-${info.event.id}`).checked = true;
                },
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: window.innerWidth < 640 ? 'timeGridDay' : 'timeGridDay,timeGridWeek,dayGridMonth'
                }
            });
            calendar.setOption('locale', '{{ App::getLocale() }}');
            calendar.render();
        })
    </script>
</x-layout>
