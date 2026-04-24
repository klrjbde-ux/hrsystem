@extends('master')
@section('content')
<main id="main" class="main">
    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">Editable Attendance Table</h4>
                <small class="text-muted">Edit rows, add new rows, then save.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary" id="addRowBtn">Add Row</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save Changes</button>
            </div>
        </div>

        <div id="msgBox" class="alert d-none" role="alert"></div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle" id="editableAttendanceTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px;">#</th>
                                <th style="min-width:240px;">Employee</th>
                                <th style="min-width:140px;">Status</th>
                                <th style="min-width:150px;">Date</th>
                                <th style="min-width:140px;">Check-in</th>
                                <th style="min-width:140px;">Check-out</th>
                                <th style="width:90px;">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="rowsBody">
                            @foreach($attendances as $row)
                                <tr data-row>
                                    <td class="text-muted">
                                        {{ $loop->iteration }}
                                        <input type="hidden" class="row-id" value="{{ $row->id }}">
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm employee-id" required>
                                            <option value="">Select...</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}" {{ $row->employee_id == $emp->id ? 'selected' : '' }}>
                                                    {{ $emp->firstname }} {{ $emp->lastname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm status" required>
                                            <option value="present" {{ $row->status == 'present' ? 'selected' : '' }}>Present</option>
                                            <option value="absent" {{ $row->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm the-date"
                                               value="{{ \Carbon\Carbon::parse($row->date)->format('Y-m-d') }}"
                                               max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control form-control-sm time-in"
                                               value="{{ $row->first_time_in ? \Carbon\Carbon::parse($row->first_time_in)->format('H:i') : '' }}" required>
                                    </td>
                                    <td>
                                        <input type="time" class="form-control form-control-sm time-out"
                                               value="{{ $row->last_time_out ? \Carbon\Carbon::parse($row->last_time_out)->format('H:i') : '' }}">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">X</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const csrfToken = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || "{{ csrf_token() }}";
  const saveUrl = "{{ route('attendance.editable.save') }}";
  const deleteBaseUrl = "{{ url('/attendance/editable') }}";

  const rowsBody = document.getElementById('rowsBody');
  const msgBox = document.getElementById('msgBox');
  const addRowBtn = document.getElementById('addRowBtn');
  const saveBtn = document.getElementById('saveBtn');
  if (!rowsBody || !msgBox || !addRowBtn || !saveBtn) {
    // If master layout renders scripts early, avoid runtime errors.
    return;
  }

  function showMsg(type, text) {
    msgBox.className = `alert alert-${type}`;
    msgBox.textContent = text;
    msgBox.classList.remove('d-none');
    setTimeout(() => msgBox.classList.add('d-none'), 5000);
  }

  function renumber() {
    const trs = rowsBody.querySelectorAll('tr[data-row]');
    trs.forEach((tr, idx) => {
      const firstCell = tr.querySelector('td');
      if (!firstCell) return;
      // keep hidden input intact; set text node safely
      const textNode = Array.from(firstCell.childNodes).find(n => n.nodeType === Node.TEXT_NODE);
      if (textNode) textNode.textContent = ` ${idx + 1} `;
    });
  }

  function newRowHtml() {
    return `
      <tr data-row>
        <td class="text-muted">
          *
          <input type="hidden" class="row-id" value="">
        </td>
        <td>
          <select class="form-select form-select-sm employee-id" required>
            <option value="">Select...</option>
            @foreach($employees as $emp)
              <option value="{{ $emp->id }}">{{ $emp->firstname }} {{ $emp->lastname }}</option>
            @endforeach
          </select>
        </td>
        <td>
          <select class="form-select form-select-sm status" required>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
          </select>
        </td>
        <td>
          <input type="date" class="form-control form-control-sm the-date"
                 max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
        </td>
        <td>
          <input type="time" class="form-control form-control-sm time-in" required>
        </td>
        <td>
          <input type="time" class="form-control form-control-sm time-out">
        </td>
        <td>
          <button type="button" class="btn btn-sm btn-outline-danger remove-row">X</button>
        </td>
      </tr>
    `;
  }

  addRowBtn.addEventListener('click', () => {
    rowsBody.insertAdjacentHTML('beforeend', newRowHtml());
    renumber();
  });

  rowsBody.addEventListener('click', (e) => {
    const btn = e.target.closest('.remove-row');
    if (!btn) return;
    const tr = btn.closest('tr');
    const id = tr?.querySelector('.row-id')?.value;

    // New unsaved row: remove only from UI
    if (!id) {
      tr?.remove();
      renumber();
      showMsg('info', 'Row removed.');
      return;
    }

    // Existing row: delete immediately from DB
    fetch(`${deleteBaseUrl}/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
    })
      .then(async (res) => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          showMsg('danger', data?.message || 'Delete failed.');
          return;
        }
        tr?.remove();
        renumber();
        showMsg('success', data?.message || 'Row deleted successfully.');
      })
      .catch(() => showMsg('danger', 'Network error while deleting.'));
  });

  saveBtn.addEventListener('click', async () => {
    const rows = Array.from(rowsBody.querySelectorAll('tr[data-row]')).map(tr => ({
      id: tr.querySelector('.row-id')?.value || null,
      employee_id: tr.querySelector('.employee-id')?.value || null,
      status: tr.querySelector('.status')?.value || null,
      date: tr.querySelector('.the-date')?.value || null,
      time_in: tr.querySelector('.time-in')?.value || null,
      time_out: tr.querySelector('.time-out')?.value || null,
    }));

    const bad = rows.find(r => !r.employee_id || !r.status || !r.date || !r.time_in);
    if (bad) return showMsg('warning', 'Please fill Employee, Status, Date, and Check-in for all rows.');

    try {
      const res = await fetch(saveUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ rows }),
      });

      const data = await res.json().catch(() => ({}));
      if (!res.ok) return showMsg('danger', data?.message || 'Save failed.');

      showMsg('success', data?.message || 'Saved successfully.');
      if (data?.refresh) window.location.reload();
    } catch (err) {
      showMsg('danger', 'Network error while saving.');
    }
  });
});
</script>
@endsection

