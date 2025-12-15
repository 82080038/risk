# DESAIN GUI/UI
## Risk Assessment Objek Wisata - Mobile-First Design

---

## 1. PRINSIP DESAIN

### 1.1 Mobile-First Approach
- Desain dimulai dari layar terkecil (320px - mobile portrait)
- Kemudian ditingkatkan untuk tablet dan desktop
- Semua elemen harus dapat diakses dengan mudah di mobile

### 1.2 Touch-Friendly Design
- **Minimum touch target:** 44x44px (Apple) / 48x48px (Material Design)
- **Spacing:** Minimal 8px antara elemen yang dapat disentuh
- **Button size:** Minimal 48px tinggi untuk mobile
- **Input fields:** Minimal 44px tinggi

### 1.3 Visual Hierarchy
- **Primary actions:** Tombol besar, warna mencolok
- **Secondary actions:** Tombol lebih kecil, warna lebih netral
- **Information:** Text biasa, warna abu-abu
- **Important:** Badge, alert, highlight

---

## 2. KOMPONEN UI

### 2.1 Header/Navbar

#### Mobile (< 768px)
```
┌─────────────────────────────────────┐
│ [☰] Risk Assessment    [👤] [⚙️]  │
└─────────────────────────────────────┘
```
- Hamburger menu di kiri
- Logo/Title di tengah
- Icon user dan settings di kanan
- Sticky top (tetap di atas saat scroll)

#### Desktop (≥ 768px)
```
┌─────────────────────────────────────────────────────┐
│ [Logo] Risk Assessment  [Menu] [Menu] [Menu] [👤]   │
└─────────────────────────────────────────────────────┘
```
- Logo di kiri
- Menu horizontal di tengah
- User menu di kanan

### 2.2 Form Penilaian - Mobile View

#### Layout Step-by-Step (Satu Aspek Per View)
```
┌─────────────────────────────────────┐
│ ← Kembali    Aspek 1/6    Next →   │
├─────────────────────────────────────┤
│ ████████░░░░░░░░░░ 33%              │
├─────────────────────────────────────┤
│ INFRASTRUKTUR                       │
│ Bobot: 0.20                         │
├─────────────────────────────────────┤
│                                     │
│ [Accordion: Elemen A]               │
│   └─ Kriteria 1                     │
│   └─ Kriteria 2                     │
│                                     │
│ [Accordion: Elemen B]               │
│   └─ Kriteria 1                     │
│                                     │
├─────────────────────────────────────┤
│ [Simpan Draft]  [Lanjutkan]         │
└─────────────────────────────────────┘
```

#### Detail Kriteria (Modal atau Expand)
```
┌─────────────────────────────────────┐
│ Kriteria 1                          │
│ Memiliki parameter pembatas...      │
├─────────────────────────────────────┤
│ Nilai:                              │
│ ( ) 0 - Tidak dapat dipenuhi        │
│ ( ) 1 - Terdapat kekurangan         │
│ (●) 2 - Dapat dipenuhi               │
├─────────────────────────────────────┤
│ Temuan: (hidden jika nilai = 2)     │
│ ┌─────────────────────────────┐    │
│ │ [Textarea]                   │    │
│ │                             │    │
│ └─────────────────────────────┘    │
├─────────────────────────────────────┤
│ Rekomendasi: (hidden jika nilai=2)  │
│ ┌─────────────────────────────┐    │
│ │ [Textarea]                   │    │
│ │                             │    │
│ └─────────────────────────────┘    │
├─────────────────────────────────────┤
│ Referensi:                          │
│ [📎 Upload File] [Preview]          │
├─────────────────────────────────────┤
│ [Simpan] [Batal]                    │
└─────────────────────────────────────┘
```

### 2.3 Form Penilaian - Desktop View

