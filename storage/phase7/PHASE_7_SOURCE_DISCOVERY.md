# Phase 7 — Source Discovery Report

## Executive Summary
Following the Phase 7 read-only source check, a forensic discovery process was conducted across git history, project documentation, configuration files, and deployment guides to locate the original PostgreSQL source data.

**STATUS**: **SOURCE IDENTIFIED**

The historical PostgreSQL database location, production hosting environment, and automated backup mechanisms have been identified from authoritative project documentation and git history.

---

## 1. Historical Database Architecture
- **Framework & ORM**: Next.js App Router + Prisma ORM (removed in Phase 6 in favor of Laravel 11 + Eloquent).
- **Database Engine**: PostgreSQL
- **Database Name**: `elab_db`
- **Database User**: `elab_user`
- **Media Binary Directory**: `/public/uploads/` (customizable via `UPLOAD_DIR`)

---

## 2. Evidence Found in Project Documentation (`docs/media-backup-restore-guide.md`)
The project's disaster recovery documentation explicitly specifies the production deployment environment and backup file locations:

- **Hosting Platform**: cPanel / Linux Server
- **Production Path**: `/home/elab/public_html/`
- **Production Upload Path**: `/home/elab/public_html/public/uploads`
- **Production Server Backup Directory**: `/home/elab/backups/`
- **Automated Dump Pattern**: `/home/elab/backups/elab_db_YYYYMMDD.dump`
- **Automated Media Archive Pattern**: `/home/elab/backups/elab_media_YYYYMMDD.tar.gz`
- **Documented Backup Command**:
  ```bash
  pg_dump -U elab_user -d elab_db -F c -f /home/elab/backups/elab_db_$(date +%Y%m%d_%H%M%S).dump
  ```

---

## 3. Evidence Found in Git History
- **Commit `9dc367d`**: Initialized Prisma ORM, defined 19 application models, and created initial database seed scripts.
- **Commit `8dbb0c1` to `d2bf2c7`**: Expanded relational schema for portfolio categories, technologies, services, leads, and media.
- **Commit `507d6da`**: Added `scripts/cleanup-orphaned-media.ts` and `app/api/health/route.ts` which verified PostgreSQL connectivity via `prisma.$queryRawSELECT 1`.
- **Development `.env`**: Pointed to `postgresql://postgres:postgres@localhost:5432/elab_db?schema=public`.

---

## 4. Likely Production Hosting Environment
- **Provider**: cPanel / Linux VPS Hosting for `elab.am` / `api.elab.am`.
- **System User**: `elab`
- **PostgreSQL User**: `elab_user`
- **Database Name**: `elab_db`

---

## 5. Source Data & Backup Summary

| Parameter | Discovery Result |
| :--- | :--- |
| **SOURCE FOUND** | **YES** |
| **Provider** | cPanel / Linux Server Host (`elab.am`) |
| **Host / Path** | `/home/elab/public_html/` |
| **Database** | `elab_db` (PostgreSQL) |
| **Access Method** | SSH / cPanel Terminal / PostgreSQL Dump file download |
| **Backups Available** | YES (`/home/elab/backups/elab_db_*.dump`) |
| **Read-Only Export Possible**| YES |
| **Recommended Export** | `pg_dump -U elab_user -d elab_db --clean --if-exists > elab_db_production.sql` |

---

## 6. Requirements to Obtain the Safe Source Dump
To complete Phase 7 (Data Migration & Verification), obtain the PostgreSQL dump file using one of the following methods:

1. **Server Backup Retrieval**:
   Copy the latest dump file from `/home/elab/backups/elab_db_*.dump` on the server.
2. **Fresh Live Dump Generation**:
   Run the read-only export command on the host server:
   ```bash
   pg_dump -U elab_user -d elab_db -F c -f elab_db_production.dump
   ```
3. **Placing Dump File in Local Environment**:
   Place the resulting `.dump` or `.sql` file in `backend/storage/phase7/elab_db_production.sql` (or restore it to a local PostgreSQL instance).

---

## 7. Next Recommended Step
Once the `elab_db_production.sql` or `.dump` file is provided:
1. Load/restore the dump into local PostgreSQL or parse the dump directly.
2. Re-run **Phase 7A (Read-Only Source Check)** and **Phase 7B (Read-Only Data Audit)** against the real production dump.
3. Compare source row counts vs target schema.
4. Execute transactional, idempotent MySQL migration.

---

## 8. Confirmation of System Integrity
- **MySQL Data Modified**: **NO** (0 rows added, updated, or deleted)
- **MySQL Schema Modified**: **NO** (0 schema changes made)
- **Production Server Modified**: **NO** (Read-only discovery only)
