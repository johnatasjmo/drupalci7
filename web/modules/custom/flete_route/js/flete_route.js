(function ($, Drupal, drupalSettings) {

  function calcRoute (drupalSettings) {

    $("#edit-field-carga-driving-0-value").val("");
    $("#edit-field-carga-distance-0-value").val("");

    var  directionsService = new google.maps.DirectionsService();
    var start = $("#edit-field-carga-ori-name-0-value").val();
    var end = $("#edit-field-carga-des-name-0-value").val();

    if (start && end) {

      if (Number(drupalSettings.route_planner.unitSystem) == 1) {
        var unit = google.maps.UnitSystem.IMPERIAL;
      }
      else {
        var unit = google.maps.UnitSystem.METRIC;
      }

      var request = {
        origin: start,
        destination: end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING,
        unitSystem: unit
      };

      directionsService.route(request, function (response, status) {

        if (status == google.maps.DirectionsStatus.OK) {
          this.directionsDisplay.setDirections(response);
          distance = response.routes[0].legs[0].distance.text;
          time = response.routes[0].legs[0].duration.text;

          $("#edit-field-carga-driving-0-value").val(time);
          $("#edit-field-carga-distance-0-value").val(distance);
        }
      });
    }

  }

  function validateRoutes(settings) {

    $("#edit-field-carga-des-name-0-value").blur(function () {
      calcRoute(settings)
    });

    $("#edit-field-carga-ori-name-0-value").blur(function () {
      calcRoute(settings)
    });

  }

  Drupal.behaviors.flete_route = {

    attach: function (context, settings) {
      validateRoutes(settings);
    }
  };
}(jQuery, Drupal));
