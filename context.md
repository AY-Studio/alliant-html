# Current Status

- Demo importer/remover remains idempotent, supports Alliant vs. Default presets, and now seeds every Theme Settings color (primary, accent, nav, headings, body, value-card, news-card) plus preset-specific logos/theme-mods so the options screen always mirrors the chosen preset.
- Theme helpers + Customizer output CSS custom properties (including Bootstrap tokens) driven by those fields, while SCSS now defines fallback `--ay-*` values and updated selectors (value cards, news cards, legal page typography, etc.) to consume them, so palette updates flow through both the static build and WordPress.
- `/css/style.css` was rebuilt and synced to the WP theme after the SCSS changes, keeping the static Panini build and the WordPress front end visually identical.
- Blocks still mirror the HTML reference (hero CTA link arrays, attached media, pagination), so branding/content swaps are isolated to presets rather than per-block patches.

# Next Steps

1. **Preset Config Loader** – Extract preset metadata (colors, copy, attachments) into discrete config files and have the importer read them dynamically so we can add future brands without touching PHP.
2. **Theme Settings UX** – Ensure header/footer logo fields store attachment IDs (not URLs) so editors can see/change them after import, and add a Theme screenshot so the WP Appearance screen shows a preview.
3. **Global Styling Follow-up** – Continue replacing hard-coded `$alliant-navy` usages (forms, CTA buttons, dividers) with CSS-variable driven rules and document any remaining selectors that still need variable support.
