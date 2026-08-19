---
name: Scrollify
colors:
  surface: "#fbf9f5"
  surface-dim: "#dbdad6"
  surface-bright: "#fbf9f5"
  surface-container-lowest: "#ffffff"
  surface-container-low: "#f5f3ef"
  surface-container: "#efeeea"
  surface-container-high: "#eae8e4"
  surface-container-highest: "#e4e2de"
  on-surface: "#1b1c1a"
  on-surface-variant: "#4d4354"
  inverse-surface: "#30312e"
  inverse-on-surface: "#f2f0ed"
  outline: "#7e7385"
  outline-variant: "#cfc2d6"
  surface-tint: "#6F39EE"
  primary: "#6F39EE"
  on-primary: "#ffffff"
  primary-container: "#9c48ea"
  on-primary-container: "#fffbff"
  inverse-primary: "#ddb7ff"
  secondary: "#b61722"
  on-secondary: "#ffffff"
  secondary-container: "#da3437"
  on-secondary-container: "#fffbff"
  tertiary: "#006947"
  on-tertiary: "#ffffff"
  tertiary-container: "#00855b"
  on-tertiary-container: "#f5fff6"
  # --- BARU: token amber/kuning (dipakai di header "Rekomendasi", bintang rating,
  # dan badge ranking Populer). Sebelumnya warna ini dipakai di UI tapi tidak
  # terdaftar sebagai token resmi.
  accent-amber: "#F2B705"
  on-accent-amber: "#1b1c1a"
  amber-container: "#FDE68A"
  on-amber-container: "#1b1c1a"
  error: "#ba1a1a"
  on-error: "#ffffff"
  error-container: "#ffdad6"
  on-error-container: "#93000a"
  primary-fixed: "#f0dbff"
  primary-fixed-dim: "#ddb7ff"
  on-primary-fixed: "#2c0051"
  on-primary-fixed-variant: "#6900b3"
  secondary-fixed: "#ffdad7"
  secondary-fixed-dim: "#ffb3ad"
  on-secondary-fixed: "#410004"
  on-secondary-fixed-variant: "#930013"
  tertiary-fixed: "#6ffbbe"
  tertiary-fixed-dim: "#4edea3"
  on-tertiary-fixed: "#002113"
  on-tertiary-fixed-variant: "#005236"
  background: "#fbf9f5"
  on-background: "#1b1c1a"
  surface-variant: "#e4e2de"
  border-black: "#000000"
  shadow-black: "#000000"
  pure-white: "#FFFFFF"
typography:
  display-lg:
    fontFamily: Space Grotesk
    fontSize: 48px
    fontWeight: "700"
    lineHeight: "1.1"
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Space Grotesk
    fontSize: 32px
    fontWeight: "700"
    lineHeight: "1.2"
  headline-md:
    fontFamily: Space Grotesk
    fontSize: 24px
    fontWeight: "700"
    lineHeight: "1.2"
  body-lg:
    fontFamily: Space Grotesk
    fontSize: 18px
    fontWeight: "500"
    lineHeight: "1.5"
  body-md:
    fontFamily: Space Grotesk
    fontSize: 16px
    fontWeight: "500"
    lineHeight: "1.5"
  label-lg:
    fontFamily: Space Grotesk
    fontSize: 14px
    fontWeight: "700"
    lineHeight: "1"
  label-sm:
    fontFamily: Space Grotesk
    fontSize: 12px
    fontWeight: "600"
    lineHeight: "1"
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  border-width-thick: 4px
  border-width-standard: 3px
  # --- BARU: border width khusus tag/badge kecil (NEW, UP, PROJECT, MIRROR, ranking).
  # Sebelumnya spec Tags menyebut "2px Black border" tapi tidak ada token untuk itu,
  # jadi developer bisa salah pakai border-width-standard (3px).
  border-width-tag: 2px
  # --- BARU: border untuk avatar/icon bulat, disebut di Shapes tapi belum ada angkanya.
  border-width-circular: 3px
  shadow-offset: 5px
  # --- BARU: shadow lebih tipis untuk card padat di grid Update (6 kolom desktop),
  # supaya tidak terlalu berat dibanding card besar di Rekomendasi/Populer.
  shadow-offset-compact: 3px
  gutter: 1.5rem
  margin-mobile: 1rem
  margin-desktop: 2.5rem
---

## Brand & Style

This design system is built on the principles of **High-Contrast Neobrutalism**, specifically tailored for a high-energy comic reading experience. It rejects the softness and subtlety of modern minimalism in favor of raw, impactful elements that mirror the expressive nature of graphic novels.

The aesthetic is characterized by intentional "heaviness"—thick strokes, unyielding shadows, and a vibrant palette that demands attention. The target audience is younger, digitally native readers who appreciate a bold, editorial-style interface that feels as dynamic as the content they consume. The UI should evoke a sense of urgency, excitement, and tactile interaction.

## Colors

The palette is anchored by a warm, off-white background to prevent eye strain during long reading sessions, while the interactive layer is dominated by high-saturation "electric" colors.

- **Primary Purple:** Used exclusively for active navigation states, primary buttons, and selected tab indicators.
- **Accent Red:** Reserved for high-priority signals such as "NEW" tags, "UP" updates, and hot status indicators.
- **Accent Green:** Used for secondary positive signals or alternative status tags.
- **Accent Amber:** Used for section headers that signal curation/ranking (e.g. "Rekomendasi"), star-rating indicators, and numbered ranking badges in "Populer". Always pairs with `on-accent-amber` (near-black) text for contrast — never white text on amber.
- **Neutral Background:** A cream-based off-white provides a comic-page feel, contrasting sharply with the **Pure White** used for content cards.
- **Structural Black:** Every element is defined by a deep, absolute black used for borders and solid block shadows.

