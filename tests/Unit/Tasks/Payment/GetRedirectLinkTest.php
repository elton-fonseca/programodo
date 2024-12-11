<?php

use App\Enums\PaymentType;
use App\Services\Payshop\PayshopClientFactoryInterface;
use App\Tasks\Payment\GetRedirectLink;
use Tests\Doubles\PayshopClientFactoryFake;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->baseRedirectUrl = 'https://payment-url.com';

    $fakeFactory = new PayshopClientFactoryFake;

    app()->instance(PayshopClientFactoryInterface::class, $fakeFactory);

    $this->getRedirectLinkService = app(GetRedirectLink::class);

    $this->instruction = [
        'gatewayStoreApiKey' => 'test-key',
        'gatewayStoreApiSignature' => 'test-signature',
    ];
});

test('adds apm parameter for MBWAY payment type', function () {
    $result = $this->getRedirectLinkService->execute(
        $this->instruction,
        'test-token',
        PaymentType::MBWAY
    );

    expect($result)->toBe($this->baseRedirectUrl.'?apm=MBWAY');
});

test('adds apm parameter for MULTIBANCO payment type', function () {
    $result = $this->getRedirectLinkService->execute(
        $this->instruction,
        'test-token',
        PaymentType::MULTIBANCO
    );

    expect($result)->toBe($this->baseRedirectUrl.'?apm=MULTIBANCO');
});

test('returns original url for CARD payment type', function () {
    $result = $this->getRedirectLinkService->execute(
        $this->instruction,
        'test-token',
        PaymentType::CARD
    );

    expect($result)->toBe($this->baseRedirectUrl);
});

test('returns original url for PAYSHOP payment type', function () {
    $result = $this->getRedirectLinkService->execute(
        $this->instruction,
        'test-token',
        PaymentType::PAYSHOP
    );

    expect($result)->toBe($this->baseRedirectUrl);
});

afterEach(function () {
    Mockery::close();
});
