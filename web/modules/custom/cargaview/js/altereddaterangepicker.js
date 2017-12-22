(function ($) {
  "use strict";

  // All the JavaScript for this file.
  $('input[type="date"]').attr('type','text');

  $("#edit-field-carga-dates-0-value-date").daterangepicker({
    "minDate":moment().subtract(0, 'days'),
    "dateLimit": {
        "days": 30
      },
      autoUpdateInput: false,
  });

  $("#edit-field-carga-dates-0-value-date").on('apply.daterangepicker', function(ev, picker) {
    $('#edit-field-carga-dates-0-value-date').val(picker.startDate.format('DD/MM/YYYY'));
    $('#edit-field-carga-dates-0-end-value-date').val(picker.endDate.format('DD/MM/YYYY'));
  });

  $("#edit-field-carga-dates-0-value-date").on('cancel.daterangepicker', function(ev, picker) {
    $('#edit-field-carga-dates-0-value-date').val(picker.startDate.format('DD/MM/YYYY'));
    $('#edit-field-carga-dates-0-end-value-date').val(picker.endDate.format('DD/MM/YYYY'));
  });

  $('#edit-field-carga-dates-0-end-value-date').on('focus', function() {
    $('#edit-field-carga-dates-0-value-date').focus();
  });

})(jQuery);
