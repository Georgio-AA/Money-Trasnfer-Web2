# Store Pages Design System

## Overview
Modern, clean design with consistent branding across all store pages. Uses Bootstrap 5 with custom styling for digital services e-commerce platform.

---

## Color Palette

### Primary Colors
- **Primary Blue**: `#007bff` - Buttons, active states, main CTAs
- **Success Green**: `#11998e` to `#38ef7d` - Store branding, accents
- **Dark Gray**: `#212529` - Text, headings
- **Light Gray**: `#f8f9fa` - Backgrounds, cards

### Status Colors
- **Success**: `#28a745` - Confirmed purchases, active products
- **Warning**: `#ffc107` - Pending orders
- **Danger**: `#dc3545` - Unavailable, errors
- **Info**: `#17a2b8` - Information alerts

---

## Typography

### Font Stack
- **Primary Font**: System fonts (Bootstrap default)
- **Sizes**:
  - Page Titles (h1): 2.5rem, bold
  - Section Headers (h2): 2rem, bold
  - Card Titles (h5): 1.25rem, semi-bold
  - Body Text: 1rem, regular
  - Small Text: 0.875rem, regular

### Spacing Rules
- **Container Padding**: `mt-5` (top), `mb-4` (bottom sections)
- **Card Padding**: 1.5rem default
- **Element Spacing**: `me-2` (right margin for icons)

---

## Common Components

### Page Header Pattern
```
├─ Icon + Page Title (h1)
├─ Subtitle (text-muted)
└─ Optional: CTA Button (right-aligned)
```

### Alert Box Pattern
```
├─ Icon (optional)
├─ Message/Content
└─ Optional: Action Button
```

### Product Card Pattern
```
┌─────────────────────────────┐
│  Category Badge             │
│  Provider Logo/Icon         │
│  Product Name (h5)          │
│  Price Display              │
│  [Purchase Button]          │
└─────────────────────────────┘
```

### Form Elements
- **Input Fields**: `.form-control` with Bootstrap styling
- **Labels**: Bold, `mb-2` margin bottom
- **Buttons**: Consistent sizing with `.btn` classes
- **Validation**: Red text for errors, `.alert-danger` for error messages

---

# Page-Specific Designs

## 1. User Store Pages

### 1.1 Store Index (Product Catalog)
**Route**: `/store`
**Purpose**: Browse and purchase digital services

#### Header Section
- **Icon**: 🛍️ Shopping bag
- **Title**: "Digital Services Store"
- **Subtitle**: "Purchase mobile recharges, streaming subscriptions, and more"

#### Balance Display Section
```
┌─────────────────────────────────────────┐
│  ℹ️ Your Current Balance: $X,XXX.XX     │
│                       [Add Funds Button]│
└─────────────────────────────────────────┘
```
- **Background**: Light blue (`alert-info`)
- **Button**: Blue, right-aligned

#### Filter Section
**Button Group**: Category Filter Buttons
- "All Categories" (default selected)
- "Mobile Recharge", "Streaming", "Music & Audio", "TV", "Gaming"
- **Style**: Outline primary buttons, active state highlighted
- **Layout**: Horizontal scrollable on mobile

#### Products Grid
**Layout**: Responsive grid
- Desktop: 4 columns (`col-md-3`)
- Tablet: 3 columns (`col-md-4`)
- Mobile: 1 column

**Card Structure**:
```
┌──────────────────────────────┐
│  Badge: Category             │ (top-left, green)
│  Provider Name               │ (gray text, small)
│  ───────────────────────────│
│  Product Name                │ (h5, bold)
│  Description (2 lines)       │ (truncated, gray)
│  ───────────────────────────│
│  Price: $XX.XX               │ (large, bold blue)
│  [Purchase Now] Button       │ (blue, full-width)
│  [View Details] Link         │ (optional)
└──────────────────────────────┘
```

**Card Styling**:
- Border: Light gray, 1px
- Box Shadow: Subtle (0 2px 4px rgba)
- Hover Effect: Lift effect (shadow increase, slight scale)
- Corner Radius: 4px

#### No Products Message
```
┌────────────────────────────────┐
│  🔍 No products found         │
│  Try adjusting your filters   │
└────────────────────────────────┘
```
- **Styling**: Center-aligned, gray text, light background card

---

### 1.2 Purchase Confirmation Page
**Route**: `/store/confirmation/{order}`
**Purpose**: Display purchased code after successful purchase

#### Header Section
- **Icon**: ✅ Check circle (green)
- **Title**: "Purchase Successful"
- **Subtitle**: "Your digital service code is ready to use"

