@extends('components.app')

@section('content')

{{-- Page Title --}}
<div class="row mb-2 mt-4">
    <div class="col-md">
        <h5 class="text-secondary">Setting</h5>
    </div>
</div>

{{-- Datatables --}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md">
                <div class="btn btn-warning btn-sm custom-btn-sm shadow-none">Manage Schedule</div>
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
                    <table id="settingTable" class="table table-bordered table-hover">                       
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
        $('#settingTable').DataTable().draw();
    });
</script>

@endsection
