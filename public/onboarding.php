<?php

use EssentialsBundle\KernelLoader;

require_once "../bootstrap.php";

$container = KernelLoader::load()->getContainer();
$templating = $container->get('templating');
$collectors = json_decode('[
           {
               "name": "EC2",
               "total": "25"
           },
           {
               "name": "S3",
               "total": "11"
           },
           {
               "name": "EBS",
               "total": "32"
           },
           {
               "name": "GLACIER",
               "total": "4"
           },
           {
               "name": "AUTOSCALING",
               "total": "17"
           },
           {
               "name": "ELB",
               "total": "15"
           }
       ]');
print $templating->render(
    'Emails/onBoarnding.html.twig',
    [ 'total' => 95, 'collectors' => $collectors ]
);