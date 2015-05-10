<?hh //strict
namespace Goul\Routing;

type MatchingData = Pair<mixed, array<mixed>>;

interface MatcherInterface
{
    /**
     * Return the MatchingData of the Route matching the given Url
     *
     * @param  string $url      The Url to match
     * @return MatchingData     A MatchingData type containing the callback
     *                          and the arguments for the url call
     */
    public function match(string $url): MatchingData;
}