#### Layout dengan Tabs
```
┌─────────────────────────────────────────────────────┐
│ [Tab 1] [Tab 2] [Tab 3] [Tab 4] [Tab 5] [Tab 6]    │
├─────────────────────────────────────────────────────┤
│ Sidebar          │ Main Content Area                │
│ ──────────────   │ ─────────────────────────────   │
│ Aspek 1 ✓        │ INFRASTRUKTUR                    │
│ Aspek 2 ✓        │                                  │
│ Aspek 3 →        │ Elemen A: KELAIKAN GEDUNG        │
│ Aspek 4          │ ─────────────────────────────    │
│ Aspek 5          │ Kriteria 1                      │
│ Aspek 6          │ [Radio: 0] [Radio: 1] [Radio: 2] │
│                  │ Temuan: [Textarea]               │
│                  │ Rekomendasi: [Textarea]          │
│                  │ Referensi: [Upload]               │
│                  │                                  │
│                  │ Kriteria 2                      │
│                  │ ...                              │
│                  │                                  │
│                  │ [Simpan Draft] [Next →]          │
└─────────────────────────────────────────────────────┘
```

### 2.4 Dashboard - Mobile

```
┌─────────────────────────────────────┐
│ Dashboard                            │
├─────────────────────────────────────┤
│ [Card: Statistik]                    │
│ Total Penilaian: 25                  │
│ Selesai: 20                          │
│ Draft: 5                             │
├─────────────────────────────────────┤
│ [Card: Aksi Cepat]                   │
│ [➕ Penilaian Baru]                  │
│ [📋 Daftar Objek Wisata]             │
│ [📊 Laporan]                         │
├─────────────────────────────────────┤
│ [Card: Penilaian Terbaru]            │
│ ┌─────────────────────────────┐    │
│ │ Objek A - 85% (Perak)        │    │
│ │ 15 Jan 2025                  │    │
│ └─────────────────────────────┘    │
│ ┌─────────────────────────────┐    │
│ │ Objek B - 72% (Perunggu)    │    │
│ │ 14 Jan 2025                  │    │
│ └─────────────────────────────┘    │
└─────────────────────────────────────┘
```

### 2.5 Daftar Objek Wisata - Mobile

```
┌─────────────────────────────────────┐
│ [🔍 Search] [➕ Tambah]              │
├─────────────────────────────────────┤
│ ┌─────────────────────────────┐    │
│ │ Objek Wisata A              │    │
│ │ Jl. Contoh No. 123          │    │
│ │ [📊 3 Penilaian] [✏️ Edit]  │    │
│ └─────────────────────────────┘    │
│ ┌─────────────────────────────┐    │
│ │ Objek Wisata B              │    │
│ │ Jl. Contoh No. 456          │    │
│ │ [📊 1 Penilaian] [✏️ Edit]  │    │
│ └─────────────────────────────┘    │
└─────────────────────────────────────┘
```

### 2.6 Laporan Hasil Penilaian

#### Mobile
```
┌─────────────────────────────────────┐
│ Laporan Penilaian                   │
│ Objek Wisata: ABC                   │
│ Tanggal: 15 Jan 2025               │
├─────────────────────────────────────┤
│ [Card: Skor Final]                  │
│ 85.5%                               │
│ Kategori: Baik (Perak)              │
├─────────────────────────────────────┤
│ [Card: Per Aspek]                   │
│ ████████░░ Infrastruktur: 80%       │
│ ██████████ Keamanan: 90%            │
│ ████████░░ Keselamatan: 75%        │
│ ...                                 │
├─────────────────────────────────────┤
│ [📄 PDF] [📊 Excel] [📧 Share]       │
└─────────────────────────────────────┘
```

---

## 3. KOMPONEN FORM

### 3.1 Radio Button untuk Nilai (0, 1, 2)

#### Mobile - Horizontal Layout
```
┌─────────────────────────────────────┐
│ Nilai:                              │
│ ┌────┐ ┌────┐ ┌────┐                │
│ │ 0  │ │ 1  │ │ 2  │                │
│ │ ❌ │ │ ⚠️ │ │ ✅ │                │
│ └────┘ └────┘ └────┘                │
│ Tidak  Kurang  Baik                 │
└─────────────────────────────────────┘
```

