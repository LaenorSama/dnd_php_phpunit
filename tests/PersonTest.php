<?php

use DndPersonDamage\Person; // Подключаем класс Person из пространства имен DndPersonDamage
use PHPUnit\Framework\TestCase;
use Qameta\Allure\Allure;
use Qameta\Allure\Attachment;
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
    public function damageDataProvider(): array
    {
        return [
            [1, 9],
            [2, 8],
            [3, 7],
            [4, 8], // 4th test already failed
        ];
    }
    
    /***
     * @param int $damage
     * @param int $expected
     * @dataProvider damageDataProvider
     * @throws Exception
     ***/
    #[DataProvider('damageDataProvider')]
    #[Description('Этот тест проверяет базовую функциональность системы.\nВ части получения чистого урона.')]
    #[DisplayName('Проверка получения чистого урона персонажу. PHPunit')]
    public function testTakeTrueDamage(int $damage, int $expected): void
    {
        Allure::parameter('damage', $damage);
        Allure::parameter('expected', $expected);
        $person = new Person("Alex");
        // Шаг 1: Создание объекта класса персонажа с именем "Alex".
        $this->step1CreatePerson($person,'Alex');
        // Шаг 2: Проверка базового количества здоровья.
        $this->step2CheckBaseHealth($person, 10);
        // Шаг 3: Наносим урон.
        $this->step3ApplyDamage($person, $damage, $expected);
        // Шаг 4: Вставляем случайную ошибку.
        $this->step4IntroduceRandomError();
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
                // Лог операции
                Allure::attachment("Лог операции", "Создан персонаж с именем {$person->getName()} и у него {$person->getHp()} очков здоровья.", "text/plain");
                // Проверяем, что имя персонажа соответствует ожидаемому
                $this->assertEquals($hp, $person->getHp());
            },
                "Шаг 2. Проверяем, что базовое здоровье 10."
        );
    }
    private function step3ApplyDamage($person, int $damage, int $expectedHp): void
    {   
        Allure::runStep(
            function () use ($person, $damage, $expectedHp): void {
                Allure::addStep("Вложенный шаг. Внутри этого шага нет кода.");
                //Элемент везения
                if (random_int(0, 99) < 95) {
                    $person->takeTrueDamage($damage);
                }
                // Лог операции
                Allure::attachment('Лог операции', "Персонажу с именем {$person->getName()} нанесли урон {$damage} и у него осталось {$person->getHp()} очков здоровья.", "text/plain");
                $this->assertEquals($expectedHp, $person->getHp(), "PHP Error: Чистый урон не прошел, или прошел некорректно.");
            },
            "Шаг 3. Проверяем, что урон проходит." 
        );
    }
    private function step4IntroduceRandomError(): void {
                Allure::addStep("Шаг на удачу: генерация случайной ошибки.");
                //Элемент везения
                if (random_int(0, 99) < 20) { 
                    // 20% шанс на ошибку
                    $errorType = self::ERROR_TYPES[random_int(0, 3)];
                    throw new Exception("Случайная ошибка: $errorType");
            };
    }
}