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
            $currentValueChar = $energyResource->getCharacteristic(
                static::RESOURCE__CHAR_VALUE
            );
            $intensityPossibleChar = $world->getCharacteristic(
                static::CHAR__INTENSITY
            );
            $saturationRatioProperty = $world->getProperty(
                static::PROPERTY__SATURATION
            );

            $currentValue       = $currentValueChar->getValue(0);
            $intensityPossible  = $intensityPossibleChar->getValue(1);
            $saturationRatio    = $saturationRatioProperty
                ->getParameter(static::PROPERTY__SATURATION__RATIO)
                ->getValue(1);

            if ($currentValue < $intensityPossible) {
                $currentValue += $saturationRatio <= ($intensityPossible - $currentValue)
                    ? $saturationRatio
                    : $intensityPossible - $currentValue;

                $currentValueChar->setValue($currentValue);
                $energyResource->addCharacteristic($currentValueChar);
                $world->addResource($energyResource);
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
            $currentValueChar = $energyResource->getCharacteristic(
                static::RESOURCE__CHAR_VALUE
            );
            $intensityPossibleChar = $world->getCharacteristic(
                static::CHAR__INTENSITY
            );

            $currentValue       = $currentValueChar->getValue(0);
            $intensityPossible  = $intensityPossibleChar->getValue(1);

            if ($currentValue == $intensityPossible) {
                $progress = $world->getProperty(static::PROPERTY__PROGRESS);
                $intensityPossible += $progress
                    ->getParameter(static::PROPERTY__PROGRESS__INTENSITY)
                    ->getValue(1);
                $intensityPossibleChar->setValue($intensityPossible);
                $world->addCharacteristic($intensityPossibleChar);
            }

            if ($currentValue && (round($intensityPossible/$currentValue) >= 3)) {
                $progress = $world->getProperty(static::PROPERTY__PROGRESS);
                $saturationProperty = $world->getProperty(
                    static::PROPERTY__SATURATION
                );
                $saturationRatio    = $saturationProperty
                    ->getParameter(static::PROPERTY__SATURATION__RATIO);
                $saturationRatio->setValue(
                    $saturationRatio->getValue(1)
                    + $progress->getParameter(static::PROPERTY__PROGRESS__SATURATION)
                        ->getValue(1)
                );
                $saturationProperty->setParameter($saturationRatio->getName(), $saturationRatio);
                $world->addProperty($saturationProperty);
            }
        }
    }
}
