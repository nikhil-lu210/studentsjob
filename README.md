# NUP Student Jobs: Institutionally Verified Student Employment Platform

> A Laravel web application: a closed-loop, university-verified job platform connecting Neapolis University Pafos (NUP) students with vetted Paphos employers, with legal compliance built into the platform itself.

This README is the **single source of truth** for what we are building, why, and how every role behaves. If code and this document disagree, ask before changing either.

---

## 1. Motive: why this project exists

### 1.1 Background
This application is the practical artefact of an MSc dissertation (*Investigating the Role of Institutionally Verified Digital Platforms in Student Employment Matching: A Case Study at Neapolis University Pafos and the Paphos Labour Market*). The research uses **Design Science Research (DSR)**: build an artefact, evaluate it, and derive transferable design principles.

### 1.2 The problem
- Paphos has a tourism, hospitality and seasonal-retail economy that needs flexible, rotating labour. Students are a natural supply.
- Existing channels (LinkedIn, Ergodotisi.com, word of mouth) are built for professional full-time hiring. They have:
  - **No institutional identity verification** for students or employers.
  - **No awareness of academic timetables.**
  - **No enforcement** of Cyprus Ministry of Labour rules for student workers (permitted sectors, weekly hour caps).
- The result is a **high-friction, low-trust market**. Employers can't verify applicants or their work eligibility. Students can't verify employer legitimacy. This is *information asymmetry* (Akerlof, 1970).

### 1.3 The core idea
Use the **institution (NUP) as the trusted intermediary**:

1. **Student identity** is proven by a verified institutional email domain (`.ac.cy`) plus Career Services confirmation. This is a low-cost, hard-to-fake *signal* (Spence, 1973).
2. **Employer identity** is proven by Career Services vetting (business registration, contact checks). Employers extend trust to NUP, not to unknown individuals (*trust transfer*).
3. **Legal compliance** (sector restrictions, weekly hour caps) is enforced by the software, not left to chance.
4. **Cold start is avoided.** The student user base already exists and is contactable through the university, so the platform doesn't need to buy network growth.

### 1.4 What the software must demonstrate (thesis to feature map)

| Thesis claim | What the app must do |
|---|---|
| Institutional verification works as a trust signal (RQ2) | Domain-gated registration, Career Services approval, visible **Verified** badges, and a way to measure their effect |
| Compliance is embedded in platform architecture (Objective 4) | A configurable **compliance engine** (sectors, hour caps, term vs holiday) |
| Schedule-aware matching | Student availability grid plus explainable match scoring |
| Closed loop bypasses cold start | Institution-bounded users; Career Services onboards employers |
| TAM plus Perceived Risk evaluation (RQ3) | In-app **survey module** (PU, PEOU, PR, adoption intent, 7-point Likert) with CSV export |
| Transferable design principles (RQ4) | Everything institution-specific is **configuration**, not hard-coded |

### 1.5 Scope and constraints (important)
- This is a **research prototype**. All demo and test data is **synthetic**. Never store or process real student employment records.
- Legal rules (hour caps, permitted sectors) **change over time**. Store them in database tables with effective dates. **Never hard-code them.**
- The initial rule values come from the proposal: **20 h/week during term, 38 h/week during holidays**. These must be re-verified against current Cyprus Ministry of Labour directives and may differ by student status (EU vs third-country).
- A real launch would need a GDPR assessment and formal NUP approval. Build with privacy in mind anyway (see section 9).

---

## 2. Tech stack

| Layer | Choice |
|---|---|
| Framework | Laravel 12 (PHP 8.3+) |
| Database | MySQL 8 or PostgreSQL |
| Frontend | Blade + Livewire 3 + Tailwind CSS (Alpine.js for small interactions) |
| Admin and Career Services panel | Filament 3 |
| Auth | Laravel Breeze (with `MustVerifyEmail`) |
| Roles and permissions | `spatie/laravel-permission` |
| Audit trail | `spatie/laravel-activitylog` |
| Queues and notifications | Laravel Queues (database driver), Mail + database notifications |
| Scheduling | Laravel Scheduler |
| Exports | `maatwebsite/excel` |
| Testing | Pest or PHPUnit, with model factories and seeders |

