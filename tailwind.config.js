/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Primary Background Canvas (Soft Off-White Slate)
        'canvas': '#F8FAFC',
        'surface': '#FFFFFF',
        
        // Harmonized Secondary Blue Palette (Tailored UI Navy/Royal Blue)
        'brand': {
          'dark': '#0F172A',      // Darkest Slate for primary text & headers
          'navy': '#1E293B',      // Deep Navy for active sidebar & containers
          'primary': '#1E40AF',   // Balanced UI Navy Blue for primary actions/buttons
          'active': '#2563EB',    // Vibrant Blue for active indicators & hover
          'soft': '#EFF6FF',      // Soft tinted blue background for selected cards
        },
        
        // Muted Accent Colors (Balanced & Non-glaring)
        'amber-accent': {
          DEFAULT: '#D97706',
          'soft': '#FEF3C7',
          'text': '#92400E',
        },
        'red-accent': {
          DEFAULT: '#DC2626',
          'soft': '#FEE2E2',
          'text': '#991B1B',
        },
        
        // Crisp High-Contrast Neutrals
        'neutral-text': '#0F172A',
        'neutral-muted': '#64748B',
        'neutral-border': '#E2E8F0',
        'neutral-subtle': '#F1F5F9',

        // Compatibility Aliases
        'primary-bg': '#F8FAFC',
        'surface-card': '#FFFFFF',
        'brand-blue': {
          DEFAULT: '#1E40AF',
          'hover': '#1E293B',
          'light': '#2563EB',
          'soft': '#EFF6FF',
        },
        'accent-gold': {
          DEFAULT: '#D97706',
          'soft': '#FEF3C7',
          'border': '#F59E0B',
        },
        'accent-red': {
          DEFAULT: '#DC2626',
          'soft': '#FEE2E2',
          'border': '#EF4444',
        },
        'neutral-dark': '#0F172A',
      }
    },
  },
  plugins: [],
}
