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

## Components Updated

We have applied this flexible pattern to the following components:
- **Hero Section** - Overlay opacity, button hover states
- **Value Cards** - Tab organization, background options, pattern positioning
- **Product Offerings** - Tab organization, background options, pattern positioning, simplified icon handling
- **News Page Hero** - Editable hero section for blog/posts page

---

## Component Improvements

### Hero Section

#### 1. Overlay Opacity Control

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
    $overlay_opacity = 30; // 30%
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

#### 2. Button Hover States

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

#### 3. Button Background Transparency Fix

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

---

### Value Cards

Applied the Content/Style tab pattern to improve organization and add background/pattern control.

**Location:** `themes/ay-aip-base/acf-json/group_page_sections.json` (Value Cards layout)

**Changes Made:**

1. **Added Content/Style Tabs** - Separated content fields (heading, cards) from styling options
2. **Background Color Options** - White, Light Grey, or Navy background selection
3. **Pattern Control** - Toggle to show/hide diagonal pattern overlay
4. **Pattern Positioning** - Four position options (top-right, top-left, bottom-right, bottom-left)
5. **Conditional Logic** - Pattern position only appears when pattern is enabled

**Field Structure:**
```json
{
    "key": "field_value_cards_style_tab",
    "label": "Style",
    "type": "tab",
    "placement": "top"
},
{
    "key": "field_value_cards_background_group",
    "label": "Background & Pattern",
    "type": "message"
},
{
    "key": "field_value_cards_background",
    "label": "Background Color",
    "type": "select",
    "choices": {
        "white": "White",
        "light": "Light Grey",
        "navy": "Navy"
    },
    "default_value": "white",
    "wrapper": { "width": "50" }
},
{
    "key": "field_value_cards_show_pattern",
    "label": "Show Diagonal Pattern",
    "type": "true_false",
    "ui": 1,
    "default_value": 1,
    "wrapper": { "width": "50" }
},
{
    "key": "field_value_cards_pattern_position",
    "label": "Pattern Position",
    "type": "select",
    "choices": {
        "top-right": "Top Right",
        "top-left": "Top Left",
        "bottom-right": "Bottom Right",
        "bottom-left": "Bottom Left"
    },
    "default_value": "top-right",
    "conditional_logic": [
        [{
            "field": "field_value_cards_show_pattern",
            "operator": "==",
            "value": "1"
        }]
    ],
    "wrapper": { "width": "50" }
}
```

**Template Implementation:** `themes/ay-aip-base/template-parts/blocks/value-cards.php`
```php
$background        = ay_aip_base_get_block_field( 'value_cards_background', 'white' );
$show_pattern      = ay_aip_base_get_block_field( 'value_cards_show_pattern', true );
$pattern_position  = ay_aip_base_get_block_field( 'value_cards_pattern_position', 'top-right' );

// Determine section class based on background
$section_class = 'section section-white';
if ( 'light' === $background ) {
    $section_class = 'section section-light';
} elseif ( 'navy' === $background ) {
    $section_class = 'section group-navy';
}

// Build pattern class if pattern is enabled
if ( $show_pattern ) {
    $pattern_class = 'diagonal-lines pattern-' . esc_attr( $pattern_position );
}
```

**Pattern Positioning CSS:** `scss/_layout.scss` lines 212-247
```scss
.diagonal-lines {
  position: absolute;
  width: clamp(220px, 32vw, 360px);
  height: clamp(240px, 34vw, 420px);
  pointer-events: none;
  background-image: url('../img/bg-corner.png');
  background-repeat: no-repeat;
  background-size: contain;
  mix-blend-mode: multiply;
  opacity: 0.3;
  z-index: 1;

  &.pattern-top-right {
    top: 0; right: 0;
    transform: none;
  }

  &.pattern-top-left {
    top: 0; left: 0;
    transform: scaleX(-1); // Flip horizontally
  }

  &.pattern-bottom-right {
    bottom: 0; right: 0;
    transform: scaleY(-1); // Flip vertically
  }

  &.pattern-bottom-left {
    bottom: 0; left: 0;
    transform: scale(-1, -1); // Flip both ways
  }
}
```

---

### Product Offerings

Applied the same Content/Style tab pattern as Value Cards, plus simplified icon handling.

**Location:** `themes/ay-aip-base/acf-json/group_page_sections.json` (Product Offerings layout)

**Changes Made:**

1. **Added Content/Style Tabs** - Separated content fields from styling options
2. **Background Color Options** - White, Light Grey, Grey, or Navy background selection
3. **Pattern Control** - Toggle to show/hide diagonal pattern overlay
4. **Pattern Positioning** - Four position options (same as Value Cards)
5. **Removed Unnecessary Icon Fields** - Eliminated `icon_svg` and `icon_class` fields that weren't being used
6. **Simplified Icon Handling** - Only uses `icon_image` field with fallback to default bridge.svg