---

## 3. Roles overview

| Role | Who | Panel | Primary goal |
|---|---|---|---|
| **Guest** | Unregistered visitor | Public site | Understand the platform, register |
| **Student** | Verified NUP student | Student area | Find compliant jobs, apply, manage hours |
| **Employer** | Paphos business user | Employer area | Post jobs, hire verified students |
| **Career Services Officer** | NUP Career/Liaison staff | Staff panel (Filament) | Vet employers, moderate listings, monitor compliance |
| **Super Admin** | System administrator | Admin panel (Filament) | Configure rules, domains, terms, users |
| **Researcher** | Dissertation author | Research panel (read-only) | Anonymised analytics, survey export |

### 3.1 Permission matrix (summary)

| Capability | Guest | Student | Employer | Career Svc | Admin | Researcher |
|---|:-:|:-:|:-:|:-:|:-:|:-:|
| View landing / how it works | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Register | ✅ | n/a | n/a | n/a | n/a | n/a |
| Browse published jobs | ❌ | ✅ (eligible only) | own only | ✅ | ✅ | ❌ |
| Apply to jobs | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Post jobs | ❌ | ❌ | ✅ (if approved) | ✅ (on behalf, optional) | ❌ | ❌ |
| Approve employers | ❌ | ❌ | ❌ | ✅ | ✅ | ❌ |
| Moderate listings | ❌ | ❌ | ❌ | ✅ | ✅ | ❌ |
| Edit compliance rules | ❌ | ❌ | ❌ | read only | ✅ | ❌ |
| Manage users / roles | ❌ | ❌ | ❌ | limited (suspend) | ✅ | ❌ |
| View anonymised analytics | ❌ | ❌ | ❌ | ✅ | ✅ | ✅ |
| Export survey data | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| View audit log | ❌ | ❌ | ❌ | ✅ | ✅ | ❌ |

> **Rule:** every route, Livewire component and Filament resource must be protected by role/permission middleware and policies. Never rely on hidden UI alone.

---

## 4. Core domain concepts

### 4.1 Verification states
- **Student:** `unverified` → `email_verified` → `verified` (Career Services confirmed, optional) → `expired` (per-semester re-verification) / `suspended`
- **Employer:** `pending` → `approved` / `rejected` / `needs_info` → `suspended`

### 4.2 Job categories
`part_time`, `full_time`, `internship`

### 4.3 Student status (drives legal rules)
`eu` or `third_country`. The compliance engine uses this to decide which rules apply. **Verify actual rules before launch.**

### 4.4 Academic periods
Stored in `academic_terms` with `type` = `term` or `holiday`, `starts_on`, `ends_on`. The active period decides which weekly cap applies.

### 4.5 Hours ledger
The weekly cap applies **per student across all employers**, not per job. The platform can only see hours booked through it, so:
- The ledger sums committed weekly hours from active placements.
- Students may declare external hours (self-reported) which count toward the cap.
- Document this limitation in the thesis.

### 4.6 Compliance verdicts
`pass`, `warning` (fixable, e.g. hours slightly high), `block` (not allowed, e.g. restricted sector for a student category).

---

## 5. End-to-end workflow

```
Student onboarding ─┐
                    ├─► Job posting ─► Compliance check ─► Moderation ─► Published
Employer vetting  ──┘                                                      │
                                                                           ▼
        Completion & review ◄─ Placement & hours ◄─ Offer ◄─ Selection ◄─ Application
                    │
                    ▼
            TAM survey (research evaluation)
```

### Stage 1: Student onboarding
1. Student opens `/register/student` and enters an email.
2. System checks the domain against `allowed_domains` (e.g. `nup.ac.cy`). Non-matching domains are **rejected** with a clear message.
3. Laravel sends a verification link (`MustVerifyEmail`).
4. After verification, the student completes the profile: name, programme, year of study, **student status (EU / third-country)**, languages, skills, preferred job categories and sectors.
5. Student fills the **weekly availability grid** (Mon–Sun time blocks marked available or busy, e.g. lectures).
6. Account gets the **NUP Verified** badge. Optionally, Career Services confirms enrolment manually.
7. Verification **expires each semester**. A scheduled job reminds the student to re-verify. Expired students cannot apply.

