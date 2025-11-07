# Alliant Theme Flexibility Plan

This doc outlines how we can turn the current Panini/SCSS site into a reusable WordPress theme with flexible design controls and builder-friendly components. The goal is to keep the codebase lean while exposing key options through the Theme Customizer (or `theme.json`), Advanced Custom Fields (ACF), and block variations. We’ll ensure compatibility with Gravity Forms (for site forms) and ACF (for structured content fields).

## 1. Theme Architecture

1. Convert existing layouts/partials into WordPress templates:
   - `page.php` (default page)
   - `front-page.php` (home hero + featured sections)
   - `single.php` / `archive.php` (news/articles)
   - `templates/template-pagebuilder.php` for page-builder pages (loads basic header/footer + full-width content area where we drop Gutenberg blocks or a builder).
2. Move global assets into theme structure:
   - `functions.php` enqueues compiled CSS/JS, registers navigation menus, theme support (custom logo, featured images, align-wide, editor styles, etc.).
   - SCSS compiled to `/assets/css/style.css`; JS to `/assets/js/main.js`.
   - Font Awesome loaded (locally or via CDN) and integrated into editor styles so icon usage is consistent in Gutenberg and the front end.
   - Configure translation readiness: load text domain via `load_theme_textdomain( 'alliant', get_template_directory() . '/languages' );` and generate `.pot` using WP-CLI `i18n make-pot`.
3. Register custom post types / taxonomies if needed (e.g., "Insights" = CPT) using `init` hooks.
4. Ensure Gravity Forms styles inherit theme typography/colors; enqueue GF stylesheet overrides inside `gravityforms_enqueue_scripts` hook.
5. Ensure ACF is used for all structured data (hero fields, CTA banner, card settings) and that `acf-json/` folder is committed for version-controlled field groups. Add filters to set custom ACF JSON save/load paths so field definitions stay in sync across environments.

## 2. Global Design Controls

Expose via Theme Customizer or `theme.json` (preferred for WP 5.9+). Key controls (implemented as CSS custom properties, stored via Customizer + `get_theme_mod`):

- **Typography**
  - Heading font family (Google Fonts dropdown / manual input).
  - Body font family.
  - Base font size / line height.
  - Font weight presets for headings/body.
- **Color Palette**
  - Primary/nav background color.
  - Secondary/accent color.
  - Body text color.
  - Heading color.
  - Background color (global + section variants: light, dark, gradient).
  - Button colors (primary/secondary states).
- **Layout**
  - Container width (px or %).
  - Section padding (top/bottom) defaults.
  - Card grid columns (per breakpoint) with range sliders (1–4) mirrored as ACF options for reusable blocks.
- **Header / Nav**
  - Sticky toggle.
  - Background color.
  - Hover underline enable/disable.
  - CTA button text/link and style.
- **Footer**
  - Logo upload.
  - Background color.
  - Link color/hover color.
  - Footer CTA block enable + content fields (managed via ACF Options Page).

Implement via `theme.json` + `Customizer` API:
```php
$wp_customize->add_setting( 'alliant_heading_font', [ 'default' => 'Mulish', 'transport' => 'refresh' ] );
$wp_customize->add_control( 'alliant_heading_font', [
    'label' => __( 'Heading Font', 'alliant' ),
    'section' => 'alliant_typography',
    'type' => 'text',
] );
```
Generate dynamic CSS (`wp_add_inline_style`) or use CSS variables printed in `<head>` to propagate settings.

## 3. Flexible Components / Blocks

### A. Gutenberg Block Patterns
- Create block patterns for hero, values cards, product grids, stats, team lists, CTA rows.
- Each pattern uses Group + Columns + core blocks styled via theme styles.
- Provide custom `block.json` variations for cards with the following supports:
  - Columns per breakpoint (1–4). Use CSS grid with custom block attributes or `acf_register_block_type` when more control is needed.
  - Background color options (inherit theme palette).
  - Heading/body text color overrides.

