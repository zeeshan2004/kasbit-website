@if($chatbotSettings?->is_enabled)
    <div id="kasbit-chatbot"
         class="kasbit-chatbot"
         style="--chatbot-primary: {{ $chatbotSettings->primary_color }};"
         data-bootstrap-url="{{ route('chatbot.bootstrap') }}"
         data-profile-url="{{ route('chatbot.profile') }}"
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

            <div class="kasbit-chatbot__profile" data-chatbot-profile hidden>
                <div class="kasbit-chatbot__profile-card">
                    <span class="kasbit-chatbot__profile-icon"><i class="fa-solid fa-user-graduate"></i></span>
                    <h3>Tell us about yourself</h3>
                    <p>Enter your details once so answers and follow-up support can be routed correctly.</p>
                    <form data-chatbot-profile-form>
                        <label>Student ID
                            <input type="text" name="student_id" maxlength="30" autocomplete="off" required>
                        </label>
                        <label>Full Name
                            <input type="text" name="full_name" maxlength="120" autocomplete="name" required>
                        </label>
                        <label>Department
                            <select name="department_id" required>
                                <option value="">Select department</option>
                            </select>
                        </label>
                        <div class="kasbit-chatbot__profile-error" data-chatbot-profile-error role="alert" hidden></div>
                        <button type="submit"><i class="fa-solid fa-arrow-right"></i> Continue to Chat</button>
                    </form>
                    <small>Your Student ID is kept for administrator routing and is not sent to the external AI provider.</small>
                </div>
            </div>

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
