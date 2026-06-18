# AGENTS.md

## Project Context

- This repository is a Laravel backend API template.
- The installed dependency set in `composer.lock` currently includes Laravel `v13.16.1`, Laravel Passport `v13.7.5`, Laravel Boost `v2.4.10`, Laravel Pint `v1.29.3`, and PHPUnit `v11.5.55`.
- Treat `composer.json` and `composer.lock` as the source of truth for versions. Re-check them before giving version-specific guidance because `GEMINI.md` may be stale.
- API routes live under `routes/api.php`, with the main pattern using `/api/v1/...`, `CheckApiToken`, and `auth:api` where required.

## Local Conventions

- Follow sibling files before adding or changing structure.
- Keep controllers thin. Existing API controllers extend `App\Http\Controllers\BaseApiController` and delegate business logic to services.
- Put reusable data operations in services. Existing services extend `App\Services\BaseService`.
- Use Form Request classes for validation rather than inline controller validation.
- Use API resources for response data where the existing endpoint pattern does so.
- Prefer Eloquent models, relationships, eager loading, and query builders over raw SQL.
- Use named routes and Laravel helpers where practical.
- Do not add new top-level directories, dependencies, or architecture patterns without user approval.

## Code Style

- Use explicit PHP return types and parameter types when adding or changing methods.
- Use constructor property promotion for dependencies when it matches the existing style.
- Always use braces for control structures.
- Prefer useful PHPDoc for array shapes or non-obvious types.
- Keep comments sparse; add them only for complex logic that benefits from explanation.
- Use ASCII unless an edited file already uses another character set for a clear reason.

## Commands

- Install PHP dependencies: `composer install`
- Install frontend dependencies: `npm install`
- Run the app/dev stack: `composer run dev`
- Run all tests: `php artisan test`
- Run one test file: `php artisan test tests/Feature/ExampleTest.php`
- Run a filtered test: `php artisan test --filter=testName`
- Format changed PHP files before finalizing PHP edits: `vendor/bin/pint --dirty`
- Build frontend assets when needed: `npm run build`

## Laravel Boost

- This project includes Laravel Boost. When Boost MCP tools are available, use them for Laravel-specific work.
- Before changing Laravel ecosystem code that depends on framework behavior, use Boost `search-docs` for version-specific documentation.
- Use Boost database or tinker tools when available for targeted debugging instead of creating temporary scripts.

## Testing And Verification

- Run the smallest relevant test set for the change.
- If PHP files are changed, run `vendor/bin/pint --dirty` before final response.
- If tests cannot be run because of environment or dependency issues, report the exact blocker.
- Do not remove tests or test files without explicit approval.

## Codex Skills

### skill-creator

- Use `/home/filip/.codex/skills/.system/skill-creator/SKILL.md` when creating or updating Codex skills.
- Read the full `SKILL.md` before acting.
- Keep skill instructions concise and focused on non-obvious procedural knowledge.
- For new skills, use the skill initializer script unless the user is asking only for planning or documentation.
- Validate completed skill folders with the skill validation script.

### skill-installer

- Use `/home/filip/.codex/skills/.system/skill-installer/SKILL.md` when the user asks to list, install, or update Codex skills from curated sources or GitHub.
- Read the full `SKILL.md` before acting.
- The installer scripts require network access, so request escalation when running them in a restricted sandbox.
- After installing skills, tell the user to restart Codex to pick up new skills.
- The `.system` skills are already preinstalled; do not reinstall them unless the user explicitly insists.

## Git And Workspace Safety

- The working tree may contain user edits. Inspect `git status --short` before editing.
- Do not revert or overwrite changes you did not make.
- Avoid destructive commands such as `git reset --hard` or broad deletes unless the user explicitly requests them.
- Keep edits scoped to the user request.
