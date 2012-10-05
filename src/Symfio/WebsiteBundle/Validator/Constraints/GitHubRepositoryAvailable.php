<?php

namespace Symfio\WebsiteBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GitHubRepositoryAvailable extends Constraint
{
    public $message = "Repository {{ owner }}/{{ repo }} not found on GitHub";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'github_repository_available';
    }
}