### Stage 2: Employer onboarding and vetting
1. Employer opens `/register/employer` and submits: business name, sector, registration number, Paphos address, contact person, phone, email, optional document upload.
2. Email verification is required. Free-mail domains (Gmail etc.) are **allowed but flagged** for extra scrutiny.
3. The application lands in the **Career Services approval queue**.
4. Officer reviews and chooses: **Approve**, **Reject** (with reason), or **Request more info**.
5. On approval, the employer gets the **Verified Employer** badge and can post jobs. Until then, they can only edit their profile.
6. Every decision is written to the audit log and emailed to the employer.

### Stage 3: Job posting with compliance check
1. Approved employer opens the posting wizard: title, description, category, sector, location, pay (required), start and end dates, weekly hours, shift schedule, required skills and languages.
2. **ComplianceService runs live** as the form is filled:
   - Is the sector permitted for student workers?
   - Do weekly hours exceed the cap for the relevant period (term vs holiday)?
   - Is pay stated?
   - Do dates overlap a term/holiday boundary? (Show both caps.)
3. Outcome shown immediately: `pass` / `warning` / `block`. A `block` prevents submission.
4. Valid postings enter status `pending_moderation`.
5. Career Services moderates: **Approve** (publish) / **Reject** (reason) / **Request changes**.
6. Published listings display a **Compliance Checked** badge and the employer's **Verified Employer** badge.

### Stage 4: Discovery and matching
- Students browse and filter by category, sector, pay, schedule fit and location.
- **Eligibility filter:** students only see jobs they can legally take, based on status, sector rules and remaining hours.
- **Explainable match score.** Suggested weights (configurable):
  - Availability overlap: 40%
  - Skills and language fit: 25%
  - Preferences (category, sector): 20%
  - Proximity: 15%
- Each recommendation shows **why** it matched (e.g. "Fits your Tue/Thu evenings", "Needs English, you speak English").
- Employers can view suggested candidates for a listing (anonymised until the student applies or accepts an invitation) and send an **invite to apply**.

### Stage 5: Application
1. Student clicks Apply, adds a short message and optional CV.
2. **Hour-budget check:** `current committed hours + job hours` is compared against the cap for each affected week. Result:
   - Within cap: submit.
   - Over cap: **blocked** with an explanation.
   - Timetable clash: **warning** (student may proceed).
3. The application is created with status `applied`. Both parties get notifications (in-app and email).

### Stage 6: Selection pipeline
Application status machine:

```
applied → shortlisted → interview → offered → accepted (hired)
   │           │            │          │
   └───────────┴────────────┴──────────┴──► rejected
applied/shortlisted/interview/offered ──► withdrawn (by student)
offered ──► declined (by student)
```

- Employer moves candidates through the pipeline (kanban board).
- **In-app messaging** per application. Personal contact details stay hidden until an offer is accepted.
- **Interview scheduling** proposes slots that respect the student's availability.
- Offer includes: weekly hours, pay, start and end date. Offer is re-checked by the compliance engine at send time.

### Stage 7: Placement and compliance tracking
1. Student **accepts** the offer, which creates a `placement` and updates the student's **hours ledger**.
2. The placement stores the agreed weekly hours, pay and dates.
3. Optional **hours logging:** student logs worked hours, employer confirms weekly.
4. **Scheduled alerts:**
   - Term ↔ holiday switch (cap change) affecting active placements
   - Student approaching or exceeding cap
   - Placement ending soon
   - Verification expiring
5. Career Services **compliance dashboard** lists placements and students near or over limits.

### Stage 8: Completion, feedback and disputes
- At placement end, both sides submit a **rating (1–5) and short review** (reduces perceived risk and protects against exploitation or unreliability).
- Either side can **report** a listing, employer, student or placement (reasons: non-payment, unsafe conditions, no-show, misrepresentation, other).
- Career Services handles reports: dismiss, warn, suspend or ban. All actions are audited.

