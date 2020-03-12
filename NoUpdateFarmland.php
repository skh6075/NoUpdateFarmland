<?php

/**
 * @name NoUpdateFarmland
 * @author AvasKr
 * @main NoUpdateFarmland\NoUpdateFarmland
 * @api 3.10.0
 * @version 1.0.0
 */

namespace NoUpdateFarmland;

use pocketmine\plugin\PluginBase;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Liquid;
use pocketmine\block\BlockToolType;
use pocketmine\block\Transparent;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Tool;

use pocketmine\entity\Entity;

use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use pocketmine\math\Vector3;
use pocketmine\math\AxisAlignedBB;

use pocketmine\Player;

class NoUpdateFarmland extends PluginBase
{


    public function onLoad (): void
    {
        BlockFactory::registerBlock (new Farmland (), true);
        BlockFactory::registerBlock (new Water (), true);
    }

}

class Farmland extends Transparent
{

    /** @var int */
    protected $id = self::FARMLAND;


    public function __construct (int $meta = 0)
    {
        $this->meta = $meta;
    }

    public function getName (): string
    {
        return "Farmland";
    }

    public function getHardness (): float
    {
        return 0.6;
    }

    public function getToolType (): int
    {
        return BlockToolType::TYPE_SHOVEL;
    }

    public function ticksRandomly (): bool
    {
        return true;
    }

    protected function recalculateBoundingBox (): ?AxisAlignedBB
    {
        return new AxisAlignedBB (
            $this->x,
            $this->y,
            $this->z,
            $this->x + 1,
            $this->y + 0.9375,
            $this->z + 1
        );
    }

    public function getDrops (Item $item): array
    {
        return [
            ItemFactory::get (Item::DIRT, 0, 1)
        ];
    }
}

class Water extends Liquid
{

    /** @var int */
    protected $id = self::FLOWING_WATER;


    public function __construct (int $meta = 0)
    {
        $this->meta = $meta;
    }

    public function getStillForm (): Block
    {
        return BlockFactory::get (Block::STILL_WATER, $this->meta);
    }

    public function getFlowingForm (): Block
    {
        return BlockFactory::get (Block::FLOWING_WATER, $this->meta);
    }

    public function getName (): string
    {
        return "Water";
    }

    public function getBucketFillSound (): int
    {
        return LevelSoundPacket::SOUND_BUCKET_FILL_WATER;
    }

    public function tickRate (): int
    {
        return 1;
    }

    public function getBucketEmptySound (): int
    {
        return LevelSoundEventPacket::SOUND_BUCKET_EMPTY_WATER;
    }

    public function onUpdate ($type): bool
    {
        return false;
    }

    public function onEntityCollide (Entity $entity): void
    {
        $entity->resetFallDistance ();
        if ($entity->fireTicks > 0) {
            $entity->extinguish ();
        }
    }
}