### B. ACF-Powered Blocks & Fields
- Use ACF Pro (part of plugin requirements) to register flexible blocks with server-side rendering. Key blocks and field settings:
  1. **Hero Block**
     - Fields: `layout_style` (enum: default, short), `heading`, `subheading`, `buttons` (repeater: label, URL, style), `background_image`, `overlay_color`.
     - Settings: align-wide support, toggle for dark/light text.
  2. **Card Grid Block**
     - Fields: `cards` repeater (image, heading, body, CTA label/link, optional Font Awesome icon class).
     - Layout settings: `columns_desktop` (1–4), `columns_tablet` (1–3), `columns_mobile` (1–2), `card_variant` (solid, outline, minimal), `background_color`, `text_color`.
  3. **Values/Stats Block**
     - Fields: repeater for items (icon/svg or Font Awesome class, heading, description, metric value).
     - Options: `items_per_row` (1–4), `section_style` (light, dark, gradient), `animation_toggle`.
  4. **CTA Banner Block**
     - Fields: `eyebrow`, `heading`, `body`, `primary_button`, `secondary_button`, `background_color`, `text_color`, `alignment`.
  5. **Contact/Gravity Forms Block**
     - Fields: dropdown to select Gravity Form ID, optional intro text, background style.
  6. **Team Grid Block**
     - Fields: repeater (name, title, bio, headshot, LinkedIn URL) or relational field pointing to Team CPT.
     - Layout controls similar to Card Grid.

ACF Options Page: create “Theme Settings” page to mirror Customizer values (fonts, colors, nav CTA). Where Customizer controls aren’t sufficient (e.g., structured data), use ACF Options + `get_field` for global settings.

### C. Page Builder Template
- Template file provides blank canvas with header/footer so editors can use block editor or an external builder (e.g., Elementor) for bespoke layouts.
- Provide style guide page (blocks.html content) as WordPress block pattern library for copy/paste.
- Expose per-section options via ACF Flexible Content (e.g., `page_sections` with layouts: hero, cards, image+copy, testimonial, CTA) for clients preferring structured builder.

## 4. Global Styles & CSS Variables

Introduce CSS custom properties for color/typography to enable runtime changes:
```scss
:root {
  --alliant-font-heading: 'Mulish', sans-serif;
  --alliant-font-body: 'Mulish', sans-serif;
  --alliant-color-primary: #223a69;
  --alliant-color-secondary: #526fa1;
  --alliant-color-text: #1c1f2a;
  --alliant-color-bg: #ffffff;
}
```
Customizer/`theme.json` updates these values; SCSS references variables for nav, cards, buttons, etc.

## 5. Data Separation & Content Controls

- Use Advanced Custom Fields (ACF) or core custom fields for hero content, CTA text, etc., so editors can manage copy per page.
- For repeating data (team members, news cards), rely on CPTs + block query loops.
- Provide default fallback content for demo installs.

## 6. Implementation Roadmap

1. **Bootstrap WP Theme Shell**: set up theme folder, enqueue assets, convert header/footer to `header.php`/`footer.php`, add Gravity Forms + ACF plugin checks.
2. **Create Templates**: front page, news archive, article single, standard page, builder page.
3. **Migrate Content Blocks**: transform Panini partials into block patterns or ACF flexible content layouts; register block patterns via `register_block_pattern`.
4. **Add Theme Settings**: `theme.json` base palette + Customizer for advanced controls, output CSS vars; create ACF Options Page to mirror design controls when editors prefer the admin UI.
5. **Build ACF Blocks/Patterns**: hero, card grids, CTA, stats, Gravity Forms embed. Ensure each block exposes field settings described above.
6. **Add CPT for News (optional)**: register `insight` post type + taxonomy; create archive/single templates.
7. **Performance Enhancements**: define custom image sizes (hero, card, thumbnail) and use `wp_get_attachment_image_srcset()` for responsive images; leverage `loading="lazy"` where appropriate.
8. **Testing & Plugin Compatibility**: verify Gravity Forms styling, ACF field rendering, responsive layouts, block editor support, i18n text domain coverage.
9. **Documentation**: document how to adjust fonts/colors, use block patterns, manage builder pages, sync ACF JSON (include instructions for exporting/importing via `acf-json`).

This plan keeps the current visual language but exposes site-wide design tokens, letting us rebrand quickly for new clients while keeping the editing workflow simple.
