@php($chartMax = max(1, (int) collect($chartData)->max()))
<div class="chatbot-bar-chart" role="img" aria-label="Chatbot statistics">
    @forelse($chartData as $label => $value)
        <div class="chatbot-bar-chart__row">
            <span title="{{ str($label ?: 'Unknown')->replace('_', ' ')->title() }}">{{ str($label ?: 'Unknown')->replace('_', ' ')->title() }}</span>
            <div><i style="width: {{ max(3, ((int) $value / $chartMax) * 100) }}%"></i></div>
            <strong>{{ $value }}</strong>
        </div>
    @empty
        <p class="chatbot-muted">No data is available yet.</p>
    @endforelse
</div>
