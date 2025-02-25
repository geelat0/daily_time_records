@extends('components.app')

@section('content')

<style>
    .is-invalid {
        border: 1px solid red !important;
        background-color: #ffe6e6;
    }
    .btn-warning{
        background-color: #F97316 !important;
        color: #ffff !important;
    }

    .btn-warning:hover{
        background-color: #E45D00 !important;
        color: #ffff !important;
    }

    .btn-secondary{
        background-color: transparent !important;
        color: #F97316 !important;
        border: 1px solid #F97316 !important;
    }

    .btn-secondary:hover{
        background-color: #f974162a !important;
        color: #F97316 !important;
        border: 1px solid #F97316 !important;
    }

    .text-bg-warning{
        background-color: #F97316 !important;
    }

    .custom-icon{
        color: #F97316 !important;
    }
</style>

{{-- Page Title --}}
<div class="row mb-2 mt-4">
    <div class="col-md">
        <h5 class="text-secondary">View Time Sheet</h5>
    </div>
</div>

<!-- Add Approve Attendance/Absence Modal -->
<div class="modal fade" id="addApproveAttendanceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h6 class="text-dark modal-title" id="exampleModalLabel">Add Approve Attendance/Absence Form</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="approveAttendanceForm">
                <div class="row mb-4">
                    <div class="col-md">
                        <div class="card bg-label-secondary shadow-none p-2 mb-2" id="dateRangeCardField">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-center rounded-pill text-bg-warning" id="dateRangeFieldCount">1</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <div class="mb-3 w-100">
                                    <label for="startDateApproveAttendance" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" placeholder="MM-DD-YYYY" id="startDateApproveAttendance" />
                                </div>
                                <div class="mb-3 w-100">
                                    <label for="endDateApproveAttendance" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" placeholder="MM-DD-YYYY" id="endDateApproveAttendance" />
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm shadow-none me-2" id="addDateRangeField">
                            <span class="icon-base bx bx-plus-circle me-1"></span>Add Another Date
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="attendanceTypeField" class="form-label">Attendance Type <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="attendanceTypeField">
                                <option value="" selected>Attendance Type</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="formFileMultiple" class="form-label">Upload Files <span class="text-danger">*</span></label>
                            <input class="form-control form-control-sm" type="file" id="formFileMultiple" multiple>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="remarksField" class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="remarksField" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm shadow-none" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-warning btn-sm shadow-none" id="saveChangesBtn">Save changes</button>
        </div>
      </div>
    </div>
</div>


{{-- Datatables --}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <div class="btn btn-warning btn-sm custom-btn-sm shadow-none" data-bs-toggle="modal" data-bs-target="#addApproveAttendanceModal">Add Approved Attendance/Absence</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="d-flex justify-content-end gap-2">
                    <div class="d-flex">
                        <div class="mb-3">
                            {{-- <label for="flatpickr-date" class="form-label">Date Picker</label> --}}
                            <input type="text" class="form-control form-control-sm" placeholder="Start Date" id="startDate" />
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3">
                            {{-- <label for="flatpickr-date" class="form-label">Date Picker</label> --}}
                            <input type="text" class="form-control form-control-sm" placeholder="End Date" id="endDate" />
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
    // Initialize Date Range Picker Filter
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


    // Date Range Field - Adding and Removing Field
    $(document).ready(function () {
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

        // Update field count numbers
        function updateFieldCount() {
            fieldCount = 1; // Reset count
            $(".date-range-card .date-range-count").each(function () {
                $(this).text(fieldCount++);
            });
        }
    });

    // Validation Appoval Attendance/Absence Form Modal
    $(document).ready(function () {
        $("#saveChangesBtn").click(function (e) {
            e.preventDefault(); // Prevent form submission
            let isValid = true;

            // Clear previous error messages
            $(".error-msg").remove();
            $("input, select, textarea").removeClass("is-invalid");

            // Validate Start Date
            if ($("#startDateApproveAttendance").val().trim() === "") {
                isValid = false;
                $("#startDateApproveAttendance").addClass("is-invalid")
                    .after('<div class="text-danger small mt-1 error-msg">Start Date is required</div>');
            }

            // Validate End Date
            if ($("#endDateApproveAttendance").val().trim() === "") {
                isValid = false;
                $("#endDateApproveAttendance").addClass("is-invalid")
                    .after('<div class="text-danger small mt-1 error-msg">End Date is required</div>');
            }

            // Validate Attendance Type
            if ($("#attendanceTypeField").val() === "") {
                isValid = false;
                $("#attendanceTypeField").addClass("is-invalid")
                    .after('<div class="text-danger small mt-1 error-msg">Attendance Type is required</div>');
            }

            // Validate File Upload
            if ($("#formFileMultiple").get(0).files.length === 0) {
                isValid = false;
                $("#formFileMultiple").addClass("is-invalid")
                    .after('<div class="text-danger small mt-1 error-msg">Please upload at least one file</div>');
            }

            // Validate Remarks
            if ($("#remarksField").val().trim() === "") {
                isValid = false;
                $("#remarksField").addClass("is-invalid")
                    .after('<div class="text-danger small mt-1 error-msg">Remarks are required</div>');
            }

            // If form is valid, submit (or replace with AJAX request)
            if (isValid) {
                alert("Form submitted successfully!"); // Replace with actual form submission logic
            }
        });

        // Remove error message on user input
        $("input, select, textarea").on("input change", function () {
            $(this).removeClass("is-invalid");
            $(this).next(".error-msg").remove();
        });
    });
</script>
@endsection
