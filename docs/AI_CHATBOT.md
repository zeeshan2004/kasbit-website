# KASBIT AI Chatbot

The chatbot is installed as a native Laravel module. It searches approved local
answers first, then gives matching website content and configured live sources
to the selected AI provider. Provider secrets never reach the browser.

## Installation

1. Copy the chatbot variables from `.env.example` into `.env`.
2. Add at least one server-side API key, for example:

   ```dotenv
   OPENAI_API_KEY=your-secret-key
   OPENAI_MODEL=gpt-5.6-sol
   ```

3. Run the migration:

   ```bash
   php artisan migrate
   ```

4. Clear cached configuration after changing a key:

   ```bash
   php artisan optimize:clear
   ```

5. Sign in as an administrator and open `/admin/ai-chatbot`.

The migration creates default settings, common knowledge categories, welcome
suggestions, and provider records for OpenAI, Claude, and Gemini. Only OpenAI is
enabled by default, and it will safely fall back to the unanswered queue until
`OPENAI_API_KEY` is configured.

## Admin workflow

- **Providers:** choose the provider type, model, endpoint, and `.env` variable
  name. Optionally add a public **Knowledge Source URL**, a JSON **Knowledge API
  URL**, and the API bearer-key environment variable. Use **Test Connection**
  before selecting the provider as default.
- **Knowledge:** add approved answers, alternative wording, keywords, and
  related questions. Approved records have the highest answer priority.
- **Unanswered:** review repeated questions, write the correct answer, add
  alternative/related wording, then promote it to the knowledge base.
- **History:** filter stored answers by date, visitor, provider, source,
  category, or answered state.
- **Suggestions:** control welcome shortcuts and optional fixed admin answers.
- **Settings:** change appearance, limits, privacy text, history behavior, AI
  fallback, guest access, and the protected system prompt.

Only one provider is treated as the default. Other provider records may remain
enabled as ready alternatives, but the default active record is selected first.

## Supported provider variables

```dotenv
OPENAI_API_KEY=
OPENAI_MODEL=gpt-5.6-sol
OPENAI_API_ENDPOINT=https://api.openai.com/v1/responses

ANTHROPIC_API_KEY=
ANTHROPIC_MODEL=claude-sonnet-4-5
ANTHROPIC_API_ENDPOINT=https://api.anthropic.com/v1/messages
ANTHROPIC_API_VERSION=2023-06-01

GEMINI_API_KEY=
GEMINI_MODEL=gemini-2.5-flash
GEMINI_API_ENDPOINT=https://generativelanguage.googleapis.com/v1beta/models

CUSTOM_AI_API_KEY=
KNOWLEDGE_API_KEY=
CHATBOT_AI_TIMEOUT=25
CHATBOT_AI_RETRIES=2
CHATBOT_SOURCE_TIMEOUT=8
CHATBOT_SOURCE_CONTEXT_LIMIT=10000
CHATBOT_SIMILARITY_THRESHOLD=72
CHATBOT_RELATED_LIMIT=5
CHATBOT_HISTORY_LIMIT=30
```

If configuration caching is used, a custom provider should use
`CUSTOM_AI_API_KEY`. The database stores only environment variable names, never
secret values. The Knowledge API receives a GET request with the visitor's text
in the `question` query parameter and an optional bearer token.

## Answer priority and safety

1. Fixed administrator suggestion answer
2. Approved knowledge base and alternative-question match
3. Default AI provider, with matching website content plus configured live
   webpage/API context
4. Approved website content directly (only when AI fallback is unavailable)
5. Unanswered review queue

Messages are length-limited, converted to plain text, rate-limited per user or
IP, checked for common prompt-injection attempts, and sent with CSRF protection.
External source requests reject local/development addresses, use short timeouts,
and do not follow redirects. Roman Urdu/Roman English questions are normalized
for matching, and the provider is instructed to answer in the visitor's own
language style. Empty or uncertain provider replies are stored for administrator
review instead of being shown as confirmed facts.

Run the automated coverage with:

```bash
php artisan test --filter=ChatbotSystemTest
```