### Stage 9: Research evaluation (dissertation Phase 3)
1. After a guided prototype session, participants see a **consent screen** (voluntary, anonymous).
2. They complete the **modified TAM survey**: Perceived Usefulness (PU), Perceived Ease of Use (PEOU), Perceived Risk (PR), Adoption Intent. All on a **7-point Likert** scale.
3. Optional **"Why verified?" experiment:** randomly show a listing with or without the verification badge, then ask a perceived-risk item, to test RQ2.
4. The Researcher role exports **anonymised** responses (CSV) for Cronbach's alpha (target > 0.7) and analysis.
5. Respondents are identified only by a random respondent code, never by account.

---

## 6. How each role works (detailed)

### 6.1 Guest
**Can:** view landing page, "How it works", FAQ, public aggregate stats (e.g. number of verified employers), register as student or employer, log in.
**Cannot:** see job details beyond a teaser, apply, post.
**Flow:** Land → read value proposition → choose *I'm a student* or *I'm an employer* → register.

### 6.2 Student
**Daily flow**
1. Log in → dashboard (new matches, application statuses, hours this week vs cap, alerts).
2. Keep profile and availability up to date.
3. Browse or search jobs; open a job to see compliance badge, employer reputation and match explanation.
4. Apply (hour-budget check runs) → track status → chat → attend interviews → accept or decline offers.
5. Log hours (if enabled) and watch the hours ledger.
6. Review the employer at the end; report problems.
7. Re-verify each semester.

**Features**
- Domain-restricted registration, email verification, NUP Verified badge
- Profile, skills, languages, CV upload, availability grid
- Search, filters, eligibility filtering, explainable match score
- Save jobs, apply, withdraw, application tracker
- **Hours ledger** (weekly committed vs cap, term/holiday indicator, external hours declaration)
- Messaging, interview scheduling, offer accept/decline
- Employer ratings and reviews, report function
- Notifications and preferences
- Privacy: download my data, delete my account (GDPR)

**Restrictions**
- Cannot apply if unverified, expired, suspended, or if the application would breach the cap.
- Cannot see jobs in sectors restricted for their status.

### 6.3 Employer
**Daily flow**
1. Register → wait for Career Services approval (track status on dashboard).
2. After approval: create a job via the wizard and fix any compliance warnings.
3. Wait for moderation; once published, review applicants.
4. Move candidates through the pipeline, message, schedule interviews, send offers.
5. Confirm placements and weekly hours; review students at the end.

**Features**
- Registration, business profile, verification status tracker
- Job wizard with real-time compliance feedback
- Edit, pause, close and **duplicate** listings (useful for seasonal rehiring)
- Applicant kanban, suggested candidates, invites
- Messaging, interview scheduling, offers
- Placement management and hours confirmation
- Student ratings; employer reputation page
- Optional: multiple recruiter users under one business

**Restrictions**
- Cannot post until approved.
- Cannot post blocked sectors or hours over cap.
- Cannot see student contact details before offer acceptance.
- Edits to a published listing that change hours, sector or pay trigger re-check and re-moderation.

### 6.4 Career Services Officer
**Daily flow**
1. Open staff dashboard (queues, alerts, KPIs).
2. Process the **employer approval queue**.
3. Process the **listing moderation queue** (review compliance flags).
4. Check the **compliance dashboard** (students near or over caps, restricted-sector flags).
5. Handle **reports and disputes**.
6. Run re-verification and manual student verification when needed.
7. Send announcements and do employer outreach (supports cold-start strategy).

**Features**
- Employer approval queue (documents, notes, approve/reject/request info)
- Listing moderation queue
- Student verification management
- Compliance dashboard and placement monitoring
- Reports and dispute handling; warn, suspend, ban
- Audit trail view
- Statistics: placements, sectors, time-to-fill, applications per job
- Broadcast announcements to students and/or employers

