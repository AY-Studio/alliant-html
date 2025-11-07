# AY AIP Base Theme

A starter WordPress theme that implements the flexibility plan outlined in `wp-theme-plan.md`. It includes:

- CSS variables + Customizer controls for typography and colors.
- ACF JSON definitions for hero, card grid, stats, CTA, contact, and team blocks, plus theme options.
- ACF-powered block templates rendered via PHP (`template-parts/blocks`).
- Gravity Forms compatibility for contact sections.
- Font Awesome loaded globally for icon selections inside block fields.

## Installation

1. Copy `themes/ay-aip-base` into `wp-content/themes/`.
2. Ensure Advanced Custom Fields Pro and Gravity Forms are installed.
3. Activate the theme in WordPress.
4. In **Appearance → AY Starter Setup**, click “Import Starter Content” to create placeholder pages, posts, and menus that mirror the reference HTML (optional).
5. In **ACF → Sync**, import the provided field groups from `acf-json`.
6. Configure fonts/colors in **Customizer → Design Settings** or via the ACF Theme Settings options page.
