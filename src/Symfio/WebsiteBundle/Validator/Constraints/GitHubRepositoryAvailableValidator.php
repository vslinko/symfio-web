<?php

namespace Symfio\WebsiteBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfio\WebsiteBundle\Entity\Project;
use Github\Api\Repo;

class GitHubRepositoryAvailableValidator extends ConstraintValidator
{
    protected $api;

    public function __construct(Repo $api)
    {
        $this->api = $api;
    }

    public function validate($project, Constraint $constraint)
    {
        if (!$project instanceof Project) {
            throw new \RuntimeException();
        }

        if (!$project->getOwner() || !$project->getRepo()) {
            return;
        }

        try {
            $this->api->show($project->getOwner(), $project->getRepo());
        } catch (\Github\Exception\RuntimeException $e) {
            $this->context->addViolation($constraint->message, array(
                '{{ owner }}' => $project->getOwner(),
                '{{ repo }}' => $project->getRepo(),
            ));
        }
    }
}
