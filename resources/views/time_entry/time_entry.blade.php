@extends('components.app')

@section('content')

<div class="container mt-5">
    <div class="text-center">
        <h1 class="time-display" id="clock">00:00:00</h1>
        <p class="date-text" id="date">February 17, 2025 <i>(Monday)</i></p>
        <button class="btn-time-in mt-3" id="btn-time-in" onclick="updateTimeIn()">AM Time In</button>
        <button class="btn-time-out mt-3 d-none" id="btn-time-out" onclick="updateTimeOut()">AM Time Out</button>
        <button class="btn-time-in-pm mt-3 d-none" id="btn-time-in-pm" onclick="updateTimeInPM()">PM Time In</button>
        <button class="btn-time-out-pm mt-3 d-none" id="btn-time-out-pm" onclick="updateTimeOutPM()">PM Time Out</button>
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
   
    function updateTime() {
        let now = new Date();
        let hours = now.getHours().toString().padStart(2, '0');  // Keep 24-hour format and pad with zero
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById("clock").innerText = `${hours}:${minutes}:${seconds}`;
    }
   

    function updateDate() { 
        let now = new Date();
        const weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let weekDay = weekDays[now.getDay()];
        let day = now.getDate();
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        let month = monthNames[now.getMonth()];
        let year = now.getFullYear();
        document.getElementById("date").innerText = `${weekDay} ${month} ${day}, ${year}`;
    }
   
    
    function formatTime(timeString) {
        if (!timeString) return '00:00 --';
        return timeString.substring(0, 5);
    }

    function getTimeEntries() {
        $.ajax({
            url: '{{ route('time_entries') }}',
            type: 'GET',
            success: function(response) {
                $('#time_entry_id').val(response.id);
                $('#am_time_in').text(formatTime(response.am_time_in));
                $('#am_time_out').text(formatTime(response.am_time_out));
                $('#pm_time_in').text(formatTime(response.pm_time_in));
                $('#pm_time_out').text(formatTime(response.pm_time_out));

                let currentHour = new Date().getHours();
                
                if (response.am_time_in) {
                    $('#btn-time-in').prop('disabled', true);
                }

                if (currentHour > 10) {
                    $('#btn-time-in').addClass('d-none');
                    $('#btn-time-out').removeClass('d-none');                
                }

                if (response.am_time_out) {
                    $('#btn-time-out').prop('disabled', true);
                }

                if (currentHour > 11) {
                    $('#btn-time-out').addClass('d-none');
                    $('#btn-time-in-pm').removeClass('d-none');
                }

                if (response.pm_time_in) {
                    $('#btn-time-in-pm').prop('disabled', true);
                }

                if (currentHour > 15) {
                    $('#btn-time-in-pm').addClass('d-none');
                    $('#btn-time-out-pm').removeClass('d-none');
                }

                if (response.pm_time_out) {
                    $('#btn-time-out-pm').prop('disabled', true);
                }   
                
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    function updateTimeIn() {
        $.ajax({
            url: '{{ route('update_time_in') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                am_time_in: $('#clock').text(),
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    function updateTimeOut() {
        $.ajax({
            url: '{{ route('update_time_out') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                am_time_out: $('#clock').text(),
                id: $('#time_entry_id').val(),
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    function updateTimeInPM() {
        $.ajax({
            url: '{{ route('update_time_in_pm') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                pm_time_in: $('#clock').text(),
                id: $('#time_entry_id').val(),
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    function updateTimeOutPM() {
        $.ajax({
            url: '{{ route('update_time_out_pm') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                pm_time_out: $('#clock').text(),
                id: $('#time_entry_id').val(),
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    setInterval(updateTime, 1000);
    updateDate();
    getTimeEntries();
    setInterval(getTimeEntries, 1000);


</script>

@endsection
