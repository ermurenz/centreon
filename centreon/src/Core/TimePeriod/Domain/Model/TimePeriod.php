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

namespace Core\TimePeriod\Domain\Model;

use Assert\AssertionFailedException;
use Centreon\Domain\Common\Assertion\Assertion;

class TimePeriod
{
    public const MAX_ALIAS_LENGTH = 200;
    public const MAX_NAME_LENGTH = 200;

    private string $name;
    private string $alias;
    /**
     * @var list<Template>
     */
    private array $templates = [];
    /**
     * @var list<ExtraTimePeriod>
     */
    private array $extraTimePeriods = [];
    /**
     * @var list<Day>
     */
    private array $days;

    /**
     * @param int $id
     * @param string $name
     * @param string $alias
     *
     * @throws AssertionFailedException
     */
    public function __construct(
        readonly private int $id,
        string $name,
        string $alias,
    ) {
        $this->setName($name);
        $this->setAlias($alias);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return void
     * @throws AssertionFailedException
     */
    public function setAlias(string $alias): void
    {
        $alias = trim($alias);
        Assertion::notEmpty($alias, 'TimePeriod::alias');
        Assertion::maxLength($alias, self::MAX_ALIAS_LENGTH, 'TimePeriod::alias');
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     * @throws AssertionFailedException
     */
    public function setName(string $name): void
    {
        $name = trim($name);
        Assertion::notEmpty($name, 'TimePeriod::name');
        Assertion::maxLength($name, self::MAX_NAME_LENGTH, 'TimePeriod::name');
        $this->name = $name;
    }

    /**
     * @param list<ExtraTimePeriod> $extraTimePeriods
     */
    public function setExtraTimePeriods(array $extraTimePeriods): void
    {
        $this->extraTimePeriods = [];
        foreach ($extraTimePeriods as $extra) {
            $this->addExtraTimePeriod($extra);
        }
    }

    /**
     * @param ExtraTimePeriod $extraTimePeriod
     * @return void
     */
    public function addExtraTimePeriod(ExtraTimePeriod $extraTimePeriod): void
    {
        $this->extraTimePeriods[] = $extraTimePeriod;
    }

    /**
     * @return ExtraTimePeriod[]
     */
    public function getExtraTimePeriods(): array
    {
        return $this->extraTimePeriods;
    }

    /**
     * @return list<Template>
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param list<Template> $templates
     */
    public function setTemplates(array $templates): void
    {
        $this->templates = [];
        foreach ($templates as $template) {
            $this->addTemplate($template);
        }
    }

    /**
     * @param Template $template
     * @return void
     */
    public function addTemplate(Template $template): void
    {
        $this->templates[] = $template;
    }

    /**
     * @param Day $day
     *
     * @return void
     */
    public function addDay(Day $day): void
    {
        $this->days[] = $day;
    }

    /**
     * @return Day[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * @param Day[] $days
     */
    public function setDays(array $days): void
    {
        $this->days = [];
        foreach ($days as $day) {
            $this->addDay($day);
        }
    }
}