#### Success Banner
```
┌─────────────────────────────────────────┐
│  ✅ Thank you for your purchase!        │
│                                         │
│  Your code has been generated and       │
│  is ready to use immediately.           │
└─────────────────────────────────────────┘
```
- **Background**: Light green (`#d4edda`)
- **Border**: Green (`#c3e6cb`)
- **Text**: Dark green (`#155724`)

#### Order Details Section
**Card Layout**:
```
┌────────────────────────────────────┐
│  📦 Order Details                  │
├────────────────────────────────────┤
│  Order ID: #ABC123                 │
│  Product: Netflix Premium          │
│  Provider: Netflix                 │
│  Price: $15.99                     │
│  Purchase Date: 2025-12-04         │
│  Status: ✅ Active                 │
└────────────────────────────────────┘
```
- **Layout**: 2-column grid on desktop, 1-column on mobile
- **Text**: Muted labels with bold values

#### Code Display Section
**Primary Focus**:
```
┌──────────────────────────────────────┐
│  Your Redemption Code                │
├──────────────────────────────────────┤
│                                      │
│         ABC123-XYZ789-AB             │
│  (Formatted in readable chunks)      │
│                                      │
│  [Copy Code Button] [Download PDF]   │
│                                      │
└──────────────────────────────────────┘
```
- **Code Font**: Monospace, larger size (1.5rem)
- **Code Styling**: 
  - Background: Light gray
  - Padding: 1.5rem
  - Letter spacing: 0.2em
  - Center-aligned
  - Selectable text
- **Copy Button**: Blue, with confirmation feedback
- **Download**: Optional PDF button for record-keeping

#### Instructions Section
```
┌────────────────────────────────────┐
│  📝 How to Use Your Code           │
├────────────────────────────────────┤
│  1. Visit the provider's website   │
│  2. Select your redemption option  │
│  3. Enter the code when prompted   │
│  4. Your service will activate     │
└────────────────────────────────────┘
```
- **Layout**: Numbered list with icons
- **Styling**: Light background card

#### Action Buttons
```
[Continue Shopping] [View My Purchases] [Download Invoice]
```
- **Layout**: Centered at bottom
- **Spacing**: 1rem between buttons
- **Primary Button**: Blue, left-aligned "Continue Shopping"
- **Secondary Buttons**: Outline style

#### Footer Notes
- **Text**: "Please save your code in a safe location. Codes cannot be regenerated."
- **Styling**: Small, italic, warning color

---

### 1.3 My Purchases Page
**Route**: `/store/my-purchases`
**Purpose**: View purchase history and manage redemption codes

#### Header Section
- **Icon**: 📋 History
- **Title**: "My Purchases"
- **Subtitle**: "View your digital service purchase history and redemption codes"

#### Statistics Cards (If purchases exist)
```
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│  Total Spent│  │ Purchased   │  │ Redeemed    │
│  $XXX.XX    │  │ Services    │  │ Codes       │
│  (blue)     │  │ X (green)   │  │ X (success) │
└─────────────┘  └─────────────┘  └─────────────┘
```
- **Card Style**: Light background, colored top border
- **Icon**: Font Awesome icon (shopping-bag, star, check)

#### Filters (Optional)
- **Date Range**: From/To date pickers
- **Status**: All, Active, Used, Expired
- **Provider**: Dropdown filter by provider

#### Purchases Table
**Desktop View** (Table):
```
┌─────────┬──────────────┬──────────┬──────────┬────────────┬─────────┐
│ Service │ Provider     │ Price    │ Status   │ Date       │ Action  │
├─────────┼──────────────┼──────────┼──────────┼────────────┼─────────┤
│ Netflix │ Netflix      │ $15.99   │ Active   │ 2025-12-04 │ View    │
│ Spotify │ Anghami      │ $9.99    │ Active   │ 2025-12-03 │ View    │
│ MTC 100 │ MTC          │ $25.00   │ Used     │ 2025-12-02 │ View    │
└─────────┴──────────────┴──────────┴──────────┴────────────┴─────────┘
```

**Mobile View** (Card Layout):
```
┌──────────────────────────────┐
│  Netflix Premium             │
│  Provider: Netflix           │
│  Price: $15.99              │
│  Status: ✅ Active          │
│  Date: 2025-12-04          │
│  [View Code] [Re-download]  │
└──────────────────────────────┘
```

#### Table Styling
- **Header**: Dark background, white text
- **Row Alternation**: Zebra striping (light gray every other row)
- **Status Badge**:
  - Active: Green
  - Used: Gray
  - Expired: Red
