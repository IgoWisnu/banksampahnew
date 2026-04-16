# Banksampah Styling Rules

## 1. Core Framework
- **CSS Framework**: Tailwind CSS (via CDN)
- **Icons**: Inline SVG Icons (Heroicons or similar modern style)
- **Typography**: `Inter` from Google Fonts
  - **Body text**: text-gray-500 to text-gray-800
  - **Headings**: text-gray-900, font-bold or font-semibold

## 2. Color Palette
Define these in your `tailwind.config` configuration script on every standalone page:
- **Brand Green (Primary)**: `#00926E` (`brand-green`)
- **Brand Dark (Hover/Active)**: `#006c50` (`brand-dark`)
- **Brand Yellow (Accent/Secondary)**: `#f59e0b` (`brand-yellow`)
- **Brand Light**: `#fef3c7` (`brand-light`)

*(Additional helpers: `teal-500` is used for gradient bridges, standard standard red/green for alerts)*

## 3. Backgrounds and Containers
- **Main Container Background (Auth/Guest pages)**: 
  `bg-gradient-to-br from-brand-green via-teal-500 to-brand-yellow`
- **Card Containers**: 
  `bg-white/95 backdrop-blur-sm shadow-2xl rounded-[2rem]`
- **Desktop Grid Expansion**: 
  For auth/registration cards, expand from `max-w-md` (mobile) to `max-w-4xl flex-row` (desktop). Use responsive prefixes (`md:flex`, `hidden md:block`, etc) for layout switching.

## 4. Forms & Inputs
- **Input Fields**: 
  `w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200`
- **Labels**: 
  `block text-sm font-semibold text-gray-700 mb-2`
- **Validation Blocks**: 
  Use `<small class="text-red-500 text-xs mt-1 block">`

## 5. Buttons & Micro-Interactions
- **Primary Button (Green)**:
  `bg-brand-green hover:bg-brand-dark text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-brand-green/30 transition-colors active:scale-95 duration-200`
- **Secondary/Accent Button (Yellow)**:
  `bg-brand-yellow hover:bg-yellow-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-brand-yellow/30 transition-colors active:scale-95 duration-200`
- Always apply standard hover state transitions `transition-colors overflow-hidden transform hover:scale-[1.01] active:scale-95`.

## 6. Layout Style
- **Responsive Approach**: All pages (auth, homepage, riwayat, etc.) should utilize responsive, full-screen widths on desktop. 
- **Main Container**: Use `w-full min-h-screen` and consider constraining maximal inner content width (e.g. `max-w-7xl mx-auto`) for readability on ultrawide monitors. Do not use constrained mobile app widths (`max-w-md`) on desktop unless specifically required.
- **Bottom Navigation**: Ensure the bottom navigation bar expands logically on larger screens or is safely anchored to the bottom.
