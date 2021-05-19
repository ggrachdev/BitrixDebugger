Что это?
--------------------------  

BitrixDebugger - библиотека для дебага в 1с битрикс, которая позволяет выводить данные в дебаг-панель, логировать в файлы, выводить дебаг-данные на месте вызова соответствующей функции.

Зачем вам это?
--------------------------  

Из проекта в проект я видел картину, что каждый разработчик реализует свою дебаг-функцию, которая в целом делает то же самое - вывод код внутри тега \<pre\>. Согласитесь, было бы удобнее скачать библиотеку и настроить в IDE сниппеты для нее

Способы установки
--------------------------  
1) (Только для сайтов с кодировкой UTF-8 (В будущем будет исправлено) ) Скопируйте папку ggrachdev.debugbar из папки module и положите в /bitrix/modules и выполните стандартную установку и подключите модуль в init.php:
```php
<?php

\Bitrix\Main\Loader::includeModule('ggrachdev.debugbar');
```
Теперь вам будут доступны все нижеописанные функции для дебага данных.

2) Зайдите в Вашу папку php_interface где лежит так же файл init.php и введите команду (либо скачайте архив с данного репозитория):

```console
$ git clone https://github.com/ggrachdev/BitrixDebugger
```
Далее в файле init.php подключите библиотеку
```php
<?php

include 'BitrixDebugger/initializer.php';
```
Как пользоваться?
--------------------------  
Чтобы получить дебаг-данные в дебаг-панели введите в любом месте в коде (данные будут отображены в разных вкладках панели):
```php
<?php

DD()->notice('Моя переменная', 'Моя переменная 2');
DD()->error('Моя переменная', 'Моя переменная 2');
DD()->warning('Моя переменная', 'Моя переменная 2');
DD()->success('Моя переменная', 'Моя переменная 2', 'Моя переменная 3', ['test' => [
    'testSubKey1' => [
        1,2,3
    ],
    'testSubKey2' => [
        1,2,3
    ],
    'testSubKey3' => [
        1,2,3
    ]
]]);

// Можно осуществлять цепочку вызовов
DD()->notice('Моя переменная', 'Моя переменная 2')->error(/*...*/)->notice(/*...*/)->...;

// Можно дебажить без вызова notice явно (просто передав параметры в DD() - идентично notice):
DD('Моя переменная', 'Моя переменная 2')->error(/*...*/)->...;
```

Так же можно логировать дебаг-данные в файлы (Эти данные не будут отображены в дебаг-панели и будут записываться в файлы при хите):
```php
<?php

DD()->noticeLog('Моя переменная', 'Моя переменная 2');
DD()->errorLog('Моя переменная', 'Моя переменная 2');
DD()->warningLog('Моя переменная', 'Моя переменная 2');
DD()->successLog('Моя переменная', 'Моя переменная 2', 'Моя переменная 3');
```

Настройте папку для логирования в файлы следующим образом куда вам удобно:
```php
<?php

DD()->getConfiguratorDebugger()->setLogPath('error', __DIR__ . '/error.log')
    ->setLogPath('warning', __DIR__ . '/warning.log')
    ->setLogPath('success', __DIR__ . '/success.log')
    ->setLogPath('notice', __DIR__ . '/notice.log');
```

Чтобы настроить разделитель между логами в файле как вам удобно сделайте следующее (указано значение по умолчанию):
```php
<?php

DD()->getConfiguratorDebugger()->setLogChunkDelimeter("\n======\n");
```

Если планируете использовать логирование в файлы без сервера (cli), то укажите ваш DOCUMENT_ROOT в папке initializers в файле cli.php

Фильтрация, отсечение элементов
-------------------------- 
```php
<?php
// Будет дебаг только переменной под 0 индексом (1)
DD()->first()->notice([1,2,3,4,5]);

// Будет дебаг только переменной под 0 индексом (1) и после чего 6,7,8
DD()->first()->notice([1,2,3,4,5])->notice([6,7,8]);

// Будет дебаг только переменной под 0 индексом всегда (1) и после чего только 6, так как "заморозили фильтр"
DD()->freezeFilter()->first()->notice([1,2,3,4,5])->notice([6,7,8]);

// Чтобы "разморозить" фильтр нужно:
DD()->unfreezeFilter();

// Фильтр можно сбросить:
DD()->limit(4)->last()->keys(['ALLOW_KEY_1', 'ALLOW_KEY_2'])->resetFilter();

// Будут браться всегда (так как freezeFilter) только первые 2 элемента данных, т.е [1,2] и [6,7]
DD()->freezeFilter()->limit(2)->notice([1,2,3,4,5])->notice([6,7,8]);

DD()->keys(['key1'])->error(['key1' => 123, 'key2' => 456]);

// Доступные фильтры:
DD()->first(); // Взять первый элемент
DD()->last(); // Взять последний элемент
DD()->limit(10); // Брать первые N элементов
DD()->keys(['key1', 'key2'/*...*/]); // Взять только определенные ключи
```

Прочая информация
-------------------------- 
Дебаг-панель видна только пользователям, которые являются администраторами.

Данные в дебаг-панели можно скрывать, раскрывать по ключам массива. Если присутствует переменная-строка и она больше 200 символов, то будет замена на [Очень длинный текст], так же у строк удаляются все html теги, чтобы исключить ошибки в отображении.

Тестировалась скорость формирования страницы с библиотекой и без, при ее наличии скорость дольше примерно на 0,01 сек., что не значительно замедляет проект.

Дебаг-панель:
![Image alt](https://github.com/ggrachdev/BitrixDebugger/raw/master/assets/images/git/example.png)