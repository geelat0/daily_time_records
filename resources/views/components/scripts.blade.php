<!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/vendor/libs/select2/select2.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js" />
    // <script src="assets/vendor/libs/apex-charts/apexcharts.js"><script>

    <!-- CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>


    <script>

        // Floating Side Bar
       
        var flatpickrDate = document.querySelector("#startDate");

        flatpickrDate.flatpickr({
        monthSelectorType: "static",
        static: true
        });

        var flatpickrDate = document.querySelector("#endDate");

        flatpickrDate.flatpickr({
        monthSelectorType: "static",
        static: true
        });

        function checkShiftSchedule(){
            $.ajax({
                url: '{{ route('check_shift_schedule') }}',
                type: 'GET',
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        checkShiftSchedule();

        function updateTime() {
            let now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');  // Keep 24-hour format and pad with zero
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById("clock").innerText = `${hours}:${minutes}`;
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

                    // if (currentHour > 10) {
                    //     $('#btn-time-in').addClass('d-none');
                    //     $('#btn-time-out').removeClass('d-none');                
                    // }

                    if (response.am_time_out) {
                        $('#btn-time-out').prop('disabled', true);
                    } 

                    // if (currentHour > 11) {
                    //     $('#btn-time-out').addClass('d-none');
                    //     $('#btn-time-in-pm').removeClass('d-none');
                    // }

                    if (response.pm_time_in) {
                        $('#btn-time-in-pm').prop('disabled', true);
                    }

                    // if (currentHour > 14) {
                    //     $('#btn-time-in-pm').addClass('d-none');
                    //     $('#btn-time-out-pm').removeClass('d-none');
                    // }

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
                    getTimeEntries();
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
                    getTimeEntries();

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
                    getTimeEntries();

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
                    getTimeEntries();

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function getAttendanceType() {
            $.ajax({
                url: '{{ route('get_attendance_tyoe') }}',
                type: "GET",
                success: function (response) {
                    // Populate select options
                    $.each(response, function (index, item) {
                        $("#attendanceTypeField").append(`<option value="${item.id}">${item.type}</option>`);
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
            
        }

        function addDateRangeField() {
            let fieldCount = 1; // Initial count

            // Add new date range field
            $("#addDateRangeField").click(function () {
                fieldCount++;

                let newField = `
                <div class="card bg-label-secondary shadow-none p-2 mb-2 date-range-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge badge-center rounded-pill text-bg-warning date-range-count">${fieldCount}</span>
                        <button type="button" class="btn btn-icon btn-sm me-2 btn-label-danger removeDateRangeField">
                            <span class="icon-base bx bx-trash"></span>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <div class="mb-3 w-100">
                            <label class="form-label">Start Date</label>
                            <input type="text" class="form-control form-control-sm dynamic-date-range-field" placeholder="MM-DD-YYYY" />
                        </div>
                        <div class="mb-3 w-100">
                            <label class="form-label">End Date</label>
                            <input type="text" class="form-control form-control-sm dynamic-date-range-field" placeholder="MM-DD-YYYY" />
                        </div>
                    </div>
                </div>`;

                // Append new field before the "Add Another Date" button
                $(newField).insertBefore("#addDateRangeField");
            });

            // Remove date range field
            $(document).on("click", ".removeDateRangeField", function () {
                $(this).closest(".date-range-card").remove();
                updateFieldCount();
            });

        }

        function updateFieldCount() {
            fieldCount = 1; // Reset count
            $(".date-range-card .date-range-count").each(function () {
                $(this).text(fieldCount++);
            });
        }

        function generateDateRange(startDate, endDate) {
            let dates = [];
            let currentDate = new Date(startDate);

            while (currentDate <= new Date(endDate)) {
                dates.push(currentDate.toISOString().split('T')[0]);
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dates;
        }

        function displayValidationErrors(errors) {
            // Clear previous error messages
            $(".error-msg").remove();
            $("input, select, textarea").removeClass("is-invalid");

            // Display error messages
            for (let field in errors) {
                let input = $(`[name="${field}"]`);
                input.addClass("is-invalid");
                input.after(`<div class="text-danger small mt-1 error-msg">${errors[field][0]}</div>`);
            }
        }


        // JS for Date Range Picker for Approve Attendance Form
        $(document).ready(function () {
            $("#startDateApproveAttendance, #endDateApproveAttendance").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: true,
                enableTime: false,  // Disable time selection
                altInput: true,     // Display a readable date
                altFormat: "F j, Y", // Example: February 23, 2025
            });
        });

        // JS for Dynamic Date Range Field for Approve Attendance Form
        $(document).on("focus", ".dynamic-date-range-field", function () {
            if (!$(this).hasClass("flatpickr-initialized")) {
                $(this).addClass("flatpickr-initialized");
                $(this).flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    enableTime: false,  // Disable time selection
                    altInput: true,     // Display a readable date
                    altFormat: "F j, Y", // Example: February 23, 2025
                });
            }
        });

    </script>




