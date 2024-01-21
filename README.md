# Пакет для создания документов с переводом

## Содержание
- [Установка](#install)
- [SimpleDocument](#simple-document)
- [LibreTranslator](#libr-translator)
- [Fb2ParallelDocumentGenerator](#fb2-parallel)
- [SimpleFB2](#simple-fb2)

<a id="install"></a>
## Установка

```
composer require nigo/doc-translator
```

<a id="simple-document"></a>
## Класс ``SimpleDocument``

```php
$generator = new SimpleDocument('lang', 'path_to_save');
```

Класс ``Fb2ParallelDocumentGenerator`` принимает два параметра при создании: язык и путь к папке, куда будет сохранятся файл.

### generateByFile()
Метод принимает файл, который нужно перевести, и название файла, которое будет у перевода, и возвращает ``false``, если файл не создался, или размер файла

```php
$generator->generateByFile('file_for_translate', 'filename');
```

### generate()
Метод принимает текст, который нужно перевести, и название файла, которое будет у перевода, и возвращает ``false``, если файл не создался, или размер файла

```php
$generator->generate('text', 'filename');
```

<a id="libr-translator"></a>
## ВСЕ ПРИМЕРЫ РАБОТАЮТ С КЛАССОМ LibreTranslator, КОТОРЫЙ ОСНОВАН НА API ИЗ ЭТОГО [РЕПОЗИТОРИЯ](https://github.com/LibreTranslate/LibreTranslate).

### setNewTranslator()

Класс ``Fb2ParallelDocumentGenerator`` в конструкторе создает переводчик.

```php
$this->translator = new LibreTranslator();
```

Для изменения переводчика можно использовать следующий метод

```php
public function setNewTranslator(TranslatorAbstract $translator): void
{
    $this->translator = $translator;
}
```

В проекте реализован класс ``LibreTranslator``,
который работает на основе API из этого [репозитория](https://github.com/LibreTranslate/LibreTranslate).

Чтобы создать свою реализацию переводчика, нужно создать класс, который будет занаследован от

```php
abstract class TranslatorAbstract
{

}
```

далее нужно будет создать свою реализацию метода ``translate()``.

Иногда API может присылать не то, что мы хотим (LibreTranslator иногда не переводит имена, названия каких-либо мест и т.п. и возвращает ответ, где указывается, что язык выбран неправильно), и для более удобной отладки, ответы,
которые не имеют статус ``200``, могут записываться в файл ``./storage/logs/day_moth_year.txt``,
для этого при создании переводчика нужно передать ``true`` в параметр ``logState``.

```php
new LibreTranslator(true);
```

Изменить состояние для логов можно с помощью метода
```php
public function setLogState(bool $state): void
{
    $this->logState = $state;
}
```

Или из класса ``Fb2ParallelDocumentGenerator``

```php
public function setLogStateForTranslator(bool $state): void
{
    $this->translator = new LibreTranslator($state);
}
```

<a id="fb2-parallel"></a>
## Класс ``Fb2ParallelDocumentGenerator``

Работает аналогично классу ``SimpleDocument``, но создает документ в формате FB2 с параллельным переводом.

```php
$generator = new Fb2ParallelDocumentGenerator('lang', 'path_to_save');
$generator->generateByFile('path_to_file', 'filename');
```

## Перевод
![Текст описания](./doc/images/translated_text.png)


Тестовые тексты для перевода берутся из ``./storage/test_doc/``.\
One_Day-Helen_Naylor.txt - большой текст и text.txt - маленький текст.

<a id="simple-fb2"></a>
## Класс ``SimpleFB2``

Класс создает файл FB2 формата без перевода.

```php
$document = new SimpleFB2('path_to_save');
$document->generateByFile('path_to_file', 'filename');
```