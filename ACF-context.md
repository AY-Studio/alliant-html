# ACF Component Flexibility - Technical Context

## Overview
This document explains the customizations made to various ACF (Advanced Custom Fields) components in the WordPress theme to make them more flexible, user-friendly, and maintainable. The goal is to establish a consistent pattern for organizing fields using **Content/Style tabs**, providing **sensible defaults**, and giving users **granular control** over styling without overwhelming them.

## Design Philosophy

We are systematically improving all ACF components to follow these principles:

1. **Tab Organization** - Separate Content from Style using ACF tabs for better UX
2. **Sensible Defaults** - Every field has intelligent defaults so components work out-of-the-box
3. **Visual Grouping** - Use message fields as section headers to organize related fields
4. **Granular Control** - Provide fine-tuned controls (like separate color + opacity sliders)
5. **Pattern Consistency** - Apply the same organizational structure across all components
6. **Conditional Logic** - Show/hide fields based on user selections to reduce clutter

This pattern should be applied to **all future components** to maintain consistency across the theme.

## What We Changed

### 1. Overlay Opacity Control

**Problem:** The overlay color field accepted rgba values, but there was no user-friendly way to adjust opacity separately from the color.

**Solution:** Split overlay control into two fields:
- **Overlay Color** (`hero_section_overlay_color`) - Color picker for the overlay color
- **Overlay Opacity** (`hero_section_overlay_opacity`) - Range slider (0-100%, steps of 5)

**Location:** `themes/ay-aip-base/acf-json/group_page_sections.json` lines 101-124

**Field Configuration:**
```json
{
    "key": "field_hero_section_overlay_color",
    "label": "Overlay Color",
    "name": "hero_section_overlay_color",
    "type": "color_picker",
    "wrapper": { "width": "50" }
},
{
    "key": "field_hero_section_overlay_opacity",
    "label": "Overlay Opacity",
    "name": "hero_section_overlay_opacity",
    "type": "range",
    "default_value": 70,
    "min": 0,
    "max": 100,
    "step": 5,
    "wrapper": { "width": "50" }
}
```

**Template Implementation:** `themes/ay-aip-base/template-parts/blocks/hero-section.php`
```php
$overlay_color   = ay_aip_base_get_block_field( 'hero_section_overlay_color' );
$overlay_opacity = ay_aip_base_get_block_field( 'hero_section_overlay_opacity' );

// Defaults
if ( ! $overlay_color ) {
    $overlay_color = '#223a69'; // Navy
}
if ( ! $overlay_opacity ) {
    $overlay_opacity = 70; // 70%
}

// Convert to rgba format
$overlay = ay_aip_base_hex_to_rgba( $overlay_color, $overlay_opacity / 100 );
```

**Helper Function:** `themes/ay-aip-base/inc/helpers.php` lines 434-471

Created `ay_aip_base_hex_to_rgba()` function that:
- Accepts hex colors (e.g., `#223a69`)
- Accepts rgb colors (e.g., `rgb(34, 58, 105)`)
- Accepts rgba colors (returns as-is)
- Converts to rgba format with specified opacity

### 2. Button Hover States

**Problem:** Buttons with custom styling had no hover effects defined, resulting in poor UX.

**Solution:** Added three hover color fields with intelligent defaults:

**ACF Fields Added:** `themes/ay-aip-base/acf-json/group_page_sections.json` lines 193-220
- `hero_section_button_hover_background_color` - Hover background color
- `hero_section_button_hover_border_color` - Hover border color
- `hero_section_button_hover_text_color` - Hover text color

**Field Organization:**
Fields are grouped using ACF message fields as visual separators:
- "Background & Overlay" section
- "Button Styling" section
  - "Normal State" subsection (3 color fields at 33% width each)
  - "Hover State" subsection (3 color fields at 33% width each)

**Default Hover Behavior:** `themes/ay-aip-base/inc/helpers.php` lines 614-626

In the `ay_aip_base_get_button_style_tokens()` function:
```php
if ( $has_custom_style ) {
    // Use custom hover colors if provided, otherwise use defaults
    $final_hover_bg     = '' !== $hover_bg_value ? $hover_bg_value : '#ffffff';
    $final_hover_border = '' !== $hover_border_value ? $hover_border_value : $border_value;
    $final_hover_text   = '' !== $hover_text_value ? $hover_text_value : '#223a69';

    $hover_styles[] = '--btn-hover-bg:' . esc_attr( $final_hover_bg );
    if ( '' !== $final_hover_border ) {
        $hover_styles[] = '--btn-hover-border:' . esc_attr( $final_hover_border );
    }
    $hover_styles[] = '--btn-hover-color:' . esc_attr( $final_hover_text );
}
```

**Default Values:**
- Hover Background: `#ffffff` (white) - Creates inversion effect
- Hover Border: Inherits from normal border color - Maintains visual consistency
- Hover Text: `#223a69` (navy) - Provides contrast against white background

