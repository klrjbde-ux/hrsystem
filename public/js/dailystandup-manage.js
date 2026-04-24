$(function() {
    // Parse the employees list from data attribute
    let employeesList = [];
    try {
        const employeesData = $('#meetings-table').data('employees-list');
        if (typeof employeesData === 'string') {
            employeesList = JSON.parse(employeesData);
        } else {
            employeesList = employeesData || [];
        }
        console.log('Employees loaded:', employeesList); // Debug log
    } catch (e) {
        console.error('Error parsing employees list:', e);
        employeesList = [];
    }

    const meetingsTable = $('#meetings-table').DataTable({
        ajax: {
            url: $('#meetings-table').data('ajax-url'),
            dataSrc: 'data',
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX error:', error, thrown);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [{
                data: 'row_no',
                orderable: false,
                render: function(data, type, row) {
                    if (type === 'display' && row.id === 'new') {
                        return '<span class="text-muted">New</span>';
                    }
                    return data;
                }
            },
            {
                data: 'date_display',
                orderable: true,
                render: function(val, type, row) {
                    if (type === 'display') {
                        return '<span class="editable-display">' + (val || '—') + '</span>';
                    }
                    return val;
                }
            },
            {
                data: 'employee_name',
                orderable: true,
                render: function(val, type, row) {
                    if (type === 'display') {
                        return '<span class="editable-display">' + (val || '—') + '</span>';
                    }
                    return val;
                }
            },
            {
                data: 'status',
                orderable: true,
                render: function(val, type, row) {
                    if (type === 'display') {
                        return '<span class="editable-display">' + (val || '—') + '</span>';
                    }
                    return val;
                }
            },
            {
                data: 'remarks',
                orderable: false,
                render: function(val, type, row) {
                    if (type === 'display') {
                        return '<span class="editable-display">' + (val || '—') + '</span>';
                    }
                    return val;
                }
            },
            {
                data: null,
                orderable: false,
                render: function(row) {
                    if (row.id === 'new') {
                        return '<button type="button" class="btn btn-success btn-sm btn-save-new">Save</button>';
                    }
                    return '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' + row.id + '">Delete</button>';
                }
            }
        ],
        order: [
            [1, 'desc']
        ],
        pageLength: 10,
        createdRow: function(tr, row) {
            $(tr).attr('data-id', row.id || 'new')
                .attr('data-date', row.date || '')
                .attr('data-employee-id', row.employee_id || '')
                .attr('data-employee-name', row.employee_name || '')
                .attr('data-status', row.status || '')
                .attr('data-remarks', (row.remarks || ''));

            // Add editable class to all cells except first and last
            $(tr).find('td:not(:first-child):not(:last-child)').addClass('editable-cell');

            // Add special styling for new rows
            if (row.id === 'new') {
                $(tr).addClass('table-info');
            }
        }
    });

    // Add new row button functionality
    $('#addNewRowBtn').on('click', function() {
        const today = new Date().toISOString().split('T')[0];
        const todayDisplay = new Date().toLocaleDateString('en-GB', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });

        const newRowData = {
            id: 'new',
            row_no: 'New',
            date: today,
            date_display: todayDisplay,
            employee_id: '',
            employee_name: '',
            status: '',
            remarks: ''
        };

        // Add row to DataTable
        meetingsTable.row.add(newRowData).draw(false);

        // Scroll to the new row
        const $newRow = meetingsTable.row(':last').node();
        $($newRow).find('td:eq(1)').click(); // Auto-open date editor for first field
    });

    function getRowData($tr) {
        return {
            id: $tr.attr('data-id'),
            date: $tr.attr('data-date'),
            employee_id: $tr.attr('data-employee-id'),
            employee_name: $tr.attr('data-employee-name'),
            status: $tr.attr('data-status'),
            remarks: $tr.attr('data-remarks') || ''
        };
    }

    function setRowData($tr, data) {
        $tr.attr('data-date', data.date || '');
        $tr.attr('data-employee-id', data.employee_id || '');
        $tr.attr('data-employee-name', data.employee_name || '');
        $tr.attr('data-status', data.status || '');
        $tr.attr('data-remarks', data.remarks || '');
    }

    function showSavedPopup(msg) {
        msg = msg || 'Saved.';
        if (typeof toastr !== 'undefined') toastr.success(msg);
        else alert(msg);
    }

    function showErrorPopup(msg) {
        if (typeof toastr !== 'undefined') toastr.error(msg);
        else alert(msg);
    }

    function saveRow($tr, extra, skipAutoSave = false) {
        var data = getRowData($tr);
        if (extra) {
            if (extra.date !== undefined) data.date = extra.date;
            if (extra.employee_id !== undefined) data.employee_id = extra.employee_id;
            if (extra.employee_name !== undefined) data.employee_name = extra.employee_name;
            if (extra.status !== undefined) data.status = extra.status;
            if (extra.remarks !== undefined) data.remarks = extra.remarks;
            setRowData($tr, data);
        }

        // Validate required fields before saving
        if (!data.date) {
            showErrorPopup('Date is required');
            return false;
        }
        if (!data.employee_id) {
            showErrorPopup('Employee is required');
            return false;
        }
        if (!data.status) {
            showErrorPopup('Status is required');
            return false;
        }

        // Determine if it's a new row or existing row
        const isNewRow = data.id === 'new';

        // IMPORTANT: Only auto-save existing rows, not new rows
        if (isNewRow && skipAutoSave) {
            // For new rows, don't auto-save - wait for Save button click
            return true;
        }

        // Use different URLs for new and existing rows
        const url = isNewRow ? $('#meetings-table').data('store-url') : $('#meetings-table').data('update-url');

        // For new rows, don't send an ID
        // For existing rows, send the ID
        const requestData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            date: data.date,
            employee_id: data.employee_id,
            status: data.status,
            remarks: data.remarks
        };

        // Only add ID for existing rows
        if (!isNewRow) {
            requestData.id = data.id;
        }

        console.log('Saving row:', { isNewRow, url, requestData }); // Debug log

        $.ajax({
            url: url,
            type: 'POST',
            data: requestData,
            dataType: 'json',
            success: function(res) {
                console.log('Save response:', res); // Debug log
                if (res.success) {
                    showSavedPopup(res.message);

                    // If this was a new row that was just saved, update its ID in the UI
                    if (isNewRow && res.meeting && res.meeting.id) {
                        $tr.attr('data-id', res.meeting.id);
                        $tr.removeClass('table-info');

                        // Change the action button from Save to Delete
                        const actionCell = $tr.find('td:last');
                        actionCell.html('<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' + res.meeting.id + '">Delete</button>');
                    }

                    meetingsTable.ajax.reload(null, false); // Reload from server
                } else {
                    showErrorPopup(res.message || 'Operation failed');
                }
            },
            error: function(xhr, status, error) {
                console.error('Save error:', { xhr, status, error }); // Debug log
                console.error('Response text:', xhr.responseText); // Debug log

                let errorMsg = 'Operation failed.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        // Handle validation errors
                        errorMsg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                    } else if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                }
                showErrorPopup(errorMsg);
            }
        });

        return true;
    }

    // Save new row button click handler
    $(document).on('click', '.btn-save-new', function(e) {
        e.stopPropagation();
        const $tr = $(this).closest('tr');
        const rowData = getRowData($tr);

        // Validate before saving
        if (!rowData.date || !rowData.employee_id || !rowData.status) {
            showErrorPopup('Please fill in all required fields (Date, Employee, Status)');
            return;
        }

        // Save the new row (this will use the store URL)
        saveRow($tr, null, false);
    });

    // Enhanced editable cell click handler
    $(document).on('click', '#meetings-table td.editable-cell', function(e) {
        if ($(this).find('input, select, textarea').length) return;
        var $td = $(this);
        var $tr = $td.closest('tr');
        var colIdx = $td.index();
        var row = getRowData($tr);
        var isNewRow = row.id === 'new';

        if (colIdx === 1) {
            var originalDate = row.date || '';
            var currentDate = originalDate || new Date().toISOString().split('T')[0];
            var $input = $('<input type="date" class="form-control form-control-sm">').val(currentDate);
            $td.empty().append($input);
            $input.focus();
            $input.on('blur', function() {
                var val = $input.val() || '';
                $tr.attr('data-date', val);
                var disp = val ? new Date(val).toLocaleDateString('en-GB', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                }) : '—';
                $td.empty().html('<span class="editable-display">' + disp + '</span>');
                if (val !== originalDate) {
                    // For existing rows, auto-save. For new rows, just update the data attribute
                    if (!isNewRow) {
                        saveRow($tr, { date: val });
                    }
                }
            });
            $input.on('keydown', function(e) {
                if (e.key === 'Enter') $input.blur();
                if (e.key === 'Escape') {
                    var disp = originalDate ? new Date(originalDate).toLocaleDateString('en-GB', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    }) : '—';
                    $td.empty().html('<span class="editable-display">' + disp + '</span>');
                }
            });
        } else if (colIdx === 2) {
            var originalEmployeeId = (row.employee_id || '').toString();
            var $sel = $('<select class="form-select form-select-sm">');
            $sel.append($('<option value="">Select Employee</option>'));

            // Use the parsed employeesList
            employeesList.forEach(function(emp) {
                $sel.append($('<option>').val(emp.id).text(emp.name));
            });

            $sel.val(row.employee_id || '');
            $td.empty().append($sel);
            $sel.focus();

            function done() {
                var opt = $sel.find('option:selected');
                var val = (opt.val() || '').toString();
                var name = opt.text();
                $tr.attr('data-employee-id', val).attr('data-employee-name', name);
                $td.empty().html('<span class="editable-display">' + (name || '—') + '</span>');
                if (val !== originalEmployeeId) {
                    // For existing rows, auto-save. For new rows, just update the data attribute
                    if (!isNewRow) {
                        saveRow($tr, { employee_id: val, employee_name: name });
                    }
                }
            }
            $sel.on('change', done);
            $sel.on('blur', done);
            $sel.on('keydown', function(e) {
                if (e.key === 'Escape') {
                    var originalName = row.employee_name || '—';
                    $td.empty().html('<span class="editable-display">' + originalName + '</span>');
                }
            });
        } else if (colIdx === 3) {
            var originalStatus = (row.status || '').toString();
            var $sel = $('<select class="form-select form-select-sm">');
            $sel.append($('<option value="">Select Status</option>'));
            $sel.append($('<option value="present">Present</option>'));
            $sel.append($('<option value="absent">Absent</option>'));
            $sel.val(row.status || '');
            $td.empty().append($sel);
            $sel.focus();

            function done() {
                var val = ($sel.val() || '').toString();
                $tr.attr('data-status', val);
                $td.empty().html('<span class="editable-display">' + (val || '—') + '</span>');
                if (val !== originalStatus) {
                    // For existing rows, auto-save. For new rows, just update the data attribute
                    if (!isNewRow) {
                        saveRow($tr, { status: val });
                    }
                }
            }
            $sel.on('change', done);
            $sel.on('blur', done);
            $sel.on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $td.empty().html('<span class="editable-display">' + (originalStatus || '—') + '</span>');
                }
            });
        } else if (colIdx === 4) {
            var originalRemarks = (row.remarks || '').toString();
            var $ta = $('<textarea class="form-control form-control-sm" rows="2" placeholder="Enter remarks...">').val(row.remarks);
            $td.empty().append($ta);
            $ta.focus();
            $ta.on('blur', function() {
                var val = ($ta.val() || '').toString();
                $tr.attr('data-remarks', val);
                $td.empty().html('<span class="editable-display">' + (val || '—') + '</span>');
                if (val !== originalRemarks) {
                    // For existing rows, auto-save. For new rows, just update the data attribute
                    if (!isNewRow) {
                        saveRow($tr, { remarks: val });
                    }
                }
            });
            $ta.on('keydown', function(e) {
                if (e.ctrlKey && e.key === 'Enter') $ta.blur();
                if (e.key === 'Escape') {
                    $td.empty().html('<span class="editable-display">' + (originalRemarks || '—') + '</span>');
                }
            });
        }
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.stopPropagation();
        var id = $(this).data('id');
        $('#delete_meeting_id').val(id);
        new bootstrap.Modal(document.getElementById('deleteMeetingModal')).show();
    });

    $('#deleteMeetingConfirm').on('click', function() {
        var id = $('#delete_meeting_id').val();
        $.ajax({
            url: $('#meetings-table').data('delete-url'),
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('deleteMeetingModal')).hide();
                    if (typeof toastr !== 'undefined') toastr.success(res.message);
                    else alert(res.message);
                    meetingsTable.ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Delete failed.';
                if (typeof toastr !== 'undefined') toastr.error(msg);
                else alert(msg);
            }
        });
    });
});
