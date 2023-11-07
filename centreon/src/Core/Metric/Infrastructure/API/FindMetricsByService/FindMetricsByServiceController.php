<?php

/*
 * Copyright 2005 - 2023 Centreon (https://www.centreon.com/)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * For more information : contact@centreon.com
 *
 */

declare(strict_types=1);

namespace Core\Metric\Infrastructure\API\FindMetricsByService;

use Centreon\Application\Controller\AbstractController;
use Core\Metric\Application\UseCase\FindMetricsByService\{FindMetricsByService};
use Symfony\Component\HttpFoundation\Response;

final class FindMetricsByServiceController extends AbstractController
{
    /**
     * @param int $hostId
     * @param int $serviceId
     * @param FindMetricsByService $useCase
     * @param FindMetricsByServicePresenter $presenter
     */
    public function __invoke(
        int $hostId,
        int $serviceId,
        FindMetricsByService $useCase,
        FindMetricsByServicePresenter $presenter
    ): Response {
        $this->denyAccessUnlessGrantedForApiRealtime();

        $useCase($hostId, $serviceId, $presenter);

        return $presenter->show();
    }
}