<?php
declare(strict_types=1);

/**
 * OpenDXP
 *
 * This source file is licensed under the GNU General Public License version 3 (GPLv3).
 *
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (https://pimcore.com)
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Bundle\GenericExecutionEngineBundle\Configuration;

use Exception;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @internal
 */
trait ValidateStepConfigurationTrait
{
    protected OptionsResolver $stepConfiguration;

    public function configurationIsValid(array $config): bool
    {
        try {
            $this->resolveStepConfiguration($config);
        } catch (Exception) {
            return false;
        }

        return true;
    }

    protected function configureStep(): void
    {
        // not configured should be configured in the usage.
    }

    /**
     * @throws Exception
     */
    private function resolveStepConfiguration(array $config): array
    {
        return $this->stepConfiguration->resolve($config);
    }
}
