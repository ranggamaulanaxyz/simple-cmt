# Design System Specification: The Luminous Analytical Interface

## 1. Overview & Creative North Star
**Creative North Star: "The Luminous Architect"**
This design system is built to transform complex data environments into a high-clarity, editorial experience. Shifting from a nocturnal focus to a bright, energized workspace, it rejects the "Bootstrap" aesthetic in favor of **Organic Structuralism**. By pairing the deep authority of `#2D1B4D` (Deep Purple) with the kinetic energy of `#F97316` (Vibrant Orange) and the sophisticated highlights of `#FFCD71` (Warm Gold) against a pure white canvas, we create an environment that feels both professional and hyper-modern.

The goal is to move away from a flat "dashboard" and toward a "command center" that thrives in daylight. We achieve this through intentional asymmetry—placing heavy data visualizations against generous negative space—and utilizing tonal layering to guide the eye without the clutter of traditional structural lines.

---

## 2. Colors
Our palette is rooted in a light, high-clarity spectrum designed for optimal readability and focus during extended use.

### Core Palette
- **Primary (Deep Purple):** `#2D1B4D`. Provides the foundational "ink" and structural grounding.
- **Secondary (Vibrant Orange):** `#F97316`. This is our "Action Signal." Use it to draw immediate attention to critical data points or primary conversions.
- **Tertiary (Warm Gold):** `#FFCD71`. Used for supplementary accents, badges, and decorative highlights that require distinction from the primary action color.
- **Neutral Surface:** `#FFFFFF`. A pure, high-luminance base provides the global canvas for the interface.

### The "No-Line" Rule
**Explicit Instruction:** Designers are prohibited from using 1px solid borders to define major UI sections. 
Structure must be achieved through:
- **Background Shifts:** Placing a `surface-container-low` component on a `surface` background.
- **Tonal Contrast:** Using `surface-container-high` to distinguish a sidebar from the main canvas.

### Surface Hierarchy & Nesting
Treat the UI as a series of physical layers. Use the `surface-container` tiers to create "nested depth":
1. **Base:** `surface` - The global white canvas.
2. **Low:** `surface-container-low` - Secondary sidebars or navigation wraps.
3. **High:** `surface-container-high` - Primary content cards.
4. **Highest:** `surface-container-highest` - Popovers and active state overlays.

### The "Glass & Gradient" Rule
To prevent the UI from feeling "flat," main action areas should utilize subtle gradients. 
- **Signature CTA:** A linear gradient from `secondary` (#F97316) to a deeper `secondary_container` variant at a 135-degree angle.
- **Glassmorphism:** For floating elements like tooltips or "sticky" headers, use `surface_bright` at 80% opacity with a `24px` backdrop blur.

---

## 3. Typography
We use **Manrope** exclusively. Its geometric yet approachable form factor supports the "Analytical" feel while remaining premium.

### Hierarchy Guidelines
- **Display (Display-LG/MD):** Reserved for high-level "Hero" metrics. Use `on_surface` with a `-0.02em` letter spacing to feel tight and custom.
- **Headlines & Titles:** Use `headline-sm` for card titles. These are the anchors of your layout.
- **Body & Labels:** `body-md` is the workhorse for data. For technical labels (e.g., "Timestamp"), use `label-sm` in all-caps with `0.05em` letter spacing to evoke a precise, digitized feel.

---

## 4. Elevation & Depth
In this system, elevation is a matter of **light and tone**, not structural geometry.

- **The Layering Principle:** Depth is achieved by "stacking." A card sits on a lower-tier section. In light mode, these subtle shifts in brightness against the `#FFFFFF` base are sufficient for the eye to perceive hierarchy.
- **Ambient Shadows:** When an element must "float" (e.g., a Modal), use an extra-diffused, soft shadow: `0px 24px 48px rgba(45, 27, 77, 0.1)`. The shadow must never be pure black; it should feel like a soft occlusion influenced by the primary brand color.
- **The "Ghost Border" Fallback:** If accessibility requires a container edge, use the `outline-variant` token at **15% opacity**. This creates a "suggestion" of a border that maintains the "No-Line" philosophy.

---

## 5. Components

### Buttons
- **Primary:** Gradient from `secondary` to `secondary_container`. High-contrast text (`on_secondary`). Subtle roundedness (0.25rem).
- **Secondary (Outline):** Ghost Border (outline-variant @ 20%) with `primary` (#2D1B4D) text.
- **Tertiary:** No background. Bold `on_surface_variant` text that shifts to `primary` on hover.

### Input Fields
- **Surface:** Use `surface_container_lowest`. 
- **States:** On focus, the field should not gain a heavy border. Instead, use a subtle `primary` outer glow (2px blur) and change the background to `surface_container`.
- **Error:** Utilize `error` for text and a low-opacity `error_container` fill.

### Cards & Data Lists
- **Rule:** **No divider lines.**
- **Separation:** Separate list items using the **Normal Spacing** scale (spacing: 2) or by alternating background tones.
- **Cards:** Use `surface-container-high`. The header of a card should be distinguished by a slightly larger `title-sm` font weight rather than a line.

### Chips & Tags
- **Filter Chips:** Pill-shaped (`full` roundedness). Use `surface-container-highest` for inactive and `secondary_container` or `tertiary_container` for active states.

---

## 6. Do's and Don'ts

### Do
- **DO** use white space as a structural element. With our **Normal** spacing scale, lean into the editorial feel.
- **DO** use the Vibrant Orange (`secondary`) for "Micro-Moments"—a notification dot, a trend-up arrow, or a radio-select.
- **DO** incorporate Warm Gold (`tertiary`) for secondary visual cues that need to pop without competing with primary CTAs.

### Don't
- **DON'T** use 100% opaque, high-contrast borders. It breaks the "Architect" aesthetic.
- **DON'T** use pure black (#000000) for shadows. Use the primary deep purple tones to maintain color harmony.
- **DON'T** clutter the display. If a screen has more than 5 primary "Action" points, move secondary actions into a "Ghost-Border" overflow menu.

---

## 7. Roundedness Scale
Adhere to these values to maintain the "Subtle Modern" look (System Roundedness: 1):
- **Small (sm):** 0.125rem (For checkboxes/fine details)
- **Medium (md):** 0.25rem (Standard for Buttons/Inputs)
- **Extra Large (xl):** 0.5rem (For primary content Containers/Cards)
- **Full:** 9999px (For Chips and Toggle switches)