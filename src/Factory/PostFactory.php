<?php

namespace App\Factory;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Post>
 *
 * @method static Post|Proxy createOne(array $attributes = [])
 * @method static Post[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Post|Proxy find(object|array|mixed $criteria)
 * @method static Post|Proxy findOrCreate(array $attributes)
 * @method static Post|Proxy first(string $sortedField = 'id')
 * @method static Post|Proxy last(string $sortedField = 'id')
 * @method static Post|Proxy random(array $attributes = [])
 * @method static Post|Proxy randomOrCreate(array $attributes = [])
 * @method static Post[]|Proxy[] all()
 * @method static Post[]|Proxy[] findBy(array $attributes)
 * @method static Post[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Post[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PostRepository|RepositoryProxy repository()
 * @method Post|Proxy create(array|callable $attributes = [])
 */
final class PostFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    public function unpublishedPosts(): self
    {
        return $this->addState(['posted_at' => null]);
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => self::faker()->realText(50),
            'postBody' => self::faker()->paragraphs(4, true),
            'postedAt' => self::faker()->dateTimeBetween('-100 days', '-1 minute'),
            'postVotes' => random_int(-50, 50),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            ->afterInstantiate(function(Post $post) {
//                if (!$post->getSlug()) {
//                    $slugger = new AsciiSlugger();
//                    $post->setSlug($slugger->slug($post->getTitle()));
            }) ;
    }

    protected static function getClass(): string
    {
        return Post::class;
    }
}
