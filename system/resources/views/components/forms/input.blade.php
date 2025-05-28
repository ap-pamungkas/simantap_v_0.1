<div class="form-group mb-3">
    <label class="form-label " for="{{ $model }}">{{ $label ?? 'label' }}</label>
    <input accept="{{ $accept ?? '' }}" type="{{ $type ?? 'text' }}"  wire:keyup="{{ $keyup ?? '' }}"  @isset($wirelive) wire:model.live="{{ $model }}" @else wire:model="{{ $model }}" @endisset class="form-control  @error($model) is-invalid @enderror" id="{{ $model }}" wire:model="{{ $model }}" placeholder="{{ $placeholder ?? 'placeholder' }}" @isset($readonly) readonly @endisset>
    <span class="text-muted">{{ $description ?? '' }}</span>
    @error($model)
        <span class="input-text-error">{{ $message }}</span>
    @enderror
</div>
