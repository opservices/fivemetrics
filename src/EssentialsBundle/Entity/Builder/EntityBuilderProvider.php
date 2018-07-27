<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/06/17
 * Time: 15:23
 */

namespace EssentialsBundle\Entity\Builder;

use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceBuilder;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Entity\DataSource\DataSourceCollectBuilder;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use DataSourceBundle\Entity\DataSource\DataSourceParameterBuilder;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValue;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValueBuilder;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountBuilder;
use EssentialsBundle\Entity\Account\AccountConfiguration;
use EssentialsBundle\Entity\Account\AccountConfigurationBuilder;
use EssentialsBundle\KernelLoader;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BuilderProvider
 * @package EssentialsBundle\Entity\Builder
 */
class EntityBuilderProvider
{
    protected const BUILDERS = [
        Account::class => AccountBuilder::class,
        AccountConfiguration::class => AccountConfigurationBuilder::class,
        DataSourceCollect::class => DataSourceCollectBuilder::class,
        DataSource::class => DataSourceBuilder::class,
        DataSourceParameterValue::class => DataSourceParameterValueBuilder::class,
        DataSourceParameter::class => DataSourceParameterBuilder::class
    ];

    public static function factory(
        string $class,
        ValidatorInterface $validator = null
    ): EntityBuilderInterface {
        if (array_key_exists($class, self::BUILDERS)) {
            $class = self::BUILDERS[$class];
            $kernel = KernelLoader::load();

            if (is_null($validator)) {
                $validator = $kernel->getContainer()
                    ->get('validator');
            }

            return new $class($validator, $kernel->isDebug());
        }

        throw new \InvalidArgumentException(
            "An unknown entity builder has been provided."
        );
    }
}
