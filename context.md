# Current Status

- The demo importer/remover is fully idempotent and now uploads all referenced media (value cards, product offerings, team photos, SVG icons) as real attachments tagged with the demo flag. Resetting content cleans up menus, attachments, and stored media caches.
- Import work is preset-aware: the default “Alliant” preset reproduces the reference site 1:1, while a brand-agnostic “Default Starter Content” preset shares the same structure with generic copy (via text filtering) so we can spin up other client demos quickly.
- Presets can be selected from the importer UI, stored in options, and the current preset is available to builders/seeders via helper functions. The importer UI hides the Classic editor when using the page-builder template.
- Blocks (Hero, Value Cards, Product Offerings, Icon Features, Contact Form, About Team, etc.) all run off attachment fields, Font Awesome selects, and shared typography so both the HTML reference and WP theme stay in sync.
- Panini build + SCSS stay aligned with the theme (hero weights, product offerings typography, pagination partial, etc.), and Swup/AOS, sticky nav, Google Fonts picker, and news permalinks continue to match the reference.

# Next Steps

1. **Preset Architecture Enhancements**
   - Add more preset slots (e.g., “Finance Default”, “Leasing Default”) and allow preset-specific overrides for menus/pages so multiple brands can coexist without code edits.
   - Externalize preset data (JSON/YAML) so future starter sites can drop in their own copy/assets without touching PHP.
2. **HTML Content Parity for Presets**
   - Introduce neutral HTML fragments (news/contact/legal) for the Default preset so no Alliant references remain anywhere.
   - Ensure migration helpers cover any legacy fields when switching presets mid-project.
3. **Documentation & UX**
   - Document the preset workflow (how to add/edit presets, expected file locations, how media is cached) in README/Theme docs.
   - Surface the active preset + description inside the importer page and maybe WP dashboard so editors know what’s loaded.
4. **QA & Future Blocks**
   - Regression-test preset switching (import/remove cycles) and pagination, then continue componentizing any remaining static sections into reusable blocks/patterns.
