<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/26/17
 * Time: 3:17 PM
 */

namespace DataSourceBundle\Entity\Aws\Glacier\Job;

use DataSourceBundle\Collection\Aws\Glacier\Job\JobCollection;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\Glacier\Job
 */
class Builder
{
    /**
     * @param array $data
     * @param Vault $vault
     * @param JobCollection|null $jobs
     * @return JobCollection
     */
    public static function build(
        array $data,
        Vault $vault,
        JobCollection $jobs = null
    ): JobCollection {

        if (is_null($jobs)) {
            $jobs = new JobCollection();
        }

        foreach ($data as $job) {
            $jobs->add(
                new Job(
                    $job["JobId"],
                    $vault,
                    (empty($job["Action"])) ? 0 : $job["Action"],
                    (empty($job["VaultARN"])) ? 0 : $job["VaultARN"],
                    (empty($job["CreationDate"])) ? null : new DateTime($job["CreationDate"]),
                    ($job["Completed"]) ? true : false,
                    (empty($job["StatusCode"])) ? null : $job["StatusCode"],
                    (empty($job["CompletionDate"])? null : new DateTime($job["CompletionDate"]))
                )
            );
        }
        return $jobs;
    }
}
