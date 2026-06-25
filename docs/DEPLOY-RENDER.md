# Deploy to Render (free tier)

Docker-based deploy of this Laravel app with a free Postgres database.

## What's included
- `Dockerfile` — multi-stage build (Vite assets → Composer deps → PHP 8.3 runtime)
- `docker/start.sh` — caches config, migrates, optionally seeds, serves on `$PORT`
- `render.yaml` — web service + free Postgres, blueprint-deployable

## Steps

1. **Generate an app key** locally and copy the output:
   ```
   php artisan key:generate --show
   ```

2. **Create the Blueprint** on Render:
   - Push this repo to GitHub (already on `main`).
   - Render Dashboard → **New → Blueprint** → pick the repo.
   - Render reads `render.yaml`, creating the web service + `cishs-db`.

3. **Set the secret env vars** (marked `sync: false`) in the web service:
   - `APP_KEY` → the value from step 1.
   - `APP_URL` → your URL, e.g. `https://cishs-enrollment.onrender.com`.
   - Mail (`MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`) —
     use a free SMTP (Mailtrap sandbox, Resend, or Gmail app password).

4. **First deploy** runs migrations and seeds (`SEED_ON_DEPLOY=true`).
   After it's live, set `SEED_ON_DEPLOY=false` so later deploys don't duplicate data.

## Free-tier limits (expected, livable for a demo)
- **Sleeps after 15 min idle** → first request ~30–50s cold start.
- **Postgres expires ~90 days** → recreate the DB and redeploy with `SEED_ON_DEPLOY=true`.
- **No persistent disk** → files uploaded to `storage/app/public` are lost on redeploy.
  Fine for short demos; for permanence move uploads to S3/Cloudinary and set the
  `s3` disk in `config/filesystems.php`.

## Notes
- App default is MySQL; on Render it runs on **Postgres** (`DB_CONNECTION=pgsql`).
  The schema is portable — no MySQL-only SQL is used.
- Uploaded documents are served through an authed route (`DocumentController`),
  so no `storage:link` symlink is needed.
- Logs stream to Render via `LOG_CHANNEL=stderr`.
