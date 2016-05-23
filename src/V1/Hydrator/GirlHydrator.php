<?php
namespace Strapieno\NightClubGirl\Api\V1\Hydrator;

use Matryoshka\Model\Hydrator\Strategy\HasOneStrategy;
use Matryoshka\Model\Hydrator\Strategy\SetTypeStrategy;
use Strapieno\NightClub\Model\Entity\Object\AggregateRatingObject;
use Strapieno\NightClubReview\Model\Entity\Object\NightClubReferenceObject;
use Strapieno\Utils\Hydrator\DateHystoryHydrator;
use Strapieno\Utils\Hydrator\Strategy\NamingStrategy\MapUnderscoreNamingStrategy;
use Strapieno\Utils\Hydrator\Strategy\ReferenceEntityCompressStrategy;

/**
 * Class GirlHydrator
 */
class GirlHydrator extends DateHystoryHydrator
{
    public function __construct($underscoreSeparatedKeys = true)
    {
        parent::__construct($underscoreSeparatedKeys);
        // Naming strategy
        $this->setNamingStrategy(new MapUnderscoreNamingStrategy(['nightclub_id' => 'nightClubReference']));
        // Property strategy
        $aggregateRating = new AggregateRatingObject();
        $aggregateRating->getHydrator()->addStrategy('partial', new SetTypeStrategy('array', 'array'));
        $this->addStrategy(
            'aggregate_rating',
            new HasOneStrategy($aggregateRating, false)
        );

        $this->addStrategy(
            'nightclub_id',
            new ReferenceEntityCompressStrategy(new NightClubReferenceObject(), false)
        );
    }
}