- **Hover Effect**: Light background highlight
- **Sorting**: Click headers to sort

#### Empty State
```
┌─────────────────────────────────┐
│  🛒 No Purchases Yet           │
│                                 │
│  Start shopping to see your     │
│  purchase history here.         │
│                                 │
│  [Browse Store] Button          │
└─────────────────────────────────┘
```
- **Styling**: Center-aligned, large icon, light card background

#### Code Modal (when "View Code" clicked)
```
┌──────────────────────────────────┐
│ Service: Netflix Premium          │ X
├──────────────────────────────────┤
│                                  │
│  Your Redemption Code:           │
│  ABC123-XYZ789-AB               │
│                                  │
│  [Copy Code] [Print] [Download]  │
│                                  │
│             [Close]              │
└──────────────────────────────────┘
```

---

## 2. Admin Store Pages

### 2.1 Products Management Index
**Route**: `/admin/store/products`
**Purpose**: Manage digital service products

#### Header Section
- **Icon**: 📦 Boxes
- **Title**: "Store Products"
- **Subtitle**: "Manage digital services inventory"
- **CTA Button**: "Add Product" (top-right, blue)

#### Bulk Actions Bar (Optional)
```
[Select All] [Delete Selected] [Activate Selected] [Deactivate Selected]
```
- **Layout**: Right-aligned in light background bar
- **Visibility**: Appears only when items selected

#### Products Table
```
┌────┬──────────┬──────────┬───────────┬──────────┬──────────┬────────────┐
│ ☑️ │ Product  │ Provider │ Price     │ Status   │ Orders   │ Actions    │
├────┼──────────┼──────────┼───────────┼──────────┼──────────┼────────────┤
│ ☑️ │ Netflix  │ Netflix  │ $15.99    │ ✅ Active│ 12       │ ✎ ⊙ ⚙️ ✖️ │
│ ☑️ │ Spotify  │ Anghami  │ $9.99     │ ✅ Active│ 8        │ ✎ ⊙ ⚙️ ✖️ │
│ ☑️ │ MTC 100  │ MTC      │ $25.00    │ ⏸ Inactive│ 0      │ ✎ ⊙ ⚙️ ✖️ │
└────┴──────────┴──────────┴───────────┴──────────┴──────────┴────────────┘
```

#### Column Definitions
- **Checkbox**: Select for bulk actions
- **Product Name**: Linked to edit page
- **Provider**: Text
- **Price**: Bold, blue text
- **Status Badge**:
  - Active: Green, checkmark
  - Inactive: Gray, paused icon
  - Clickable to toggle
- **Orders**: Number of orders, linked to orders view
- **Actions**:
  - ✎ Edit (pencil icon) → Edit page
  - ⊙ Toggle (circle icon) → Activate/Deactivate
  - ⚙️ View Orders (gear icon) → Orders list
  - ✖️ Delete (trash icon) → Confirmation dialog

#### Table Styling
- **Header**: Primary blue background, white text
- **Row Hover**: Light gray background
- **Pagination**: Bottom of table with next/previous buttons
- **Search Bar**: Above table "Search products..."

#### Empty State
```
┌─────────────────────────────────┐
│  📭 No Products Yet             │
│                                 │
│  Create your first product      │
│  to start managing the store.   │
│                                 │
│  [Add Product] Button           │
└─────────────────────────────────┘
```

---

### 2.2 Create/Edit Product Form
**Routes**: 
- Create: `/admin/store/products/create`
- Edit: `/admin/store/products/{id}/edit`

**Purpose**: Add or update digital service products

#### Header Section
- **Create Page**:
  - **Icon**: ➕ Plus circle
  - **Title**: "Create New Product"
- **Edit Page**:
  - **Icon**: ✏️ Edit
  - **Title**: "Edit Product"

#### Error Display
```
┌──────────────────────────────────┐
│  ⚠️ Validation Error             │
├──────────────────────────────────┤
│  • Product name is required      │
│  • Price must be greater than 0  │
│  • Category is required          │
└──────────────────────────────────┘
```
- **Background**: Light red (`#f8d7da`)
- **Border**: Red
- **Icon**: Warning symbol
- **Text**: Dark red