#### Desktop - Horizontal dengan Label
```
┌─────────────────────────────────────┐
│ Nilai:                              │
│ ( ) 0 - Tidak dapat dipenuhi         │
│ ( ) 1 - Terdapat kekurangan         │
│ (●) 2 - Dapat dipenuhi               │
└─────────────────────────────────────┘
```

### 3.2 Textarea untuk Temuan dan Rekomendasi

```
┌─────────────────────────────────────┐
│ Temuan *                            │
│ ┌─────────────────────────────┐    │
│ │                             │    │
│ │ [Auto-expand textarea]      │    │
│ │                             │    │
│ └─────────────────────────────┘    │
│ 0/500 karakter                      │
└─────────────────────────────────────┘
```

### 3.3 File Upload

```
┌─────────────────────────────────────┐
│ Referensi Dokumen                   │
│ ┌─────────────────────────────┐    │
│ │ [📎 Pilih File]              │    │
│ └─────────────────────────────┘    │
│ Format: JPG, PNG, PDF, DOC         │
│ Maks: 5MB                           │
│                                     │
│ [Preview jika sudah upload]          │
│ ┌─────────────────────────────┐    │
│ │ [📄 dokumen.pdf] [🗑️ Hapus] │    │
│ └─────────────────────────────┘    │
└─────────────────────────────────────┘
```

---

## 4. FEEDBACK & NOTIFICATION

### 4.1 Toast Notification (Mobile)
```
┌─────────────────────────────────────┐
│                                       │
│  ┌─────────────────────────────┐    │
│  │ ✅ Data berhasil disimpan    │    │
│  └─────────────────────────────┘    │
│                                       │
└─────────────────────────────────────┘
```

### 4.2 Alert Messages
```
┌─────────────────────────────────────┐
│ ⚠️ Peringatan                        │
│ Temuan wajib diisi untuk nilai 0/1  │
│ [Tutup]                              │
└─────────────────────────────────────┘
```

### 4.3 Loading Indicator
```
┌─────────────────────────────────────┐
│                                       │
│      [Spinner]                       │
│    Memproses data...                 │
│                                       │
└─────────────────────────────────────┘
```

---

## 5. COLOR SCHEME

### 5.1 Primary Colors
- **Primary Blue:** `#0d6efd` (Bootstrap primary)
- **Success Green:** `#198754` (Nilai 2 - Baik)
- **Warning Yellow:** `#ffc107` (Nilai 1 - Kurang)
- **Danger Red:** `#dc3545` (Nilai 0 - Tidak)
- **Info Cyan:** `#0dcaf0`

### 5.2 Neutral Colors
- **Background:** `#ffffff` (White)
- **Background Light:** `#f8f9fa` (Light gray)
- **Text Primary:** `#212529` (Dark)
- **Text Secondary:** `#6c757d` (Gray)
- **Border:** `#dee2e6` (Light gray)

### 5.3 Status Colors
- **Draft:** `#6c757d` (Gray)
- **Selesai:** `#198754` (Green)
- **Kategori Emas:** `#ffd700` (Gold)
- **Kategori Perak:** `#c0c0c0` (Silver)
- **Kategori Perunggu:** `#cd7f32` (Bronze)

---

## 6. TYPOGRAPHY

### 6.1 Font Stack
```css
font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", 
             Roboto, "Helvetica Neue", Arial, sans-serif;
```

### 6.2 Font Sizes (Mobile)
- **H1:** 24px (Page title)
- **H2:** 20px (Section title)
- **H3:** 18px (Subsection)
- **Body:** 16px (Default)
- **Small:** 14px (Helper text)
- **Caption:** 12px (Labels)

### 6.3 Font Sizes (Desktop)
- **H1:** 32px
- **H2:** 24px
- **H3:** 20px
- **Body:** 16px
- **Small:** 14px

