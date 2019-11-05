<?php
namespace c2b\components\extensions\worlds;

use c2b\interfaces\extensions\worlds\IWorldExtensionEnergy;
use c2b\interfaces\worlds\IWorld;
use extas\components\extensions\Extension;

/**
 * Class WorldExtensionEnergy
 *
 * @package c2b\components\extensions\worlds
 * @author jeyroik@gmail.com
 */
class WorldExtensionEnergy extends Extension implements IWorldExtensionEnergy
{
    /**
     * Насыщение
     *
     * @param IWorld|null $world
     */
    public function saturateEnergy(IWorld &$world = null)
    {
        $energyResource = $world->getResource(static::RESOURCE__NAME);

        if ($energyResource) {
            $currentValue       = $this->getCurrentEnergy($world);
            $intensityPossible  = $this->getIntensityPossible($world);
            $saturationRatio    = $this->getSaturationRatio($world);

            if ($currentValue < $intensityPossible) {
                $this->incCurrentEnergy(
                    $saturationRatio <= ($intensityPossible - $currentValue)
                                ? $saturationRatio
                                : $intensityPossible - $currentValue,
                    $world
                );
            }
        }
    }

    /**
     * @param IWorld|null $world
     */
    public function progressEnergy(IWorld &$world = null)
    {
        $energyResource = $world->getResource(static::RESOURCE__NAME);
        if ($energyResource) {
            $currentValue       = $this->getCurrentEnergy($world);
            $intensityPossible  = $this->getIntensityPossible($world);

            if ($currentValue == $intensityPossible) {
                $progress = $world->getProperty(static::PROPERTY__PROGRESS);
                $this->incIntensityPossible(
                    $progress
                        ->getParameter(static::PROPERTY__PROGRESS__INTENSITY)
                        ->getValue(1),
                    $world
                );
            }

            if ($currentValue && (round($intensityPossible/$currentValue) >= 3)) {
                $progress = $world->getProperty(static::PROPERTY__PROGRESS);
                $this->incSaturationRatio(
                    $progress->getParameter(static::PROPERTY__PROGRESS__SATURATION)
                        ->getValue(1),
                    $world
                );
            }
        }
    }

    /**
     * @param IWorld|null $world
     *
     * @return int
     */
    public function getIntensityPossible(IWorld $world = null): int
    {
        return $world
            ->getCharacteristic(static::CHAR__INTENSITY)
            ->getValue(0);
    }

    /**
     * @param int $increment
     * @param IWorld|null $world
     */
    public function incIntensityPossible(int $increment, IWorld &$world = null)
    {
        $intensity = $world->getCharacteristic(static::CHAR__INTENSITY);
        $intensity->setValue($intensity->getValue(0) + $increment);
        $world->addCharacteristic($intensity);
    }

    /**
     * @param IWorld|null $world
     *
     * @return int
     */
    public function getSaturationRatio(IWorld $world = null): int
    {
        return $world
            ->getProperty(static::PROPERTY__SATURATION)
            ->getParameter(static::PROPERTY__SATURATION__RATIO)
            ->getValue();
    }

    /**
     * @param int $increment
     *
     * @param IWorld|null $world
     */
    public function incSaturationRatio(int $increment, IWorld &$world = null)
    {
        $saturationProperty = $world->getProperty(static::PROPERTY__SATURATION);
        $saturationRatio    = $saturationProperty
            ->getParameter(static::PROPERTY__SATURATION__RATIO);
        $saturationRatio->setValue(
            $saturationRatio->getValue(1)
            + $increment
        );
        $saturationProperty->setParameter($saturationRatio->getName(), $saturationRatio);
        $world->addProperty($saturationProperty);
    }

    /**
     * @param IWorld|null $world
     *
     * @return int
     */
    public function getCurrentEnergy(IWorld $world = null): int
    {
        return $world->getResource(IWorldExtensionEnergy::RESOURCE__NAME)
            ->getCharacteristic(IWorldExtensionEnergy::RESOURCE__CHAR_VALUE)
            ->getValue(0);
    }

    /**
     * @param int $decrement
     *
     * @param IWorld|null $world
     */
    public function decCurrentEnergy(int $decrement, IWorld &$world = null)
    {
        $this->changeCurrentEnergy($decrement, false, $world);
    }

    /**
     * @param int $increment
     *
     * @param IWorld|null $world
     */
    public function incCurrentEnergy(int $increment, IWorld &$world = null)
    {
        $this->changeCurrentEnergy($increment, true, $world);
    }

    /**
     * @param int $ratio
     * @param bool $isInc
     *
     * @param IWorld $world
     */
    protected function changeCurrentEnergy(int $ratio, bool $isInc, IWorld &$world)
    {
        $energy = $world->getResource(IWorldExtensionEnergy::RESOURCE__NAME);
        $energyCur = $energy->getCharacteristic(
            IWorldExtensionEnergy::RESOURCE__CHAR_VALUE
        );

        $isInc
            ? $energyCur->setValue($energyCur->getValue(0) + $ratio)
            : $energyCur->setValue($energyCur->getValue(0) - $ratio);

        $energy->addCharacteristic($energyCur);
        $world->addResource($energy);
    }
}
