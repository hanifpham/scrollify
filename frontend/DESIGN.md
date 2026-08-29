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
  # CORRECTED: nilai asli dari kode mockup adalah "danger": "#EF4444"
  # (setara Tailwind red-500), bukan #b61722 seperti dugaan awal.
  secondary: "#EF4444"
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
  # CORRECTED: kode mockup asli pakai Tailwind yellow-400 (#FBBF24), bukan #F2B705.
  accent-amber: "#FBBF24"
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
  # CORRECTED: kode mockup asli pakai "Archivo Black" khusus untuk headline
  # besar (font-display class), BUKAN Space Grotesk. Space Grotesk tetap
  # dipakai untuk body text dan label.
  display-lg:
    fontFamily: Archivo Black
    fontSize: 48px
    fontWeight: "900"
    lineHeight: "1.1"
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Archivo Black
    fontSize: 32px
    fontWeight: "900"
    lineHeight: "1.2"
  headline-md:
    fontFamily: Archivo Black
    fontSize: 24px
    fontWeight: "900"
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
# CORRECTED: kode mockup asli TIDAK memakai radius sama sekali di elemen
# manapun (sudut tajam total), kecuali elemen circular murni (avatar).
# Token rounded-sm/md/lg/xl DIHAPUS dari pemakaian; hanya "full" (lingkaran)
# yang masih relevan.
rounded:
  none: 0px
  full: 9999px
spacing:
  # CORRECTED: border width standard komponen besar (card, hero, section
  # header, tombol) adalah 4px. border-width-standard (3px) sebelumnya
  # tidak pernah benar-benar dipakai di kode asli, dihapus.
  border-width-thick: 4px
  border-width-tag: 2px
  border-width-circular: 3px
  # CORRECTED: sistem shadow 3 tier, ganti total dari shadow-offset (5px)
  # dan shadow-offset-compact (3px) yang merupakan dugaan awal yang salah.
  shadow-offset-badge: 4px
  shadow-offset-button: 4px
  shadow-offset-heavy: 10px
  gutter: 1.5rem
  margin-mobile: 1rem
  margin-desktop: 2.5rem
  container-max-width: 1440px
  container-padding: 1.5rem
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

## Layout & Spacing (CORRECTED — verified against actual mockup source)

- **Container:** Every page wraps its content (navbar inner row, main
  sections, footer inner content) in a single shared container:
  `max-w-[1440px] mx-auto p-6`. This is non-negotiable — sections must not
  invent their own width, or content sprawls edge-to-edge on wide screens.
- **Mobile:** Same container, padding shrinks responsively; no separate
  mobile-only margin system.
- **Brutalist Spacing:** Elements do not use soft padding; spacing is
  rhythmic and calculated in 4px increments to align with border widths.
- **Grid Reflow (exact values from source):**
  - **Rekomendasi:** `grid-cols-2` mobile → `md:grid-cols-3` tablet →
    `lg:grid-cols-5` desktop, `gap-8`. Responsive GRID at every breakpoint —
    NOT horizontal scroll on mobile.
  - **Update:** `grid-cols-2` mobile → `md:grid-cols-4` tablet →
    `lg:grid-cols-6` desktop, `gap-8`.
  - **Populer:** Always horizontal scroll (`flex overflow-x-auto`) at every
    breakpoint. Fixed card width `w-56` (14rem). Numbered rank badge
    overlaps the top-left corner of the cover (`-top-2 -left-2` offset).

## Elevation & Depth

Depth is conveyed through **Hard Solid Shadows** (Block Shadows) rather than light-source simulation. See "Shadow Tiers" below for exact per-element values — there are three tiers (Badge 4px, Button 4px, Card/Heavy 10px), not a flat 5px/3px system.

- **No Blurs:** Gaussian blurs are strictly prohibited.
- **No Gradients:** Flat fills only, everywhere — gradients break the flat "cut-out sticker" read that block shadows depend on. No exceptions verified in source; the Populer ranking cards use flat solid rank-number badges (white/yellow-400/orange-400), not gradients.
- **Active State Elevation:** On hover, shadow offset increases slightly with a small negative translate (lift effect). On active/press, offset reduces to roughly half its resting value with a positive translate (pressed-in effect). Apply per the element's own tier (a Badge presses from 4px→2px, a Card presses from 10px→6px), not a single global value.

## Shapes

**CORRECTED (verified against actual mockup source code):** the shape language is fully sharp, NOT "softly sharp." There is NO border-radius anywhere in the reference implementation — buttons, cards, tags, inputs all have hard 90° corners. This is intentional and central to the raw, sticker-cut-out aesthetic.

- **All Elements:** Buttons, cards, tags, input fields, section headers — `rounded-none` (radius 0) across the board. Do not apply `rounded-sm` or any radius utility.
- **Circular Elements:** Avatars remain fully circular (`rounded-full` is the one exception, for literal circles only, e.g. avatar), with `border-width-circular` (3px) black border.

## Components (CORRECTED — verified against actual mockup source)

### Buttons

- **Primary:** Bright Purple (`primary`) background, `border-width-thick` (4px) Black border, Button-tier shadow (4px), Bold White (`on-primary`) text, uppercase.
- **Secondary:** Stark White (`pure-white`) background, `border-width-thick` (4px) Black border, Button-tier shadow (4px).
- **No radius** on any button.
- **Hover/Active:** see Elevation & Depth active state rules, Button tier.

### Cards

- **Background:** Pure White (`pure-white`), **no radius**.
- **ComicCard (Rekomendasi/Update/Populer covers):** `border-width-thick` (4px) Solid Black border, Card/Heavy-tier shadow (10px). Cover image `aspect-[3/4]`, `object-cover`, flush to the card edges (no internal padding around the image) with `border-b-4 border-black` separating image from text below.
- **Title area under cover:** fixed-height wrapper so 1-line and 2-line titles produce identically-tall cards in a row — use `h-12` (3rem) with `line-clamp-2`, OR `line-clamp-1` centered (Rekomendasi style: centered, uppercase, single line) vs left-aligned with an "UP" prefix tag (Update style: left-aligned, two lines, `h-12` fixed).
- **Hero/Announcement panel:** same Card/Heavy tier (10px shadow, 4px border).

### Tags & Badges

- **Badge tier** (2px border, 4px shadow): rating star badge, views-count badge, format badge (MANGA/MANHWA/MANHUA), NEW/UP tag, relative-time badge, language flag badge.
- **NEW/UP:** Bright Red (`secondary` = `#EF4444`) background, White (`on-secondary`) Bold text, uppercase.
- **Rating/Star indicator:** Amber (`accent-amber` = `#FBBF24`) fill, near-black (`on-accent-amber`) text — never white text on amber, contrast ratio fails.
- **Positioning:** Absolute positioning on the top-left or top-right of comic covers; multiple badges on the same corner stack vertically with a small gap, never overlapping.
- **No radius** on any tag/badge.

### Input Fields

- Stark White background, `border-width-thick` (4px) Black border, **no radius**.
- Focus state: Change border to Primary Purple or increase border thickness.
- Placeholder text: Mid-gray (`on-surface-variant`), using the `label-lg` font style.

### Navigation Bar

- Background: White.
- Bottom Border: `border-width-thick` (4px) Solid Black.
- Nav Links: Bold sans-serif; active link has a Purple (`primary`) background with a Black border (pill-shaped, `rounded-full`).
