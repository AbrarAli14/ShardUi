@props(['isRemote' => false, 'target' => 'mobile', 'placeholder' => null, 'name' => null])

@if ($isRemote)
    <div {{ $attributes->merge(['data-shard-target' => $target]) }}>
        {{ $slot }}
    </div>
@else
    <div class="shard-placeholder" {{ $attributes }}>
        <p>{{ $placeholder }}</p>
        <div class="shard-placeholder__status">
            <span class="shard-placeholder__badge">{{ strtoupper($target) }}</span>
        </div>
    </div>
@endif
