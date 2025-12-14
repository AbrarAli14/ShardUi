import Alpine from 'alpinejs';
import Echo from 'laravel-echo';

window.Alpine = window.Alpine || Alpine;

export function shardClient({ sessionId, channel, initialPayload = null }) {
    if (window.Echo) {
        window.Echo.disconnect();
    }

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: window.location.hostname,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_ENCRYPTED ?? 'false') === 'true',
        enabledTransports: ['ws', 'wss'],
    });

    const resolveSubscription = () => {
        if (channel.startsWith('private-')) {
            return window.Echo.private(channel.substring('private-'.length));
        }

        if (channel.startsWith('presence-')) {
            return window.Echo.join(channel.substring('presence-'.length));
        }

        return window.Echo.channel(channel);
    };

    return {
        payload: initialPayload ?? '<div class="text-sm text-slate-400">Waiting for shard…</div>',
        errors: [],
        init() {
            resolveSubscription()
                .listen('.ShardHtmlPushed', (event) => {
                    if (event.session_id !== sessionId) {
                        return;
                    }

                    this.payload = event.html;
                    this.$nextTick(() => {
                        Alpine.initTree(this.$el);
                    });
                })
                .listen('.ShardSessionEnded', () => {
                    this.payload = '<p class="text-center text-sm text-slate-400">Host ended the session.</p>';
                })
                .error((error) => {
                    this.errors.push(error.message ?? 'Connection error');
                });

            // Initialize tree for initial payload if present
            if (initialPayload) {
                this.$nextTick(() => {
                    Alpine.initTree(this.$el);
                });
            }
        },
    };
}

window.shardClient = shardClient;

Alpine.start();
