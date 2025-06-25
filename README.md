# Receipt Generator PDF

A comprehensive PDF generator application built with Laravel and Livewire that supports structured receipt generation, custom HTML to PDF conversion, and a complete template management system for PDF, Email, and SMS templates.

## Features

### 📄 Receipt Generator
- **Customer Information**: Add customer names to receipts
- **Dynamic Items**: Add, remove, and modify receipt items with real-time calculations
- **Automatic Calculations**: Subtotal, tax (10%), and discount calculations
- **PDF Generation**: Professional PDF receipts with proper styling
- **Database Storage**: All receipts and items are stored in the database

### 🎨 HTML to PDF Converter
- **Custom HTML Input**: Enter any HTML content and convert it to PDF
- **Template Integration**: Select from saved templates or quick templates
- **CSS Support**: Built-in CSS classes for styling (text-center, font-bold, bg-colors, etc.)
- **Professional Styling**: Consistent PDF styling that matches the receipt format
- **Save Templates**: Save current HTML content as reusable templates

### 🗂️ Template Manager
- **Multi-Format Support**: Create and manage templates for PDF, Email, and SMS
- **Template Types**:
  - **PDF Templates**: For generating styled PDF documents
  - **Email Templates**: For automated email communications
  - **SMS Templates**: For text message notifications
- **Full CRUD Operations**: Create, read, update, and delete templates
- **Template Preview**: Preview templates with sample data
- **Variable Support**: Use `{{variable}}` syntax for dynamic content
- **Default Templates**: Pre-built professional templates included

## Template Management Features

### 📋 Template Operations
- **Create Templates**: Build custom templates for any format
- **Edit Templates**: Modify existing templates with live preview
- **Delete Templates**: Remove custom templates (default templates protected)
- **Preview Templates**: See how templates look with sample data
- **Template Selection**: Choose templates from organized grid view

### 🔄 Template Variables
Templates support dynamic variables using `{{variable}}` syntax:

**Common Variables:**
- `{{customer_name}}` - Customer name
- `{{company_name}}` - Your company name
- `{{date}}` - Current date
- `{{invoice_number}}` / `{{receipt_number}}` - Document numbers
- `{{total}}`, `{{subtotal}}`, `{{tax}}`, `{{discount}}` - Financial calculations

**Loop Support:**
```html
{{#items}}
<tr>
    <td>{{name}}</td>
    <td>{{quantity}}</td>
    <td>${{price}}</td>
</tr>
{{/items}}
```

## Available CSS Classes for HTML Content

When using the HTML to PDF feature, you can use these CSS classes:

- **Text Alignment**: `text-center`, `text-right`, `text-left`
- **Font Weight**: `font-bold`
- **Spacing**: `mt-4`, `mb-4`, `p-4`
- **Borders**: `border`, `rounded`
- **Backgrounds**: `bg-gray-100`, `bg-blue-100`, `bg-green-100`, `bg-yellow-100`, `bg-red-100`
- **Text Colors**: `text-blue-800`, `text-green-800`, `text-yellow-800`, `text-red-800`

## Default Templates Included

### PDF Templates
1. **Invoice Template**: Professional invoice layout with billing information and itemized costs
2. **Receipt Template**: Simple receipt format for transactions

### Email Templates
1. **Invoice Email**: Professional email for sending invoice notifications
2. **Receipt Email**: Confirmation email for completed transactions

### SMS Templates
1. **Payment Confirmation SMS**: Short confirmation for received payments
2. **Invoice Due SMS**: Payment reminder for overdue invoices
3. **Appointment Reminder SMS**: Appointment notification template

## Installation & Setup

1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Run migrations: `php artisan migrate`
4. Seed templates: `php artisan db:seed --class=TemplateSeeder`
5. Build assets: `npm run build`
6. Link storage: `php artisan storage:link`
7. Start server: `php artisan serve`

## Usage

### Receipt Generation
1. Visit the application homepage
2. Use "Receipt Generator" tab
3. Fill in customer info and add items
4. Generate and download PDF

### HTML to PDF
1. Switch to "HTML to PDF" tab
2. Select from available templates or use quick templates
3. Customize HTML content as needed
4. Generate PDF or save as new template

### Template Management
1. Go to "Template Manager" tab
2. Switch between PDF, Email, and SMS template types
3. Create new templates or edit existing ones
4. Preview templates before using
5. Use templates in HTML to PDF generation

## Technical Stack

- **Backend**: Laravel 12.x
- **Frontend**: Livewire, Tailwind CSS, Vite
- **PDF Generation**: DomPDF
- **Database**: SQLite (default)
- **Template Engine**: Custom variable replacement system

## Database Schema

### Templates Table
- `id` - Primary key
- `name` - Template name
- `type` - Template type (pdf, email, sms)
- `content` - Template content with variables
- `description` - Template description
- `metadata` - Additional template settings (JSON)
- `is_default` - Whether template is a default template
- `created_at` / `updated_at` - Timestamps

## File Storage

Generated PDFs are stored in `storage/app/public/` and accessible via the `/storage/` URL.

## API Potential

The template system is designed to support future API integration for:
- Email sending with template rendering
- SMS sending with template rendering
- Webhook-based document generation
- Template sharing and importing 