<?php
namespace c2b\interfaces\extensions\worlds;

/**
 * Interface IWorldExtensionEnergy
 *
 * @package c2b\interfaces\extensions\worlds
 * @author jeyroik@gmail.com
 */
interface IWorldExtensionEnergy
{
    const RESOURCE__NAME = 'energy';
    const RESOURCE__CHAR_VALUE = 'value';

    const CHAR__INTENSITY = 'energy_intensity';

    const PROPERTY__SATURATION = 'energy_saturation';
    const PROPERTY__SATURATION__RATIO = 'saturation.ratio';
    const PROPERTY__PROGRESS = 'energy_progress';
    const PROPERTY__PROGRESS__SATURATION = 'saturation.progress.ratio';
    const PROPERTY__PROGRESS__INTENSITY = 'intensity.progress.ratio';

    /**
     * @return void
     */
    public function saturateEnergy();

    /**
     * @return void
     */
    public function progressEnergy();
}
