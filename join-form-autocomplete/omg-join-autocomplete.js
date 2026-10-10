/*
 * OMG Group — Google Places autocomplete for Gravity Forms
 *
 * Form 2 (Join Our Team, /join-our-team/):
 *   Address input_2_17 -> Suburb 18 (text), State 16 (select), Postcode 19, Country 20
 * Form 3 (Partner With Us, /partner-with-us/):
 *   Address input_3_14 -> City 15 (text), State 21 (checkbox group), Postcode 18, Country 19
 *
 * Attaches Autocomplete to each form's Address field and auto-populates the
 * other fields. A search icon is added to the Address field via CSS.
 */
(function () {
  'use strict';

  var API_KEY = 'AIzaSyAQPSgakhnhy0F8oSeb94We6qWr8wtoaJg'; // restrict by HTTP referrer in Google Cloud
  var COUNTRY = 'au';

  // Each form: the Address input bound to Autocomplete, plus the fields to fill.
  // comp = which address component feeds the field; kind = how to write it.
  var FORMS = [
    { formId: 2, address: 'input_2_17', fields: [
        { comp: 'locality', kind: 'text',   id: 'input_2_18' },   // Suburb
        { comp: 'state',    kind: 'select', id: 'input_2_16' },   // State dropdown
        { comp: 'postal',   kind: 'text',   id: 'input_2_19' },   // Postal Code
        { comp: 'country',  kind: 'text',   id: 'input_2_20' }    // Country
    ] },
    { formId: 3, address: 'input_3_14', fields: [
        { comp: 'locality', kind: 'text',     id: 'input_3_15' },       // City
        { comp: 'state',    kind: 'checkbox', group: 'choice_3_21_' },  // State checkboxes
        { comp: 'postal',   kind: 'text',     id: 'input_3_18' },       // ZIP / Postal Code
        { comp: 'country',  kind: 'text',     id: 'input_3_19' }        // Country
    ] }
  ];

  function byId(id) { return document.getElementById(id); }

  function setText(id, v) {
    var el = byId(id);
    if (!el || v == null) return;
    el.value = v;
    el.dispatchEvent(new Event('input',  { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
  }

  // The Address field is bound to Autocomplete; writing to it must NOT fire an
  // 'input' event or the widget re-queries and the dropdown reopens. 'change' only.
  function setAddress(id, v) {
    var el = byId(id);
    if (!el) return;
    el.value = v == null ? '' : v;
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
        if (window.jQuery && window.jQuery(el).data('chosen')) {
          window.jQuery(el).trigger('chosen:updated');
        }
        return;
      }
    }
  }

  // Tick the checkbox whose value matches the state abbreviation; clear the rest
  // in the same group so a new selection replaces the old one.
  function setCheckboxGroup(prefix, shortName) {
    if (!shortName) return;
    var want = String(shortName).toUpperCase();
    var nodes = document.querySelectorAll('input[id^="' + prefix + '"]');
    for (var i = 0; i < nodes.length; i++) {
      var cb = nodes[i];
      var match = (cb.value || '').toUpperCase() === want;
      if (cb.checked !== match) {
        cb.checked = match;
        cb.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }
  }

  function pick(components, type, useShort) {
    for (var i = 0; i < components.length; i++) {
      if (components[i].types.indexOf(type) !== -1) {
        return useShort ? components[i].short_name : components[i].long_name;
      }
    }
    return '';
  }

  function components(place) {
    var c = place.address_components || [];
    return {
      street:   ((pick(c, 'street_number', false) + ' ' + pick(c, 'route', false)).trim()) || place.name || '',
      locality: pick(c, 'locality', false) || pick(c, 'postal_town', false)
             || pick(c, 'sublocality_level_1', false) || pick(c, 'sublocality', false),
      state:    pick(c, 'administrative_area_level_1', true),
      postal:   pick(c, 'postal_code', false),
      country:  pick(c, 'country', false)
    };
  }

  function fill(cfg, place) {
    if (!place || !place.address_components) return;
    var v = components(place);
    setAddress(cfg.address, v.street);
    for (var i = 0; i < cfg.fields.length; i++) {
      var f = cfg.fields[i], val = v[f.comp];
      if (f.kind === 'text')          setText(f.id, val);
      else if (f.kind === 'select')   setSelect(f.id, val);
      else if (f.kind === 'checkbox') setCheckboxGroup(f.group, val);
    }
  }

  function initForm(cfg) {
    var input = byId(cfg.address);
    if (!input || input.dataset.omgAac === '1') return; // once per field
    if (!(window.google && google.maps && google.maps.places)) return;

    input.dataset.omgAac = '1';
    input.classList.add('omg-aac-field'); // search icon (CSS)

    var auto = new google.maps.places.Autocomplete(input, {
      componentRestrictions: { country: COUNTRY },
      fields: ['address_components', 'formatted_address', 'geometry', 'name'],
      types: ['address']
    });
    auto.addListener('place_changed', function () { fill(cfg, auto.getPlace()); });

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.keyCode === 13) {
        var pac = document.querySelector('.pac-container');
        if (pac && pac.offsetParent !== null) e.preventDefault();
      }
    });
  }

  function initAll() { for (var i = 0; i < FORMS.length; i++) initForm(FORMS[i]); }

  function anyAddressField() {
    for (var i = 0; i < FORMS.length; i++) { if (byId(FORMS[i].address)) return true; }
    return false;
  }

  function ensure() {
    if (!anyAddressField()) return; // no target form on this page

    if (window.google && google.maps && google.maps.places) { initAll(); return; }
    if (window.__omgAacLoading) {
      var t = setInterval(function () {
        if (window.google && google.maps && google.maps.places) { clearInterval(t); initAll(); }
      }, 200);
      return;
    }
    window.__omgAacLoading = true;
    window.__omgAacInit = function () { initAll(); };
    var s = document.createElement('script');
    s.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(API_KEY) +
            '&libraries=places&loading=async&callback=__omgAacInit';
    s.async = true; s.defer = true;
    document.head.appendChild(s);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ensure);
  } else {
    ensure();
  }
  // Re-attach after Gravity Forms AJAX re-renders a form.
  if (window.jQuery) {
    window.jQuery(document).on('gform_post_render', function () { ensure(); });
  }
})();
