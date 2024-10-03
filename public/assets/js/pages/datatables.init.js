$(document).ready(function () {
    $("#datatable-buttons").DataTable({
        lengthChange: false,
        buttons: [
            { extend: 'copy', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'excel', exportOptions: { columns: ':not(:last-child)' } },
            { extend: 'pdf', 
              customize: function(doc) {
                  doc.defaultStyle.width = '100%'; // Set PDF width to 100%
                  doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*']; // Set column widths to '*'
              },
              exportOptions: { columns: ':not(:last-child)' }
            },
            { extend: 'colvis' }
        ]
    }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

    $(".dataTables_length select").addClass("form-select form-select-sm");
});
