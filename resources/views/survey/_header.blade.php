<div class="header">
    <div class="logo"><img src="{{ asset('images/logors.png') }}?v={{ time() }}" alt="Logo"></div>
    <div class="area-info">
        <h4><strong id="areaName">{{ $survey->location->name ?? '' }}</strong></h4>
    </div>
    <div class="datetime" id="dateTime"></div>
</div>
