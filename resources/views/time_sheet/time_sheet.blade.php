@extends('components.app')

@section('content')
{{-- Datatables --}}
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex w-50 gap-2">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" id="date-range-picker" class="form-control" placeholder="Select date range">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="timesheet-table" class="table table-hover">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('components.specific_page_scripts')

<script>
       // Initialize DataTable
       const tableTimeSheet = $('#timesheet-table').DataTable({
            processing: false,
            serverSide: false,
            searching: false,
            ajax: {
                url: '',
                data: function(d) {
                    // Include the date range in the AJAX request
                    d.date_range = $('#date-range-picker').val();
                },
            },
            ordering: false,
            lengthMenu: [ [10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"] ],
            pageLength: 10,
            columns: [
                { data: 'Day', name: 'Day', title: 'Date' },
                { data: 'time_in', name: 'time_in', title: 'AM Time In' },
                { data: 'time_out', name: 'time_out', title: 'AM Time Out' },
                { data: 'break_in', name: 'break_in', title: 'PM Time In' },
                { data: 'break_out', name: 'break_out', title: 'PM Time Out' },
                { data: 'rendered_hours', name: 'rendered_hours', title: 'Rendered Hours' },
                { data: 'excess_minutes', name: 'excess_minutes', title: 'Excess Minutes' },
                { data: 'late', name: 'late', title: 'Late' },
                { data: 'status', name: 'status', title: 'Remarks' },
                {
                    data: 'id',
                    title: 'Actions',
                    render: function(data) {
                        return `
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-warning edit-btn" data-id="${data}">
                                    Edit
                                </button>
                                
                            </div>
                        `;
                    }
                }
            ],
        });
</script>
@endsection
