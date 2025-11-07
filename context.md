# Current Status

- Created the `AY AIP Base` WordPress theme that mirrors the original static HTML site.
- Imported the exact HTML markup for Home, About, Insights, Contact, Terms, and Privacy into the demo importer so starters render identically.
- Built ACF-powered blocks (hero, card grids, stats, CTA, contact, team) but the pages currently ingest full-page HTML rather than assembling from individual blocks.
- Added Swup + AOS transitions, sticky/shrinking navbar, Google Fonts, and fallback logos.
- Implemented menu import/reset logic, news permalinks (`/news/{slug}/`), Classic Editor enforcement, and dummy posts.
- Customizer/Theme Settings now control typography and nav background color via CSS variables.

# Next Steps

1. **Componentize the HTML**
   - Break the homepage sections (hero, values, offerings, stats, CTA) into re-usable ACF block templates.
   - Repeat the process for About, Insights grid, Contact intro, Terms/Privacy layouts.
2. **Page Builder Assembly**
   - Convert each page (Home/About/Contact/News) to use block patterns/flexible content rather than full HTML dumps.
   - Ensure importer assembles the page using the new blocks so editors can reorder/remove sections.
3. **Finalize Block Field Groups**
   - Audit existing ACF fields to match actual markup needs (icons, images, copy, CTAs).
   - Add any missing blocks (e.g. product offerings cards, values carousel) as needed.
4. **Documentation/Polish**
   - Update README/theme docs to explain block usage and Theme Settings.
   - Re-run QA (Swup transitions, nav shrink, Gutenberg off, responsive checks) after refactor.
