<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/24/17
 * Time: 2:09 PM
 */

namespace DataSourceBundle\Entity\Aws\Glacier\Vault;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Vault
 * @package DataSourceBundle\Entity\Aws\Glacier\Vault
 */
class Vault extends EntityAbstract
{

    /**
     * @var string
     */
    protected $vaultName;

    /**
     * @var integer
     */
    protected $numberOfArchives;

    /**
     * @var integer
     */
    protected $sizeInBytes;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * Vault constructor.
     * @param string $vaultName
     * @param int|null $numberOfArchives
     * @param int|null $sizeInBytes
     * @param TagCollection|null $tags
     */
    public function __construct(
        string $vaultName,
        int $numberOfArchives = null,
        int $sizeInBytes = null,
        TagCollection $tags = null
    ) {
        $this->vaultName = $vaultName;
        (is_null($numberOfArchives)) ?: $this->setNumberOfArchives($numberOfArchives);
        (is_null($sizeInBytes)) ?: $this->setSizeInBytes($sizeInBytes);
        (is_null($tags)) ?: $this->setTags($tags);
    }

    /**
     * @return string
     */
    public function getVaultName(): string
    {
        return $this->vaultName;
    }

    /**
     * @param string $vaultName
     * @return Vault
     */
    public function setVaultName(string $vaultName): Vault
    {
        $this->vaultName = $vaultName;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfArchives(): int
    {
        return $this->numberOfArchives;
    }

    /**
     * @param int $numberOfArchives
     * @return Vault
     */
    public function setNumberOfArchives(int $numberOfArchives): Vault
    {
        $this->numberOfArchives = $numberOfArchives;
        return $this;
    }

    /**
     * @return int
     */
    public function getSizeInBytes(): int
    {
        return $this->sizeInBytes;
    }

    /**
     * @param int $sizeInBytes
     * @return Vault
     */
    public function setSizeInBytes(int $sizeInBytes): Vault
    {
        $this->sizeInBytes = $sizeInBytes;
        return $this;
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    /**
     * @param TagCollection $tags
     * @return Vault
     */
    public function setTags(TagCollection $tags): Vault
    {
        $this->tags = $tags;
        return $this;
    }
}
