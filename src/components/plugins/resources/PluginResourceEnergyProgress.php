<?php
namespace c2b\components\plugins\resources;

use c2b\interfaces\extensions\worlds\IWorldExtensionEnergy;
use c2b\interfaces\worlds\IWorld;
use c2b\interfaces\worlds\resources\IWorldResource;
use extas\components\plugins\Plugin;

/**
 * Class PluginResourceEnergyProgress
 *
 * @stage world.energy_base.saturation
 * @package c2b\components\plugins\resources
 * @author jeyroik@gmail.com
 */
class PluginResourceEnergyProgress extends Plugin
{
    /**
     * @param IWorld|IWorldExtensionEnergy $world
     * @param IWorldResource $resource
     */
    public function __invoke(IWorld &$world, IWorldResource &$resource)
    {
        $world->progressEnergy();
    }
}
