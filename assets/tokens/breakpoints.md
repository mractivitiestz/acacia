# Breakpoints

Custom properties cannot drive `@media`, so the widths are written in the media queries and listed here.

## Contents

- [Widths](#widths)
- [Strays](#strays)

## Widths

| Width | Meaning |
|---|---|
| `max-width: 700px` | phones |
| `max-width: 1080px` | tablets and small laptops: the header folds into the phone menu, the detail page has one column |
| `max-width: 1240px` | narrow desktops: fewer columns in grids |

The screens checked are 390, 820 and 1440 px wide.

## Strays

Carried over from the engine with the rules that use them, to fold into the three widths when those components are
next touched: 479 px (language menu), 600 px (map popup), 640 px (map key), 720 px (two days), 1279 px (header
navigation), and `min-width: 1081px` (the desktop-only header search).
