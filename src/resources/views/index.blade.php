@extends('activity-log::layouts.app')

@section('content')
  <div class="container mt-4">
    <h2>Activity Logs for {{ $date }}</h2>

    <form method="GET" class="mb-3">
      <div class="row g-2">
        <div class="col-auto">
          <input type="date" name="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="col-auto">
          <input type="text" name="search" class="form-control" placeholder="Search Model/Action/User ID/Record ID" value="{{ $search ?? '' }}">
        </div>
        <div class="col-auto">
          <select name="action" class="form-select">
            <option value="">All Actions</option>
            <option value="created" {{ ($action ?? '') == 'created' ? 'selected' : '' }}>Created</option>
            <option value="updated" {{ ($action ?? '') == 'updated' ? 'selected' : '' }}>Updated</option>
            <option value="deleted" {{ ($action ?? '') == 'deleted' ? 'selected' : '' }}>Deleted</option>
          </select>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Filter</button>
        </div>
      </div>
    </form>

    @if(count($logs) > 0)
      <div class="table-responsive">
        <table id="activity-log-table" class="table table-bordered table-striped">
          <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Model / Action <br>Time <br>IP</th>
            <th>Record ID</th>
            <th>User ID</th>
            <th>Data / Changes</th>
          </tr>
          </thead>
          <tbody>
          @foreach($logs as $index => $log)
            @php
              $bgColor = '';
              if(($log['action'] ?? '') == 'created') $bgColor = 'table-success';
              if(($log['action'] ?? '') == 'updated') $bgColor = 'table-warning';
              if(($log['action'] ?? '') == 'deleted') $bgColor = 'table-danger';
            @endphp
            <tr class="{{ $bgColor }}">
              <td>{{ $index + 1 }}</td>
              <td>{{ ($log['model'] ?? '') . ' / ' . ($log['action'] ?? '') }}<br>{{ \Carbon\Carbon::parse($log['time'])->format('d M Y, h:i A') }}<br>{{ $log['ip'] }}</td>
              <td>{{ $log['id'] ?? 'N/A' }}</td>
              <td>{{ $log['user_id'] ?? 'N/A' }}</td>
              <td>
                <pre style="margin:0;">{{ json_encode($log['data'] ?? $log['changes'] ?? [], JSON_PRETTY_PRINT) }}</pre>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="alert alert-info">No logs found for this date or search criteria.</div>
    @endif
  </div>
@endsection

@section('scripts')
  <!-- Include jQuery and DataTables -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#activity-log-table').DataTable({
        "order": [[ 0, "desc" ]],   // default sort by #
        "pageLength": 25,
        "lengthMenu": [10, 25, 50, 100],
        "responsive": true
      });
    });
  </script>
@endsection