#### Form Card
```
┌────────────────────────────────────────┐
│  Product Information                   │
├────────────────────────────────────────┤
│                                        │
│  Product Name *                        │
│  [Text Input]                          │
│                                        │
│  Provider *                            │
│  [Dropdown with predefined options]    │
│                                        │
│  Category *                            │
│  [Dropdown: Mobile, Streaming, etc]    │
│                                        │
│  Price (USD) *                         │
│  [Number Input: Min 0.01]              │
│                                        │
│  Description                           │
│  [Textarea - 200 characters]           │
│                                        │
│  Status                                │
│  ☑️ Active  ☐ Inactive                 │
│                                        │
│        [Save] [Cancel]                 │
│                                        │
└────────────────────────────────────────┘
```

#### Form Field Styling
- **Labels**: Bold, required fields marked with *
- **Input Width**: Full width (100%)
- **Input Height**: Standard Bootstrap sizing
- **Placeholder Text**: Light gray, descriptive
- **Focus State**: Blue border (primary color)
- **Error State**: Red border with error message below
- **Help Text**: Small gray text below field (optional)

#### Form Sections
1. **Product Information**
   - Product Name (text input, required)
   - Provider (dropdown, required)
   - Category (dropdown, required)

2. **Pricing & Details**
   - Price (number input, required, min 0.01)
   - Description (textarea, optional)

3. **Status**
   - Active/Inactive toggle (radio or checkbox)

#### Buttons
- **Save Button**: 
  - Primary blue color
  - Full width or fixed width (200px)
  - Padding: 10px 20px
  - Hover: Darker blue
- **Cancel Button**:
  - Outline blue
  - Same size as save button
  - Positioned next to save

#### Success Message
```
┌──────────────────────────────────┐
│  ✅ Product saved successfully!  │
│     Redirecting...               │
└──────────────────────────────────┘
```
- **Auto-dismiss**: 3 seconds
- **Background**: Light green
- **Text**: Dark green

---

### 2.3 View Orders by Product
**Route**: `/admin/store/orders?product={id}`
**Purpose**: View all orders for a specific product

#### Header Section
- **Icon**: 📋 Receipt
- **Title**: "Orders: [Product Name]"
- **Subtitle**: "Customer purchases for this product"
- **Breadcrumb**: Back to Products link

#### Filters
```
[Status: All ▼] [Date Range ▼] [Sort By ▼]
```

#### Orders Table
```
┌────┬──────────┬──────────┬─────────┬───────────┬────────────┬─────────┐
│ ID │ Customer │ Email    │ Amount  │ Status    │ Date       │ Code    │
├────┼──────────┼──────────┼─────────┼───────────┼────────────┼─────────┤
│ 1  │ John Doe │ john@... │ $15.99  │ ✅ Active │ 2025-12-04 │ View    │
│ 2  │ Jane Sm. │ jane@... │ $15.99  │ ✅ Active │ 2025-12-03 │ View    │
└────┴──────────┴──────────┴─────────┴───────────┴────────────┴─────────┘
```

#### Columns
- **ID**: Order number
- **Customer**: User name (linked to user profile)
- **Email**: Customer email
- **Amount**: Price paid (green text)
- **Status**: Badge (Active/Used/Expired)
- **Date**: Purchase date (formatted)
- **Code**: View button → Shows code in modal

#### Code View Modal
```
┌──────────────────────────────────┐
│ Order #123 - Code               │ X
├──────────────────────────────────┤
│                                  │
│ Customer: John Doe              │
│ Email: john@example.com         │
│                                  │
│ Redemption Code:                │
│ ABC123-XYZ789-AB               │
│                                  │
│ [Copy] [Email Customer]         │
│                                  │
│        [Close]                   │
└──────────────────────────────────┘
```

---

### 2.4 All Orders View (Global)
**Route**: `/admin/store/orders`
**Purpose**: View all store orders across all products

#### Header Section
- **Icon**: 📊 Receipt
- **Title**: "Store Orders"
- **Subtitle**: "Monitor all customer purchases and digital service deliveries"

#### Statistics Cards
```
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ Total Orders │  │ Revenue      │  │ This Month   │  │ Pending      │
│ 156          │  │ $5,234.56    │  │ 42 orders    │  │ 3 orders     │
│ (blue)       │  │ (green)      │  │ (primary)    │  │ (warning)    │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
```

#### Filters
```
[Date Range ▼] [Status ▼] [Provider ▼] [Search Customer ▼]
```

