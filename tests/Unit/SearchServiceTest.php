<?php

namespace Tests\Unit;

use App\Repositories\ProductRepositoryInterface;
use App\Services\SearchService;
use Mockery;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function makeService(): SearchService
    {
        // sanitizeTerm() is a pure function that never touches the
        // repository, so a mock that expects no calls is enough here.
        $repository = Mockery::mock(ProductRepositoryInterface::class);

        return new SearchService($repository);
    }

    public function test_it_strips_html_tags_from_the_term(): void
    {
        $result = $this->makeService()->sanitizeTerm('<script>alert(1)</script>Ролики');

        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringNotContainsString('</script>', $result);
        $this->assertStringContainsString('Ролики', $result);
    }

    public function test_it_collapses_repeated_whitespace_and_trims(): void
    {
        $result = $this->makeService()->sanitizeTerm("  velo   \n\t bike  ");

        $this->assertSame('velo bike', $result);
    }

    public function test_it_caps_the_term_length_at_100_characters(): void
    {
        $result = $this->makeService()->sanitizeTerm(str_repeat('a', 500));

        $this->assertSame(100, mb_strlen($result));
    }

    public function test_it_strips_control_characters(): void
    {
        $result = $this->makeService()->sanitizeTerm("bike\x00\x1F name");

        $this->assertSame('bike name', $result);
    }

    public function test_empty_term_stays_empty(): void
    {
        $this->assertSame('', $this->makeService()->sanitizeTerm('   '));
    }
}
