# Database Design Documentation

## 1. Overview

This schema supports a manufacturing procurement control workflow for a Phase-1 MVP:

- Capture internal demand as Purchase Requests (PR)
- Route PRs through approval with auditability
- Generate RFQs from approved PR lines
- Track recipient vendors and quotation versions

### Design philosophy
- **Control first:** all key business documents are persisted and status-driven.
- **Traceability by default:** PR/RFQ states, actors, and timestamps are retained.
- **Master-data-led transactions:** materials, vendors, units, and currencies drive document quality.
- **Document integrity:** important business rules are enforced at DB level (unique keys, checks, FKs).

---

## 2. Entity List (with business purpose)

| Table | Business purpose |
|---|---|
| `departments` | Organizational units that own users and PRs. |
| `users` | Authenticated users with role-based responsibilities. |
| `password_reset_tokens` | Laravel password reset token store. |
| `sessions` | Laravel session persistence. |
| `jobs` | Async queue jobs. |
| `currencies` | Commercial currencies used in RFQ and vendor pricing. |
| `products` | Finished goods produced by the factory. |
| `units` | Standard units of measure (UOM). |
| `raw_materials` | Procured materials required for production. |
| `bom` | Bill of materials mapping products to required materials and quantities. |
| `vendors` | Approved supplier master records. |
| `vendor_materials` | Vendor-material sourcing facts (price, currency, lead time, MOQ). |
| `pr_header` | PR document header (internal request). |
| `pr_lines` | Material line items within a PR. |
| `rfq` | RFQ document created from one approved PR line. |
| `rfq_recipients` | Vendor recipients attached to RFQ distribution. |
| `quotation` | Versioned vendor quotation responses per recipient. |

---

## 3. Table Documentation

### `departments`
**Purpose:** Department reference used by users and PR ownership.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `department_id` | `bigIncrements` | No | Primary key. |
| `name` | `string(100)` | No | Department name. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `departments (1) -> users (many)` via `users.department_id -> departments.department_id`
- `departments (1) -> pr_header (many)` via `pr_header.department_id -> departments.department_id`

**Key constraints / rules**
- Unique: `name`

---

### `users`
**Purpose:** User identity, authentication attributes, and role assignment.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `user_id` | `bigIncrements` | No | Primary key. |
| `name` | `string(150)` | No | Full name. |
| `email` | `string` | No | Login email. |
| `email_verified_at` | `timestamp` | Yes | Verification timestamp. |
| `password` | `string` | No | Password hash. |
| `department_id` | `foreignId` | Yes | Optional department FK. |
| `role` | `enum` | No | `requester`, `procurement_manager`, `purchasing_officer`, `admin`. |
| `is_active` | `boolean` | No | Activation flag. |
| `remember_token` | `string` | Yes | Remember-me token. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `users (many) -> departments (1)` via `users.department_id -> departments.department_id`
- `users (1) -> pr_header (many)` via `pr_header.requester_id -> users.user_id`
- `users (1) -> pr_header (many)` via `pr_header.approved_by -> users.user_id`
- `users (1) -> pr_header (many)` via `pr_header.cancelled_by -> users.user_id`
- `users (1) -> rfq (many)` via `rfq.issued_by -> users.user_id`

**Key constraints / rules**
- Unique: `email`
- Index: `role, is_active`
- FK delete policy: `department_id` uses `ON DELETE SET NULL`

---


### `sessions`
**Purpose:** Session store for authenticated web sessions.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | `string` | No | Session primary key. |
| `user_id` | `foreignId` | Yes | Indexed user ID reference (not constrained by FK in migration). |
| `ip_address` | `string(45)` | Yes | Client IP. |
| `user_agent` | `text` | Yes | Browser user agent. |
| `payload` | `longText` | No | Serialized session payload. |
| `last_activity` | `integer` | No | Unix timestamp of last activity. |

