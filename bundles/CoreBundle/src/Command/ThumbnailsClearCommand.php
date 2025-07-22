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
 * @copyright  Modification Copyright (c) OpenDXP (https://www.opendxp.ch)
 * @license    https://www.gnu.org/licenses/gpl-3.0.html  GNU General Public License version 3 (GPLv3)
 */

namespace OpenDxp\Bundle\CoreBundle\Command;

use OpenDxp\Console\AbstractCommand;
use OpenDxp\Model\Asset;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
#[AsCommand(
    name: 'opendxp:thumbnails:clear',
    description: 'Clear certain image or video thumbnails (temp. files)'
)]
class ThumbnailsClearCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->addOption(
                'type',
                null,
                InputOption::VALUE_REQUIRED,
                'video or image'
            )
            ->addOption(
                'name',
                null,
                InputOption::VALUE_REQUIRED,
                'name of the thumbnail config of which the temp. files should be cleared'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $possibleOptions = ['image', 'video'];
        if (!in_array($input->getOption('type'), $possibleOptions)) {
            $this->writeError(sprintf('Input option `type` must be one of %s', implode(',', $possibleOptions)));

            return 1;
        }

        if (!$input->getOption('name')) {
            $this->writeError('Input option `name` is required');

            return 1;
        }

        $configClass = 'OpenDxp\Model\Asset\\' . ucfirst($input->getOption('type')) . '\Thumbnail\Config';
        /** @var Asset\Image\Thumbnail\Config|Asset\Video\Thumbnail\Config|null $thumbConfig */
        $thumbConfig = $configClass::getByName($input->getOption('name'));
        if (!$thumbConfig) {
            $this->writeError(sprintf('Unable to find %s thumbnail config with name: %s. Nevertheless trying to delete remaining files', $input->getOption('type'), $input->getOption('name')));
            $thumbConfig = new $configClass();
            $thumbConfig->setName($input->getOption('name'));
        }

        $thumbConfig->clearTempFiles();

        return 0;
    }
}
