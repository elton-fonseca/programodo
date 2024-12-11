<div class="payment-option" id="payment-option-{{ $name }}">
    <label>
        <input type="radio" name="payment_type" value="{{ $name }}" id="{{ $name }}" required>
        {{ $slot }}
    </label>
    <img src="{{ $icon }}" alt="{{ $name }}" class="payment-icon">

    {{ $fields ?? '' }}
</div>