**CSS Implementation:** `scss/_layout.scss` lines 970-977
```scss
.btn {
  &:hover, &:focus {
    background-color: var(--btn-hover-bg) !important;
    border-color: var(--btn-hover-border) !important;
    color: var(--btn-hover-color) !important;
  }
}
```

Uses CSS custom properties set inline via PHP, with `!important` to override Bootstrap defaults.

### 3. Button Background Transparency Fix

**Problem:** Outline buttons were showing a solid background color instead of transparent.

**Solution:** `themes/ay-aip-base/inc/helpers.php` lines 597-603
```php
if ( $has_custom_style ) {
    // For outline buttons, only set background if explicitly provided
    if ( '' !== $background_value ) {
        $styles[] = 'background-color:' . esc_attr( $background_value );
    } elseif ( 'outline' === $type ) {
        $styles[] = 'background-color:transparent';
    }
}
```

## Demo Importer Updates

**Location:** `themes/ay-aip-base/inc/demo-import.php`

Updated all hero section imports to use separate color and opacity fields:

**Before:**
```php
'hero_section_overlay_color' => 'rgba(34, 58, 105, 0.7)',
```

**After:**
```php
'hero_section_overlay_color' => '#223a69',
'hero_section_overlay_opacity' => 70,
```

This ensures imported content uses the new field structure correctly.

## ACF Sync Issue Resolution

**Problem:** After editing ACF JSON files manually, WordPress showed the field group as needing sync even after syncing.

**Cause:** The `modified` timestamp in the JSON file was set to a future date (`1763406405` = November 2025).

**Solution:** Updated timestamp to current time (`1762818599` = November 2024) in `group_page_sections.json` line 1586.

**Why This Matters:** ACF compares the `modified` timestamp in the JSON file with the timestamp stored in the database. If the JSON file has a newer timestamp, ACF flags it for sync. A future timestamp caused perpetual sync warnings.

## File Architecture

```
themes/ay-aip-base/
├── acf-json/
│   └── group_page_sections.json          # ACF field definitions
├── inc/
│   ├── helpers.php                        # Helper functions
│   └── demo-import.php                    # Demo content seeder
├── template-parts/blocks/
│   └── hero-section.php                   # Hero section template
└── css/
    └── style.css                          # Compiled CSS (includes hover styles)

scss/
└── _layout.scss                           # Button hover SCSS source
```

## How It Works Together

1. **User edits page** → ACF fields capture color and opacity values separately
2. **Template renders** → `hero-section.php` fetches field values
3. **Helper function processes** → `ay_aip_base_hex_to_rgba()` combines color + opacity
4. **Button helper generates styles** → `ay_aip_base_get_button_style_tokens()` creates inline CSS custom properties with defaults
5. **CSS applies styles** → SCSS rules consume custom properties for hover states

## Key Defaults

| Field | Default Value | Reason |
|-------|---------------|--------|
| Overlay Color | `#223a69` (Navy) | Brand color |
| Overlay Opacity | `70` (70%) | Good readability balance |
| Button Hover BG | `#ffffff` (White) | Creates inversion effect |
| Button Hover Text | `#223a69` (Navy) | Contrast on white background |
| Button Hover Border | Inherits normal border | Visual consistency |

## WordPress Admin UX

Fields appear in this logical grouping:

**Content Tab:**
- Heading
- Lead Copy
- CTA Link
- Text Color

**Style Tab:**
- **Background & Overlay** (message field header)
  - Background Image
  - Overlay Color (50% width)
  - Overlay Opacity (50% width - slider)
- **Button Styling** (message field header)
  - Button Type (Solid/Outline dropdown)
  - **Normal State** (message field header)
    - Background (33% width)
    - Border (33% width)
    - Text (33% width)
  - **Hover State** (message field header)
    - Background (Hover) (33% width)
    - Border (Hover) (33% width)
    - Text (Hover) (33% width)

## Why These Changes

1. **Separated Opacity Control** - Non-technical users can adjust overlay darkness without understanding rgba() syntax
2. **Default Hover Colors** - Buttons work out-of-the-box with professional hover effects
3. **Transparent Outline Fix** - Buttons render correctly per Bootstrap conventions
4. **Organized Fields** - Grouped fields with message separators improve admin UX
5. **Flexible Overrides** - Defaults can be overridden for brand-specific needs

## Technical Notes for AI/Developers

- ACF JSON files use Unix timestamps for sync tracking
- CSS custom properties (`--btn-hover-bg`) allow dynamic styles without rebuilding CSS
- Helper functions centralize logic for reuse across multiple templates
- The `!important` flag is necessary to override Bootstrap's built-in button hover styles
- Demo importer uses the same helper functions to ensure consistency
- Range field type provides better UX than number field for opacity (visual slider)
- Message field type (`type: "message"`) creates visual section headers without storing data
- Wrapper width percentages create responsive multi-column layouts in ACF admin
