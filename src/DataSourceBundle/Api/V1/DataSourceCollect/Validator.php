<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/09/17
 * Time: 11:21
 */

namespace DataSourceBundle\Api\V1\DataSourceCollect;

use DataSourceBundle\Collection\Api\V1\DataSourceRequestParameterCollection;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValue;
use Doctrine\Common\Persistence\ObjectManager;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Exception\Exceptions;

class Validator
{
    /**
     * @var Validator
     */
    protected static $me;

    /**
     * @var array
     */
    protected $checksum = [];

    protected function __construct()
    {
    }

    protected static function getValidatorInstance(): Validator
    {
        if (is_null(self::$me)) {
            self::$me = new Validator();
        }

        return self::$me;
    }

    /**
     * @param DataSourceRequestParameterCollection $collect
     * @return bool
     */
    public static function validateProperties(
        DataSourceRequestParameterCollection $collect
    ): bool {
        return self::getValidatorInstance()
            ->validateCollectProperties($collect);
    }

    protected function validateCollectProperties(
        DataSourceRequestParameterCollection $collect
    ): bool {
        if (! preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\-\_\.]+$/', $collect['dataSource'])) {
            throw new \InvalidArgumentException(
                "A data source group can have only numbers, characters, spaces, dots, '-' or '_'.",
                Exceptions::VALIDATION_ERROR
            );
        }

        foreach ($collect['parameters'] as $name => $value) {
            if (! preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\-\_\.]+$/', $name)) {
                throw new \InvalidArgumentException(
                    "The parameter name can't be empty and must have only letters, numbers, dots, \"-\" and \"_\"",
                    Exceptions::VALIDATION_ERROR
                );
            }

            if (! is_scalar($value)) {
                throw new \InvalidArgumentException(
                    "The parameter value must be a string, integer, float or a boolean.",
                    Exceptions::VALIDATION_ERROR
                );
            }
        }

        return true;
    }

    public static function collectExists(
        Account $account,
        ObjectManager $em,
        array $collect
    ): bool {
        $md5 = self::getValidatorInstance()
            ->findCollectMd5($account, $em, $collect);

        return (! is_null($md5));
    }

    protected function findCollectMd5(
        Account $account,
        ObjectManager $em,
        array $collect
    ) {
        if (! empty(self::$me->checksum[$account->getUid()])) {
            return (isset(self::$me->checksum[$account->getUid()][$collect]));
        }

        $collects = $em->getRepository(DataSourceCollect::class)
            ->findBy(['account' => $account]);

        foreach ($collects as $collect) {
            /** @var DataSourceCollect $collect */
            $parameters = $collect->getParameterValues()
                ->map(function (DataSourceParameterValue $el) {
                    return [
                        'name' => $el->getParameter()->getName(),
                        'value' => $el->getValue(),
                    ];
                })->toArray();

            $md5 = md5(serialize([
                'dataSource' => $collect->getDataSource()->getName(),
                'parameters' => $parameters,
            ]));

            self::$me->checksum[$account->getUid()][$md5] = true;
        }
    }
}
