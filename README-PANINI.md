# Panini Template System

This project now uses **Panini** for page templating, making sections reusable and easy to edit in one place.

## Folder Structure

```
src/
├── pages/          # Page files (index.html, etc.)
├── layouts/        # Layout templates (default.html)
└── partials/       # Reusable sections
    ├── header.html
    ├── hero.html
    ├── values.html
    ├── products.html
    ├── assets.html
    └── footer.html
```

## Editing Sections

All sections are now stored as **partials** in `src/partials/`. To edit content:

1. **Header/Navigation** - Edit `src/partials/header.html`
2. **Hero Section** - Edit `src/partials/hero.html`
3. **Alliant Values** - Edit `src/partials/values.html`
4. **Product Offerings** - Edit `src/partials/products.html`
5. **Asset Types** - Edit `src/partials/assets.html`
6. **Footer** - Edit `src/partials/footer.html`

After editing, run `npm run build:dev` to rebuild the HTML files.

## Build Commands

```bash
# Development build (outputs to root directory)
npm run build:dev

# Production build (outputs to dist/)
npm run build

# Watch mode (auto-rebuild on changes)
npm watch

# Build only HTML pages
npm run build:panini:dev

# Build only SCSS
npm run build:scss:dev
```

## Creating New Pages

1. Create a new file in `src/pages/` (e.g., `about.html`)
2. Add frontmatter at the top:

```html
---
title: About Us
description: Learn more about Alliant AirFinance
---

<!-- Include sections -->
{{> header}}
{{> about-content}}
{{> footer}}
```

3. Create any new partials needed in `src/partials/`
4. Run `npm run build:dev`

## How It Works

- **Layouts** (`src/layouts/default.html`) - Main HTML structure with `<head>`, `<body>`
- **Pages** (`src/pages/`) - Content files that use layouts and include partials
- **Partials** (`src/partials/`) - Reusable sections included with `{{> partial-name}}`

Panini compiles these together into complete HTML files in the root directory (dev) or `dist/` (production).

## Benefits

✅ Edit each section in one place
✅ Reuse sections across multiple pages
✅ Maintain consistent header/footer
✅ Cleaner, more organized codebase
✅ Easy to add new pages

## Learn More

Panini Documentation: https://github.com/foundation/panini
