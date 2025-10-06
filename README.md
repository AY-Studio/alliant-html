# Alliant AirFinance Website

A faithful recreation of the Alliant AirFinance homepage using Bootstrap 5 with SCSS.

## Project Structure

```
alliant/
├── index.html              # Main HTML file
├── package.json            # Node.js dependencies and scripts
├── scss/                   # SCSS source files
│   ├── _variables.scss     # Custom color variables and Bootstrap overrides
│   ├── _header.scss        # Header and hero section styles
│   ├── _layout.scss        # Main layout and section styles
│   ├── _footer.scss        # Footer styles
│   └── style.scss          # Main SCSS file (imports Bootstrap + partials)
├── css/                    # Compiled CSS output
│   └── style.css           # Compiled from scss/style.scss
└── img/                    # Images folder
    └── Home.jpg            # Reference design image
```

## Features

- **Bootstrap 5.3.2** - Built entirely with Bootstrap's SCSS version
- **Responsive Design** - Fully responsive for desktop, tablet, and mobile
- **Custom Colors** - SCSS variables to match the original design colors
- **Bootstrap Grid** - Uses Bootstrap's grid system throughout
- **Bootstrap Components** - Navbar, cards, buttons, and utilities
- **Clean Code** - Well-commented SCSS and HTML

## Setup Instructions

### 1. Install Dependencies

```bash
npm install
```

This installs:
- Bootstrap 5.3.2
- Sass compiler

### 2. Compile SCSS

**Development mode (uncompressed):**
```bash
npm run build:dev
```

**Production mode (compressed):**
```bash
npm run build
```

**Watch mode (auto-compile on save):**
```bash
npm run watch
```

### 3. Open in Browser

Simply open `index.html` in your web browser:

```bash
open index.html
```

Or use a local server (recommended):

```bash
# Using Python 3
python3 -m http.server 8000

# Using Node.js (npx)
npx http-server
```

Then visit `http://localhost:8000`

## Sections

1. **Header Navigation** - Dark blue navbar with Alliant logo and navigation links
2. **Hero Section** - Full-width hero with background image, overlay, and CTA
3. **Alliant Values** - Three value cards with images and blue overlays
4. **Product Offerings** - Icon grid with 6 financial product types
5. **Asset Types** - Dark blue section with 6 asset type icons
6. **Footer** - Simple footer with logo, navigation, and copyright

## Customization

### Colors

Edit `scss/_variables.scss` to change colors:

```scss
$primary: #2d4a7c;           // Main dark blue
$alliant-navy: #2d4a7c;      // Primary navy blue
$alliant-blue: #3d5a8c;      // Card overlay blue
$alliant-light-gray: #f5f5f5; // Light gray backgrounds
```

### Layout

Edit `scss/_layout.scss` for section spacing, card styles, and grid layouts.

### Typography

Bootstrap's default font stack is used. Customize in `scss/_variables.scss`:

```scss
$font-family-sans-serif: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
```

## NPM Scripts

- `npm run scss` - Watch SCSS files and auto-compile
- `npm run build` - Compile SCSS to compressed CSS
- `npm run build:dev` - Compile SCSS to uncompressed CSS
- `npm run watch` - Watch mode (alias for scss)

## Browser Support

Supports all modern browsers:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Notes

- All layout and spacing uses Bootstrap's built-in utility classes
- Custom colors are defined via SCSS variables
- Icons are inline SVGs for easy customization
- Hero background uses the provided `Home.jpg` image
- Responsive breakpoints follow Bootstrap's defaults (576px, 768px, 992px, 1200px)

## License

MIT