**Restrictions**
- Cannot edit compliance rules (read-only) or system settings.
- Every decision requires a reason and is audited.

### 6.5 Super Admin
**Features**
- User and role management
- **Allowed email domains** (e.g. `nup.ac.cy`)
- **Compliance rules:** sector, category, student status, period type, max weekly hours, `valid_from`, `valid_to`
- **Academic calendar:** terms and holidays
- Taxonomies: sectors, categories, skills, languages
- System settings (match weights, verification expiry, feature toggles)
- Activity logs, backups, queue/health monitoring

**Principle:** changing a rule must not break history. Use effective dates and keep old rules.

### 6.6 Researcher (read-only)
**Features**
- Anonymised analytics dashboard (counts, funnels, time-to-fill, compliance outcomes)
- TAM survey management and **CSV export** (respondent code, construct items, timestamps)
- Seeded synthetic dataset tools (generate and reset fake students, employers, jobs)

**Restrictions**
- No access to personal identifiers, messages, CVs or individual applications.
- No write access to operational data.

---

## 7. Business rules (for the compliance engine)

1. **Rules are data.** Table `compliance_rules` with: `sector_id`, `job_category`, `student_status`, `period_type` (`term`/`holiday`), `max_weekly_hours`, `is_allowed`, `valid_from`, `valid_to`.
2. **Initial seed values (VERIFY before use):** term = 20 h/week, holiday = 38 h/week.
3. A job spanning term and holiday periods must satisfy the cap for **each** week it covers.
4. The student-level check = sum of active placements' weekly hours + declared external hours + new job hours ≤ applicable cap for **every** affected week.
5. Compliance is evaluated at three points: **job save**, **application submit**, **offer send**. It is re-evaluated by the scheduler when a period changes.
6. Every compliance decision stores a snapshot (rule version, inputs, verdict) for the audit trail and for thesis evidence.
7. Matching never overrides compliance. A non-eligible job is not shown, regardless of score.

---

## 8. Data model (core tables)

| Table | Purpose |
|---|---|
| `users` | Auth, role, status, email verification |
| `student_profiles` | Programme, year, student status, skills, languages, preferences, verification state and expiry |
| `employer_profiles` | Business details, registration no., sector, verification state, reviewer notes |
| `employer_members` | (optional) Multiple users per employer |
| `allowed_domains` | Accepted student email domains |
| `sectors`, `skills`, `languages` | Taxonomies |
| `academic_terms` | Term/holiday periods |
| `compliance_rules` | Configurable legal rules |
| `jobs` | Listings (category, sector, pay, hours, dates, status, compliance verdict) |
| `job_shifts` | Shift schedule per job |
| `availability_slots` | Student weekly availability |
| `applications` | Student ↔ job application with status |
| `application_events` | Status history |
| `messages` | Per-application threads |
| `interviews` | Scheduled interviews |
| `offers` | Terms offered |
| `placements` | Accepted engagements |
| `hour_logs` | Logged and confirmed hours |
| `external_hour_declarations` | Student-declared outside hours |
| `reviews` | Ratings and comments |
| `reports` | Complaints and outcomes |
| `compliance_checks` | Snapshots of each compliance evaluation |
| `survey_responses` | TAM responses (anonymous respondent code) |
| `activity_log` | Audit trail (spatie) |
| `notifications` | Database notifications |

---

## 9. Non-functional requirements

- **Privacy and GDPR-minded:** collect minimum data; consent for research; data export and delete; no real data in the prototype.
- **Security:** role policies on everything, CSRF, rate limiting, validated uploads (type and size), private file storage, hashed passwords, 2FA optional for staff.
- **Auditability:** all approvals, moderation, rule changes and suspensions are logged with actor, time and reason.
- **Explainability:** match scores and compliance verdicts must always show reasons in plain language.
- **Configurability:** domains, rules, weights and terms live in DB or config so another university can reuse the platform (RQ4).
- **Localisation-ready:** English first; keep strings in lang files so Greek can be added.
- **Accessibility and mobile:** responsive, keyboard-accessible, WCAG-minded (Gen-Z users will mostly be on phones).
- **Performance:** eager-load relations, queue emails and notifications, index foreign keys and filter columns.

