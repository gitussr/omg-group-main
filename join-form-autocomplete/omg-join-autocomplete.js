/*
 * OMG Group — "Join Our Team" Google Places autocomplete
 * Form id: 2  |  Page: https://omggroup.com.au/join-our-team/
 *
 * Attaches Google Places Autocomplete to the Address field and auto-populates
 * the separate Suburb, State, Postal Code and Country fields. Adds a search
 * icon inside the Address field.
 *
 * Field map (form 2):
 *   Address      -> #input_2_17  (text)   street number + route
 *   Suburb       -> #input_2_18  (text)   locality
 *   State        -> #input_2_16  (select) admin_area_level_1 short_name
 *   Postal Code  -> #input_2_19  (text)   postal_code
 *   Country      -> #input_2_20  (text)   country long_name
 */
(function () {
  'use strict';

  var CONFIG = {
    apiKey: 'AIzaSyAQPSgakhnhy0F8oSeb94We6qWr8wtoaJg', // restrict by HTTP referrer in Google Cloud
    country: 'au',
    fields: {
      address: 'input_2_17',
      suburb:  'input_2_18',
      state:   'input_2_16',
      zip:     'input_2_19',
      country: 'input_2_20'
    }
  };

  // ---- small helpers --------------------------------------------------------
  function byId(id) { return document.getElementById(id); }

  function setValue(id, value) {
    var el = byId(id);
    if (!el || value == null) return;
    el.value = value;
    // Let Gravity Forms / conditional logic / Chosen notice the change.
    el.dispatchEvent(new Event('input',  { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
  }

  // The Address field is the one bound to Autocomplete. Writing to it must NOT
  // fire an 'input' event, or the Autocomplete widget re-runs its search and
  // the suggestions dropdown reopens in a loop. Fire 'change' only.
  function setAddressValue(id, value) {
    var el = byId(id);
    if (!el) return;
    el.value = value == null ? '' : value;
    el.dispatchEvent(new Event('change', { bubbles: true }));
  }

  function setSelect(id, shortName) {
    var el = byId(id);
    if (!el || !shortName) return;
    var want = String(shortName).toUpperCase();
    for (var i = 0; i < el.options.length; i++) {
      if (el.options[i].value.toUpperCase() === want) {
        el.selectedIndex = i;
        el.dispatchEvent(new Event('change', { bubbles: true }));
        // Keep a Chosen-enhanced select in sync, if present.
        if (window.jQuery && window.jQuery(el).data('chosen')) {
          window.jQuery(el).trigger('chosen:updated');
        }
        return;
      }
    }
  }

  // Pull a named component out of a Google place result.
  function pick(components, type, useShort) {
    for (var i = 0; i < components.length; i++) {
      if (components[i].types.indexOf(type) !== -1) {
        return useShort ? components[i].short_name : components[i].long_name;
      }
    }
    return '';
  }

  // ---- fill the form from a selected place ----------------------------------
  function fillFromPlace(place) {
    if (!place || !place.address_components) return;
    var c = place.address_components;

    var streetNo = pick(c, 'street_number', false);
    var route    = pick(c, 'route', false);
    var street   = (streetNo + ' ' + route).trim();

    var suburb = pick(c, 'locality', false)
              || pick(c, 'postal_town', false)
              || pick(c, 'sublocality_level_1', false)
              || pick(c, 'sublocality', false);

    var state = pick(c, 'administrative_area_level_1', true);  // e.g. "NSW"
    var zip   = pick(c, 'postal_code', false);
    var ctry  = pick(c, 'country', false);                     // e.g. "Australia"

    // Address line: prefer the street; fall back to the place name so the box
    // is never left showing the full "street, suburb, state" formatted string.
    setAddressValue(CONFIG.fields.address, street || place.name || '');
    setValue(CONFIG.fields.suburb,  suburb);
    setSelect(CONFIG.fields.state,  state);
    setValue(CONFIG.fields.zip,     zip);
    setValue(CONFIG.fields.country, ctry);
  }

  // ---- attach autocomplete to the address field -----------------------------
  // (The search icon is a pure-CSS background-image on the input; see the CSS.)
  function initField() {
    var input = byId(CONFIG.fields.address);
    if (!input || input.dataset.omgAac === '1') return; // run once per field
    if (!(window.google && google.maps && google.maps.places)) return;

    input.dataset.omgAac = '1';
    input.classList.add('omg-aac-field');

    var auto = new google.maps.places.Autocomplete(input, {
      componentRestrictions: { country: CONFIG.country },
      fields: ['address_components', 'formatted_address', 'geometry', 'name'],
      types: ['address']
    });

    auto.addListener('place_changed', function () {
      fillFromPlace(auto.getPlace());
    });

    // Stop the Enter key (used to accept a suggestion) from submitting the form.
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.keyCode === 13) {
        var pac = document.querySelector('.pac-container');
        if (pac && pac.offsetParent !== null) e.preventDefault();
      }
    });
  }

  // ---- load the Google Maps JS API once, then init --------------------------
  function ensureApiThenInit() {
    if (!byId(CONFIG.fields.address)) return; // field not on this page

    if (window.google && google.maps && google.maps.places) {
      initField();
      return;
    }
    // Another script (e.g. the old plugin) may already be loading it.
    if (window.__omgAacLoading) {
      var t = setInterval(function () {
        if (window.google && google.maps && google.maps.places) {
          clearInterval(t);
          initField();
        }
      }, 200);
      return;
    }
    window.__omgAacLoading = true;
    window.__omgAacInit = function () { initField(); };
    var s = document.createElement('script');
    s.src = 'https://maps.googleapis.com/maps/api/js?key=' +
      encodeURIComponent(CONFIG.apiKey) +
      '&libraries=places&loading=async&callback=__omgAacInit';
    s.async = true;
    s.defer = true;
    document.head.appendChild(s);
  }

  // ---- bootstrap: initial load + Gravity Forms AJAX re-render ---------------
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ensureApiThenInit);
  } else {
    ensureApiThenInit();
  }

  // Gravity Forms re-renders the form markup after AJAX validation; re-attach.
  if (window.jQuery) {
    window.jQuery(document).on('gform_post_render', function (e, formId) {
      if (formId == 2) ensureApiThenInit();
    });
  }
})();