**Relationships**
- Logical reference from `sessions.user_id` to `users.user_id` (index only).

**Key constraints / rules**
- Primary key: `id`
- Indexes: `user_id`, `last_activity`

---

### `currencies`
**Purpose:** Currency master used for RFQ commercial terms and vendor price context.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | `bigIncrements` | No | Primary key. |
| `code` | `char(3)` | No | ISO-like currency code (e.g., USD). |
| `name` | `string(100)` | No | Currency display name. |
| `symbol` | `string(10)` | No | Symbol or short label. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `currencies (1) -> rfq (many)` via `rfq.currency_id -> currencies.id`
- `currencies (1) -> vendor_materials (many)` via `vendor_materials.currency_id -> currencies.id`

**Key constraints / rules**
- Unique: `code`

---

### `products`
**Purpose:** Finished goods that define material demand through BOM.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `product_id` | `bigIncrements` | No | Primary key. |
| `code` | `string(30)` | No | Product code. |
| `name` | `string(150)` | No | Product name. |
| `description` | `text` | Yes | Optional description. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `products (1) -> bom (many)` via `bom.product_id -> products.product_id`

**Key constraints / rules**
- Unique: `code`
- Unique: `name`

---

### `units`
**Purpose:** Reusable units of measure across master and transactional tables.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `unit_id` | `bigIncrements` | No | Primary key. |
| `name` | `string(50)` | No | Unit name. |
| `symbol` | `string(20)` | No | Unit symbol. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `units (1) -> raw_materials (many)` via `raw_materials.base_unit_id -> units.unit_id`
- `units (1) -> bom (many)` via `bom.unit_id -> units.unit_id`
- `units (1) -> pr_lines (many)` via `pr_lines.unit_id -> units.unit_id`
- `units (1) -> rfq (many)` via `rfq.unit_id -> units.unit_id`

**Key constraints / rules**
- Unique: `name`
- Unique: `symbol`

---

### `raw_materials`
**Purpose:** Procurement items referenced in BOM, PR lines, RFQ snapshots, and sourcing.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `material_id` | `bigIncrements` | No | Primary key. |
| `name` | `string(150)` | No | Material name. |
| `base_unit_id` | `foreignId` | No | Base UOM FK. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `raw_materials (many) -> units (1)` via `raw_materials.base_unit_id -> units.unit_id`
- `raw_materials (1) -> bom (many)` via `bom.material_id -> raw_materials.material_id`
- `raw_materials (1) -> pr_lines (many)` via `pr_lines.material_id -> raw_materials.material_id`
- `raw_materials (1) -> rfq (many)` via `rfq.material_id -> raw_materials.material_id`
- `raw_materials (1) -> vendor_materials (many)` via `vendor_materials.material_id -> raw_materials.material_id`

**Key constraints / rules**
- Unique: `name`
- FK delete policy: `base_unit_id` uses `ON DELETE RESTRICT`

---

### `bom`
**Purpose:** Product-to-material requirement lines (quantity and UOM).

| Column | Type | Nullable | Description |
|---|---|---|---|
| `bom_id` | `bigIncrements` | No | Primary key. |
| `product_id` | `foreignId` | No | Product FK. |
| `material_id` | `foreignId` | No | Material FK. |
| `quantity_required` | `decimal(18,4)` | No | Required quantity per BOM line. |
| `unit_id` | `foreignId` | No | UOM FK. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `bom (many) -> products (1)` via `bom.product_id -> products.product_id`
- `bom (many) -> raw_materials (1)` via `bom.material_id -> raw_materials.material_id`
- `bom (many) -> units (1)` via `bom.unit_id -> units.unit_id`

**Key constraints / rules**
- Unique: `product_id, material_id`
- Check: `quantity_required > 0`
- FK delete policy: `product_id` cascade, `material_id` restrict, `unit_id` restrict

---

