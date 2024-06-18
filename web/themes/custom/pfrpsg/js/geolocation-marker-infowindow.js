/* eslint-disable strict */
/* eslint-disable no-shadow */
/* eslint-disable max-nested-callbacks */
/* eslint-disable no-console */
/* eslint-disable no-undef */
/* eslint-disable vars-on-top */
/* eslint-disable no-var */
/* eslint-disable prettier/prettier */
/* eslint-disable object-shorthand */
/**
 * @file
 * Marker InfoWindow.
 */

/**
 * @typedef {Object} MarkerInfoWindowSettings
 *
 * @extends {GeolocationMapFeatureSettings}
 *
 * @property {Boolean} infoAutoDisplay
 * @property {Boolean} disableAutoPan
 * @property {Boolean} infoWindowSolitary
 * @property {int} maxWidth
 */

/**
 * @typedef {Object} GoogleInfoWindow
 * @property {Function} open
 * @property {Function} close
 */

/**
 * @property {GoogleInfoWindow} GeolocationGoogleMap.infoWindow
 * @property {function({}):GoogleInfoWindow} GeolocationGoogleMap.InfoWindow
 */
 (function (Drupal) {
  "use strict";

  /**
   * Marker InfoWindow.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches common map style functionality to relevant elements.
   */

  Drupal.behaviors.geolocationMarkerInfoWindow = {
    attach: function (context, drupalSettings) {
      Drupal.geolocation.executeFeatureOnAllMaps(
        "marker_infowindow",

        /**
         * @param {GeolocationGoogleMap} map - Current map.
         * @param {MarkerInfoWindowSettings} featureSettings - Settings for current feature.
         */
        function (map, featureSettings) {
          map.addMarkerAddedCallback(function (currentMarker) {
            if (typeof currentMarker.locationWrapper === "undefined") {
              return;
            }

            var content =
              currentMarker.locationWrapper.find(".location-content");

            if (content.length < 1) {
              return;
            }
            content = content.html();

            const customPixelOffset = window.innerWidth > 768 ? new google.maps.Size( 140, 0 ) : null;
            var markerInfoWindow = {
              content: content.toString(),
              pixelOffset: customPixelOffset,
              disableAutoPan: featureSettings.disableAutoPan,
            };

            if (featureSettings.maxWidth > 0) {
              markerInfoWindow.maxWidth = featureSettings.maxWidth;
            }

            // Set the info popup text.
            var currentInfoWindow = new google.maps.InfoWindow(
              markerInfoWindow
            );

            function pfPharmacyFinderResults(pharmacyID, pharmacyName) {
              const dataLayer = [];
              dataLayer.push({
                event: 'pfPharmacyFinderResults',
                pfPharmacyFinderResults: {
                  pharmacyID,
                  pharmacyName,
                },
              });
            
              const event = new CustomEvent('pfAnalytics', {
                detail: dataLayer,
              });
              document.querySelector('body').dispatchEvent(event);
            }

            currentMarker.addListener("click", function () {
              const customPixelOffset = window.innerWidth > 768 ? new google.maps.Size( 140, 0 ) : null;
              currentInfoWindow.pixelOffset = customPixelOffset;
              if (featureSettings.infoWindowSolitary) {
                if (typeof map.infoWindow !== "undefined") {
                  map.infoWindow.close();
                }
                map.infoWindow = currentInfoWindow;
              }
              currentInfoWindow.open(map.googleMap, currentMarker);
              setTimeout(() => {
                if (window.innerWidth > 768 ) {
                  document.querySelector('.gm-style-iw-tc').classList.add('left');
                }
              }, 0);
            });

            if (featureSettings.infoAutoDisplay) {
              if (map.googleMap.get("tilesloading")) {
                google.maps.event.addListenerOnce(
                  map.googleMap,
                  "tilesloaded",
                  function () {
                    google.maps.event.trigger(currentMarker, "click");
                  }
                );
              } else {
                jQuery.each(map.mapMarkers, function (index, currentMarker) {
                  google.maps.event.trigger(currentMarker, "click");
                });
              }
            }
          });

          return true;
        },
        drupalSettings
      );
    },
    detach: function (context, drupalSettings) {},
  };
})(Drupal);