**Usage rule:** every color used anywhere in the UI must exist as a token in this file before it ships. If a screen needs a new color, add it here first — don't hardcode a hex value in a component.

## Typography

This design system uses **Space Grotesk** across all levels to maintain a cohesive, technical, and chunky aesthetic. The typography is treated as a structural element rather than just information.

- **Headlines:** Must be set in Bold (700) with tight line heights to maximize impact.
- **Chunky Labels:** Small tags (like "NEW" or "Chapter #") use heavy weights and uppercase styling to ensure legibility against high-contrast backgrounds.
- **Scaling:** On mobile devices, `display-lg` should scale down to `headline-lg` to maintain readability without overwhelming the screen.

## Layout & Spacing

The layout follows a **Fixed Grid** philosophy on desktop to create a structured, "newspaper" comic feel.

- **Desktop:** 12-column grid with 24px (1.5rem) gutters. Content is contained within a max-width container to prevent sprawling.
- **Mobile:** Fluid single-column layout with 16px (1rem) side margins.
- **Brutalist Spacing:** Elements do not use soft padding; spacing is rhythmic and calculated in 4px increments to align with border widths.
- **Grid Reflow:**
  - **Updates:** 6 columns on desktop, 3 on tablet, 2 on mobile.
  - **Popular:** 8 narrow columns on desktop, 4 on tablet, 3 horizontal scroll on mobile.

## Elevation & Depth

Depth is conveyed through **Hard Solid Shadows** (Block Shadows) rather than light-source simulation.

- **Block Shadows:** Large cards and buttons (Recommendation cards, Popular cards, primary buttons) use `shadow-offset` → `5px 5px 0px 0px #000000`.
- **Compact Block Shadows:** Dense grid cards (Update section, 6-column desktop grid) use `shadow-offset-compact` → `3px 3px 0px 0px #000000`. This keeps tightly packed cards from visually competing with the heavier shadows on hero/featured content.
- **No Blurs:** Gaussian blurs are strictly prohibited.
- **No Gradients (default):** Flat fills only, everywhere, by default — gradients break the flat "cut-out sticker" read that block shadows depend on.
  - **Exception — Popular ranking cards only:** the top-5 ranking cards in "Populer" may use a two-stop flat-toned gradient overlay behind the rank number, to visually differentiate the ranking module from standard comic cards. This is the _only_ place gradients are permitted. Any other section using a gradient is a design bug, not a style choice.
- **Active State Elevation:** On hover or click, the shadow offset may decrease to `2px 2px`, simulating the element being physically pressed into the page. For compact cards, this reduces further to `1px 1px`.

## Shapes

The shape language is "Softly Sharp." While the design is aggressive, a subtle radius of **4px (0.25rem)** on borders prevents the UI from feeling dangerously sharp, providing just enough friendliness for a consumer app.

- **Standard Elements:** Buttons, cards, and input fields use `rounded-sm`.
- **Large Containers:** Hero banners and section wrappers use `rounded-md`.
- **Circular Elements:** Avatars and icon-only buttons remain fully circular, and use `border-width-circular` (3px) black border — thinner than standard cards since circular shapes read as "heavier" at the same border weight due to their continuous outline.

## Components

### Buttons

- **Primary:** Bright Purple (`primary`) background, `border-width-thick` (4px) Black border, `shadow-offset` (5px) Hard Black shadow, Bold White (`on-primary`) text.
- **Secondary:** Stark White (`pure-white`) background, `border-width-standard` (3px) Black border, `shadow-offset` (5px) Hard Black shadow.
- **Hover State:** Translate element `-2px -2px` and increase shadow, or translate `+2px +2px` and remove shadow for a "pressed" effect.

### Cards (Comic & Update)

- **Background:** Pure White (`pure-white`).
- **Standard cards (Rekomendasi, Populer):** `border-width-standard` (3px) or `border-width-thick` (4px) Solid Black border, `shadow-offset` (5px) Black Block Shadow.
- **Compact cards (Update grid):** `border-width-standard` (3px) Solid Black border, `shadow-offset-compact` (3px) Black Block Shadow — see Elevation & Depth.
- **Internal Structure:** Thumbnails should be flush to the top or have a consistent 8px internal margin. Meta-info (Chapter lists) should be contained in their own sub-bordered boxes.

### Tags (NEW / UP / Genre / Ranking)

- **NEW/UP:** Bright Red (`secondary`) background, `border-width-tag` (2px) Black border, White (`on-secondary`) Bold text.
- **Genre/Status:** Purple (`primary`) or Green (`tertiary`) backgrounds, `border-width-tag` (2px) Black border.
- **Rating/Star indicator:** Amber (`accent-amber`) fill, `border-width-tag` (2px) Black border, near-black (`on-accent-amber`) text — never white text on amber, contrast ratio fails.
- **Positioning:** Absolute positioning on the top-left or top-right of comic covers.

### Input Fields

- Stark White background, `border-width-standard` (3px) Black border.
- Focus state: Change border to Primary Purple or increase border thickness.
- Placeholder text: Mid-gray (`on-surface-variant`), using the `label-lg` font style.

### Navigation Bar

- Background: White.
- Bottom Border: `border-width-thick` (4px) Solid Black.
- Nav Links: Bold sans-serif; active link has a Purple (`primary`) background with a Black border (pill-shaped, `rounded-full`).
