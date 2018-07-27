<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/24/17
 * Time: 1:38 PM
 */

namespace DataSourceBundle\Aws\Glacier;

use Aws\Glacier\Exception\GlacierException;
use Aws\Glacier\GlacierClient;
use DataSourceBundle\Aws\ClientAbstract;
use DataSourceBundle\Collection\Aws\Glacier\Job\JobCollection;
use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Entity\Aws\Glacier\Job\Builder as JobVaultBuilder;
use DataSourceBundle\Entity\Aws\Glacier\Tag\Builder as TagBuilder;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Builder;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;

/**
 * Class Glacier
 * @package DataSourceBundle\Aws\Glacier
 */
class Glacier extends ClientAbstract
{

    /**
     * @const string
     */
    const GLACIER_CLIENT_VERSION = '2012-06-01';

    /**
     * @var int
     */
    protected $limit = 100;

    /**
     * @var GlacierClient
     */
    protected $glacierCli;

    /**
     * Glacier constructor.
     * @param string $key
     * @param string $secret
     * @param RegionInterface $region
     */
    public function __construct($key, $secret, RegionInterface $region)
    {
        parent::__construct($key, $secret, $region);

        $this->glacierCli = new GlacierClient([
            "region" => $region->getCode(),
            "version" => self::GLACIER_CLIENT_VERSION,
            "credentials" => $this->getCredential()
        ]);
    }

    /**
     * @param VaultCollection|null $vaultsCollection
     * @return VaultCollection
     */
    public function retrieveVaults(VaultCollection $vaultsCollection = null): VaultCollection
    {
        $marker = null;
        do {
            $result = $this->glacierCli->listVaults(['limit' => 2, 'marker' => $marker]);
            $vaultsCollection = (is_null($vaultsCollection))
                ? Builder::build($result->search('* | [0]'))
                : Builder::build($result->search('* | [0]'), $vaultsCollection);
            $marker = $result->search('Marker') ?: null;
        } while (!is_null($marker));

        foreach ($vaultsCollection as $vault) {
            $this->updateVaultTag($vault);
        }

        return $vaultsCollection;
    }

    /**
     * @param Vault $vault
     * @param JobCollection|null $collection
     * @return JobCollection
     */
    public function retrieveJobs(Vault $vault, JobCollection $collection = null) : JobCollection
    {
        $jobs = $this->glacierCli->listJobs(
            [
                'vaultName' => $vault->getVaultName()
            ]
        )->search('* | [0]');
        return (is_null($collection))
            ? JobVaultBuilder::build($jobs, $vault)
            : JobVaultBuilder::build($jobs, $vault, $collection);
    }

    /**
     * @param Vault $vault
     * @return Glacier
     */
    public function updateVaultTag(Vault $vault): Glacier
    {
        try {
            $tags = $this->glacierCli->listTagsForVault([
                "vaultName" => $vault->getVaultName()
            ])->search("Tags");
        } catch (GlacierException $exception) {
            $tags = [];
        }

        $vault->setTags(TagBuilder::build($tags));
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function checkCredential(): bool
    {
        $this->retrieveVaults();
        return true;
    }
}
