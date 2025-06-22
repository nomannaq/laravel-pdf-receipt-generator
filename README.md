# Receipt Generator PDF

A comprehensive PDF generator application built with Laravel and Livewire that supports both structured receipt generation and custom HTML to PDF conversion.

## Features

### 📄 Receipt Generator
- **Customer Information**: Add customer names to receipts
- **Dynamic Items**: Add, remove, and modify receipt items with real-time calculations
- **Automatic Calculations**: Subtotal, tax (10%), and discount calculations
- **PDF Generation**: Professional PDF receipts with proper styling
- **Database Storage**: All receipts and items are stored in the database

### 🎨 HTML to PDF Converter
- **Custom HTML Input**: Enter any HTML content and convert it to PDF
- **Pre-styled Templates**: Choose from predefined templates (Invoice, Report, Certificate)
- **CSS Support**: Built-in CSS classes for styling (text-center, font-bold, bg-colors, etc.)
- **Professional Styling**: Consistent PDF styling that matches the receipt format

## Available CSS Classes for HTML Content

When using the HTML to PDF feature, you can use these CSS classes:

- **Text Alignment**: `text-center`, `text-right`, `text-left`
- **Font Weight**: `font-bold`
- **Spacing**: `mt-4`, `mb-4`, `p-4`
- **Borders**: `border`, `rounded`
- **Backgrounds**: `bg-gray-100`, `bg-blue-100`, `bg-green-100`, `bg-yellow-100`, `bg-red-100`
- **Text Colors**: `text-blue-800`, `text-green-800`, `text-yellow-800`, `text-red-800`

## Templates Available

1. **Invoice Template**: Professional invoice layout with billing information and itemized costs
2. **Report Template**: Monthly report format with metrics and summary sections
3. **Certificate Template**: Certificate of completion with signature lines

## Installation & Setup

1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Run migrations: `php artisan migrate`
4. Build assets: `npm run build`
5. Link storage: `php artisan storage:link`
6. Start server: `php artisan serve`

## Usage

1. Visit the application homepage
2. Choose between "Receipt Generator" or "HTML to PDF" tabs
3. For receipts: Fill in customer info and add items
4. For HTML: Enter custom HTML or use a template
5. Generate and download your PDF

## Technical Stack

- **Backend**: Laravel 12.x
- **Frontend**: Livewire, Tailwind CSS, Vite
- **PDF Generation**: DomPDF
- **Database**: SQLite (default)

## File Storage

Generated PDFs are stored in `storage/app/public/` and accessible via the `/storage/` URL. 