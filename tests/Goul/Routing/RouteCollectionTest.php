<?hh //partial

use Goul\Routing\Route;
use Goul\Routing\RouteEnum\Requirements;
use Goul\Routing\RouteEnum\Options;
use Goul\Routing\RouteCollection;

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $collection = new RouteCollection();

        $route = $collection->get('/', () ==> {});

        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);

        $this->assertNotEquals(-1, $property->getValue($collection)->linearSearch($route));
    }

    public function testPost()
    {
        $collection = new RouteCollection();

        $route = $collection->post('/', () ==> {});

        $property = new ReflectionProperty(Route::class, 'requirements');
        $property->setAccessible(true);
        $this->assertEquals('POST', $property->getValue($route)[Requirements::METHOD]);

        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);

        $this->assertNotEquals(-1, $property->getValue($collection)->linearSearch($route));
    }

    public function testGroup()
    {
        $a = new RouteCollection();

        $fn = () ==> {};

        $routes = Vector {
            new Route('/', $fn),
            new Route('foo', $fn),
            new Route('bar', $fn)
        };

        $routesProperty = new ReflectionProperty(RouteCollection::class, 'routes');
        $routesProperty->setAccessible(true);
        $routesProperty->setValue($a, $routes);

        $collection = new RouteCollection();
        $collection->group('/test', $a);

        $expected = Vector {};

        $options = new ReflectionProperty(Route::class, 'options');
        $options->setAccessible(true);

        $route = new Route('/test/', $fn);
        $options->setValue($route, Map{Options::PREFIX => '/test'});
        $expected[] = $route;
        $route = new Route('/test/foo', $fn);
        $options->setValue($route, Map{Options::PREFIX => '/test'});
        $expected[] = $route;
        $route = new Route('/test/bar', $fn);
        $options->setValue($route, Map{Options::PREFIX => '/test'});
        $expected[] = $route;

        $this->assertEquals($expected, $routesProperty->getValue($collection));
    }

    public function testGroupTrimPrefix()
    {
        $a = new RouteCollection();

        $fn = () ==> {};

        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);
        $property->setValue($a, Vector {
            new Route('/', $fn)
        });

        $collection = new RouteCollection();
        $collection->group('    /test    ', $a);

        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);

        $expected = Vector {};

        $options = new ReflectionProperty(Route::class, 'options');
        $options->setAccessible(true);

        $route = new Route('/test/', $fn);
        $options->setValue($route, Map{Options::PREFIX => '/test'});
        $expected[] = $route;

        $this->assertEquals($expected, $property->getValue($collection));
    }

    public function testGroupEmptyPrefix()
    {
        $a = new RouteCollection();

        $fn = () ==> {};

        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);
        $property->setValue($a, Vector {
            new Route('/', $fn)
        });

        $collection = new RouteCollection();
        $collection->group('    ', $a);

        $this->assertEquals(Vector{}, $property->getValue($collection));
    }

    public function testGroupMultipleSlashesPrefix()
    {
        $a = new RouteCollection();

        $fn = () ==> {};

        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);
        $property->setValue($a, Vector {
            new Route('/', $fn)
        });

        $collection = new RouteCollection();
        $collection->group('////', $a);

        $this->assertEquals(Vector{}, $property->getValue($collection));
    }

    public function testRoutes()
    {
        $routes = Vector {
            new Route('/', () ==> {}),
            new Route('/foo', () ==> {}),
            new Route('/bar', () ==> {})
        };

        $collection = new RouteCollection();

        $property = new ReflectionProperty(RouteCollection::class, 'routes');
        $property->setAccessible(true);
        $property->setValue($collection, $routes);

        $this->assertEquals($routes, $collection->routes());
    }
}
