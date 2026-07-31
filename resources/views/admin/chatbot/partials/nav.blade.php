<nav class="chatbot-admin-nav" aria-label="AI chatbot sections">
    <a href="{{ route('admin.chatbot.dashboard') }}" class="{{ request()->routeIs('admin.chatbot.dashboard') ? 'is-active' : '' }}">
        <i class="fa-solid fa-gauge-high"></i> Overview
    </a>
    <a href="{{ route('admin.chatbot.providers.index') }}" class="{{ request()->routeIs('admin.chatbot.providers.*') ? 'is-active' : '' }}">
        <i class="fa-solid fa-microchip"></i> Providers
    </a>
    <a href="{{ route('admin.chatbot.knowledge.index') }}" class="{{ request()->routeIs('admin.chatbot.knowledge.*') || request()->routeIs('admin.chatbot.categories.*') ? 'is-active' : '' }}">
        <i class="fa-solid fa-book-open"></i> Knowledge
    </a>
    <a href="{{ route('admin.chatbot.unanswered.index') }}" class="{{ request()->routeIs('admin.chatbot.unanswered.*') ? 'is-active' : '' }}">
        <i class="fa-solid fa-circle-question"></i> Unanswered
    </a>
    <a href="{{ route('admin.chatbot.history.index') }}" class="{{ request()->routeIs('admin.chatbot.history.*') ? 'is-active' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i> History
    </a>
    <a href="{{ route('admin.chatbot.suggestions.index') }}" class="{{ request()->routeIs('admin.chatbot.suggestions.*') ? 'is-active' : '' }}">
        <i class="fa-solid fa-lightbulb"></i> Suggestions
    </a>
    <a href="{{ route('admin.chatbot.settings.edit') }}" class="{{ request()->routeIs('admin.chatbot.settings.*') ? 'is-active' : '' }}">
        <i class="fa-solid fa-sliders"></i> Settings
    </a>
</nav>