---

## 10. Suggested project structure

```
app/
  Enums/                 # JobCategory, StudentStatus, ApplicationStatus, VerificationState ...
  Models/
  Policies/
  Services/
    ComplianceService.php
    HoursLedgerService.php
    MatchingService.php
    VerificationService.php
  Actions/               # single-purpose actions (SubmitApplication, SendOffer ...)
  Livewire/
    Student/ Employer/ Public/
  Filament/
    Staff/               # Career Services panel
    Admin/               # Super Admin panel
    Research/            # Researcher panel
  Notifications/
  Console/Commands/      # scheduled tasks
database/
  migrations/ factories/ seeders/
resources/views/
routes/
tests/
  Feature/ Unit/
```

**Key services**
- `ComplianceService`: validates jobs, applications and offers against rules; returns verdict plus reasons.
- `HoursLedgerService`: weekly committed hours per student, remaining budget.
- `MatchingService`: weighted, explainable scoring; returns score plus reasons.
- `VerificationService`: domain checks, expiry, re-verification.

**Scheduled tasks (Laravel Scheduler)**
- Daily: expire verifications, send re-verification reminders, check term/holiday switches, placement-ending alerts.
- Weekly: cap-proximity alerts, hours confirmation reminders.

---

## 11. Build order (milestones)

1. **MVP:** auth with domain gating, roles, student and employer profiles, employer approval queue, job posting, applications, basic compliance rules
2. **Core value:** availability grid, hours ledger, matching, messaging, pipeline, offers, placements
3. **Trust layer:** reviews, reports, badges, audit trail, compliance dashboard
4. **Research layer:** TAM survey module, badge A/B experiment, anonymised analytics, CSV export, synthetic seeders

> **Scope guard:** the dissertation's required deliverable is a conceptual framework, journey map and low-fidelity wireframe. This app is a working prototype built on top of that. Do not let it consume the research phases. Prefer a smaller working slice over breadth.

---

## 12. Seeded demo data (synthetic only)

Provide seeders and factories for:
- 1 Super Admin, 2 Career Services Officers, 1 Researcher
- 30 students (mixed EU / third-country, varied programmes and availability)
- 10 employers (hospitality, retail, food service; mix of approved, pending, rejected)
- 25 jobs (mixed categories; some compliant, some blocked or warning)
- Sample applications, placements, reviews, and one academic year of terms/holidays
- Seed `compliance_rules` with the proposal's values (flagged as "to verify")

Demo accounts must use obviously fake data (e.g. `student01@demo.nup.ac.cy` style, **never real people**).

---

## 13. Instructions for Cursor / AI assistants

- Treat this README as the product spec. Ask before inventing features that contradict it.
- **Never hard-code legal limits, domains or sector lists.** Read them from the database.
- Put business logic in Services/Actions, not controllers or views.
- Every new route/component needs authorization (policy or permission) and a feature test.
- Use Enums for statuses and state transitions; validate transitions in one place.
- Log significant decisions through the activity log with actor and reason.
- Use factories for all test data. No real personal data anywhere.
- Prefer small, reviewable changes following the milestone order in section 11.
- Show reasons in the UI for every automated decision (compliance verdicts, match scores).

---

## 14. Glossary

| Term | Meaning |
|---|---|
| **TAM** | Technology Acceptance Model: Perceived Usefulness (PU), Perceived Ease of Use (PEOU) |
| **PR** | Perceived Risk, added to TAM in this study |
| **DSR** | Design Science Research: build, evaluate, theorise |
| **Closed-loop** | Only institution-verified users can participate |
| **Information asymmetry** | One side knows more than the other, causing poor matching (Akerlof) |
| **Signalling** | Using a verifiable credential to reduce uncertainty (Spence) |
| **Trust transfer** | Users trust the platform because they trust the institution behind it |
| **Cold start** | The difficulty of attracting enough users to a new two-sided platform |
| **Hours ledger** | Per-student record of committed weekly hours versus the legal cap |
