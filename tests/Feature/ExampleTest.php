<?php

use Illuminate\Foundation\Application;

test('the application container resolves the app binding', function () {
    expect(app())->toBeInstanceOf(Application::class)
        ->and(app()->version())->toStartWith('11.');
});
