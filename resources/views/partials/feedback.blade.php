<!-- Flash messages -->
@if (session('flash_notification', collect())->count() > 0)

    <div id="feedback-wrapper" @if(isset($extraMargin) && $extraMargin) class="extra-margin" @endif>
        @foreach (session('flash_notification', collect())->toArray() as $message)
            <div class="elevation-1 alert alert-{{ $message['level'] }} {{ $message['important'] ? 'alert-important' : '' }}" role="alert">
                {!! $message['message'] !!}
            </div>
        @endforeach
    </div>
    
    <!-- Forget the message so it disappears (on the next request) after being shown -->
    {{ session()->forget('flash_notification') }}

@endif