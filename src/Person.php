<?php
namespace DndPersonDamage;

class Person
{
    private string $name;
    private int $hp;
    private int $mdef;
    private int $pdef;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hp = 10;
        $this->mdef = 2;
        $this->pdef = 3;
    }

    public function takeTrueDamage(int $count): void
    {
        $this->hp -= $count;
    }

    public function takeMagicDamage(int $count): void
    {
        $this->hp -= $count / $this->mdef;
    }

    public function takeMeleeDamage(int $count): void
    {
        $this->hp -= $count / $this->pdef;
    }

    public function TakeRangedDamage(int $count, int $length): void
    {
        $k = (60 - $length) / 100;
        $this->hp -= $count * $k / $this->pdef;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHp(): int
    {
        return $this->hp;
    }
}