### `vendors`
**Purpose:** Supplier master data used for sourcing and RFQ distribution.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `vendor_id` | `bigIncrements` | No | Primary key. |
| `name` | `string(150)` | No | Vendor legal/commercial name. |
| `email` | `string(255)` | No | Vendor contact email. |
| `phone` | `string(50)` | Yes | Vendor phone. |
| `country` | `string(100)` | Yes | Vendor country. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `vendors (1) -> vendor_materials (many)` via `vendor_materials.vendor_id -> vendors.vendor_id`
- `vendors (1) -> rfq_recipients (many)` via `rfq_recipients.vendor_id -> vendors.vendor_id`

**Key constraints / rules**
- Unique: `email`

---

### `vendor_materials`
**Purpose:** Sourcing matrix between vendor and material with commercial and planning indicators.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `vendor_material_id` | `bigIncrements` | No | Primary key. |
| `vendor_id` | `foreignId` | No | Vendor FK. |
| `material_id` | `foreignId` | No | Material FK. |
| `lead_time_days` | `integer` | Yes | Typical lead time in days. |
| `minimum_order_qty` | `decimal(18,4)` | Yes | Minimum order quantity. |
| `preferred_vendor` | `boolean` | No | Preferred source flag. |
| `last_price` | `decimal(18,4)` | Yes | Last known price amount. |
| `currency_id` | `foreignId` | Yes | Currency for `last_price`. |
| `vendor_material_code` | `string(100)` | Yes | Vendor-side material identifier. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `vendor_materials (many) -> vendors (1)` via `vendor_materials.vendor_id -> vendors.vendor_id`
- `vendor_materials (many) -> raw_materials (1)` via `vendor_materials.material_id -> raw_materials.material_id`
- `vendor_materials (many) -> currencies (1)` via `vendor_materials.currency_id -> currencies.id`

**Key constraints / rules**
- Unique: `vendor_id, material_id`
- FK delete policy: `vendor_id` cascade, `material_id` cascade, `currency_id` restrict

---

### `pr_header`
**Purpose:** Purchase Request document header with lifecycle and audit trail.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `pr_id` | `bigIncrements` | No | Primary key. |
| `pr_number` | `string(30)` | No | Generated document number (`PR-YYYYMMDD-XXXX`). |
| `requester_id` | `foreignId` | No | Requesting user FK. |
| `department_id` | `foreignId` | No | Department snapshot FK at creation time. |
| `request_date` | `date` | No | PR date. |
| `status` | `enum` | No | `draft`, `submitted`, `approved`, `cancelled`. |
| `notes` | `text` | Yes | Optional PR note. |
| `approved_by` | `foreignId` | Yes | Approving user FK. |
| `approved_at` | `timestamp` | Yes | Approval timestamp. |
| `cancelled_by` | `foreignId` | Yes | Cancelling user FK. |
| `cancelled_at` | `timestamp` | Yes | Cancellation timestamp. |
| `cancellation_reason` | `text` | Yes | Required at business layer when cancelling. |
| `deleted_at` | `timestamp` | Yes | Soft delete timestamp. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `pr_header (many) -> users (1)` via `requester_id -> users.user_id`
- `pr_header (many) -> departments (1)` via `department_id -> departments.department_id`
- `pr_header (many) -> users (1)` via `approved_by -> users.user_id`
- `pr_header (many) -> users (1)` via `cancelled_by -> users.user_id`
- `pr_header (1) -> pr_lines (many)` via `pr_lines.pr_id -> pr_header.pr_id`

**Key constraints / rules**
- Unique: `pr_number`
- Index: `status`
- Index: `requester_id`
- Soft deletes enabled
- FK delete policy: requester/department/approver/canceller are all `ON DELETE RESTRICT` (except nullable columns)

---

