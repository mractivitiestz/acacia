# mractivities/design-system

The Mr Activities CSS framework: plain CSS in native cascade layers, cut from the design engine's stylesheet, shipped
as a Composer package that is also a Symfony bundle exposing its files to AssetMapper. No Node, no preprocessor.

## Contents

- [What it is](#what-it-is)
- [Install](#install)
- [Layers](#layers)
- [Naming rules](#naming-rules)
- [Adding a component](#adding-a-component)
- [Docs](#docs)
- [Tests](#tests)
- [Palettes](#palettes)
- [The class mapping](#the-class-mapping)

## What it is

- `assets/mractivities.css`: the entry. It declares the layer order once and imports every file into its layer.
- `assets/tokens/`: custom properties only (palette, fixed colours, type, space, radius, shadow, motion, z) and
  `breakpoints.md`.
- `assets/base/`, `assets/layout/`, `assets/components/` (one file per component, with its states and sizes),
  `assets/pages/` (what belongs to one page kind), `assets/utilities/utilities.css`.
- `assets/vendor/leaflet.css`: Leaflet 1.9.4's stylesheet (BSD 2-Clause, `assets/vendor/LEAFLET-LICENSE`), in the
  lowest layer so the map skin overrides it without `!important`.
- `dist/mractivities.css`: every file concatenated in layer order, for production.

## Install

**Symfony (AssetMapper).**

```bash
composer require mractivities/design-system
```

Register `MrActivities\DesignSystem\MrActivitiesDesignSystemBundle` (Flex does it). Its `prependExtension()` adds
`assets/` to `framework.asset_mapper.paths` under the namespace `@mractivities/design-system`, so the layout links:

```twig
<link rel="stylesheet" href="{{ asset('@mractivities/design-system/mractivities.css') }}">
```

AssetMapper versions the entry and every file it imports. The bundle defines no services.

**The design engine (static).** Require the package with Composer (a `path` repository while both are developed side
by side) and link the file directly:

```html
<link rel="stylesheet" href="{root}../vendor/mractivities/design-system/dist/mractivities.css">
```

Link `assets/mractivities.css` instead while editing the framework, so a change shows without a build.

## Layers

```css
@layer vendor, tokens, base, layout, components, pages, utilities;
```

| Layer | Holds |
|---|---|
| `vendor` | Leaflet's stylesheet |
| `tokens` | custom properties on `:root` and `[data-palette="N"]` |
| `base` | reset, element defaults, focus ring, skip link, page states (`data-locked`, `data-header`, …) |
| `layout` | container, stack and cluster, grids, the detail page's columns |
| `components` | one file per component |
| `pages` | rules for one page kind; a page places components, it never restyles one |
| `utilities` | `.vh`, `.nw`, `.muted`, `.dot`, the language spans, `[hidden]`, print and reduced motion |

A later layer always wins over an earlier one, whatever the specificity, which is why the framework needs no
`!important`. The only two are in `components/map.css`, against inline sizes Leaflet writes itself.

## Naming rules

The C7 scheme:

- **Blocks and parts are classes with one dash**: `.card`, `.card-body`; never deeper. A block name is one word.
- **Variants are data attributes named after the Twig component's props**: `data-variant`, `data-size`,
  `data-width`, `data-as` (the tag a part renders as).
- **States are the ARIA attributes the component needs anyway**: `aria-pressed`, `aria-current`, `aria-expanded`,
  `aria-selected`, `aria-checked`, `aria-invalid`. A visual state ARIA has no word for is `data-state`.
- **No prefix.** The other stylesheets on a page are Leaflet's (`leaflet-`) and Stripe's (in iframes).
- **No element selectors in components**, except replaced elements, form controls and SVG internals
  (`img`, `svg`, `path`, `text`, `input`, `select`, `option`, `textarea`) and Leaflet's own markup.
- **Specificity at most 0,3,0**; `:where()` keeps a selector's context without its weight.
- **Colours only from tokens**: the palette, or `tokens/fixed.css` for what does not follow it (white and shades
  over photographs, the error red). The Apple Pay and Google Pay buttons keep their providers' literal colours.
- **Radii**: `var(--r)` 16 px, `var(--r-s)` 12 px, `var(--r-pill)` 999 px, or `0`, `50%`, `inherit`.

## Adding a component

1. Pick a one-word block name and create `assets/components/<name>.css`; its first line names its blocks.
2. Import it in `assets/mractivities.css` with `layer(components)`.
3. Style the block and its parts with classes, variants with `[data-*]`, states with `[aria-*]`, values with tokens.
4. Add its examples to `docs/index.html`.
5. Run `composer build`, then `composer check`.

## Docs

`docs/index.html` shows every component the engine's pages render, with its variants and states and the HTML to
copy, styled by `dist/mractivities.css`; the palette and logo pickers switch `data-palette` and `data-logo`. Open it
through any static server from the package root, for example `php -S 127.0.0.1:8000` and
`http://127.0.0.1:8000/docs/`. Screenshots at 1440 and 390 px are in `docs/shots/`.

## Tests

```bash
composer check   # php-cs-fixer (dry run), PHPStan at level max, PHPUnit
```

- `tests/Unit/ConformanceTest.php`: the layer order declared once; every file imported once into the layer of its
  directory; C7 selectors with specificity at most 0,3,0; literal colours only in tokens; the three radii; no
  `!important` beyond the two Leaflet overrides; each class owned by one file; every class the docs use is defined;
  `dist/` equal to the concatenation of the sources.
- `tests/Unit/PaletteServiceTest.php`: palettes 3 and 22 equal the engine's computed tones; the shipped palette file
  is fresh.
- `tests/Functional/BundleTest.php`: a kernel with FrameworkBundle and this bundle resolves
  `@mractivities/design-system/mractivities.css` through AssetMapper.

## Palettes

`palettes.json` holds the 24 review palettes. `bin/palettes.php` (run by `composer build`) writes
`assets/tokens/palette.css` with the engine's formula: ink and accent as given, the page on the paper colour, every
other tone a tone of the ink's hue, white or near-black text on brand and accent by contrast, and `--brand-text` /
`--accent-text` falling back to ink below 4.5:1. Palette 3, Highland green and sun, is the default on `:root`;
`<html data-palette="N">` switches to any other. `bin/concat.php` then rebuilds `dist/`.

## The class mapping

`MAPPING.md` (and `mapping.json`) maps every class of the engine's `app.css` and `expeditions.css` to its new hook,
lists the parts that replace element selectors, the dropped dead classes, and the script changes the engine needs to
render from this package.
