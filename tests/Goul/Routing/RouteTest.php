<?hh //partial

use Goul\Routing\Route;
use Goul\Routing\RouteEnum\Arguments;
use Goul\Routing\RouteEnum\Options;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorTrimPattern()
    {
        $pattern = new \ReflectionProperty(Route::class, 'pattern');
        $pattern->setAccessible(true);

        $route = new Route('//test  ', () ==> {});

        $this->assertEquals('/test', $pattern->getValue($route));
    }

    public function testConstructorWithEmptyNatternThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $route = new Route('', () ==> {});
    }

    public function testConstructorWithNoCallablecallbackThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $route = new Route('/', '');
    }

    public function testPattern()
    {
        $route = new Route('//test  ', () ==> {});

        $this->assertEquals('/test', $route->pattern());
    }

    public function testPrefixPattern()
    {
        $pattern = new \ReflectionProperty(Route::class, 'pattern');
        $pattern->setAccessible(true);

        $route = new Route('//test  ', () ==> {});

        $route->prefixPattern('foo');

        $this->assertEquals('/foo/test', $pattern->getValue($route));
    }

    public function testPrefixPatternAddsPreficInOptions()
    {
        $options = new \ReflectionProperty(Route::class, 'options');
        $options->setAccessible(true);

        $route = new Route('//test  ', () ==> {});

        $route->prefixPattern('foo');

        $this->assertEquals('/foo', $options->getValue($route)[Options::PREFIX]);
    }

    public function testCallback()
    {
        $callback = array(Route::class, 'pattern');

        $route = new Route('//test  ', $callback);

        $this->assertEquals($callback, $route->callback());
    }

    public function testArguments()
    {
        $route = new Route('//test  ', array(Route::class, 'pattern'));

        $this->assertInstanceOf(Map::class, $route->arguments());
    }

    public function testOptions()
    {
        $route = new Route('//test  ', array(Route::class, 'pattern'));

        $this->assertInstanceOf(Map::class, $route->options());
    }

    public function testRequirements()
    {
        $route = new Route('//test  ', array(Route::class, 'pattern'));

        $this->assertInstanceOf(Map::class, $route->requirements());
    }
}