### `pr_lines`
**Purpose:** Detailed material requests under a PR.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `pr_line_id` | `bigIncrements` | No | Primary key. |
| `pr_id` | `foreignId` | No | PR header FK. |
| `line_no` | `unsignedSmallInteger` | No | Sequence number inside PR. |
| `material_id` | `foreignId` | No | Requested material FK. |
| `quantity` | `decimal(18,4)` | No | Requested quantity. |
| `unit_id` | `foreignId` | No | Requested UOM FK. |
| `required_delivery_date` | `date` | No | Requested delivery date. |
| `notes` | `text` | Yes | Optional line notes. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `pr_lines (many) -> pr_header (1)` via `pr_id -> pr_header.pr_id`
- `pr_lines (many) -> raw_materials (1)` via `material_id -> raw_materials.material_id`
- `pr_lines (many) -> units (1)` via `unit_id -> units.unit_id`
- `pr_lines (1) -> rfq (0..1)` via `rfq.pr_line_id -> pr_lines.pr_line_id` (unique on RFQ side)

**Key constraints / rules**
- Unique: `pr_id, line_no`
- FK delete policy: `pr_id` cascade; `material_id` restrict; `unit_id` restrict

---

### `rfq`
**Purpose:** Vendor-facing request document generated from one PR line.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `rfq_id` | `bigIncrements` | No | Primary key. |
| `pr_line_id` | `foreignId` | No | Source PR line FK (one RFQ per line). |
| `rfq_number` | `string(30)` | No | Generated document number (`RFQ-YYYYMMDD-XXXX`). |
| `material_id` | `foreignId` | No | Snapshot material FK copied from PR line. |
| `quantity` | `decimal(18,4)` | No | Snapshot quantity copied from PR line. |
| `unit_id` | `foreignId` | No | Snapshot unit FK copied from PR line. |
| `required_delivery_date` | `date` | No | Snapshot required date. |
| `rfq_date` | `date` | No | RFQ date. |
| `quotation_due_date` | `date` | No | Vendor quotation deadline. |
| `currency_id` | `foreignId` | Yes | Required business currency for responses (FK). |
| `payment_terms` | `string(255)` | Yes | Commercial payment terms. |
| `delivery_location` | `string(255)` | Yes | Delivery location text. |
| `pdf_path` | `string(500)` | Yes | Stored PDF path, if persisted. |
| `issued_by` | `foreignId` | Yes | Issuing user FK. |
| `issued_at` | `timestamp` | Yes | Issue timestamp. |
| `status` | `enum` | No | `draft`, `issued`, `closed`, `awarded`, `cancelled`. |
| `deleted_at` | `timestamp` | Yes | Soft delete timestamp. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `rfq (many) -> pr_lines (1)` via `pr_line_id -> pr_lines.pr_line_id`
- `rfq (many) -> raw_materials (1)` via `material_id -> raw_materials.material_id`
- `rfq (many) -> units (1)` via `unit_id -> units.unit_id`
- `rfq (many) -> currencies (1)` via `currency_id -> currencies.id`
- `rfq (many) -> users (1)` via `issued_by -> users.user_id`
- `rfq (1) -> rfq_recipients (many)` via `rfq_recipients.rfq_id -> rfq.rfq_id`

**Key constraints / rules**
- Unique: `pr_line_id` (one RFQ per PR line)
- Unique: `rfq_number`
- Check: `quotation_due_date >= rfq_date`
- Check: `quantity > 0`
- Check: `status = 'draft' OR issued_by IS NOT NULL`
- Index: `status`
- Soft deletes enabled
- FK delete policy: source/snapshot refs are `ON DELETE RESTRICT`; recipients cascade from RFQ

---

### `rfq_recipients`
**Purpose:** Junction table defining which vendors receive each RFQ.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `recipient_id` | `bigIncrements` | No | Primary key. |
| `rfq_id` | `foreignId` | No | RFQ FK. |
| `vendor_id` | `foreignId` | No | Vendor FK. |
| `status` | `enum` | No | `pending`, `sent`, `responded`, `declined`, `expired`. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `rfq_recipients (many) -> rfq (1)` via `rfq_id -> rfq.rfq_id`
- `rfq_recipients (many) -> vendors (1)` via `vendor_id -> vendors.vendor_id`
- `rfq_recipients (1) -> quotation (many)` via `quotation.recipient_id -> rfq_recipients.recipient_id`

