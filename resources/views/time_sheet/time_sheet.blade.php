@extends('components.app')

@section('content')

{{-- Page Title --}}
<div class="row mb-2 mt-4">
    <div class="col-md">
        <h5 class="text-secondary">View Time Sheet</h5>
    </div>
</div>

{{-- Datatables --}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <div class="btn btn-warning btn-sm custom-btn-sm shadow-none">Add Approved Attendance/Absence</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="d-flex justify-content-end gap-2">
                    <div class="d-flex">
                        <div class="mb-3">
                            {{-- <label for="flatpickr-date" class="form-label">Date Picker</label> --}}
                            <input type="text" class="form-control form-control-sm" placeholder="Select Start Date" id="startDate" />
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3">
                            {{-- <label for="flatpickr-date" class="form-label">Date Picker</label> --}}
                            <input type="text" class="form-control form-control-sm" placeholder="Select End Date" id="endDate" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="table-responsive">
                    <table id="timesheetTable" class="table table-bordered table-hover">
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('components.specific_page_scripts')

<script>
    // Initialize Date Range Picker
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
       // Initialize DataTable
    const tableTimeSheet = $('#timesheetTable').DataTable({
        processing: false,
        serverSide: false,
        searching: false,
        ajax: {
            data: function(d) {
                // Include the date range in the AJAX request
                d.start_date = $('#startDate').val();
                d.end_date = $('#endDate').val();
            },
        },
        ordering: false,
        lengthMenu: [ [10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"] ],
        pageLength: 10,
        columns: [
            { data: 'id', name: 'id', title: 'ID', visible: false },
            { data: 'Day', name: 'Day', title: 'Date' },
            { data: 'am_time_in', name: 'am_time_in', title: 'AM Time In' },
            { data: 'am_time_out', name: 'am_time_out', title: 'AM Time Out' },
            { data: 'pm_time_in', name: 'pm_time_in', title: 'PM Time In' },
            { data: 'pm_time_out', name: 'pm_time_out', title: 'PM Time Out' },
            { data: 'late_hours', name: 'late_hours', title: 'Late' },
            { data: 'rendered_hours', name: 'rendered_hours', title: 'Rendered Hours' },
            { data: 'excess_minutes', name: 'excess_minutes', title: 'Excess Minutes' },
            { data: 'remarks', name: 'remarks', title: 'Remarks' },
            { 
                data: 'attachments', 
                name: 'attachments', 
                title: 'Attachments', 
                render: function(data, type, row) {
                    return `<a href="/return/${row.id}" class="text-decoration-underline fst-italic">File Name Here</a>`;
                } 
            },
            { 
                data: 'id', 
                name: 'actions', 
                title: 'Action', 
                render: function(data, type, row) {
                    return `<a href="/return/${row.id}" class="text-decoration-underline fst-italic custom-text">Edit</a>`;
                } 
            },
            
        ],
    });

         // Reload DataTable on date change
    $('#startDate, #endDate').change(function() {
        tableTimeSheet.ajax.reload();
    });
</script>
@endsection
