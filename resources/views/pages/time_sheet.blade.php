@extends('components.app')

@section('content')

{{-- Page Title --}}
<div class="row mb-2 mt-4">
    <div class="col-md">
        <h5 class="text-secondary">View Time Sheet</h5>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="col-md-12 d-flex align-items-center gap-2">
            <div class="mb-3">
                <div for="flatpickr-date" class="flatpickr-date">Start Date</div>
                <input type="text" class="form-control form-control-sm flatpickr-date-filter" placeholder="YYYY-MM-DD" id="startDate"/>
            </div>
            <Label class="fw-medium flatpickr-date">&nbsp; to &nbsp;</Label>
            <div class="mb-3">
                <div for="flatpickr-date" class="flatpickr-date">End Date</div>
                <input type="text" class="form-control form-control-sm flatpickr-date-filter" placeholder="YYYY-MM-DD" id="endDate"/>
            </div>
        </div>
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
                                    <label for="startDateApproveAttendance" class="form-label required">Start Date </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="MM-DD-YYYY" id="startDateApproveAttendance" name="start_date" />
                                </div>
                                <div class="mb-3 w-100">
                                    <label for="endDateApproveAttendance" class="form-label required">End Date </label>
                                    <input type="text" class="form-control form-control-sm" placeholder="MM-DD-YYYY" id="endDateApproveAttendance" name="end_date"/>
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
                            <label for="attendanceTypeField" class="form-label required">Attendance Type </label>
                            <select class="form-select form-select-sm" id="attendanceTypeField" name="attendance_type">
                                <option value="" selected>Attendance Type</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="formFileMultiple" class="form-label required">Upload Files </label>
                            <input class="form-control form-control-sm" type="file" id="formFileMultiple" name="file">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="remarksField" class="form-label required">Remarks </label>
                            <textarea class="form-control" id="remarksField" rows="3" name="remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm shadow-none" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning btn-sm shadow-none" id="saveChangesBtn">Save changes</button>
                </div>
                <input class="form-control form-control-sm d-none" type="text" multiple name="user_id" value="1">
            </form>
        </div>
       
      </div>
    </div>
</div>

{{-- Datatables --}}
<div class="card">
    <div class="card-body">
       
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

{{$dataTable->scripts()}}

<script>
    
    // Reload DataTable on date change
    $('#startDate, #endDate').change(function() {
        $('#timesheetTable').DataTable().draw();
    });

    // Fetch data from API and populate select attendance type options
    getAttendanceType();

    // Date Range Field - Adding and Removing Field
    addDateRangeField();
    
    //Save approved attendance/absence
    $(document).ready(function () {
        
        $('#approveAttendanceForm').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            let startDate = $('#startDateApproveAttendance').val();
            let endDate = $('#endDateApproveAttendance').val();
            let dateRange = generateDateRange(startDate, endDate);
            formData.append('dates', JSON.stringify(dateRange));

            $("input, select, textarea").on("input change", function () {
                $(this).removeClass("is-invalid");
                $(this).next(".error-msg").remove();
            });

            $.ajax({
                url: '{{ route('store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    displayValidationErrors(errors);
                }
            });
        });
        
    })
</script>

@endsection