#### Orders Table
```
┌────┬──────────┬──────────┬──────────┬─────────┬───────────┬────────────┐
│ ID │ Customer │ Product  │ Amount   │ Status  │ Date      │ Actions    │
├────┼──────────┼──────────┼─────────┼───────────┼────────────┼────────────┤
│ 123│ John Doe │ Netflix  │ $15.99  │ ✅ Active│ 2025-12-04│ View Code  │
│ 122│ Jane Sm. │ Spotify  │ $9.99   │ ✅ Active│ 2025-12-03│ View Code  │
│ 121│ Bob J.   │ MTC 100  │ $25.00  │ ⏳ Pending│ 2025-12-02│ View Code  │
└────┴──────────┴──────────┴──────────┴───────────┴────────────┴────────────┘
```

#### Export Options
```
[Export CSV] [Export PDF] [Print]
```
- **Right-aligned buttons**
- **Blue outline style**

---

## 3. Design Guidelines

### Responsive Breakpoints
- **Mobile**: < 576px (1 column)
- **Tablet**: 576px - 992px (2 columns)
- **Desktop**: > 992px (3-4 columns)

### Shadows & Depth
- **Cards**: `box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075)`
- **Hover**: `box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15)`
- **No shadow**: `.border-0` on flat cards

### Animations
- **Transitions**: 200ms ease-in-out
- **Hover Effects**: Lift (shadow increase), color change
- **Loading**: Spinner with text "Loading..."
- **Feedback**: Toast notifications (3-second auto-dismiss)

### Accessibility
- **Color Contrast**: WCAG AA compliant
- **Icons + Text**: Always pair icons with text labels
- **Focus States**: Visible focus ring on all interactive elements
- **Keyboard Navigation**: Tab through all interactive elements
- **Alt Text**: All images have descriptive alt text

### Icons Used
- **Shopping**: 🛍️ (shopping-bag, shopping-cart)
- **Actions**: ✏️ (edit), ✖️ (delete), ⊙ (toggle), ⚙️ (settings)
- **Status**: ✅ (active), ⏸️ (inactive), ⏳ (pending)
- **Navigation**: 📖 (history), 📦 (box), 📋 (list), 📊 (chart)
- **UI**: ℹ️ (info), ⚠️ (warning), 🔍 (search), ☑️ (checkbox)

### Loading States
```
[Loading...] ⟳

or 

Spinner icon with text "Please wait..."
```

### Error States
- **404 Page Not Found**: Large icon, message, back link
- **500 Server Error**: Apologetic message, support contact
- **Network Error**: Retry button, offline indicator

---

## 4. Component Library

### Buttons
**Primary**: `btn btn-primary` - Blue, full-featured CTAs
**Secondary**: `btn btn-outline-primary` - Outlined, less important actions
**Danger**: `btn btn-danger` - Delete, remove actions
**Success**: `btn btn-success` - Confirm, approve actions
**Small**: `btn btn-sm` - Compact spacing
**Large**: `btn btn-lg` - Prominent spacing

### Badges
**Primary**: `badge bg-primary` - Information
**Success**: `badge bg-success` - Completed, active
**Warning**: `badge bg-warning` - Pending, caution
**Danger**: `badge bg-danger` - Critical, errors
**Info**: `badge bg-info` - Notifications

### Alerts
**Success**: `alert alert-success` - Positive confirmations
**Info**: `alert alert-info` - General information
**Warning**: `alert alert-warning` - Cautions
**Danger**: `alert alert-danger` - Errors, critical

### Forms
**Input Groups**: `.input-group` for labels + input
**Validation Feedback**: `.invalid-feedback` for errors
**Help Text**: `.form-text` for supporting text
**Disabled State**: `disabled` attribute, grayed out

---

## 5. Responsive Design Rules

### Desktop (> 992px)
- 4-column product grid
- Table layouts for orders
- Side-by-side sections
- Fixed navigation

### Tablet (576px - 992px)
- 2-3 column product grid
- Stacked table rows (card style alternative)
- 2-column form sections
- Hamburger menu

### Mobile (< 576px)
- 1-column product grid
- Card-based layouts
- Full-width buttons
- Stack everything vertically
- Simplified tables (show key columns only)
- Collapsible sections

---

## 6. Theme Customization

### Quick Brand Change
Update these CSS variables:
```css
--primary-color: #007bff;
--success-color: #11998e;
--accent-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
--text-dark: #212529;
--text-muted: #6c757d;
--bg-light: #f8f9fa;
```

### Font Customization
```css
--font-family-base: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
--font-size-base: 1rem;
--line-height-base: 1.5;
```

---

## Summary

This design system provides:
- ✅ Consistent visual language across all pages
- ✅ Clear hierarchy and information architecture
- ✅ Responsive design for all devices
- ✅ Accessible components and interactions
- ✅ Professional, modern aesthetic
- ✅ Easy-to-customize color and typography
- ✅ Scalable component system
