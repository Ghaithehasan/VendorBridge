# ProcureFlow Manufacturing Procurement MVP

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1)
![Blade](https://img.shields.io/badge/UI-Blade%20%2B%20Tailwind-38B2AC)
![PDF](https://img.shields.io/badge/PDF-DomPDF-6B7280)

## Project Overview
ProcureFlow is a manufacturing procurement MVP built to replace fragmented paper, spreadsheet, and email-based purchasing coordination with a controlled digital workflow.

The system is designed for factory planning teams, procurement managers, and purchasing officers who need visibility, accountability, and a reliable audit trail from internal material demand through vendor-facing RFQ issuance.

This MVP focuses on Phase 1 of procurement digitalization:

- Capture Purchase Requests (PRs) in a structured system
- Route PRs through approval with clear role ownership
- Generate Request for Quotation (RFQ) records from approved PR lines
- Issue RFQs to selected vendors with downloadable PDF output

### Project documentation (SRS & database)
Extended specifications and data design are shipped **inside this repository** under the folder **`Documantation/`**:

| File | Contents |
|------|----------|
| [`Documantation/srs.md`](Documantation/srs.md) | Software Requirements Specification (SRS) |
| [`Documantation/DATABASE.md`](Documantation/DATABASE.md) | Database documentation (schema-oriented reference) |

> **Note:** The folder is named `Documantation/` in this repository (spelling as committed). It contains the SRS and database docs listed above.

## Tech Stack
- Backend: Laravel 12
- Language: PHP 8.2
- Frontend: Blade templates, Tailwind CSS, Alpine.js
- Database: MySQL
- PDF generation: `barryvdh/laravel-dompdf`
- Authentication: Laravel Breeze
- Build tooling: Vite

## System Architecture
### Functional Scope
The application is organized around two core business documents:

- `Purchase Request (PR)`: internal request raised by the factory side
- `Request for Quotation (RFQ)`: vendor-facing procurement document created from approved PR lines

Master data supports both workflows through products, raw materials, units of measure, vendors, currencies, departments, and vendor-material sourcing references.

### Application Layers
- `Controllers`: request handling, response orchestration, redirects, and view rendering
- `Form Requests`: authorization and validation
- `Services`: business rules, state transitions, numbering, and transaction handling
- `Models`: Eloquent relationships and data access
- `Blade Views`: workflow UI, document presentation, and PDF template rendering

### Role Model
| Role | Primary responsibility |
|------|------------------------|
| `requester` | Create, edit, and submit own PRs |
| `procurement_manager` | Review submitted PRs and approve or cancel them |
| `purchasing_officer` | Create draft RFQs from approved PR lines and issue RFQs |
| `admin` | Full access across PR and RFQ workflows |

### PR Lifecycle
`draft -> submitted -> approved | cancelled`

Business rules implemented in the current codebase:

- PR numbers are generated in service layer using `PR-YYYYMMDD-XXXX`
- Requesters can only see and manage their own PRs
- Only draft PRs can be edited
- Only submitted PRs can be approved or cancelled
- Approval and cancellation are written to audit fields (`approved_by`, `approved_at`, `cancelled_by`, `cancelled_at`, `cancellation_reason`)

### RFQ Lifecycle
`draft -> issued -> closed / awarded / cancelled`

Business rules implemented in the current codebase:

- RFQ numbers are generated in service layer using `RFQ-YYYYMMDD-XXXX`
- Each RFQ is created from exactly one approved PR line
- One PR line can only have one RFQ
- Snapshot fields are copied from the PR line into the RFQ at creation time
- Draft RFQs can be edited; issued RFQs become operationally fixed
- RFQ currency is defined at the RFQ header level using `currency_id`
- RFQ PDFs are generated from Blade via DomPDF

### Project Structure
```text
app/
  Http/
    Controllers/
    Requests/
    Middleware/
  Models/
  Services/
database/
  migrations/
  seeders/
resources/
  views/
routes/
  web.php
  auth.php
Documantation/
  srs.md
  DATABASE.md
```

## Database Schema
### Core Entities
| Entity | Purpose |
|--------|---------|
| `departments` | Organisational ownership of requesters |
| `users` | Role-based authenticated users |
| `products` | Finished goods / manufactured outputs |
| `units` | Units of measure such as kg, liter, piece, meter |
| `raw_materials` | Requestable and sourceable procurement items |
| `bom` | Product-to-material quantity requirements |
| `vendors` | External suppliers |
| `currencies` | Supported commercial currencies |
| `vendor_materials` | Vendor sourcing reference, latest price, MOQ, lead time, preferred supplier flag |
| `pr_header` | Purchase Request document header |
| `pr_lines` | Purchase Request line items |
| `rfq` | Request for Quotation header generated from approved PR lines |
| `rfq_recipients` | Vendors invited under an RFQ |
| `quotation` | Vendor quotation versions linked to RFQ recipients |

### Relationship Summary
- One `department` has many `users` and many `purchase requests`
- One `user` can create many `purchase requests` and issue many `rfqs`
- One `product` has many BOM lines
- One `raw_material` belongs to one base `unit`
- `products` and `raw_materials` are linked through `bom`
- `vendors` and `raw_materials` are linked through `vendor_materials`
- One `purchase request` has many `pr_lines`
- One `pr_line` can produce one `rfq`
- One `rfq` has many `rfq_recipients`
- One `rfq_recipient` can have many `quotation` versions
- One `currency` can be used by many `rfqs` and many `vendor_materials`

### Audit and Control Design
- PR and RFQ documents use soft deletes
- Approval and cancellation actions are recorded explicitly
- RFQ and quotation tables include status fields
- Database constraints enforce uniqueness and key workflow validations

## Installation & Setup
### Prerequisites
- PHP 8.2+
- Composer
- Node.js and npm
- MySQL

### Step-by-Step Setup
1. Clone the repository.
2. Install PHP dependencies:

```bash
composer install
```

3. Create the environment file:

```bash
cp .env.example .env
```

4. Configure database connection values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=procurement_process
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Generate the application key:

```bash
php artisan key:generate
```

6. Run migrations and seed demo data:

```bash
php artisan migrate --seed
```

7. Install frontend dependencies:

```bash
npm install
```

8. Build frontend assets:

```bash
npm run build
```

9. Start the application:

```bash
php artisan serve
```

10. Open the app in your browser, typically at [http://127.0.0.1:8000](http://127.0.0.1:8000).

### Development Mode
For local development with Vite:

```bash
npm run dev
```

## Default Demo Users
All seeded demo users use the password below:

```text
password
```

| Role | Name | Email |
|------|------|-------|
| Requester | Ahmad Al-Mansouri | `ahmad@factory.com` |
| Procurement Manager | Sara Al-Hassan | `sara@factory.com` |
| Purchasing Officer | Ali Nasser | `khalid@factory.com` |
| Admin | Mohamed Ali | `admin@factory.com` |

## Key Features
- Role-based access control across requester, procurement manager, purchasing officer, and admin personas
- Purchase Request creation with multi-line material entries
- Draft, submit, approve, and cancel PR workflow with audit trail
- RFQ creation directly from approved PR lines
- Vendor selection per RFQ with recipient tracking
- RFQ edit and issue workflow controlled by role and document status
- RFQ PDF generation for formal vendor-facing output
- Master data visibility for products, raw materials, and vendors
- Currency-managed RFQ commercial context and vendor-material price references
- Demo seeders populated with manufacturing-oriented master data

## Notes
- The current UI is centered on PR management and RFQ issuance.
- The `quotation` table and model are already present in the schema as a foundation for later phases, but quotation submission and comparison are not yet exposed as a complete end-user workflow.
