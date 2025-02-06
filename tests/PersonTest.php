<?php

use DndPersonDamage\Person; // Подключаем класс Person из пространства имен DndPersonDamage
use PHPUnit\Framework\TestCase;
use Qameta\Allure\Allure;
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
    #[Allure\Description('Этот тест проверяет базовую функциональность системы.\nВ части получения чистого урона.')]
    #[Allure\Title('Проверка получения чистого урона персонажу. PHPunit')]
    public function testTakeTrueDamage(int $damage, int $expected): void
    {
        // Шаг 1: Создание объекта класса персонажа с именем "Alex".
        $this->step1CreatePerson('Alex');

        // Шаг 2: Проверка базового количества здоровья.
        $this->step2CheckBaseHealth(10);

        // Шаг 3: Наносим урон.
        $this->step3ApplyDamage($damage, $expected);

        // Шаг 4: Вставляем случайную ошибку.
        $this->step4IntroduceRandomError();
    }

    private function step1CreatePerson(string $name): void
    {
        $person = new Person($name);
        $this->assertEquals($name, $person->getName());

        // Прикрепляем логотип к отчету.
        $this->attachImage('img/logo.jpeg');
    }

    private function step2CheckBaseHealth(int $expectedHp): void
    {
        $this->assertEquals($expectedHp, $person->getHp());

        // Лог операции
        Allure::attachment('Лог операции', "Создан персонаж с именем {$person->getName()} и у него {$person->getHp()} очков здоровья.", AttachmentType::TEXT);
    }

    private function step3ApplyDamage(int $damage, int $expectedHp): void
    {
        $person = new Person("Alex");

        if (random_int(0, 99) < 95) {
            $person->takeTrueDamage($damage);
        }

        $this->assertEquals($expectedHp, $person->getHp(), "Crit Error: Чистый урон не прошел, или прошел некорректно.");
        
        // Лог операции
        Allure::attachment('Лог операции', "Персонажу с именем {$person->getName()} нанесли урон {$damage} и у него осталось {$person->getHp()} очков здоровья.", AttachmentType::TEXT);
    }

    private function step4IntroduceRandomError(): void
    {
        if (random_int(0, 99) < 20) { // 20% шанс на ошибку
            $errorType = self::ERROR_TYPES[random_int(0, 3)];
            throw new Exception("Случайная ошибка: $errorType");
        }
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


    #[Allure\Epic('TestOps')]
    #[Allure\Feature('BackEnd')]
    #[Allure\Story('Server')]
    #[Allure\Description('Этот тест проверяет базовую функциональность системы.\nВ части корректности имени персонажа.')]
    #[Allure\Title('Проверка корректности имени персонажа. PHPunit')]
    public function testNaming(): void
    {
        $this->step1CreatePerson('Alex');
        
        // Проверяем корректность имени
        $this->step2CheckName('Alex');
        
        // Проверяем тип данных в имени
        $this->step3CheckNameType('Alex');
        
        // Шаг 4: Генерация случайной ошибки
        $this->step4IntroduceRandomError();
    }

    private function step2CheckName(string $expectedName): void
    {
        $person = new Person($expectedName);
        $this->assertEquals($expectedName, $person->getName());

        Allure::attachment('Лог операции', "Создан объект {$person}.", AttachmentType::TEXT);
    }

    private function step3CheckNameType(string $expectedName): void
    {
        $person = new Person($expectedName);

        $result = gettype($person->getName());
        $this->assertEquals('string', $result);

        $firstChar = $person->getName()[0];
        $this->assertEquals($expectedName[0], $firstChar);
    }
}