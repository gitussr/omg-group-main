# Join Our Team — Google Places autocomplete

Adds Google Places autocomplete to the **Address** field of Gravity Form **id 2**
on <https://omggroup.com.au/join-our-team/> and auto-populates the separate
Suburb, State, Postal Code and Country fields. Also adds a search icon inside
the Address field.

## Field map (form 2)

| Form field | Input id | Element | Populated from Google |
|---|---|---|---|
| Address | `input_2_17` | text | street number + route |
| Suburb | `input_2_18` | text | locality |
| State / Province / Region | `input_2_16` | select | state short name (NSW, VIC, …) |
| Postal Code | `input_2_19` | text | postal_code |
| Country | `input_2_20` | text | country name |

The state dropdown's option values (ACT, NSW, NT, QLD, SA, TAS, VIC, WA) already
match the abbreviations Google returns, so the state is selected automatically.

## ⚠️ Before you install — remove the old autocomplete

The site currently runs the **GF Google Address Autocomplete**
(`gf-google-address-autocomplete` v1.3.7) plugin, which is already bound to this
Address field but does **not** fill the Suburb/State/Postcode/Country fields.

Deactivate that plugin (or remove the Address field from its settings) first.
If you leave it active alongside this snippet you'll get two suggestion
dropdowns on the same field.

## Install — choose one

**Option A — one PHP snippet (recommended, nothing to upload)**
Paste the code from `functions-snippet.php` (everything below the `====` line)
into your child theme's `functions.php`, or add it as a new snippet in the
WPCode / Code Snippets plugin. CSS + JS are inlined, so no extra files are
needed.

**Option B — separate asset files**
Upload `omg-join-autocomplete.js` and `omg-join-autocomplete.css` and load them
with a "Custom CSS & JS" plugin, or enqueue them from the theme. The JS loads
the Google Maps API itself using the key in `CONFIG.apiKey`.

## Google Cloud key setup

The key used is `AIzaSyAQPSgakhnhy0F8oSeb94We6qWr8wtoaJg`. In Google Cloud:

1. Enable **Maps JavaScript API** and **Places API** for the project.
2. Restrict the key to the **HTTP referrer** `https://omggroup.com.au/*`
   (and `https://www.omggroup.com.au/*` if used). This is safe because the key
   is visible in the page source — the referrer restriction is what protects it.

## Verify after install

1. Open <https://omggroup.com.au/join-our-team/> (hard refresh / incognito).
2. Start typing an Australian street address in **Address** — suggestions appear,
   with a magnifying-glass icon in the field.
3. Pick a suggestion → Address, Suburb, State, Postal Code and Country fill in.
4. Press Enter on a suggestion → it selects it and does **not** submit the form.
5. Submit the form and confirm the entry in **Forms → Entries** has all fields.

## Notes

- Restricted to Australian addresses (`componentRestrictions: { country: 'au' }`).
  Remove/change `country` in the config to allow other countries.
- Uses the classic `google.maps.places.Autocomplete`, which binds to the
  existing Gravity Forms input so the submitted value is preserved. (Google's
  newer `PlaceAutocompleteElement` replaces the input with a web component and
  does not retrofit cleanly onto a Gravity Forms field.)
- Re-initialises on `gform_post_render` so it still works after the form's AJAX
  validation re-renders the markup.
