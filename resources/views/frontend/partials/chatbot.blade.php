@if($chatbotSettings?->is_enabled)
    <div id="kasbit-chatbot"
         class="kasbit-chatbot"
         style="--chatbot-primary: {{ $chatbotSettings->primary_color }};"
         data-bootstrap-url="{{ route('chatbot.bootstrap') }}"
         data-profile-url="{{ route('chatbot.profile') }}"
         data-login-url="{{ route('chatbot.login') }}"
         data-guest-url="{{ route('chatbot.guest') }}"
         data-message-url="{{ route('chatbot.message') }}"
         data-clear-url="{{ route('chatbot.clear') }}"
         data-default-error="{{ $chatbotSettings->default_error_message }}"
         data-max-length="{{ $chatbotSettings->max_message_length }}">
        <button type="button"
                class="kasbit-chatbot__launcher"
                data-chatbot-toggle
                aria-label="Open {{ $chatbotSettings->chatbot_name }}"
                aria-expanded="false"
                aria-controls="kasbit-chatbot-panel">
            <i class="{{ $chatbotSettings->chatbot_icon }}" aria-hidden="true"></i>
            <span class="kasbit-chatbot__launcher-label">Ask KASBIT</span>
            <span class="kasbit-chatbot__unread" data-chatbot-unread hidden>1</span>
        </button>

        <section id="kasbit-chatbot-panel"
                 class="kasbit-chatbot__panel"
                 role="dialog"
                 aria-modal="false"
                 aria-label="{{ $chatbotSettings->header_title }}"
                 aria-hidden="true"
                 hidden>
            <header class="kasbit-chatbot__header">
                <span class="kasbit-chatbot__avatar"><i class="{{ $chatbotSettings->chatbot_icon }}"></i></span>
                <span>
                    <strong>{{ $chatbotSettings->header_title }}</strong>
                    <small><i></i> Online assistant</small>
                </span>
                <button type="button" data-chatbot-clear title="Clear chat" aria-label="Clear chat">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <button type="button" data-chatbot-close title="Close chatbot" aria-label="Close chatbot">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </header>

            {{-- Step 1: Choice Screen (Login ya Guest) --}}
            <div class="kasbit-chatbot__profile" data-chatbot-profile hidden>
                <div class="kasbit-chatbot__profile-card">
                    <span class="kasbit-chatbot__profile-icon"><i class="fa-solid fa-user-graduate"></i></span>
                    <h3>KASBIT Assistant</h3>
                    <p>Choose how you'd like to use the chatbot.</p>
                    <button type="button" class="kasbit-chatbot__choice-btn kasbit-chatbot__choice-btn--login" data-chatbot-show-login>
                        <i class="fa-solid fa-sign-in-alt"></i> Login
                    </button>
                    <button type="button" class="kasbit-chatbot__choice-btn kasbit-chatbot__choice-btn--guest" data-chatbot-guest-continue>
                        <i class="fa-solid fa-arrow-right"></i> Continue to Chat
                    </button>
                    <small>Students with an account can login for a personalized experience.</small>
                </div>
            </div>

            {{-- Step 2: Login Form (hidden by default) --}}
            <div class="kasbit-chatbot__profile" data-chatbot-login-view hidden>
                <div class="kasbit-chatbot__profile-card">
                    <span class="kasbit-chatbot__profile-icon"><i class="fa-solid fa-sign-in-alt"></i></span>
                    <h3>Student Login</h3>
                    <p>Enter your KASBIT email and password.</p>
                    <form data-chatbot-login-form>
                        <label>Email
                            <input type="email" name="email" maxlength="255" autocomplete="email" required>
                        </label>
                        <label>Password
                            <input type="password" name="password" maxlength="255" autocomplete="current-password" required>
                        </label>
                        <div class="kasbit-chatbot__profile-error" data-chatbot-login-error role="alert" hidden></div>
                        <button type="submit"><i class="fa-solid fa-sign-in-alt"></i> Login</button>
                    </form>
                    <button type="button" class="kasbit-chatbot__back-btn" data-chatbot-back-to-choice>
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>

            {{-- Hidden guest profile form (not shown, used internally) --}}
            <form data-chatbot-profile-form style="display:none !important; position:absolute; visibility:hidden;">
                <select name="department_id"><option value="">Select department</option></select>
            </form>

            <div class="kasbit-chatbot__chat" data-chatbot-chat hidden>
                <div class="kasbit-chatbot__profile-summary" data-chatbot-profile-summary>
                    <span></span>
                    <button type="button" data-chatbot-profile-edit>Edit</button>
                </div>
            <div class="kasbit-chatbot__privacy" data-chatbot-privacy hidden></div>
            <div class="kasbit-chatbot__messages"
                 data-chatbot-messages
                 role="log"
                 aria-live="polite"
                 aria-relevant="additions"></div>
            <div class="kasbit-chatbot__suggestions" data-chatbot-suggestions hidden></div>
            <div class="kasbit-chatbot__error" data-chatbot-error role="alert" hidden></div>

            <form class="kasbit-chatbot__composer" data-chatbot-form>
                <label class="visually-hidden" for="kasbit-chatbot-input">{{ $chatbotSettings->placeholder_text }}</label>
                <textarea id="kasbit-chatbot-input"
                          data-chatbot-input
                          rows="1"
                          maxlength="{{ $chatbotSettings->max_message_length }}"
                          placeholder="{{ $chatbotSettings->placeholder_text }}"
                          autocomplete="off"></textarea>
                <button type="submit" data-chatbot-send aria-label="Send question">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
            </div>
            <footer>Powered by KASBIT · Do not share passwords or payment details</footer>
        </section>
    </div>
@endif