**Repeater Fields (Simplified):**
```json
"sub_fields": [
    {
        "key": "field_product_offerings_icon_image",
        "label": "Icon Image",
        "name": "icon_image",
        "type": "image",
        "return_format": "array",
        "preview_size": "medium"
    },
    {
        "key": "field_product_offerings_title_field",
        "label": "Title",
        "name": "title",
        "type": "text"
    },
    {
        "key": "field_product_offerings_description",
        "label": "Description",
        "name": "description",
        "type": "textarea",
        "rows": 3
    }
]
```

**Template Implementation:** `themes/ay-aip-base/template-parts/blocks/product-offerings.php`
```php
// Simplified icon handling - only icon_image with fallback
if ( ! empty( $item['icon_image'] ) ) {
    $icon_id = is_array( $item['icon_image'] ) && isset( $item['icon_image']['ID'] )
        ? $item['icon_image']['ID']
        : $item['icon_image'];
    $icon_id = absint( $icon_id );
    if ( $icon_id ) {
        $icon_markup = wp_get_attachment_image(
            $icon_id,
            'full',
            false,
            [
                'alt'   => esc_attr( $item['title'] ?? '' ),
                'class' => 'img-fluid',
            ]
        );
    }
}

// Fallback to default icon
if ( ! $icon_markup ) {
    $fallback = ay_aip_base_get_theme_asset_url( 'img/ico/bridge.svg' );
    $icon_markup = '<img src="' . esc_url( $fallback ) . '" alt="' . esc_attr__( 'Product icon', 'ay-aip-base' ) . '" class="img-fluid">';
}
```

**Why This Is Better:**
- Cleaner admin interface (removed confusing unused fields)
- Simpler code maintenance (one icon source instead of three)
- Consistent fallback behavior across all cards

---

### News Page Hero

Created a new ACF field group specifically for the News & Insights page (blog/posts index page).

**Problem:** The blog index page (`home.php`) had a hardcoded hero section that couldn't be edited, and text color was incorrectly showing as navy instead of white.

**Solution:** Created dedicated ACF field group for the posts page with overlay opacity controls.

**Location:** `themes/ay-aip-base/acf-json/group_news_page_hero.json` (New file)

