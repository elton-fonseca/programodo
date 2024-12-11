<div class="info-content">
    <p>
        <span>Valor:</span>
        <span>{{ number_format($value, 2, ',', '.') }} €</span>
    </p>
    @foreach($details as $detail)
        @if ($detail['showToUser'])
            <p>
                <span><strong>{{ $detail['labelToUser'] }}</strong>: </span>
                <span>{{ $detail['value'] }}</span>
            </p>
        @endif
    @endforeach
</div>
<p>
    <span>Data limite de Pagamento:</span>
    <span><i>{{ date_format(date_create($expiresAt),"d/m/Y") }}</i></span>
</p>