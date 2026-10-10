# OMG Group footer change (2026-10-10)

Target: https://omggroup.com.au/ footer, to align with the reference
https://creativus-sites.com/demo/dev/omg-entertainment-new/ footer.
Constraint: do not change overall UI (typography/colour).

Theme: `omg` (active). Footer is rendered by `wp-content/themes/omg/footer.php`;
styles in `wp-content/themes/omg/assets/css/styles.css`.

## Backups
- `backup/footer.php.<ts>.bak` — original theme footer.php
- `backup/styles.css.orig` — original styles.css
- `backup/rendered-footer.<ts>.html` — original rendered footer HTML
- On the server: `footer.php.omgbak-<ts>` and `assets/css/styles.css.omgbak-<ts>`

## Done (deployed via FTP, theme files only)
3. **Square social icons** — `styles.css` override changes the social icon tile
   from `border-radius:50%` (disc) to `border-radius:6px` (square). Shape only;
   size/background/colour unchanged.
4. **Rearranged columns** — in `footer.php`, swapped the *Contact* and *Social*
   columns so order is now **Services → Social → Contact → Logo**, matching the
   reference. (Content-only swap, verified no content lost.)
5. **Footer logo vertically centered** — override centers the logo in its column
   (`align-items:center` + box `height:auto`).

Override block is appended to `styles.css` between the
`/* ===== OMG footer tweaks ===== */` markers (see
`deployed/styles-footer-override.css`). Revert = restore the two `.omgbak-*`
files on the server (or re-upload `backup/` copies).

## NOT done — needs WordPress admin / DB access (requirement 1)
The reference's footer *content/links* live in the database, not theme files,
so they can't be changed over FTP:

- **Social URLs & email** come from ACF options (`contact_details`): to match the
  reference, Facebook → `https://www.facebook.com/share/1FKUjpzeKi/?mibextid=wwXIfr`,
  Instagram → `https://www.instagram.com/omgstudiophotobooths?...`,
  email → `info@OMGent.com.au` (currently `info@OMGgroup.com.au`).
- **"OMG Group Services" brand links** come from WP nav menus (`footer`,
  `footer-other`): reference uses https (not http), reorders items, and adds
  **OMG Studio** and **Food & Beverage** entries.
  ⚠️ Those two pages return **HTTP 404** on the live site — they don't exist yet,
  so there's nothing valid to link to.
- **Footer logo image** is set in Customizer (`the_custom_logo()`).

These require live `wp-admin` (Appearance → Menus, ACF options, Customizer) or DB
access, neither of which is available from the FTP credentials provided.

## Cache note
LiteSpeed Cache is active. The `?ver` on styles.css bumped automatically, but if
anything looks stale to a logged-out visitor, purge **LiteSpeed Cache → Purge All**.
