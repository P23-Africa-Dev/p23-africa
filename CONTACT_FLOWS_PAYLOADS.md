# P23 Africa — Contact Form Flows & Payload Specification

This document details the complete end-to-end payload specifications, routing rules, data dictionaries, and API contracts for all three flows triggered by the 3-step Contact Enquiry Form.

---

## 1. Flow Routing Overview

The user's budget amount entered in **Step 3 (`budget_range`)** determines the subsequent user journey and API routing:

| Budget Range (GBP) | Flow / Route | Target Destination | Full User Flow |
| :--- | :--- | :--- | :--- |
| **£0 – £1,499.99** | **Route 3 (`resources`)** | Resource Hub | Contact Form (Steps 1–3) $\rightarrow$ Success Screen $\rightarrow$ Resource Hub (`/resource-hub`) |
| **£1,500 – £2,500** | **Route 2 (`consultation`)** | Paid Consultation | Contact Form $\rightarrow$ Schedule Consultation $\rightarrow$ Key Challenges $\rightarrow$ Consultation Summary $\rightarrow$ Payment Success |
| **> £2,500** | **Route 1 (`discovery`)** | Discovery Call | Contact Form $\rightarrow$ Schedule Discovery Call $\rightarrow$ Discovery Call Confirmed Modal |

---

## 2. Base Contact Form Submission (Steps 1–3)

All three flows start with the three-step enquiry form at `/contact`.

### HTTP Request
- **Endpoint:** `POST /contact`
- **Headers:**
  - `Content-Type: application/json`
  - `Accept: application/json`
  - `X-CSRF-TOKEN: <token>`
- **Form Flow Identifier:** `form_flow: "contact-v2"`

### Complete Request Payload
```json
{
  "form_flow": "contact-v2",
  "name": "Elijah Obominuru",
  "country_code": "+44",
  "phone": "7911123456",
  "company_name": "Acme Africa Technologies",
  "email": "elijah@acme.africa",
  "industry": "Technology",
  "company_website": "https://acme.africa",
  "role": "Founder & CEO",
  "selected_services": [
    "Market Entry & Expansion",
    "Distributor & Partner Development"
  ],
  "expansion_markets": [
    "Nigeria",
    "Ghana",
    "Kenya"
  ],
  "message": "We are looking to expand our B2B SaaS platform into West Africa and identify vetted channel partners.",
  "referral_source": "LinkedIn",
  "referral_other": null,
  "start_timeline": "Within the next month",
  "budget_range": 2000,
  "additional_notes": "We currently have a localized team in London and wish to establish our initial Lagos presence in Q1."
}
```

### Field Definitions & Validation Rules
| Field | Step | Type | Required | Constraints / Allowed Values |
| :--- | :---: | :--- | :---: | :--- |
| `form_flow` | Hidden | `string` | **Yes** | Must be `"contact-v2"`. |
| `name` | 1 | `string` | **Yes** | Max 255 characters. |
| `country_code` | 1 | `string` | **Yes** | E.164 country dial code, e.g. `"+44"`, `"+234"`, `"+27"`, `"+1"`. |
| `phone` | 1 | `string` | **Yes** | 5 to 20 characters, regex: `^[0-9 ()+\-]{5,20}$`. |
| `company_name` | 1 | `string` | **Yes** | Max 255 characters. |
| `email` | 1 | `string` | **Yes** | Valid RFC email address, max 255 characters. |
| `industry` | 1 | `string` | **Yes** | Must be one of: `"Manufacturing"`, `"Technology"`, `"Consumer Goods"`, `"Healthcare"`, `"Financial Services"`, `"Agriculture"`, `"Energy"`, `"Professional Services"`, `"Other"`. |
| `company_website` | 1 | `string` | **Yes** | Website or LinkedIn URL, max 2048 characters. |
| `role` | 1 | `string` | **Yes** | Max 255 characters. |
| `selected_services` | 2 | `array[string]` | **Yes** | Min 1, Max 5. Options: `"Market Entry & Expansion"`, `"Distributor & Partner Development"`, `"Outbound Sales Demand Testing"`, `"Sales & Market Support"`, `"Investment & Growth Readiness"`. |
| `expansion_markets` | 2 | `array[string]` | **Yes** | Min 1, Max 10. Selected African country names. |
| `message` | 2 | `string` | **Yes** | Max 2000 characters (goals / what you want to accomplish). |
| `referral_source` | 2 | `string` | **Yes** | One of: `"LinkedIn"`, `"Tiktok/Instagram"`, `"Google"`, `"AI Chat"`, `"Referrals"`, `"Other"`. |
| `referral_other` | 2 | `string` | Conditional | Required if `referral_source` is `"Other"`, max 255 characters. |
| `start_timeline` | 3 | `string` | **Yes** | One of: `"As soon as possible"`, `"Within the next month"`, `"In 1–3 months"`, `"More than 3 months"`, `"Just exploring"`. |
| `budget_range` | 3 | `numeric` | **Yes** | Min 0, whole or decimal number in GBP (£). |
| `additional_notes` | 3 | `string` | Optional | Max 2000 characters. |