**Key constraints / rules**
- Unique: `rfq_id, vendor_id` (same vendor cannot be added twice to one RFQ)
- FK delete policy: `rfq_id` cascade; `vendor_id` restrict

---

### `quotation`
**Purpose:** Vendor quotations with revision/version control per recipient.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `quotation_id` | `bigIncrements` | No | Primary key. |
| `recipient_id` | `foreignId` | No | RFQ recipient FK. |
| `version_no` | `unsignedSmallInteger` | No | Sequential version per recipient. |
| `unit_price` | `decimal(18,4)` | No | Offered unit price. |
| `lead_time_days` | `integer` | Yes | Offered lead time in days. |
| `status` | `enum` | No | `draft`, `submitted`, `revised`, `withdrawn`, `rejected`, `awarded`. |
| `created_at` | `timestamp` | Yes | Creation timestamp. |
| `updated_at` | `timestamp` | Yes | Update timestamp. |

**Relationships**
- `quotation (many) -> rfq_recipients (1)` via `recipient_id -> rfq_recipients.recipient_id`
- Currency is inherited through chain: `quotation -> rfq_recipients -> rfq -> currencies`

**Key constraints / rules**
- Unique: `recipient_id, version_no`
- Check: `unit_price > 0`
- Check: `lead_time_days IS NULL OR lead_time_days >= 0`
- Index: `status`
- FK delete policy: `recipient_id` cascade

---

## 4. ERD Summary (plain-English relationship chains)

1. **Demand chain:**  
   A requester (user) creates a PR header, then adds one or more PR lines that reference materials and UOM.

2. **RFQ generation chain:**  
   Each approved PR line can produce exactly one RFQ. The RFQ stores a snapshot of material, quantity, unit, and required date.

3. **Vendor distribution chain:**  
   One RFQ is sent to many vendor recipients using `rfq_recipients`; each recipient entry tracks delivery/response status.

4. **Quotation chain:**  
   Each recipient can submit multiple quotation versions. Version numbering is unique per recipient.

5. **Commercial context chain:**  
   RFQ header sets one currency (`rfq.currency_id`) for all quotes under that RFQ. Quotations do not store currency directly.

6. **Sourcing intelligence chain:**  
   Vendor capability and latest commercial terms are modeled in `vendor_materials` including `last_price`, `currency_id`, lead time, and MOQ.

---

## 5. Design Decisions

### Snapshot pattern
- `pr_header.department_id` is captured as a snapshot of requester’s department at creation time.
- `rfq` captures snapshot fields from the source PR line (`material_id`, `quantity`, `unit_id`, `required_delivery_date`).
- Rationale: preserves historical correctness even if master data or PR line context changes later.

### Soft deletes on business documents
- Enabled on `pr_header` and `rfq`.
- Rationale: cancellation/archival should not destroy audit history or reporting traceability.

### Audit trail strategy
- PR approval/cancellation actor and timestamps are persisted (`approved_by`, `approved_at`, `cancelled_by`, `cancelled_at`, `cancellation_reason`).
- RFQ issuance actor and timestamp are persisted (`issued_by`, `issued_at`).
- Rationale: supports accountability and compliance-ready timeline reconstruction.

### Currency architecture
- RFQ uses `currency_id` FK to define target commercial currency.
- Quotation table intentionally has **no currency field** and inherits currency from parent RFQ via recipient link.
- Vendor-material pricing uses `vendor_materials.currency_id` to preserve pricing context by supplier market.
- Rationale: one RFQ = one currency, reducing inconsistency across quotations for the same event.
