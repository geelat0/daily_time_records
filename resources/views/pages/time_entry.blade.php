@extends('components.app')

@section('content')

<div class="container mt-5">
    <div class="text-center">
        <h1 class="time-display" id="clock">00:00</h1>
        <p class="date-text" id="date"></i></p>
        <button class="btn-time-in mt-3" id="btn-time-in" onclick="updateTimeIn()">AM Time In</button>
        <button class="btn-time-out mt-3" id="btn-time-out" onclick="updateTimeOut()">AM Time Out</button>
        <button class="btn-time-in-pm mt-3" id="btn-time-in-pm" onclick="updateTimeInPM()">PM Time In</button>
        <button class="btn-time-out-pm mt-3" id="btn-time-out-pm" onclick="updateTimeOutPM()">PM Time Out</button>
    </div>

    <h5 class="mt-4">Today's Record</h5>
    <div class="row mt-3">
        <input type="hidden" id="time_entry_id" name="time_entry_id">
        <div class="col-md-6">
            <div class="record-card">
                <h3 id="am_time_in">00:00 --</h3>
                <p>AM Time-In</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="record-card">
                <h3 id="am_time_out">00:00 --</h3>
                <p>AM Time-Out</p>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="record-card">
                <h3 id="pm_time_in">00:00 --</h3>
                <p>PM Time-In</p>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="record-card">
                <h3 id="pm_time_out">00:00 --</h3>
                <p>PM Time-Out</p>
            </div>
        </div>
    </div>
</div>


@endsection

@section('components.specific_page_scripts')

<script>
    
    checkShiftSchedule();
    setInterval(updateTime, 1000);
    updateDate();
    getTimeEntries();

</script>

@endsection
