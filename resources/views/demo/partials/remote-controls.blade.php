<div class="space-y-4" x-data="{ status: 'idle' }">
    <div class="grid grid-cols-2 gap-3">
        <button type="button"
                @click="status = 'thrusters'"
                class="rounded-xl bg-gradient-to-b from-emerald-500 to-emerald-600 px-4 py-3 text-white font-medium shadow-lg shadow-emerald-900/40">
            Thrusters
        </button>
        <button type="button"
                @click="status = 'warp'"
                class="rounded-xl bg-gradient-to-b from-indigo-500 to-indigo-600 px-4 py-3 text-white font-medium shadow-lg shadow-indigo-900/40">
            Warp Drive
        </button>
        <button type="button"
                @click="status = 'shields'"
                class="rounded-xl bg-gradient-to-b from-cyan-500 to-cyan-600 px-4 py-3 text-white font-medium shadow-lg shadow-cyan-900/40">
            Shields
        </button>
        <button type="button"
                @click="status = 'weapons'"
                class="rounded-xl bg-gradient-to-b from-rose-500 to-rose-600 px-4 py-3 text-white font-medium shadow-lg shadow-rose-900/40">
            Weapons
        </button>
    </div>
    <div class="rounded-2xl bg-slate-900/80 border border-slate-800/80 px-4 py-3 text-sm text-slate-200 flex items-center gap-2">
        <span class="h-2 w-2 rounded-full" :class="{
            'bg-emerald-400 animate-pulse': status === 'idle',
            'bg-amber-400 animate-pulse': status !== 'idle'
        }"></span>
        <p x-text="status === 'idle' ? 'Standing by for commands…' : `Executing ${status} command`"></p>
    </div>
</div>