**Fields Added:**
- `news_hero_heading` - Text field for hero heading (default: "News & Insights")
- `news_hero_subheading` - Textarea for hero description
- `news_hero_background_image` - Image field for background
- `news_hero_overlay_color` - Color picker (default: #223a69 navy)
- `news_hero_overlay_opacity` - Range slider 0-100% (default: 30%)
- `news_hero_text_color` - Color picker (default: #ffffff white)

**Location Rule:**
```json
"location": [
    [{
        "param": "page_type",
        "operator": "==",
        "value": "posts_page"
    }]
]
```

This targets the page set as "Posts page" in Settings > Reading.

**Template Implementation:** `themes/ay-aip-base/home.php`
```php
// Get the posts page ID
$posts_page_id = get_option( 'page_for_posts' );

// Get hero fields
$heading           = get_field( 'news_hero_heading', $posts_page_id );
$subheading        = get_field( 'news_hero_subheading', $posts_page_id );
$background_image  = get_field( 'news_hero_background_image', $posts_page_id );
$overlay_color     = get_field( 'news_hero_overlay_color', $posts_page_id );
$overlay_opacity   = get_field( 'news_hero_overlay_opacity', $posts_page_id );
$text_color        = get_field( 'news_hero_text_color', $posts_page_id );

// Set defaults
if ( ! $overlay_color ) {
    $overlay_color = '#223a69';
}
if ( ! $overlay_opacity ) {
    $overlay_opacity = 30;
}
if ( ! $text_color ) {
    $text_color = '#ffffff'; // Fixed from navy to white
}

// Convert overlay color to rgba
if ( function_exists( 'ay_aip_base_hex_to_rgba' ) ) {
    $overlay_rgba = ay_aip_base_hex_to_rgba( $overlay_color, $overlay_opacity / 100 );
}
```

**Key Improvement:** Uses the same overlay opacity pattern as Hero Section, making it consistent with the rest of the theme. Text now displays in white by default for proper contrast.

---

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

**Problem:** After editing ACF JSON files manually, WordPress showed multiple field groups as needing sync even after syncing.

**Cause:** The `modified` timestamps in several JSON files were set to future dates (November 2025).

**Solution:** Updated timestamps to current time (`1762818599` = November 2024) in:
- `group_page_sections.json` line 1586
- `group_block_hero.json` line 175
- `group_theme_settings.json` line 463
- `group_news_page_hero.json` line 79 (set correctly on creation)

**Why This Matters:** ACF compares the `modified` timestamp in the JSON file with the timestamp stored in the database. If the JSON file has a newer timestamp, ACF flags it for sync. A future timestamp caused perpetual sync warnings.

## File Architecture

```
themes/ay-aip-base/
├── acf-json/
│   ├── group_page_sections.json          # Hero Section, Value Cards, Product Offerings
│   ├── group_block_hero.json             # Hero Block (Gutenberg)
│   ├── group_theme_settings.json         # Global theme settings
│   └── group_news_page_hero.json         # News page hero (NEW)
├── inc/
│   ├── helpers.php                        # Helper functions (hex_to_rgba, button styles)
│   └── demo-import.php                    # Demo content seeder
├── template-parts/blocks/
│   ├── hero-section.php                   # Hero section template
│   ├── value-cards.php                    # Value Cards template (UPDATED)
│   └── product-offerings.php              # Product Offerings template (UPDATED)
├── home.php                               # News/blog index template (UPDATED)
└── css/
    └── style.css                          # Compiled CSS (hover styles, pattern positioning)

scss/
└── _layout.scss                           # Button hover + pattern positioning SCSS
```

## How It Works Together

1. **User edits page** → ACF fields capture values in organized Content/Style tabs
2. **Template renders** → Component templates fetch field values with sensible defaults
3. **Helper functions process** → `ay_aip_base_hex_to_rgba()` combines color + opacity, button helper generates CSS custom properties
4. **Conditional rendering** → Templates show/hide elements (patterns, overlays) based on user choices
5. **CSS applies styles** → SCSS rules consume custom properties and modifier classes (.pattern-top-left, etc.)
6. **Pattern positioning** → CSS transforms flip the diagonal pattern based on position selection

## Key Defaults

| Component | Field | Default Value | Reason |
|-----------|-------|---------------|--------|
| **All Components with Overlays** | Overlay Color | `#223a69` (Navy) | Brand color |
| **All Components with Overlays** | Overlay Opacity | `30` (30%) | Good readability balance |
| **All Components with Overlays** | Text Color | `#ffffff` (White) | Contrast on dark overlays |
| **Hero Section** | Button Hover BG | `#ffffff` (White) | Creates inversion effect |
| **Hero Section** | Button Hover Text | `#223a69` (Navy) | Contrast on white background |
| **Hero Section** | Button Hover Border | Inherits normal border | Visual consistency |
| **Value Cards** | Background | `white` | Clean, neutral default |
| **Value Cards** | Show Pattern | `1` (true) | Adds visual interest |
| **Value Cards** | Pattern Position | `top-right` | Standard corner decoration |
| **Product Offerings** | Background | `white` | Clean, neutral default |
| **Product Offerings** | Show Pattern | `1` (true) | Adds visual interest |
| **Product Offerings** | Pattern Position | `top-right` | Standard corner decoration |

## WordPress Admin UX

All components now follow consistent tab organization for better user experience.

### Hero Section Example

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

### Value Cards / Product Offerings Example

**Content Tab:**
- Section Heading
- Cards/Items Repeater
  - Title
  - Description
  - Icon/Image

**Style Tab:**
- **Background & Pattern** (message field header)
  - Background Color (50% width) - Dropdown: White/Light/Navy
  - Show Diagonal Pattern (50% width) - Toggle switch
  - Pattern Position (50% width) - Dropdown: Top Right/Top Left/Bottom Right/Bottom Left
    - (Only appears when pattern is enabled - conditional logic)

### News Page Hero Example

**Single Group (No Tabs):**
- Heading
- Subheading
- Background Image
- Overlay Color (50% width)
- Overlay Opacity (50% width - slider)
- Text Color

## Why These Changes Matter

1. **Separated Opacity Control** - Non-technical users can adjust overlay darkness without understanding rgba() syntax
2. **Default Hover Colors** - Buttons work out-of-the-box with professional hover effects
3. **Transparent Outline Fix** - Buttons render correctly per Bootstrap conventions
4. **Organized Fields with Tabs** - Content/Style separation makes it easier to find relevant fields
5. **Visual Section Headers** - Message fields act as labels to group related controls
6. **Flexible Overrides** - Sensible defaults mean components work immediately, but can be customized
7. **Pattern Positioning Control** - Users can place decorative elements in any corner without CSS knowledge
8. **Conditional Logic** - Irrelevant fields hide automatically, reducing cognitive load
9. **Consistent Patterns** - Same organizational structure across all components makes theme easier to learn
10. **Simplified Icon Handling** - Removed confusing unused fields, clearer interface for content editors
11. **Editable News Hero** - Previously hardcoded content now fully customizable through ACF
12. **Fixed Text Contrast** - News page text now displays in white for proper readability on dark overlays

## Technical Notes for AI/Developers

### ACF Field Types Used

- **Tab field** (`type: "tab"`) - Creates tabbed interfaces for organizing fields
- **Message field** (`type: "message"`) - Visual section headers that don't store data
- **Range field** (`type: "range"`) - Slider UI for numeric values (better UX than number input)
- **True/False field** (`type: "true_false"`) - Toggle switch UI when `ui: 1` is set
- **Select field** (`type: "select"`) - Dropdown for predefined choices
- **Color Picker** (`type: "color_picker"`) - Returns hex color values
- **Conditional Logic** - Show/hide fields based on other field values using `conditional_logic` array

### Technical Implementation Details

- **ACF JSON Sync** - Uses Unix timestamps (`modified` field) for version control tracking
- **CSS Custom Properties** - `--btn-hover-bg` allows dynamic button styles without rebuilding CSS
- **Helper Functions** - Centralize logic (e.g., `ay_aip_base_hex_to_rgba()`) for reuse across templates
- **CSS Transforms** - `scaleX(-1)`, `scaleY(-1)`, `scale(-1, -1)` flip pattern image for positioning
- **Wrapper Widths** - Percentages (`"width": "50"`) create responsive multi-column layouts in admin
- **Important Flag** - `!important` necessary to override Bootstrap's built-in button hover styles
- **Demo Importer Consistency** - Uses same helper functions to ensure imported content matches field structure
- **get_option('page_for_posts')** - WordPress function to retrieve the posts page ID for custom queries
- **Conditional Rendering** - PHP conditionals in templates control element display based on ACF values

### Pattern Positioning System

The diagonal pattern system uses:
1. **Position properties** (top/bottom/left/right) to place the element
2. **CSS transforms** to flip the background image:
   - `scaleX(-1)` - Horizontal flip
   - `scaleY(-1)` - Vertical flip
   - `scale(-1, -1)` - Both directions
3. **Modifier classes** (.pattern-top-right, .pattern-top-left, etc.) for easy template application
4. **Mix-blend-mode** and **opacity** for subtle visual integration

### Field Organization Best Practices

1. Use wrapper widths to create logical column layouts (50% for side-by-side, 33% for three columns)
2. Place related fields next to each other with appropriate widths
3. Use message fields as visual separators between field groups
4. Apply conditional logic to hide advanced/optional fields until needed
5. Set meaningful defaults so components work without configuration
6. Use instructions parameter to provide contextual help for complex fields

## Applying This Pattern to Future Components

When creating or updating ACF components, follow this checklist:

### 1. Field Organization
- [ ] Create Content and Style tabs (if component has both content and styling options)
- [ ] Use message fields as section headers (e.g., "Background & Pattern", "Typography")
- [ ] Group related fields using wrapper widths (50% for pairs, 33% for triplets)
- [ ] Add clear instructions for complex fields

### 2. Sensible Defaults
- [ ] Set default values for every field
- [ ] Ensure component displays correctly with no user input
- [ ] Use brand colors (#223a69 navy, #ffffff white) as defaults
- [ ] Default to enabled state for visual features (patterns, overlays)

### 3. Granular Control
- [ ] Separate color from opacity (two fields: color picker + range slider)
- [ ] Provide position/alignment options where relevant
- [ ] Include toggle switches for show/hide features
- [ ] Add hover states for interactive elements

### 4. Conditional Logic
- [ ] Hide advanced options until base option is enabled
- [ ] Show position controls only when element is visible
- [ ] Display size/scale options only when relevant

### 5. Template Implementation
- [ ] Fetch all fields with fallback defaults in PHP
- [ ] Use helper functions for complex transformations (rgba conversion, etc.)
- [ ] Conditionally render elements based on toggle fields
- [ ] Apply modifier classes based on user selections

### 6. CSS/SCSS
- [ ] Use CSS custom properties for dynamic values
- [ ] Create modifier classes for user-selectable variations
- [ ] Use clamp() for responsive sizing
- [ ] Add appropriate transitions for interactive elements

### 7. Testing
- [ ] Test with all defaults (no user input)
- [ ] Test with custom values in all fields
- [ ] Test all conditional logic paths
- [ ] Test all background/pattern combinations
- [ ] Verify mobile responsiveness

### Example: Creating a New "Team Grid" Component

Following this pattern, a Team Grid component would include:

**Content Tab:**
- Section heading
- Team members repeater:
  - Photo
  - Name
  - Title
  - Bio

**Style Tab:**
- Background & Pattern section:
  - Background color (white/light/navy)
  - Show pattern toggle
  - Pattern position (conditional on pattern toggle)
- Grid Layout section:
  - Columns (2/3/4 column options)
  - Card style (default/bordered/shadow)
- Typography section:
  - Name color
  - Title color

This maintains consistency with existing components while providing appropriate controls for team member display.
