<?php

use DndPersonDamage\Person; // Подключаем класс Person из пространства имен DndPersonDamage
use PHPUnit\Framework\TestCase;
use Qameta\Allure\Allure;
use Qameta\Allure\attachment;
use Qameta\Allure\AttachmentType;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Allure\Title;
use Qameta\Allure\Feature\Feature;
use Qameta\Allure\Feature\FeatureInterface;
use Qameta\Allure\Feature\FeatureTrait;
use Qameta\Allure\Attribute\DisplayName;
use Qameta\Allure\Attribute\Name;
use Qameta\Allure\Attribute\Value;
use Qameta\Allure\Attribute\Label;
use Qameta\Allure\Attribute\Tag;



class PersonTest extends TestCase
{
    private const ERROR_TYPES = ['IndexError', 'ValueError', 'TypeError', 'KeyError'];

    /**
     * @param int $damage
     * @param int $expected
     * @dataProvider damageDataProvider
     * @throws Exception
     */
    #[Description('Этот тест проверяет базовую функциональность системы.\nВ части получения чистого урона.')]
    #[DisplayName('Проверка получения чистого урона персонажу. PHPunit')]
    public function testTakeTrueDamage(int $damage, int $expected): void
    {
        $person = new Person("Alex");
        // Шаг 1: Создание объекта класса персонажа с именем "Alex".
        $this->step1CreatePerson($person,'Alex');
        // Шаг 2: Проверка базового количества здоровья.
        $this->step2CheckBaseHealth($person, 10);

    }

    private function step1CreatePerson(Person $person, string $name): void
    {
        Allure::runStep(
            function () use ($person, $name): void {
                Allure::addStep("Вложенный шаг. Внутри этого шага нет кода.");
                // Проверяем, что базовое здоровье 10
                $this->assertEquals($name, $person->getName());
            },
            "Шаг 1. Проверка что у нас есть объект класса с нужным именем."
        );
    }
    private function step2CheckBaseHealth(Person $person, string $hp): void
    {
        Allure::runStep(
            function () use ($person, $hp): void {
                Allure::addStep("Вложенный шаг. Внутри этого шага нет кода.");
                // Проверяем, что имя персонажа соответствует ожидаемому
                $this->assertEquals($hp, $person->getHp());
            },
            "Шаг 2. Проверяем, что базовое здоровье 10."
        );
    }


    public function damageDataProvider(): array
    {
        return [
            [1, 9],
            [2, 8],
            [3, 7],
            [4, 8], // 4th test already failed
        ];
    }

}