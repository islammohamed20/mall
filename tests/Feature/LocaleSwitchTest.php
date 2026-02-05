<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    public function test_it_switches_locale_via_route_and_applies_on_next_request(): void
    {
        $this->withHeader('Referer', '/login')
            ->get('/lang/en')
            ->assertRedirect('/login');

        $this->assertSame('en', session('locale'));

        $this->get('/login')->assertOk();

        $this->assertSame('en', app()->getLocale());
    }
}
