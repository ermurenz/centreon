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

namespace Tests\Core\HostCategory\Application\UseCase\FindHostCategories;

use Centreon\Domain\Contact\Contact;
use Centreon\Domain\Contact\Interfaces\ContactInterface;
use Core\Application\Common\UseCase\ErrorResponse;
use Core\Application\Common\UseCase\ForbiddenResponse;
use Core\HostCategory\Application\Repository\ReadHostCategoryRepositoryInterface;
use Core\HostCategory\Application\UseCase\FindHostCategories\FindHostCategories;
use Core\HostCategory\Application\UseCase\FindHostCategories\FindHostCategoriesResponse;
use Core\HostCategory\Domain\Model\HostCategory;
use Core\Infrastructure\Common\Presenter\PresenterFormatterInterface;
use Core\Security\AccessGroup\Application\Repository\ReadAccessGroupRepositoryInterface;

beforeEach(function () {
    $this->hostCategoryRepository = $this->createMock(ReadHostCategoryRepositoryInterface::class);    $this->accessGroupRepository = $this->createMock(ReadAccessGroupRepositoryInterface::class);
    $this->presenterFormatter = $this->createMock(PresenterFormatterInterface::class);
    $this->user = $this->createMock(ContactInterface::class);
    $this->hostCategoryName = 'hc-name';
    $this->hostCategoryAlias = 'hc-alias';
    $this->hostCategory = new HostCategory(1, $this->hostCategoryName, $this->hostCategoryAlias);
    $this->responseArray = [
        'id' => 1,
        'name' => $this->hostCategoryName,
        'alias' => $this->hostCategoryAlias
    ];
});

it('should present an ErrorResponse when an exception is thrown', function () {
    $useCase = new FindHostCategories(
        $this->hostCategoryRepository,
        $this->accessGroupRepository,
        $this->user
    );
    $presenter = new FindHostCategoriesPresenterStub($this->presenterFormatter);

    $this->user
        ->expects($this->once())
        ->method('isAdmin')
        ->willReturn(true);
    $this->hostCategoryRepository
        ->expects($this->once())
        ->method('findAll')
        ->willThrowException(new \Exception());

    $useCase($presenter);

    expect($presenter->getResponseStatus())
        ->toBeInstanceOf(ErrorResponse::class)
        ->and($presenter->getResponseStatus()->getMessage())
        ->toBe('Error while searching for host categories');
});

it('should present an ForbiddenResponse when a non-admin user has unsufficient rights', function (): void {
    $useCase = new FindHostCategories(
        $this->hostCategoryRepository,
        $this->accessGroupRepository,
        $this->user
    );
    $presenter = new FindHostCategoriesPresenterStub($this->presenterFormatter);

    $this->user
        ->expects($this->once())
        ->method('isAdmin')
        ->willReturn(false);
    $this->user
        ->expects($this->atMost(2))
        ->method('hasTopologyRole')
        ->willReturnMap(
            [
                [Contact::ROLE_CONFIGURATION_HOSTS_CATEGORIES_READ, false],
                [Contact::ROLE_CONFIGURATION_HOSTS_CATEGORIES_READ_WRITE, false],
            ]
        );

    $useCase($presenter);

    expect($presenter->getResponseStatus())
        ->toBeInstanceOf(ForbiddenResponse::class)
        ->and($presenter->getResponseStatus()?->getMessage())
        ->toBe('You are not allowed to access host categories');
});

it('should present a FindHostGroupsResponse when a non-admin user has read only rights', function (): void {
    $useCase = new FindHostCategories(
        $this->hostCategoryRepository,
        $this->accessGroupRepository,
        $this->user
    );
    $presenter = new FindHostCategoriesPresenterStub($this->presenterFormatter);

    $this->user
        ->expects($this->once())
        ->method('isAdmin')
        ->willReturn(false);
    $this->user
        ->expects($this->atMost(2))
        ->method('hasTopologyRole')
        ->willReturnMap(
            [
                [Contact::ROLE_CONFIGURATION_HOSTS_CATEGORIES_READ, true],
                [Contact::ROLE_CONFIGURATION_HOSTS_CATEGORIES_READ_WRITE, false],
            ]
        );
    $this->hostCategoryRepository
        ->expects($this->once())
        ->method('findAllByAccessGroups')
        ->willReturn([$this->hostCategory]);

    $useCase($presenter);

    expect($presenter->response)
        ->toBeInstanceOf(FindHostCategoriesResponse::class)
        ->and($presenter->response->hostCategories[0])
        ->toBe($this->responseArray);
});

it('should present a FindHostGroupsResponse when a non-admin user has read/write rights', function (): void {
    $useCase = new FindHostCategories(
        $this->hostCategoryRepository,
        $this->accessGroupRepository,
        $this->user
    );
    $presenter = new FindHostCategoriesPresenterStub($this->presenterFormatter);

    $this->user
        ->expects($this->once())
        ->method('isAdmin')
        ->willReturn(false);
    $this->user
        ->expects($this->atMost(2))
        ->method('hasTopologyRole')
        ->willReturnMap(
            [
                [Contact::ROLE_CONFIGURATION_HOSTS_CATEGORIES_READ, false],
                [Contact::ROLE_CONFIGURATION_HOSTS_CATEGORIES_READ_WRITE, true],
            ]
        );
    $this->hostCategoryRepository
        ->expects($this->once())
        ->method('findAllByAccessGroups')
        ->willReturn([$this->hostCategory]);

    $useCase($presenter);

    expect($presenter->response)
        ->toBeInstanceOf(FindHostCategoriesResponse::class)
        ->and($presenter->response->hostCategories[0])
        ->toBe($this->responseArray);
});


it('should present a FindHostCategoriesResponse with admin user', function () {
    $useCase = new FindHostCategories(
        $this->hostCategoryRepository,
        $this->accessGroupRepository,
        $this->user
    );
    $presenter = new FindHostCategoriesPresenterStub($this->presenterFormatter);

    $this->user
        ->expects($this->once())
        ->method('isAdmin')
        ->willReturn(true);
    $this->hostCategoryRepository
        ->expects($this->once())
        ->method('findAll')
        ->willReturn([$this->hostCategory]);

    $useCase($presenter);

    expect($presenter->response)
        ->toBeInstanceOf(FindHostCategoriesResponse::class)
        ->and($presenter->response->hostCategories[0])
        ->toBe($this->responseArray);
});
