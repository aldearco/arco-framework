<?php 

namespace Arco\Tests\Routing;

use Arco\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase {
    public function routesWithNoParameters() {
        return [
            ["/"],
            ["/test"],
            ["/test/nested"],
            ["/test/another/nested"],
            ["/test/another/nested/route"],
            ["/test/another/nested/very/nested/route"],
        ];
    }

    /**
     * @dataProvider routesWithNoParameters
     */
    public function test_regex_with_no_parameters(string $uri) {
        $route = new Route($uri, fn () => "test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("$uri/extra/path"));
        $this->assertFalse($route->matches("/some/path/$uri"));
        $this->assertFalse($route->matches("/random/route"));
    }

    /**
     * @dataProvider routesWithNoParameters
     */
    public function test_regex_on_uri_that_ends_with_slash(string $uri) {
        $route = new Route($uri, fn () => "test");
        $this->assertTrue($route->matches("$uri/"));
    }


    public function routesWithParameters() {
        return [
            [
                "/test/{test}", 
                "/test/1", 
                ["test" => 1]
            ],
            [
                "/users/{user}", 
                "/users/2", 
                ["user" => 2]
            ],
            [
                "/test/{test}", 
                "/test/string", 
                ["test" => "string"]
            ],
            [
                "/test/nested/{test}", 
                "/test/nested/5", 
                ["test" => 5]
            ],
            [
                "/test/{param}/long/{test}/with/{multiple}/params", 
                "/test/yellow/long/5/with/12345/params",
                ["param" => "yellow", "test" => 5, "multiple" => 12345]
            ],
        ];
    }

    /**
     * @dataProvider routesWithParameters
     */
    public function test_regex_with_parameters(string $definition, string $uri) {
        $route = new Route($definition, fn () => "test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("$uri/extra/path"));
        $this->assertFalse($route->matches("/some/path/$uri"));
        $this->assertFalse($route->matches("/random/route"));
    }

    /**
     * @dataProvider routesWithParameters
     */
    public function test_parse_parameters(string $definition, string $uri, array $expectedParameters) {
        $route = new Route($definition, fn () => "test");
        $this->assertTrue($route->hasParameters());
        $this->assertEquals($expectedParameters, $route->parseParameters($uri));
    }
}