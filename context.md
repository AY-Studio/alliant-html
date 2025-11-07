# Current Status

- `AY AIP Base` theme mirrors the exported HTML (header/nav, `#swup`, footer) across all main pages.
- Demo importer seeds Home/About/Insights/Contact/Terms/Privacy/Blocks with the exact HTML snippets plus menus, `/news/{slug}` permalinks, and sample posts.
- Swup + AOS transitions, sticky/shrinking navbar, Classic Editor enforcement, and fallback logos are in place; the nav background color is theme-configurable.
- Fonts now pull from Google Fonts via the official API (requires API key in Theme Settings) with a searchable dropdown in both Customizer and Theme Settings.
- Blocks Library demo page includes hero variants, Product Offerings, Team Grid, etc., giving us a reference for future blockization.
- News archive (`/news/`) uses the Swup hero layout; single posts use the exported article markup including share buttons.

# Next Steps

1. **Componentize Page Sections**
   - Convert each static section (hero, values, product offerings, stats, CTA, team, etc.) into discrete ACF blocks / block patterns so pages are assembled from components instead of raw HTML.
   - Ensure those blocks mirror the HTML reference 1:1 to preserve styling.
2. **Rebuild Pages via Blocks**
   - Recreate Home/About/Contact/Insights/Blocks pages using the new blocks or flexible-content layouts, and update the importer to place those blocks rather than dumping HTML.
3. **CMS Enhancements**
   - Add any missing global settings (e.g., nav CTA, section backgrounds) to Theme Settings.
4. **Documentation & QA**
   - Update README/theme docs to explain how to use the new blocks, Theme Settings, and importer workflow.
   - Regression test Swup transitions, sticky nav, Gutenberg disablement, and responsive layouts after the refactor.