### 6.4 Line Height
- **Headings:** 1.2
- **Body:** 1.5
- **Dense text:** 1.4

---

## 7. SPACING SYSTEM

### 7.1 Spacing Scale (8px base)
- **xs:** 4px
- **sm:** 8px
- **md:** 16px
- **lg:** 24px
- **xl:** 32px
- **xxl:** 48px

### 7.2 Padding
- **Card padding:** 16px (mobile), 24px (desktop)
- **Button padding:** 12px 24px
- **Input padding:** 12px 16px
- **Section spacing:** 24px (mobile), 32px (desktop)

---

## 8. RESPONSIVE BREAKPOINTS

### 8.1 Bootstrap 5 Breakpoints
```css
/* Extra small devices (portrait phones) */
@media (max-width: 575.98px) { }

/* Small devices (landscape phones) */
@media (min-width: 576px) and (max-width: 767.98px) { }

/* Medium devices (tablets) */
@media (min-width: 768px) and (max-width: 991.98px) { }

/* Large devices (desktops) */
@media (min-width: 992px) and (max-width: 1199.98px) { }

/* Extra large devices (large desktops) */
@media (min-width: 1200px) { }
```

### 8.2 Layout Changes per Breakpoint

#### Mobile (< 768px)
- Single column layout
- Stacked cards
- Hamburger menu
- Bottom navigation (optional)
- Full-width buttons
- Accordion for sections

#### Tablet (768px - 991px)
- 2-column grid where applicable
- Sidebar navigation (collapsible)
- Tabs for sections
- Larger touch targets

#### Desktop (≥ 992px)
- Multi-column layout
- Sidebar always visible
- Tabs horizontal
- Hover states
- More whitespace

---

## 9. INTERACTIVE STATES

### 9.1 Button States
- **Default:** Solid color background
- **Hover:** Darker shade (desktop)
- **Active:** Pressed effect
- **Disabled:** Grayed out, no interaction
- **Loading:** Spinner + disabled state

### 9.2 Input States
- **Default:** Border, white background
- **Focus:** Blue border, shadow
- **Error:** Red border, error message
- **Success:** Green border
- **Disabled:** Gray background, no interaction

### 9.3 Link States
- **Default:** Primary color
- **Hover:** Underline (desktop)
- **Active:** Darker color
- **Visited:** Different shade

---

## 10. ACCESSIBILITY

### 10.1 Keyboard Navigation
- Tab order logical
- Focus indicators visible
- Skip links for main content
- ARIA labels where needed

### 10.2 Screen Reader Support
- Semantic HTML
- ARIA attributes
- Alt text for images
- Form labels associated

### 10.3 Color Contrast
- Text: WCAG AA minimum (4.5:1)
- Large text: WCAG AA (3:1)
- Interactive elements: High contrast

---

## 11. PERFORMANCE CONSIDERATIONS

### 11.1 Image Optimization
- Lazy loading for images
- WebP format where supported
- Responsive images (srcset)

### 11.2 Code Optimization
- Minified CSS/JS
- Critical CSS inlined
- Defer non-critical JS

### 11.3 Loading Strategy
- Skeleton screens
- Progressive enhancement
- Graceful degradation

---

## 12. IMPLEMENTATION NOTES

### 12.1 Bootstrap 5 Components Used
- Grid system
- Cards
- Forms (input, textarea, select)
- Buttons
- Modals
- Accordion/Collapse
- Tabs
- Alerts
- Badges
- Progress bars
- Navbar
- Dropdowns

### 12.2 Custom Components Needed
- Radio button group (0,1,2) dengan icon
- File upload dengan preview
- Progress stepper
- Score display card
- Category badge

### 12.3 jQuery Plugins (Optional)
- Form validation
- File upload (jQuery File Upload)
- Date picker
- Toast notifications

---

**Dokumen ini akan terus diperbarui seiring dengan implementasi desain.**