---

## 3. Flow 1: Low Budget (£0 – £1,499.99) — Resource Hub

When `budget_range` is less than `1500`, the contact submission is saved, and the visitor is guided to P23's self-serve playbooks and resources.

### Primary API Response
```json
{
  "success": true,
  "message": "Thank you for reaching out. Our team will get back to you shortly.",
  "outcome": {
    "route": "resources",
    "title": "Start with practical resources",
    "description": "Explore insights and guides to sharpen your plans before committing to a project.",
    "label": "Explore our services",
    "url": "https://p23africa.com/resource-hub"
  }
}
```

### Client UI Action
1. The contact form steps are hidden.
2. The `.cf-success` screen is displayed:
   - **Heading:** *"Thank you for reaching out."*
   - **Text:** *"Your enquiry has been sent. Our team will get back to you soon."*
   - **Button / Link:** *"Explore our services"* linking directly to `/resource-hub`.
3. No further payloads or booking steps are required.

---

## 4. Flow 2: Mid Budget (£1,500 – £2,500) — Paid Consultation

When `budget_range` is between `1500` and `2500` (inclusive), the user is routed through consultation scheduling, key challenges, review summary, and payment.

### Step 2.1: Contact Form Response
```json
{
  "success": true,
  "message": "Thank you for reaching out. Our team will get back to you shortly.",
  "outcome": {
    "route": "consultation",
    "url": "https://p23africa.com/contact/consultation/schedule?assessment=eyJvdXRjb21lIjoiY29uc3VsdGF0aW9uIiwibmFtZSI6IkVsaWphaCBPYm9taW51cnUiLC..."
  }
}
```

### Step 2.2: Schedule Consultation Time Slot
- **Page:** `GET /contact/consultation/schedule?assessment=<token>`
- **Endpoint:** `POST /contact/consultation/schedule`
- **Headers:** `Content-Type: application/json`, `X-CSRF-TOKEN: <token>`

#### Request Payload
```json
{
  "session_date": "2026-11-06",
  "session_time": "11:00",
  "timezone": "GMT"
}
```

#### Field Specifications
| Field | Type | Required | Constraints |
| :--- | :--- | :---: | :--- |
| `session_date` | `string` | **Yes** | Format: `YYYY-MM-DD`. Must be strictly after today (from tomorrow up to 6 months). |
| `session_time` | `string` | **Yes** | Format: `HH:mm`, e.g. `"09:00"`, `"10:00"`, `"11:00"`, `"14:00"`. |
| `timezone` | `string` | **Yes** | One of: `"GMT"`, `"UTC"`, `"WAT"`, `"Africa/Lagos"`. |

#### Response
```json
{
  "success": true,
  "redirect": "/contact/consultation/challenges?assessment=<token>"
}
```

---

### Step 2.3: Submit Key Challenges
- **Page:** `GET /contact/consultation/challenges?assessment=<token>`
- **Endpoint:** `POST /contact/consultation/challenges`
- **Headers:** `Content-Type: application/x-www-form-urlencoded` or `application/json`

#### Request Payload
```json
{
  "_token": "dGhpcy1pcy1hLXNhbXBsZS1jc3JmLXRva2Vu",
  "assessment": "eyJvdXRjb21lIjoiY29uc3VsdGF0aW9uIiwibmFtZSI6IkVsaWphaCBPYm9taW51cnUiLC...",
  "key_challenges": [
    "Weak marketing strategy",
    "Scaling challenges",
    "Difficulty acquiring customers"
  ]
}
```

#### Allowed Key Challenge Values (Min 1, Max 3)
- `"Low or inconsistent sales"`
- `"Weak marketing strategy"`
- `"Difficulty acquiring customers"`
- `"Operational inefficiencies"`
- `"Monetization not working"`
- `"Need clarity on product-market fit"`
- `"Scaling challenges"`

#### Response
- **HTTP 302 Redirect** to:
  `/book-consultation/summary?booking=<booking_uuid>`

---

### Step 2.4: Review Summary & Initiate Payment
- **Page:** `GET /book-consultation/summary?booking=<booking_uuid>`
  Displays prefilled overview, expansion markets, selected key challenges, and scheduled time.
