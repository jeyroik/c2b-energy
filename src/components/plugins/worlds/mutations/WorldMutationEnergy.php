<?php
namespace c2b\components\plugins\worlds\mutations;

use c2b\components\plugins\PluginChoiceAble;
use c2b\interfaces\extensions\worlds\IWorldExtensionEnergy as I;
use c2b\components\worlds\characteristics\WorldCharacteristic;
use c2b\components\worlds\properties\WorldProperty;
use c2b\components\worlds\resources\WorldResource;
use c2b\interfaces\worlds\characteristics\IWorldCharacteristic;
use c2b\interfaces\worlds\IWorld;
use c2b\interfaces\worlds\properties\IWorldProperty;
use c2b\interfaces\worlds\resources\IWorldResource;
use extas\interfaces\parameters\IParameter;

/**
 * Class WorldMutationEnergy
 *
 * Add
 * - char "energy"
 * - prop "energy_saturation"
 *
 * @stage world.mutation
 * @package c2b\components\plugins\worlds\mutations
 * @author jeyroik@gmail.com
 */
class WorldMutationEnergy extends PluginChoiceAble
{
    protected $choice = 10;

    /**
     * @param IWorld $world
     */
    public function __invoke(IWorld &$world)
    {
        $energyChar = $world->getCharacteristic(I::CHAR__INTENSITY);

        if (!$energyChar) {
            $world->addCharacteristic(new WorldCharacteristic([
                IWorldCharacteristic::FIELD__NAME => I::CHAR__INTENSITY,
                IWorldCharacteristic::FIELD__VALUE => 1
            ]));
        }

        $energyProp = $world->getProperty(I::PROPERTY__SATURATION);

        if (!$energyProp) {
            $world->addProperty(new WorldProperty([
                IWorldProperty::FIELD__NAME => I::PROPERTY__SATURATION,
                IWorldProperty::FIELD__PARAMETERS => [
                    [
                        IParameter::FIELD__NAME => I::PROPERTY__SATURATION__RATIO,
                        IParameter::FIELD__VALUE => 1
                    ]
                ]
            ]))->addProperty(new WorldProperty([
                IWorldProperty::FIELD__NAME => I::PROPERTY__PROGRESS,
                IWorldProperty::FIELD__PARAMETERS => [
                    [
                        IParameter::FIELD__NAME => I::PROPERTY__PROGRESS__SATURATION,
                        IParameter::FIELD__VALUE => 1
                    ],
                    [
                        IParameter::FIELD__NAME => I::PROPERTY__PROGRESS__INTENSITY,
                        IParameter::FIELD__VALUE => 1
                    ]
                ]
            ]));
        }

        $energyResource = $world->getResource(I::RESOURCE__NAME);

        if (!$energyResource) {
            $energy = new WorldResource([IWorldResource::FIELD__NAME => I::RESOURCE__NAME]);
            $energy->addCharacteristic(new WorldCharacteristic([
                IWorldCharacteristic::FIELD__NAME => I::RESOURCE__CHAR_VALUE,
                IWorldCharacteristic::FIELD__VALUE => 0
            ]));
            $world->addResource($energy);
        }
    }
}
