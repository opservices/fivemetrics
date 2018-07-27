<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/6/17
 * Time: 9:41 AM
 */

namespace DataSourceBundle\Entity\Aws\S3\Bucket;

use DataSourceBundle\Entity\Aws\EntityAbstract;

class Versioning extends EntityAbstract
{

    const STATUS_TYPES = [
        'Enabled',
        'Disabled',
        'Suspended'
    ];

    /**
     * @var string
     */
    protected $status;
    
    /**
     * @var string
     */
    protected $MFADelete;

    /**
     * Versioning constructor.
     * @param string $state
     * @param string|null $MFADelete
     */
    public function __construct(string $state, string $MFADelete = null)
    {
        $this->setStatus($state);
        (empty($MFADelete)) ?: $this->setMFADelete($MFADelete);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        if (! in_array($status, self::STATUS_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid monitoring status type was provided: "' . $status . '""'
            );
        }

        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getMFADelete(): string
    {
        return $this->MFADelete;
    }

    /**
     * @param string $MFADelete
     * @return Versioning
     */
    public function setMFADelete(string $MFADelete): Versioning
    {
        $this->MFADelete = $MFADelete;
        return $this;
    }
}
