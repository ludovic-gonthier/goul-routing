<?hh //partial

use Goul\Routing\UrlMatcher;
use Goul\Routing\Route;
use Goul\Routing\RouteCollection;

class UrlMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testMatchCommonRoute()
    {
        $fn = () ==> {};

        $routes = Vector {
            new Route('/user/profile', $fn)
        };

        $collection = new RouteCollection();
        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);
        $property->setValue($collection, $routes);

        $matcher = new UrlMatcher($collection);
        $this->assertInstanceOf('HH\Pair', $matcher->match('/user/profile'));
    }

    /**
     * @expectedException Goul\Exception\Http\NotFoundException
     */
    public function testMatchThrowNotFoundException()
    {
        $fn = () ==> {};

        $routes = Vector {
            new Route('/', $fn)
        };

        $collection = new RouteCollection();
        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);
        $property->setValue($collection, $routes);

        $matcher = new UrlMatcher($collection);
        $matcher->match('/user/profile');
    }

    /**
     * @expectedException Goul\Exception\Http\NotFoundException
     */
    public function testMatchThrowNotFoundExceptionOnEmptyCollection()
    {
        $routes = Vector {
        };

        $collection = new RouteCollection();
        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);
        $property->setValue($collection, $routes);

        $matcher = new UrlMatcher($collection);
        $matcher->match('/user/profile');
    }
}
