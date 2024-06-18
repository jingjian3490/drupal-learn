/* eslint-disable no-unused-vars */
const $ = jQuery;

const VIEW_RESULT_LIST = '.view-display-id-hospital_list';
const VIEW_MAP_LIST = '.view-display-id-hospital_map';
const DISPLAY_SEARCH_FORM = '.hospital-filter-form';
const DISPLAY_SEARCH_INPUT_BUTTON = `${DISPLAY_SEARCH_FORM} .search-button`;
const DISPLAY_SEARCH_INPUT = `${DISPLAY_SEARCH_FORM} .form-text`;
const DISPLAY_SEARCH_CHECKBOX = `${DISPLAY_SEARCH_FORM} .form-checkbox`;
const RESULT_SEARCH_INPUT = `${VIEW_RESULT_LIST} .form-text`;
const RESULT_SEARCH_SELECT = `${VIEW_RESULT_LIST} .form-select`;
const RESULT_SEARCH_BUTTON = `${VIEW_RESULT_LIST} .view-filters .form-submit`;
const MAP_SEARCH_INPUT = `${VIEW_MAP_LIST} .form-text`;
const MAP_SEARCH_SELECT = `${VIEW_MAP_LIST} .form-select`;
const MAP_SEARCH_BUTTON = `${VIEW_MAP_LIST} .view-filters .form-submit`;

class GoogleMapSearch {
  constructor() {
    this.searchInputVal = '';
    this.coords = null;
    this.errorWindow = null;
    this.isAddErrorWindow = false;
    this.searchZoneArr = {
      North: {
        val: '1',
        selected: false,
      },
      South: {
        val: '2',
        selected: false,
      },
      Central: {
        val: '3',
        selected: false,
      },
      East: {
        val: '4',
        selected: false,
      },
      West: {
        val: '5',
        selected: false,
      },
    };
    this.isClick = false;
    this.init();
  }

  init() {
    const _this = this;
    Drupal.behaviors.judgeSubmit = {
      // eslint-disable-next-line object-shorthand
      attach: function (context) {
        if (_this.isClick === true) {
          _this.judgeSubmitMapForm();
          _this.isClick = false;
        }
      },
    };
    $(window).on('load', () => {
      if (!$(DISPLAY_SEARCH_INPUT_BUTTON).length) {
        return;
      }
      $(DISPLAY_SEARCH_FORM).attr('action', null);
      this.errorWindow = new window.google.maps.InfoWindow();
      this.getCurrentPosition();
      this.bindEvents();
    });
  }

  getCurrentPosition() {
    const _this = this;
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          const { coords } = position;
          _this.coords = coords;
        },
        function () {
          _this.coords = null;
        },
      );
    } else {
      _this.coords = null;
    }
  }

  bindEvents() {
    $(DISPLAY_SEARCH_INPUT).on('input', (el) => {
      this.searchInputVal = $(el.currentTarget).val();
    });
    $(DISPLAY_SEARCH_FORM).on('submit', (event) => {
      event.preventDefault();
      this.isClick = true;
      this.checkAddressIsExits();
    });
    $(DISPLAY_SEARCH_INPUT_BUTTON).on('click', () => {
      this.isClick = true;
      this.checkAddressIsExits();
    });
    $(DISPLAY_SEARCH_CHECKBOX).on('click', (el) => {
      this.isClick = true;
      const newZone = $(el.currentTarget).val();
      this.searchZoneArr[newZone].selected =
        !this.searchZoneArr[newZone].selected;
      this.checkAddressIsExits();
    });
  }

  commonSelected(target) {
    $(target)
      .find('option')
      .each((index, el) => {
        const content = $(el).text();
        $(el).prop('selected', this.searchZoneArr[content].selected);
      });
  }

  mapFormFillData() {
    this.commonSelected(MAP_SEARCH_SELECT);
    $(MAP_SEARCH_INPUT).val(this.searchInputVal);
    this.fillCenterPosition(VIEW_MAP_LIST);
  }

  resultFormFillData() {
    this.commonSelected(RESULT_SEARCH_SELECT);
    $(RESULT_SEARCH_INPUT).val(this.searchInputVal);
    this.fillCenterPosition(VIEW_RESULT_LIST);
  }

  fillCenterPosition(element) {
    if (this.coords) {
      const lat = this.coords.latitude;
      const lng = this.coords.longitude;
      $(element)
        .find('.views-exposed-form')
        .append(
          `<input type="hidden" name="center[coordinates][lat]" value=${lat}><input type="hidden" name="center[coordinates][lng]" value=${lng}>`,
        );
    }
  }

  judgeSubmitMapForm() {
    const _this = this;

    if (_this.isAddErrorWindow) {
      if ($('.view-empty').length) {
        if (_this.coords) {
          // location not found.
          _this.submitMapForm();
          _this.errInfoWindow(Drupal.t('Location not found'));
        } else {
          // Location services are not turned on
          _this.errInfoWindow(Drupal.t('Location services are not turned on'));
        }
      } else {
        _this.submitMapForm();
        // eslint-disable-next-line no-lonely-if
        if (_this.errorWindow) {
          _this.errorWindow.close();
        }
      }
    } else {
      _this.submitMapForm();
    }
  }

  filterClick() {
    this.mapFormFillData();
    this.resultFormFillData();
    this.submitResultForm();
  }

  // eslint-disable-next-line class-methods-use-this
  errInfoWindow(text) {
    if (Drupal.geolocation.maps[0].infoWindow) {
      Drupal.geolocation.maps[0].infoWindow.close();
    }
    this.errorWindow.setPosition(Drupal.geolocation.maps[0].googleMap.center);
    this.errorWindow.setContent(text);
    this.errorWindow.open(Drupal.geolocation.maps[0].googleMap);
  }

  // eslint-disable-next-line class-methods-use-this
  submitMapForm() {
    $(MAP_SEARCH_BUTTON).click();
  }

  // eslint-disable-next-line class-methods-use-this
  submitResultForm() {
    $(RESULT_SEARCH_BUTTON).click();
  }

  // eslint-disable-next-line class-methods-use-this
  checkAddressIsExits() {
    const _this = this;
    _this.getCurrentPosition();
    let onlyZipCode = true;
    // Only input zip code
    if (!/^\d{6}$/.test(_this.searchInputVal)) {
      onlyZipCode = false;
    }
    if (onlyZipCode) {
      const loading = `<div class="ajax-progress ajax-progress-fullscreen hospital-search-loading">&nbsp;</div>`;
      $('body').append(loading);
      $.ajax({
        type: 'get',
        url: '/hospital/is-exits',
        data: {
          address: _this.searchInputVal,
        },
        success(res) {
          if (res.status === 'OK') {
            if (res.code === 201) {
              const location = {
                latitude: res.data.location.lat,
                longitude: res.data.location.lng,
              };
              _this.coords = location;
              const zipCodeInput = `<input type="hidden" name="zip_code_no_results" value="1">`;
              $(VIEW_RESULT_LIST)
                .find('.views-exposed-form')
                .append(zipCodeInput);
              $(VIEW_MAP_LIST).find('.views-exposed-form').append(zipCodeInput);
            }
          }
        },
        error(res) {},
        complete() {
          $('.hospital-search-loading').remove();
          _this.filterClick();
        },
      });
    } else {
      _this.filterClick();
    }
  }
}

export default new GoogleMapSearch();
