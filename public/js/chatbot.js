(() => {
    const root = document.getElementById('kasbit-chatbot');
    if (!root) return;

    const panel = root.querySelector('#kasbit-chatbot-panel');
    const toggle = root.querySelector('[data-chatbot-toggle]');
    const close = root.querySelector('[data-chatbot-close]');
    const clear = root.querySelector('[data-chatbot-clear]');
    const chatView = root.querySelector('[data-chatbot-chat]');
    const profileView = root.querySelector('[data-chatbot-profile]');
    const loginView = root.querySelector('[data-chatbot-login-view]');
    const profileForm = root.querySelector('[data-chatbot-profile-form]');
    const loginForm = root.querySelector('[data-chatbot-login-form]');
    const loginError = root.querySelector('[data-chatbot-login-error]');
    const showLoginBtn = root.querySelector('[data-chatbot-show-login]');
    const guestContinueBtn = root.querySelector('[data-chatbot-guest-continue]');
    const backToChoiceBtn = root.querySelector('[data-chatbot-back-to-choice]');
    const profileSummary = root.querySelector('[data-chatbot-profile-summary]');
    const profileEdit = root.querySelector('[data-chatbot-profile-edit]');
    const form = root.querySelector('[data-chatbot-form]');
    const input = root.querySelector('[data-chatbot-input]');
    const sendButton = root.querySelector('[data-chatbot-send]');
    const messages = root.querySelector('[data-chatbot-messages]');
    const suggestions = root.querySelector('[data-chatbot-suggestions]');
    const errorBox = root.querySelector('[data-chatbot-error]');
    const privacy = root.querySelector('[data-chatbot-privacy]');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const defaultError = root.dataset.defaultError || 'Sorry, something went wrong. Please try again.';
    let initialized = false;
    let loading = false;
    let profile = null;
    let chatRendered = false;
    let welcomeMessage = '';
    let welcomeSuggestions = [];
    let savedHistory = [];

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                ...(options.headers || {}),
            },
            ...options,
        });
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const validationMessage = data.errors
                ? Object.values(data.errors).flat()[0]
                : null;
            const failure = new Error(validationMessage || data.message || defaultError);
            failure.status = response.status;
            throw failure;
        }

        return data;
    };

    const scrollToBottom = () => {
        window.requestAnimationFrame(() => {
            messages.scrollTop = messages.scrollHeight;
        });
    };

    const safeHttpUrl = (value) => {
        try {
            const url = new URL(String(value).trim());
            return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
        } catch {
            return null;
        }
    };

    const appendInlineMarkdown = (parent, value) => {
        const text = String(value ?? '');
        const pattern = /\[([^\]\n]+)]\((https?:\/\/[^\s)]+)\)|\*\*([^*\n]+)\*\*|https?:\/\/[^\s<]+/gi;
        let cursor = 0;

        for (const match of text.matchAll(pattern)) {
            if (match.index > cursor) {
                parent.appendChild(document.createTextNode(text.slice(cursor, match.index)));
            }

            if (match[1] !== undefined) {
                const href = safeHttpUrl(match[2]);

                if (href) {
                    const link = document.createElement('a');
                    link.href = href;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    link.textContent = match[1];
                    parent.appendChild(link);
                } else {
                    parent.appendChild(document.createTextNode(match[0]));
                }
            } else if (match[3] !== undefined) {
                const strong = document.createElement('strong');
                strong.textContent = match[3];
                parent.appendChild(strong);
            } else {
                const rawUrl = match[0];
                const cleanUrl = rawUrl.replace(/[.,!?;:]+$/u, '');
                const suffix = rawUrl.slice(cleanUrl.length);
                const href = safeHttpUrl(cleanUrl);

                if (href) {
                    const link = document.createElement('a');
                    link.href = href;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    link.textContent = cleanUrl;
                    parent.appendChild(link);
                    if (suffix) parent.appendChild(document.createTextNode(suffix));
                } else {
                    parent.appendChild(document.createTextNode(rawUrl));
                }
            }

            cursor = match.index + match[0].length;
        }

        if (cursor < text.length) {
            parent.appendChild(document.createTextNode(text.slice(cursor)));
        }
    };

    const renderAssistantMarkdown = (bubble, content) => {
        const lines = String(content ?? '').replaceAll('\r\n', '\n').replaceAll('\r', '\n').split('\n');
        let index = 0;

        while (index < lines.length) {
            if (!lines[index].trim()) {
                index += 1;
                continue;
            }

            const bullet = lines[index].match(/^\s*[-*]\s+(.+)$/);
            const numbered = lines[index].match(/^\s*\d+[.)]\s+(.+)$/);

            if (bullet || numbered) {
                const list = document.createElement(bullet ? 'ul' : 'ol');
                const itemPattern = bullet ? /^\s*[-*]\s+(.+)$/ : /^\s*\d+[.)]\s+(.+)$/;

                while (index < lines.length) {
                    const item = lines[index].match(itemPattern);
                    if (!item) break;

                    const listItem = document.createElement('li');
                    appendInlineMarkdown(listItem, item[1]);
                    list.appendChild(listItem);
                    index += 1;
                }

                bubble.appendChild(list);
                continue;
            }

            const paragraph = document.createElement('p');

            while (index < lines.length && lines[index].trim()) {
                if (paragraph.childNodes.length) paragraph.appendChild(document.createElement('br'));
                appendInlineMarkdown(paragraph, lines[index]);
                index += 1;
            }

            bubble.appendChild(paragraph);
        }
    };

    const addMessage = (role, content, source = '') => {
        const wrapper = document.createElement('div');
        wrapper.className = `kasbit-chatbot__message kasbit-chatbot__message--${role === 'user' ? 'user' : 'assistant'}`;

        const bubble = document.createElement('div');
        bubble.className = 'kasbit-chatbot__bubble';
        if (role === 'user') {
            bubble.textContent = content;
        } else {
            renderAssistantMarkdown(bubble, content);
        }
        wrapper.appendChild(bubble);

        if (role !== 'user' && source && !['security', 'unanswered'].includes(source)) {
            const sourceLabel = document.createElement('span');
            sourceLabel.className = 'kasbit-chatbot__source';
            const sourceNames = {
                openai: 'OpenAI',
                openrouter: 'OpenRouter',
                claude: 'Claude',
                gemini: 'Gemini',
                custom_api: 'Custom API',
                knowledge_base: 'Knowledge Base',
                admin_answer: 'Admin Answer',
                website_data: 'Website',
            };
            sourceLabel.textContent = sourceNames[source] || source.replaceAll('_', ' ');
            wrapper.appendChild(sourceLabel);
        }

        messages.appendChild(wrapper);
        scrollToBottom();
        return wrapper;
    };

    const addTyping = () => {
        const wrapper = addMessage('assistant', '');
        wrapper.dataset.chatbotTyping = 'true';
        wrapper.querySelector('.kasbit-chatbot__bubble').innerHTML =
            '<span class="kasbit-chatbot__typing" aria-label="Assistant is typing"><i></i><i></i><i></i></span>';
        return wrapper;
    };

    const showSuggestions = (items = []) => {
        suggestions.replaceChildren();
        items.filter(Boolean).slice(0, 5).forEach((question) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = question;
            button.title = question;
            button.addEventListener('click', () => submitQuestion(question));
            suggestions.appendChild(button);
        });
        suggestions.hidden = suggestions.childElementCount === 0;
    };

    const showError = (message) => {
        errorBox.textContent = message;
        errorBox.hidden = false;
    };

    const setLoading = (state) => {
        loading = state;
        input.disabled = state;
        sendButton.disabled = state;
    };

    const populateDepartments = (departments = []) => {
        const select = profileForm.elements.department_id;
        const selected = String(profile?.department_id || select.value || '');
        select.replaceChildren(new Option('Select department', ''));

        departments.forEach((department) => {
            select.add(new Option(department.name, department.id));
        });
        select.value = selected;
    };

    const fillProfileForm = () => {
        // No longer needed - guest flow doesn't use profile form fields
    };

    const renderProfileSummary = () => {
        profileSummary.querySelector('span').textContent = profile
            ? `${profile.full_name} · ID: ${profile.student_id} · ${profile.department_name}`
            : '';
    };

    const renderChat = () => {
        if (chatRendered) return;
        chatRendered = true;

        if (savedHistory.length) {
            savedHistory.forEach((item) => addMessage(item.role, item.content, item.source));
        } else if (welcomeMessage) {
            addMessage('assistant', welcomeMessage);
        }
        showSuggestions(welcomeSuggestions);
    };

    const showProfile = () => {
        chatView.hidden = true;
        loginView.hidden = true;
        profileView.hidden = false;
        clear.hidden = true;
    };

    const showLoginView = () => {
        profileView.hidden = true;
        chatView.hidden = true;
        loginView.hidden = false;
        clear.hidden = true;
        window.requestAnimationFrame(() => loginForm.elements.email.focus());
    };

    const showChat = () => {
        profileView.hidden = true;
        loginView.hidden = true;
        chatView.hidden = false;
        clear.hidden = false;
        renderProfileSummary();
        renderChat();
        window.requestAnimationFrame(() => input.focus());
    };

    const loadChat = async () => {
        if (initialized) return;
        initialized = true;
        setLoading(true);

        try {
            const data = await request(root.dataset.bootstrapUrl);
            profile = data.profile || null;
            welcomeMessage = data.settings?.welcome_message || '';
            welcomeSuggestions = data.suggestions || [];
            savedHistory = Array.isArray(data.history) ? data.history : [];
            input.placeholder = data.settings?.placeholder || input.placeholder;
            input.maxLength = data.settings?.max_message_length || input.maxLength;
            populateDepartments(data.departments || []);

            if (data.settings?.privacy_message) {
                privacy.textContent = data.settings.privacy_message;
                privacy.hidden = false;
            }

            profile ? showChat() : showProfile();
        } catch (error) {
            if (error.status === 404) {
                root.remove();
                return;
            }
            showError(error.message || defaultError);
            initialized = false;
        } finally {
            setLoading(false);
        }
    };

    const openPanel = async () => {
        panel.hidden = false;
        panel.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-expanded', 'true');
        await loadChat();
    };

    const closePanel = () => {
        panel.hidden = true;
        panel.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.focus();
    };

    const submitQuestion = async (value) => {
        if (!profile) {
            showProfile();
            return;
        }

        const question = String(value ?? input.value).trim();
        if (!question || loading) return;

        errorBox.hidden = true;
        showSuggestions([]);
        addMessage('user', question);
        input.value = '';
        input.style.height = '';
        setLoading(true);
        const typing = addTyping();

        try {
            const data = await request(root.dataset.messageUrl, {
                method: 'POST',
                body: JSON.stringify({ message: question }),
            });
            typing.remove();
            addMessage('assistant', data.answer, data.source);
            showSuggestions(data.related_questions || []);
        } catch (error) {
            typing.remove();
            showError(error.message || defaultError);
            addMessage('assistant', defaultError);
        } finally {
            setLoading(false);
            input.focus();
        }
    };

    // Guest continue — skip profile form, go directly to chat as guest
    guestContinueBtn.addEventListener('click', async () => {
        const button = guestContinueBtn;
        button.disabled = true;

        try {
            const data = await request(root.dataset.guestUrl, {
                method: 'POST',
                body: JSON.stringify({}),
            });
            profile = data.profile;
            showChat();
        } catch (error) {
            // If guest save fails, just set local profile and continue
            profile = { student_id: 'GUEST', full_name: 'Guest User', department_id: 0, department_name: 'General' };
            showChat();
        } finally {
            button.disabled = false;
        }
    });

    // Show login form
    showLoginBtn.addEventListener('click', showLoginView);

    // Back to choice screen
    backToChoiceBtn.addEventListener('click', showProfile);

    // Login form submit
    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!loginForm.reportValidity()) return;

        loginError.hidden = true;
        const button = loginForm.querySelector('button[type="submit"]');
        button.disabled = true;

        try {
            const data = await request(root.dataset.loginUrl, {
                method: 'POST',
                body: JSON.stringify({
                    email: loginForm.elements.email.value.trim(),
                    password: loginForm.elements.password.value,
                }),
            });
            profile = data.profile;
            showChat();
        } catch (error) {
            loginError.textContent = error.message || defaultError;
            loginError.hidden = false;
        } finally {
            button.disabled = false;
        }
    });

    toggle.addEventListener('click', () => panel.hidden ? openPanel() : closePanel());
    close.addEventListener('click', closePanel);
    profileEdit.addEventListener('click', showProfile);
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        submitQuestion();
    });
    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            submitQuestion();
        }
    });
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = `${Math.min(input.scrollHeight, 100)}px`;
    });
    clear.addEventListener('click', async () => {
        if (!window.confirm('Clear this chatbot conversation?')) return;
        errorBox.hidden = true;
        try {
            await request(root.dataset.clearUrl, { method: 'DELETE' });
            messages.replaceChildren();
            chatRendered = true;
            if (welcomeMessage) addMessage('assistant', welcomeMessage);
            showSuggestions(welcomeSuggestions);
        } catch (error) {
            showError(error.message || defaultError);
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !panel.hidden) closePanel();
    });
})();
