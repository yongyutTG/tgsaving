# TGSaving CMS — local demo

Initial CodeIgniter 4.7.5 implementation. Requires PHP 8.2+, intl, mbstring, SQLite3, GD, fileinfo.
XAMPP PHP 7.4 cannot run this project.

## Run on this machine
Open PowerShell in this folder and run:
```powershell
.\start-demo.ps1
```
Frontend: http://localhost:8085/
Admin: http://localhost:8085/admin

The local database is writable/cms.sqlite. Uploaded images are under writable/uploads.
Keep BOTH when backing up. Stop the demo server before copying the database.

## Fresh setup
```powershell
composer install
C:\Users\A\.codex\runtimes\php84\php.exe spark migrate
C:\Users\A\.codex\runtimes\php84\php.exe spark db:seed DemoSeeder
C:\Users\A\.codex\runtimes\php84\php.exe spark cms:admin admin
```
The last command prints a generated password. Existing users are never overwritten.
The demo seeder skips an existing article collection. It creates 6 published demo articles and 1 draft.
All content and artwork are synthetic demonstration material.

## Available now
- Responsive Thai public homepage, category/search filters, pagination, article detail.
- Staff sign-in, session regeneration, logout, login rate limiting and CSRF.
- News create/edit/publish/unpublish, cover upload/removal, trash and restore through editor.
- Images stored outside public root and served only for published articles or authenticated staff.
- Plain-text article body is escaped; HTML is deliberately not interpreted.
- A single administrator permission level in this first iteration.

## Remaining phases
Rich text editor and preview, role-based permissions, categories editor, shared media library,
PDF/downloads, journals, calendar, banners, editable pages/menus/rates, audit history,
Joomla import and URL redirects. The purple/gold design is provisional; original site fidelity
has not yet been verified.

## MySQL / deployment
Use a new empty MySQL database with utf8mb4. Enable mysqli in PHP.
Change database.default.DBDriver to MySQLi and set hostname/database/username/password in .env.
Run migrations to create schema. This does NOT transfer SQLite records; export/import content
and preserve image files as a separate migration step.
Set app.baseURL for the actual hostname and CI_ENVIRONMENT=production, enable HTTPS,
and configure the server document root to public/ only. Never serve the project root.
Do not expose this development server publicly.

## Expanded menu demo
Run `php spark migrate` followed by `php spark db:seed MenuDemoSeeder` after the initial demo seed.
MenuDemoSeeder adds 50 sample records in demo_content (10 sections, 5 each), plus 9 published
news items to bring the initial news categories to 5 each. Running it again skips existing keys/titles.
The public homepage shows the latest 5 published items per category. All menu headings now link
to lists and detail pages; publicity links to the existing news filter.
New menu content is read-only demo data, not yet editable through the news admin.
Documents, reports and videos contain sample descriptions only; no downloadable or playable files.
Financial rates, contact details and property prices are deliberately unspecified demo fields.
Validation: `node tests/menu-demo-smoke.cjs` checks every navigation link and all 50 detail pages.

## Announcement PDF reading
Announcements now show an embedded PDF reader instead of a cover image in the article detail.
Use the Open PDF or Download PDF buttons on mobile/browser viewers without inline support.
Admin accepts PDF uploads up to 2 MB. Publishing an announcement requires an attached PDF.
PDF files are stored in writable/uploads, served as application/pdf with inline/download disposition,
and inaccessible to anonymous users when the parent article is draft or trashed. Staff can preview drafts.
The initial five announcements have generated one-page Thai demo PDFs attached.
Run `node tests/pdf-smoke.cjs` with CMS_TEST_PASSWORD set to test upload/publication/privacy;
the test creates a disposable [TEST] PDF lifecycle article for cleanup after running.