- **Endpoint:** `POST /book-consultation/payment/initiate`

#### Request Payload
```json
{
  "_token": "dGhpcy1pcy1hLXNhbXBsZS1jc3JmLXRva2Vu",
  "booking": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d"
}
```

#### Response (Paystack Redirect)
- Redirects to Paystack checkout URL.

---

### Step 2.5: Payment Success Screen
- **Endpoint:** `GET /book-consultation/payment-success?booking=<booking_uuid>`
- **Display:** Displays payment confirmation, consultation receipt, and calendar link.

---

## 5. Flow 3: High Budget (> £2,500) — Discovery Call

When `budget_range` is strictly greater than `2500`, the visitor is qualified for an executive Discovery Call.

### Step 3.1: Contact Form Response
```json
{
  "success": true,
  "message": "Thank you for reaching out. Our team will get back to you shortly.",
  "outcome": {
    "route": "discovery",
    "url": "https://p23africa.com/contact/discovery-call?assessment=eyJvdXRjb21lIjoiZGlzY292ZXJ5IiwibmFtZSI6IkVsaWphaCBPYm9taW51cnUiLC..."
  }
}
```

### Step 3.2: Schedule Discovery Call
- **Page:** `GET /contact/discovery-call?assessment=<token>`
- **Endpoint:** `POST /contact/discovery-request`
- **Headers:**
  - `Content-Type: application/json`
  - `Accept: application/json`
  - `X-CSRF-TOKEN: <token>`

#### Request Payload
```json
{
  "session_date": "2026-11-05",
  "session_time": "14:00",
  "timezone": "GMT",
  "assessment": "eyJvdXRjb21lIjoiZGlzY292ZXJ5IiwibmFtZSI6IkVsaWphaCBPYm9taW51cnUiLC..."
}
```

#### Field Specifications
| Field | Type | Required | Constraints |
| :--- | :--- | :---: | :--- |
| `session_date` | `string` | **Yes** | Format: `YYYY-MM-DD`. Today or future date within 6 months. |
| `session_time` | `string` | **Yes** | Format: `HH:mm`. |
| `timezone` | `string` | **Yes** | One of: `"GMT"`, `"UTC"`, `"WAT"`, `"Africa/Lagos"`. |
| `assessment` | `string` | Optional | Base64URL encoded state token (used when stateless or cross-domain). |

#### Response
```json
{
  "success": true,
  "message": "A confirmation email containing the date, time, and Google Meet link has been sent to your registered email address.",
  "user_email": "elijah@acme.africa"
}
```

### Step 3.3: UI Confirmation
- The `Schedule Your Discovery Call` page displays the **Discovery Call Confirmed!** modal:
  - **Title:** *"Discovery Call Confirmed!"*
  - **Body:** *"A confirmation email containing the date, time, and Google Meet link has been sent to your registered email address."*
  - **Email badge:** Shows the user's registered email (`elijah@acme.africa`).
  - **Action button:** Returns to homepage.

---

## 6. Summary Comparison Table

```
+---------------------------------------------------------------------------------------------------------+
|                                    CONTACT FORM (POST /contact)                                         |
|                                     form_flow: "contact-v2"                                             |
+----------------------------------------------------+----------------------------------------------------+
                                                     |
                                   [ Evaluates budget_range ]
                                                     |
        +--------------------------------------------+--------------------------------------------+
        |                                            |                                            |
        v (< 1500 GBP)                               v (1500 - 2500 GBP)                          v (> 2500 GBP)
+-------------------------------+  +------------------------------------------+  +--------------------------------+
|    ROUTE 3: RESOURCE HUB      |  |       ROUTE 2: PAID CONSULTATION         |  |   ROUTE 1: DISCOVERY CALL      |
+-------------------------------+  +------------------------------------------+  +--------------------------------+
| 1. Inline Success Card        |  | 1. POST /contact/consultation/schedule   |  | 1. POST /contact/discovery-    |
| 2. Link: /resource-hub        |  |    Payload: session_date, time, tz       |  |    request                     |
|                               |  | 2. POST /contact/consultation/challenges |  |    Payload: session_date,      |
|                               |  |    Payload: key_challenges[] (1-3)      |  |    session_time, timezone      |
|                               |  | 3. POST /book-consultation/payment/      |  | 2. Modal: "Discovery Call      |
|                               |  |    initiate                              |  |    Confirmed!"                 |
|                               |  |    Payload: booking (UUID)               |  |    (Google Meet invite sent)   |
|                               |  | 4. GET /book-consultation/               |  |                                |
|                               |  |    payment-success                       |  |                                |
+-------------------------------+  +------------------------------------------+  +--------------------------------+
```

