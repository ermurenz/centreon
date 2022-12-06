<?php

/*
 * Copyright 2005 - 2022 Centreon (https://www.centreon.com/)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
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

namespace Core\TimePeriod\Application\UseCase\AddTimePeriod;

use Centreon\Domain\Log\LoggerTrait;
use Core\Application\Common\UseCase\ErrorResponse;
use Core\Application\Common\UseCase\PresenterInterface;
use Core\TimePeriod\Application\Repository\WriteTimePeriodRepositoryInterface;

class AddTimePeriod
{
    use LoggerTrait;

    /**
     * @param WriteTimePeriodRepositoryInterface $writeTimePeriodRepository
     */
    public function __construct(private WriteTimePeriodRepositoryInterface $writeTimePeriodRepository)
    {
    }

    /**
     * @param AddTimePeriodRequest $request
     * @param PresenterInterface $presenter
     * @return void
     */
    public function __invoke(AddTimePeriodRequest $request, PresenterInterface $presenter): void
    {
        try {
            $newTimePeriod = NewTimePeriodFactory::create($request);
            $this->writeTimePeriodRepository->add($newTimePeriod);
        } catch (\Throwable $ex) {
            $presenter->setResponseStatus(new ErrorResponse('Error'));
        }
    }
}
