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

namespace Core\ServiceTemplate\Application\Exception;

class ServiceTemplateException extends \Exception
{
    /**
     * @return self
     */
    public static function accessNotAllowed(): self
    {
        return new self(_('You are not allowed to access service templates'));
    }

    /**
     * @param string $serviceTemplateName
     *
     * @return self
     */
    public static function cannotBeDelete(string $serviceTemplateName): self
    {
        return new self(
            sprintf(
                _('The service template \'%s\' is locked and cannot be deleted'),
                $serviceTemplateName
            )
        );
    }

    /**
     * @return self
     */
    public static function deleteNotAllowed(): self
    {
        return new self(_('You are not allowed to delete this service template'));
    }

    /**
     * @param \Throwable $ex
     *
     * @return self
     */
    public static function errorWhileDeleting(\Throwable $ex): self
    {
        return new self(_('Error while deleting the service template'), 0, $ex);
    }

    /**
     * @param \Throwable $ex
     *
     * @return self
     */
    public static function errorWhileSearching(\Throwable $ex): self
    {
        return new self(_('Error while searching for service templates'), 0, $ex);
    }
}